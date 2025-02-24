<?php

namespace App\Services;

use App\DTOs\GraphDTO;
use App\Helpers\Functions;
use App\Models\Absensi;
use App\Models\AbsensiHarian;
use App\Models\DatadiriUser;
use App\Models\DataKepegawaian;
use App\Models\KeteranganAbsensi;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DashboardService
{   
    public static function widgetAbsensi(GraphDTO $dto)
    {
        $startDate = $dto->startDate;
        $endDate = $dto->endDate;
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
            return $item->keteranganAbsensi && in_array($item->keteranganAbsensi->slug, ['wfo', 'wfh', 'lembur']);
        })->count();

        $data->push((object)[
            'nama' => 'PERSENTASE KEHADIRAN',
            'slug' => 'persentase-kehadiran',
            'count'    => $countKehadiran ? round(($countKehadiran/$countHariKerjaPegawai) * 100,2) : 0,
            // 'color' => Functions::generateColorForKeteranganAbsensi('hari-kerja'),
        ]);

        $averageSeconds = $absensiHarians->whereNotNull('waktu_masuk')->map(function ($item) {
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
    
        $countKeterlambatan = $absensiHarians->filter(function ($absensiHarian) use (&$totalKeterlambatan, &$jumlahTerlambat) {
            $data = json_decode($absensiHarian->data ?? null, true);
            $batasWaktuTerlambat = $data['batas_waktu_terlambat'] ?? null;
    
            // Pastikan batas keterlambatan dan waktu masuk tidak null sebelum perbandingan
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
    
        // Hitung rata-rata keterlambatan dalam menit
        $rataRataKeterlambatan = $countKeterlambatan > 0 ? round($totalKeterlambatan / $countKeterlambatan, 2) : 0;
        //dd($countKeterlambatan);

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
        $startDate = $dto->startDate;
        $endDate = $dto->endDate;
        $userId = $dto->userId;
        $data = collect();


        $absensiHarians = AbsensiHarian::when($userId, function ($query) use ($userId) {
            return $query->where('user_id', $userId);
        })
        ->whereBetween('tanggal_kerja', [$startDate, $endDate])->get();
        $keteranganAbsensis = KeteranganAbsensi::all();
        
        $countHariKerja = 0;
        for ($date = clone $startDate; $date->lte($endDate); $date->addDay()) {
            $hari = $date->translatedFormat('l') ?? '-';          
            $isLibur = Absensi::where('hari',$hari)->value('is_libur');
            if($isLibur == false) $countHariKerja++;
        }    

        $data->push((object)[
            'nama' => 'Hari Kerja',
            'slug' => 'hari-kerja',
            'count'    => $countHariKerja ? $countHariKerja : 0,
            'color' => Functions::generateColorForKeteranganAbsensi('hari-kerja'),
        ]);

        foreach($keteranganAbsensis as $keterangan){
            $color = Functions::generateColorForKeteranganAbsensi($keterangan->slug);
            $data->push((object)[
                'nama' => $keterangan->nama ?? '-',
                'slug' => $keterangan->slug ?? '-',
                'count'    => $absensiHarians ? $absensiHarians->where('keterangan_id', $keterangan->id)->count() : 0,
                'color' => $color,
            ]);
        }
        return $data;
    }

    public static function graphPercentageAbsensiHarianByKeterangan(GraphDTO $dto)
    {
        $startDate = $dto->startDate;
        $endDate = $dto->endDate;
        $userId = $dto->userId;
        $data = collect();

        $absensiHarians = AbsensiHarian::when($userId, function ($query) use ($userId) {
            return $query->where('user_id', $userId);
        })
        ->whereBetween('tanggal_kerja', [$startDate, $endDate])->get();
        $keteranganAbsensis = KeteranganAbsensi::all();
        $countHariKerja = 0;
        for ($date = clone $startDate; $date->lte($endDate); $date->addDay()) {
            $hari = $date->translatedFormat('l') ?? '-';          
            $isLibur = Absensi::where('hari',$hari)->value('is_libur');
            if($isLibur == false) $countHariKerja++;
        }
          
        $kepegawaians = DataKepegawaian::when($userId, function ($query) use ($userId) {
            return $query->where('user_id', $userId);
        })
        ->where('tgl_masuk', '<=', $endDate)
        ->where(function ($query) use ($startDate) {
            $query->where('tgl_berakhir', '>=', $startDate)
                ->orWhereNull('tgl_berakhir'); // Jika tgl_berakhir NULL, tetap diambil
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

        $data->push((object)[
            'nama' => 'Hari Kerja',
            'slug' => 'hari-kerja',
            'count'    => $countHariKerja ? ($countHariKerja/$countHariKerja) * 100 : 0,
            'color' => Functions::generateColorForKeteranganAbsensi('hari-kerja'),
        ]);

        foreach($keteranganAbsensis as $keterangan){
            $color = Functions::generateColorForKeteranganAbsensi($keterangan->slug);
            $count = $absensiHarians->where('keterangan_id', $keterangan->id)->count();
            $data->push((object)[
                'nama' => $keterangan->nama ?? '-',
                'slug' => $keterangan->slug ?? '-',
                'count'    => $countHariKerjaPegawai ? round(($count/$countHariKerjaPegawai) * 100, 2)  : 0,
                'color' => $color,
            ]);
        }
        return $data;
    }

    public static function graphBarPegawaiByJamMasuk(GraphDTO $dto)
    {
        $startDate = $dto->startDate;
        $endDate = $dto->endDate;
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
    
            $data->push((object)[
                'nama' => ucwords(strtolower($pegawai->nama_lengkap ?? '-')),
                'count' => $rataJamMasuk, // Sudah dalam format jam desimal
                'color' => "rgb(238, 51, 94, 1)",
            ]);
        }
    
        return $data;
    }
    
    public static function graphBarValueKehadiranPerBulan(GraphDTO $dto)
    {
        $startDate = $dto->startDate;
        $endDate = $dto->endDate;
        $userId = $dto->userId;
        $keteranganAbsensis = KeteranganAbsensi::all();
        $arrYear = range($startDate->year, $endDate->year);
        $dataPerMonth = collect();

        foreach ($arrYear as $year) {
            for ($month = 1; $month <= 12; $month++) {
                $date = Carbon::create($year, $month, 1);
                
                // Jika tanggal ini di luar range $startDate - $endDate, skip
                // if ($date->lt($startDate) || $date->gt($endDate)) {
                //     continue;
                // }
        
                $dataKeterangan = collect();
                
                $absensiHarians = AbsensiHarian::when($userId, function ($query) use ($userId) {
                        return $query->where('user_id', $userId);
                    })
                    ->whereYear('tanggal_kerja', $year)
                    ->whereMonth('tanggal_kerja', $month)
                    ->get();
        
                // $countHariKerja = 0;
                // for ($day = $date->copy()->startOfMonth(); $day->lte($date->copy()->endOfMonth()) && $day->lte($endDate); $day->addDay()) {
                //     $hari = $day->translatedFormat('l') ?? '-';
                //     $isLibur = Absensi::where('hari', $hari)->value('is_libur');
                //     if ($isLibur == false) $countHariKerja++;
                // }
        
                foreach ($keteranganAbsensis as $keterangan) {
                    $color = Functions::generateColorForKeteranganAbsensi($keterangan->slug);
                    $count = $absensiHarians->where('keterangan_id', $keterangan->id)->count();
                    $dataKeterangan->push((object)[
                        'nama'  => $keterangan->nama ?? '-',
                        'slug'  => $keterangan->slug ?? '-',
                        'count' => $count ? $count : 0,
                        'color' => $color,
                    ]);
                }
        
                $dataPerMonth->push((object)[
                    'year'  => $year,
                    'month' => $month,
                    'month_text' => $date->translatedFormat('F'),
                    'data' => $dataKeterangan,
                ]);
            }
        }
        
        return $dataPerMonth;        
    }  
    
    public static function graphBarPercentageKehadiranPerBulan(GraphDTO $dto)
    {
        $startDate = $dto->startDate;
        $endDate = $dto->endDate;
        $userId = $dto->userId;
        $keteranganAbsensis = KeteranganAbsensi::all();
        $year = max($startDate->year, $endDate->year);

        $dataPerMonth = collect();

        // foreach ($arrYear as $year) {
            for ($month = 1; $month <= 12; $month++) {
                $date = Carbon::create($year, $month, 1);
                $monthText = $date->translatedFormat('F');
                $start = Carbon::create($year, $month, 1)->startOfMonth();
                $end = Carbon::create($year, $month, 1)->endOfMonth();
                
                // Jika tanggal ini di luar range $startDate - $endDate, skip
                // if ($date->lt($startDate) || $date->gt($endDate)) {
                //     continue;
                // }
        
                $dataKeterangan = collect();
                
                $absensiHarians = AbsensiHarian::when($userId, function ($query) use ($userId) {
                        return $query->where('user_id', $userId);
                    })
                    ->whereYear('tanggal_kerja', $year)
                    ->whereMonth('tanggal_kerja', $month)
                    ->get();
        
                // $countHariKerja = 0;
                // for ($day = $date->copy()->startOfMonth(); $day->lte($date->copy()->endOfMonth()) && $day->lte($endDate); $day->addDay()) {
                //     $hari = $day->translatedFormat('l') ?? '-';
                //     $isLibur = Absensi::where('hari', $hari)->value('is_libur');
                //     if ($isLibur == false) $countHariKerja++;
                // }

                $kepegawaians = DataKepegawaian::when($userId, function ($query) use ($userId) {
                    return $query->where('user_id', $userId);
                })
                ->where('tgl_masuk', '<=', $end)
                ->where(function ($query) use ($start) {
                    $query->where('tgl_berakhir', '>=', $start)
                        ->orWhereNull('tgl_berakhir'); // Jika tgl_berakhir NULL, tetap diambil
                })
                ->get();
        
                $countHariKerjaPegawai = 0;
        
                foreach ($kepegawaians as $kepegawaian) {
                
                    for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
                        $hari = $date->translatedFormat('l'); // Format hari (contoh: "Monday")
                        $isLibur = Absensi::where('hari', $hari)->value('is_libur');
                
                        if (!$isLibur) $countHariKerjaPegawai++;
                    }
                } 
        
                foreach ($keteranganAbsensis as $keterangan) {
                    $color = Functions::generateColorForKeteranganAbsensi($keterangan->slug);
                    $count = $absensiHarians->where('keterangan_id', $keterangan->id)->count();
                    $dataKeterangan->push((object)[
                        'nama'  => $keterangan->nama ?? '-',
                        'slug'  => $keterangan->slug ?? '-',
                        'count' => $countHariKerjaPegawai ? round(($count / $countHariKerjaPegawai) * 100, 2) : 0,
                        'color' => $color,
                    ]);
                }
        
                $dataPerMonth->push((object)[
                    'year'  => $year,
                    'month' => $month,
                    'month_text' => $monthText,
                    'data' => $dataKeterangan,
                ]);
            }
        // }
        
        return $dataPerMonth;        
    } 

    public static function graphBarValueKehadiranPerHari(GraphDTO $dto)
    {
        $startDate = $dto->startDate;
        $endDate = $dto->endDate;
        $month = $dto->month ?? 1;
        $keteranganAbsensis = KeteranganAbsensi::all();
        
        $year = max($startDate->year, $endDate->year);
        
        $start = Carbon::create($year, $month, 1)->startOfMonth(); // Mulai dari bulan pertama tahun ini
        $end = Carbon::create($year, $month, 1)->endOfMonth(); // Sampai akhir dari endDate
    
        $dataPerDay = collect();
    
        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            // $dayText = $date->translatedFormat('l, d F Y');
            $dayText = $date->format('d/m');

            $absensiHarians = AbsensiHarian::whereDate('tanggal_kerja', $date->toDateString())->get();
    
            $dataKeterangan = collect();
            
            foreach ($keteranganAbsensis as $keterangan) {
                $color = Functions::generateColorForKeteranganAbsensi($keterangan->slug);
                $count = $absensiHarians->where('keterangan_id', $keterangan->id)->count();
                $dataKeterangan->push((object)[
                    'nama'  => $keterangan->nama ?? '-',
                    'slug'  => $keterangan->slug ?? '-',
                    'count' => $count ? $count : 0,
                    'color' => $color,
                ]);
            }
    
            $dataPerDay->push((object)[
                'year'  => $year,
                'month' => $month,
                'date'  => $date->toDateString(),
                'day_text' => $dayText,
                'data' => $dataKeterangan,
            ]);
        }
        return $dataPerDay;
    }
    
    public static function graphBarPercentageKehadiranPerHari(GraphDTO $dto)
    {
        $startDate = $dto->startDate;
        $endDate = $dto->endDate;
        $month = $dto->month ?? 1;
        $keteranganAbsensis = KeteranganAbsensi::all();
        
        $year = max($startDate->year, $endDate->year);
        
        $start = Carbon::create($year, $month, 1)->startOfMonth(); // Mulai dari bulan pertama tahun ini
        $end = Carbon::create($year, $month, 1)->endOfMonth(); // Sampai akhir dari endDate
    
        $dataPerDay = collect();
    
        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            // $dayText = $date->translatedFormat('l, d F Y');
            $dayText = $date->format('d/m');

            $absensiHarians = AbsensiHarian::whereDate('tanggal_kerja', $date->toDateString())->get();

            $countKepegawaians = DataKepegawaian::where('tgl_masuk', '<=', $end)
                ->where(function ($query) use ($start) {
                    $query->where('tgl_berakhir', '>=', $start)
                        ->orWhereNull('tgl_berakhir');
                })
                ->count();
    
            $dataKeterangan = collect();
            
            foreach ($keteranganAbsensis as $keterangan) {
                $color = Functions::generateColorForKeteranganAbsensi($keterangan->slug);
                $count = $absensiHarians->where('keterangan_id', $keterangan->id)->count();
                $dataKeterangan->push((object)[
                    'nama'  => $keterangan->nama ?? '-',
                    'slug'  => $keterangan->slug ?? '-',
                    'count' => $countKepegawaians ? round(($count / max($countKepegawaians, 1)) * 100, 2) : 0,
                    'color' => $color,
                ]);
            }
    
            $dataPerDay->push((object)[
                'year'  => $year,
                'month' => $month,
                'date'  => $date->toDateString(),
                'day_text' => $dayText,
                'data' => $dataKeterangan,
            ]);
        }
        return $dataPerDay;
    }
     
}