@extends('layouts.app')

@section('title', 'Shopping Bag & Checkout — Royal Collection')

@section('content')
<div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">
    <div class="border-b border-[#e6dfd3] pb-4 mb-8 flex flex-wrap items-end justify-between gap-3">
        <div>
            <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-[#9b742e]">ROYAL COLLECTION BAG</p>
            <h1 class="font-editorial text-2xl sm:text-3xl lg:text-4xl font-bold tracking-tight text-[#141414]">Shopping Bag &amp; Checkout</h1>
        </div>
        <a href="{{ route('shop') }}" class="text-xs font-bold text-[#9b742e] hover:text-[#141414] underline underline-offset-4">Continue Shopping</a>
    </div>

    @if(empty($cartSummary['items']))
        <div class="border border-[#e6dfd3] rounded-2xl py-16 sm:py-20 px-6 text-center bg-[#faf7f2]">
            <h2 class="font-editorial text-2xl font-bold text-[#141414]">Your Royal Collection Bag is Empty</h2>
            <p class="text-xs text-neutral-500 mt-2 mb-6">Explore curated heavyweight Menswear and playful Kidswear essentials.</p>
            <div class="flex flex-wrap gap-3 justify-center">
                <a href="{{ route('men') }}" class="px-6 py-3 bg-[#141414] text-[#e3c587] text-xs font-extrabold uppercase tracking-wider rounded-xl">Explore Mens</a>
                <a href="{{ route('kids') }}" class="px-6 py-3 border border-[#141414] text-[#141414] text-xs font-extrabold uppercase tracking-wider rounded-xl">Explore Kids</a>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10">
            {{-- Left: Bag Items --}}
            <div class="lg:col-span-7 space-y-4">
                @foreach($cartSummary['items'] as $item)
                    <div class="flex gap-3.5 sm:gap-4 p-3.5 sm:p-4 border border-[#e6dfd3] rounded-xl bg-white">
                        <a href="{{ route('product.show', $item['product']->slug) }}" class="w-20 h-26 sm:w-24 sm:h-32 rounded-lg bg-[#f2ece1] overflow-hidden shrink-0 border border-[#e6dfd3]">
                            <img src="{{ asset($item['product']->image) }}" alt="{{ $item['product']->name }}" class="w-full h-full object-cover object-top">
                        </a>
                        <div class="flex-1 min-w-0 flex flex-col justify-between">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-[#9b742e]">{{ $item['product']->audience === 'kids' ? 'Kids' : 'Mens' }} · {{ $item['product']->subtitle }}</span>
                                    <a href="{{ route('product.show', $item['product']->slug) }}" class="block text-sm font-extrabold text-[#141414] hover:text-[#9b742e] truncate sm:whitespace-normal">
                                        {{ $item['product']->name }}
                                    </a>
                                    <p class="text-xs text-neutral-600 mt-1">Size: <span class="font-bold text-[#141414]">{{ $item['size'] }}</span></p>
                                </div>
                                <form action="{{ route('cart.remove') }}" method="POST" class="shrink-0">
                                    @csrf
                                    <input type="hidden" name="key" value="{{ $item['key'] }}">
                                    <button type="submit" class="text-xs text-neutral-400 hover:text-[#7c232b] font-bold cursor-pointer">Remove</button>
                                </form>
                            </div>

                            <div class="flex flex-wrap items-center justify-between gap-2 pt-3 border-t border-[#efe9dd] mt-2">
                                <form action="{{ route('cart.update') }}" method="POST" class="inline-flex items-center border border-[#d9d0c1] rounded-lg bg-[#faf7f2]">
                                    @csrf
                                    <input type="hidden" name="key" value="{{ $item['key'] }}">
                                    <button type="submit" name="quantity" value="{{ $item['quantity'] - 1 }}" class="px-2.5 py-1 text-xs font-bold hover:bg-white rounded-l-lg cursor-pointer">−</button>
                                    <span class="px-3 py-1 text-xs font-bold">{{ $item['quantity'] }}</span>
                                    <button type="submit" name="quantity" value="{{ $item['quantity'] + 1 }}" class="px-2.5 py-1 text-xs font-bold hover:bg-white rounded-r-lg cursor-pointer">+</button>
                                </form>
                                <div class="text-right">
                                    <p class="text-sm font-extrabold text-[#141414]">₹ {{ number_format($item['line_total']) }}</p>
                                    <p class="text-[11px] text-neutral-400">₹ {{ number_format($item['product']->price) }} each</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Right: Order Summary & Express Checkout Form --}}
            <div class="lg:col-span-5">
                <div class="bg-[#faf7f2] border border-[#e6dfd3] rounded-2xl p-5 sm:p-6 space-y-6">
                    <div>
                        <h2 class="text-sm font-extrabold uppercase tracking-wider text-[#141414] mb-4">Order Summary</h2>
                        <div class="space-y-2.5 text-xs">
                            <div class="flex justify-between">
                                <span class="text-neutral-600">Bag Subtotal ({{ $cartSummary['count'] }} items)</span>
                                <span class="font-bold text-[#141414]">₹ {{ number_format($cartSummary['subtotal']) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-neutral-600">Express Delivery</span>
                                <span class="font-bold {{ $cartSummary['shipping'] === 0 ? 'text-emerald-700' : 'text-[#141414]' }}">
                                    {{ $cartSummary['shipping'] === 0 ? 'FREE' : '₹ ' . number_format($cartSummary['shipping']) }}
                                </span>
                            </div>
                            <div class="pt-3 border-t border-[#e6dfd3] flex justify-between text-base font-extrabold text-[#141414]">
                                <span>Total Payable</span>
                                <span>₹ {{ number_format($cartSummary['total']) }}</span>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('cart.checkout') }}" method="POST" class="space-y-4 border-t border-[#e6dfd3] pt-5">
                        @csrf
                        <h3 class="text-xs font-extrabold uppercase tracking-wider text-[#141414]">Shipping Details</h3>

                        @if($errors->any())
                            <div class="p-3 bg-[#f9ecee] border border-[#e3b8be] text-[#7c232b] text-xs rounded-xl">
                                {{ $errors->first() }}
                            </div>
                        @endif

                        <div>
                            <label class="block text-[11px] font-bold text-neutral-700 mb-1">Full Name *</label>
                            <input type="text" name="customer_name" value="{{ old('customer_name', auth()->user()?->name) }}" required
                                   class="w-full h-10 px-3 border border-[#d9d0c1] rounded-xl bg-white text-xs focus:outline-none focus:border-[#9b742e]">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[11px] font-bold text-neutral-700 mb-1">Email Address *</label>
                                <input type="email" name="email" value="{{ old('email', auth()->user()?->email) }}" required
                                       class="w-full h-10 px-3 border border-[#d9d0c1] rounded-xl bg-white text-xs focus:outline-none focus:border-[#9b742e]">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-neutral-700 mb-1">Phone Number *</label>
                                <input type="text" name="phone" value="{{ old('phone', auth()->user()?->phone) }}" required placeholder="+91 98XXXXXXXX"
                                       class="w-full h-10 px-3 border border-[#d9d0c1] rounded-xl bg-white text-xs focus:outline-none focus:border-[#9b742e]">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-neutral-700 mb-1">Delivery Address *</label>
                            <textarea name="address" rows="2" required class="w-full p-3 border border-[#d9d0c1] rounded-xl bg-white text-xs focus:outline-none focus:border-[#9b742e]">{{ old('address') }}</textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[11px] font-bold text-neutral-700 mb-1">City *</label>
                                <input type="text" name="city" value="{{ old('city', auth()->user()?->city) }}" required
                                       class="w-full h-10 px-3 border border-[#d9d0c1] rounded-xl bg-white text-xs focus:outline-none focus:border-[#9b742e]">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-neutral-700 mb-1">PIN Code *</label>
                                <input type="text" name="pincode" value="{{ old('pincode') }}" required
                                       class="w-full h-10 px-3 border border-[#d9d0c1] rounded-xl bg-white text-xs focus:outline-none focus:border-[#9b742e]">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-neutral-700 mb-1.5">Payment Method *</label>
                            <div class="grid grid-cols-3 gap-2 text-xs font-bold">
                                <label class="cursor-pointer">
                                    <input type="radio" name="payment_method" value="cod" checked class="sr-only peer">
                                    <span class="block text-center py-2.5 border border-[#d9d0c1] rounded-xl bg-white peer-checked:bg-[#141414] peer-checked:text-[#e3c587] peer-checked:border-[#141414]">Cash / COD</span>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="payment_method" value="upi" class="sr-only peer">
                                    <span class="block text-center py-2.5 border border-[#d9d0c1] rounded-xl bg-white peer-checked:bg-[#141414] peer-checked:text-[#e3c587] peer-checked:border-[#141414]">UPI</span>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="payment_method" value="card" class="sr-only peer">
                                    <span class="block text-center py-2.5 border border-[#d9d0c1] rounded-xl bg-white peer-checked:bg-[#141414] peer-checked:text-[#e3c587] peer-checked:border-[#141414]">Card</span>
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="w-full h-12 bg-[#141414] hover:bg-neutral-800 text-[#e3c587] text-xs font-extrabold uppercase tracking-widest rounded-xl transition-colors cursor-pointer">
                            Place Order · ₹ {{ number_format($cartSummary['total']) }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
