@extends('layouts.admin')

@section('title', 'Cart #'.$cart->id)
@section('page_title', 'Shopping Bag #'.$cart->id)
@section('page_subtitle', 'Last active '.(optional($cart->last_activity_at)->diffForHumans() ?? 'recently'))

@section('top_actions')
    <a href="{{ route('admin.carts.index') }}"
       class="px-3.5 py-2 rounded-xl border border-[#d9d0c1] bg-white text-xs font-bold text-[#141414] hover:border-[#c69d54] transition-colors whitespace-nowrap">
        ← All Carts
    </a>
@endsection

@section('content')
@php
    $statusBadge = match($cart->status) {
        'converted' => 'bg-emerald-50 text-emerald-800 border-emerald-200/80',
        'abandoned' => 'bg-[#f9ecee] text-[#7c232b] border-[#e3b8be]',
        default => 'bg-[#f7f1e3] text-[#8c6829] border-[#e3c587]',
    };
@endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-white rounded-2xl border border-[#e6dfd3] overflow-hidden shadow-[0_10px_25px_-20px_rgba(20,20,20,0.12)]">
        <div class="px-5 sm:px-6 py-4 bg-[#faf7f2] border-b border-[#e6dfd3] flex flex-wrap items-center justify-between gap-2">
            <h2 class="text-xs font-extrabold uppercase tracking-[0.18em] text-[#141414]">Bag Contents ({{ $cart->item_count }} pcs)</h2>
            <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase border {{ $statusBadge }}">{{ $cart->status }}</span>
        </div>

        <div class="divide-y divide-[#efe9dd]">
            @forelse($cart->items as $item)
                <div class="p-4 sm:p-5 flex items-start sm:items-center justify-between gap-3 sm:gap-4">
                    <div class="flex items-start sm:items-center gap-3.5 min-w-0">
                        @if($item->product)
                            <img src="{{ asset($item->product->image) }}" alt="{{ $item->product->name }}"
                                 class="w-14 h-16 rounded-xl object-cover bg-[#f2ece1] border border-[#e6dfd3] shrink-0">
                        @endif
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-[#141414] leading-snug break-words">{{ $item->product?->name ?? 'Removed Product' }}</p>
                            <p class="text-xs text-[#6f685e] mt-1">Size: <span class="font-semibold text-[#141414]">{{ $item->size }}</span> · Qty: {{ $item->quantity }} × ₹{{ number_format($item->unit_price) }}</p>
                        </div>
                    </div>
                    <p class="text-sm font-extrabold text-[#141414] shrink-0">₹{{ number_format($item->line_total) }}</p>
                </div>
            @empty
                <p class="p-8 text-center text-xs text-[#787166]">This shopping bag currently has zero items.</p>
            @endforelse
        </div>

        <div class="px-5 sm:px-6 py-4 bg-[#faf7f2] border-t border-[#e6dfd3] flex justify-between items-center text-sm font-extrabold text-[#141414]">
            <span>Bag Subtotal</span>
            <span>₹{{ number_format($cart->subtotal) }}</span>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-2xl border border-[#e6dfd3] p-5 sm:p-6 space-y-3 text-xs shadow-[0_10px_25px_-20px_rgba(20,20,20,0.12)]">
            <h2 class="text-xs font-extrabold uppercase tracking-[0.18em] text-[#141414] mb-2">Shopper Info</h2>
            <p class="break-words"><span class="text-[#787166]">Name:</span> <span class="font-bold text-[#141414]">{{ $cart->customer_name ?: ($cart->user?->name ?? 'Guest Shopper') }}</span></p>
            <p class="break-all"><span class="text-[#787166]">Email:</span> <span class="font-semibold text-[#141414]">{{ $cart->customer_email ?: ($cart->user?->email ?? 'Not provided') }}</span></p>
            <p><span class="text-[#787166]">Token:</span> <span class="font-mono text-[11px] text-[#5c564e] break-all">{{ $cart->session_token }}</span></p>
        </div>

        <div class="bg-white rounded-2xl border border-[#e6dfd3] p-5 sm:p-6 space-y-3 shadow-[0_10px_25px_-20px_rgba(20,20,20,0.12)]">
            <h2 class="text-xs font-extrabold uppercase tracking-[0.18em] text-[#141414]">Cart Actions</h2>
            <form action="{{ route('admin.carts.status', $cart) }}" method="POST" class="space-y-3">
                @csrf
                @method('PATCH')
                <select name="status" class="w-full h-10 px-3.5 rounded-xl border border-[#d9d0c1] bg-[#faf7f2] text-xs font-bold text-[#141414] capitalize focus:bg-white focus:outline-none focus:border-[#141414]">
                    @foreach($statuses as $st)
                        <option value="{{ $st }}" {{ $cart->status === $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                    @endforeach
                </select>
                <button type="submit" class="w-full h-10 rounded-xl bg-[#141414] text-[#e3c587] text-xs font-extrabold uppercase tracking-wider hover:bg-[#2a2722] transition-colors cursor-pointer">
                    Update Status
                </button>
            </form>

            <form action="{{ route('admin.carts.destroy', $cart) }}" method="POST" onsubmit="return confirm('Delete this cart completely?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full h-10 rounded-xl border border-[#e3b8be] text-[#7c232b] text-xs font-bold hover:bg-[#f9ecee] transition-colors cursor-pointer">
                    Clear Cart Record
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
