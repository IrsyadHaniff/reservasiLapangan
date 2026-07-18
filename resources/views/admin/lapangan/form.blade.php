@extends('admin.layouts.app')

@section('title', ($lapangan->exists ? 'Edit' : 'Tambah') . ' Lapangan - Admin')
@section('page-title', $lapangan->exists ? 'Edit Lapangan' : 'Tambah Lapangan')

@section('content')

    <div class="max-w-xl bg-white border border-gray-200 rounded-2xl p-6">
        <form method="POST"
              action="{{ $lapangan->exists ? route('admin.lapangan.update', $lapangan) : route('admin.lapangan.store') }}"
              enctype="multipart/form-data" class="space-y-5">
            @csrf
            @if ($lapangan->exists) @method('PUT') @endif

            <div>
                <label for="nama" class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lapangan</label>
                <input id="nama" name="nama" type="text" value="{{ old('nama', $lapangan->nama) }}" required
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand">
                @error('nama') <p class="text-xs text-court-booked mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label for="kategori" class="block text-sm font-medium text-gray-700 mb-1.5">Kategori</label>
                    <select id="kategori" name="kategori" required
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand">
                        @foreach (['Futsal', 'Badminton'] as $kategori)
                            <option value="{{ $kategori }}" {{ old('kategori', $lapangan->kategori) === $kategori ? 'selected' : '' }}>{{ $kategori }}</option>
                        @endforeach
                    </select>
                    @error('kategori') <p class="text-xs text-court-booked mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="harga_per_jam" class="block text-sm font-medium text-gray-700 mb-1.5">Harga per Jam (Rp)</label>
                    <input id="harga_per_jam" name="harga_per_jam" type="number" min="0" step="1000"
                           value="{{ old('harga_per_jam', $lapangan->harga_per_jam) }}" required
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand">
                    @error('harga_per_jam') <p class="text-xs text-court-booked mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="rating" class="block text-sm font-medium text-gray-700 mb-1.5">Rating (opsional, 0 - 5)</label>
                <input id="rating" name="rating" type="number" min="0" max="5" step="0.1"
                       value="{{ old('rating', $lapangan->rating) }}"
                       class="w-full sm:w-40 px-4 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand">
                @error('rating') <p class="text-xs text-court-booked mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="gambar" class="block text-sm font-medium text-gray-700 mb-1.5">Foto Lapangan</label>
                @if ($lapangan->exists)
                    <img src="{{ $lapangan->gambar_url }}" alt="" width="96" height="72" class="w-24 h-[4.5rem] rounded-lg object-cover mb-2 border border-gray-200">
                @endif
                <input id="gambar" name="gambar" type="file" accept="image/*"
                       class="w-full text-sm text-gray-600 file:mr-4 file:px-4 file:py-2 file:rounded-full file:border-0 file:bg-brand/15 file:text-brand-dark file:font-medium hover:file:bg-brand/25 file:cursor-pointer cursor-pointer">
                <p class="text-xs text-gray-400 mt-1">{{ $lapangan->exists ? 'Kosongkan kalau gak mau ganti foto.' : 'Format JPG/PNG/WEBP, maks 2MB.' }}</p>
                @error('gambar') <p class="text-xs text-court-booked mt-1">{{ $message }}</p> @enderror
            </div>

            <label class="flex items-center gap-2.5">
                <input type="checkbox" name="is_aktif" value="1" {{ old('is_aktif', $lapangan->is_aktif ?? true) ? 'checked' : '' }}
                       class="rounded border-gray-300 text-brand focus:ring-brand">
                <span class="text-sm text-gray-700">Aktif (tampil di halaman reservasi publik)</span>
            </label>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="px-6 py-2.5 rounded-full bg-brand text-brand-black text-sm font-semibold hover:bg-[#4fd43f] transition-colors duration-200">
                    {{ $lapangan->exists ? 'Simpan Perubahan' : 'Tambah Lapangan' }}
                </button>
                <a href="{{ route('admin.lapangan.index') }}" class="px-6 py-2.5 rounded-full border border-gray-300 text-gray-600 text-sm font-semibold hover:border-gray-400 transition-colors duration-200">
                    Batal
                </a>
            </div>
        </form>
    </div>

@endsection