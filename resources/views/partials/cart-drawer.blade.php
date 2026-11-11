{{-- Slide-over Shopping Bag Drawer --}}
<div id="cartDrawerBackdrop" class="rc-drawer-backdrop fixed inset-0 z-50 bg-black/55 backdrop-blur-[2px]"></div>

<aside id="cartDrawer" class="rc-drawer-right fixed top-0 right-0 bottom-0 z-50 w-[88vw] max-w-[400px] bg-white shadow-2xl flex flex-col">
    <div class="px-5 py-4 border-b border-[#e6dfd3] flex items-center justify-between bg-[#faf7f2]">
        <div>
            <h3 class="text-sm font-extrabold uppercase tracking-wider text-[#141414]">Your Shopping Bag</h3>
            <p class="text-[11px] font-medium text-[#9b742e]">Royal Collection Luxury Apparel</p>
        </div>
        <button type="button" id="closeCartDrawer" aria-label="Close bag" class="p-1.5 rounded-lg text-neutral-600 hover:bg-white hover:text-[#141414] cursor-pointer">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    {{-- Free Shipping Progress Bar --}}
    <div class="bg-[#141414] px-5 py-3 border-b border-[#c69d54]/25">
        <p id="cartShippingBarText" class="text-[11px] font-bold text-[#f4efe6] mb-1.5">
            @if($cartSummary['subtotal'] === 0)
                Free Express Delivery on orders above ₹ 1,499
            @elseif($cartSummary['freeShippingRemaining'] > 0)
                Add ₹ {{ number_format($cartSummary['freeShippingRemaining']) }} more for FREE Express Shipping
            @else
                You unlocked FREE Express Shipping!
            @endif
        </p>
        <div class="w-full h-1.5 bg-neutral-700 rounded-full overflow-hidden">
            @php
                $pct = $cartSummary['subtotal'] > 0 ? min(100, round(($cartSummary['subtotal'] / 1499) * 100)) : 0;
            @endphp
            <div id="cartShippingProgress" class="h-full bg-[#c69d54] transition-all duration-300" style="width: {{ $pct }}%"></div>
        </div>
    </div>

    {{-- Cart Items --}}
    <div id="cartDrawerBody" class="flex-1 overflow-y-auto px-5">
        @if(empty($cartSummary['items']))
            <div class="py-16 text-center px-6">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-[#faf7f2] border border-[#e6dfd3] flex items-center justify-center text-[#9b742e]">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
                <h4 class="font-bold text-[#141414] mb-1">Your Royal Bag is Empty</h4>
                <p class="text-xs text-neutral-500 mb-5">Explore heavyweight Menswear and soft Kidswear essentials.</p>
                <div class="flex gap-2 justify-center">
                    <a href="{{ route('men') }}" class="px-4 py-2 bg-[#141414] text-[#e3c587] text-xs font-bold uppercase tracking-wider rounded-lg">Shop Men</a>
                    <a href="{{ route('kids') }}" class="px-4 py-2 border border-[#141414] text-[#141414] text-xs font-bold uppercase tracking-wider rounded-lg">Shop Kids</a>
                </div>
            </div>
        @else
            @foreach($cartSummary['items'] as $item)
                <div class="flex gap-3.5 py-4 border-b border-[#efe9dd]">
                    <a href="{{ route('product.show', $item['product']->slug) }}" class="w-20 h-24 rounded-lg bg-[#f2ece1] overflow-hidden shrink-0 border border-[#e6dfd3]">
                        <img src="{{ asset($item['product']->image) }}" alt="{{ $item['product']->name }}" class="w-full h-full object-cover object-top">
                    </a>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start gap-2">
                            <a href="{{ route('product.show', $item['product']->slug) }}" class="text-xs font-bold text-[#141414] line-clamp-2 hover:text-[#9b742e]">{{ $item['product']->name }}</a>
                            <button type="button" class="text-neutral-400 hover:text-[#7c232b] text-xs js-cart-remove cursor-pointer" data-key="{{ $item['key'] }}" title="Remove">✕</button>
                        </div>
                        <p class="text-[11px] text-neutral-500 mt-1">Size: <span class="font-semibold text-[#141414]">{{ $item['size'] }}</span></p>
                        <div class="flex items-center justify-between mt-3">
                            <div class="inline-flex items-center border border-[#d9d0c1] rounded-lg">
                                <button type="button" class="px-2.5 py-0.5 text-xs font-bold text-neutral-700 hover:bg-[#faf7f2] js-cart-qty cursor-pointer" data-key="{{ $item['key'] }}" data-qty="{{ $item['quantity'] - 1 }}">−</button>
                                <span class="px-2.5 py-0.5 text-xs font-bold text-[#141414]">{{ $item['quantity'] }}</span>
                                <button type="button" class="px-2.5 py-0.5 text-xs font-bold text-neutral-700 hover:bg-[#faf7f2] js-cart-qty cursor-pointer" data-key="{{ $item['key'] }}" data-qty="{{ $item['quantity'] + 1 }}">+</button>
                            </div>
                            <span class="text-sm font-extrabold text-[#141414]">₹ {{ number_format($item['line_total']) }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    {{-- Cart Drawer Footer --}}
    <div id="cartDrawerFooter" class="{{ empty($cartSummary['items']) ? 'hidden' : '' }} border-t border-[#e6dfd3] p-5 bg-[#faf7f2] space-y-3">
        <div class="flex items-center justify-between text-sm">
            <span class="font-semibold text-neutral-600">Subtotal</span>
            <span id="cartDrawerSubtotal" class="text-base font-extrabold text-[#141414]">₹ {{ number_format($cartSummary['subtotal']) }}</span>
        </div>
        <a href="{{ route('cart.index') }}" class="block w-full py-3.5 bg-[#141414] hover:bg-neutral-800 text-[#e3c587] text-center text-xs font-extrabold uppercase tracking-widest rounded-xl transition-colors">
            View Bag &amp; Checkout
        </a>
    </div>
</aside>
