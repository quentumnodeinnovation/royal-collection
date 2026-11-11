@php
    $isMen = request()->routeIs('men') || (isset($activeAudience) && $activeAudience === 'men' && !request()->routeIs('home'));
    $isKids = request()->routeIs('kids') || (isset($activeAudience) && $activeAudience === 'kids' && !request()->routeIs('home'));
    $isShop = request()->routeIs('shop') && !$isMen && !$isKids;
    $isHome = request()->routeIs('home');
    $isContact = request()->routeIs('contact');
@endphp

{{-- Slim Luxury Announcement Strip --}}
<div class="bg-[#141414] text-[#f4efe6] text-[10.5px] sm:text-[11px] font-medium tracking-wider py-2 px-4 text-center border-b border-[#c69d54]/25">
    <div class="max-w-[1440px] mx-auto flex flex-wrap items-center justify-center gap-x-4 gap-y-1">
        <span class="uppercase font-bold text-[#e3c587]">ROYAL COLLECTION · LUXURY APPAREL</span>
        <span class="hidden sm:inline text-neutral-600">|</span>
        <span>FREE EXPRESS SHIPPING ON ORDERS ABOVE ₹1,499</span>
        <span class="hidden md:inline text-neutral-600">|</span>
        <span class="hidden md:inline text-neutral-300">EXCLUSIVE MENS &amp; KIDS EDITIONS</span>
    </div>
</div>

