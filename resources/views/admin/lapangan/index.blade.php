@extends('admin.layouts.app')

@section('title', 'Kelola Lapangan - Admin')
@section('page-title', 'Kelola Lapangan')

@section('content')

    <div class="flex justify-end mb-4">
        <a href="{{ route('admin.lapangan.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-brand text-brand-black text-sm font-semibold hover:bg-[#4fd43f] transition-colors duration-200">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Tambah Lapangan
        </a>
    </div>

    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-500 border-b border-gray-100">
                        <th class="px-5 py-3 font-medium">Lapangan</th>
                        <th class="px-5 py-3 font-medium">Kategori</th>
                        <th class="px-5 py-3 font-medium">Harga/Jam</th>
                        <th class="px-5 py-3 font-medium">Rating</th>
                        <th class="px-5 py-3 font-medium">Status</th>
                        <th class="px-5 py-3 font-medium text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($lapangans as $lapangan)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $lapangan->gambar_url }}" alt="" width="40" height="40" class="w-10 h-10 rounded-lg object-cover shrink-0">
                                    <span class="font-medium text-brand-black">{{ $lapangan->nama }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-3 text-gray-600">{{ $lapangan->kategori }}</td>
                            <td class="px-5 py-3 text-gray-600">Rp{{ number_format($lapangan->harga_per_jam, 0, ',', '.') }}</td>
                            <td class="px-5 py-3 text-gray-600">{{ number_format($lapangan->rating, 1) }}</td>
                            <td class="px-5 py-3">
                                <form method="POST" action="{{ route('admin.lapangan.toggle-aktif', $lapangan) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium transition-colors
                                                {{ $lapangan->is_aktif ? 'bg-court-available/15 text-court-available' : 'bg-gray-100 text-gray-400' }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $lapangan->is_aktif ? 'bg-court-available' : 'bg-gray-400' }}"></span>
                                        {{ $lapangan->is_aktif ? 'Aktif' : 'Nonaktif' }}
                                    </button>
                                </form>
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.lapangan.edit', $lapangan) }}" class="text-brand-dark hover:underline text-xs font-medium">Edit</a>
                                    <form method="POST" action="{{ route('admin.lapangan.destroy', $lapangan) }}" onsubmit="return confirm('Yakin mau hapus {{ $lapangan->nama }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-court-booked hover:underline text-xs font-medium">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-gray-400 py-10">Belum ada data lapangan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection