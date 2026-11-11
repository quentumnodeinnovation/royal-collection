@extends('layouts.app')

@section('title', 'Order Confirmed #' . $order->order_number . ' — Royal Collection')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14">
    <div class="border border-[#e6dfd3] rounded-2xl p-6 sm:p-8 bg-white shadow-sm space-y-6">
        <div class="text-center border-b border-[#e6dfd3] pb-6">
            <div class="w-14 h-14 mx-auto mb-4 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center justify-center">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <p class="text-[11px] font-extrabold uppercase tracking-[0.2em] text-emerald-700">Order Confirmed</p>
            <h1 class="font-editorial text-2xl sm:text-3xl font-bold text-[#141414] mt-1">Thank You, {{ $order->customer_name }}!</h1>
            <p class="text-xs text-neutral-500 mt-1.5">
                Order Reference: <span class="font-extrabold text-[#141414]">{{ $order->order_number }}</span>
            </p>
        </div>

        <div class="space-y-3">
            <h2 class="text-xs font-extrabold uppercase tracking-wider text-[#141414]">Items Ordered</h2>
            @foreach($order->items as $item)
                <div class="flex items-center justify-between gap-4 text-xs py-2.5 border-b border-[#efe9dd]">
                    <div class="min-w-0">
                        <p class="font-bold text-[#141414]">{{ $item->product_name }}</p>
                        <p class="text-neutral-500">Size: {{ $item->size }} · Qty: {{ $item->quantity }}</p>
                    </div>
                    <span class="font-extrabold text-[#141414] shrink-0">₹ {{ number_format($item->line_total) }}</span>
                </div>
            @endforeach
        </div>

        <div class="bg-[#faf7f2] border border-[#e6dfd3] rounded-xl p-4 text-xs space-y-2">
            <div class="flex flex-col sm:flex-row sm:justify-between gap-1 sm:gap-4">
                <span class="text-neutral-600 shrink-0">Shipping Address</span>
                <span class="font-semibold text-[#141414] sm:text-right">{{ $order->address }}, {{ $order->city }} - {{ $order->pincode }}</span>
            </div>
            <div class="flex justify-between gap-4">
                <span class="text-neutral-600">Payment Mode</span>
                <span class="font-bold uppercase text-[#141414]">{{ $order->payment_method }}</span>
            </div>
            <div class="flex justify-between pt-2 border-t border-[#e6dfd3] text-sm font-extrabold text-[#141414]">
                <span>Total Amount</span><span>₹ {{ number_format($order->total) }}</span>
            </div>
        </div>

        <div class="flex flex-wrap gap-3 justify-center pt-2">
            <a href="{{ route('shop') }}" class="px-6 py-3 bg-[#141414] text-[#e3c587] text-xs font-extrabold uppercase tracking-wider rounded-xl">Continue Shopping</a>
            <a href="{{ route('home') }}" class="px-6 py-3 border border-[#d9d0c1] text-xs font-extrabold uppercase tracking-wider rounded-xl hover:border-[#141414]">Back to Home</a>
        </div>
    </div>
</div>
@endsection
