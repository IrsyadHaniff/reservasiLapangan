<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link rel="shortcut icon" href="{{ asset('assets/img/icon.webp') }}" alt="Icon" type="image/x-icon">
    <title>@yield('title', 'SM-SPORT CENTER - beranda')</title>

    <style>
        /* * {outline: 1px solid red} debugging */
        html {
            scroll-behavior: smooth;
        }
    </style>
    {{-- Tailwind CSS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="select-none overflow-x-hidden">
    {{-- Navbar Component --}}
    <x-navbar />

    {{-- Konten dari halaman --}}
    @yield('content')

    {{-- Footer --}}
    @include('components.footer')

    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    {{--     
    disable klick kanan
    <script>
        document.addEventListener('contextmenu', e => e.preventDefault());
    </script> --}}

</body>

</html>
