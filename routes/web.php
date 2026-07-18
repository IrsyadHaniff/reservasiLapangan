<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminJadwalController;
use App\Http\Controllers\Admin\AdminLapanganController;
use App\Http\Controllers\Admin\AdminLaporanController;
use App\Http\Controllers\Admin\AdminReservasiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Pelanggan\PelangganDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::name('booking.')->prefix('booking')->group(function () {
    Route::get('/{lapangan}', [BookingController::class, 'show'])->name('show');
    Route::post('/{lapangan}', [BookingController::class, 'store'])->name('store');
});

// ===== Auth (dipakai bareng admin & pelanggan) =====
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
 

// ===== Admin =====
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
 
    Route::resource('lapangan', AdminLapanganController::class)->except('show');
    Route::patch('lapangan/{lapangan}/toggle-aktif', [AdminLapanganController::class, 'toggleAktif'])->name('lapangan.toggle-aktif');
 
    Route::get('reservasi', [AdminReservasiController::class, 'index'])->name('reservasi.index');
    Route::patch('reservasi/{reservasi}/status', [AdminReservasiController::class, 'updateStatus'])->name('reservasi.update-status');
 
    Route::get('laporan', [AdminLaporanController::class, 'index'])->name('laporan.index');
 
    Route::get('jadwal', [AdminJadwalController::class, 'index'])->name('jadwal.index');
});

// ===== Pelanggan =====
Route::middleware(['auth', 'role:pelanggan'])->prefix('dashboard')->name('pelanggan.')->group(function () {
    Route::get('/', [PelangganDashboardController::class, 'index'])->name('dashboard');
});