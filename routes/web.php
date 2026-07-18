<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::name('booking.')->prefix('booking')->group(function () {
    Route::get('/{lapangan}', [BookingController::class, 'show'])->name('show');
    Route::post('/{lapangan}', [BookingController::class, 'store'])->name('store');
});