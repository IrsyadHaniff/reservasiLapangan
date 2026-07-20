<?php

namespace App\Http\Controllers;

use App\Models\Lapangan;
use App\Models\Reservasi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class BookingController extends Controller
{
    public function show(Request $request, Lapangan $lapangan)
    {
        $tanggal = $request->query('tanggal', now()->toDateString());

        $bookedIndexes = $lapangan->slotTerpakaiPada($tanggal);

        return view('booking.show', compact('lapangan', 'tanggal', 'bookedIndexes'));
    }

    public function store(Request $request, Lapangan $lapangan)
    {
        // 23 slot jam operasional per hari: 06:00-23:00, lanjut 00:00-04:00
        // besok paginya. Jam 05:00 sengaja gak ada di daftar ini (tutup).
        $jamValid = array_merge(
            array_map(fn ($h) => sprintf('%02d:00', $h), range(6, 23)),
            array_map(fn ($h) => sprintf('%02d:00', $h), range(0, 4))
        );

        $validated = $request->validate([
            'nama'               => ['required', 'string', 'max:255'],
            'email'              => ['required', 'email', 'max:255'],
            'no_hp'              => ['required', 'string', 'max:20'],
            'tanggal'            => ['required', 'date', 'after_or_equal:today'],
            'jam_mulai'          => ['required', 'date_format:H:i', Rule::in($jamValid)],
            'durasi_jam'         => ['required', 'integer', 'min:1', 'max:6'],
            'metode_pembayaran'  => ['required', 'in:qris,va_bca'],
        ]);

        $jamMulaiJam = (int) substr($validated['jam_mulai'], 0, 2);

        // Jam 00:00-04:00 itu bagian dari "hari operasional" tanggal yang dipilih,
        // tapi secara kalender sebenarnya jatuh di tanggal BESOKnya.
        $mulaiAt = $jamMulaiJam >= 6
            ? Carbon::parse($validated['tanggal'] . ' ' . $validated['jam_mulai'])
            : Carbon::parse($validated['tanggal'])->addDay()->setTimeFromTimeString($validated['jam_mulai']);

        $selesaiAt = $mulaiAt->copy()->addHours($validated['durasi_jam']);

        // Validasi ulang di server — jangan percaya slot yang dipilih di frontend.
        if (! $lapangan->isTersediaPada($mulaiAt, $selesaiAt)) {
            return response()->json([
                'message' => 'Yah, jam yang kamu pilih baru saja terisi. Coba pilih jam lain ya.',
            ], 409);
        }

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
            'nama_lapangan'      => $lapangan->nama,
            'user_id'            => $user->id,
            'tanggal'            => $validated['tanggal'], // tanggal operasional yang dipilih pelanggan
            'jam_mulai'          => $mulaiAt->format('H:i:s'),
            'jam_selesai'        => $selesaiAt->format('H:i:s'),
            'mulai_at'           => $mulaiAt,
            'selesai_at'         => $selesaiAt,
            'durasi_jam'         => $validated['durasi_jam'],
            'harga_per_jam'      => $lapangan->harga_per_jam,
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