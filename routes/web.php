<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AjaxController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DivisiController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AbsensiHarianController;
use App\Http\Controllers\AdminSdmController;
use App\Http\Controllers\DatadiriController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\GajiController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KesahatanController;
use App\Http\Controllers\PelatihanController;
use App\Http\Controllers\SubJabatanController;
use App\Http\Controllers\KepegawaianController;
use App\Http\Controllers\LoginSSOController;
use App\Http\Controllers\ManajerController;
use App\Http\Controllers\SocialMediaController;
use App\Http\Controllers\PengalamanKerjaController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\StatusPekerjaanController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use SebastianBergmann\CodeCoverage\Report\Xml\Project;

//Auth Register & Login 
// Route::get('/register', function () {
//     return view('auth.register'); // Ganti path ini sesuai struktur view Anda
// })->name('register');

Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register_process', [AuthController::class, 'register_process'])->name('register_process');
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login-proses', [AuthController::class, 'login'])->name('login-proses');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
Route::post('/sso/login', [LoginSSOController::class, 'loginSSO'])->withoutMiddleware([VerifyCsrfToken::class])->name('sso.login');
Route::post('/sso/login/form', [LoginSSOController::class, 'loginSSOForm'])->name('sso.login.form');

Route::get('/csrf-token', function () {
    return response()->json(['csrf_token' => csrf_token()]);
});

// Route::middleware(['auth', 'role:1'])->group(function () {
//     Route::get('/superadmin/dashboard', [::class, 'dashboard'])->name('superadmin.dashboard');
// });

Route::controller(ExportController::class)->group(function () {
    Route::get('/report/excel-kepegawaian', 'exportKepegawaian')->name('api.export.excel_kepegawaian');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    // Admin
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
    // Data Karyawan
    Route::get('/admin/data_karyawan', [AdminController::class, 'data_karyawan']);
    Route::get('/admin/detail_karyawan/{id}', [AdminController::class, 'detail_karyawan']);

    //Management User 
    Route::get('/admin/users', [UserController::class, 'index']);
    Route::post('/admin/users/{id}/update-role', [UserController::class, 'updateRole']);
    Route::put('/admin/users/{id}/updateSubJabatan', [UserController::class, 'updateSubJabatan']);

    //Management Role
    Route::get('/admin/roles', [RoleController::class, 'create'])->name('admin.roles'); // Display all roles
    Route::post('/admin/roles/store', [RoleController::class, 'store'])->name('admin.roles.store'); // Store a new role
    Route::put('/admin/roles/update/{id}', [RoleController::class, 'update'])->name('admin.roles.update'); // Update an existing role
    Route::delete('/admin/roles/{id}', [RoleController::class, 'destroy'])->name('admin.roles.destroy'); // Delete a role

    // Admin : Absensi
    Route::get('/admin/absensi', [AbsensiController::class, 'index']);
    Route::put('/admin/absensi/update/{id}', [AbsensiController::class, 'update'])->name('admin.absensi.update'); // Update an existing role

});

Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        $title = 'title';
        return view('home', compact('title'));
    })->name('home');

    Route::get('/ajax/get_karyawan', [AjaxController::class, 'get_karyawan']);
});

