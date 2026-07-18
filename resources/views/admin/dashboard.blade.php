{{--
    Halaman: Dashboard Admin
    Lokasi: resources/views/admin/dashboard.blade.php
--}}

@extends('admin.layouts.app')

@section('title', 'Dashboard - Admin SM-SPORT CENTER')
@section('page-title', 'Dashboard')

@section('content')

    {{-- Kartu statistik --}}
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white border border-gray-200 rounded-2xl p-5">
            <p class="text-sm text-gray-500 mb-1">Total Lapangan</p>
            <p class="font-display font-bold text-2xl text-brand-black">{{ $totalLapangan }}</p>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl p-5 {{ $menungguVerifikasi > 0 ? 'ring-1 ring-amber-300' : '' }}">
            <p class="text-sm text-gray-500 mb-1">Menunggu Verifikasi</p>
            <p class="font-display font-bold text-2xl {{ $menungguVerifikasi > 0 ? 'text-amber-500' : 'text-brand-black' }}">
                {{ $menungguVerifikasi }}
            </p>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl p-5">
            <p class="text-sm text-gray-500 mb-1">Reservasi Hari Ini</p>
            <p class="font-display font-bold text-2xl text-brand-black">{{ $reservasiHariIni }}</p>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl p-5">
            <p class="text-sm text-gray-500 mb-1">Pendapatan Bulan Ini</p>
            <p class="font-display font-bold text-2xl text-brand-dark">Rp{{ number_format($pendapatanBulanIni, 0, ',', '.') }}</p>
        </div>
    </div>

    {{-- Reservasi terbaru --}}
    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="font-display font-bold text-brand-black">Reservasi Terbaru</h2>
        </div>

        @if ($reservasiTerbaru->isEmpty())
            <p class="text-sm text-gray-400 text-center py-10">Belum ada reservasi masuk.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-500 border-b border-gray-100">
                            <th class="px-5 py-3 font-medium">No. Pesanan</th>
                            <th class="px-5 py-3 font-medium">Lapangan</th>
                            <th class="px-5 py-3 font-medium">Pelanggan</th>
                            <th class="px-5 py-3 font-medium">Tanggal</th>
                            <th class="px-5 py-3 font-medium">Total</th>
                            <th class="px-5 py-3 font-medium">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($reservasiTerbaru as $reservasi)
                            @php
                                $statusStyle = match ($reservasi->status) {
                                    \App\Models\Reservasi::STATUS_MENUNGGU => 'bg-amber-50 text-amber-600',
                                    \App\Models\Reservasi::STATUS_DIKONFIRMASI => 'bg-court-available/15 text-court-available',
                                    \App\Models\Reservasi::STATUS_DITOLAK, \App\Models\Reservasi::STATUS_DIBATALKAN => 'bg-court-booked/15 text-court-booked',
                                    default => 'bg-gray-100 text-gray-500',
                                };
                                $statusLabel = match ($reservasi->status) {
                                    \App\Models\Reservasi::STATUS_MENUNGGU => 'Menunggu Verifikasi',
                                    \App\Models\Reservasi::STATUS_DIKONFIRMASI => 'Dikonfirmasi',
                                    \App\Models\Reservasi::STATUS_DITOLAK => 'Ditolak',
                                    \App\Models\Reservasi::STATUS_SELESAI => 'Selesai',
                                    \App\Models\Reservasi::STATUS_DIBATALKAN => 'Dibatalkan',
                                    default => $reservasi->status,
                                };
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-3 font-mono text-xs text-brand-black">{{ $reservasi->nomor_pesanan }}</td>
                                <td class="px-5 py-3 text-gray-700">{{ $reservasi->nama_lapangan }}</td>
                                <td class="px-5 py-3 text-gray-700">{{ $reservasi->user->name }}</td>
                                <td class="px-5 py-3 text-gray-500">{{ $reservasi->tanggal->format('d M Y') }}</td>
                                <td class="px-5 py-3 text-gray-700">Rp{{ number_format($reservasi->total_harga, 0, ',', '.') }}</td>
                                <td class="px-5 py-3">
                                    <span class="inline-block px-2.5 py-1 rounded-full text-xs font-medium {{ $statusStyle }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

@endsection