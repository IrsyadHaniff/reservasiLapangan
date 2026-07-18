@props(['lapangans'])

@php
    $kategoriList = $lapangans->pluck('kategori')->unique()->values();
    $filterItems = $lapangans->map(fn ($l) => [
        'nama' => $l->nama,
        'kategori' => $l->kategori,
        'status' => $l->isTersediaSekarang() ? 'tersedia' : 'terisi',
    ])->values();
@endphp

<section
    id="reservasi"
    aria-label="Reservasi Lapangan"
    x-data="{
        search: '',
        filterStatus: 'semua',
        filterKategori: 'semua',
        items: @js($filterItems),
        get filteredCount() {
            return this.items.filter(i =>
                (this.filterStatus === 'semua' || this.filterStatus === i.status) &&
                (this.filterKategori === 'semua' || this.filterKategori === i.kategori) &&
                i.nama.toLowerCase().includes(this.search.toLowerCase())
            ).length;
        }
    }"
    class="relative bg-white py-16 md:py-24 scroll-mt-16 md:scroll-mt-20"
>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Heading --}}
        <div class="text-center max-w-2xl mx-auto mb-10 md:mb-14">
            <span class="inline-block text-brand-dark font-semibold text-sm tracking-wide uppercase mb-2">
                Reservasi
            </span>
            <h2 class="font-display font-bold text-3xl md:text-4xl text-brand-black">
                Pilih Lapangan, Cek Ketersediaan
            </h2>
            <p class="mt-3 text-gray-600">
                Cari berdasarkan nama, jenis olahraga, atau lihat langsung mana yang masih kosong hari ini.
            </p>
        </div>

        {{-- Search & Filter Bar --}}
        <div class="flex flex-col lg:flex-row gap-4 lg:items-center lg:justify-between mb-6">

            {{-- Search input --}}
            <label for="search-lapangan" class="relative w-full lg:max-w-sm">
                <span class="sr-only">Cari lapangan</span>
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
                <input
                    id="search-lapangan"
                    type="text"
                    x-model="search"
                    placeholder="Cari nama lapangan..."
                    class="w-full pl-11 pr-4 py-3 rounded-full border border-gray-300 text-sm text-gray-700 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand transition-shadow"
                >
            </label>

            <div class="flex flex-col sm:flex-row gap-3 sm:items-center">
                {{-- Filter status: pelanggan bisa lihat semua / hanya tersedia / hanya terisi --}}
                <div class="inline-flex items-center gap-1 bg-gray-100 rounded-full p-1 self-start" role="group" aria-label="Filter status ketersediaan">
                    <button type="button" @click="filterStatus = 'semua'"
                            :class="filterStatus === 'semua' ? 'bg-white shadow text-brand-black' : 'text-gray-500 hover:text-brand-black'"
                            class="px-4 py-2 rounded-full text-sm font-medium transition-all duration-150">
                        Semua
                    </button>
                    <button type="button" @click="filterStatus = 'tersedia'"
                            :class="filterStatus === 'tersedia' ? 'bg-white shadow text-court-available' : 'text-gray-500 hover:text-court-available'"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-sm font-medium transition-all duration-150">
                        <span class="h-2 w-2 rounded-full bg-court-available"></span>
                        Tersedia
                    </button>
                    <button type="button" @click="filterStatus = 'terisi'"
                            :class="filterStatus === 'terisi' ? 'bg-white shadow text-court-booked' : 'text-gray-500 hover:text-court-booked'"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-sm font-medium transition-all duration-150">
                        <span class="h-2 w-2 rounded-full bg-court-booked"></span>
                        Terisi
                    </button>
                </div>

                {{-- Filter kategori --}}
                <label class="relative">
                    <span class="sr-only">Filter jenis lapangan</span>
                    <select x-model="filterKategori"
                            class="appearance-none pl-4 pr-9 py-2.5 rounded-full border border-gray-300 text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand cursor-pointer">
                        <option value="semua">Semua Jenis</option>
                        @foreach ($kategoriList as $kategori)
                            <option value="{{ $kategori }}">{{ $kategori }}</option>
                        @endforeach
                    </select>
                    <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                    </svg>
                </label>
            </div>
        </div>

        {{-- Hitung hasil --}}
        <p class="text-sm text-gray-500 mb-6" x-text="`Menampilkan ${filteredCount} dari {{ $lapangans->count() }} lapangan`"></p>

        {{-- Grid Card Lapangan --}}
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($lapangans as $lapangan)
                @php
                    $isTersedia = $lapangan->isTersediaSekarang();
                @endphp
                <article
                    x-show="(filterStatus === 'semua' || filterStatus === '{{ $isTersedia ? 'tersedia' : 'terisi' }}')
                        && (filterKategori === 'semua' || filterKategori === '{{ $lapangan->kategori }}')
                        && '{{ Str::lower($lapangan->nama) }}'.includes(search.toLowerCase())"
                    x-transition
                    class="group relative rounded-2xl border overflow-hidden bg-white transition-all duration-200 hover:shadow-xl hover:-translate-y-1
                        {{ $isTersedia ? 'border-court-available/30 hover:border-court-available/60' : 'border-court-booked/30 hover:border-court-booked/60' }}"
                >
                    {{-- Aksen warna status di atas card --}}
                    <div class="h-1 w-full {{ $isTersedia ? 'bg-court-available' : 'bg-court-booked' }}" aria-hidden="true"></div>

                    {{-- Gambar --}}
                    <div class="relative aspect-[4/3] overflow-hidden bg-gray-100">
                        <img
                            src="{{ $lapangan->gambar_url }}"
                            alt="{{ $lapangan->nama }} - {{ $lapangan->kategori }}"
                            width="400" height="300"
                            loading="lazy"
                            class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105 {{ $isTersedia ? '' : 'grayscale-[40%]' }}"
                        >
                        {{-- Badge status --}}
                        <span class="absolute top-3 right-3 inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold backdrop-blur-sm
                            {{ $isTersedia ? 'bg-court-available/15 text-court-available' : 'bg-court-booked/15 text-court-booked' }}">
                            <span class="h-1.5 w-1.5 rounded-full {{ $isTersedia ? 'bg-court-available animate-pulse' : 'bg-court-booked' }}"></span>
                            {{ $isTersedia ? 'Tersedia' : 'Terisi' }}
                        </span>
                    </div>

                    {{-- Info --}}
                    <div class="p-5">
                        <div class="flex items-start justify-between gap-2">
                            <h3 class="font-display font-bold text-lg text-brand-black leading-snug">
                                {{ $lapangan->nama }}
                            </h3>
                            <span class="shrink-0 inline-flex items-center gap-1 text-xs font-medium text-gray-500">
                                <svg class="w-3.5 h-3.5 text-brand" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.958a1 1 0 0 0 .951.69h4.162c.969 0 1.371 1.24.588 1.81l-3.367 2.447a1 1 0 0 0-.363 1.118l1.287 3.958c.3.922-.755 1.688-1.538 1.118l-3.367-2.447a1 1 0 0 0-1.176 0l-3.367 2.447c-.783.57-1.838-.196-1.538-1.118l1.287-3.958a1 1 0 0 0-.363-1.118L2.062 9.385c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 0 0 .95-.69l1.287-3.958Z" />
                                </svg>
                                {{ number_format($lapangan->rating, 1) }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">{{ $lapangan->kategori }} · Indoor</p>

                        <div class="flex items-center justify-between mt-4">
                            <p class="text-brand-black font-semibold">
                                Rp{{ number_format($lapangan->harga_per_jam, 0, ',', '.') }}
                                <span class="text-gray-400 font-normal text-sm">/jam</span>
                            </p>

                            @if ($isTersedia)
                                <a href="{{ route('booking.show', $lapangan) }}"
                                   class="inline-flex items-center px-4 py-2 rounded-full bg-brand text-brand-black text-sm font-semibold hover:bg-[#4fd43f] transition-colors duration-200">
                                    Booking
                                </a>
                            @else
                                <button type="button" disabled aria-disabled="true"
                                        class="inline-flex items-center px-4 py-2 rounded-full bg-gray-100 text-gray-400 text-sm font-semibold cursor-not-allowed">
                                    Terisi
                                </button>
                            @endif
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        {{-- Empty state kalau hasil filter kosong --}}
        <div x-show="filteredCount === 0" x-cloak class="text-center py-16">
            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
            </svg>
            <p class="text-gray-500 font-medium">Lapangan tidak ditemukan</p>
            <p class="text-gray-400 text-sm mt-1">Coba ubah kata kunci atau filter yang kamu pakai.</p>
        </div>
    </div>
</section>