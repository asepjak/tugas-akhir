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
    AdminDashboardController,
    UserManagementController,
    PimpinanDashboardController,
    BonusController,
    PermissionApprovalController,
    PimpinanRekapController,
};

Route::get('/', fn() => redirect('/login'));
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Routes Pimpinan
Route::middleware(['auth', 'role:pimpinan'])->prefix('pimpinan')->group(function () {
    Route::get('/dashboard', [PimpinanDashboardController::class, 'index'])->name('pimpinan.dashboard');
    Route::get('/bonus', [BonusController::class, 'index'])->name('pimpinan.bonus.index');
    Route::post('/bonus', [BonusController::class, 'store'])->name('pimpinan.bonus.store');
    Route::put('/bonus/{id}', [BonusController::class, 'update'])->name('pimpinan.bonus.update');
    Route::delete('/bonus/{id}', [BonusController::class, 'destroy'])->name('pimpinan.bonus.destroy');
    Route::post('/bonus/calculate', [BonusController::class, 'calculateBonus'])->name('pimpinan.bonus.calculate');
    Route::get('/permissions', [PermissionApprovalController::class, 'index'])->name('pimpinan.permissions.index');
    Route::put('/permissions/{id}/status', [PermissionApprovalController::class, 'updateStatus'])->name('pimpinan.permissions.updateStatus');
    Route::get('/permissions/riwayat', [PermissionApprovalController::class, 'riwayat'])->name('pimpinan.permissions.riwayat');
    Route::get('/rekap/bulanan', [PimpinanRekapController::class, 'bulanan'])->name('pimpinan.rekap.bulanan');
    Route::get('/rekap/export', [PimpinanRekapController::class, 'exportBulanan'])->name('pimpinan.rekap.export');
    Route::get('/rekap/print', [PimpinanRekapController::class, 'print'])->name('pimpinan.rekap.print');
    Route::get('/rekap/detail', [PimpinanRekapController::class, 'getDetailIzinSakit'])->name('pimpinan.rekap.detail');

    // Profile untuk Pimpinan
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('pimpinan.profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('pimpinan.profile.update');
});

// Routes Karyawan
Route::middleware(['auth', 'role:karyawan'])->prefix('karyawan')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'karyawan'])->name('dashboard');

    // Profile untuk Karyawan
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('karyawan.profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('karyawan.profile.update');

    // Permissions
    Route::get('/permissions', [PermissionController::class, 'index'])->name('karyawan.permissions.index');
    Route::get('/permissions/create', [PermissionController::class, 'create'])->name('karyawan.permission.create');
    Route::post('/permissions', [PermissionController::class, 'store'])->name('karyawan.permission.store');
    Route::delete('permissions/{id}', [PermissionController::class, 'destroy'])->name('karyawan.permissions.destroy');
    Route::get('/karyawan/permissions/print/{id}', [PermissionController::class, 'print'])->name('karyawan.permissions.print');

    // Absensi
    Route::get('/absensi', [AbsensiController::class, 'index'])->name('karyawan.absensi.index');
    Route::post('/absensi', [AbsensiController::class, 'store'])->name('absensi.store');
    Route::post('/absensi/keluar', [AbsensiController::class, 'keluar'])->name('absensi.keluar');
    Route::get('/absensi/check-ip', [AbsensiController::class, 'checkIp'])->name('karyawan.absensi.check-ip');
    Route::get('/karyawan/surat-permission/{id}/print', [PermissionController::class, 'print'])->name('karyawan.permissions.print');
});

// Routes Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // Profile untuk Admin
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('admin.profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('admin.profile.update');

    // Verifikasi Perizinan
    // Route::get('/verifikasi', [VerifikasiPerizinanController::class, 'index'])->name('verifikasi.index');
    // Route::get('/verifikasi/create', [VerifikasiPerizinanController::class, 'create'])->name('verifikasi.create');
    // Route::post('/verifikasi/store', [VerifikasiPerizinanController::class, 'store'])->name('verifikasi.store');
    Route::patch('/permissions/{id}/status', [VerifikasiPerizinanController::class, 'updateStatus'])->name('permissions.updateStatus');
    // Verifikasi izin (admin)
    Route::get('/verifikasi/permissions', [VerifikasiPerizinanController::class, 'permissions'])->name('verifikasi.permissions');
    Route::get('/admin/rekap/cetak', [RekapAbsensiController::class, 'print'])->name('admin.rekap.bulanan.print');
    // Route::put('/verifikasi/permissions/{id}', [VerifikasiPerizinanController::class, 'updateStatus'])->name('verifikasi.updateStatus');

    Route::post('/users/{user}/reset-token', [UserManagementController::class, 'resetToken'])->name('admin.users.resetToken');

    // Lihat semua data izin karyawan
    Route::get('/permissions', [VerifikasiPerizinanController::class, 'permissions'])->name('verifikasi.permissions');

    // Rekap Absensi
    Route::get('rekap', [RekapAbsensiController::class, 'index'])->name('admin.rekap.index');
    Route::get('rekap/create', [RekapAbsensiController::class, 'create'])->name('admin.rekap.create');
    Route::post('rekap/store', [RekapAbsensiController::class, 'store'])->name('admin.rekap.store');

    Route::get('rekap/bulanan', [RekapAbsensiController::class, 'bulanan'])->name('admin.rekap.bulanan');
    Route::get('/admin/rekap/export', [RekapAbsensiController::class, 'exportBulanan'])->name('admin.rekap.export');

    //user management
    Route::get('/users', [UserManagementController::class, 'index'])->name('admin.users.index');
    Route::get('/users/create', [UserManagementController::class, 'create'])->name('admin.users.create');
    Route::post('/users', [UserManagementController::class, 'store'])->name('admin.users.store');
    Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('admin.users.update');

    //absensi
    Route::get('/absensi', [AbsensiController::class, 'index'])->name('admin.absensi.index');
    Route::post('/absensi', [AbsensiController::class, 'store'])->name('admin.absensi.store');
    Route::post('/absensi/keluar', [AbsensiController::class, 'keluar'])->name('admin.absensi.keluar');
    Route::get('/absensi/check-ip', [AbsensiController::class, 'checkIp'])->name('admin.absensi.check-ip');
    Route::delete('/absensi/reset', [AbsensiController::class, 'reset'])->name('admin.absensi.reset');
    Route::get('/absensi/rekap', [AbsensiController::class, 'rekap'])->name('admin.absensi.rekap');
});

// Universal Profile Routes (fallback untuk semua role)
// Route::middleware(['auth'])->group(function () {
//     Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('universal.profile.edit');
//     Route::post('/profile/update', [ProfileController::class, 'update'])->name('universal.profile.update');
// });