{{-- Sticky Ivory-White Storefront Header --}}
<header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-[#e6dfd3] shadow-[0_2px_16px_rgba(20,20,20,0.04)]">
    <div class="max-w-[1440px] mx-auto px-3 sm:px-6 lg:px-8 h-14 sm:h-16 lg:h-[76px] grid grid-cols-[auto_1fr_auto] lg:flex items-center justify-between gap-2 sm:gap-4">

        {{-- Left: Hamburger Drawer Button + Primary Desktop Links --}}
        <div class="flex items-center gap-3 xl:gap-7">
            <button type="button" id="openMenuDrawer" aria-label="Open categories menu"
                class="p-2 -ml-1 sm:-ml-2 rounded-lg text-[#141414] hover:bg-[#faf7f2] transition-colors cursor-pointer">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.9" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <nav class="hidden lg:flex items-center gap-6" aria-label="Main navigation">
                <a href="{{ route('home') }}" class="rc-nav-link {{ $isHome ? 'active' : '' }}">HOME</a>
                <a href="{{ route('men') }}" class="rc-nav-link {{ $isMen ? 'active' : '' }}">MEN</a>
                <a href="{{ route('kids') }}" class="rc-nav-link {{ $isKids ? 'active' : '' }}">KIDS</a>
                <a href="{{ route('shop') }}" class="rc-nav-link {{ $isShop ? 'active' : '' }}">SHOP</a>
                <a href="{{ route('contact') }}" class="rc-nav-link {{ $isContact ? 'active' : '' }}">CONTACT US</a>
            </nav>
        </div>

        {{-- Center: Exact ROYAL COLLECTION Logo (perfectly centered on mobile/tablet) --}}
        <a href="{{ route('home') }}" class="flex items-center justify-center shrink-0 group mx-auto lg:mx-0" title="Royal Collection — Luxury Apparel">
            <img src="{{ asset('images/royal-collection-logo.png') }}"
                 alt="Royal Collection Luxury Apparel"
                 class="h-10 sm:h-12 lg:h-[62px] w-auto object-contain mix-blend-multiply transition-transform duration-200 group-hover:scale-[1.02]">
        </a>

        {{-- Right: Desktop Search Pill + Account + Wishlist + Bag --}}
        <div class="flex items-center justify-end gap-1 sm:gap-2.5 lg:gap-3">
            <form action="{{ route('shop') }}" method="GET" class="hidden lg:flex items-center relative">
                <input type="text"
                       name="q"
                       value="{{ request('q') }}"
                       placeholder="Search Mens & Kids..."
                       class="w-52 xl:w-72 h-10 pl-4 pr-10 rounded-full border border-[#d9d0c1] bg-[#faf7f2] text-xs font-medium text-[#141414] placeholder:text-neutral-500 focus:outline-none focus:border-[#9b742e] focus:bg-white transition-all">
                <button type="submit" aria-label="Search products" class="absolute right-3.5 text-neutral-600 hover:text-[#141414] cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z"/>
                    </svg>
                </button>
            </form>

            {{-- Mobile & Tablet Search Toggle --}}
            <button type="button" id="toggleMobileSearch" aria-label="Toggle Search"
                    class="lg:hidden p-2 rounded-full text-[#141414] hover:bg-[#faf7f2] transition-colors cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.9" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z"/>
                </svg>
            </button>

            {{-- Account / Login --}}
            <a href="{{ auth()->check() ? route('account') : route('login') }}"
               class="relative p-2 rounded-full text-[#141414] hover:bg-[#faf7f2] transition-colors"
               title="{{ auth()->check() ? auth()->user()->name : 'Sign In / Account' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.9" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                @auth
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-[#9b742e] ring-2 ring-white"></span>
                @endauth
            </a>

            {{-- Wishlist --}}
            <a href="{{ route('wishlist.index') }}" class="relative p-2 rounded-full text-[#141414] hover:bg-[#faf7f2] transition-colors" title="Wishlist">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.9" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
                <span class="js-wishlist-count {{ $wishlistCount === 0 ? 'hidden' : '' }} absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] px-1 rounded-full bg-[#7c232b] text-white text-[10px] font-bold flex items-center justify-center">
                    {{ $wishlistCount }}
                </span>
            </a>

            {{-- Shopping Bag Trigger --}}
            <button type="button" data-open-cart class="relative p-2 rounded-full text-[#141414] hover:bg-[#faf7f2] transition-colors cursor-pointer" title="Shopping Bag">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.9" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                <span class="js-cart-count {{ $cartSummary['count'] === 0 ? 'hidden' : '' }} absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] px-1 rounded-full bg-[#141414] text-[#e3c587] text-[10px] font-bold flex items-center justify-center">
                    {{ $cartSummary['count'] }}
                </span>
            </button>
        </div>
    </div>

    {{-- Expandable Mobile & Tablet Search Bar --}}
    <div id="mobileSearchBar" class="{{ request('q') ? '' : 'hidden' }} lg:hidden border-t border-[#e6dfd3] bg-[#faf7f2] px-4 py-2.5">
        <form action="{{ route('shop') }}" method="GET" class="relative flex items-center max-w-xl mx-auto">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search Menswear & Kidswear..."
                   class="w-full h-9 pl-3.5 pr-10 rounded-full border border-[#d9d0c1] bg-white text-xs text-[#141414] focus:outline-none focus:border-[#9b742e]">
            <button type="submit" aria-label="Submit search" class="absolute right-3 text-neutral-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z"/>
                </svg>
            </button>
        </form>
    </div>

    {{-- Mobile & Tablet Bottom-Header Navigation Strip --}}
    <nav class="lg:hidden border-t border-[#efe9dd] bg-[#faf7f2]/70 px-3 py-2 flex items-center justify-around sm:justify-center sm:gap-8 overflow-x-auto no-scrollbar text-[11px] font-extrabold tracking-wider whitespace-nowrap">
        <a href="{{ route('home') }}" class="px-2 py-0.5 rounded {{ $isHome ? 'text-[#9b742e] underline underline-offset-4' : 'text-neutral-700 hover:text-[#141414]' }}">HOME</a>
        <a href="{{ route('men') }}" class="px-2 py-0.5 rounded {{ $isMen ? 'text-[#9b742e] underline underline-offset-4' : 'text-neutral-700 hover:text-[#141414]' }}">MEN</a>
        <a href="{{ route('kids') }}" class="px-2 py-0.5 rounded {{ $isKids ? 'text-[#9b742e] underline underline-offset-4' : 'text-neutral-700 hover:text-[#141414]' }}">KIDS</a>
        <a href="{{ route('shop') }}" class="px-2 py-0.5 rounded {{ $isShop ? 'text-[#9b742e] underline underline-offset-4' : 'text-neutral-700 hover:text-[#141414]' }}">SHOP</a>
        <a href="{{ route('contact') }}" class="px-2 py-0.5 rounded {{ $isContact ? 'text-[#9b742e] underline underline-offset-4' : 'text-neutral-700 hover:text-[#141414]' }}">CONTACT US</a>
    </nav>
</header>
