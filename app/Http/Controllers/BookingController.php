<?php

namespace App\Http\Controllers;

use App\Models\Lapangan;
use App\Models\Reservasi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class BookingController extends Controller
{
    public function show(Request $request, Lapangan $lapangan)
    {
        $tanggal = $request->query('tanggal', now()->toDateString());

        // Ambil slot jam (index 0 = 06:00) yang sudah kepakai reservasi aktif
        // di tanggal ini, biar grid pemilihan jam di frontend akurat.
        $bookedIndexes = $lapangan->reservasis()
            ->aktif()
            ->where('tanggal', $tanggal)
            ->get(['jam_mulai', 'jam_selesai'])
            ->flatMap(function ($reservasi) {
                $jamAwal   = (int) Carbon::parse($reservasi->jam_mulai)->format('H');
                $jamAkhir  = (int) Carbon::parse($reservasi->jam_selesai)->format('H');
                return range($jamAwal - 6, $jamAkhir - 6 - 1); // -6 karena slot dimulai jam 06:00
            })
            ->unique()
            ->values();

        return view('booking.show', compact('lapangan', 'tanggal', 'bookedIndexes'));
    }

    public function store(Request $request, Lapangan $lapangan)
    {
        $validated = $request->validate([
            'nama'               => ['required', 'string', 'max:255'],
            'email'              => ['required', 'email', 'max:255'],
            'no_hp'              => ['required', 'string', 'max:20'],
            'tanggal'            => ['required', 'date', 'after_or_equal:today'],
            'jam_mulai'          => ['required', 'date_format:H:i'],
            'durasi_jam'         => ['required', 'integer', 'min:1', 'max:6'],
            'metode_pembayaran'  => ['required', 'in:qris,va_bca'],
        ]);

        $jamMulai   = Carbon::createFromFormat('H:i', $validated['jam_mulai']);
        $jamSelesai = $jamMulai->copy()->addHours($validated['durasi_jam']);

        // Validasi ulang di server — JANGAN percaya slot yang dipilih di frontend,
        // karena bisa saja slot itu baru saja dibooking orang lain sebelum request ini masuk.
        $tersedia = $lapangan->isTersediaPada(
            $validated['tanggal'],
            $jamMulai->format('H:i:s'),
            $jamSelesai->format('H:i:s')
        );

        if (! $tersedia) {
            return response()->json([
                'message' => 'Yah, jam yang kamu pilih baru saja terisi. Coba pilih jam lain ya.',
            ], 409);
        }

        // Cari akun pelanggan berdasarkan email. Kalau belum ada, otomatis dibuatkan
        // dengan password default — ini yang bikin pelanggan bisa booking tanpa
        // daftar akun manual dulu.
        $user = User::firstOrCreate(
            ['email' => $validated['email']],
            [
                'name'     => $validated['nama'],
                'no_hp'    => $validated['no_hp'],
                'role'     => 'pelanggan',
                'password' => Hash::make('smsport262'),
            ]
        );

        $reservasi = Reservasi::create([
            'lapangan_id'        => $lapangan->id,
            'nama_lapangan'      => $lapangan->nama, // snapshot, sama alasannya dengan harga_per_jam
            'user_id'            => $user->id,
            'tanggal'            => $validated['tanggal'],
            'jam_mulai'          => $jamMulai->format('H:i:s'),
            'jam_selesai'        => $jamSelesai->format('H:i:s'),
            'durasi_jam'         => $validated['durasi_jam'],
            'harga_per_jam'      => $lapangan->harga_per_jam, // snapshot harga saat ini
            'total_harga'        => $lapangan->harga_per_jam * $validated['durasi_jam'],
            'metode_pembayaran'  => $validated['metode_pembayaran'],
            'status'             => Reservasi::STATUS_MENUNGGU,
        ]);

        return response()->json([
            'message'       => 'Booking berhasil dibuat.',
            'nomor_pesanan' => $reservasi->nomor_pesanan,
            'status'        => $reservasi->status,
            'akun_baru'     => $user->wasRecentlyCreated,
        ], 201);
    }
}