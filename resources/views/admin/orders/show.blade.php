@extends('layouts.admin')

@section('title', 'Order '.$order->order_number)
@section('page_title', 'Order '.$order->order_number)
@section('page_subtitle', 'Placed '.$order->created_at->format('d M Y, h:i A'))

@section('top_actions')
    <a href="{{ route('admin.orders.index') }}"
       class="px-3.5 py-2 rounded-xl border border-[#d9d0c1] bg-white text-xs font-bold text-[#141414] hover:border-[#c69d54] transition-colors whitespace-nowrap">
        ← All Orders
    </a>
@endsection

@section('content')
@php
    $badgeClass = match($order->status) {
        'delivered' => 'bg-emerald-50 text-emerald-800 border-emerald-200/80',
        'shipped' => 'bg-sky-50 text-sky-800 border-sky-200/80',
        'cancelled' => 'bg-[#f9ecee] text-[#7c232b] border-[#e3b8be]',
        'confirmed', 'processing' => 'bg-[#f7f1e3] text-[#8c6829] border-[#e3c587]',
        default => 'bg-[#f2ece1] text-[#4a453e] border-[#d9d0c1]',
    };
@endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Line Items --}}
    <div class="lg:col-span-2 bg-white rounded-2xl border border-[#e6dfd3] overflow-hidden shadow-[0_10px_25px_-20px_rgba(20,20,20,0.12)]">
        <div class="px-5 sm:px-6 py-4 bg-[#faf7f2] border-b border-[#e6dfd3] flex flex-wrap items-center justify-between gap-2">
            <h2 class="text-xs font-extrabold uppercase tracking-[0.18em] text-[#141414]">Ordered Items ({{ $order->items->count() }})</h2>
            <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase border {{ $badgeClass }}">{{ $order->status }}</span>
        </div>

        <div class="divide-y divide-[#efe9dd]">
            @foreach($order->items as $item)
                <div class="p-4 sm:p-5 flex items-start sm:items-center justify-between gap-3 sm:gap-4">
                    <div class="flex items-start sm:items-center gap-3.5 min-w-0">
                        @if($item->product)
                            <img src="{{ asset($item->product->image) }}" alt="{{ $item->product_name }}"
                                 class="w-14 h-16 rounded-xl object-cover bg-[#f2ece1] border border-[#e6dfd3] shrink-0">
                        @endif
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-[#141414] leading-snug break-words">{{ $item->product_name }}</p>
                            <p class="text-xs text-[#6f685e] mt-1">
                                Size: <span class="font-bold text-[#141414]">{{ $item->size }}</span> · Qty: {{ $item->quantity }} × ₹{{ number_format($item->unit_price) }}
                            </p>
                        </div>
                    </div>
                    <p class="text-sm font-extrabold text-[#141414] shrink-0">₹{{ number_format($item->line_total) }}</p>
                </div>
            @endforeach
        </div>

        <div class="p-5 sm:p-6 bg-[#faf7f2] border-t border-[#e6dfd3] space-y-2 text-xs">
            <div class="flex justify-between text-[#5c564e]">
                <span>Subtotal</span>
                <span class="font-semibold text-[#141414]">₹{{ number_format($order->subtotal) }}</span>
            </div>
            <div class="flex justify-between text-[#5c564e]">
                <span>Express Shipping</span>
                <span class="font-semibold text-[#141414]">{{ $order->shipping === 0 ? 'FREE' : '₹'.number_format($order->shipping) }}</span>
            </div>
            <div class="flex justify-between text-base font-extrabold text-[#141414] pt-2.5 border-t border-[#e6dfd3]">
                <span>Grand Total ({{ strtoupper($order->payment_method) }})</span>
                <span>₹{{ number_format($order->total) }}</span>
            </div>
        </div>
    </div>

    {{-- Customer & Status Control --}}
    <div class="space-y-6">
        <div class="bg-white rounded-2xl border border-[#e6dfd3] p-5 sm:p-6 shadow-[0_10px_25px_-20px_rgba(20,20,20,0.12)]">
            <h2 class="text-xs font-extrabold uppercase tracking-[0.18em] text-[#141414] mb-4">Fulfilment Status</h2>
            <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="space-y-3">
                @csrf
                @method('PATCH')
                <select name="status" class="w-full h-10 px-3.5 rounded-xl border border-[#d9d0c1] bg-[#faf7f2] text-sm font-semibold text-[#141414] capitalize focus:bg-white focus:outline-none focus:border-[#141414]">
                    @foreach($statuses as $st)
                        <option value="{{ $st }}" {{ $order->status === $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                    @endforeach
                </select>
                <button type="submit" class="w-full h-10 rounded-xl bg-[#141414] text-[#e3c587] text-xs font-extrabold uppercase tracking-wider hover:bg-[#2a2722] transition-colors cursor-pointer">
                    Update Order Status
                </button>
            </form>
        </div>

        <div class="bg-white rounded-2xl border border-[#e6dfd3] p-5 sm:p-6 space-y-3 text-xs shadow-[0_10px_25px_-20px_rgba(20,20,20,0.12)]">
            <h2 class="text-xs font-extrabold uppercase tracking-[0.18em] text-[#141414] mb-2">Customer &amp; Shipping</h2>
            <p class="break-words"><span class="text-[#787166]">Name:</span> <span class="font-bold text-[#141414]">{{ $order->customer_name }}</span></p>
            <p class="break-all"><span class="text-[#787166]">Email:</span> <span class="font-semibold text-[#141414]">{{ $order->email }}</span></p>
            <p><span class="text-[#787166]">Phone:</span> <span class="font-semibold text-[#141414]">{{ $order->phone }}</span></p>
            <p class="pt-2.5 border-t border-[#efe9dd] text-[#4a453e] leading-relaxed break-words">
                {{ $order->address }}<br>
                {{ $order->city }} — {{ $order->pincode }}
            </p>
            @if($order->user)
                <a href="{{ route('admin.users.edit', $order->user) }}" class="inline-block pt-2 font-bold text-[#9b742e] hover:underline">
                    View User Account →
                </a>
            @endif
        </div>
    </div>
</div>
@endsection
