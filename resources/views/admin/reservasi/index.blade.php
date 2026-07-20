@extends('admin.layouts.app')

@section('title', 'Kelola Reservasi - Admin')
@section('page-title', 'Kelola Reservasi')

@section('content')

    {{-- Filter --}}
    <form method="GET" class="flex flex-wrap gap-3 mb-5">
        <label class="relative">
            <span class="sr-only">Cari nomor pesanan / pelanggan</span>
            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
            </svg>
            <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari no. pesanan / nama / email..."
                   class="pl-10 pr-4 py-2.5 rounded-xl border border-gray-300 text-sm w-64 focus:outline-none focus:ring-2 focus:ring-brand">
        </label>

        <select name="status" onchange="this.form.submit()"
                class="px-4 py-2.5 rounded-xl border border-gray-300 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-brand">
            <option value="">Semua Status</option>
            @foreach (['menunggu_verifikasi' => 'Menunggu Verifikasi', 'dikonfirmasi' => 'Dikonfirmasi', 'ditolak' => 'Ditolak', 'selesai' => 'Selesai', 'dibatalkan' => 'Dibatalkan'] as $value => $label)
                <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>

        <select name="lapangan_id" onchange="this.form.submit()"
                class="px-4 py-2.5 rounded-xl border border-gray-300 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-brand">
            <option value="">Semua Lapangan</option>
            @foreach ($lapangans as $lapangan)
                <option value="{{ $lapangan->id }}" {{ (string) request('lapangan_id') === (string) $lapangan->id ? 'selected' : '' }}>{{ $lapangan->nama }}</option>
            @endforeach
        </select>

        <input type="date" name="tanggal" value="{{ request('tanggal') }}" onchange="this.form.submit()"
               class="px-4 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-brand">

        <button type="submit" class="px-4 py-2.5 rounded-xl bg-brand-black text-white text-sm font-medium hover:bg-brand-dark transition-colors">
            Cari
        </button>

        @if (request()->hasAny(['cari', 'status', 'lapangan_id', 'tanggal']))
            <a href="{{ route('admin.reservasi.index') }}" class="px-4 py-2.5 text-sm text-gray-500 hover:text-brand-dark">Reset filter</a>
        @endif
    </form>

    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-500 border-b border-gray-100">
                        <th class="px-5 py-3 font-medium">No. Pesanan</th>
                        <th class="px-5 py-3 font-medium">Lapangan</th>
                        <th class="px-5 py-3 font-medium">Pelanggan</th>
                        <th class="px-5 py-3 font-medium">Jadwal</th>
                        <th class="px-5 py-3 font-medium">Total</th>
                        <th class="px-5 py-3 font-medium">Metode</th>
                        <th class="px-5 py-3 font-medium">Status</th>
                        <th class="px-5 py-3 font-medium text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($reservasis as $reservasi)
                        @php
                            $statusStyle = match ($reservasi->status) {
                                'menunggu_verifikasi' => 'bg-amber-50 text-amber-600',
                                'dikonfirmasi', 'selesai' => 'bg-court-available/15 text-court-available',
                                'ditolak', 'dibatalkan' => 'bg-court-booked/15 text-court-booked',
                                default => 'bg-gray-100 text-gray-500',
                            };
                        @endphp
                        <tr class="hover:bg-gray-50" x-data="{ open: false }">
                            <td class="px-5 py-3 font-mono text-xs text-brand-black">{{ $reservasi->nomor_pesanan }}</td>
                            <td class="px-5 py-3 text-gray-700">{{ $reservasi->nama_lapangan }}</td>
                            <td class="px-5 py-3 text-gray-700">
                                {{ $reservasi->user->name }}
                                <span class="block text-xs text-gray-400">{{ $reservasi->user->email }}</span>
                            </td>
                            <td class="px-5 py-3 text-gray-600">
                                {{ $reservasi->tanggal->format('d M Y') }}
                                <span class="block text-xs text-gray-400">
                                    {{ \Illuminate\Support\Carbon::parse($reservasi->jam_mulai)->format('H:i') }} - {{ \Illuminate\Support\Carbon::parse($reservasi->jam_selesai)->format('H:i') }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-gray-700">Rp{{ number_format($reservasi->total_harga, 0, ',', '.') }}</td>
                            <td class="px-5 py-3 text-gray-600 uppercase text-xs">{{ str_replace('_', ' ', $reservasi->metode_pembayaran) }}</td>
                            <td class="px-5 py-3">
                                <span class="inline-block px-2.5 py-1 rounded-full text-xs font-medium {{ $statusStyle }}">
                                    {{ str_replace('_', ' ', ucfirst($reservasi->status)) }}
                                </span>
                            </td>
                            <td class="px-5 py-3 relative">
                                <button @click="open = !open" class="text-brand-dark hover:underline text-xs font-medium">
                                    Ubah Status
                                </button>
                                <div x-show="open" x-cloak @click.outside="open = false"
                                     class="absolute right-5 top-10 z-10 w-56 bg-white border border-gray-200 rounded-xl shadow-lg p-3 text-left">
                                    <form method="POST" action="{{ route('admin.reservasi.update-status', $reservasi) }}" class="space-y-2">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" required class="w-full px-3 py-2 rounded-lg border border-gray-300 text-xs">
                                            <option value="dikonfirmasi" {{ $reservasi->status === 'dikonfirmasi' ? 'selected' : '' }}>Konfirmasi</option>
                                            <option value="ditolak" {{ $reservasi->status === 'ditolak' ? 'selected' : '' }}>Tolak</option>
                                            <option value="selesai" {{ $reservasi->status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                                            <option value="dibatalkan" {{ $reservasi->status === 'dibatalkan' ? 'selected' : '' }}>Batalkan</option>
                                        </select>
                                        <textarea name="catatan_admin" rows="2" placeholder="Catatan (opsional)"
                                                  class="w-full px-3 py-2 rounded-lg border border-gray-300 text-xs">{{ $reservasi->catatan_admin }}</textarea>
                                        <button type="submit" class="w-full py-2 rounded-lg bg-brand text-brand-black text-xs font-semibold hover:bg-[#4fd43f]">
                                            Simpan
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-gray-400 py-10">Belum ada reservasi yang cocok dengan filter ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-5">{{ $reservasis->links() }}</div>

@endsection