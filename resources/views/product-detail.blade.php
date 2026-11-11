@extends('layouts.app')

@section('title', $product->name . ' — Royal Collection')

@section('content')
@php
    $isWishlisted = in_array($product->id, $wishlistIds ?? [], true);
@endphp

<div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
    {{-- Breadcrumb --}}
    <nav class="text-[11px] font-semibold text-neutral-500 flex flex-wrap items-center gap-1.5 mb-5 sm:mb-6">
        <a href="{{ route('home') }}" class="hover:text-[#141414]">Home</a>
        <span>/</span>
        <a href="{{ route($product->audience) }}" class="uppercase hover:text-[#141414]">{{ $product->audience === 'men' ? 'Mens' : 'Kids' }}</a>
        <span>/</span>
        <a href="{{ route('category.show', $product->category->slug) }}" class="hover:text-[#141414]">{{ $product->category->name }}</a>
        <span>/</span>
        <span class="text-[#141414] truncate">{{ $product->name }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">
        {{-- Left: Studio Image Showcase --}}
        <div class="lg:col-span-7">
            <div class="relative aspect-[4/5] sm:aspect-[3/4] max-h-[560px] lg:max-h-[760px] w-full bg-[#f2ece1] rounded-xl overflow-hidden border border-[#e6dfd3]">
                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover object-top">

                @if($product->fit_badge)
                    <span class="absolute top-3.5 left-3.5 sm:top-4 sm:left-4 bg-[#141414]/85 text-[#f4efe6] text-[10px] sm:text-[11px] font-bold tracking-widest uppercase px-3 py-1.5 rounded-xs">
                        {{ $product->fit_badge }}
                    </span>
                @endif

                @if($product->fabric_badge)
                    <span class="absolute bottom-3.5 left-3.5 sm:bottom-4 sm:left-4 bg-[#141414]/85 text-[#e3c587] text-[10px] sm:text-[11px] font-bold tracking-wider uppercase px-3 py-1.5 rounded-xs">
                        {{ $product->fabric_badge }}
                    </span>
                @endif
            </div>
        </div>

        {{-- Right: Product Buy Box --}}
        <div class="lg:col-span-5">
            <div class="lg:sticky lg:top-24 space-y-6">
                <div class="border-b border-[#e6dfd3] pb-5">
                    <div class="flex flex-wrap items-center justify-between gap-2 mb-2">
                        <span class="text-[11px] font-extrabold uppercase tracking-[0.18em] text-[#9b742e]">
                            ROYAL COLLECTION · {{ $product->audience === 'kids' ? 'KIDS EDITION' : 'MENS EDITION' }}
                        </span>
                        <span class="text-[11px] font-semibold text-neutral-400">SKU: {{ $product->sku }}</span>
                    </div>

                    <h1 class="font-editorial text-2xl sm:text-3xl lg:text-4xl font-bold text-[#141414] tracking-tight leading-snug">
                        {{ $product->name }}
                    </h1>
                    <p class="text-xs sm:text-sm text-neutral-500 mt-1">{{ $product->subtitle }}</p>

                    <div class="mt-4 flex flex-wrap items-baseline gap-3">
                        <span class="text-2xl font-extrabold text-[#141414]">₹ {{ number_format($product->price) }}</span>
                        @if($product->mrp > $product->price)
                            <span class="text-base text-neutral-400 line-through">₹ {{ number_format($product->mrp) }}</span>
                            <span class="text-xs font-extrabold uppercase px-2.5 py-0.5 rounded bg-[#f9ecee] text-[#7c232b]">
                                {{ $product->discount_percent }}% OFF
                            </span>
                        @endif
                    </div>
                    <p class="text-[11px] text-neutral-500 mt-1">MRP inclusive of all taxes · Free Express Shipping above ₹1,499</p>
                </div>

                {{-- Color Indicator --}}
                <div class="flex items-center gap-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-neutral-700">Color:</span>
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full border border-[#d9d0c1] bg-[#faf7f2] text-xs font-semibold text-[#141414]">
                        <span class="w-3.5 h-3.5 rounded-full border border-neutral-300" style="background-color: {{ $product->color_hex }}"></span>
                        {{ $product->color_name }}
                    </span>
                </div>

                {{-- Add to Bag Form --}}
                <form id="detailAddToBagForm" action="{{ route('cart.add') }}" method="POST" class="space-y-5">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    <div>
                        <div class="flex items-center justify-between mb-2.5">
                            <span class="text-xs font-extrabold uppercase tracking-wider text-[#141414]">
                                Select {{ $product->audience === 'kids' ? 'Age / Size' : 'Size' }}
                            </span>
                            <button type="button" onclick="document.getElementById('sizeGuideModal').classList.remove('hidden')" class="text-[11px] font-bold text-[#9b742e] hover:underline cursor-pointer">
                                Size Guide
                            </button>
                        </div>

                        <div class="flex flex-wrap gap-2">
                            @foreach($product->sizes as $idx => $size)
                                <label class="cursor-pointer">
                                    <input type="radio" name="size" value="{{ $size }}" {{ $idx === 0 || $idx === 1 ? ($idx === 0 && count($product->sizes) === 1 ? 'checked' : ($idx === 1 ? 'checked' : '')) : '' }} class="sr-only peer" required>
                                    <span class="min-w-[52px] h-11 px-3 inline-flex items-center justify-center border border-[#d9d0c1] rounded-lg text-xs font-extrabold text-[#141414] bg-white peer-checked:border-[#141414] peer-checked:bg-[#141414] peer-checked:text-[#e3c587] hover:border-[#141414] transition-colors">
                                        {{ $size }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <label class="text-xs font-extrabold uppercase tracking-wider text-[#141414]">Qty</label>
                        <select name="quantity" class="h-11 px-3 border border-[#d9d0c1] rounded-lg text-xs font-bold bg-white focus:outline-none focus:border-[#9b742e]">
                            @for($q = 1; $q <= 5; $q++)
                                <option value="{{ $q }}">{{ $q }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 pt-1">
                        <button type="submit" class="sm:col-span-8 h-12 bg-[#141414] hover:bg-neutral-800 text-[#e3c587] text-xs font-extrabold uppercase tracking-widest rounded-xl transition-colors cursor-pointer">
                            Add to Bag
                        </button>
                        <button type="button"
                                data-wishlist-toggle
                                data-product-id="{{ $product->id }}"
                                class="sm:col-span-4 h-12 border border-[#d9d0c1] bg-[#faf7f2] hover:border-[#141414] rounded-xl flex items-center justify-center gap-2 text-xs font-extrabold uppercase tracking-wider cursor-pointer {{ $isWishlisted ? 'text-[#7c232b]' : 'text-[#141414]' }}">
                            <svg class="w-4 h-4" fill="{{ $isWishlisted ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                            <span>Wishlist</span>
                        </button>
                    </div>
                </form>

                {{-- Description & Fabric Info --}}
                <div class="border-t border-[#e6dfd3] pt-5 space-y-4">
                    <div>
                        <h2 class="text-xs font-extrabold uppercase tracking-wider text-[#141414] mb-1.5">Product Details</h2>
                        <p class="text-xs sm:text-sm text-neutral-600 leading-relaxed">{{ $product->description }}</p>
                    </div>

                    @if($product->fabric_details)
                        <div class="bg-[#faf7f2] border border-[#e6dfd3] rounded-xl p-4">
                            <h3 class="text-[11px] font-extrabold uppercase tracking-wider text-[#9b742e] mb-1">Fabric &amp; Craftsmanship</h3>
                            <p class="text-xs text-neutral-700">{{ $product->fabric_details }}</p>
                        </div>
                    @endif

                    <div class="grid grid-cols-2 gap-3 text-[11px] text-neutral-600 pt-1">
                        <div class="border border-[#e6dfd3] rounded-xl p-3 bg-white">
                            <p class="font-extrabold text-[#141414]">Express Delivery</p>
                            <p class="mt-0.5">Dispatched within 24 hours across India</p>
                        </div>
                        <div class="border border-[#e6dfd3] rounded-xl p-3 bg-white">
                            <p class="font-extrabold text-[#141414]">7-Day Exchange</p>
                            <p class="mt-0.5">Instant doorstep size swap guarantee</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Related Products --}}
    @if($relatedProducts->isNotEmpty())
        <section class="mt-14 sm:mt-16 pt-10 border-t border-[#e6dfd3]">
            <div class="flex items-end justify-between mb-6">
                <h2 class="font-editorial text-2xl sm:text-3xl font-bold tracking-tight text-[#141414]">
                    More From {{ $product->audience === 'kids' ? 'Royal Kids' : 'Royal Mens' }}
                </h2>
                <a href="{{ route($product->audience) }}" class="text-xs font-bold text-[#9b742e] hover:underline">View All →</a>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-x-3.5 sm:gap-x-6 gap-y-7 sm:gap-y-8">
                @foreach($relatedProducts as $rel)
                    @include('partials.product-card', ['product' => $rel])
                @endforeach
            </div>
        </section>
    @endif
</div>

{{-- Size Guide Modal --}}
<div id="sizeGuideModal" class="hidden fixed inset-0 z-50 bg-black/55 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl border border-[#e6dfd3] max-w-lg w-full p-5 sm:p-6 shadow-2xl space-y-4">
        <div class="flex items-center justify-between border-b border-[#e6dfd3] pb-3">
            <h3 class="text-sm font-extrabold uppercase tracking-wider text-[#141414]">Royal Collection Size Guide</h3>
            <button type="button" onclick="document.getElementById('sizeGuideModal').classList.add('hidden')" class="text-neutral-500 hover:text-[#141414] font-bold cursor-pointer">✕</button>
        </div>
        @if($product->audience === 'kids')
            <p class="text-xs text-neutral-600">Standard Indian Kids Apparel Sizing (Inches):</p>
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left border border-[#e6dfd3]">
                    <thead class="bg-[#faf7f2] font-bold text-[#141414]">
                        <tr><th class="p-2.5 border border-[#e6dfd3]">Age Size</th><th class="p-2.5 border border-[#e6dfd3]">Chest (in)</th><th class="p-2.5 border border-[#e6dfd3]">Length (in)</th></tr>
                    </thead>
                    <tbody>
                        <tr><td class="p-2.5 border border-[#e6dfd3] font-bold">4-5Y</td><td class="p-2.5 border border-[#e6dfd3]">25"</td><td class="p-2.5 border border-[#e6dfd3]">17.5"</td></tr>
                        <tr><td class="p-2.5 border border-[#e6dfd3] font-bold">6-7Y</td><td class="p-2.5 border border-[#e6dfd3]">27"</td><td class="p-2.5 border border-[#e6dfd3]">19"</td></tr>
                        <tr><td class="p-2.5 border border-[#e6dfd3] font-bold">8-9Y</td><td class="p-2.5 border border-[#e6dfd3]">29.5"</td><td class="p-2.5 border border-[#e6dfd3]">21"</td></tr>
                        <tr><td class="p-2.5 border border-[#e6dfd3] font-bold">10-11Y</td><td class="p-2.5 border border-[#e6dfd3]">32"</td><td class="p-2.5 border border-[#e6dfd3]">23"</td></tr>
                        <tr><td class="p-2.5 border border-[#e6dfd3] font-bold">12-13Y</td><td class="p-2.5 border border-[#e6dfd3]">34.5"</td><td class="p-2.5 border border-[#e6dfd3]">24.5"</td></tr>
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-xs text-neutral-600">Royal Mens Tailored &amp; Oversized Garment Measurements (Inches):</p>
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left border border-[#e6dfd3]">
                    <thead class="bg-[#faf7f2] font-bold text-[#141414]">
                        <tr><th class="p-2.5 border border-[#e6dfd3]">Size</th><th class="p-2.5 border border-[#e6dfd3]">Chest (in)</th><th class="p-2.5 border border-[#e6dfd3]">Length (in)</th><th class="p-2.5 border border-[#e6dfd3]">Waist (Bottoms)</th></tr>
                    </thead>
                    <tbody>
                        <tr><td class="p-2.5 border border-[#e6dfd3] font-bold">S / 28-30</td><td class="p-2.5 border border-[#e6dfd3]">40"</td><td class="p-2.5 border border-[#e6dfd3]">27.5"</td><td class="p-2.5 border border-[#e6dfd3]">28" - 30"</td></tr>
                        <tr><td class="p-2.5 border border-[#e6dfd3] font-bold">M / 32</td><td class="p-2.5 border border-[#e6dfd3]">42"</td><td class="p-2.5 border border-[#e6dfd3]">28.5"</td><td class="p-2.5 border border-[#e6dfd3]">32"</td></tr>
                        <tr><td class="p-2.5 border border-[#e6dfd3] font-bold">L / 34</td><td class="p-2.5 border border-[#e6dfd3]">44"</td><td class="p-2.5 border border-[#e6dfd3]">29.5"</td><td class="p-2.5 border border-[#e6dfd3]">34"</td></tr>
                        <tr><td class="p-2.5 border border-[#e6dfd3] font-bold">XL / 36</td><td class="p-2.5 border border-[#e6dfd3]">46"</td><td class="p-2.5 border border-[#e6dfd3]">30.5"</td><td class="p-2.5 border border-[#e6dfd3]">36"</td></tr>
                        <tr><td class="p-2.5 border border-[#e6dfd3] font-bold">XXL</td><td class="p-2.5 border border-[#e6dfd3]">48"</td><td class="p-2.5 border border-[#e6dfd3]">31.5"</td><td class="p-2.5 border border-[#e6dfd3]">38"</td></tr>
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
