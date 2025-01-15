<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AbsensiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roleSlug = Auth::user()->role->slug;
        $getAbsensi = Absensi::get();
        //dd(Auth::user()->role->slug);
        
        $data = [
            'title' => 'Master Absensi',
            'absensi' => $getAbsensi
        ];

        // return view('admin.sub_jabatan', $data);
        if($roleSlug == 'admin-sdm'){
            return view('admin_sdm.absensi', $data);
        }else{
            return redirect()->back()->with('error', 'Anda Tidak Memiliki Akses ke Halaman Ini');
        }
    }

    /**
    * Store a newly created resource in storage.
    */
   public function update(Request $request, $id)
   {
        try {
            DB::beginTransaction();
            $absensi = Absensi::find($id);

            if (!$absensi) {
                return redirect()->back()->with('error', 'Data tidak ditemukan.');
            }

            $request->validate([
                // 'id'                       => 'required|exists:\App\Models\Absensi,id',
                //'hari'                      => 'nullable|in:senin,selasa,rabu,kamis,jumat,sabtu,minggu',
                'waktu_masuk'               => 'nullable|date_format:H:i',
                'waktu_pulang'              => 'nullable|date_format:H:i',
                'batas_waktu_terlambat'     => 'nullable|date_format:H:i',
                'denda_terlambat'           => 'nullable|string',
                'overtime'                  => 'nullable|numeric',
                //'is_libur'                  => 'nullable|boolean',
            ]);

            if($request->is_libur){
                $isLibur = true;
            }else{
                $isLibur = false;
            }

            $updateData = collect([
                //'hari'                  => $request->hari,
                'waktu_masuk'           => $request->waktu_masuk,
                'waktu_pulang'          => $request->waktu_pulang,
                'batas_waktu_terlambat' => $request->batas_waktu_terlambat,
                'denda_terlambat'       => $request->denda_terlambat,
                'overtime'              => $request->overtime,
                'is_libur'              => $isLibur,
            ]);

            $absensi->update($updateData->toArray());
            
            DB::commit();
            return redirect()->back()->with('success', 'Master Absensi Berhasil Diubah');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal Mengubah Data');
            //return redirect()->back()->with('error', "{$e->getMessage()}");
        }
   }
}
