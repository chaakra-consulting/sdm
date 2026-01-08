<?php

use Mockery\Matcher\Subset;
use App\Models\ProjectPerusahaan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;
use App\Http\Controllers\AjaxController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GajiController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DivisiController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\ManajerController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SubTaskController;
use App\Http\Controllers\AdminSdmController;
use App\Http\Controllers\DatadiriController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\LoginSSOController;
use App\Http\Controllers\TipeTaskController;
use App\Http\Controllers\HariLiburController;
use App\Http\Controllers\KesahatanController;
use App\Http\Controllers\PelatihanController;
use App\Http\Controllers\SubJabatanController;
use App\Http\Controllers\DownloadPDFController;
use App\Http\Controllers\GajiBulananController;
use App\Http\Controllers\KepegawaianController;
use App\Http\Controllers\SocialMediaController;
use App\Http\Controllers\UsersProjectController;
use App\Http\Controllers\AbsensiHarianController;
use App\Http\Controllers\LaporanKinerjaController;
use App\Http\Controllers\PengalamanKerjaController;
use App\Http\Controllers\StatusPekerjaanController;
use SebastianBergmann\CodeCoverage\Report\Xml\Project;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;

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
Route::post('/external-project', [ProjectController::class, 'storeFromExternal'])->name('external.project.store')->withoutMiddleware([VerifyCsrfToken::class]);
Route::get('/project/{id}/progress', function (Request $request, $id) {
    $project = ProjectPerusahaan::findOrFail($id);
    return response()->json([
        'progress' => $project->calculateProgress()
    ]);
})->withoutMiddleware('auth');

Route::get('/csrf-token', function () {
    return response()->json(['csrf_token' => csrf_token()]);
});

// Route::middleware(['auth', 'role:1'])->group(function () {
//     Route::get('/superadmin/dashboard', [::class, 'dashboard'])->name('superadmin.dashboard');
// });

Route::controller(ExportController::class)->group(function () {
    Route::get('/report/excel-kepegawaian', 'exportKepegawaian')->name('api.export.excel_kepegawaian');
    Route::get('/report/excel-absensi', 'exportAbsensi')->name('api.export.excel_absensi');
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
    Route::delete('/admin/rolyes/{id}', [RoleController::class, 'destroy'])->name('admin.roles.destroy'); // Delete a role

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
    Route::get('/cetak-payslip/{id}', [DownloadPDFController::class, 'generatePDFPayslip']);
});

