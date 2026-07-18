@extends('admin.layouts.app')

@section('title', 'Jadwal Lapangan - Admin')
@section('page-title', 'Jadwal Penggunaan Lapangan')

@section('content')

    <form method="GET" class="flex items-end gap-3 mb-5">
        <div>
            <label class="block text-xs text-gray-500 mb-1">Tanggal</label>
            <input type="date" name="tanggal" value="{{ $tanggal }}" onchange="this.form.submit()"
                   class="px-4 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-brand">
        </div>
        <div class="flex items-center gap-4 text-xs text-gray-500 ml-2">
            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-white border border-gray-300 inline-block"></span> Kosong</span>
            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-court-booked/20 border border-court-booked/40 inline-block"></span> Terisi</span>
        </div>
    </form>

    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs border-collapse">
                <thead>
                    <tr>
                        <th class="sticky left-0 bg-white px-4 py-3 text-left font-medium text-gray-500 border-b border-r border-gray-100 min-w-[160px]">
                            Lapangan
                        </th>
                        @foreach ($slots as $jam)
                            <th class="px-2 py-3 text-center font-medium text-gray-500 border-b border-gray-100 min-w-[52px]">
                                {{ sprintf('%02d:00', $jam) }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse ($lapangans as $lapangan)
                        <tr class="border-b border-gray-100">
                            <td class="sticky left-0 bg-white px-4 py-3 border-r border-gray-100">
                                <p class="font-medium text-brand-black">{{ $lapangan->nama }}</p>
                                <p class="text-gray-400">{{ $lapangan->kategori }}</p>
                            </td>
                            @foreach ($slots as $jam)
                                @php $nomorPesanan = $jamTerpakai[$lapangan->id][$jam] ?? null; @endphp
                                <td class="text-center p-1">
                                    <div
                                        title="{{ $nomorPesanan ? 'Terisi — ' . $nomorPesanan : 'Kosong' }}"
                                        class="w-full h-8 rounded-md flex items-center justify-center
                                            {{ $nomorPesanan ? 'bg-court-booked/20 border border-court-booked/40' : 'bg-gray-50 border border-gray-100' }}"
                                    >
                                        @if ($nomorPesanan)
                                            <svg class="w-3.5 h-3.5 text-court-booked" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.707-9.293a1 1 0 0 0-1.414-1.414L9 10.586 7.707 9.293a1 1 0 0 0-1.414 1.414l2 2a1 1 0 0 0 1.414 0l4-4Z" clip-rule="evenodd" transform="rotate(45 10 10)" />
                                            </svg>
                                        @endif
                                    </div>
                                </td>
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($slots) + 1 }}" class="text-center text-gray-400 py-10">Belum ada data lapangan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <p class="text-xs text-gray-400 mt-3">Arahkan kursor ke kotak terisi untuk lihat nomor pesanannya.</p>

@endsection