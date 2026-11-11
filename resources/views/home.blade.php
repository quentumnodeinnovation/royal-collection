@extends('layouts.app')

@section('title', 'Royal Collection — Luxury Apparel | Mens & Kids Clothing')

@section('content')

{{-- DYNAMIC HERO CAROUSEL (Managed via /admin/sliders) --}}
@if($sliders->isNotEmpty())
<section class="relative w-full h-[430px] sm:h-[500px] lg:h-[580px] bg-[#141414] overflow-hidden">
    @foreach($sliders as $idx => $slide)
        <div data-hero-slide class="absolute inset-0 transition-opacity duration-700 {{ $idx === 0 ? 'opacity-100' : 'opacity-0 pointer-events-none' }}">
            <img src="{{ asset($slide->image) }}" alt="{{ $slide->title }}" class="w-full h-full object-cover {{ $idx === 0 ? 'object-top' : 'object-center' }}">
            <div class="absolute inset-0 bg-gradient-to-t md:bg-gradient-to-r from-black/85 via-black/55 to-black/15"></div>
            <div class="absolute inset-0 max-w-[1440px] mx-auto px-5 sm:px-8 lg:px-12 pb-12 sm:pb-0 flex flex-col justify-end sm:justify-center">
                @if($slide->eyebrow)
                    <span class="inline-flex items-center gap-2 text-[10px] sm:text-[11px] font-bold uppercase tracking-[0.22em] text-[#e3c587] mb-2.5 sm:mb-3">
                        <span class="w-5 sm:w-6 h-[1.5px] bg-[#e3c587]"></span>
                        {{ $slide->eyebrow }}
                    </span>
                @endif
                @if($idx === 0)
                    <h1 class="font-editorial text-3xl sm:text-5xl lg:text-6xl font-bold text-white tracking-tight max-w-2xl leading-[1.05]">
                        {{ $slide->title }}
                    </h1>
                @else
                    <h2 class="font-editorial text-3xl sm:text-5xl lg:text-6xl font-bold text-white tracking-tight max-w-2xl leading-[1.05]">
                        {{ $slide->title }}
                    </h2>
                @endif
                @if($slide->subtitle)
                    <p class="mt-3 sm:mt-4 text-xs sm:text-sm lg:text-base text-neutral-200 max-w-lg leading-relaxed line-clamp-3 sm:line-clamp-none">
                        {{ $slide->subtitle }}
                    </p>
                @endif
                <div class="mt-5 sm:mt-7 flex flex-wrap gap-2.5 sm:gap-3">
                    @if($slide->primary_cta_label && $slide->primary_cta_url)
                        <a href="{{ str_starts_with($slide->primary_cta_url, 'http') ? $slide->primary_cta_url : url($slide->primary_cta_url) }}" class="px-5 sm:px-7 py-3 sm:py-3.5 bg-[#e3c587] text-[#141414] text-[11px] sm:text-xs font-extrabold uppercase tracking-widest rounded-lg hover:bg-white transition-colors">
                            {{ $slide->primary_cta_label }}
                        </a>
                    @endif
                    @if($slide->secondary_cta_label && $slide->secondary_cta_url)
                        <a href="{{ str_starts_with($slide->secondary_cta_url, 'http') ? $slide->secondary_cta_url : url($slide->secondary_cta_url) }}" class="px-5 sm:px-7 py-3 sm:py-3.5 border border-white/80 text-white text-[11px] sm:text-xs font-extrabold uppercase tracking-widest rounded-lg hover:bg-white hover:text-[#141414] transition-colors">
                            {{ $slide->secondary_cta_label }}
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @endforeach

    @if($sliders->count() > 1)
        <button type="button" id="heroPrev" aria-label="Previous slide" class="hidden md:flex absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-black/45 hover:bg-black/75 border border-white/20 text-white items-center justify-center backdrop-blur-xs cursor-pointer transition-colors">‹</button>
        <button type="button" id="heroNext" aria-label="Next slide" class="hidden md:flex absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-black/45 hover:bg-black/75 border border-white/20 text-white items-center justify-center backdrop-blur-xs cursor-pointer transition-colors">›</button>

        <div class="absolute bottom-4 sm:bottom-5 left-1/2 -translate-x-1/2 flex items-center gap-2">
            @foreach($sliders as $idx => $slide)
                <button type="button" data-hero-dot aria-label="Slide {{ $idx + 1 }}" class="h-2 {{ $idx === 0 ? 'w-7 bg-white' : 'w-2.5 bg-white/45' }} rounded-full transition-all cursor-pointer"></button>
            @endforeach
        </div>
    @endif
</section>
@endif

{{-- DUAL AUDIENCE SHOWCASE: STRICTLY MENS & KIDS --}}
<section class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 pt-8 sm:pt-10 pb-4">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5">
        <a href="{{ route('men') }}" class="group relative h-64 sm:h-72 lg:h-80 rounded-xl overflow-hidden bg-[#141414] border border-[#e6dfd3]">
            <img src="{{ asset('images/products/men-oversized-obsidian.jpg') }}" alt="Mens Collection" class="w-full h-full object-cover object-top opacity-85 group-hover:scale-105 transition-transform duration-500">
            <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/30 to-transparent"></div>
            <div class="absolute bottom-5 left-5 right-5 sm:bottom-6 sm:left-6 sm:right-6 flex flex-col sm:flex-row sm:items-end justify-between gap-3">
                <div class="min-w-0">
                    <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#e3c587]">6 Categories · Heavyweight Craft</span>
                    <h2 class="font-editorial text-2xl sm:text-3xl font-bold text-white tracking-tight mt-0.5">MENS COLLECTION</h2>
                    <p class="text-xs text-neutral-300 mt-1 line-clamp-1">Oversized Tees, Linen Shirts, Polos, Hoodies, Jeans &amp; Cargos</p>
                </div>
                <span class="self-start sm:self-auto px-4 py-2 bg-white text-[#141414] text-[11px] font-extrabold uppercase tracking-wider rounded-lg shrink-0 group-hover:bg-[#e3c587] transition-colors">
                    Shop Men →
                </span>
            </div>
        </a>

        <a href="{{ route('kids') }}" class="group relative h-64 sm:h-72 lg:h-80 rounded-xl overflow-hidden bg-[#141414] border border-[#e6dfd3]">
            <img src="{{ asset('images/products/kids-denim-mint-set.jpg') }}" alt="Kids Collection" class="w-full h-full object-cover object-top opacity-90 group-hover:scale-105 transition-transform duration-500">
            <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/30 to-transparent"></div>
            <div class="absolute bottom-5 left-5 right-5 sm:bottom-6 sm:left-6 sm:right-6 flex flex-col sm:flex-row sm:items-end justify-between gap-3">
                <div class="min-w-0">
                    <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#e3c587]">5 Categories · Ages 2Y–13Y</span>
                    <h2 class="font-editorial text-2xl sm:text-3xl font-bold text-white tracking-tight mt-0.5">KIDS COLLECTION</h2>
                    <p class="text-xs text-neutral-300 mt-1 line-clamp-1">Bio-Wash Tees, Varsity Hoodies, Co-ord Sets &amp; Denim</p>
                </div>
                <span class="self-start sm:self-auto px-4 py-2 bg-white text-[#141414] text-[11px] font-extrabold uppercase tracking-wider rounded-lg shrink-0 group-hover:bg-[#e3c587] transition-colors">
                    Shop Kids →
                </span>
            </div>
        </a>
    </div>
</section>