Route::middleware(['auth', 'role:admin-sdm'])->group(function () {
    Route::get('/admin_sdm/dashboard', [AdminSdmController::class, 'dashboard'])->name('admin_sdm.dashboard');
    Route::get('/admin_sdm/dashboard_chart', [AdminSdmController::class, 'dashboardChart'])->name('admin_sdm.dashboard_chart');
    Route::get('/admin_sdm/dashboard_gaji', [AdminSdmController::class, 'dashboardGaji'])->name('admin_sdm.dashboard_gaji');

    //Management User
    Route::get('/admin_sdm/users', [UserController::class, 'index'])->name('admin.users');
    Route::post('/admin_sdm/users/{id}/update-role', [UserController::class, 'updateRole'])->name('admin.users.update-role');
    Route::put('/admin_sdm/users/{id}/updateSubJabatan', [UserController::class, 'updateSubJabatan'])->name('admin.users.updateSubJabatan');

    // Aadmin SDM : Kepegawaian
    Route::get('/admin_sdm/kepegawaian', [KepegawaianController::class, 'index'])->name('admin_sdm.kepegawaian');
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

    //Route::get('/admin_sdm/absensi_verifikasi/store/{id}', [AbsensiHarianController::class, 'storeVerifikasi'])->name('admin_sdm.absensi_verifikasi.store');

    // Admin SDM : Gaji
    Route::get('/admin_sdm/gaji', [GajiController::class, 'index'])->name('admin_sdm.gaji.index');
    Route::post('/admin_sdm/gaji/store', [GajiController::class, 'store'])->name('admin_sdm.gaji.store');
    Route::put('/admin_sdm/gaji/update/{id}', [GajiController::class, 'update'])->name('admin_sdm.gaji.update');

    // Admin SDM : Gaji Bulanan
    Route::get('/admin_sdm/gaji_bulanan', [GajiBulananController::class, 'index'])->name('admin_sdm.gaji_bulanan.index');
    Route::post('/admin_sdm/gaji_bulanan/store', [GajiBulananController::class, 'store'])->name('admin_sdm.gaji_bulanan.store');
    Route::put('/admin_sdm/gaji_bulanan/update/{id}', [GajiBulananController::class, 'update'])->name('admin_sdm.gaji_bulanan.update');
    Route::get('/admin_sdm/gaji_bulanan/sync', [GajiBulananController::class, 'sync'])->name('admin_sdm.gaji_bulanan.sync');

    Route::get('/admin_sdm/gaji_bulanan/diri', [GajiBulananController::class, 'indexKaryawan'])->name('admin_sdm.gaji_bulanan.index_karyawan');

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

    Route::get('/admin_sdm/datadiri/sdm', [DatadiriController::class, 'indexSDM'])->name('admin_sdm.datadiri.index_sdm');
    Route::post('/admin_sdm/datadiri/store-sdm', [DataDiriController::class, 'storeFromSDM'])->name('admin_sdm.datadiri.store_sdm');

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

    // admin SDM: Hari Libur
    Route::get('/admin_sdm/hari_libur/', [HariLiburController::class, 'index'])->name('admin_sdm.hari_libur');
    Route::post('/admin_sdm/hari_libur/store', [HariLiburController::class, 'store'])->name('admin_sdm.hari_libur.store');
    Route::put('/admin_sdm/hari_libur/update/{id}', [HariLiburController::class, 'update'])->name('admin_sdm.hari_libur.update');
    Route::delete('/admin_sdm/hari_libur/delete/{id}', [HariLiburController::class, 'destroy'])->name('admin_sdm.hari_libur.destroy');

    Route::get('/admin_sdm/project', [ProjectController::class, 'show'])->name('admin_sdm.project');
    Route::get('/admin_sdm/project/detail/{id}', [ProjectController::class, 'detail'])->name('admin_sdm.detail.project');
    // Route::post('/admin_sdm/project/store', [UsersProjectController::class, 'store'])->name('admin_sdm.project.store');
    // Route::put('/admin_sdm/project/update/{id}', [UsersProjectController::class, 'update'])->name('admin_sdm.update.project');
});

