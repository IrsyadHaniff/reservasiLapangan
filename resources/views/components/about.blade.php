<section id="about" aria-label="Tentang SM-SPORT CENTER" class="relative bg-brand/5 py-16 md:py-24 scroll-mt-16 md:scroll-mt-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">

            {{-- Kolom Gambar --}}
            <div class="relative order-1">
                <div class="relative rounded-3xl overflow-hidden border border-gray-200 shadow-xl shadow-gray-200/50 aspect-[4/3]">
                    <img
                        src="{{ asset('assets/img/preview.webp') }}"
                        alt="Suasana venue SM-SPORT CENTER dengan lapangan indoor dan area tunggu pelanggan"
                        width="1200" height="900"
                        loading="lazy"
                        class="w-full h-full object-cover"
                    >
                </div>

                {{-- Kartu tahun berdiri: aksen dekoratif mengambang --}}
                {{-- <div class="absolute -top-5 -left-5 md:-top-6 md:-left-6 bg-brand-black text-white rounded-2xl px-5 py-4 shadow-xl">
                    <p class="font-display font-bold text-2xl md:text-3xl leading-none">2019</p>
                    <p class="text-xs text-gray-300 mt-1">Melayani sejak</p>
                </div> --}}
            </div>

            {{-- Kolom Teks --}}
            <div class="order-2">
                <span class="inline-block text-brand-dark font-semibold text-sm tracking-wide uppercase mb-2">
                    Tentang Kami
                </span>
                <h2 class="font-display font-bold text-3xl md:text-4xl text-brand-black">
                    Tempat Main Futsal & Badminton Favorit Sekitarmu
                </h2>
                <p class="mt-4 text-gray-600 leading-relaxed">
                    SM-SPORT CENTER menyediakan lapangan futsal dan badminton indoor dengan permukaan
                    standar pertandingan, pencahayaan memadai, dan sistem reservasi online yang
                    memudahkan kamu cek jadwal kapan saja tanpa perlu datang atau telepon dulu.
                </p>

                {{-- Fitur / kelebihan --}}
                <ul class="mt-8 grid sm:grid-cols-2 gap-4">
                    @php
                        $fitur = [
                            ['icon' => 'clock', 'title' => 'Buka Setiap Hari', 'desc' => '06.00 – 24.00 WIB'],
                            ['icon' => 'shield', 'title' => 'Lapangan Standar', 'desc' => 'Permukaan & pencahayaan terjaga'],
                            ['icon' => 'wallet', 'title' => 'Harga Bersahabat', 'desc' => 'Mulai dari Rp60.000/jam'],
                            ['icon' => 'map', 'title' => 'Lokasi Strategis', 'desc' => 'Mudah dijangkau & parkir luas'],
                        ];
                        $icons = [
                            'clock' => 'M12 6v6l4 2m6-2a10 10 0 1 1-20 0 10 10 0 0 1 20 0Z',
                            'shield' => 'M9 12.75 11.25 15 15 9.75m-3-7-8.25 3v6c0 5.007 3.51 8.997 8.25 10.5 4.74-1.503 8.25-5.493 8.25-10.5v-6L12 2.75Z',
                            'wallet' => 'M21 12a2.25 2.25 0 0 0-2.25-2.25H15a3 3 0 1 1 0-6h3.75M21 12v6.75A2.25 2.25 0 0 1 18.75 21H5.25A2.25 2.25 0 0 1 3 18.75V5.25A2.25 2.25 0 0 1 5.25 3H15a3 3 0 1 1 0 6h3.75A2.25 2.25 0 0 1 21 12Z',
                            'map' => 'M9 6.75V15m6-6v8.25m.503 3.498 4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.804a1.125 1.125 0 0 0-1.006 0L3.622 6.24A1.125 1.125 0 0 0 3 7.246v13.44c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0Z',
                        ];
                    @endphp
                    @foreach ($fitur as $item)
                        <li class="flex items-start gap-3">
                            <span class="shrink-0 flex items-center justify-center w-10 h-10 rounded-xl bg-brand/15 text-brand-dark">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons[$item['icon']] }}" />
                                </svg>
                            </span>
                            <div>
                                <p class="font-semibold text-brand-black text-sm">{{ $item['title'] }}</p>
                                <p class="text-gray-500 text-sm">{{ $item['desc'] }}</p>
                            </div>
                        </li>
                    @endforeach
                </ul>

                <div class="mt-9">
                    <a href="{{ url('/#contact') }}"
                       class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-brand-black text-white text-sm font-semibold hover:bg-brand-dark transition-colors duration-200">
                        Hubungi Kami
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25 21 12m0 0-3.75 3.75M21 12H3" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>