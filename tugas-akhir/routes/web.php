<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

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
Route::middleware(['auth', 'role:karyawan'])->prefix('karyawan')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'karyawan'])->name('karyawan.dashboard');
});
