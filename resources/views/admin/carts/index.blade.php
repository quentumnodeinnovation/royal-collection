@extends('layouts.admin')

@section('title', 'Shopping Carts')
@section('page_title', 'Shopping Carts Monitor')
@section('page_subtitle', 'Live SQLite-persisted shopping bags synced automatically from the Royal Collection storefront.')

@section('content')
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
    <div class="bg-white rounded-2xl border border-[#e6dfd3] p-4">
        <p class="text-[10px] font-bold uppercase tracking-wider text-neutral-500">Active Carts Value</p>
        <p class="text-xl font-extrabold text-[#141414] mt-1">₹{{ number_format($activeValue) }}</p>
        <p class="text-xs text-neutral-500">{{ $countsByStatus['active'] ?? 0 }} open bags</p>
    </div>
    <div class="bg-white rounded-2xl border border-[#e6dfd3] p-4">
        <p class="text-[10px] font-bold uppercase tracking-wider text-neutral-500">Abandoned Carts Value</p>
        <p class="text-xl font-extrabold text-[#8c6829] mt-1">₹{{ number_format($abandonedValue) }}</p>
        <p class="text-xs text-neutral-500">{{ $countsByStatus['abandoned'] ?? 0 }} dropped bags</p>
    </div>
    <div class="bg-white rounded-2xl border border-[#e6dfd3] p-4">
        <p class="text-[10px] font-bold uppercase tracking-wider text-neutral-500">Converted Bags</p>
        <p class="text-xl font-extrabold text-emerald-700 mt-1">{{ $countsByStatus['converted'] ?? 0 }} Completed</p>
        <p class="text-xs text-neutral-500">Checked out to orders</p>
    </div>
</div>

<div class="bg-white rounded-2xl border border-[#e6dfd3] p-4 flex flex-col lg:flex-row lg:items-center justify-between gap-3">
    <div class="flex items-center gap-2 overflow-x-auto no-scrollbar pb-1 lg:pb-0">
        <a href="{{ route('admin.carts.index', ['q' => $search]) }}"
           class="px-3.5 py-1.5 rounded-full text-xs font-bold border whitespace-nowrap {{ !$statusFilter ? 'bg-[#141414] text-[#e3c587] border-[#141414]' : 'border-[#d9d0c1] text-neutral-700 hover:bg-[#faf7f2]' }}">
            All
        </a>
        @foreach($statuses as $st)
            <a href="{{ route('admin.carts.index', ['status' => $st, 'q' => $search]) }}"
               class="px-3.5 py-1.5 rounded-full text-xs font-bold capitalize border whitespace-nowrap {{ $statusFilter === $st ? 'bg-[#141414] text-[#e3c587] border-[#141414]' : 'border-[#d9d0c1] text-neutral-700 hover:bg-[#faf7f2]' }}">
                {{ $st }} ({{ $countsByStatus[$st] ?? 0 }})
            </a>
        @endforeach
    </div>

    <form action="{{ route('admin.carts.index') }}" method="GET" class="flex items-center gap-2 w-full lg:w-auto">
        @if($statusFilter)<input type="hidden" name="status" value="{{ $statusFilter }}">@endif
        <input type="text" name="q" value="{{ $search }}" placeholder="Search shopper, email, session..."
               class="h-9 px-3.5 rounded-xl border border-[#d9d0c1] text-xs flex-1 lg:w-64 focus:outline-none focus:border-[#9b742e]">
        <button type="submit" class="h-9 px-4 rounded-xl bg-[#141414] text-[#e3c587] text-xs font-bold shrink-0 cursor-pointer">Search</button>
    </form>
</div>