{{-- INTERACTIVE FILTER PILLS & 4-COLUMN EDITORIAL PRODUCT GRID --}}
<section class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-3 mb-5">
        <div>
            <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-[#9b742e]">ROYAL COLLECTION DROPS</p>
            <h2 class="font-editorial text-2xl sm:text-3xl lg:text-4xl font-bold tracking-tight text-[#141414]">Explore Trending Apparel</h2>
        </div>
        <div class="flex items-center justify-between sm:justify-end gap-4">
            <span id="homeFilterCount" class="text-xs font-bold text-neutral-500">{{ $trendingProducts->count() }} Styles</span>
            <a href="{{ route('shop') }}" class="text-xs font-extrabold uppercase tracking-wider text-[#141414] border-b-2 border-[#c69d54] pb-0.5 hover:text-[#9b742e] transition-colors">
                View All Products →
            </a>
        </div>
    </div>

    {{-- Filter Pill Row --}}
    <div class="flex items-center gap-2 sm:gap-2.5 overflow-x-auto no-scrollbar pb-3 mb-6">
        <button type="button" data-home-filter="trending" class="rc-pill active">Trending</button>
        <button type="button" data-home-filter="men-oversized-tshirts" class="rc-pill">Oversized Tees</button>
        <button type="button" data-home-filter="men-shirts" class="rc-pill">Shirts</button>
        <button type="button" data-home-filter="men-polos" class="rc-pill">Polos</button>
        <button type="button" data-home-filter="men-hoodies" class="rc-pill">Hoodies</button>
        <button type="button" data-home-filter="men-jeans-pants" class="rc-pill">Jeans &amp; Pants</button>
        <button type="button" data-home-filter="men-cargos-joggers" class="rc-pill">Cargos</button>
        <button type="button" data-home-filter="kids" class="rc-pill">Kids Wear</button>
    </div>

    {{-- 4-Column Product Grid --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-3.5 sm:gap-x-6 gap-y-7 sm:gap-y-8">
        @foreach($allProducts as $product)
            @php
                $tags = [$product->category->slug, $product->audience];
                if ($product->is_trending) {
                    $tags[] = 'trending';
                }
                $isInitialVisible = $product->is_trending;
            @endphp
            <div class="{{ $isInitialVisible ? '' : 'hidden' }}" data-filter-tags="{{ implode(',', $tags) }}">
                @include('partials.product-card', ['product' => $product])
            </div>
        @endforeach
    </div>
</section>

{{-- MENS CATEGORIES EDITORIAL BAND --}}
<section class="bg-[#faf7f2] border-y border-[#e6dfd3] py-10 sm:py-12">
    <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-2 mb-6 sm:mb-7">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-[#9b742e]">MENSWARDROBE</p>
                <h2 class="font-editorial text-2xl sm:text-3xl lg:text-4xl font-bold tracking-tight text-[#141414]">Shop Mens Categories</h2>
            </div>
            <a href="{{ route('men') }}" class="text-xs font-extrabold uppercase tracking-wider text-[#9b742e] hover:text-[#141414] hover:underline self-start sm:self-auto">
                Shop All Mens →
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3.5 sm:gap-4">
            @foreach($menCategories as $cat)
                <a href="{{ route('category.show', $cat->slug) }}" class="group block bg-white border border-[#e6dfd3] rounded-xl overflow-hidden hover:shadow-md hover:border-[#c69d54] transition-all">
                    <div class="aspect-[4/5] bg-[#f2ece1] overflow-hidden">
                        <img src="{{ asset($cat->image) }}" alt="{{ $cat->name }}" class="w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <div class="p-3">
                        <h3 class="text-xs font-extrabold uppercase tracking-wider text-[#141414] group-hover:text-[#9b742e] transition-colors truncate">
                            {{ $cat->name }}
                        </h3>
                        <p class="text-[11px] text-neutral-500 mt-0.5 line-clamp-1">{{ $cat->tagline }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- KIDS CATEGORIES & SPOTLIGHT GRID --}}
<section class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-12">
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-2 mb-6 sm:mb-7">
        <div>
            <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-[#9b742e]">ROYAL KIDS STUDIO</p>
            <h2 class="font-editorial text-2xl sm:text-3xl lg:text-4xl font-bold tracking-tight text-[#141414]">Shop Kids Categories</h2>
        </div>
        <a href="{{ route('kids') }}" class="text-xs font-extrabold uppercase tracking-wider text-[#141414] border-b-2 border-[#c69d54] pb-0.5 hover:text-[#9b742e] self-start sm:self-auto">
            Shop All Kids →
        </a>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3.5 sm:gap-4 mb-10 sm:mb-12">
        @foreach($kidsCategories as $cat)
            <a href="{{ route('category.show', $cat->slug) }}" class="group block bg-white border border-[#e6dfd3] rounded-xl overflow-hidden hover:shadow-md hover:border-[#c69d54] transition-all">
                <div class="aspect-[4/5] bg-[#f2ece1] overflow-hidden">
                    <img src="{{ asset($cat->image) }}" alt="{{ $cat->name }}" class="w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-300">
                </div>
                <div class="p-3 bg-white">
                    <h3 class="text-xs font-extrabold uppercase tracking-wider text-[#141414] group-hover:text-[#9b742e] transition-colors truncate">
                        {{ $cat->name }}
                    </h3>
                    <p class="text-[11px] text-neutral-500 mt-0.5 line-clamp-1">{{ $cat->tagline }}</p>
                </div>
            </a>
        @endforeach
    </div>

    {{-- Kids Featured Products Row --}}
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-2 mb-6">
        <div>
            <h3 class="font-editorial text-2xl sm:text-3xl font-bold tracking-tight text-[#141414]">Kids Bestsellers</h3>
            <p class="text-xs text-neutral-500 mt-0.5">Comfy bio-washed fabrics built for ages 2 to 13</p>
        </div>
        <a href="{{ route('kids') }}" class="text-xs font-bold text-[#9b742e] hover:underline self-start sm:self-auto">Explore Kids →</a>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-x-3.5 sm:gap-x-6 gap-y-7 sm:gap-y-8">
        @foreach($kidsShowcase as $product)
            @include('partials.product-card', ['product' => $product])
        @endforeach
    </div>
</section>

{{-- EDITORIAL BRAND PROMISE & CONTACT CTA --}}
<section class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 pb-6">
    <div class="bg-[#141414] text-white rounded-2xl border border-[#c69d54]/30 p-6 sm:p-10 lg:p-12 flex flex-col lg:flex-row items-center justify-between gap-6 sm:gap-8">
        <div class="flex flex-col sm:flex-row items-center gap-5 sm:gap-6 text-center sm:text-left">
            <div class="bg-white p-3.5 rounded-xl shrink-0">
                <img src="{{ asset('images/royal-collection-logo.png') }}" alt="Royal Collection Logo" class="h-12 sm:h-14 w-auto object-contain">
            </div>
            <div>
                <p class="text-[10.5px] font-bold uppercase tracking-[0.22em] text-[#e3c587]">BESPOKE FIT &amp; CONCIERGE</p>
                <h2 class="font-editorial text-2xl sm:text-3xl font-bold tracking-tight mt-1">Need Help With Sizing or Bulk Orders?</h2>
                <p class="text-xs sm:text-sm text-neutral-300 mt-1.5 max-w-xl">
                    Connect with our Royal Collection team for Mens &amp; Kids fit guidance, custom studio appointments, or order tracking.
                </p>
            </div>
        </div>
        <div class="flex flex-wrap justify-center gap-3 shrink-0 w-full sm:w-auto">
            <a href="{{ route('contact') }}" class="flex-1 sm:flex-initial text-center px-6 sm:px-7 py-3.5 bg-[#e3c587] text-[#141414] text-xs font-extrabold uppercase tracking-widest rounded-xl hover:bg-white transition-colors">
                Contact Us
            </a>
            <a href="{{ route('shop') }}" class="flex-1 sm:flex-initial text-center px-6 sm:px-7 py-3.5 border border-neutral-600 text-white text-xs font-extrabold uppercase tracking-widest rounded-xl hover:border-[#e3c587] hover:text-[#e3c587] transition-colors">
                Browse Shop
            </a>
        </div>
    </div>
</section>

@endsection
