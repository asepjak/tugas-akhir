<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController,
    DashboardController,
    ProfileController,
    PermissionController,
    AbsensiController,
    VerifikasiPerizinanController,
    RekapAbsensiController,
    AdminDashboardController
};

Route::get('/', fn() => redirect('/login'));
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Routes Pimpinan
Route::middleware(['auth', 'role:pimpinan'])->prefix('pimpinan')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'pimpinan'])->name('pimpinan.dashboard');

    // Profile untuk Pimpinan
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('pimpinan.profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('pimpinan.profile.update');
});

// Routes Karyawan
Route::middleware(['auth', 'role:karyawan'])->prefix('karyawan')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'karyawan'])->name('dashboard');

    // Profile untuk Karyawan
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // Permissions
    Route::get('/permissions', [PermissionController::class, 'index'])->name('karyawan.permission.index');
    Route::get('/permissions/create', [PermissionController::class, 'create'])->name('karyawan.permission.create');
    Route::post('/permissions', [PermissionController::class, 'store'])->name('karyawan.permission.store');

    // Absensi
    Route::get('/absensi', [AbsensiController::class, 'index'])->name('karyawan.absensi.index');
    Route::post('/absensi', [AbsensiController::class, 'store'])->name('absensi.store');
    Route::post('/absensi/keluar', [AbsensiController::class, 'keluar'])->name('absensi.keluar');
    Route::get('/absensi/check-ip', [AbsensiController::class, 'checkIp'])->name('karyawan.absensi.check-ip');
});

// Routes Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // Profile untuk Admin
    // Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // Verifikasi Perizinan
    Route::get('/verifikasi', [VerifikasiPerizinanController::class, 'index'])->name('verifikasi.index');
    Route::get('/verifikasi/create', [VerifikasiPerizinanController::class, 'create'])->name('verifikasi.create');
    Route::post('/verifikasi/store', [VerifikasiPerizinanController::class, 'store'])->name('verifikasi.store');
    Route::patch('/permissions/{id}/status', [VerifikasiPerizinanController::class, 'updateStatus'])->name('permissions.updateStatus');

    // Lihat semua data izin karyawan
    Route::get('/permissions', [VerifikasiPerizinanController::class, 'permissions'])->name('verifikasi.permissions');

    // Rekap Absensi
    Route::get('rekap', [RekapAbsensiController::class, 'index'])->name('admin.rekap.index');
    Route::get('rekap/create', [RekapAbsensiController::class, 'create'])->name('admin.rekap.create');
    Route::post('rekap/store', [RekapAbsensiController::class, 'store'])->name('admin.rekap.store');

    Route::get('rekap/bulanan', [RekapAbsensiController::class, 'bulanan'])->name('admin.rekap.bulanan');
    Route::get('/admin/rekap/export', [RekapAbsensiController::class, 'exportBulanan'])->name('admin.rekap.export');
});

// Universal Profile Routes (fallback untuk semua role)
// Route::middleware(['auth'])->group(function () {
//     Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('universal.profile.edit');
//     Route::post('/profile/update', [ProfileController::class, 'update'])->name('universal.profile.update');
// });
