<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lapangan;
use App\Models\Reservasi;
use Illuminate\Http\Request;

class AdminLaporanController extends Controller
{
    public function index(Request $request)
    {
        $dariTanggal   = $request->input('dari', now()->startOfMonth()->toDateString());
        $sampaiTanggal = $request->input('sampai', now()->toDateString());
        $lapanganId    = $request->input('lapangan_id');

        $query = Reservasi::with('lapangan')
            ->whereIn('status', [Reservasi::STATUS_DIKONFIRMASI, Reservasi::STATUS_SELESAI])
            ->whereBetween('tanggal', [$dariTanggal, $sampaiTanggal]);

        if ($lapanganId) {
            $query->where('lapangan_id', $lapanganId);
        }

        $reservasis = $query->orderBy('tanggal')->get();

        $totalPendapatan  = $reservasis->sum('total_harga');
        $totalJamTerpakai = $reservasis->sum('durasi_jam');
        $totalTransaksi   = $reservasis->count();

        $lapangans = Lapangan::orderBy('nama')->get();

        return view('admin.laporan.index', compact(
            'reservasis', 'dariTanggal', 'sampaiTanggal', 'lapanganId',
            'totalPendapatan', 'totalJamTerpakai', 'totalTransaksi', 'lapangans'
        ));
    }
}