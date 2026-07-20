
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - SM-SPORT CENTER</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-brand/5 min-h-screen flex items-center justify-center px-4">

    <div class="w-full max-w-sm">
        {{-- Logo --}}
        <a href="{{ url('/') }}" class="flex items-center justify-center gap-2.5 mb-8">
            <img src="{{ asset('assets/img/icon.webp') }}" alt="" width="40" height="40" class="w-10 h-10">
            <span class="font-display font-bold text-xl text-brand-black">
                SM-<span class="text-brand-dark">SPORT</span> CENTER
            </span>
        </a>

        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6 md:p-8">
            <h1 class="font-display font-bold text-xl text-brand-black mb-1">Masuk ke Akun</h1>
            <p class="text-sm text-gray-500 mb-6">Login pakai email & password akun kamu.</p>

            @if ($errors->any())
                <div class="mb-5 px-3 py-2.5 rounded-lg bg-court-booked/10 border border-court-booked/30 text-court-booked text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                           placeholder="nama@email.com"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                    <input id="password" type="password" name="password" required
                           placeholder="••••••••"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand">
                </div>

                <label class="flex items-center gap-2 text-sm text-gray-600">
                    <input type="checkbox" name="remember" class="rounded border-gray-300 text-brand focus:ring-brand">
                    Ingat saya
                </label>

                <button type="submit"
                        class="w-full py-3 rounded-full bg-brand text-brand-black font-semibold text-sm hover:bg-[#4fd43f] transition-colors duration-200">
                    Masuk
                </button>
            </form>

            <p class="text-xs text-gray-400 text-center mt-6">
                Pelanggan baru? Akun otomatis dibuat saat kamu
                <a href="{{ url('/#reservasi') }}" class="text-brand-dark font-medium hover:underline">melakukan booking</a> pertama kali.
            </p>
        </div>

        <a href="{{ url('/') }}" class="flex items-center justify-center gap-1.5 text-sm text-gray-500 hover:text-brand-dark mt-6 transition-colors duration-200">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
            Kembali ke Beranda
        </a>
    </div>

</body>
</html>