Route::middleware(['auth', 'role:admin-sdm'])->group(function () {
    Route::get('/admin_sdm/dashboard', [AdminSdmController::class, 'dashboard'])->name('admin_sdm.dashboard');

    //Management User 
    Route::get('/admin_sdm/users', [UserController::class, 'index'])->name('admin.users');
    Route::post('/admin_sdm/users/{id}/update-role', [UserController::class, 'updateRole'])->name('admin.users.update-role');
    Route::put('/admin_sdm/users/{id}/updateSubJabatan', [UserController::class, 'updateSubJabatan'])->name('admin.users.updateSubJabatan');

    // Aadmin SDM : Kepegawaian
    Route::get('/admin_sdm/kepegawaian', [KepegawaianController::class, 'index']);
    Route::get('/admin_sdm/detail_kepegawaian/{id}', [KepegawaianController::class, 'show']);

    Route::post('/admin_sdm/kepegawaian/store', [KepegawaianController::class, 'store']);
    Route::put('/admin_sdm/kepegawaian/update/{id}', [KepegawaianController::class, 'update']);

    // Admin SDM : Master Sub Jabatan
    Route::get('/admin_sdm/sub_jabatan', [SubJabatanController::class, 'index'])->name('admin_sdm.sub_jabatan'); // Display all sub_jabatan
    Route::post('/admin_sdm/sub_jabatan/store', [SubJabatanController::class, 'store'])->name('admin_sdm.sub_jabatan.store'); // Store a new role
    Route::put('/admin_sdm/sub_jabatan/update/{id}', [SubJabatanController::class, 'update'])->name('admin_sdm.sub_jabatan.update'); // Update an existing role
    Route::delete('/admin_sdm/sub_jabatan/delete/{id}', [SubJabatanController::class, 'destroy'])->name('admin.sub_jabatan.delete'); // Delete a role

    // Admin SDM : Absensi
    Route::get('/admin_sdm/absensi', [AbsensiController::class, 'index'])->name('admin_sdm.absensi.index');
    Route::put('/admin_sdm/absensi/update/{id}', [AbsensiController::class, 'update'])->name('admin_sdm.absensi.update'); // Update an existing role

    // Admin SDM : Absensi Harian
    Route::get('/admin_sdm/absensi_harian', [AbsensiHarianController::class, 'index'])->name('admin_sdm.absensi_harian.index');
    Route::get('/admin_sdm/absensi_harian/{id}', [AbsensiHarianController::class, 'show'])->name('admin_sdm.absensi_harian.show');
    Route::post('/admin_sdm/absensi_harian/store/{id}', [AbsensiHarianController::class, 'store'])->name('admin_sdm.absensi_harian.store');
    Route::put('/admin_sdm/absensi_harian/update/{pegawai_id}/{id}', [AbsensiHarianController::class, 'update'])->name('admin_sdm.absensi_harian.update');
    Route::delete('/admin_sdm/absensi_harian/delete/{id}', [AbsensiHarianController::class, 'destroy'])->name('admin_sdm.absensi_harian.delete'); // Delete a role

    // Admin SDM : Gaji
    Route::get('/admin_sdm/gaji', [GajiController::class, 'index'])->name('admin_sdm.gaji.index');
    Route::post('/admin_sdm/gaji/store', [GajiController::class, 'store'])->name('admin_sdm.gaji.store');
    Route::put('/admin_sdm/gaji/update/{id}', [GajiController::class, 'update'])->name('admin_sdm.gaji.update');

    // Admin SDM : Master Status Pekerjaan
    Route::get('/admin_sdm/status_pekerjaan', [StatusPekerjaanController::class, 'index'])->name('admin_sdm.status_pekerjaan'); // Display all status_pekerjaan
    Route::post('/admin_sdm/status_pekerjaan/store', [StatusPekerjaanController::class, 'store'])->name('admin_sdm.status_pekerjaan.store'); // Store a new role
    Route::put('/admin_sdm/status_pekerjaan/update/{id}', [StatusPekerjaanController::class, 'update'])->name('admin_sdm.status_pekerjaan.update'); // Update an existing role
    Route::delete('/admin_sdm/status_pekerjaan/delete/{id}', [StatusPekerjaanController::class, 'destroy'])->name('admin.status_pekerjaan.delete'); // Delete a role


    Route::get('/admin_sdm/datadiri', [DatadiriController::class, 'index'])->name('admin_sdm.datadiri');
    Route::post('/admin_sdm/datadiri/store', [DataDiriController::class, 'store'])->name('admin_sdm.datadiri.store');
    Route::put('/admin_sdm/datadiri/update/{id}', [DatadiriController::class, 'update'])->name('admin_sdm.datadiri.update');
    Route::post('/admin_sdm/datadiri/pendidikan', [DataDiriController::class, 'pendidikanstore'])->name('admin_sdm.pendidikan.store');
    Route::put('/admin_sdm/datadiri/pendidikan/{id}', [DatadiriController::class, 'pendidikanupdate'])->name('admin_sdm.pendidikan.update');

    // admin SDM : Pengalaman Kerja
    Route::get('/admin_sdm/pengalaman_kerja/', [PengalamanKerjaController::class, 'index'])->name('admin_sdm.pengalaman_kerja');
    Route::post('/admin_sdm/pengalaman_kerja/store', [PengalamanKerjaController::class, 'store'])->name('admin_sdm.pengalaman_kerja.store');
    Route::put('/admin_sdm/pengalaman_kerja/update/{id}', [PengalamanKerjaController::class, 'update'])->name('admin_sdm.pengalaman_kerja.update');
    Route::delete('/admin_sdm/pengalaman_kerja/delete/{id}', [PengalamanKerjaController::class, 'destroy'])->name('admin_sdm.pengalaman_kerja.delete');

    //admin SDM : PElatihan
    Route::get('/admin_sdm/pelatihan/', [PelatihanController::class, 'index'])->name('admin_sdm.pelatihan');
    Route::post('/admin_sdm/pelatihan/store', [PelatihanController::class, 'store'])->name('admin_sdm.pelatihan.store');
    Route::put('/admin_sdm/pelatihan/update/{id}', [PelatihanController::class, 'update'])->name('admin_sdm.pelatihan.update');
    Route::delete('/admin_sdm/pelatihan/delete/{id}', [PelatihanController::class, 'destroy'])->name('admin_sdm.pelatihan.delete');

    // admin SDM: Kesehatan
    Route::post('/admin_sdm/kesehatan/store', [KesahatanController::class, 'store'])->name('admin_sdm.kesehatan.store');
    Route::put('/admin_sdm/kesehatan/update/{id}', [KesahatanController::class, 'update'])->name('admin_sdm.kesehatan.update');
    Route::delete('/admin_sdm/kesehatan/delete/{id}', [KesahatanController::class, 'destroy'])->name('admin_sdm.kesehatan.delete');

    // admin SDM: Social Media
    Route::get('/admin_sdm/social_media/', [SocialMediaController::class, 'index'])->name('admin_sdm.social_media');
    Route::post('/admin_sdm/social_media/store', [SocialMediaController::class, 'store'])->name('admin_sdm.social_media.store');
    Route::put('/admin_sdm/social_media/update/{id}', [SocialMediaController::class, 'update'])->name('admin_sdm.social_media.update');
    Route::delete('/admin_sdm/social_media/delete/{id}', [SocialMediaController::class, 'destroy'])->name('admin_sdm.social_media.delete');

    // admin SDM: Divisi
    Route::get('/admin_sdm/divisi/', [DivisiController::class, 'index'])->name('admin_sdm.divisi');
    Route::post('/admin_sdm/divisi/store', [DivisiController::class, 'store'])->name('admin_sdm.divisi.store');
    Route::put('/admin_sdm/divisi/update/{id}', [DivisiController::class, 'update'])->name('admin_sdm.divisi.update');
    Route::delete('/admin_sdm/divisi/delete/{id}', [DivisiController::class, 'destroy'])->name('admin_sdm.divisi.destroy');
});

