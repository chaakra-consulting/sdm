<?php

namespace App\Http\Controllers;

use App\Models\DatadiriUser;
use App\Models\Gaji;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GajiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roleSlug = Auth::user()->role->slug;
        $gajis = Gaji::whereHas('pegawai.kepegawaian', function ($query) {
            $query->where('is_active', 1);
        })->get(); 
        
        $gajisNotActive = Gaji::whereHas('pegawai.kepegawaian', function ($query) {
            $query->where('is_active', 0);
        })->get(); 
        
        $pegawais = DatadiriUser::whereNotIn('id', $gajis->pluck('pegawai_id'))->get();
        //$pegawais = DatadiriUser::get();
        
        $data = [
            'title' => 'Data Gaji Karyawan',
            'pegawais' => $pegawais,
            'gajis' => $gajis,
            'gajis_not_active' => $gajisNotActive,
        ];

        // return view('admin.sub_jabatan', $data);
        if($roleSlug == 'admin-sdm'){
            return view('admin_sdm.gaji', $data);
        }else{
            return redirect()->back()->with('error', 'Anda Tidak Memiliki Akses ke Halaman Ini');
        }
    }

    public function store(Request $request)
    {
         try {
             DB::beginTransaction();
 
             $request->validate([
                 'pegawai_id'                => 'required|exists:\App\Models\DatadiriUser,id',
                 'gaji_pokok'                => 'required',
                 'uang_makan'                => 'nullable',
                 'uang_bensin'               => 'nullable',
                 'bpjs_ketenagakerjaan'      => 'nullable',
                 'bpjs_kesehatan'            => 'nullable',
             ]);
             
             $userId = DatadiriUser::where('id',$request->pegawai_id)->value('user_id');

             $data = [
                'user_id'               => $userId,
                'pegawai_id'            => $request->pegawai_id,
                'gaji_pokok'            => $request->gaji_pokok,
                'uang_makan'            => $request->uang_makan,
                'uang_bensin'           => $request->uang_bensin,
                'bpjs_ketenagakerjaan'  => $request->bpjs_ketenagakerjaan,
                'bpjs_kesehatan'        => $request->bpjs_kesehatan
            ];
    
            Gaji::create($data);
             
             DB::commit();
             return redirect()->back()->with('success', 'Data Gaji Berhasil Diubah');
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

             $gaji = Gaji::find($id);

             if (!$gaji) {
                 return redirect()->back()->with('error', 'Data tidak ditemukan.');
             }
 
             $request->validate([
                 'gaji_pokok'                => 'required',
                 'uang_makan'                => 'nullable',
                 'uang_bensin'               => 'nullable',
                 'bpjs_ketenagakerjaan'      => 'nullable',
                 'bpjs_kesehatan'            => 'nullable',
             ]);

             $updateData = collect([
                'gaji_pokok'            => $request->gaji_pokok,
                'uang_makan'            => $request->uang_makan,
                'uang_bensin'           => $request->uang_bensin,
                'bpjs_ketenagakerjaan'  => $request->bpjs_ketenagakerjaan,
                'bpjs_kesehatan'        => $request->bpjs_kesehatan
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
}
