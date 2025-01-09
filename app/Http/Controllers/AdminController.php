<?php

namespace App\Http\Controllers;

use App\Models\DatadiriUser;
use App\Models\DataKesehatan;
use App\Models\DataPelatihan;
use App\Models\PendidikanUser;
use App\Models\PengalamanKerja;
use App\Models\SubJabatan;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    //

    public function dashboard()
    {
        $data = [
            'title' => 'Dashboard'
        ];

        return view('admin.dashboard', $data);
    }

    public function data_karyawan()
    {
        $getDataDiri = DatadiriUser::all();

        $data = [
            'title' => 'Data Karyawan',
            'data_diri' => $getDataDiri
        ];

        return view('admin.data_karyawan', $data);
    }

    public function detail_karyawan($id)
    {
        $getKaryawan = DatadiriUser::where('id', $id)->first();
        $getUser = User::where('id', $getKaryawan->user_id)->first();

        $getJabatan = null;
        if($getUser->sub_jabatan_id != null){
            $getJabatan = SubJabatan::where('id', $getUser->sub_jabatan_id)->first();
        }else{
            $getJabatan = null;
        }
        $pendidikan = PendidikanUser::where('user_id', $getKaryawan->user_id)->first(); // Ambil data pendidikan pengguna
        $kesehatan = DataKesehatan::where('user_id', $getKaryawan->user_id)->first();
        $pengalaman_kerja = PengalamanKerja::where('user_id', $getKaryawan->user_id)->get();
        $pelatihan = DataPelatihan::where('user_id', $getKaryawan->user_id)->get();

        $data = [
            'title' => 'Detail Karyawan',
            'karyawan' => $getKaryawan,
            'pendidikan' => $pendidikan,
            'kesehatan' => $kesehatan,
            'pengalaman_kerja' => $pengalaman_kerja,
            'pelatihan' => $pelatihan,
            'jabatan' => $getJabatan
        ];

        return view('admin.detail_karyawan', $data);
    }
}