Route::middleware(['auth', 'role:karyawan'])->group(function () {
    //Karyawan
    Route::get('/karyawan/dashboard', [KaryawanController::class, 'dashboard'])->name('karyawan.dashboard');
    Route::get('/karyawan/get-kehadiran-data-value', [KaryawanController::class, 'getDashboardKehadiranDataValue'])->name('karyawan.dashboard_kehadiran_data_value');
    Route::get('/karyawan/get-kehadiran-data-percentage', [KaryawanController::class, 'getDashboardKehadiranDataPercentage'])->name('karyawan.dashboard_kehadiran_data');

    //Management Datadiri
    Route::get('/karyawan/datadiri', [DatadiriController::class, 'index'])->name('karyawan.datadiri');
    Route::post('/karyawan/datadiri/store', [DataDiriController::class, 'store'])->name('karyawan.datadiri.store');
    Route::put('/karyawan/datadiri/update/{id}', [DatadiriController::class, 'update'])->name('karyawan.datadiri.update');
    Route::post('/karyawan/datadiri/pendidikan', [DataDiriController::class, 'pendidikanstore'])->name('karyawan.pendidikan.store');
    Route::put('/karyawan/datadiri/pendidikan/{id}', [DatadiriController::class, 'pendidikanupdate'])->name('karyawan.pendidikan.update');
    //Route::put('/datadiri/update/{id}', [DataDiriController::class, 'update'])->name('datadiri.update');
    //Route::post('/datadiri', [DataDiriController::class, 'store'])->name('datadiri.store');

    // Karyawan: Absensi Harian
    Route::get('/karyawan/absensi_harian/{id}', [AbsensiHarianController::class, 'show'])->name('karyawan.absensi_harian.show');

    // karyawan: project
    Route::get('/karyawan/project', [ProjectController::class, 'show'])->name('karyawan.project');
    Route::post('/karyawan/project/store', [UsersProjectController::class, 'store'])->name('karyawan.project.store');
    Route::get('/karyawan/project/detail/{id}', [ProjectController::class, 'detail'])->name('karyawan.detail.project');
    Route::put('/karyawan/project/update/{id}', [UsersProjectController::class, 'update'])->name('karyawan.update.project');

    // karyawan : Task
    Route::get('/karyawan/task/', [TaskController::class, 'index'])->name('karyawan.task');
    Route::post('/karyawan/task/store', [TaskController::class, 'store'])->name('karyawan.task.store');
    Route::get('/karyawan/task/detail/{id}', [TaskController::class, 'detail'])->name('karyawan.detail.task');
    Route::put('/karyawan/task/update/detail/{id}', [TaskController::class, 'updateDetailTask'])->name('karyawan.update.detail.task');
    Route::delete('/karyawan/task/delete/{id}', [TaskController::class, 'destroy'])->name('karyawan.delete.task');
    Route::get('/karyawan/project/{id}/tasks', [UsersProjectController::class, 'getTasks'])->name('karyawan.project.tasks');

    //karyawan : Gaji Bulanan
    Route::get('/karyawan/gaji_bulanan/diri', [GajiBulananController::class, 'indexKaryawan'])->name('admin_sdm.gaji_bulanan.index_karyawan');

    // karyawan : sub task
    Route::get('/karyawan/subtask', [SubTaskController::class, 'show'])->name('karyawan.subtask');
    Route::post('/karyawan/subtask/store', [SubTaskController::class, 'store'])->name('karyawan.subtask.store');
    Route::get('/karyawan/subtask/detail/{id}', [SubTaskController::class, 'detail'])->name('karyawan.subtask.detail');
    Route::put('/karyawan/subtask/detail/kirim/{id}', [SubTaskController::class, 'kirim'])->name('karyawan.subtask.detail.kirim');
    Route::put('/karyawan/subtask/detail/batal/{id}', [SubTaskController::class, 'batal'])->name('karyawan.subtask.detail.batal');
    Route::put('/karyawan/subtask/detail/update/{id}', [SubTaskController::class, 'updateDetail'])->name('karyawan.subtask.update.detail');
    Route::put('/karyawan/subtask/detail/update/lampiran/{id}', [SubTaskController::class, 'updateDetailLampiran'])->name('karyawan.subtask.update.detail.lampiran');
    Route::delete('/karyawan/subtask/detail/lampiran/{id}', [SubTaskController::class, 'destroyLampiran'])->name('lampiran-subtask.delete');
    Route::put('/karyawan/subtask/update/{id}', [SubTaskController::class, 'update'])->name('karyawan.subtask.update');
    Route::delete('/karyawan/subtask/delete/{id}', [SubTaskController::class, 'destroy'])->name('karyawan.subtask.delete');

    // karyawan : laporan kinerja
    Route::get('/karyawan/laporan_kinerja', [LaporanKinerjaController::class, 'show'])->name('karyawan.laporan_kinerja');
    Route::post('/karyawan/laporan_kinerja/store', [LaporanKinerjaController::class, 'store'])->name('karyawan.laporan_kinerja.store');
    Route::put('/karyawan/laporan_kinerja/update/{id}', [LaporanKinerjaController::class, 'update'])->name('karyawan.laporan_kinerja.update');
    Route::get('/karyawan/laporan_kinerja/getDataByDate',[LaporanKinerjaController::class, 'getDataByDate'])->name('karyawan.laporan_kinerja.getDataByDate');
    Route::get('/karyawan/laporan_kinerja/detail/{id}/{month?}/{year?}',[LaporanKinerjaController::class, 'detail'])->name('karyawan.laporan_kinerja.detail');
    Route::put('/karyawan/laporan_kinerja/kirim/{id}', [LaporanKinerjaController::class, 'kirim'])->name('karyawan.laporan_kinerja.kirim');
    Route::put('/karyawan/laporan_kinerja/batal/{id}', [LaporanKinerjaController::class, 'batal'])->name('karyawan.laporan_kinerja.batal');
    Route::delete('/karyawan/laporan_kinerja/delete/{id}', [LaporanKinerjaController::class, 'destroy'])->name('karyawan.laporan_kinerja.delete');
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
    Route::post('/manajer/project/update/anggota', [ProjectController::class, 'updateUserProject'])->name('manajer.update.anggota.project');
    Route::delete('/manajer/project/delete/{id}', [ProjectController::class, 'destroy'])->name('manajer.delete.project');
    Route::delete('/manajer/project/delete/anggota/{id}', [ProjectController::class, 'destroyUserProject'])->name('manajer.delete.anggota.project');

    // manajer : task
    Route::get('/manajer/task/', [TaskController::class, 'index'])->name('manajer.task');
    Route::get('/manajer/task/{id}', [TaskController::class, 'detail'])->name('manajer.detail.task');
    Route::post('/manajer/task/store', [TaskController::class, 'store'])->name('manajer.store.task');
    Route::put('/manajer/task/update/{id}', [TaskController::class, 'update'])->name('manajer.update.task');
    Route::put('/manajer/task/update/detail/{id}', [TaskController::class, 'updateDetailTask'])->name('manajer.update.detail.task');
    Route::put('/manajer/task/update/lampiran/{id}', [TaskController::class, 'updateLampiran'])->name('manajer.update.lampiran.task');
    Route::post('/manajer/task/update/anggota', [TaskController::class, 'updateUserTask'])->name('manajer.update.anggota.task');
    Route::delete('/manajer/task/delete/{id}', [TaskController::class, 'destroy'])->name('manajer.delete.task');
    Route::delete('/manajer/task/delete/anggota/{id}', [TaskController::class, 'destroyUserTask'])->name('manajer.delete.anggota.task');

    // manajer : tipe task
    Route::get('/manajer/tipe_task', [TipeTaskController::class, 'index'])->name('manajer.tipe_task');
    Route::post('/manajer/tipe_task/store', [TipeTaskController::class, 'store'])->name('manajer.store.tipe_task');
    Route::put('/manajer/tipe_task/update/{id}', [TipeTaskController::class, 'update'])->name('manajer.update.tipe_task');
    Route::delete('/manajer/tipe_task/delete/{id}', [TipeTaskController::class, 'destroy'])->name('manajer.delete.tipe_task');

    // manajer : sub task
    Route::get('/manajer/subtask', [SubTaskController::class, 'show'])->name('manajer.subtask');
    Route::get('/manajer/subtask/detail/{id}', [SubTaskController::class, 'detail'])->name('manajer.subtask.detail');

    // manajer : laporan kinerja
    Route::get('/manajer/laporan_kinerja', [ManajerController::class, 'laporanKinerja'])->name('manajer.laporan_kinerja');
    Route::get('/manajer/laporan_kinerja/{id}', [ManajerController::class, 'listLaporanKinerja'])->name('manajer.list.laporan_kinerja');
    Route::get('/manajer/laporan_kinerja/detail/{id}', [ManajerController::class, 'detailLaporanKinerja'])->name('manajer.detail.laporan_kinerja');
    Route::post('/manajer/laporan_kinerja/approve/{id}', [ManajerController::class, 'approveLaporanKinerja'])->name('manajer.approve.laporan_kinerja');
    Route::post('/manajer/laporan_kinerja/approve/subtask/{id}', [ManajerController::class, 'approveSubtask'])->name('manajer.approve.subtask');
    Route::post('/manajer/laporan_kinerja/revise/{id}', [ManajerController::class, 'reviseLaporanKinerja'])->name('manajer.revise.laporan_kinerja');
    Route::post('/manajer/laporan_kinerja/revise/subtask/{id}', [ManajerController::class, 'reviseSubtask'])->name('manajer.revise.subtask');
    Route::delete('/manajer/laporan_kinerja/delete/{id}', [ManajerController::class, 'destroyLaporanKinerja'])->name('manajer.laporan_kinerja.delete');

    // manajer : data transfer
    Route::get('/manajer/transfer-data', [ManajerController::class, 'dataTransfer'])->name('manajer.transfer.data');
});

