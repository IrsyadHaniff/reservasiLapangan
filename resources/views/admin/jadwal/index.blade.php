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
    <p class="text-xs text-gray-400 mb-4">
        Jadwal 1 hari operasional: 06:00 hari ini s/d 05:00 keesokan harinya (tutup jam 05:00-06:00).
    </p>

    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs border-collapse">
                <thead>
                    <tr>
                        <th class="sticky left-0 bg-white px-4 py-3 text-left font-medium text-gray-500 border-b border-r border-gray-100 min-w-[160px]">
                            Lapangan
                        </th>
                        @foreach ($jamList as $jam)
                            <th class="px-2 py-3 text-center font-medium text-gray-500 border-b border-gray-100 min-w-[48px]">
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
                            @foreach ($jamList as $index => $jam)
                                @php $terisi = in_array($index, $slotTerpakai[$lapangan->id]); @endphp
                                <td class="text-center p-1">
                                    <div
                                        title="{{ $terisi ? 'Terisi' : 'Kosong' }}"
                                        class="w-full h-8 rounded-md flex items-center justify-center
                                            {{ $terisi ? 'bg-court-booked/20 border border-court-booked/40' : 'bg-gray-50 border border-gray-100' }}"
                                    >
                                        @if ($terisi)
                                            <span class="w-2 h-2 rounded-full bg-court-booked"></span>
                                        @endif
                                    </div>
                                </td>
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($jamList) + 1 }}" class="text-center text-gray-400 py-10">Belum ada data lapangan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection