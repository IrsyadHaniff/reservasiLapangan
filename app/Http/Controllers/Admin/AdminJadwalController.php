<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lapangan;
use Illuminate\Http\Request;

class AdminJadwalController extends Controller
{
    public function index(Request $request)
    {
        $tanggal = $request->input('tanggal', now()->toDateString());

        // 23 slot jam: 06:00-23:00 lanjut 00:00-04:00 besok (tutup jam 05:00-06:00)
        $jamList = array_merge(range(6, 23), range(0, 4));

        $lapangans = Lapangan::orderBy('kategori')->orderBy('nama')->get();

        // slotTerpakaiPada() sudah pakai mulai_at/selesai_at (datetime), jadi
        // otomatis benar walau reservasinya nyebrang tengah malam.
        $slotTerpakai = $lapangans->mapWithKeys(fn($lapangan) => [
            $lapangan->id => $lapangan->slotTerpakaiPada($tanggal),
        ]);

        return view('admin.jadwal.index', compact('lapangans', 'jamList', 'tanggal', 'slotTerpakai'));
    }
}
