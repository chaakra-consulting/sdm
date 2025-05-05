<?php

namespace App\Services;

use App\Models\Absensi;
use App\Models\AbsensiHarian;
use App\Models\BukukasPurchaseInvoice;
use App\Models\BukukasPurchaseInvoiceItems;
use App\Models\Gaji;
use App\Models\GajiBulanan;
use App\Models\GajiBulananSync;
use App\Models\HariLibur;
use App\Models\User;
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

    public static function syncBebanTunjangan($data)
    {
        $users = User::all();
        foreach($users as $user){
            $bebans = config('constants.beban');
            foreach($bebans as $beban){
                $syncBeban = GajiBulananSync::where('user_id',$user->id)->where('tipe',$beban)->where('tahun',$data->year)->where('bulan',$data->month)->first();

                // if($syncBeban){
                    $gajiBulanan = GajiBulanan::where('user_id',$user->id)->whereYear('tanggal_gaji',$data->year)->whereMonth('tanggal_gaji',$data->month)->first();
                    $bpjsKetenagakerjaan = $gajiBulanan && $gajiBulanan->potongan_bpjs_ketenagakerjaan ? $gajiBulanan->potongan_bpjs_ketenagakerjaan : 0;
                    $bpjsKesehatan = $gajiBulanan && $gajiBulanan->potongan_bpjs_kesehatan ? $gajiBulanan->potongan_bpjs_kesehatan : 0;
                    $price = $beban == 'bpjs-ketenagakerjaan' ? $bpjsKetenagakerjaan : $bpjsKesehatan;
                    $namaBeban = $beban == 'bpjs-ketenagakerjaan' ? 'BPJS Ketenagakerjaan' : 'BPJS Kesehatan';
                    $nama = $user->name ? $user->name : '-';

                    $invoice = $syncBeban ? BukukasPurchaseInvoice::where('id',$syncBeban->bukukas_invoice_id)->where('deleted',false)->first() : null;
                    $invoiceItems = $syncBeban ? BukukasPurchaseInvoiceItems::where('id',$syncBeban->bukukas_invoice_item_id)->first() : null;

                    if ($invoice && $invoiceItems) {
                        $invoice = BukukasPurchaseInvoice::where('id',$syncBeban->bukukas_invoice_id)->first();
        
                        $updateSync = collect([
                            'tanggal_sync_terakhir' => Carbon::now(),
                        ]);
                        $syncBeban->update($updateSync->toArray());
        
                        $updateInvoiceItems = collect([
                            'basic_price' => $price,
                            'total' => $price,
                        ]);
                        $invoiceItems->update($updateInvoiceItems->toArray());
        
                    }else{
                        if(!empty($price)){
                            if($syncBeban) $syncBeban->delete();
            
                            $month_text = Carbon::createFromDate($data->year, $data->month, 1)->translatedFormat('F');
            
                            $dataInvoice = [
                                'code' => "514 - Beban Tunjangan",
                                'memo' => "Tunjangan ". $namaBeban. " " .$nama. " Bulan " .$month_text. " Tahun ".$data->year,
                                'status' => 'draft',
                                'paid' => 'Not Paid',
                                'inv_date' => Carbon::create($data->year, $data->month, 28),
                                'created_at' => Carbon::now(),
                                'is_verified' => 0,
                                'fid_order' => 0,
                                'fid_quot' => 0,
                                'fid_vendor' => 0,
                                'fid_cust' => 0,
                                'fid_custt' => 0,
                                'fid_tax' => 0,
                                'sub_total' => 0,
                                'amount' => 0,
                                'ppn' => 0,
                                'residual' => 0,
                                'deleted' => 0,
                                'bukti' => '',
                                'inv_address' => '',
                                'delivery_address' => '',
                                'email_to' => '',
                                'end_date' => Carbon::now(),
                                'currency' => '',
                            ];       
                            $puchaseInvoice = BukukasPurchaseInvoice::create($dataInvoice);
            
                            $dataInvoiceItems = [
                                'fid_invoices' => $puchaseInvoice->id,
                                'title' => "Tunjangan ". $namaBeban. " " .$nama. " Bulan " .$month_text. " Tahun ".$data->year,
                                'description'=> '',
                                "status" => 'terverifikasi',
                                "quantity" => 1,
                                "basic_price" => $price ?? 0,
                                "total" => $price ?? 0,
                                "deleted" => 0,
                                "kode_produk" => '',
                                "unit_type" => '',
                                "rate" => 0,
                            ];       
                            $puchaseInvoiceItems = BukukasPurchaseInvoiceItems::create($dataInvoiceItems);
            
                            $dataSync = [
                                'bukukas_invoice_id'  => $puchaseInvoice->id,
                                'bukukas_invoice_item_id' => $puchaseInvoiceItems->id,
                                'user_id' => $user->id,
                                'tipe' => $beban,
                                'tahun' => $data->year,
                                'bulan' => $data->month,
                                'tanggal_sync_pertama'  => Carbon::now(),
                                'tanggal_sync_terakhir'  => Carbon::now(),
                            ];       
                            GajiBulananSync::create($dataSync);
                        }
                    }
                // }
            }
        }
    }

    public static function syncBebanGaji($data)
    {
        $sync = GajiBulananSync::where('tahun',$data->year)->where('bulan',$data->month)->where('tipe','gaji')->first();

        $gajiBulanan = GajiBulanan::whereYear('tanggal_gaji', $data->year)
        ->whereMonth('tanggal_gaji', $data->month)
        ->get();

        $invoice = $sync ? BukukasPurchaseInvoice::where('id',$sync->bukukas_invoice_id)->where('deleted',false)->first() : null;
        $invoiceItems = $sync ? BukukasPurchaseInvoiceItems::where('id',$sync->bukukas_invoice_item_id)->first() : null;

        $totalPotonganGajiPokok = $gajiBulanan->sum('potongan_gaji_pokok');
        $totalPotonganUangMakan = $gajiBulanan->sum('potongan_uang_makan');
        $totalPotonganKinerja = $gajiBulanan->sum('potongan_kinerja');
        $totalPotonganKeterlambatan = $gajiBulanan->sum('potongan_keterlambatan');
        $totalPotonganPajak = $gajiBulanan->sum('potongan_pajak');
        $totalPotonganKasbon = $gajiBulanan->sum('potongan_kasbon');
        $totalPotonganLainnya = $gajiBulanan->sum('potongan_lainnya');

        $totalInsentifKinerja = $gajiBulanan->sum('insentif_kinerja');
        $totalInsentifUangMakan = $gajiBulanan->sum('insentif_uang_makan');
        $totalInsentifUangBensin = $gajiBulanan->sum('insentif_uang_bensin');
        $totalInsentifPenjualan = $gajiBulanan->sum('insentif_penjualan');
        $totalInsentifLainnya = $gajiBulanan->sum('insentif_lainnya');
        $totalOvertime = $gajiBulanan->sum('overtime');

        $gajiPokokTotal = $gajiBulanan->sum('gaji_pokok');
        
        $potonganTotal = $totalPotonganGajiPokok + $totalPotonganUangMakan + $totalPotonganKinerja + $totalPotonganKeterlambatan + $totalPotonganPajak + $totalPotonganKasbon + $totalPotonganLainnya;
        $insentifTotal = $totalInsentifKinerja + $totalInsentifUangMakan + $totalInsentifUangBensin + $totalInsentifPenjualan + $totalOvertime + $totalInsentifLainnya;
        $gajiTotal = ($gajiPokokTotal + $insentifTotal) - $potonganTotal;

        if ($invoice && $invoiceItems) {
            // $invoice = BukukasPurchaseInvoice::where('id',$sync->bukukas_invoice_id)->first();

            $updateSync = collect([
                'tanggal_sync_terakhir' => Carbon::now(),
            ]);
            $sync->update($updateSync->toArray());

            $updateInvoiceItems = collect([
                'basic_price' => $gajiTotal,
                'total' => $gajiTotal,
            ]);
            $invoiceItems->update($updateInvoiceItems->toArray());

        }else{
            if($sync) $sync->delete();

            $month_text = Carbon::createFromDate($data->year, $data->month, 1)->translatedFormat('F');

            $dataInvoice = [
                'code' => "506 - Gaji",
                'memo' => "Gaji Karyawan Bulan ". $month_text ." Tahun ". $data->year,
                'status' => 'terverifikasi',
                'paid' => 'PAID',
                'inv_date' => Carbon::create($data->year, $data->month, 28),
                'created_at' => Carbon::now(),
                'is_verified' => 1,
                'fid_order' => 0,
                'fid_quot' => 0,
                'fid_vendor' => 0,
                'fid_cust' => 0,
                'fid_custt' => 0,
                'fid_tax' => 0,
                'sub_total' => 0,
                'amount' => 0,
                'ppn' => 0,
                'residual' => 0,
                'deleted' => 0,
                'bukti' => '',
                'inv_address' => '',
                'delivery_address' => '',
                'email_to' => '',
                'end_date' => Carbon::now(),
                'currency' => '',
            ];       
            $puchaseInvoice = BukukasPurchaseInvoice::create($dataInvoice);

            $dataInvoiceItems = [
                'fid_invoices' => $puchaseInvoice->id,
                'title' => "Gaji Karyawan Bulan ". $month_text ." Tahun ". $data->year,
                'description'=> '',
                "status" => 'terverifikasi',
                "quantity" => 1,
                "basic_price" => $gajiTotal,
                "total" => $gajiTotal,
                "deleted" => 0,
                "kode_produk" => '',
                "unit_type" => '',
                "rate" => 0,
            ];       
            $puchaseInvoiceItems = BukukasPurchaseInvoiceItems::create($dataInvoiceItems);

            $dataSync = [
                'bukukas_invoice_id'  => $puchaseInvoice->id,
                'bukukas_invoice_item_id' => $puchaseInvoiceItems->id,
                'tipe' => 'gaji',
                'tahun' => $data->year,
                'bulan' => $data->month,
                'tanggal_sync_pertama'  => Carbon::now(),
                'tanggal_sync_terakhir'  => Carbon::now(),
            ];       
            GajiBulananSync::create($dataSync);
        }
    }
     
}