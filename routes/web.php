<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PelaporanController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminLaporanController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\KategoriController;

// Redirect root ke halaman login ganda (default: siswa)
Route::redirect('/', '/login/siswa');

// === LOGIN GANDA (HANYA INI YANG DIPAKAI) ===
Route::get('/login/{type?}', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login/siswa', [AuthController::class, 'loginSiswa'])->name('login.siswa');
Route::post('/login/admin', [AuthController::class, 'loginAdmin'])->name('login.admin');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/logout', [AuthController::class, 'logout']); // opsional, untuk testing

// === DASHBOARD ===
Route::get('/dashboard/siswa', [DashboardController::class, 'index'])
    ->name('dashboard.siswa')
    ->middleware('role:siswa');

Route::get('/dashboard/admin', [AdminDashboardController::class, 'index'])
    ->name('dashboard.admin')
    ->middleware('role:admin');

Route::get('/riwayat', [DashboardController::class, 'riwayat'])
    ->name('riwayat.siswa')
    ->middleware('role:siswa');

// === DETAIL LAPORAN (UNTUK SEMUA ROLE) ===
// Ini harus DI LUAR group middleware spesifik
Route::get('/laporan/{id_pelaporan}', [PelaporanController::class, 'show'])->name('pelaporan.show');

// === LAPORAN SISWA ===
Route::prefix('laporan/sarana')->middleware('role:siswa')->group(function () {
    Route::get('/buat', [PelaporanController::class, 'create'])->name('pelaporan.create');
    Route::get('/sarana', [PelaporanController::class, 'create'])->name('pelaporan.sarana');
    Route::post('/', [PelaporanController::class, 'store'])->name('pelaporan.store');
    Route::get('/{id_pelaporan}/edit', [PelaporanController::class, 'edit'])->name('pelaporan.edit');
    Route::put('/{id_pelaporan}', [PelaporanController::class, 'update'])->name('pelaporan.update');
    Route::delete('/{id_pelaporan}', [PelaporanController::class, 'destroy'])->name('pelaporan.destroy');
});

// === ADMIN ROUTES (SEMUA DILINDUNGI MIDDLEWARE) ===
Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {

    // Manajemen Laporan
    Route::get('/laporan', [AdminLaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/masuk', [AdminLaporanController::class, 'masuk'])->name('laporan.masuk');
    Route::get('/laporan/diproses', [AdminLaporanController::class, 'diproses'])->name('laporan.diproses');
    Route::get('/laporan/selesai', [AdminLaporanController::class, 'selesai'])->name('laporan.selesai');
    Route::get('/laporan/{id}/status/{status}', [AdminLaporanController::class, 'updateStatus'])->name('laporan.status');
    Route::delete('/laporan/{id}', [AdminLaporanController::class, 'destroy'])->name('laporan.destroy');

    // Manajemen Data Siswa
    Route::resource('siswa', SiswaController::class)->except(['show']);

    // Manajemen Data Kategori
    Route::resource('kategori', KategoriController::class)->except(['show']);
});
