<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin - SM-SPORT CENTER')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50" x-data="{ sidebarOpen: false }">

    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        <aside
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
            class="fixed lg:sticky top-0 left-0 h-screen w-64 bg-brand-black text-gray-300 flex flex-col z-40 transition-transform duration-200"
        >
            <div class="flex items-center gap-2.5 px-5 h-16 border-b border-white/10 shrink-0">
                <img src="{{ asset('assets/img/icon.webp') }}" alt="" width="32" height="32" class="w-8 h-8">
                <span class="font-display font-bold text-white text-sm leading-tight">
                    SM-<span class="text-brand">SPORT</span><br>
                    <span class="text-[10px] tracking-[0.2em] text-gray-400 font-sans font-medium">ADMIN PANEL</span>
                </span>
            </div>

            <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
                @php
                    $menus = [
                        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'M3 12l2-2m0 0 7-7 7 7M5 10v10a1 1 0 0 0 1 1h3m10-11 2 2m-2-2v10a1 1 0 0 1-1 1h-3m-6 0a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1m-6 0h6'],
                        ['label' => 'Lapangan', 'route' => null, 'icon' => 'M3 3h18v18H3V3Zm0 9h18M12 3v18'],
                        ['label' => 'Reservasi', 'route' => null, 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2Z'],
                        ['label' => 'Laporan', 'route' => null, 'icon' => 'M9 17v-6h6v6M9 17H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-2M9 17h6'],
                        ['label' => 'Jadwal', 'route' => null, 'icon' => 'M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z'],
                    ];
                @endphp
                @foreach ($menus as $menu)
                    @php $isActive = $menu['route'] && request()->routeIs($menu['route']); @endphp
                    <a href="{{ $menu['route'] ? route($menu['route']) : '#' }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150
                           {{ $isActive ? 'bg-brand text-brand-black' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-4.5 h-4.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $menu['icon'] }}" />
                        </svg>
                        {{ $menu['label'] }}
                        @if (! $menu['route'])
                            <span class="ml-auto text-[10px] text-gray-500 border border-gray-700 rounded px-1.5 py-0.5">Segera</span>
                        @endif
                    </a>
                @endforeach
            </nav>

            <div class="p-3 border-t border-white/10">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 w-full px-3 py-2.5 rounded-lg text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-court-booked transition-colors duration-150">
                        <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l3 3m0 0-3 3m3-3H3" />
                        </svg>
                        Keluar
                    </button>
                </form>
            </div>
        </aside>

        {{-- Overlay buat mobile saat sidebar terbuka --}}
        <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" class="fixed inset-0 bg-black/40 z-30 lg:hidden"></div>

        {{-- Konten --}}
        <div class="flex-1 min-w-0">
            {{-- Topbar --}}
            <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-4 md:px-6 sticky top-0 z-20">
                <button @click="sidebarOpen = true" class="lg:hidden p-2 -ml-2 text-gray-600" aria-label="Buka menu">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <h1 class="font-display font-bold text-brand-black text-lg">@yield('page-title', 'Dashboard')</h1>
                <div class="flex items-center gap-2.5">
                    <span class="hidden sm:block text-sm text-gray-600">{{ auth()->user()->name }}</span>
                    <div class="w-9 h-9 rounded-full bg-brand/15 text-brand-dark flex items-center justify-center font-semibold text-sm">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                </div>
            </header>

            <main class="p-4 md:p-6">
                @yield('content')
            </main>
        </div>
    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>