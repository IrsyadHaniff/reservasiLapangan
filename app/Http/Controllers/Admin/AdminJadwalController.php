<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lapangan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AdminJadwalController extends Controller
{
    public function index(Request $request)
    {
        $tanggal = $request->input('tanggal', now()->toDateString());

        $slots = [];
        for ($h = 6; $h < 24; $h++) {
            $slots[] = $h;
        }

        $lapangans = Lapangan::with(['reservasis' => function ($query) use ($tanggal) {
            $query->aktif()->where('tanggal', $tanggal);
        }])->orderBy('kategori')->orderBy('nama')->get();

        // Precompute jam-jam mana aja yang kepakai per lapangan, biar view tinggal cek array
        $jamTerpakai = $lapangans->mapWithKeys(function ($lapangan) {
            $jamSet = [];
            foreach ($lapangan->reservasis as $reservasi) {
                $awal  = (int) Carbon::parse($reservasi->jam_mulai)->format('H');
                $akhir = (int) Carbon::parse($reservasi->jam_selesai)->format('H');
                for ($h = $awal; $h < $akhir; $h++) {
                    $jamSet[$h] = $reservasi->nomor_pesanan;
                }
            }
            return [$lapangan->id => $jamSet];
        });

        return view('admin.jadwal.index', compact('lapangans', 'slots', 'tanggal', 'jamTerpakai'));
    }
}