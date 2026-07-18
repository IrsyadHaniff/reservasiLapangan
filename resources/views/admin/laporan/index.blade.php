@extends('admin.layouts.app')

@section('title', 'Laporan Penggunaan - Admin')
@section('page-title', 'Laporan Penggunaan Lapangan')

@section('content')

    {{-- Filter --}}
    <form method="GET" class="flex flex-wrap items-end gap-3 mb-5 no-print">
        <div>
            <label class="block text-xs text-gray-500 mb-1">Dari Tanggal</label>
            <input type="date" name="dari" value="{{ $dariTanggal }}"
                   class="px-4 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-brand">
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">Sampai Tanggal</label>
            <input type="date" name="sampai" value="{{ $sampaiTanggal }}"
                   class="px-4 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-brand">
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">Lapangan</label>
            <select name="lapangan_id" class="px-4 py-2.5 rounded-xl border border-gray-300 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-brand">
                <option value="">Semua Lapangan</option>
                @foreach ($lapangans as $lapangan)
                    <option value="{{ $lapangan->id }}" {{ (string) $lapanganId === (string) $lapangan->id ? 'selected' : '' }}>{{ $lapangan->nama }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="px-5 py-2.5 rounded-full bg-brand text-brand-black text-sm font-semibold hover:bg-[#4fd43f]">
            Terapkan
        </button>
        <button type="button" onclick="window.print()"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full border border-gray-300 text-gray-700 text-sm font-semibold hover:border-brand-dark hover:text-brand-dark ml-auto">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z" />
            </svg>
            Cetak
        </button>
    </form>

    {{-- Header cetak (cuma keliatan pas print) --}}
    <div class="hidden print:block mb-6">
        <h1 class="font-display font-bold text-xl text-brand-black">Laporan Penggunaan Lapangan — SM-SPORT CENTER</h1>
        <p class="text-sm text-gray-500">Periode: {{ \Illuminate\Support\Carbon::parse($dariTanggal)->format('d M Y') }} — {{ \Illuminate\Support\Carbon::parse($sampaiTanggal)->format('d M Y') }}</p>
    </div>

    {{-- Ringkasan --}}
    <div class="grid sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white border border-gray-200 rounded-2xl p-5">
            <p class="text-sm text-gray-500 mb-1">Total Transaksi</p>
            <p class="font-display font-bold text-2xl text-brand-black">{{ $totalTransaksi }}</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-2xl p-5">
            <p class="text-sm text-gray-500 mb-1">Total Jam Terpakai</p>
            <p class="font-display font-bold text-2xl text-brand-black">{{ $totalJamTerpakai }} jam</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-2xl p-5">
            <p class="text-sm text-gray-500 mb-1">Total Pendapatan</p>
            <p class="font-display font-bold text-2xl text-brand-dark">Rp{{ number_format($totalPendapatan, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-500 border-b border-gray-100">
                        <th class="px-5 py-3 font-medium">No. Pesanan</th>
                        <th class="px-5 py-3 font-medium">Lapangan</th>
                        <th class="px-5 py-3 font-medium">Tanggal</th>
                        <th class="px-5 py-3 font-medium">Jam</th>
                        <th class="px-5 py-3 font-medium">Durasi</th>
                        <th class="px-5 py-3 font-medium">Total</th>
                        <th class="px-5 py-3 font-medium">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($reservasis as $reservasi)
                        <tr>
                            <td class="px-5 py-3 font-mono text-xs text-brand-black">{{ $reservasi->nomor_pesanan }}</td>
                            <td class="px-5 py-3 text-gray-700">{{ $reservasi->nama_lapangan }}</td>
                            <td class="px-5 py-3 text-gray-600">{{ $reservasi->tanggal->format('d M Y') }}</td>
                            <td class="px-5 py-3 text-gray-600">{{ \Illuminate\Support\Carbon::parse($reservasi->jam_mulai)->format('H:i') }}</td>
                            <td class="px-5 py-3 text-gray-600">{{ $reservasi->durasi_jam }} jam</td>
                            <td class="px-5 py-3 text-gray-700">Rp{{ number_format($reservasi->total_harga, 0, ',', '.') }}</td>
                            <td class="px-5 py-3 text-gray-600 capitalize">{{ str_replace('_', ' ', $reservasi->status) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-gray-400 py-10">Gak ada data di periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection