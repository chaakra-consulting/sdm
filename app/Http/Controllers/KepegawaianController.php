<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DatadiriUser;
use App\Models\DataKepegawaian;
use App\Models\DataKesehatan;
use App\Models\DataPelatihan;
use App\Models\DataStatusPekerjaan;
use App\Models\PendidikanUser;
use App\Models\PengalamanKerja;
use App\Models\SubJabatan;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class KepegawaianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $getDataDiri = DatadiriUser::all();
        
        $data = [
            'title' => 'Data Kepegawaian',
            'data_diri' => $getDataDiri
        ];

        return view('admin_sdm.kepegawaian', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'user_id' => 'required',
            'sub_jabatan_id' => 'required',
            'status_pekerjaan_id' => 'required',
            'tgl_masuk' => 'required',
            'tgl_berakhir' => 'required',
        ]);

        $data = [
            'user_id' => $request->user_id,
            'sub_jabatan_id' => $request->sub_jabatan_id,
            'status_pekerjaan_id' => $request->status_pekerjaan_id,
            'tgl_masuk' => $request->tgl_masuk,
            'tgl_berakhir' => $request->tgl_berakhir,
            'no_npwp' => $request->no_npwp
        ];

        DataKepegawaian::create($data);
        return redirect()->back()->with('success', 'Data kepegawaian berhasil di update');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $getKaryawan = DatadiriUser::where('id', $id)->first();
        
        $kepegawaian = DB::table('data_kepegawaians')
            ->select(
                'data_kepegawaians.id as id_kepegawaian',
                'data_kepegawaians.*', 
                'sub_jabatans.id', 
                'sub_jabatans.nama_sub_jabatan',
                'data_status_pekerjaans.id',
                'data_status_pekerjaans.nama_status_pekerjaan'
            )
            ->join('sub_jabatans', 'sub_jabatans.id', '=', 'data_kepegawaians.sub_jabatan_id')
            ->join('data_status_pekerjaans', 'data_status_pekerjaans.id', '=' ,'data_kepegawaians.status_pekerjaan_id')
            ->where('data_kepegawaians.user_id', $getKaryawan->user_id)
            ->first();

            $pendidikan = PendidikanUser::where('user_id', $getKaryawan->user_id)->first(); // Ambil data pendidikan pengguna
            $kesehatan = DataKesehatan::where('user_id', $getKaryawan->user_id)->first();
            $pengalaman_kerja = PengalamanKerja::where('user_id', $getKaryawan->user_id)->get();
            $pelatihan = DataPelatihan::where('user_id', $getKaryawan->user_id)->get();
            $sub_jabatan = SubJabatan::all();
            $status_pekerjaan = DataStatusPekerjaan::all();

        $data = [
            'title' => 'Detail Karyawan',
            'karyawan' => $getKaryawan,
            'pendidikan' => $pendidikan,
            'kesehatan' => $kesehatan,
            'pengalaman_kerja' => $pengalaman_kerja,
            'pelatihan' => $pelatihan,
            'kepegawaian' => $kepegawaian,
            'sub_jabatan' => $sub_jabatan,
            'status_pekerjaan' => $status_pekerjaan
        ];

        return view('admin_sdm.detail_kepegawaian', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $getKepegawaian = DataKepegawaian::findOrFail($id);

        $request->validate([
            'sub_jabatan_id' => 'required',
            'status_pekerjaan_id' => 'required',
            'tgl_masuk' => 'required',
            'tgl_berakhir' => 'required',
        ]);

        $data = [
            'sub_jabatan_id' => $request->sub_jabatan_id,
            'status_pekerjaan_id' => $request->status_pekerjaan_id,
            'tgl_masuk' => $request->tgl_masuk,
            'tgl_berakhir' => $request->tgl_berakhir,
            'no_npwp' => $request->no_npwp
        ];

        $getKepegawaian->update($data);
        return redirect()->back()->with('success', 'Data kepegawaian berhasil di update');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
