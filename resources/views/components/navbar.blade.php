{{--
    Komponen: Navbar
    Lokasi: resources/views/components/navbar.blade.php

    Catatan integrasi:
    1. Pastikan warna brand sudah didaftarkan di @theme resources/css/app.css (lihat app.css.snippet.css)
    2. Tambahkan id="reservasi", id="about", id="contact" pada section/komponen terkait
       (x-reservasi, x-about, dan footer) agar anchor scroll berfungsi.
    3. Tambahkan [x-cloak] { display: none !important; } di resources/css/app.css
       supaya menu mobile tidak "kedip" sebelum Alpine siap.
    4. Tema navbar sekarang: putih, ikut background halaman. Border & shadow tipis
       dipakai sebagai pemisah, bukan warna solid, biar tetap terasa "menyatu".
--}}

{{-- Skip link: aksesibilitas + Lighthouse (bypass navbar ke konten utama) --}}
<a href="#main-content"
   class="sr-only focus:not-sr-only focus:absolute focus:top-2 focus:left-2 focus:z-[60] focus:px-4 focus:py-2 focus:rounded-lg focus:bg-brand focus:text-brand-black focus:font-semibold">
    Langsung ke konten
</a>

<header
    x-data="{ mobileOpen: false, scrolled: false }"
    x-init="scrolled = window.scrollY > 10; window.addEventListener('scroll', () => scrolled = window.scrollY > 10)"
    x-cloak