Route::middleware(['auth', 'role:direktur'])->group(function () {
    // Dashboard
    Route::get('/direktur/dashboard', [AdminSdmController::class, 'dashboard'])->name('direktur.dashboard');
    Route::get('/direktur/dashboard_gaji', [AdminSdmController::class, 'dashboardGaji'])->name('direktur.dashboard_gaji');

    // Direktur : Kepegawaian
    Route::get('/direktur/kepegawaian', [KepegawaianController::class, 'index']);
    Route::get('/direktur/detail_kepegawaian/{id}', [KepegawaianController::class, 'show']);

    // Direktur : Absensi Harian
    Route::get('/direktur/absensi_harian', [AbsensiHarianController::class, 'index'])->name('direktur.absensi_harian.index');
    Route::get('/direktur/absensi_harian/{id}', [AbsensiHarianController::class, 'show'])->name('direktur.absensi_harian.show');
    Route::post('/direktur/absensi_harian/store/{id}', [AbsensiHarianController::class, 'store'])->name('direktur.absensi_harian.store');
    Route::put('/direktur/absensi_harian/update/{pegawai_id}/{id}', [AbsensiHarianController::class, 'update'])->name('direktur.absensi_harian.update');
    Route::delete('/direktur/absensi_harian/delete/{id}', [AbsensiHarianController::class, 'destroy'])->name('direktur.absensi_harian.delete'); // Delete a role

    // Admin SDM : Gaji
    Route::get('/direktur/gaji', [GajiController::class, 'index'])->name('direktur.gaji.index');
    Route::post('/direktur/gaji/store', [GajiController::class, 'store'])->name('direktur.gaji.store');
    Route::put('/direktur/gaji/update/{id}', [GajiController::class, 'update'])->name('direktur.gaji.update');

    // Admin SDM : Gaji Bulanan
    Route::get('/direktur/gaji_bulanan', [GajiBulananController::class, 'index'])->name('direktur.gaji_bulanan.index');
    // Route::post('/direktur/gaji_bulanan/store', [GajiBulananController::class, 'store'])->name('direktur.gaji_bulanan.store');
    Route::put('/direktur/gaji_bulanan/update/{id}', [GajiBulananController::class, 'update'])->name('direktur.gaji_bulanan.update');
    Route::get('/direktur/gaji_bulanan/sync', [GajiBulananController::class, 'sync'])->name('direktur.gaji_bulanan.sync');

});

Route::middleware(['auth'])->group(function () {
    // sso
    Route::get('/sso/get', [LoginSSOController::class, 'index'])->name('sso');
    Route::post('/sso/store', [LoginSSOController::class, 'storeSSO'])->name('sso.store');

    Route::get('/get-kehadiran-data-value', [AdminSdmController::class, 'getDashboardKehadiranDataValue'])->name('admin_sdm.dashboard_kehadiran_data_value');
    Route::get('/get-kehadiran-data-percentage', [AdminSdmController::class, 'getDashboardKehadiranDataPercentage'])->name('admin_sdm.dashboard_kehadiran_data');
    Route::get('/get-kehadiran-data-value-per-hari', [AdminSdmController::class, 'getDashboardKehadiranDataValuePerHari'])->name('admin_sdm.dashboard_kehadiran_value_per_hari');
    Route::get('/get-kehadiran-data-percentage-per-hari', [AdminSdmController::class, 'getDashboardKehadiranDataPercentagePerHari'])->name('admin_sdm.dashboard_kehadiran_percentage_per_hari');

    Route::get('/absensi_verifikasi/store/{id}', [AbsensiHarianController::class, 'storeVerifikasi'])->name('admin_sdm.absensi_verifikasi.store');

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
