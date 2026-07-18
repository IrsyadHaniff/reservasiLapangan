@extends('pelanggan.layouts.app')

@section('title', 'Riwayat Reservasi - SM-SPORT CENTER')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <h1 class="font-display font-bold text-2xl text-brand-black">Riwayat Reservasi</h1>
        <a href="{{ url('/#reservasi') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-brand text-brand-black text-sm font-semibold hover:bg-[#4fd43f] transition-colors duration-200">
            Booking Lagi
        </a>
    </div>

    {{-- Filter status --}}
    <div class="flex flex-wrap gap-2 mb-6">
        @php
            $filters = [
                '' => 'Semua',
                'menunggu_verifikasi' => 'Menunggu Verifikasi',
                'dikonfirmasi' => 'Dikonfirmasi',
                'selesai' => 'Selesai',
                'ditolak' => 'Ditolak',
                'dibatalkan' => 'Dibatalkan',
            ];
        @endphp
        @foreach ($filters as $value => $label)
            <a href="{{ route('pelanggan.dashboard', $value ? ['status' => $value] : []) }}"
               class="px-4 py-2 rounded-full text-xs font-medium transition-colors duration-150
                   {{ request('status', '') === $value ? 'bg-brand-black text-white' : 'bg-white border border-gray-200 text-gray-600 hover:border-brand-dark' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    {{-- List reservasi --}}
    @if ($reservasis->isEmpty())
        <div class="bg-white border border-gray-200 rounded-2xl p-10 text-center">
            <p class="text-gray-500 font-medium">Belum ada reservasi.</p>
            <p class="text-gray-400 text-sm mt-1">Yuk booking lapangan pertamamu.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach ($reservasis as $reservasi)
                @php
                    $statusStyle = match ($reservasi->status) {
                        'menunggu_verifikasi' => 'bg-amber-50 text-amber-600',
                        'dikonfirmasi', 'selesai' => 'bg-court-available/15 text-court-available',
                        'ditolak', 'dibatalkan' => 'bg-court-booked/15 text-court-booked',
                        default => 'bg-gray-100 text-gray-500',
                    };
                    $statusLabel = str_replace('_', ' ', ucfirst($reservasi->status));
                @endphp
                <div class="bg-white border border-gray-200 rounded-2xl p-5">
                    <div class="flex items-start justify-between gap-4 flex-wrap">
                        <div>
                            <p class="font-mono text-xs text-gray-400 mb-1">{{ $reservasi->nomor_pesanan }}</p>
                            <h2 class="font-display font-bold text-brand-black">{{ $reservasi->nama_lapangan }}</h2>
                            <p class="text-sm text-gray-500 mt-1">
                                {{ $reservasi->tanggal->format('d M Y') }} ·
                                {{ \Illuminate\Support\Carbon::parse($reservasi->jam_mulai)->format('H:i') }} - {{ \Illuminate\Support\Carbon::parse($reservasi->jam_selesai)->format('H:i') }}
                                ({{ $reservasi->durasi_jam }} jam)
                            </p>
                        </div>
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-medium shrink-0 {{ $statusStyle }}">
                            {{ $statusLabel }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-100">
                        <p class="text-sm text-gray-500">
                            Metode: <span class="uppercase text-gray-700 font-medium">{{ str_replace('_', ' ', $reservasi->metode_pembayaran) }}</span>
                        </p>
                        <p class="font-display font-bold text-brand-dark">Rp{{ number_format($reservasi->total_harga, 0, ',', '.') }}</p>
                    </div>

                    @if ($reservasi->status === 'ditolak' && $reservasi->catatan_admin)
                        <p class="text-xs text-court-booked bg-court-booked/5 border border-court-booked/20 rounded-lg px-3 py-2 mt-3">
                            Catatan admin: {{ $reservasi->catatan_admin }}
                        </p>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="mt-6">{{ $reservasis->links() }}</div>
    @endif

@endsection