Route::middleware(['auth', 'role:karyawan'])->group(function () {
    //Karyawan
    Route::get('/karyawan/dashboard', [KaryawanController::class, 'dashboard'])->name('karyawan.dashboard');

    //Management Datadiri
    Route::get('/karyawan/datadiri', [DatadiriController::class, 'index'])->name('karyawan.datadiri');
    Route::post('/karyawan/datadiri/store', [DataDiriController::class, 'store'])->name('karyawan.datadiri.store');
    Route::put('/karyawan/datadiri/update/{id}', [DatadiriController::class, 'update'])->name('karyawan.datadiri.update');
    Route::post('/karyawan/datadiri/pendidikan', [DataDiriController::class, 'pendidikanstore'])->name('karyawan.pendidikan.store');
    Route::put('/karyawan/datadiri/pendidikan/{id}', [DatadiriController::class, 'pendidikanupdate'])->name('karyawan.pendidikan.update');
    //Route::put('/datadiri/update/{id}', [DataDiriController::class, 'update'])->name('datadiri.update');
    //Route::post('/datadiri', [DataDiriController::class, 'store'])->name('datadiri.store');

    // Karyawan : Pengalaman Kerja
    Route::get('/karyawan/pengalaman_kerja/', [PengalamanKerjaController::class, 'index'])->name('karyawan.pengalaman_kerja');
    Route::post('/karyawan/pengalaman_kerja/store', [PengalamanKerjaController::class, 'store'])->name('karyawan.pengalaman_kerja.store');
    Route::put('/karyawan/pengalaman_kerja/update/{id}', [PengalamanKerjaController::class, 'update'])->name('karyawan.pengalaman_kerja.update');
    Route::delete('/karyawan/pengalaman_kerja/delete/{id}', [PengalamanKerjaController::class, 'destroy'])->name('karyawan.pengalaman_kerja.delete');

    //KAryawan : PElatihan
    Route::get('/karyawan/pelatihan/', [PelatihanController::class, 'index'])->name('karyawan.pelatihan');
    Route::post('/karyawan/pelatihan/store', [PelatihanController::class, 'store'])->name('karyawan.pelatihan.store');
    Route::put('/karyawan/pelatihan/update/{id}', [PelatihanController::class, 'update'])->name('karyawan.pelatihan.update');
    Route::delete('/karyawan/pelatihan/delete/{id}', [PelatihanController::class, 'destroy'])->name('karyawan.pelatihan.delete');

    // Karyawan: Kesehatan
    Route::post('/karyawan/kesehatan/store', [KesahatanController::class, 'store'])->name('karyawan.kesehatan.store');
    Route::put('/karyawan/kesehatan/update/{id}', [KesahatanController::class, 'update'])->name('karyawan.kesehatan.update');
    Route::delete('/karyawan/kesehatan/delete/{id}', [KesahatanController::class, 'destroy'])->name('karyawan.kesehatan.delete');

    // Karyawan: Social Media
    Route::get('/karyawan/social_media/', [SocialMediaController::class, 'index'])->name('karyawan.social_media');
    Route::post('/karyawan/social_media/store', [SocialMediaController::class, 'store'])->name('karyawan.social_media.store');
    Route::put('/karyawan/social_media/update/{id}', [SocialMediaController::class, 'update'])->name('karyawan.social_media.update');
    Route::delete('/karyawan/social_media/delete/{id}', [SocialMediaController::class, 'destroy'])->name('karyawan.social_media.delete');
});

