<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\HomeController;
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
    // Route Lapangan, Reservasi, Laporan, Jadwal menyusul
});