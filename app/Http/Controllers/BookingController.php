<?php

namespace App\Http\Controllers;

use App\Models\Lapangan;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Route Model Binding: Laravel otomatis ambil row `lapangans`
     * berdasarkan {lapangan} di URL (dicocokkan ke kolom `id`).
     * Kalau id gak ketemu di DB → otomatis 404, gak perlu findOrFail() manual.
     */
    public function show(Lapangan $lapangan)
    {
        // Nanti setelah tabel reservasis ada, tambahkan di sini:
        // $tanggal = request('tanggal', now()->toDateString());
        // $bookedIndexes = $lapangan->reservasis()
        //     ->where('tanggal', $tanggal)
        //     ->pluck('slot_index');

        return view('booking.show', compact('lapangan'));
    }

    public function store(Request $request, Lapangan $lapangan)
    {
        // Nanti: validasi input, cari-atau-buat pelanggan by email
        // (auto-create akun dengan password default kalau belum ada),
        // insert ke tabel reservasis dengan status 'menunggu_verifikasi',
        // balikin nomor pesanan asli dari DB (bukan generate di JS lagi).
    }
}