Route::middleware(['auth', 'role:manager'])->group(function () {
    // manajer : data perusahaan
    Route::get('/manajer/dashboard', [ManajerController::class, 'index'])->name('manajer.dashboard');
    Route::get('/manajer/perusahaan', [ManajerController::class, 'show'])->name('manajer.perusahaan');
    Route::post('/manajer/perusahaan/store', [ManajerController::class, 'store'])->name('manajer.tambah.perusahaan');
    Route::put('/manajer/perusahaan/update/{id}', [ManajerController::class, 'update'])->name('manajer.update.perusahaan');
    Route::delete('/manajer/perusahaan/delete/{id}', [ManajerController::class, 'destroy'])->name('manajer.delete.perusahaan');

    // manajer : data datadiri
    Route::get('/manajer/datadiri', [DatadiriController::class, 'index'])->name('manajer.datadiri');
    Route::post('/manajer/datadiri/store', [DataDiriController::class, 'store'])->name('manajer.datadiri.store');
    Route::put('/manajer/datadiri/update/{id}', [DatadiriController::class, 'update'])->name('manajer.datadiri.update');
    Route::post('/manajer/datadiri/pendidikan', [DataDiriController::class, 'pendidikanstore'])->name('manajer.pendidikan.store');
    Route::put('/manajer/datadiri/pendidikan/{id}', [DatadiriController::class, 'pendidikanupdate'])->name('manajer.pendidikan.update');

    // manajer : Pengalaman Kerja
    Route::get('/manajer/pengalaman_kerja/', [PengalamanKerjaController::class, 'index'])->name('manajer.pengalaman_kerja');
    Route::post('/manajer/pengalaman_kerja/store', [PengalamanKerjaController::class, 'store'])->name('manajer.pengalaman_kerja.store');
    Route::put('/manajer/pengalaman_kerja/update/{id}', [PengalamanKerjaController::class, 'update'])->name('manajer.pengalaman_kerja.update');
    Route::delete('/manajer/pengalaman_kerja/delete/{id}', [PengalamanKerjaController::class, 'destroy'])->name('manajer.pengalaman_kerja.delete');

    // manajer : Pelatihan
    Route::get('/manajer/pelatihan/', [PelatihanController::class, 'index'])->name('manajer.pelatihan');
    Route::post('/manajer/pelatihan/store', [PelatihanController::class, 'store'])->name('manajer.pelatihan.store');
    Route::put('/manajer/pelatihan/update/{id}', [PelatihanController::class, 'update'])->name('manajer.pelatihan.update');
    Route::delete('/manajer/pelatihan/delete/{id}', [PelatihanController::class, 'destroy'])->name('manajer.pelatihan.delete');

    // manajer: Kesehatan
    Route::post('/manajer/kesehatan/store', [KesahatanController::class, 'store'])->name('manajer.kesehatan.store');
    Route::put('/manajer/kesehatan/update/{id}', [KesahatanController::class, 'update'])->name('manajer.kesehatan.update');
    Route::delete('/manajer/kesehatan/delete/{id}', [KesahatanController::class, 'destroy'])->name('manajer.kesehatan.delete');

    // manajer: Social Media
    Route::get('/manajer/social_media/', [SocialMediaController::class, 'index'])->name('manajer.social_media');
    Route::post('/manajer/social_media/store', [SocialMediaController::class, 'store'])->name('manajer.social_media.store');
    Route::put('/manajer/social_media/update/{id}', [SocialMediaController::class, 'update'])->name('manajer.social_media.update');
    Route::delete('/manajer/social_media/delete/{id}', [SocialMediaController::class, 'destroy'])->name('manajer.social_media.delete');

    // manajer : data project
    Route::get('/manajer/project', [ProjectController::class, 'show'])->name('manajer.project');
    Route::post('/manajer/project/store', [ProjectController::class, 'store'])->name('manajer.tambah.project');
    Route::get('/manajer/project/detail/{id}', [ProjectController::class, 'detail'])->name('manajer.detail.project');
    Route::put('/manajer/project/update/{id}', [ProjectController::class, 'update'])->name('manajer.update.project');
});

Route::middleware(['auth'])->group(function () {
    // sso
    Route::get('/sso/get', [LoginSSOController::class, 'index'])->name('sso');
    Route::post('/sso/store', [LoginSSOController::class, 'storeSSO'])->name('sso.store');
});
