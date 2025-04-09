<?php

namespace App\Services;

use App\DTOs\GraphDTO;
use App\Helpers\Functions;
use App\Models\Absensi;
use App\Models\AbsensiHarian;
use App\Models\DatadiriUser;
use App\Models\DataKepegawaian;
use App\Models\GajiBulanan;
use App\Models\KeteranganAbsensi;
use Carbon\CarbonPeriod;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardService
{   
    public static function widgetAbsensi(GraphDTO $dto)
    {
        $startDate = $dto->startDate->startOfDay();
        $endDate = $dto->endDate->endOfDay();
        $userId = $dto->userId;
        $data = collect();
          
        $kepegawaians = DataKepegawaian::when($userId, function ($query) use ($userId) {
            return $query->where('user_id', $userId);
        })
        ->where('tgl_masuk', '<=', $endDate)
        ->where(function ($query) use ($startDate) {
            $query->where('tgl_berakhir', '>=', $startDate)
                ->orWhereNull('tgl_berakhir');
        })
        ->get();

        $countHariKerjaPegawai = 0;

        foreach ($kepegawaians as $kepegawaian) {
            $tglMasuk = Carbon::parse($kepegawaian->tgl_masuk);
            $tglBerakhir = $kepegawaian->tgl_berakhir ? Carbon::parse($kepegawaian->tgl_berakhir) : null;
        
            $startDatePegawai = $startDate->lt($tglMasuk) ? $tglMasuk : $startDate;
            $endDatePegawai = (!$tglBerakhir || $endDate->lt($tglBerakhir)) ? $endDate : $tglBerakhir;
        
            for ($date = $startDatePegawai->copy(); $date->lte($endDatePegawai); $date->addDay()) {
                $hari = $date->translatedFormat('l'); // Format hari (contoh: "Monday")
                $isLibur = Absensi::where('hari', $hari)->value('is_libur');
        
                if (!$isLibur) $countHariKerjaPegawai++;
            }
        } 
     
        $absensiHarians = AbsensiHarian::when($userId, function ($query) use ($userId) {
            return $query->where('user_id', $userId);
        })
        ->whereBetween('tanggal_kerja', [$startDate, $endDate])->get();
        $countKehadiran = $absensiHarians->filter(function ($item) {
            return $item->keteranganAbsensi && in_array($item->keteranganAbsensi->slug, ['wfo', 'wfh', 'lembur','ijin-direktur']);
        })->count();

        $data->push((object)[
            'nama' => 'PERSENTASE KEHADIRAN',
            'slug' => 'persentase-kehadiran',
            'count'    => $countHariKerjaPegawai ? round(($countKehadiran/$countHariKerjaPegawai) * 100,2) : 0,
            // 'color' => Functions::generateColorForKeteranganAbsensi('hari-kerja'),
        ]);

        $averageSeconds = $absensiHarians->whereNotNull('waktu_masuk')->filter(function ($item) {
            return $item->keteranganAbsensi && in_array($item->keteranganAbsensi->slug, ['wfo', 'lembur']);
        })
        ->map(function ($item) {
            return strtotime($item->waktu_masuk);
        })->avg();
        
        $avgJamMasuk = $averageSeconds ? date('H:i', $averageSeconds) : null;

        $data->push((object)[
            'nama' => 'RATA-RATA JAM MASUK',
            'slug' => 'rata-rata-jam-masuk',
            'count'    => $avgJamMasuk ? $avgJamMasuk : 0,
            // 'color' => Functions::generateColorForKeteranganAbsensi('hari-kerja'),
        ]);

        $totalKeterlambatan = 0;
    
        $countKeterlambatan = $absensiHarians->filter(function ($absensiHarian) use (&$totalKeterlambatan) {
            $data = json_decode($absensiHarian->data ?? null, true);
            $batasWaktuTerlambat = $data['batas_waktu_terlambat'] ?? null;
        
            if (!isset($absensiHarian->keteranganAbsensi) || !in_array($absensiHarian->keteranganAbsensi->slug, ['wfo', 'lembur'])) {
                return false;
            }

            if ($batasWaktuTerlambat && $absensiHarian->waktu_masuk) {
                $batasTimestamp = strtotime($batasWaktuTerlambat);
                $waktuMasukTimestamp = strtotime($absensiHarian->waktu_masuk);
        
                if ($waktuMasukTimestamp > $batasTimestamp) {
                    $selisihMenit = ($waktuMasukTimestamp - $batasTimestamp) / 60;
                    $totalKeterlambatan += $selisihMenit;
                    return true;
                }
            }
            return false;
        })->count();        
    
        $rataRataKeterlambatan = $countKeterlambatan > 0 ? round($totalKeterlambatan / $countKeterlambatan, 2) : 0;

        $data->push((object)[
            'nama'      => 'RATA-RATA KETERLAMBATAN',
            'slug'      => 'rata-rata-keterlambatan',
            'count'     => $countKeterlambatan ? $countKeterlambatan : 0,
            'rata_rata' => $rataRataKeterlambatan,
        ]);

        return $data;
    }

    public static function graphValueAbsensiHarianByKeterangan(GraphDTO $dto)
    {
        $startDate = $dto->startDate->startOfDay();
        $endDate = $dto->endDate->endOfDay();
        $userId = $dto->userId;
    
        // Ambil semua absensi harian berdasarkan user_id & rentang tanggal
        $absensiHarians = AbsensiHarian::when($userId, fn($query) => $query->where('user_id', $userId))
            ->whereBetween('tanggal_kerja', [$startDate, $endDate])
            ->get();
    
        // Ambil semua keterangan absensi
        //$keteranganAbsensis = KeteranganAbsensi::all();
        $keteranganAbsensis = KeteranganAbsensi::orderBy('id','asc')->get();
    
        $countHariKerja = 0;
        for ($date = clone $startDate; $date->lte($endDate); $date->addDay()) {
            $hari = $date->translatedFormat('l') ?? '-';          
            $isLibur = Absensi::where('hari',$hari)->value('is_libur');
            if($isLibur == false) $countHariKerja++;
        } 
        // Buat data
        $data = collect(
            $keteranganAbsensis->map(fn($keterangan) => (object) [
                'nama'  => $keterangan->nama ?? '-',
                'slug'  => $keterangan->slug ?? '-',
                'count' => $absensiHarians->where('keterangan_id', $keterangan->id)->count(),
                'color' => Functions::generateColorForKeteranganAbsensi($keterangan->slug),
            ])
        );
    
        return $data;
    }   

    public static function graphPercentageAbsensiHarianByKeterangan(GraphDTO $dto)
    {
        $startDate = $dto->startDate->startOfDay();
        $endDate = $dto->endDate->endOfDay();
        $userId = $dto->userId;
    
        // Ambil semua absensi harian berdasarkan user_id & rentang tanggal
        $absensiHarians = AbsensiHarian::when($userId, fn($query) => $query->where('user_id', $userId))
            ->whereBetween('tanggal_kerja', [$startDate, $endDate])
            ->get();
    
        // Ambil semua keterangan absensi
        $keteranganAbsensis = KeteranganAbsensi::orderBy('id','asc')->get();
    
        // Ambil daftar hari libur dalam satu query
        $hariLibur = Absensi::where('is_libur', true)->pluck('hari')->toArray();
    
        // Ambil kepegawaian aktif dalam rentang waktu
        $kepegawaians = DataKepegawaian::when($userId, fn($query) => $query->where('user_id', $userId))
            ->where('tgl_masuk', '<=', $endDate)
            ->where(function ($query) use ($startDate) {
                $query->where('tgl_berakhir', '>=', $startDate)->orWhereNull('tgl_berakhir');
            })
            ->get();
    
        // Hitung jumlah hari kerja pegawai
        $countHariKerjaPegawai = $kepegawaians->sum(function ($kepegawaian) use ($startDate, $endDate, $hariLibur) {
            $startPegawai = max($startDate, Carbon::parse($kepegawaian->tgl_masuk));
            $endPegawai = min($endDate, $kepegawaian->tgl_berakhir ? Carbon::parse($kepegawaian->tgl_berakhir) : $endDate);
        
            return collect(CarbonPeriod::create($startPegawai, $endPegawai))
                ->filter(fn($date) => !in_array($date->translatedFormat('l'), $hariLibur))
                ->count();
        });

        $countHariKerjaPegawai = 0;

        foreach ($kepegawaians as $kepegawaian) {
            $tglMasuk = Carbon::parse($kepegawaian->tgl_masuk);
            $tglBerakhir = $kepegawaian->tgl_berakhir ? Carbon::parse($kepegawaian->tgl_berakhir) : null;
        
            $startDatePegawai = $startDate->lt($tglMasuk) ? $tglMasuk : $startDate;
            $endDatePegawai = (!$tglBerakhir || $endDate->lt($tglBerakhir)) ? $endDate : $tglBerakhir;
        
            for ($date = $startDatePegawai->copy(); $date->lte($endDatePegawai); $date->addDay()) {
                $hari = $date->translatedFormat('l'); // Format hari (contoh: "Monday")
                $isLibur = Absensi::where('hari', $hari)->value('is_libur');
        
                if (!$isLibur) $countHariKerjaPegawai++;
            }
        } 

        $data = collect(
            $keteranganAbsensis->map(fn($keterangan) => (object) [
                'nama'  => $keterangan->nama ?? '-',
                'slug'  => $keterangan->slug ?? '-',
                'count' => $countHariKerjaPegawai ? round(($absensiHarians->where('keterangan_id', $keterangan->id)->count() / $countHariKerjaPegawai) * 100, 2) : 0,
                'color' => Functions::generateColorForKeteranganAbsensi($keterangan->slug),
            ])
        );
    
        return $data;
    }   

    public static function graphBarPegawaiByJamMasuk(GraphDTO $dto)
    {
        $startDate = $dto->startDate->startOfDay();
        $endDate = $dto->endDate->endOfDay();
        $userId = $dto->userId;
    
        $pegawais = DatadiriUser::when($userId, function ($query) use ($userId) {
            return $query->where('user_id', $userId);
        })
        ->get();
        $data = collect();
    
        foreach ($pegawais as $pegawai) {
            $absensis = AbsensiHarian::where('pegawai_id', $pegawai->id)
                ->whereBetween('tanggal_kerja', [$startDate, $endDate])
                ->whereHas('keteranganAbsensi', function ($query) {
                    $query->whereIn('slug', ['wfo', 'lembur']);
                })
                ->pluck('waktu_masuk');
    
            if ($absensis->isNotEmpty()) {
                $totalSeconds = $absensis->map(fn($time) => Carbon::parse($time)->secondsSinceMidnight())->sum();
                $averageSeconds = $totalSeconds / $absensis->count();
                $rataJamMasuk = Carbon::createFromTimestamp($averageSeconds)->format('H:i');
                $rataJamMasuk = floor($averageSeconds / 60) / 60; // Konversi ke jam desimal
                //dd($rataJamMasuk);
            } else {
                $rataJamMasuk = 0;
            }
    
            if($rataJamMasuk){
                $data->push((object)[
                    'nama' => ucwords(strtolower($pegawai->nama_lengkap ?? '-')),
                    'count' => $rataJamMasuk,
                    'color' => "rgb(238, 51, 94, 1)",
                ]);
            }
        }
    
        return $data;
    }
    
    public static function graphBarValueKehadiranPerBulan(GraphDTO $dto)
    {
        $startDate = $dto->startDate->startOfDay();
        $endDate = $dto->endDate->endOfDay();
        $userId = $dto->userId;
        $year = max($startDate->year, $endDate->year);
        
        // Ambil semua absensi dalam satu query untuk tahun yang diproses
        $absensiHarians = AbsensiHarian::when($userId, function ($query) use ($userId) {
                return $query->where('user_id', $userId);
            })
            ->whereYear('tanggal_kerja', $year)
            ->get()
            ->groupBy(fn($item) => Carbon::parse($item->tanggal_kerja)->month);
        
        $keteranganAbsensis = KeteranganAbsensi::orderBy('id','asc')->get();
        
        return collect(range(1, 12))->map(function ($month) use ($year, $absensiHarians, $keteranganAbsensis) {
            $date = Carbon::create($year, $month, 1);
            
            $dataKeterangan = $keteranganAbsensis->map(function ($keterangan) use ($absensiHarians, $month) {
                // Pastikan $absensiHarians[$month] tidak null
                $absensiData = $absensiHarians[$month] ?? collect();
    
                return (object) [
                    'nama'  => $keterangan->nama ?? '-',
                    'slug'  => $keterangan->slug ?? '-',
                    'count' => $absensiData->where('keterangan_id', $keterangan->id)->count(),
                    'color' => Functions::generateColorForKeteranganAbsensi($keterangan->slug),
                ];
            });
    
            return (object) [
                'year'  => $year,
                'month' => $month,
                'month_text' => $date->translatedFormat('F'),
                'data' => $dataKeterangan,
            ];
        });
    }    

    public static function graphBarPercentageKehadiranPerBulan(GraphDTO $dto)
    {
        $startDate = $dto->startDate->startOfDay();
        $endDate = $dto->endDate->endOfDay();
        $userId = $dto->userId;
        $year = max($startDate->year, $endDate->year);
        
        // Ambil semua absensi dalam satu query untuk tahun yang diproses
        $absensiHarians = AbsensiHarian::when($userId, fn($query) => $query->where('user_id', $userId))
            ->whereYear('tanggal_kerja', $year)
            ->get()
            ->groupBy(fn($item) => Carbon::parse($item->tanggal_kerja)->month);
    
        // Ambil semua data kepegawaian dalam satu query
        $kepegawaians = DataKepegawaian::when($userId, fn($query) => $query->where('user_id', $userId))
            ->where('tgl_masuk', '<=', Carbon::create($year, 12, 31)->endOfMonth())
            ->where(function ($query) use ($year) {
                $query->where('tgl_berakhir', '>=', Carbon::create($year, 1, 1)->startOfMonth())
                    ->orWhereNull('tgl_berakhir');
            })
            ->get();
    
        // Ambil semua keterangan absensi
        $keteranganAbsensis = KeteranganAbsensi::orderBy('id','asc')->get();
    
        return collect(range(1, 12))->map(function ($month) use ($year, $absensiHarians, $kepegawaians, $keteranganAbsensis) {
            $date = Carbon::create($year, $month, 1);
            $start = $date->copy()->startOfMonth();
            $end = $date->copy()->endOfMonth();
    
            // Hitung hari kerja pegawai dalam bulan ini
            // $countHariKerjaPegawai = 0;
        
            // foreach ($kepegawaians as $kepegawaian) {
                
            //     for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            //         $hari = $date->translatedFormat('l'); // Format hari (contoh: "Monday")
            //         $isLibur = Absensi::where('hari', $hari)->value('is_libur');
                
            //         if (!$isLibur) $countHariKerjaPegawai++;
            //     }
            // } 
            $countHariKerjaPegawai = $kepegawaians->sum(function ($kepegawaian) use ($start, $end) {
                $period = CarbonPeriod::create(
                    max($start, $kepegawaian->tgl_masuk),
                    min($end, $kepegawaian->tgl_berakhir ?? $end)
                );
                return collect($period)->reject(fn($date) => Absensi::where('hari', $date->translatedFormat('l'))->value('is_libur'))->count();
            });

            // Hitung persentase kehadiran untuk setiap keterangan
            $dataKeterangan = $keteranganAbsensis->map(function ($keterangan) use ($absensiHarians, $month, $countHariKerjaPegawai) {
                $count = isset($absensiHarians[$month]) 
                    ? $absensiHarians[$month]->where('keterangan_id', $keterangan->id)->count()
                    : 0;
            
                return (object) [
                    'nama'  => $keterangan->nama ?? '-',
                    'slug'  => $keterangan->slug ?? '-',
                    'count' => $countHariKerjaPegawai ? round(($count / $countHariKerjaPegawai) * 100, 2) : 0,
                    'color' => Functions::generateColorForKeteranganAbsensi($keterangan->slug),
                ];
            });
    
            return (object) [
                'year'  => $year,
                'month' => $month,
                'month_text' => $date->translatedFormat('F'),
                'data' => $dataKeterangan,
            ];
        });
    }
    

    public static function graphBarValueKehadiranPerHari(GraphDTO $dto)
    {
        $startDate = $dto->startDate->startOfDay();
        $endDate = $dto->endDate->endOfDay();
        $month = $dto->month ?? 1;
        $year = max($startDate->year, $endDate->year);
    
        $start = Carbon::create($year, $month, 1)->startOfMonth();
        $end = Carbon::create($year, $month, 1)->endOfMonth();
    
        $keteranganAbsensis = KeteranganAbsensi::orderBy('id','asc')->get();
        
        // Ambil semua absensi dalam rentang tanggal, lalu kelompokkan berdasarkan tanggal_kerja
        $absensiHarians = AbsensiHarian::whereBetween('tanggal_kerja', [$start, $end])
            ->get()
            ->groupBy('tanggal_kerja');
    
        return collect(range(1, $end->day))->map(function ($day) use ($year, $month, $absensiHarians, $keteranganAbsensis) {
            $date = Carbon::create($year, $month, $day);
            $dayText = $date->format('d/m');
    
            $absensiData = $absensiHarians[$date->toDateString()] ?? collect();
    
            $dataKeterangan = $keteranganAbsensis->map(fn($keterangan) => (object) [
                'nama'  => $keterangan->nama ?? '-',
                'slug'  => $keterangan->slug ?? '-',
                'count' => $absensiData->where('keterangan_id', $keterangan->id)->count(),
                'color' => Functions::generateColorForKeteranganAbsensi($keterangan->slug),
            ]);
    
            return (object) [
                'year'  => $year,
                'month' => $month,
                'date'  => $date->toDateString(),
                'day_text' => $dayText,
                'data' => $dataKeterangan,
            ];
        });
    }    
    
    public static function graphBarPercentageKehadiranPerHari(GraphDTO $dto)
    {
        $startDate = $dto->startDate->startOfDay();
        $endDate = $dto->endDate->endOfDay();
        $month = $dto->month ?? 1;
        $year = max($startDate->year, $endDate->year);
    
        $start = Carbon::create($year, $month, 1)->startOfMonth();
        $end = Carbon::create($year, $month, 1)->endOfMonth();
    
        $keteranganAbsensis = KeteranganAbsensi::orderBy('id','asc')->get();
        
        // Ambil semua absensi dalam rentang tanggal, lalu kelompokkan berdasarkan tanggal_kerja
        $absensiHarians = AbsensiHarian::whereBetween('tanggal_kerja', [$start, $end])
            ->get()
            ->groupBy('tanggal_kerja');
    
        // Ambil jumlah pegawai aktif dalam rentang tanggal hanya sekali
        $countKepegawaians = DataKepegawaian::where('tgl_masuk', '<=', $end)
            ->where(function ($query) use ($start) {
                $query->where('tgl_berakhir', '>=', $start)
                    ->orWhereNull('tgl_berakhir');
            })
            ->count();
    
        return collect(range(1, $end->day))->map(function ($day) use ($year, $month, $absensiHarians, $keteranganAbsensis, $countKepegawaians) {
            $date = Carbon::create($year, $month, $day);
            $dayText = $date->format('d/m');
    
            $absensiData = $absensiHarians[$date->toDateString()] ?? collect();
    
            $dataKeterangan = $keteranganAbsensis->map(fn($keterangan) => (object) [
                'nama'  => $keterangan->nama ?? '-',
                'slug'  => $keterangan->slug ?? '-',
                'count' => $countKepegawaians ? round(($absensiData->where('keterangan_id', $keterangan->id)->count() / max($countKepegawaians, 1)) * 100, 2) : 0,
                'color' => Functions::generateColorForKeteranganAbsensi($keterangan->slug),
            ]);
    
            return (object) [
                'year'  => $year,
                'month' => $month,
                'date'  => $date->toDateString(),
                'day_text' => $dayText,
                'data' => $dataKeterangan,
            ];
        });
    } 
    
    public static function widgetGaji(GraphDTO $dto)
    {
        $startDate = $dto->startDate->startOfDay();
        //$endDate = $dto->endDate->endOfDay();
        $userId = $dto->userId;
        $data = collect();
          
        $gajiBulanan = GajiBulanan::when($userId, function ($query) use ($userId) {
            return $query->where('user_id', $userId);
        })
        ->where('tanggal_gaji', '<=', $startDate)
        ->get();

        $potongan = $gajiBulanan->sum('potongan_gaji_pokok') + $gajiBulanan->sum('potongan_uang_makan') + $gajiBulanan->sum('potongan_kinerja') + $gajiBulanan->sum('potongan_keterlambatan') + $gajiBulanan->sum('potongan_pajak') + $gajiBulanan->sum('potongan_bpjs_ketenagakerjaan') + $gajiBulanan->sum('potongan_bpjs_kesehatan') + $gajiBulanan->sum('potongan_kasbon') + $gajiBulanan->sum('potongan_lainnya');
        $pemasukan = $gajiBulanan->sum('gaji_pokok') + $gajiBulanan->sum('insentif_kinerja') + $gajiBulanan->sum('insentif_uang_makan') + $gajiBulanan->sum('insentif_uang_bensin') + $gajiBulanan->sum('insentif_penjualan') + $gajiBulanan->sum('insentif_lainnya') + $gajiBulanan->sum('overtime');
        $totalGaji = $pemasukan - $potongan;
        
        $data->push((object)[
            'nama' => 'Total Gaji Bulan Ini',
            'slug' => 'jumlah-gaji-bulan-ini',
            'count'    => $totalGaji ? $totalGaji : 0,
            // 'color' => Functions::generateColorForKeteranganAbsensi('hari-kerja'),
        ]);

        $data->push((object)[
            'nama' => 'Total Potongan Bulan Ini',
            'slug' => 'total-potongan-bulan-ini',
            'count'    => $potongan ? $potongan : 0,
            // 'color' => Functions::generateColorForKeteranganAbsensi('hari-kerja'),
        ]);

        return $data;
    }
     
    // public static function graphBarPercentageKehadiranPerBulan(GraphDTO $dto)
    // {
    //     $startDate = $dto->startDate;
    //     $endDate = $dto->endDate;
    //     $userId = $dto->userId;
    //             $keteranganAbsensis = KeteranganAbsensi::orderBy('id','asc')->get();
    //     $year = max($startDate->year, $endDate->year);

    //     $dataPerMonth = collect();

    //     // foreach ($arrYear as $year) {
    //         for ($month = 1; $month <= 12; $month++) {
    //             $date = Carbon::create($year, $month, 1);
    //             $monthText = $date->translatedFormat('F');
    //             $start = Carbon::create($year, $month, 1)->startOfMonth();
    //             $end = Carbon::create($year, $month, 1)->endOfMonth();
                
    //             // Jika tanggal ini di luar range $startDate - $endDate, skip
    //             // if ($date->lt($startDate) || $date->gt($endDate)) {
    //             //     continue;
    //             // }
        
    //             $dataKeterangan = collect();
                
    //             $absensiHarians = AbsensiHarian::when($userId, function ($query) use ($userId) {
    //                     return $query->where('user_id', $userId);
    //                 })
    //                 ->whereYear('tanggal_kerja', $year)
    //                 ->whereMonth('tanggal_kerja', $month)
    //                 ->get();
        
    //             // $countHariKerja = 0;
    //             // for ($day = $date->copy()->startOfMonth(); $day->lte($date->copy()->endOfMonth()) && $day->lte($endDate); $day->addDay()) {
    //             //     $hari = $day->translatedFormat('l') ?? '-';
    //             //     $isLibur = Absensi::where('hari', $hari)->value('is_libur');
    //             //     if ($isLibur == false) $countHariKerja++;
    //             // }

    //             $kepegawaians = DataKepegawaian::when($userId, function ($query) use ($userId) {
    //                 return $query->where('user_id', $userId);
    //             })
    //             ->where('tgl_masuk', '<=', $end)
    //             ->where(function ($query) use ($start) {
    //                 $query->where('tgl_berakhir', '>=', $start)
    //                     ->orWhereNull('tgl_berakhir'); // Jika tgl_berakhir NULL, tetap diambil
    //             })
    //             ->get();
        
    //             $countHariKerjaPegawai = 0;
        
    //             foreach ($kepegawaians as $kepegawaian) {
                
    //                 for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
    //                     $hari = $date->translatedFormat('l'); // Format hari (contoh: "Monday")
    //                     $isLibur = Absensi::where('hari', $hari)->value('is_libur');
                
    //                     if (!$isLibur) $countHariKerjaPegawai++;
    //                 }
    //             } 
    //             dd($countHariKerjaPegawai);
    //             foreach ($keteranganAbsensis as $keterangan) {
    //                 $color = Functions::generateColorForKeteranganAbsensi($keterangan->slug);
    //                 $count = $absensiHarians->where('keterangan_id', $keterangan->id)->count();
    //                 $dataKeterangan->push((object)[
    //                     'nama'  => $keterangan->nama ?? '-',
    //                     'slug'  => $keterangan->slug ?? '-',
    //                     'count' => $countHariKerjaPegawai ? round(($count / $countHariKerjaPegawai) * 100, 2) : 0,
    //                     'color' => $color,
    //                 ]);
    //             }
        
    //             $dataPerMonth->push((object)[
    //                 'year'  => $year,
    //                 'month' => $month,
    //                 'month_text' => $monthText,
    //                 'data' => $dataKeterangan,
    //             ]);
    //         }
    //     // }
        
    //     return $dataPerMonth;        
    // }
    
}