>
    <nav
        :class="scrolled
            ? 'bg-white/90 backdrop-blur-md border-b border-brand-dark/10 shadow-sm'
            : 'bg-white/70 backdrop-blur-sm border-b border-transparent'"
        class="fixed top-0 inset-x-0 z-50 transition-all duration-300"
        aria-label="Navigasi utama"
    >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 md:h-20">

                {{-- Logo --}}
                <a href="{{ url('/#top') }}"
                   class="flex items-center gap-2.5 shrink-0 group"
                   aria-label="SM-SPORT CENTER — kembali ke beranda">
                    <img
                        src="{{ asset('assets/img/icon.webp') }}"
                        alt=""
                        width="40" height="40"
                        class="w-9 h-9 md:w-10 md:h-10 transition-transform duration-300 group-hover:scale-105"
                        loading="eager"
                        fetchpriority="high"
                    >
                    <span class="font-display font-bold text-lg md:text-xl tracking-wide text-brand-black leading-none">
                        SM-<span class="text-brand-dark">SPORT</span>
                        <span class="block text-[10px] md:text-xs font-sans font-medium tracking-[0.25em] text-gray-400 -mt-0.5">
                            CENTER
                        </span>
                    </span>
                </a>

                {{-- Menu Desktop --}}
                @php
                    $menus = [
                        ['label' => 'Home', 'href' => '/#top'],
                        ['label' => 'Reservasi', 'href' => '/#reservasi'],
                        ['label' => 'About', 'href' => '/#about'],
                        ['label' => 'Contact', 'href' => '/#contact'],
                    ];
                @endphp
                <ul class="hidden md:flex items-center gap-1 lg:gap-2">
                    @foreach ($menus as $menu)
                        <li>
                            <a href="{{ $menu['href'] }}"
                               class="relative px-4 py-2 text-sm lg:text-base font-medium text-gray-600 hover:text-brand-dark transition-colors duration-200 group">
                                {{ $menu['label'] }}
                                <span class="absolute left-4 right-4 -bottom-0.5 h-0.5 bg-brand scale-x-0 group-hover:scale-x-100 transition-transform duration-200 origin-left"></span>
                            </a>
                        </li>
                    @endforeach
                </ul>

                {{-- CTA Desktop --}}
                <div class="hidden md:flex items-center gap-3">
                    @guest
                        <a href="{{ route('login') }}"
                           class="text-sm font-medium text-gray-600 hover:text-brand-dark transition-colors duration-200">
                            Login
                        </a>
                        <a href="{{ url('/#reservasi') }}"
                           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-brand text-brand-black font-semibold text-sm hover:bg-[#4fd43f] hover:shadow-lg hover:shadow-brand/30 transition-all duration-200">
                            <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.836l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 1.994-4.593 2.699-6.75H5.106M7.5 14.25 5.106 5.272M6 18.75a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                            </svg>
                            Booking Sekarang
                        </a>
                    @endguest
                    @auth
                        <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('pelanggan.dashboard') }}"
                           class="text-sm font-medium text-gray-600 hover:text-brand-dark transition-colors duration-200">
                            {{ auth()->user()->role === 'admin' ? 'Dashboard Admin' : 'Dashboard Saya' }}
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full border border-gray-300 text-gray-700 font-semibold text-sm hover:border-court-booked hover:text-court-booked transition-colors duration-200">
                                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l3 3m0 0-3 3m3-3H3" />
                                </svg>
                                Logout
                            </button>
                        </form>
                    @endauth
                </div>

                {{-- Tombol Hamburger --}}
                <button
                    @click="mobileOpen = !mobileOpen"
                    type="button"
                    class="md:hidden inline-flex items-center justify-center p-2 rounded-lg text-gray-600 hover:text-brand-dark hover:bg-brand/10 transition-colors"
                    :aria-expanded="mobileOpen.toString()"
                    aria-controls="mobile-menu"
                    aria-label="Buka/tutup menu navigasi"
                >
                    <svg x-show="!mobileOpen" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg x-show="mobileOpen" x-cloak class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Menu Mobile --}}
        <div
            id="mobile-menu"
            x-show="mobileOpen"
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            @click.outside="mobileOpen = false"
            @keydown.escape.window="mobileOpen = false"
            class="md:hidden bg-white border-t border-brand-dark/10 shadow-sm"
        >
            <ul class="px-4 py-4 space-y-1">
                @foreach ($menus as $menu)
                    <li>
                        <a href="{{ $menu['href'] }}"
                           @click="mobileOpen = false"
                           class="block px-4 py-3 rounded-lg text-gray-700 font-medium hover:bg-brand/10 hover:text-brand-dark transition-colors duration-200">
                            {{ $menu['label'] }}
                        </a>
                    </li>
                @endforeach
                <li class="pt-2 space-y-2">
                    @guest
                        <a href="{{ route('login') }}"
                           @click="mobileOpen = false"
                           class="block text-center px-5 py-3 rounded-full border border-gray-300 text-gray-700 font-semibold hover:border-brand-dark hover:text-brand-dark transition-colors duration-200">
                            Login
                        </a>
                        <a href="{{ url('/#reservasi') }}"
                           @click="mobileOpen = false"
                           class="flex items-center justify-center gap-2 px-5 py-3 rounded-full bg-brand text-brand-black font-semibold hover:bg-[#4fd43f] transition-colors duration-200">
                            <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.836l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 1.994-4.593 2.699-6.75H5.106M7.5 14.25 5.106 5.272M6 18.75a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                            </svg>
                            Booking Sekarang
                        </a>
                    @endguest
                    @auth
                        <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('pelanggan.dashboard') }}"
                           @click="mobileOpen = false"
                           class="block text-center px-5 py-3 rounded-full border border-gray-300 text-gray-700 font-semibold hover:border-brand-dark hover:text-brand-dark transition-colors duration-200">
                            {{ auth()->user()->role === 'admin' ? 'Dashboard Admin' : 'Dashboard Saya' }}
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="flex items-center justify-center gap-2 w-full px-5 py-3 rounded-full bg-brand text-brand-black font-semibold hover:bg-[#4fd43f] transition-colors duration-200">
                                Logout
                            </button>
                        </form>
                    @endauth
                </li>
            </ul>
        </div>
    </nav>

    {{-- Spacer agar konten tidak tertutup navbar fixed --}}
    <div class="h-16 md:h-20" aria-hidden="true"></div>
</header>