<div class="bg-white rounded-2xl border border-[#e6dfd3] overflow-hidden">
    {{-- Mobile Stacked Cards --}}
    <div class="md:hidden divide-y divide-[#efe9dd]">
        @forelse($carts as $cart)
            <div class="p-4 space-y-3">
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0">
                        <p class="text-sm font-bold text-[#141414] truncate">{{ $cart->customer_name ?: ($cart->user?->name ?? 'Guest Shopper') }}</p>
                        <p class="text-[11px] text-neutral-500 truncate">{{ $cart->customer_email ?: ($cart->user?->email ?? 'Anonymous Bag') }}</p>
                    </div>
                    <div class="text-right shrink-0">
                        <p class="text-sm font-extrabold text-[#141414]">₹{{ number_format($cart->subtotal) }}</p>
                        <p class="text-[11px] text-neutral-500">{{ $cart->item_count }} pcs</p>
                    </div>
                </div>
                <div class="flex flex-wrap items-center justify-between gap-2 pt-2 border-t border-[#efe9dd]">
                    <form action="{{ route('admin.carts.status', $cart) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <select name="status" onchange="this.form.submit()" class="h-8 px-2.5 rounded-lg border border-[#d9d0c1] bg-[#faf7f2] text-xs font-bold capitalize">
                            @foreach($statuses as $st)
                                <option value="{{ $st }}" {{ $cart->status === $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                            @endforeach
                        </select>
                    </form>
                    <div class="flex items-center gap-3 text-xs font-bold">
                        <a href="{{ route('admin.carts.show', $cart) }}" class="text-[#9b742e]">View Items</a>
                        <form action="{{ route('admin.carts.destroy', $cart) }}" method="POST" onsubmit="return confirm('Clear this cart record?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-[#7c232b] cursor-pointer">Clear</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <p class="p-8 text-center text-xs text-neutral-500">No carts matching your filter.</p>
        @endforelse
    </div>

    {{-- Desktop Table --}}
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead class="bg-[#faf7f2] border-b border-[#e6dfd3] text-neutral-500 uppercase tracking-wider text-[10px]">
                <tr>
                    <th class="px-5 py-3.5">Shopper</th>
                    <th class="px-5 py-3.5">Session Token</th>
                    <th class="px-5 py-3.5">Items</th>
                    <th class="px-5 py-3.5">Subtotal</th>
                    <th class="px-5 py-3.5">Status</th>
                    <th class="px-5 py-3.5">Last Activity</th>
                    <th class="px-5 py-3.5 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#efe9dd]">
                @forelse($carts as $cart)
                    <tr class="hover:bg-[#faf7f2]/60">
                        <td class="px-5 py-4">
                            <p class="font-bold text-[#141414]">{{ $cart->customer_name ?: ($cart->user?->name ?? 'Guest Shopper') }}</p>
                            <p class="text-[11px] text-neutral-500">{{ $cart->customer_email ?: ($cart->user?->email ?? 'Anonymous Bag') }}</p>
                        </td>
                        <td class="px-5 py-4 font-mono text-[11px] text-neutral-500">{{ \Illuminate\Support\Str::limit($cart->session_token, 18) }}</td>
                        <td class="px-5 py-4 font-semibold text-neutral-800">{{ $cart->item_count }} pcs</td>
                        <td class="px-5 py-4 font-extrabold text-[#141414]">₹{{ number_format($cart->subtotal) }}</td>
                        <td class="px-5 py-4">
                            <form action="{{ route('admin.carts.status', $cart) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <select name="status" onchange="this.form.submit()"
                                        class="h-8 px-2.5 rounded-lg border border-[#d9d0c1] bg-white text-[11px] font-bold capitalize cursor-pointer">
                                    @foreach($statuses as $st)
                                        <option value="{{ $st }}" {{ $cart->status === $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </td>
                        <td class="px-5 py-4 text-neutral-500">{{ optional($cart->last_activity_at)->diffForHumans() ?? '—' }}</td>
                        <td class="px-5 py-4 text-right space-x-2 whitespace-nowrap">
                            <a href="{{ route('admin.carts.show', $cart) }}" class="text-xs font-bold text-[#9b742e] hover:underline">View Items</a>
                            <form action="{{ route('admin.carts.destroy', $cart) }}" method="POST" class="inline" onsubmit="return confirm('Clear this cart record?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs font-bold text-[#7c232b] hover:underline cursor-pointer">Clear</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-5 py-10 text-center text-neutral-500">No carts matching your filter.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($carts->hasPages())
        <div class="px-5 py-3 border-t border-[#e6dfd3]">{{ $carts->links() }}</div>
    @endif
</div>
@endsection
