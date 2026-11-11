@extends('layouts.app')

@section('title', 'Your Wishlist — Royal Collection')

@section('content')
<div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">
    <div class="border-b border-[#e6dfd3] pb-4 mb-8 flex flex-wrap items-end justify-between gap-2">
        <div>
            <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-[#9b742e]">ROYAL COLLECTION SAVED</p>
            <h1 class="font-editorial text-2xl sm:text-3xl lg:text-4xl font-bold tracking-tight text-[#141414]">Your Wishlist</h1>
        </div>
        <span class="text-xs font-bold text-neutral-500">{{ $products->count() }} Saved {{ Str::plural('Item', $products->count()) }}</span>
    </div>

    @if($products->isEmpty())
        <div class="border border-[#e6dfd3] rounded-2xl py-16 sm:py-20 px-6 text-center bg-[#faf7f2]">
            <h2 class="font-editorial text-2xl font-bold text-[#141414]">Your Wishlist is Empty</h2>
            <p class="text-xs text-neutral-500 mt-1.5 mb-6">Tap the heart icon on any Mens or Kids garment to save it for later.</p>
            <div class="flex flex-wrap gap-3 justify-center">
                <a href="{{ route('men') }}" class="px-6 py-3 bg-[#141414] text-[#e3c587] text-xs font-extrabold uppercase tracking-wider rounded-xl">Shop Mens</a>
                <a href="{{ route('kids') }}" class="px-6 py-3 border border-[#141414] text-[#141414] text-xs font-extrabold uppercase tracking-wider rounded-xl">Shop Kids</a>
            </div>
        </div>
    @else
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-3.5 sm:gap-x-6 gap-y-7 sm:gap-y-8">
            @foreach($products as $product)
                @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>
    @endif
</div>
@endsection
