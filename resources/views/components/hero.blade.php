<section id="top" aria-label="Hero" class="relative overflow-hidden bg-white">

    {{-- Dekorasi: garis lapangan (court lines) samar sebagai tekstur latar --}}
    <svg class="absolute inset-0 w-full h-full opacity-[0.08] pointer-events-none" aria-hidden="true" preserveAspectRatio="none" viewBox="0 0 800 600" fill="none">
        <rect x="40" y="40" width="720" height="520" rx="4" stroke="#040304" stroke-width="2"/>
        <line x1="400" y1="40" x2="400" y2="560" stroke="#040304" stroke-width="2"/>
        <circle cx="400" cy="300" r="70" stroke="#040304" stroke-width="2"/>
        <rect x="40" y="200" width="90" height="200" stroke="#040304" stroke-width="2"/>
        <rect x="670" y="200" width="90" height="200" stroke="#040304" stroke-width="2"/>
    </svg>

    {{-- Glow gradient hijau lembut di pojok --}}
    <div class="absolute -top-32 -right-32 w-[22rem] h-[22rem] bg-brand/35 rounded-full blur-3xl pointer-events-none" aria-hidden="true"></div>
    <div class="absolute bottom-0 -left-24 w-72 h-72 bg-brand/50 rounded-full blur-3xl pointer-events-none" aria-hidden="true"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-28 pb-16 md:pt-36 md:pb-24">
        <div class="grid lg:grid-cols-2 gap-12 lg:gap-8 items-center">

            {{-- Kolom Teks --}}
            <div class="text-center lg:text-left order-2 lg:order-1">
                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-brand/10 border border-brand/30 text-brand-dark text-xs md:text-sm font-medium mb-5">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-brand"></span>
                    </span>
                    Booking online 24 jam, real-time
                </span>

                <h1 class="font-display font-bold uppercase tracking-wide text-4xl sm:text-5xl lg:text-6xl leading-[1.05] text-brand-black">
                    Main Tanpa Ribet,
                    <span class="block text-brand-dark">Booking Secepat Kilat.</span>
                </h1>

                <p class="mt-6 text-base md:text-lg text-gray-600 max-w-xl mx-auto lg:mx-0">
                    SM-SPORT CENTER menyediakan lapangan futsal, badminton, dan basket dengan sistem reservasi online.
                    Cek ketersediaan lapangan secara langsung, tanpa perlu telepon atau datang dulu.
                </p>

                <div class="mt-8 flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-3">
                    <a href="{{ url('/#reservasi') }}"
                       class="inline-flex items-center justify-center gap-2 w-full sm:w-auto px-7 py-3.5 rounded-full bg-brand text-brand-black font-semibold hover:bg-[#4fd43f] hover:shadow-lg hover:shadow-brand/30 transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.836l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 1.994-4.593 2.699-6.75H5.106M7.5 14.25 5.106 5.272M6 18.75a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                        </svg>
                        Booking Sekarang
                    </a>
                    <a href="{{ url('/#reservasi') }}"
                       class="inline-flex items-center justify-center gap-2 w-full sm:w-auto px-7 py-3.5 rounded-full border border-gray-300 text-gray-700 font-semibold hover:border-brand-dark hover:text-brand-dark transition-colors duration-200">
                        Lihat Lapangan
                    </a>
                </div>

                {{-- Statistik singkat --}}
                <dl class="mt-10 grid grid-cols-3 gap-4 max-w-md mx-auto lg:mx-0 border-t border-gray-200 pt-6">
                    <div>
                        <dt class="sr-only">Jumlah lapangan</dt>
                        <dd class="font-display font-bold text-2xl md:text-3xl text-brand-black">12+</dd>
                        <dd class="text-xs md:text-sm text-gray-500">Lapangan</dd>
                    </div>
                    <div>
                        <dt class="sr-only">Rating pelanggan</dt>
                        <dd class="font-display font-bold text-2xl md:text-3xl text-brand-black">4.9<span class="text-brand-dark">★</span></dd>
                        <dd class="text-xs md:text-sm text-gray-500">Rating</dd>
                    </div>
                    <div>
                        <dt class="sr-only">Pelanggan aktif</dt>
                        <dd class="font-display font-bold text-2xl md:text-3xl text-brand-black">2rb+</dd>
                        <dd class="text-xs md:text-sm text-gray-500">Pelanggan</dd>
                    </div>
                </dl>
            </div>

            {{-- Kolom Gambar --}}
            <div class="relative order-1 lg:order-2">
                <div class="relative rounded-3xl overflow-hidden border border-gray-200 shadow-xl shadow-gray-200/50 aspect-[4/3]">
                    <img
                        src="{{ asset('assets/img/preview.jpeg') }}"
                        alt="Lapangan futsal SM-SPORT CENTER dengan pencahayaan malam yang siap dipesan"
                        width="1200" height="900"
                        class="w-full h-full object-cover"
                        loading="eager"
                        fetchpriority="high"
                    >
                    {{-- Gradient overlay tipis biar badge di atasnya tetap kebaca --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-brand-black/40 via-transparent to-transparent" aria-hidden="true"></div>
                </div>

                {{-- Kartu status ketersediaan mengambang: signature element,
                     mengecho sistem warna hijau/merah yang dipakai di seluruh app --}}
                <div class="absolute -bottom-6 left-4 right-4 sm:left-6 sm:right-auto sm:w-64 bg-white border border-gray-200 rounded-2xl p-4 shadow-xl shadow-gray-300/40">
                    <p class="text-xs font-medium text-gray-400 mb-2">Status hari ini</p>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-700">Lapangan Futsal A</span>
                            <span class="inline-flex items-center gap-1.5 text-court-available font-medium">
                                <span class="h-2 w-2 rounded-full bg-court-available"></span>
                                Tersedia
                            </span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-700">Lapangan Badminton 2</span>
                            <span class="inline-flex items-center gap-1.5 text-court-booked font-medium">
                                <span class="h-2 w-2 rounded-full bg-court-booked"></span>
                                Terisi
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>