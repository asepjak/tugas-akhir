<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\AbsensiController;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Routes Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');
});

// Routes Pimpinan
Route::middleware(['auth', 'role:pimpinan'])->prefix('pimpinan')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'pimpinan'])->name('pimpinan.dashboard');
});

// Routes Karyawan
// Tambahkan route ini ke dalam grup middleware karyawan yang sudah ada
Route::middleware(['auth', 'role:karyawan'])->prefix('karyawan')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'karyawan'])->name('dashboard');
    Route::get('/permissions', [PermissionController::class, 'index'])->name('karyawan.permission.index');
    Route::get('/permissions/create', [PermissionController::class, 'create'])->name('karyawan.permission.create');
    Route::post('/permissions', [PermissionController::class, 'store'])->name('karyawan.permission.store');
    // Routes Absensi
    Route::get('/absensi', [AbsensiController::class, 'index'])->name('karyawan.absensi.index');
    Route::post('/absensi', [AbsensiController::class, 'store'])->name('absensi.store');
    Route::post('/absensi/keluar', [AbsensiController::class, 'keluar'])->name('absensi.keluar');
    Route::post('/absensi/masuk', [AbsensiController::class, 'store'])->name('absen.masuk');
    // Route untuk debugging IP (tambahan yang diperlukan)
    Route::get('/absensi/check-ip', [AbsensiController::class, 'checkIp'])->name('karyawan.absensi.check-ip');
    // Development routes (hanya untuk testing)
    // Route::post('/absensi/bypass', [AbsensiController::class, 'storeBypass'])->name('absensi.bypass');
    // Route::delete('/absensi/reset', [AbsensiController::class, 'reset'])->name('absensi.reset');
});


Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
