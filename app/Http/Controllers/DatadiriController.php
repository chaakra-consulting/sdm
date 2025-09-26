<?php

namespace App\Http\Controllers;

use App\Models\User;

use App\Models\DatadiriUser;
use App\Models\DataKepegawaian;
use Illuminate\Http\Request;
use App\Models\DataKesehatan;
use App\Models\DataPelatihan;
use App\Models\PendidikanUser;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
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
        $kepegawaian = DataKepegawaian::where('user_id', $idUser)->first();
        return view('karyawan.index', compact('datadiri', 'pendidikan', 'kesehatan', 'title', 'kepegawaian'));
    }

    public function store(Request $request)
    {
        // Validasi input
        // dd('test');
        $request->validate([
            'nik' => 'required|numeric|digits:16|unique:tb_datadiris,nik',
            'nama_lengkap' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'alamat_ktp' => 'required|string|max:255',
            'email_nonchaakra' => 'required',
            'alamat_domisili' => 'nullable|string|max:255',
            'agama' => 'required|string|max:50',
            'jenis_kelamin' => 'required|string|in:Laki-laki,Perempuan',
            'no_hp' => 'required|numeric',
            'hubungan_emergency' => 'nullable|string|max:255',
            'nama_emergency' => 'nullable|string|max:255',
            'no_emergency' => ['required', 'numeric', Rule::notIn([$request->no_hp])],
            'foto_user' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'foto_ktp' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'no_emergency.not_in' => 'Nomor HP dan Nomor Emergency tidak boleh sama!',
        ]);

        // Simpan foto jika ada
        $fotoPath = null;
        $fotoPath2 = null;

        if ($request->hasFile('foto_user')) {
            $file = $request->file('foto_user');
            $filename = uniqid() . '_user_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $fotoPath = $filename;
        }

        if ($request->hasFile('foto_ktp')) {
            $file = $request->file('foto_ktp');
            $filename = uniqid() . '_ktp_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $fotoPath2 = $filename;
        }

        // Validasi jika kedua foto diunggah dan hash-nya sama
        if ($fotoPath && $fotoPath2) {
            $fotoUserHash = md5_file(public_path('uploads/' . $fotoPath));
            $fotoKtpHash = md5_file(public_path('uploads/' . $fotoPath2));

            if ($fotoUserHash == $fotoKtpHash) {
                return redirect()->back()->with('error', 'Foto KTP dan Foto User tidak boleh sama!');
            }
        }

        $data = [
            'nik' => $request->nik,
            'nama_lengkap' => $request->nama_lengkap,
            'user_id' => Auth::id(),
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat_ktp' => $request->alamat_ktp,
            'email_nonchaakra' => $request->email_nonchaakra,
            'alamat_domisili' => $request->alamat_domisili,
            'agama' => $request->agama,
            'jenis_kelamin' => $request->jenis_kelamin,
            'no_hp' => $request->no_hp,
            'hubungan_emergency' => $request->hubungan_emergency,
            'nama_emergency' => $request->nama_emergency,
            'no_emergency' => $request->no_emergency,
            'foto_user' => $fotoPath,
            'foto_ktp' => $fotoPath2,
            'status_pernikahan' => $request->status_pernikahan
        ];

        // dd($data);

        // Simpan data ke database
        DatadiriUser::create($data);

        if (Auth::check() && Auth::user()->role->slug == 'admin-sdm') {
            return redirect()->route('admin_sdm.datadiri')->with('success', 'Data diri berhasil ditambahkan!');
        } elseif (Auth::check() && Auth::user()->role->slug == 'karyawan') {
            return redirect()->route('karyawan.datadiri')->with('success', 'Data diri berhasil ditambahkan!');
        } elseif (Auth::check() && Auth::user()->role->slug == 'manager') {
            return redirect()->route('manajer.datadiri')->with('success', 'Data diri berhasil ditambahkan!');
        }
    }
    
    public function indexSDM()
    {
        $title = 'Data Diri';

        $datadiri = $pendidikan = $kesehatan = $kepegawaian = null;
    
        return view('admin_sdm.form-datadiri', compact('datadiri', 'pendidikan', 'kesehatan', 'title', 'kepegawaian'));
    }    

    public function storeFromSDM(Request $request)
    {
        // Validasi input
        // dd('test');
        $request->validate([
            'nik' => 'nullable|numeric|digits:16|unique:tb_datadiris,nik',
            'nama_lengkap' => 'nullable|string|max:255',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'alamat_ktp' => 'nullable|string|max:255',
            'email_nonchaakra' => 'nullable',
            'alamat_domisili' => 'nullable|string|max:255',
            'agama' => 'nullable|string|max:50',
            'jenis_kelamin' => 'nullable|string|in:Laki-laki,Perempuan',
            'no_hp' => 'nullable|numeric',
            'hubungan_emergency' => 'nullable|string|max:255',
            'nama_emergency' => 'nullable|string|max:255',
            'no_emergency' => ['nullable', 'numeric', Rule::notIn([$request->no_hp])],
            'foto_user' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'foto_ktp' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

            'nama_sekolah' => 'nullable|string|max:255',
            'jurusan_sekolah' => 'nullable|string|max:255',
            'alamat_sekolah' => 'nullable|string',
            'tahun_mulai' => 'nullable|numeric|digits:4',
            'tahun_lulus' => 'nullable|numeric|digits:4',
        ], [
            'no_emergency.not_in' => 'Nomor HP dan Nomor Emergency tidak boleh sama!',
        ]);
     
        $user = User::create([
            'name' => $request->nama_lengkap,
            'email' => Carbon::now()->format('YmdHis') . '@chaakraconsulting.com',
            'password' => bcrypt('chaakra123'),
            'role_id' => 3,
        ]);

        // Simpan foto jika ada
        $fotoPath = null;
        $fotoPath2 = null;

        if ($request->hasFile('foto_user')) {
            $file = $request->file('foto_user');
            $filename = uniqid() . '_user_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $fotoPath = $filename;
        }

        if ($request->hasFile('foto_ktp')) {
            $file = $request->file('foto_ktp');
            $filename = uniqid() . '_ktp_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $fotoPath2 = $filename;
        }

        // Validasi jika kedua foto diunggah dan hash-nya sama
        if ($fotoPath && $fotoPath2) {
            $fotoUserHash = md5_file(public_path('uploads/' . $fotoPath));
            $fotoKtpHash = md5_file(public_path('uploads/' . $fotoPath2));

            if ($fotoUserHash == $fotoKtpHash) {
                return redirect()->back()->with('error', 'Foto KTP dan Foto User tidak boleh sama!');
            }
        }

        $data = [
            'nik' => $request->nik,
            'nama_lengkap' => $request->nama_lengkap,
            'user_id' => $user->id,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat_ktp' => $request->alamat_ktp,
            'email_nonchaakra' => $request->email_nonchaakra,
            'alamat_domisili' => $request->alamat_domisili,
            'agama' => $request->agama,
            'jenis_kelamin' => $request->jenis_kelamin,
            'no_hp' => $request->no_hp,
            'hubungan_emergency' => $request->hubungan_emergency,
            'nama_emergency' => $request->nama_emergency,
            'no_emergency' => $request->no_emergency,
            'foto_user' => $fotoPath,
            'foto_ktp' => $fotoPath2,
            'status_pernikahan' => $request->status_pernikahan
        ];

        DatadiriUser::create($data);

        PendidikanUser::create([
            'user_id' => $user->id,
            'nama_sekolah' => $request->nama_sekolah,
            'jurusan_sekolah' => $request->jurusan_sekolah,
            'alamat_sekolah' => $request->alamat_sekolah,
            'tahun_mulai' => $request->tahun_mulai,
            'tahun_lulus' => $request->tahun_lulus,
        ]);

        if (Auth::check() && Auth::user()->role->slug == 'admin-sdm') {
            return redirect()->route('admin_sdm.kepegawaian')->with('success', 'Data diri berhasil ditambahkan!');
        }
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        // dd($request->hasFile('foto_ktp'));
        $request->validate([
            'nik' => ['required', 'numeric', 'digits:16', Rule::unique('tb_datadiris', 'nik')->ignore($request->id),],
            'nama_lengkap' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'alamat_ktp' => 'required|string|max:255',
            'email_nonchaakra' => 'required',
            'alamat_domisili' => 'nullable|string|max:255',
            'agama' => 'required|string|max:50',
            'jenis_kelamin' => 'required|string|in:Laki-laki,Perempuan',
            'no_hp' => 'required|numeric',
            'hubungan_emergency' => 'nullable|string|max:255',
            'nama_emergency' => 'nullable|string|max:255',
            'no_emergency' => ['required', 'numeric', Rule::notIn([$request->no_hp])],
            'foto_user' => 'nullable|image|max:2048',
            'foto_ktp' => 'nullable|image|max:2048',
        ], [
            'no_emergency.not_in' => 'Nomor HP dan Nomor Emergency tidak boleh sama!',
        ]);

        // dd($request->all());
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
        $datadiri->email_nonchaakra = $request->email_nonchaakra;
        $datadiri->alamat_domisili = $request->alamat_domisili;
        $datadiri->agama = $request->agama;
        $datadiri->jenis_kelamin = $request->jenis_kelamin;
        $datadiri->no_hp = $request->no_hp;
        $datadiri->hubungan_emergency = $request->hubungan_emergency;
        $datadiri->nama_emergency = $request->nama_emergency;
        $datadiri->no_emergency = $request->no_emergency;
        $datadiri->status_pernikahan = $request->status_pernikahan;

        // dd($datadiri);

        $datadiri->fill($request->except(['foto_user', 'foto_ktp']));

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
            $filename = uniqid() . '_user_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $datadiri->foto_user = $filename;
        }

        if ($request->hasFile('foto_ktp')) {
            // Hapus foto lama jika ada
            if ($datadiri->foto_ktp) {
                $oldPhotoPath = public_path('uploads/' . $datadiri->foto_ktp);
                if (file_exists($oldPhotoPath)) {
                    unlink($oldPhotoPath);
                }
            }
            $file = $request->file('foto_ktp');
            $filename = uniqid() . '_ktp_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $datadiri->foto_ktp = $filename;
        }

        // if ($datadiri->foto_user && $datadiri->foto_ktp) {
        //     $fotoUserHash = md5_file(public_path('uploads/' . $datadiri->foto_user));
        //     $fotoKtpHash = md5_file(public_path('uploads/' . $datadiri->foto_ktp));
        //     // dd($fotoUserHash, $fotoKtpHash);

        //     if ($fotoUserHash == $fotoKtpHash) {
        //         return redirect()->back()->with('error', 'Foto KTP dan Foto User tidak boleh sama!');
        //     }

        // }

        // Simpan perubahan ke database
        $datadiri->update();

        if($request->jenis_page == 'page_detail_kepegawaian'){
            return redirect()->back()->with('success', 'Data diri berhasil di Update!');
        }else{    
            if (Auth::check() && Auth::user()->role->slug == 'admin-sdm') {
                return redirect()->route('admin_sdm.datadiri')->with('success', 'Data diri berhasil di Update!');
            } elseif (Auth::check() && Auth::user()->role->slug == 'karyawan') {
                return redirect()->route('karyawan.datadiri')->with('success', 'Data diri berhasil di Update!');
            } elseif (Auth::check() && Auth::user()->role->slug == 'manager') {
                return redirect()->route('manajer.datadiri')->with('success', 'Data diri berhasil di Update!');
            }
        }
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
            'user_id' => $request->user_id ? $request->user_id : Auth::id(),
            'nama_sekolah' => $request->nama_sekolah,
            'jurusan_sekolah' => $request->jurusan_sekolah,
            'alamat_sekolah' => $request->alamat_sekolah,
            'tahun_mulai' => $request->tahun_mulai,
            'tahun_lulus' => $request->tahun_lulus,
        ]);

        if($request->jenis_page == 'page_detail_kepegawaian'){
            return redirect()->back()->with('success', 'Data Pendidikan berhasil di Update!');
        }else{  
            if (Auth::check() && Auth::user()->role->slug == 'admin-sdm') {
                return redirect()->route('admin_sdm.datadiri')->with('success', 'Data pendidikan berhasil diperbarui!');
            } elseif (Auth::check() && Auth::user()->role->slug == 'karyawan') {
                return redirect()->route('karyawan.datadiri')->with('success', 'Data pendidikan berhasil diperbarui!');
            } elseif (Auth::check() && Auth::user()->role->slug == 'manager') {
                return redirect()->route('manajer.datadiri')->with('success', 'Data pendidikan berhasil diperbarui!');
            }
        }
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

        
        if($request->jenis_page == 'page_detail_kepegawaian'){
            return redirect()->back()->with('success', 'Data Pendidikan berhasil di Update!');
        }else{    
            if (Auth::check() && Auth::user()->role->slug == 'admin-sdm') {
                return redirect()->route('admin_sdm.datadiri')->with('success', 'Data pendidikan berhasil diperbarui!');
            } elseif (Auth::check() && Auth::user()->role->slug == 'karyawan') {
                return redirect()->route('karyawan.datadiri')->with('success', 'Data pendidikan berhasil diperbarui!');
            } elseif (Auth::check() && Auth::user()->role->slug == 'manager') {
                return redirect()->route('manajer.datadiri')->with('success', 'Data pendidikan berhasil diperbarui!');
            }
        }
    }
}
