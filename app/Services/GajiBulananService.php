<?php

namespace App\Services;

use App\Models\Absensi;
use App\Models\AbsensiHarian;
use App\Models\Gaji;
use App\Models\GajiBulanan;
use App\Models\HariLibur;
use Carbon\Carbon;
use Illuminate\Support\Str;

class GajiBulananService
{   
    public static function generateGajiBulananPegawai($data)
    {
        $userId = $data['user_id'];
        $pegawaiId = $data['pegawai_id'];
        $tahun = $data['tahun'];
        $bulan = $data['bulan'];
        //$tanggalVerifikasi = $data['tanggal_verifikasi'];
        $hash = (string) Str::ulid();
        $tanggal = Carbon::createFromDate($tahun, $bulan, 1)->format('Y-m-d');
        $startDate = Carbon::createFromDate($tahun, $bulan, 1)->subMonth()->day(26);
        $endDate = Carbon::createFromDate($tahun, $bulan, 1)->day(25);
    
        $gaji = Gaji::where('pegawai_id', $pegawaiId)->first();
    
        if (!$gaji) {
            throw new \Exception("Gaji belum diisi. Silahkan Hubungi Divisi SDM!");
        }

        $gajiPokok = $gaji->gaji_pokok;
        $uangMakan = $gaji->uang_makan;
        $countHariKerja = 0;
        $countKetidakhadiran = 0;
        $sumUangLembur = 0;
        $sumPotonganTerlambat = 0;

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $hari = $date->translatedFormat('l') ?? '-';          
            $absensi = Absensi::where('hari',$hari)->first();
            $dendaTerlambat = $absensi ? $absensi->denda_terlambat : 0;
            $overtime = $absensi ? $absensi->denda_terlambat : 0;
            // $isLibur = $absensi ? $absensi->is_libur : false;
            // $isHariLibur = HariLibur::where('tanggal',$date)->first();

            $isLibur = Absensi::where('hari', $hari)->value('is_libur');
            $isHariLibur = HariLibur::where('tanggal',$date->format('Y-m-d'))->first();
    
            if (!$isLibur && !$isHariLibur) $countHariKerja++;

            $absensiHarian = AbsensiHarian::where('pegawai_id',$pegawaiId)->where('tanggal_kerja', $date->toDateString())->first();           
            $keterangan = $absensiHarian ? $absensiHarian->keteranganAbsensi : null;
            $durasiLembur = $absensiHarian ? $absensiHarian->durasi_lembur : 0;

            $data = json_decode($absensiHarian->data ?? null, true);
            $batasWaktuTerlambat = $data && $data['batas_waktu_terlambat'] ? $data['batas_waktu_terlambat'] : null;
            $waktuMasuk = $data && $data['waktu_masuk'] ? $data['waktu_masuk'] : null;

            //*! untuk menghitung jumlah hari kerja
            // if(!$isLibur && !$isHariLibur) $countHariKerja++;
            
            //*! untuk menghitung jumlah terlambat
            if($keterangan && ($keterangan->slug != 'ijin-direktur')){
                if($batasWaktuTerlambat && $absensiHarian->waktu_masuk && $absensiHarian->waktu_masuk <= $batasWaktuTerlambat && $absensiHarian->waktu_masuk > $waktuMasuk) {
                    $sumPotonganTerlambat = $sumPotonganTerlambat + $dendaTerlambat;
                }elseif($batasWaktuTerlambat && $absensiHarian->waktu_masuk && $absensiHarian->waktu_masuk > $batasWaktuTerlambat) {
                    $sumPotonganTerlambat = $sumPotonganTerlambat + ($dendaTerlambat * 2);
                }
            }

            //*! untuk menghitung jumlah kehadiran
            if((!$absensiHarian && (!$isLibur && !$isHariLibur)) || $keterangan && in_array($keterangan->slug,['alpa'])) $countKetidakhadiran++;
            
            //*! untuk menghitung jumlah lembur
            if($keterangan && in_array($keterangan->slug,['lembur'])) {
                $sumUangLembur = $sumUangLembur + ($durasiLembur * $overtime);
            }
        }

        $gajiHarian = round($gajiPokok / $countHariKerja);
        $sumPotonganGaji = $countKetidakhadiran != 0 ? $gajiHarian * $countKetidakhadiran : null;

        $uangMakanHarian = round($uangMakan / $countHariKerja);
        $sumPotonganUangMakan = $countKetidakhadiran != 0 ? $uangMakanHarian * $countKetidakhadiran : null;

        $dataGajiBulanan = [
            'user_id'                       => $userId,
            'hash'                          => $hash,
            'tanggal_gaji'                  => $tanggal,
            'pegawai_id'                    => $pegawaiId,
            'gaji_pokok'                    => $gajiPokok ?? 0,
            'potongan_gaji_pokok'           => $sumPotonganGaji ?? 0,
            'potongan_uang_makan'           => $sumPotonganUangMakan ?? 0,
            'potongan_kinerja'              => 0,
            'potongan_keterlambatan'        => $sumPotonganTerlambat ?? 0,
            'potongan_pajak'                => 0,
            'potongan_bpjs_ketenagakerjaan' => $gaji->bpjs_ketenagakerjaan ?? 0,
            'potongan_bpjs_kesehatan'       => $gaji->bpjs_kesehatan ?? 0,
            'potongan_kasbon'               => 0,
            'potongan_lainnya'              => 0,
            'insentif_kinerja'              => 0,
            'insentif_uang_makan'           => $uangMakan ?? 0,
            'insentif_uang_bensin'          => $gaji->uang_bensin ?? 0,
            'insentif_penjualan'            => 0,
            'overtime'                      => $sumUangLembur,
            'insentif_lainnya'              => 0,
            'data'                          => $gaji,
        ];   
   
        GajiBulanan::create($dataGajiBulanan);
        // return $gaji;
    } 
     
}