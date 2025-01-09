<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Models\DatadiriUser;
use App\Models\DataKesehatan;
use App\Models\DataPelatihan;
use App\Models\PendidikanUser;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class DatadiriController extends Controller
{
    public function index()
    {
        $title = 'Data Diri';
        $idUser = Auth::id(); // Mendapatkan ID pengguna yang sedang login
        $datadiri = DatadiriUser::where('user_id', $idUser)->first(); // Ambil data diri pengguna
        $pendidikan = PendidikanUser::where('user_id', $idUser)->first(); // Ambil data pendidikan pengguna
        $kesehatan = DataKesehatan::where('user_id', $idUser)->first();
        return view('karyawan.index', compact('datadiri', 'pendidikan', 'kesehatan' ,'title'));
    }

    public function store(Request $request)
    {
        // Validasi input
        // dd('test');
        $validator = Validator::make($request->all(), [
            'nik' => 'required|numeric|digits:16|unique:tb_datadiris,nik',
            'nama_lengkap' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'alamat_ktp' => 'required|string|max:255',
            'alamat_domisili' => 'nullable|string|max:255',
            'agama' => 'required|string|max:50',
            'jenis_kelamin' => 'required|string|in:Laki-laki,Perempuan',
            'no_hp' => 'required|numeric',
            'foto_user' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        if ($validator->fails()) {
            dd($validator->errors()); // Debug error validasi
        }

        // Simpan foto jika ada
        $fotoPath = null;
        
        if ($request->hasFile('foto_user')) {
            $file = $request->file('foto_user');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $fotoPath = $filename;
        }

        $data = [
            'nik' => $request->nik,
            'nama_lengkap' => $request->nama_lengkap,
            'user_id' => Auth::id(),
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat_ktp' => $request->alamat_ktp,
            'alamat_domisili' => $request->alamat_domisili,
            'agama' => $request->agama,
            'jenis_kelamin' => $request->jenis_kelamin,
            'no_hp' => $request->no_hp,
            'foto_user' => $fotoPath,
            'status_pernikahan' => 'lajang'
        ];

        //dd($data);

        // Simpan data ke database
        DatadiriUser::create($data);

        return redirect()->route('datadiri')->with('success', 'Data diri berhasil ditambahkan!');
    }
    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'nik' => 'required|numeric|digits:16',
            'nama_lengkap' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'alamat_ktp' => 'required|string',
            'alamat_domisili' => 'nullable|string',
            'agama' => 'required|string',
            'jenis_kelamin' => 'required|string',
            'instagram' => 'nullable|string|max:255',
            'linkedin' => 'nullable|string|max:255',
            'no_hp' => 'required|numeric',
            'foto_user' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Cari data diri berdasarkan id
        $datadiri = DatadiriUser::find($id);

        if (!$datadiri) {
            return redirect()->route('datadiri')->with('error', 'Data diri tidak ditemukan.');
        }

        // Perbarui data diri pengguna
        $datadiri->nik = $request->nik;
        $datadiri->nama_lengkap = $request->nama_lengkap;
        $datadiri->tempat_lahir = $request->tempat_lahir;
        $datadiri->tanggal_lahir = $request->tanggal_lahir;
        $datadiri->alamat_ktp = $request->alamat_ktp;
        $datadiri->alamat_domisili = $request->alamat_domisili;
        $datadiri->agama = $request->agama;
        $datadiri->jenis_kelamin = $request->jenis_kelamin;
        // $datadiri->instagram = $request->instagram;
        // $datadiri->linkedin = $request->linkedin;
        $datadiri->no_hp = $request->no_hp;

        // Jika ada foto baru yang diupload
        if ($request->hasFile('foto_user')) {
            // Hapus foto lama jika ada
            if ($datadiri->foto_user) {
                $oldPhotoPath = public_path('uploads/' . $datadiri->foto_user);
                if (file_exists($oldPhotoPath)) {
                    unlink($oldPhotoPath);
                }
            }

            $file = $request->file('foto_user');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $datadiri->foto_user = $filename;
        }

        // Simpan perubahan ke database
        $datadiri->save();

        return redirect()->route('datadiri')->with('success', 'Data diri berhasil diperbarui.');
    }

    public function pendidikanstore(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_sekolah' => 'required|string|max:255',
            'jurusan_sekolah' => 'required|string|max:255',
            'alamat_sekolah' => 'required|string',
            'tahun_mulai' => 'required|numeric|digits:4',
            'tahun_lulus' => 'required|numeric|digits:4',
        ]);

        // Simpan data pendidikan ke database
        PendidikanUser::create([
            'user_id' => Auth::id(),
            'nama_sekolah' => $request->nama_sekolah,
            'jurusan_sekolah' => $request->jurusan_sekolah,
            'alamat_sekolah' => $request->alamat_sekolah,
            'tahun_mulai' => $request->tahun_mulai,
            'tahun_lulus' => $request->tahun_lulus,
        ]);
        return redirect()->route('datadiri')->with('success', 'Data pendidikan berhasil ditambahkan!');
    }

    public function pendidikanupdate(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'nama_sekolah' => 'required|string|max:255',
            'jurusan_sekolah' => 'required|string|max:255',
            'alamat_sekolah' => 'required|string',
            'tahun_mulai' => 'required|numeric|digits:4',
            'tahun_lulus' => 'required|numeric|digits:4',
        ]);

        // Cari data pendidikan berdasarkan id
        $pendidikanUser = PendidikanUser::find($id);

        if (!$pendidikanUser) {
            return redirect()->route('datadiri')->with('error', 'Data pendidikan tidak ditemukan.');
        }

        // Perbarui data pendidikan
        $pendidikanUser->nama_sekolah = $request->nama_sekolah;
        $pendidikanUser->jurusan_sekolah = $request->jurusan_sekolah;
        $pendidikanUser->alamat_sekolah = $request->alamat_sekolah;
        $pendidikanUser->tahun_mulai = $request->tahun_mulai;
        $pendidikanUser->tahun_lulus = $request->tahun_lulus;

        // Simpan perubahan ke database
        $pendidikanUser->save();

        return redirect()->route('datadiri')->with('success', 'Data pendidikan berhasil diperbarui!');
    }
}
