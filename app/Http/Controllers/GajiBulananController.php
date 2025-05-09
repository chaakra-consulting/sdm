<?php

namespace App\Http\Controllers;

use App\Helpers\Functions;
use App\Models\DatadiriUser;
use App\Models\GajiBulanan;
use App\Models\GajiBulananSync;
use App\Services\GajiBulananService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GajiBulananController extends Controller
{
    public function __construct(
        protected GajiBulananService $gajiBulananService,
    ) {
    }
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

        $gajiBulananSync = GajiBulananSync::where('bulan', $month)->where('tahun', $year)->orderBy('updated_at', 'desc')->first();    
        $syncUpdatedAt = $gajiBulananSync ? Carbon::parse($gajiBulananSync->updated_at)->translatedFormat('d M Y H:i:s') : null;
    
        $collect = collect();
        foreach($gajiBulanans as $gajiBulanan){
            $potonganTotal = $gajiBulanan->potongan_gaji_pokok + $gajiBulanan->potongan_uang_makan + $gajiBulanan->potongan_kinerja + $gajiBulanan->potongan_keterlambatan + $gajiBulanan->potongan_pajak + $gajiBulanan->potongan_kasbon + $gajiBulanan->potongan_lainnya;
            $insentifTotal = $gajiBulanan->insentif_kinerja + $gajiBulanan->potongan_bpjs_ketenagakerjaan + $gajiBulanan->potongan_bpjs_kesehatan + $gajiBulanan->insentif_uang_bensin + $gajiBulanan->insentif_penjualan + $gajiBulanan->overtime + $gajiBulanan->insentif_lainnya;
            $gajiPokokUangMakan = $gajiBulanan->gaji_pokok + $gajiBulanan->insentif_uang_makan; 
            $gajiTotal = ($gajiPokokUangMakan + $insentifTotal) - $potonganTotal;

            $collect->push((object)[
                'id'                => $gajiBulanan->id,
                'hash'                => $gajiBulanan->hash,
                'pegawai_id'        => $gajiBulanan->pegawai_id,
                'pegawai_nama'        => $gajiBulanan->pegawai && $gajiBulanan->pegawai->nama_lengkap ?  $gajiBulanan->pegawai->nama_lengkap : '-',
                'tanggal_gaji'      => $gajiBulanan->tanggal_gaji,
                'tanggal_gaji_text' => $gajiBulanan->tanggal_gaji ? Carbon::parse($gajiBulanan->tanggal_gaji)->format('d F Y') : '-',
                'month_text' => $gajiBulanan->tanggal_gaji ? Carbon::parse($gajiBulanan->tanggal_gaji)->format('F') : '-',

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
                
                'gaji_dan_tunjangan' => $gajiBulanan->gaji_pokok + $gajiBulanan->insentif_uang_makan,
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

        $pegawais = DatadiriUser::whereHas('kepegawaian', function ($query) {
            $query->where('is_active', 1);
        })->whereHas('kepegawaian.statusPekerjaan', function ($query) {
            $query->where('slug', 'freelance');
        })->get(); 
        
        $data = [
            'title' => 'Realisasi Gaji Bulanan',
            'pegawais' => $pegawais,
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

    public function indexKaryawan(Request $request)
    {
        $user = Auth::user();
        $authPegawaiId = $user->dataDiri ? $user->dataDiri->id : null;

        $request->validate([
            'year' => 'nullable|integer|in:' . implode(',', range(1900, date('Y'))),
        ]);

        // dd($request->all());

        if (!$request->year) {
            $year = Carbon::now()->format('Y');
        }else{
            $year = $request->year;
        }

        $gajiBulanans = GajiBulanan::where('pegawai_id',$authPegawaiId)
        ->whereYear('tanggal_gaji',$year)
        ->get(); 
    
        $collect = collect();
        foreach($gajiBulanans as $gajiBulanan){
            $potonganTotal = $gajiBulanan->potongan_gaji_pokok + $gajiBulanan->potongan_uang_makan + $gajiBulanan->potongan_kinerja + $gajiBulanan->potongan_keterlambatan + $gajiBulanan->potongan_pajak + $gajiBulanan->potongan_kasbon + $gajiBulanan->potongan_lainnya;
            $insentifTotal = $gajiBulanan->insentif_kinerja + $gajiBulanan->potongan_bpjs_ketenagakerjaan + $gajiBulanan->potongan_bpjs_kesehatan + $gajiBulanan->insentif_uang_bensin + $gajiBulanan->insentif_penjualan + $gajiBulanan->overtime + $gajiBulanan->insentif_lainnya;
            $gajiPokokUangMakan = $gajiBulanan->gaji_pokok + $gajiBulanan->insentif_uang_makan; 
            $gajiTotal = ($gajiPokokUangMakan + $insentifTotal) - $potonganTotal;

            $collect->push((object)[
                'id'                => $gajiBulanan->id,
                'hash'                => $gajiBulanan->hash,
                'pegawai_id'        => $gajiBulanan->pegawai_id,
                'pegawai_nama'        => $gajiBulanan->pegawai && $gajiBulanan->pegawai->nama_lengkap ?  $gajiBulanan->pegawai->nama_lengkap : '-',
                'tanggal_gaji'      => $gajiBulanan->tanggal_gaji,
                'tanggal_gaji_text' => $gajiBulanan->tanggal_gaji ? Carbon::parse($gajiBulanan->tanggal_gaji)->format('d F Y') : '-',
                'month_text' => $gajiBulanan->tanggal_gaji ? Carbon::parse($gajiBulanan->tanggal_gaji)->format('F') : '-',

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
                
                'gaji_dan_tunjangan' => $gajiBulanan->gaji_pokok + $gajiBulanan->insentif_uang_makan,
                'gaji_total' => $gajiTotal,
            ]);
        }
        
        $years = range(2022, now()->year);
        // $months = [
        //     '1' => 'Januari', '2' => 'Februari', '3' => 'Maret',
        //     '4' => 'April', '5' => 'Mei', '6' => 'Juni',
        //     '7' => 'Juli', '8' => 'Agustus', '9' => 'September',
        //     '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
        // ];

        $roleSlug = Auth::user()->role->slug;
        $role = Functions::generateUrlByRoleSlug($roleSlug);

        $data = [
            'title' => 'Realisasi Gaji Bulanan',
            'role'  => $role,
            'year' => $year,
            'years' => $years,
            'gajis' => $collect,
        ];

        return view('karyawan.gaji_bulanan',$data);
    }

    public function store(Request $request)
    {
         try {
             DB::beginTransaction();
 
             $request->validate([
                 'pegawai_id' => 'required|exists:\App\Models\DatadiriUser,id',
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
                 'year' => 'nullable',
                 'month' => 'nullable',
             ]);
             
             $userId = DatadiriUser::where('id',$request->pegawai_id)->value('user_id');
             //$tanggalGaji = Carbon::create($request->year,$request->month,1);
             $tanggalGaji = Carbon::create(2025,4,1);
             $hash = (string) Str::ulid();

             $data = [
                'user_id'                       => $userId,
                'pegawai_id'                    => $request->pegawai_id,
                'hash'                          => $hash,
                'tanggal_gaji'                  => $tanggalGaji,
                'gaji_pokok'                    => $request->gaji_pokok,
                'potongan_gaji_pokok'           => $request->potongan_gaji_pokok ?? 0,
                'potongan_uang_makan'           => $request->potongan_uang_makan?? 0,
                'potongan_kinerja'              => $request->potongan_kinerja?? 0,
                'potongan_keterlambatan'        => $request->potongan_keterlambatan?? 0,
                'potongan_pajak'                => $request->potongan_pajak?? 0,
                'potongan_bpjs_ketenagakerjaan' => $request->potongan_bpjs_ketenagakerjaan?? 0,
                'potongan_bpjs_kesehatan'       => $request->potongan_bpjs_kesehatan?? 0,
                'potongan_kasbon'               => $request->potongan_kasbon?? 0,
                'potongan_lainnya'              => $request->potongan_lainnya?? 0,
                'insentif_kinerja'              => $request->insentif_kinerja?? 0,
                'insentif_uang_makan'           => $request->insentif_uang_makan?? 0,
                'insentif_uang_bensin'          => $request->insentif_uang_bensin?? 0,
                'insentif_penjualan'            => $request->insentif_penjualan?? 0,
                'overtime'                      => $request->overtime?? 0,
                'insentif_lainnya'              => $request->insentif_lainnya?? 0,
                'keterangan_potongan_lainnya'   => $request->keterangan_potongan_lainnya?? null,
            ];
    
            GajiBulanan::create($data);
             
             DB::commit();
             return redirect()->back()->with('success', 'Data Gaji Bulanan Berhasil Ditambah');
         } catch (Exception $e) {
             DB::rollback();
             //return redirect()->back()->withInput()->with('error', 'Gagal Mengubah Data');
             return redirect()->back()->withInput()->with('error', "{$e->getMessage()}");
         }
    }

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
             return redirect()->back()->with('success', 'Data Gaji Bulanan Berhasil Diubah');
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

            // beban tunjangan
            $this->gajiBulananService->syncBebanTunjangan($request);

            // beban gaji
            $this->gajiBulananService->syncBebanGaji($request);
             
             DB::commit();
             return redirect()->back()->with('success', 'Berhasil Sync Data');
         } catch (Exception $e) {
             DB::rollback();
             //return redirect()->back()->withInput()->with('error', 'Gagal Mengubah Data');
             return redirect()->back()->withInput()->with('error', "{$e->getMessage()}");
         }
    }

    // public function sync(Request $request)
    // {
    //      try {
    //         DB::beginTransaction();

    //         $request->validate([
    //             'month' => 'required|in:1,2,3,4,5,6,7,8,9,10,11,12',
    //             'year' => 'required|in:' . implode(',', range(1900, date('Y'))),
    //         ]);
             
    //          DB::commit();
    //          return redirect()->back()->with('success', 'Berhasil Sync Data');
    //      } catch (Exception $e) {
    //          DB::rollback();
    //          //return redirect()->back()->withInput()->with('error', 'Gagal Mengubah Data');
    //          return redirect()->back()->withInput()->with('error', "{$e->getMessage()}");
    //      }
    // }
}
