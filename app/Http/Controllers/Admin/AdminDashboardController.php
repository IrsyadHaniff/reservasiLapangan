<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lapangan;
use App\Models\Reservasi;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalLapangan       = Lapangan::count();
        $menungguVerifikasi  = Reservasi::where('status', Reservasi::STATUS_MENUNGGU)->count();
        $reservasiHariIni    = Reservasi::whereDate('tanggal', now())->count();
        $pendapatanBulanIni  = Reservasi::whereIn('status', [Reservasi::STATUS_DIKONFIRMASI, Reservasi::STATUS_SELESAI])
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('total_harga');

        $reservasiTerbaru = Reservasi::with('lapangan', 'user')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalLapangan',
            'menungguVerifikasi',
            'reservasiHariIni',
            'pendapatanBulanIni',
            'reservasiTerbaru'
        ));
    }
}