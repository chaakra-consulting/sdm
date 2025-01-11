<?php

namespace App\Http\Controllers;

use App\Models\DatadiriUser;
use App\Models\DataKesehatan;
use App\Models\DataPelatihan;
use App\Models\PendidikanUser;
use App\Models\PengalamanKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AjaxController extends Controller
{
    //
    public function get_karyawan()
    {
        $data_karyawan = [];

        $getDataDiri = DatadiriUser::all();

        $no = 0;
        foreach($getDataDiri as $row){
            $data_karyawan['no'] = $no;
            $data_karyawan['data_diri'] = $row;

            $getDataKesehatan = DataKesehatan::where('user_id', $row->user_id)->first();
            $getPendidikanTerakhir = PendidikanUser::where('user_id', $row->user_id)->first();
            $getPengalamanKerja = PengalamanKerja::where('user_id', $row->user_id)->get();
            $getPelatihan = DataPelatihan::where('user_id', $row->user_id)->get();

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
            ->where('data_kepegawaians.user_id', $row->user_id)
            ->first();

            $data_karyawan['data_kesehatan'] = $getDataKesehatan;
            $data_karyawan['pendidikan_terakhir'] = $getPendidikanTerakhir;
            $data_karyawan['pengalaman_kerja'] = $getPengalamanKerja;
            $data_karyawan['pelatihan'] = $getPelatihan;
            $data_karyawan['kepegawaian'] = $kepegawaian;
        }

        return response()->json($data_karyawan);
    }
}
