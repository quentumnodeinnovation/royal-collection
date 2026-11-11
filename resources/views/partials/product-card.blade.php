@php
    $isWishlisted = in_array($product->id, $wishlistIds ?? [], true);
@endphp

<article class="rc-product-card group flex flex-col min-w-0" @if(!empty($filterTags)) data-filter-tags="{{ $filterTags }}" @endif>
    <div class="rc-card-img-wrap rounded-lg border border-[#e6dfd3]">
        <a href="{{ route('product.show', $product->slug) }}" class="block w-full h-full">
            <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" loading="lazy">
        </a>

        {{-- Top-Left Fit Tag --}}
        @if($product->fit_badge)
            <span class="absolute top-2 left-2 sm:top-2.5 sm:left-2.5 bg-[#141414]/85 backdrop-blur-xs text-[#f4efe6] text-[9px] sm:text-[9.5px] font-bold tracking-wider uppercase px-2 py-1 rounded-xs leading-none pointer-events-none">
                {{ $product->fit_badge }}
            </span>
        @endif

        {{-- Top-Right Wishlist Heart Button --}}
        <button type="button"
                data-wishlist-toggle
                data-product-id="{{ $product->id }}"
                aria-label="Save {{ $product->name }} to wishlist"
                class="absolute top-2 right-2 sm:top-2.5 sm:right-2.5 w-8 h-8 rounded-full bg-white/90 hover:bg-white shadow-sm flex items-center justify-center transition-colors cursor-pointer {{ $isWishlisted ? 'text-[#7c232b]' : 'text-neutral-700' }}">
            <svg class="w-4 h-4" fill="{{ $isWishlisted ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
        </button>

        {{-- Bottom-Left Fabric Tag --}}
        @if($product->fabric_badge)
            <span class="absolute bottom-2 left-2 sm:bottom-2.5 sm:left-2.5 bg-[#141414]/85 text-[#e3c587] text-[8.5px] sm:text-[9px] font-bold tracking-wider uppercase px-2 py-1 rounded-xs leading-none pointer-events-none">
                {{ $product->fabric_badge }}
            </span>
        @endif

        {{-- Hover Quick-Add Size Bar (Tablet/Desktop) --}}
        <div class="absolute inset-x-0 bottom-0 bg-[#faf7f2]/95 backdrop-blur-xs border-t border-[#e6dfd3] px-2.5 py-2 translate-y-full group-hover:translate-y-0 transition-transform duration-200 hidden sm:block">
            <div class="flex items-center justify-between gap-1 mb-1">
                <span class="text-[9.5px] font-bold uppercase tracking-wider text-neutral-500">Quick Add</span>
                <span class="text-[9.5px] font-bold uppercase text-[#9b742e]">{{ $product->audience === 'kids' ? 'Kids' : 'Mens' }}</span>
            </div>
            <div class="flex flex-wrap gap-1">
                @foreach(array_slice($product->sizes ?? [], 0, 5) as $size)
                    <button type="button"
                            data-quick-add
                            data-product-id="{{ $product->id }}"
                            data-size="{{ $size }}"
                            class="px-2 py-1 text-[10px] font-bold border border-[#d9d0c1] bg-white rounded hover:border-[#141414] hover:bg-[#141414] hover:text-[#e3c587] transition-colors cursor-pointer">
                        {{ $size }}
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Product Info Below Photo --}}
    <div class="pt-2.5 space-y-1 min-w-0">
        <a href="{{ route('product.show', $product->slug) }}" class="block text-[13px] sm:text-[13.5px] font-bold text-[#141414] hover:text-[#9b742e] truncate transition-colors">
            {{ $product->name }}
        </a>
        <div class="flex items-center justify-between gap-2 text-[11px] text-neutral-500">
            <span class="truncate">{{ $product->subtitle }}</span>
            <span class="uppercase text-[9.5px] font-bold tracking-wider px-1.5 py-0.5 rounded bg-[#f2ece1] text-[#141414] shrink-0">
                {{ $product->audience === 'kids' ? 'Kids' : 'Men' }}
            </span>
        </div>
        <div class="pt-0.5 flex flex-wrap items-baseline gap-x-2 gap-y-0.5">
            <span class="text-[13.5px] sm:text-[14px] font-extrabold text-[#141414]">₹ {{ number_format($product->price) }}</span>
            @if($product->mrp > $product->price)
                <span class="text-[11px] sm:text-xs text-neutral-400 line-through">₹ {{ number_format($product->mrp) }}</span>
                <span class="text-[10.5px] font-extrabold text-[#7c232b]">{{ $product->discount_percent }}% OFF</span>
            @endif
        </div>
    </div>
</article>
