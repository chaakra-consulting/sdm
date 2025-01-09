<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\DatadiriController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KesahatanController;
use App\Http\Controllers\PelatihanController;
use App\Http\Controllers\PengalamanKerjaController;
use App\Http\Controllers\SocialMediaController;
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

// Route::middleware(['auth', 'role:2'])->group(function () {
//     Route::get('/admin/dashboard', [::class, 'dashboard'])->name('admin.dashboard');
// });

// Route::middleware(['auth', 'role:3'])->group(function () {
//     Route::get('/karyawan/dashboard', [::class, 'dashboard'])->name('karyawan.dashboard');
// });

Route::middleware(['auth', 'role:2'])->group(function () {
    Route::get('/', function () {
        $title = 'title';
        return view('home', compact('title'));
    })->name('home');

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
    // Manajemen Sub Jabatan
    Route::get('/admin/sub_jabatan', [SubJabatanController::class, 'index'])->name('admin.sub_jabatan'); // Display all sub_jabatan
    Route::post('/admin/sub_jabatan/store', [SubJabatanController::class, 'store'])->name('admin.sub_jabatan.store'); // Store a new role
    Route::put('/admin/sub_jabatan/update/{id}', [SubJabatanController::class, 'update'])->name('admin.sub_jabatan.update'); // Update an existing role
    Route::delete('/admin/sub_jabatan/delete/{id}', [SubJabatanController::class, 'destroy'])->name('admin.sub_jabatan.delete'); // Delete a role
    
    //Management Datadiri
    Route::get('/datadiri', [DatadiriController::class, 'index'])->name('datadiri');
    Route::post('/datadiri/store', [DataDiriController::class, 'store'])->name('datadiri.store');
    Route::get('/datadiri/update/{id}', [DataDiriController::class, 'create'])->name('datadiri.update');
    //Route::post('/datadiri', [DataDiriController::class, 'store'])->name('datadiri.store');
    
    Route::put('/datadiri/{id}', [DatadiriController::class, 'update'])->name('datadiri.update');
    Route::post('/datadiri/pendidikan', [DataDiriController::class, 'pendidikanstore'])->name('pendidikan.store');
    Route::put('/datadiri/pendidikan/{id}', [DatadiriController::class, 'pendidikanupdate'])->name('pendidikan.update');
    
});

Route::middleware(['auth', 'role:3'])->group(function () {
    //Karyawan
    Route::get('/karyawan/dashboard', action: [KaryawanController::class, 'dashboard'])->name('karyawan.dashboard');

    // Karyawan : Pengalaman Kerja
    Route::get('/karyawan/pengalaman_kerja/', [PengalamanKerjaController::class, 'index'])->name('karyawan.pengalaman_kerja');
    Route::post('/karyawan/pengalaman_kerja/store', [PengalamanKerjaController::class, 'store']);
    Route::put('/karyawan/pengalaman_kerja/update/{id}', [PengalamanKerjaController::class, 'update']);
    Route::delete('/karyawan/pengalaman_kerja/delete/{id}', [PengalamanKerjaController::class, 'destroy']);

    // Karyawan : data diri
    Route::get('/karyawan/datadiri', [DatadiriController::class, 'index'])->name('karyawan.datadiri');

    //Management Datadiri
    Route::get('/datadiri', [DatadiriController::class, 'index'])->name('datadiri');
    Route::post('/datadiri/store', [DataDiriController::class, 'store'])->name('datadiri.store');
    Route::get('/datadiri/update/{id}', [DataDiriController::class, 'create'])->name('datadiri.update');

    //KAryawan : Pelatihan
    Route::get('/karyawan/pelatihan/', [PelatihanController::class, 'index'])->name('karyawan.pelatihan');
    Route::post('/karyawan/pelatihan/store', [PelatihanController::class, 'store']);
    Route::put('/karyawan/pelatihan/update/{id}', [PelatihanController::class, 'update']);
    Route::delete('/karyawan/pelatihan/delete/{id}', [PelatihanController::class, 'destroy']);

    // Karyawan: Kesehatan
    Route::post('/karyawan/kesehatan/store', [KesahatanController::class, 'store']);
    Route::put('/karyawan/kesehatan/update/{id}', [KesahatanController::class, 'update']);
    Route::delete('/karyawan/kesehatan/delete/{id}', [KesahatanController::class, 'destroy']);

    // Karyawan: Social Media
    Route::get('/karyawan/social_media/', [SocialMediaController::class, 'index'])->name('karyawan.social_media');
    Route::post('/karyawan/social_media/store', [SocialMediaController::class, 'store']);
    Route::put('/karyawan/social_media/update/{id}', [SocialMediaController::class, 'update']);
    Route::delete('/karyawan/social_media/delete/{id}', [SocialMediaController::class, 'destroy']);
});