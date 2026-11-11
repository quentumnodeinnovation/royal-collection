<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="app-base" content="{{ url('/') }}">
    <title>@yield('title', 'Royal Collection — Luxury Apparel | Mens & Kids')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/royal-collection-logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=Montserrat:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/royal.css') }}">
    @stack('styles')
</head>
<body class="bg-white text-[#141414] min-h-screen flex flex-col">

    @include('partials.header')
    @include('partials.mega-drawer')
    @include('partials.cart-drawer')

    @if(session('success'))
        <div class="bg-[#141414] text-[#f5ecd9] border-b border-[#c69d54]/30 text-xs font-semibold py-2.5 px-4 text-center">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-[#7c232b] text-white text-xs font-semibold py-2.5 px-4 text-center">
            {{ session('error') }}
        </div>
    @endif

    <main class="flex-1">
        @yield('content')
    </main>

    @include('partials.footer')

    {{-- Floating notification toast --}}
    <div id="rcToast" class="fixed bottom-4 right-4 left-4 sm:left-auto sm:bottom-6 sm:right-6 z-50 bg-[#141414] border border-[#c69d54]/40 text-[#faf7f2] text-xs font-semibold px-4 py-3 rounded-xl shadow-xl opacity-0 translate-y-3 pointer-events-none transition-all duration-200 text-center sm:text-left"></div>

    <script src="{{ asset('js/royal.js') }}"></script>
    @stack('scripts')
</body>
</html>
