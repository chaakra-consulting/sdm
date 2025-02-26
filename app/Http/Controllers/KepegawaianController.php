<?php

namespace App\Http\Controllers;

use App\Helpers\Functions;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Divisi;
use App\Models\SubJabatan;
use App\Models\SocialMedia;
use App\Models\DatadiriUser;
use Illuminate\Http\Request;
use App\Models\DataKesehatan;
use App\Models\DataPelatihan;
use App\Models\PendidikanUser;
use App\Models\DataKepegawaian;
use App\Models\PengalamanKerja;
use Illuminate\Support\Facades\DB;
use App\Models\DataStatusPekerjaan;
use Illuminate\Support\Facades\Auth;

class KepegawaianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $data_diri = DatadiriUser::all();
        $roleSlug = Auth::user()->role->slug;
        $role = Functions::generateUrlByRoleSlug($roleSlug);
        $getKepegawaian = DataKepegawaian::with('subJabatan', 'statusPekerjaan', 'divisi')->get();
        $title = 'Data Kepegawaian';

        return view('admin_sdm.kepegawaian', compact('data_diri', 'title', 'getKepegawaian','role'));
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
        $request->validate([
            'user_id' => 'required',
            'sub_jabatan_id' => 'required',
            'status_pekerjaan_id' => 'required',
            'divisi_id' => 'required',
            'tgl_masuk' => 'required',
            'tgl_berakhir' => 'required',
        ]);

        // dd($request->all());
        
        $tanggalMasuk = Carbon::parse($request->tgl_masuk);
        $kodeTahunMasuk = $tanggalMasuk->format('Y');
        $kodeDivisi = str_pad($request->divisi_id, 2, '0', STR_PAD_LEFT); 
        $kodePegawai = str_pad($request->sub_jabatan_id, 2, '0', STR_PAD_LEFT); 
        
        $kodeNIP =  $kodeTahunMasuk .".". $kodeDivisi . "." . $kodePegawai;
        
        $lastNIP = DataKepegawaian::where('sub_jabatan_id', $request->sub_jabatan_id)->orderBy('created_at', 'desc')->first();
        
        if (!$lastNIP) {
            $lastKode = $kodeNIP . "." . '01';
            $kode = $lastKode;
        } else {
            $lastKode = substr($lastNIP->nip, -2) + 1;
            // $lastKode = '0' . $lastKode;
            $lastKode = str_pad(((int)$lastKode), 2, '0', STR_PAD_LEFT);
            $kode = $kodeNIP . "." . $lastKode;
        }

        // dd($kode);

        $data = [
            'user_id' => $request->user_id,
            'sub_jabatan_id' => $request->sub_jabatan_id,
            'status_pekerjaan_id' => $request->status_pekerjaan_id,
            'divisi_id' => $request->divisi_id,
            'tgl_masuk' => $request->tgl_masuk,
            'tgl_berakhir' => $request->tgl_berakhir,
            'no_npwp' => $request->no_npwp,
            'nip' => $kode
        ];

        // dd($data);
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
                'data_status_pekerjaans.nama_status_pekerjaan',
                'divisis.id',
                'divisis.nama_divisi',
            )
            ->join('sub_jabatans', 'sub_jabatans.id', '=', 'data_kepegawaians.sub_jabatan_id')
            ->join('data_status_pekerjaans', 'data_status_pekerjaans.id', '=', 'data_kepegawaians.status_pekerjaan_id')
            ->join('divisis', 'divisis.id', '=', 'data_kepegawaians.divisi_id')
            ->where('data_kepegawaians.user_id', $getKaryawan->user_id)
            ->first();
            
        $pendidikan = PendidikanUser::where('user_id', $getKaryawan->user_id)->first(); // Ambil data pendidikan pengguna
        $kesehatan = DataKesehatan::where('user_id', $getKaryawan->user_id)->first();
        $pengalaman_kerja = PengalamanKerja::where('user_id', $getKaryawan->user_id)->get();
        $pelatihan = DataPelatihan::where('user_id', $getKaryawan->user_id)->get();
        $social_media = SocialMedia::where('user_id', $getKaryawan->user_id)->get();
        $sub_jabatan = SubJabatan::all();
        $status_pekerjaan = DataStatusPekerjaan::all();
        $divisi = Divisi::all();
        $roleSlug = Auth::user()->role->slug;
        $role = Functions::generateUrlByRoleSlug($roleSlug);
        
        $data = [
            'title' => 'Detail Karyawan',
            'role' => $role,
            'karyawan' => $getKaryawan,
            'pendidikan' => $pendidikan,
            'kesehatan' => $kesehatan,
            'pengalaman_kerja' => $pengalaman_kerja,
            'pelatihan' => $pelatihan,
            'kepegawaian' => $kepegawaian,
            'sub_jabatan' => $sub_jabatan,
            'status_pekerjaan' => $status_pekerjaan,
            'divisi' => $divisi,
            'social_media' => $social_media
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

        // dd($request->all());
        
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
