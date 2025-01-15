<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminSdmController;
use App\Http\Controllers\AjaxController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\DatadiriController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KepegawaianController;
use App\Http\Controllers\KesahatanController;
use App\Http\Controllers\PelatihanController;
use App\Http\Controllers\PengalamanKerjaController;
use App\Http\Controllers\SocialMediaController;
use App\Http\Controllers\StatusPekerjaanController;
use App\Http\Controllers\SubJabatanController;

//Auth Register & Login 
// Route::get('/register', function () {
//     return view('auth.register'); // Ganti path ini sesuai struktur view Anda
// })->name('register');

Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register_process', [AuthController::class, 'register_process'])->name('register_process');
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login-proses', [AuthController::class, 'login'])->name('login-proses');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Route::middleware(['auth', 'role:1'])->group(function () {
//     Route::get('/superadmin/dashboard', [::class, 'dashboard'])->name('superadmin.dashboard');
// });

Route::middleware(['auth', 'role:admin'])->group(function () {
    // Admin
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
    // Data Karyawan
    Route::get('/admin/data_karyawan', [AdminController::class, 'data_karyawan']);
    Route::get('/admin/detail_karyawan/{id}', [AdminController::class, 'detail_karyawan']);

    //Management User 
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users');
    Route::post('/admin/users/{id}/update-role', [UserController::class, 'updateRole'])->name('admin.users.update-role');
    Route::put('/admin/users/{id}/updateSubJabatan', [UserController::class, 'updateSubJabatan'])->name('admin.users.updateSubJabatan');

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

    // Admin SDM : Master Status Pekerjaan
    Route::get('/admin_sdm/status_pekerjaan', [StatusPekerjaanController::class, 'index'])->name('admin_sdm.status_pekerjaan'); // Display all status_pekerjaan
    Route::post('/admin_sdm/status_pekerjaan/store', [StatusPekerjaanController::class, 'store'])->name('admin_sdm.status_pekerjaan.store'); // Store a new role
    Route::put('/admin_sdm/status_pekerjaan/update/{id}', [StatusPekerjaanController::class, 'update'])->name('admin_sdm.status_pekerjaan.update'); // Update an existing role
    Route::delete('/admin_sdm/status_pekerjaan/delete/{id}', [StatusPekerjaanController::class, 'destroy'])->name('admin.status_pekerjaan.delete'); // Delete a role


    Route::get('/admin_sdm/datadiri', [DatadiriController::class, 'index'])->name('datadiri');
    Route::post('/admin_sdm/datadiri/store', [DataDiriController::class, 'store'])->name('datadiri.store');
    Route::put('/admin_sdm/datadiri/update/{id}', [DatadiriController::class, 'update'])->name('datadiri.update');
    Route::post('/admin_sdm/datadiri/pendidikan', [DataDiriController::class, 'pendidikanstore'])->name('pendidikan.store');
    Route::put('/admin_sdm/datadiri/pendidikan/{id}', [DatadiriController::class, 'pendidikanupdate'])->name('pendidikan.update');

    // admin SDM : Pengalaman Kerja
    Route::get('/admin_sdm/pengalaman_kerja/', [PengalamanKerjaController::class, 'index']);
    Route::post('/admin_sdm/pengalaman_kerja/store', [PengalamanKerjaController::class, 'store']);
    Route::put('/admin_sdm/pengalaman_kerja/update/{id}', [PengalamanKerjaController::class, 'update']);
    Route::delete('/admin_sdm/pengalaman_kerja/delete/{id}', [PengalamanKerjaController::class, 'destroy']);

    //admin SDM : PElatihan
    Route::get('/admin_sdm/pelatihan/', [PelatihanController::class, 'index']);
    Route::post('/admin_sdm/pelatihan/store', [PelatihanController::class, 'store']);
    Route::put('/admin_sdm/pelatihan/update/{id}', [PelatihanController::class, 'update']);
    Route::delete('/admin_sdm/pelatihan/delete/{id}', [PelatihanController::class, 'destroy']);

    // admin SDM: Kesehatan
    Route::post('/admin_sdm/kesehatan/store', [KesahatanController::class, 'store']);
    Route::put('/admin_sdm/kesehatan/update/{id}', [KesahatanController::class, 'update']);
    Route::delete('/admin_sdm/kesehatan/delete/{id}', [KesahatanController::class, 'destroy']);

    // admin SDM: Social Media
    Route::get('/admin_sdm/social_media/', [SocialMediaController::class, 'index']);
    Route::post('/admin_sdm/social_media/store', [SocialMediaController::class, 'store']);
    Route::put('/admin_sdm/social_media/update/{id}', [SocialMediaController::class, 'update']);
    Route::delete('/admin_sdm/social_media/delete/{id}', [SocialMediaController::class, 'destroy']);
});

Route::middleware(['auth', 'role:karyawan'])->group(function () {
    //Karyawan
    Route::get('/karyawan/dashboard', [KaryawanController::class, 'dashboard'])->name('karyawan.dashboard');

    //Management Datadiri
    Route::get('/datadiri', [DatadiriController::class, 'index'])->name('datadiri');
    Route::post('/datadiri/store', [DataDiriController::class, 'store'])->name('datadiri.store');
    Route::put('/datadiri/update/{id}', [DatadiriController::class, 'update'])->name('datadiri.update');
    Route::post('/datadiri/pendidikan', [DataDiriController::class, 'pendidikanstore'])->name('pendidikan.store');
    Route::put('/datadiri/pendidikan/{id}', [DatadiriController::class, 'pendidikanupdate'])->name('pendidikan.update');
    //Route::put('/datadiri/update/{id}', [DataDiriController::class, 'update'])->name('datadiri.update');
    //Route::post('/datadiri', [DataDiriController::class, 'store'])->name('datadiri.store');

    // Karyawan : Pengalaman Kerja
    Route::get('/karyawan/pengalaman_kerja/', [PengalamanKerjaController::class, 'index']);
    Route::post('/karyawan/pengalaman_kerja/store', [PengalamanKerjaController::class, 'store']);
    Route::put('/karyawan/pengalaman_kerja/update/{id}', [PengalamanKerjaController::class, 'update']);
    Route::delete('/karyawan/pengalaman_kerja/delete/{id}', [PengalamanKerjaController::class, 'destroy']);

    //KAryawan : PElatihan
    Route::get('/karyawan/pelatihan/', [PelatihanController::class, 'index']);
    Route::post('/karyawan/pelatihan/store', [PelatihanController::class, 'store']);
    Route::put('/karyawan/pelatihan/update/{id}', [PelatihanController::class, 'update']);
    Route::delete('/karyawan/pelatihan/delete/{id}', [PelatihanController::class, 'destroy']);

    // Karyawan: Kesehatan
    Route::post('/karyawan/kesehatan/store', [KesahatanController::class, 'store']);
    Route::put('/karyawan/kesehatan/update/{id}', [KesahatanController::class, 'update']);
    Route::delete('/karyawan/kesehatan/delete/{id}', [KesahatanController::class, 'destroy']);

    // Karyawan: Social Media
    Route::get('/karyawan/social_media/', [SocialMediaController::class, 'index']);
    Route::post('/karyawan/social_media/store', [SocialMediaController::class, 'store']);
    Route::put('/karyawan/social_media/update/{id}', [SocialMediaController::class, 'update']);
    Route::delete('/karyawan/social_media/delete/{id}', [SocialMediaController::class, 'destroy']);
});
