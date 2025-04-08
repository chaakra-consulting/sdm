<?php

namespace App\Http\Controllers;

use App\Helpers\Functions;
use App\Models\BukukasPurchaseInvoice;
use App\Models\BukukasPurchaseInvoiceItems;
use App\Models\DatadiriUser;
use App\Models\Gaji;
use App\Models\GajiBulanan;
use App\Models\GajiBulananSync;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GajiBulananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate([
            'month' => 'nullable|integer|in:1,2,3,4,5,6,7,8,9,10,11,12',
            'year' => 'nullable|integer|in:' . implode(',', range(1900, date('Y'))),
        ]);

        // dd($request->all());

        if ($request->month && !$request->year) {
            $month = $request->month;
            $year = Carbon::now()->format('Y');
        }elseif (!$request->month && $request->year) {
            $month = Carbon::now()->format('m');
            $year = $request->year;
        }elseif ($request->month && $request->year) {
            $month = $request->month;
            $year = $request->year;
        }else{
            $month = Carbon::now()->format('m');
            $year = Carbon::now()->format('Y');          
        }

        $gajiBulanans = GajiBulanan::whereMonth('tanggal_gaji',$month)
        ->whereYear('tanggal_gaji',$year)
        ->get(); 

        $gajiBulananSync = GajiBulananSync::where('bulan',$month)->where('tahun',$year)->first();
        $syncUpdatedAt = $gajiBulananSync ? Carbon::parse($gajiBulananSync->updated_at)->translatedFormat('d M Y H:i:s') : null;
    
        $collect = collect();
        foreach($gajiBulanans as $gajiBulanan){
            $potonganTotal = $gajiBulanan->potongan_gaji_pokok + $gajiBulanan->potongan_uang_makan + $gajiBulanan->potongan_kinerja + $gajiBulanan->potongan_keterlambatan + $gajiBulanan->potongan_pajak + $gajiBulanan->potongan_bpjs_ketenagakerjaan + $gajiBulanan->potongan_bpjs_kesehatan + $gajiBulanan->potongan_kasbon + $gajiBulanan->potongan_lainnya;
            $insentifTotal = $gajiBulanan->insentif_kinerja + $gajiBulanan->insentif_uang_makan + $gajiBulanan->insentif_uang_bensin + $gajiBulanan->insentif_penjualan + $gajiBulanan->overtime + $gajiBulanan->insentif_lainnya;
            $gajiTotal = ($gajiBulanan->gaji_pokok + $insentifTotal) - $potonganTotal;

            $collect->push((object)[
                'id'                => $gajiBulanan->id,
                'hash'                => $gajiBulanan->hash,
                'pegawai_id'        => $gajiBulanan->pegawai_id,
                'pegawai_nama'        => $gajiBulanan->pegawai && $gajiBulanan->pegawai->nama_lengkap ?  $gajiBulanan->pegawai->nama_lengkap : '-',
                'tanggal_gaji'      => $gajiBulanan->tanggal_gaji,
                'tanggal_gaji_text' => $gajiBulanan->tanggal_gaji ? Carbon::parse($gajiBulanan->tanggal_gaji)->format('d F Y') : '-',

                'potongan_total' => $potonganTotal,
                'potongan_gaji_pokok' => $gajiBulanan->potongan_gaji_pokok,
                'potongan_uang_makan' => $gajiBulanan->potongan_uang_makan,
                'potongan_kinerja' => $gajiBulanan->potongan_kinerja,
                'potongan_keterlambatan' => $gajiBulanan->potongan_keterlambatan,
                'potongan_pajak' => $gajiBulanan->potongan_pajak,
                'potongan_bpjs_ketenagakerjaan' => $gajiBulanan->potongan_bpjs_ketenagakerjaan,
                'potongan_bpjs_kesehatan' => $gajiBulanan->potongan_bpjs_kesehatan,
                'potongan_kasbon' => $gajiBulanan->potongan_kasbon,
                'potongan_lainnya' => $gajiBulanan->potongan_lainnya,
                'keterangan_potongan_lainnya' => $gajiBulanan->keterangan_potongan_lainnya,

                'insentif_total' => $insentifTotal,
                'insentif_kinerja' => $gajiBulanan->insentif_kinerja,
                'insentif_uang_makan' => $gajiBulanan->insentif_uang_makan,
                'insentif_uang_bensin' => $gajiBulanan->insentif_uang_bensin,
                'insentif_penjualan' => $gajiBulanan->insentif_penjualan,
                'insentif_lainnya' => $gajiBulanan->insentif_lainnya,
                'overtime' => $gajiBulanan->overtime,
                'keterangan_insentif_lainnya' => $gajiBulanan->keterangan_insentif_lainnya,

                'gaji_pokok' => $gajiBulanan->gaji_pokok,
                'gaji_total' => $gajiTotal,
            ]);
        }
        
        $month_text = Carbon::createFromDate($year, $month, 1)->translatedFormat('F');
        $years = range(2022, now()->year);
        $months = [
            '1' => 'Januari', '2' => 'Februari', '3' => 'Maret',
            '4' => 'April', '5' => 'Mei', '6' => 'Juni',
            '7' => 'Juli', '8' => 'Agustus', '9' => 'September',
            '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
        ];

        $roleSlug = Auth::user()->role->slug;
        $role = Functions::generateUrlByRoleSlug($roleSlug);
        
        $data = [
            'title' => 'Realisasi Gaji Bulanan',
            'role'  => $role,
            'month' => $month,
            'month_text' => $month_text,
            'year' => $year,
            'months' => $months,
            'years' => $years,
            'sync_id' => $gajiBulananSync ? $gajiBulananSync->id : null,
            'sync_updated_at' => $syncUpdatedAt,
            'gajis' => $collect,
        ];

        return view('admin_sdm.gaji_bulanan',$data);
    }

    // public function store(Request $request)
    // {
    //      try {
    //          DB::beginTransaction();
 
    //          $request->validate([
    //              'pegawai_id'                => 'required|exists:\App\Models\DatadiriUser,id',
    //              'gaji_pokok'                => 'required',
    //              'uang_makan'                => 'nullable',
    //              'uang_bensin'               => 'nullable',
    //              'bpjs_ketenagakerjaan'      => 'nullable',
    //              'bpjs_kesehatan'            => 'nullable',
    //          ]);
             
    //          $userId = DatadiriUser::where('id',$request->pegawai_id)->value('user_id');

    //          $data = [
    //             'user_id'               => $userId,
    //             'pegawai_id'            => $request->pegawai_id,
    //             'gaji_pokok'            => $request->gaji_pokok,
    //             'uang_makan'            => $request->uang_makan,
    //             'uang_bensin'           => $request->uang_bensin,
    //             'bpjs_ketenagakerjaan'  => $request->bpjs_ketenagakerjaan,
    //             'bpjs_kesehatan'        => $request->bpjs_kesehatan
    //         ];
    
    //         Gaji::create($data);
             
    //          DB::commit();
    //          return redirect()->back()->with('success', 'Data Gaji Berhasil Diubah');
    //      } catch (Exception $e) {
    //          DB::rollback();
    //          //return redirect()->back()->withInput()->with('error', 'Gagal Mengubah Data');
    //          return redirect()->back()->withInput()->with('error', "{$e->getMessage()}");
    //      }
    // }

    public function update(Request $request,$id)
    {
         try {
             DB::beginTransaction();

             $gaji = GajiBulanan::find($id);

             if (!$gaji) {
                 return redirect()->back()->with('error', 'Data tidak ditemukan.');
             }
 
             $request->validate([
                 'gaji_pokok' => 'required',
                 'potongan_gaji_pokok' => 'nullable',
                 'potongan_uang_makan' => 'nullable',
                 'potongan_kinerja' => 'nullable',
                 'potongan_keterlambatan' => 'nullable',
                 'potongan_pajak' => 'nullable',
                 'potongan_bpjs_ketenagakerjaan' => 'nullable',
                 'potongan_bpjs_kesehatan' => 'nullable',
                 'potongan_kasbon' => 'nullable',
                 'potongan_lainnya' => 'nullable',
                 'keterangan_potongan_lainnya' => 'nullable',
                 'insentif_kinerja' => 'nullable',
                 'insentif_uang_makan' => 'nullable',
                 'insentif_uang_bensin' => 'nullable',
                 'insentif_penjualan' => 'nullable',
                 'overtime' => 'nullable',
                 'insentif_lainnya' => 'nullable',
             ]);

             $updateData = collect([
                'gaji_pokok'            => $request->gaji_pokok,
                'potongan_gaji_pokok'            => $request->potongan_gaji_pokok ?? 0,
                'potongan_uang_makan'            => $request->potongan_uang_makan?? 0,
                'potongan_kinerja'            => $request->potongan_kinerja?? 0,
                'potongan_keterlambatan'           => $request->potongan_keterlambatan?? 0,
                'potongan_pajak'  => $request->potongan_pajak?? 0,
                'potongan_bpjs_ketenagakerjaan'        => $request->potongan_bpjs_ketenagakerjaan?? 0,
                'potongan_bpjs_kesehatan'      => $request->potongan_bpjs_kesehatan?? 0,
                'potongan_kasbon'            => $request->potongan_kasbon?? 0,
                'potongan_lainnya'            => $request->potongan_lainnya?? 0,
                'insentif_kinerja'            => $request->insentif_kinerja?? 0,
                'insentif_uang_makan'            => $request->insentif_uang_makan?? 0,
                'insentif_uang_bensin'            => $request->insentif_uang_bensin?? 0,
                'insentif_penjualan'            => $request->insentif_penjualan?? 0,
                'overtime'            => $request->overtime?? 0,
                'insentif_lainnya'            => $request->insentif_lainnya?? 0,
                'keterangan_potongan_lainnya'            => $request->keterangan_potongan_lainnya?? null,
            ]);

            $gaji->update($updateData->toArray());
             
             DB::commit();
             return redirect()->back()->with('success', 'Data Gaji Berhasil Diubah');
         } catch (Exception $e) {
             DB::rollback();
             //return redirect()->back()->withInput()->with('error', 'Gagal Mengubah Data');
             return redirect()->back()->withInput()->with('error', "{$e->getMessage()}");
         }
    }

    public function sync(Request $request)
    {
         try {
            DB::beginTransaction();

            $request->validate([
                'month' => 'required|in:1,2,3,4,5,6,7,8,9,10,11,12',
                'year' => 'required|in:' . implode(',', range(1900, date('Y'))),
            ]);

            $sync = GajiBulananSync::where('tahun',$request->year)->where('bulan',$request->month)->first();
            $gajiBulanan = GajiBulanan::whereYear('tanggal_gaji', $request->year)
            ->whereMonth('tanggal_gaji', $request->month)
            ->get();

            $invoice = $sync ? BukukasPurchaseInvoice::where('id',$sync->bukukas_invoice_id)->where('deleted',false)->first() : null;
            $invoiceItems = $sync ? BukukasPurchaseInvoiceItems::where('id',$sync->bukukas_invoice_item_id)->first() : null;

            $totalPotonganGajiPokok = $gajiBulanan->sum('potongan_gaji_pokok');
            $totalPotonganUangMakan = $gajiBulanan->sum('potongan_uang_makan');
            $totalPotonganKinerja = $gajiBulanan->sum('potongan_kinerja');
            $totalPotonganKeterlambatan = $gajiBulanan->sum('potongan_keterlambatan');
            $totalPotonganPajak = $gajiBulanan->sum('potongan_pajak');
            $totalPotonganBPJSKetenagakerjaan = $gajiBulanan->sum('potongan_bpjs_ketenagakerjaan');
            $totalPotonganBPJSKesehatan = $gajiBulanan->sum('potongan_bpjs_kesehatan');
            $totalPotonganKasbon = $gajiBulanan->sum('potongan_kasbon');
            $totalPotonganLainnya = $gajiBulanan->sum('potongan_lainnya');

            $totalInsentifKinerja = $gajiBulanan->sum('insentif_kinerja');
            $totalInsentifUangMakan = $gajiBulanan->sum('insentif_uang_makan');
            $totalInsentifUangBensin = $gajiBulanan->sum('insentif_uang_bensin');
            $totalInsentifPenjualan = $gajiBulanan->sum('insentif_penjualan');
            $totalInsentifLainnya = $gajiBulanan->sum('insentif_lainnya');
            $totalOvertime = $gajiBulanan->sum('overtime');

            $gajiPokokTotal = $gajiBulanan->sum('gaji_pokok');
            
            $potonganTotal = $totalPotonganGajiPokok + $totalPotonganUangMakan + $totalPotonganKinerja + $totalPotonganKeterlambatan + $totalPotonganPajak + $totalPotonganBPJSKetenagakerjaan + $totalPotonganBPJSKesehatan + $totalPotonganKasbon + $totalPotonganLainnya;
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

                $month_text = Carbon::createFromDate($request->year, $request->month, 1)->translatedFormat('F');

                $dataInvoice = [
                    'code' => "506 - Gaji",
                    'memo' => "Gaji Karyawan Bulan ". $month_text ." Tahun ". $request->year,
                    'status' => 'terverifikasi',
                    'paid' => 'PAID',
                    'inv_date' => Carbon::now(),
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
                    'title' => "Gaji Karyawan Bulan ". $month_text ." Tahun ". $request->year,
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
                    'tahun' => $request->year,
                    'bulan' => $request->month,
                    'tanggal_sync_pertama'  => Carbon::now(),
                    'tanggal_sync_terakhir'  => Carbon::now(),
                ];       
                GajiBulananSync::create($dataSync);
            }
             
             DB::commit();
             return redirect()->back()->with('success', 'Berhasil Sync Data');
         } catch (Exception $e) {
             DB::rollback();
             //return redirect()->back()->withInput()->with('error', 'Gagal Mengubah Data');
             return redirect()->back()->withInput()->with('error', "{$e->getMessage()}");
         }
    }
}
