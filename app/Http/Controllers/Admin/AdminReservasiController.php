<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lapangan;
use App\Models\Reservasi;
use Illuminate\Http\Request;

class AdminReservasiController extends Controller
{
    public function index(Request $request)
    {
        $query = Reservasi::with('lapangan', 'user')->latest();

        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where(function ($q) use ($cari) {
                $q->where('nomor_pesanan', 'like', "%{$cari}%")
                  ->orWhereHas('user', function ($q2) use ($cari) {
                      $q2->where('name', 'like', "%{$cari}%")
                         ->orWhere('email', 'like', "%{$cari}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('lapangan_id')) {
            $query->where('lapangan_id', $request->lapangan_id);
        }
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        $reservasis = $query->paginate(15)->withQueryString();
        $lapangans  = Lapangan::orderBy('nama')->get();

        return view('admin.reservasi.index', compact('reservasis', 'lapangans'));
    }

    /**
     * Validasi/ubah status reservasi — ini juga yang berfungsi sebagai
     * "kelola transaksi", karena transaksi nempel langsung ke reservasi
     * (gak ada tabel transaksi terpisah).
     */
    public function updateStatus(Request $request, Reservasi $reservasi)
    {
        $validated = $request->validate([
            'status'         => ['required', 'in:dikonfirmasi,ditolak,selesai,dibatalkan'],
            'catatan_admin'  => ['nullable', 'string', 'max:500'],
        ]);

        $reservasi->update($validated);

        return back()->with('success', 'Status reservasi ' . $reservasi->nomor_pesanan . ' diperbarui.');
    }
}