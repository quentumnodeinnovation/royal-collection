@extends('layouts.app')

@section('title', 'My Account — Royal Collection')

@section('content')
<div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">
    <div class="flex flex-wrap items-center justify-between gap-4 pb-6 border-b border-[#e6dfd3] mb-8">
        <div>
            <p class="text-[11px] font-bold uppercase tracking-[0.25em] text-[#9b742e]">Customer Account</p>
            <h1 class="font-editorial text-2xl sm:text-3xl font-bold text-[#141414] mt-1">Welcome, {{ $user->name }}</h1>
            <p class="text-xs text-neutral-500 mt-1">{{ $user->email }} · {{ ucfirst($user->role) }} Member</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            @if($user->isAdmin())
                <a href="{{ route('admin.dashboard') }}"
                   class="px-4 py-2.5 rounded-xl bg-[#e3c587] text-[#141414] text-xs font-extrabold uppercase tracking-wider hover:bg-[#d2b16e] transition-colors">
                    Open Admin Panel →
                </a>
            @endif
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                        class="px-4 py-2.5 rounded-xl border border-[#d9d0c1] text-xs font-bold uppercase tracking-wider text-neutral-700 hover:bg-[#faf7f2] cursor-pointer">
                    Sign Out
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Profile Form --}}
        <div class="bg-[#faf7f2] border border-[#e6dfd3] rounded-2xl p-6 h-fit">
            <h2 class="text-sm font-extrabold uppercase tracking-wider text-[#141414] mb-4">Profile Details</h2>
            <form action="{{ route('account.update') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-neutral-600 mb-1">Full Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full h-10 px-3.5 rounded-xl border border-[#d9d0c1] bg-white text-sm focus:outline-none focus:border-[#9b742e]">
                </div>
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-neutral-600 mb-1">Email</label>
                    <input type="email" value="{{ $user->email }}" disabled
                           class="w-full h-10 px-3.5 rounded-xl border border-[#e6dfd3] bg-[#f2ece1]/60 text-sm text-neutral-500">
                </div>
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-neutral-600 mb-1">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                           class="w-full h-10 px-3.5 rounded-xl border border-[#d9d0c1] bg-white text-sm focus:outline-none focus:border-[#9b742e]">
                </div>
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-neutral-600 mb-1">City</label>
                    <input type="text" name="city" value="{{ old('city', $user->city) }}"
                           class="w-full h-10 px-3.5 rounded-xl border border-[#d9d0c1] bg-white text-sm focus:outline-none focus:border-[#9b742e]">
                </div>
                <button type="submit"
                        class="w-full h-10 rounded-xl bg-[#141414] text-[#e3c587] text-xs font-extrabold uppercase tracking-wider hover:bg-neutral-800 cursor-pointer">
                    Save Profile
                </button>
            </form>
        </div>

        {{-- Order History --}}
        <div class="lg:col-span-2 space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-extrabold uppercase tracking-wider text-[#141414]">My Orders ({{ $orders->count() }})</h2>
                <a href="{{ route('shop') }}" class="text-xs font-bold text-[#9b742e] hover:underline">Continue Shopping →</a>
            </div>

            @forelse($orders as $order)
                <div class="bg-white border border-[#e6dfd3] rounded-2xl p-5">
                    <div class="flex flex-wrap items-center justify-between gap-3 pb-3 border-b border-[#efe9dd]">
                        <div>
                            <span class="text-xs font-extrabold text-[#141414]">{{ $order->order_number }}</span>
                            <span class="text-xs text-neutral-400 ml-2">{{ $order->created_at->format('d M Y, h:i A') }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="px-2.5 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider
                                {{ $order->status === 'delivered' ? 'bg-emerald-50 text-emerald-700' :
                                   ($order->status === 'cancelled' ? 'bg-[#f9ecee] text-[#7c232b]' : 'bg-[#f7f1e3] text-[#8c6829]') }}">
                                {{ $order->status }}
                            </span>
                            <span class="text-sm font-extrabold text-[#141414]">₹{{ number_format($order->total) }}</span>
                        </div>
                    </div>
                    <div class="mt-3 space-y-2">
                        @foreach($order->items as $item)
                            <div class="flex items-center justify-between gap-2 text-xs text-neutral-700">
                                <span>{{ $item->product_name }} <span class="text-neutral-400">({{ $item->size }} × {{ $item->quantity }})</span></span>
                                <span class="font-semibold shrink-0">₹{{ number_format($item->line_total) }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-3 pt-3 border-t border-[#efe9dd] flex flex-wrap items-center justify-between gap-2 text-[11px] text-neutral-500">
                        <span>Payment: {{ strtoupper($order->payment_method) }} · {{ $order->city }}</span>
                        <a href="{{ route('order.success', $order->order_number) }}" class="font-bold text-[#9b742e] hover:underline">View Receipt →</a>
                    </div>
                </div>
            @empty
                <div class="bg-[#faf7f2] border border-dashed border-[#d9d0c1] rounded-2xl p-10 text-center">
                    <p class="text-sm font-bold text-[#141414]">No orders placed yet</p>
                    <p class="text-xs text-neutral-500 mt-1">Explore our Mens &amp; Kids luxury drops to place your first order.</p>
                    <a href="{{ route('shop') }}" class="inline-block mt-4 px-5 py-2.5 rounded-xl bg-[#141414] text-[#e3c587] text-xs font-bold uppercase tracking-wider">Browse Shop</a>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
