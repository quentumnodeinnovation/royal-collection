<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') — Royal Collection</title>
    <link rel="icon" type="image/png" href="{{ asset('images/royal-collection-logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=Montserrat:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        html, body { overflow-x: clip; font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-editorial { font-family: 'Cormorant Garamond', Georgia, serif; }
        .rc-admin-nav.active { background: #e3c587; color: #141414; font-weight: 800; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        select.h-9.text-xs { padding-top: 9px; padding-bottom: 9px; }
        select.h-10.text-xs { padding-top: 11px; padding-bottom: 11px; }
        select.h-10.text-sm { padding-top: 9px; padding-bottom: 9px; }
    </style>
</head>
<body class="bg-[#faf7f2] text-[#141414] min-h-screen flex">

    {{-- Mobile Backdrop --}}
    <div id="adminSidebarBackdrop" class="fixed inset-0 bg-black/55 backdrop-blur-[1px] z-40 hidden lg:hidden"></div>

    {{-- Obsidian Left Sidebar --}}
    <aside id="adminSidebar"
           class="fixed lg:sticky top-0 left-0 z-50 h-screen w-64 shrink-0 bg-[#141414] text-neutral-300 flex flex-col justify-between transition-transform duration-200 -translate-x-full lg:translate-x-0 border-r border-[#c69d54]/20">
        <div class="overflow-y-auto px-4 py-5 space-y-6">
            {{-- Brand --}}
            <a href="{{ route('admin.dashboard') }}" class="block bg-white rounded-xl px-3.5 py-2.5 shadow-sm">
                <div class="flex items-center justify-between gap-2">
                    <img src="{{ asset('images/royal-collection-logo.png') }}" alt="Royal Collection" class="h-10 w-auto object-contain">
                    <span class="px-2 py-0.5 rounded bg-[#141414] text-[#e3c587] text-[9px] font-extrabold uppercase tracking-widest">Admin</span>
                </div>
            </a>

            @php
                $navGroups = [
                    'Overview' => [
                        ['route' => 'admin.dashboard', 'match' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                    ],
                    'Sales & Bags' => [
                        ['route' => 'admin.orders.index', 'match' => 'admin.orders.*', 'label' => 'Orders', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                        ['route' => 'admin.carts.index', 'match' => 'admin.carts.*', 'label' => 'Carts', 'icon' => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z'],
                    ],
                    'Mens & Kids Catalog' => [
                        ['route' => 'admin.products.index', 'match' => 'admin.products.*', 'label' => 'Products', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
                        ['route' => 'admin.categories.index', 'match' => 'admin.categories.*', 'label' => 'Categories', 'icon' => 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z'],
                        ['route' => 'admin.sliders.index', 'match' => 'admin.sliders.*', 'label' => 'Sliders', 'icon' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z'],
                    ],
                    'People & Care' => [
                        ['route' => 'admin.users.index', 'match' => 'admin.users.*', 'label' => 'Users', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
                        ['route' => 'admin.inquiries.index', 'match' => 'admin.inquiries.*', 'label' => 'Inquiries', 'icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                    ],
                ];
            @endphp

            <nav class="space-y-5" aria-label="Admin Navigation">
                @foreach($navGroups as $groupName => $items)
                    <div>
                        <p class="px-3 text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-500 mb-2">{{ $groupName }}</p>
                        <div class="space-y-1">
                            @foreach($items as $item)
                                @php $active = request()->routeIs($item['match']); @endphp
                                <a href="{{ route($item['route']) }}"
                                   class="rc-admin-nav {{ $active ? 'active' : 'hover:bg-neutral-800/80 hover:text-white' }} flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.9" d="{{ $item['icon'] }}"/>
                                    </svg>
                                    <span>{{ $item['label'] }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </nav>
        </div>

        {{-- Bottom Admin Actions --}}
        <div class="p-4 border-t border-neutral-800 space-y-2.5 bg-[#0f0e0d]">
            <a href="{{ route('home') }}" target="_blank"
               class="flex items-center justify-between px-3 py-2 rounded-lg bg-neutral-900 border border-neutral-800 text-xs font-semibold text-neutral-300 hover:text-white hover:border-[#c69d54]/50 transition-colors">
                <span>View Live Storefront</span>
                <span class="text-[#e3c587]">↗</span>
            </a>

            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit"
                        class="w-full flex items-center justify-center gap-2 px-3 py-2 rounded-lg border border-neutral-800 text-xs font-semibold text-neutral-400 hover:bg-[#7c232b]/30 hover:text-[#f8d7da] hover:border-[#7c232b] transition-colors cursor-pointer">
                    <span>Sign Out</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- Main Workspace --}}
    <div class="flex-1 min-w-0 flex flex-col">
        {{-- Sticky Top Bar --}}
        <header class="sticky top-0 z-30 bg-white/95 backdrop-blur border-b border-[#e6dfd3] px-4 sm:px-6 lg:px-8 min-h-[64px] py-2.5 flex items-center justify-between gap-3">
            <div class="flex items-center gap-3 min-w-0">
                <button type="button" id="openAdminSidebar" aria-label="Toggle Menu"
                        class="lg:hidden p-2 -ml-1 rounded-lg text-[#141414] hover:bg-[#faf7f2] shrink-0 cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <div class="min-w-0">
                    <h1 class="text-sm sm:text-base lg:text-lg font-extrabold text-[#141414] truncate">@yield('page_title', 'Dashboard')</h1>
                    @hasSection('page_subtitle')
                        <p class="text-[11px] text-neutral-500 hidden md:block leading-snug">@yield('page_subtitle')</p>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-2.5 sm:gap-3 shrink-0">
                @yield('top_actions')
                <div class="hidden sm:flex items-center gap-2.5 pl-3 border-l border-[#e6dfd3]">
                    <div class="w-8 h-8 rounded-full bg-[#141414] text-[#e3c587] text-xs font-extrabold flex items-center justify-center uppercase shrink-0">
                        {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                    </div>
                    <div class="hidden xl:block leading-tight">
                        <p class="text-xs font-bold text-[#141414]">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] text-neutral-500">{{ auth()->user()->email }}</p>
                    </div>
                </div>
            </div>
        </header>

        {{-- Alerts + Page Body --}}
        <main class="flex-1 p-4 sm:p-6 lg:p-8 space-y-5 sm:space-y-6 max-w-[1440px] w-full mx-auto">
            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-900 px-4 py-3 rounded-xl text-xs font-semibold flex items-center justify-between">
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-[#f9ecee] border border-[#e3b8be] text-[#7c232b] px-4 py-3 rounded-xl text-xs font-semibold">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-[#f9ecee] border border-[#e3b8be] text-[#7c232b] px-4 py-3 rounded-xl text-xs space-y-1">
                    <p class="font-bold">Please fix the following errors:</p>
                    <ul class="list-disc pl-4 space-y-0.5">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
        (function () {
            const sidebar = document.getElementById('adminSidebar');
            const backdrop = document.getElementById('adminSidebarBackdrop');
            const openBtn = document.getElementById('openAdminSidebar');

            function toggleSidebar(show) {
                sidebar.classList.toggle('-translate-x-full', !show);
                backdrop.classList.toggle('hidden', !show);
            }

            if (openBtn) openBtn.addEventListener('click', () => toggleSidebar(true));
            if (backdrop) backdrop.addEventListener('click', () => toggleSidebar(false));
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') toggleSidebar(false);
            });
        })();
    </script>
    @stack('scripts')
</body>
</html>
