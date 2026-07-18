<?php
use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/booking/{lapangan}', [BookingController::class, 'show'])->name('booking.show');