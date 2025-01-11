<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SubJabatan;
use App\Models\SocialMedia;
use App\Models\DatadiriUser;
use Illuminate\Http\Request;
use App\Models\DataKesehatan;
use App\Models\DataPelatihan;
use App\Models\PendidikanUser;
use App\Models\PengalamanKerja;
use Illuminate\Support\Facades\DB;
use App\Models\DataStatusPekerjaan;

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
            $social_media = SocialMedia::where('user_id', $getKaryawan->user_id)->get();
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
            'status_pekerjaan' => $status_pekerjaan,
            'social_media' => $social_media
        ];

        return view('admin.detail_karyawan', $data);
    }
}
