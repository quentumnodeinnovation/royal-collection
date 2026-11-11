@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard Overview')
@section('page_subtitle', 'Real-time store metrics across Mens & Kids apparel, orders, shopping bags, sliders, and customers.')

@section('top_actions')
    <a href="{{ route('admin.products.create') }}"
       class="px-3.5 py-2 rounded-xl bg-[#141414] text-[#e3c587] text-xs font-bold hover:bg-neutral-800 transition-colors whitespace-nowrap">
        + Add Product
    </a>
@endsection

@section('content')
{{-- Primary KPI Grid --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-5">
    <div class="bg-white rounded-2xl border border-[#e6dfd3] p-5 shadow-xs">
        <div class="flex items-center justify-between gap-2">
            <span class="text-[11px] font-bold uppercase tracking-wider text-neutral-500">Total Revenue</span>
            <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-bold">Active Sales</span>
        </div>
        <p class="text-2xl font-extrabold text-[#141414] mt-2">₹{{ number_format($totalRevenue) }}</p>
        <p class="text-xs text-neutral-500 mt-1">{{ $totalOrders }} total orders placed</p>
    </div>

    <div class="bg-white rounded-2xl border border-[#e6dfd3] p-5 shadow-xs">
        <div class="flex items-center justify-between gap-2">
            <span class="text-[11px] font-bold uppercase tracking-wider text-neutral-500">Live Carts</span>
            <a href="{{ route('admin.carts.index') }}" class="text-[11px] font-bold text-[#9b742e] hover:underline">Manage →</a>
        </div>
        <p class="text-2xl font-extrabold text-[#141414] mt-2">{{ $activeCartsCount }} Active</p>
        <p class="text-xs text-neutral-500 mt-1">₹{{ number_format($activeCartsValue) }} value · {{ $abandonedCartsCount }} abandoned</p>
    </div>

    <div class="bg-white rounded-2xl border border-[#e6dfd3] p-5 shadow-xs">
        <div class="flex items-center justify-between gap-2">
            <span class="text-[11px] font-bold uppercase tracking-wider text-neutral-500">Catalog (Men &amp; Kids)</span>
            <a href="{{ route('admin.products.index') }}" class="text-[11px] font-bold text-[#9b742e] hover:underline">Catalog →</a>
        </div>
        <p class="text-2xl font-extrabold text-[#141414] mt-2">{{ $totalProducts }} Products</p>
        <p class="text-xs text-neutral-500 mt-1">{{ $menProductsCount }} Mens · {{ $kidsProductsCount }} Kids · {{ $totalCategoriesCount }} Categories</p>
    </div>

    <div class="bg-white rounded-2xl border border-[#e6dfd3] p-5 shadow-xs">
        <div class="flex items-center justify-between gap-2">
            <span class="text-[11px] font-bold uppercase tracking-wider text-neutral-500">Users &amp; Sliders</span>
            <a href="{{ route('admin.users.index') }}" class="text-[11px] font-bold text-[#9b742e] hover:underline">Users →</a>
        </div>
        <p class="text-2xl font-extrabold text-[#141414] mt-2">{{ $totalUsers }} Accounts</p>
        <p class="text-xs text-neutral-500 mt-1">{{ $customerUsersCount }} Customers · {{ $activeSlidersCount }}/{{ $totalSlidersCount }} Live Sliders · {{ $openInquiriesCount }} Inquiries</p>
    </div>
</div>

{{-- Middle Row: Order Pipeline Breakdown + Low Stock Alerts --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="bg-white rounded-2xl border border-[#e6dfd3] p-5 sm:p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-sm font-extrabold uppercase tracking-wider text-[#141414]">Order Pipeline</h2>
            <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-[#9b742e] hover:underline">All Orders</a>
        </div>

        @php $maxOrderCount = max(1, $totalOrders); @endphp
        <div class="space-y-3.5">
            @foreach($ordersByStatus as $statusName => $count)
                @php
                    $percent = round(($count / $maxOrderCount) * 100);
                    $barColor = match($statusName) {
                        'delivered' => 'bg-emerald-600',
                        'shipped' => 'bg-indigo-600',
                        'processing' => 'bg-[#c69d54]',
                        'cancelled' => 'bg-[#7c232b]',
                        default => 'bg-[#141414]',
                    };
                @endphp
                <div>
                    <div class="flex items-center justify-between text-xs mb-1">
                        <span class="font-semibold capitalize text-neutral-700">{{ $statusName }}</span>
                        <span class="font-extrabold text-[#141414]">{{ $count }}</span>
                    </div>
                    <div class="h-2 w-full rounded-full bg-[#f2ece1] overflow-hidden">
                        <div class="h-full rounded-full {{ $barColor }}" style="width: {{ $percent }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="lg:col-span-2 bg-white rounded-2xl border border-[#e6dfd3] p-5 sm:p-6">
        <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
            <div>
                <h2 class="text-sm font-extrabold uppercase tracking-wider text-[#141414]">Low Stock Monitor (≤ 10 Units)</h2>
                <p class="text-[11px] text-neutral-500">Inventory alerts across Mens &amp; Kids lines</p>
            </div>
            <a href="{{ route('admin.products.index', ['stock' => 'low']) }}" class="text-xs font-bold text-[#9b742e] hover:underline">View Low Stock →</a>
        </div>

        @if($lowStockProducts->isEmpty())
            <div class="py-10 text-center text-xs text-neutral-500 bg-[#faf7f2] rounded-xl border border-dashed border-[#d9d0c1]">
                All Mens &amp; Kids products are well stocked above 10 units.
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @foreach($lowStockProducts as $item)
                    <a href="{{ route('admin.products.edit', $item) }}"
                       class="flex items-center justify-between gap-3 p-3 rounded-xl border border-[#e6dfd3] hover:border-[#9b742e] bg-[#faf7f2]/40 transition-colors">
                        <div class="flex items-center gap-3 min-w-0">
                            <img src="{{ asset($item->image) }}" alt="{{ $item->name }}" class="w-11 h-12 rounded-lg object-cover bg-[#f2ece1] shrink-0">
                            <div class="min-w-0">
                                <p class="text-xs font-bold text-[#141414] truncate">{{ $item->name }}</p>
                                <p class="text-[11px] text-neutral-500">{{ strtoupper($item->audience) }} · {{ $item->sku }}</p>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-[11px] font-extrabold shrink-0 {{ $item->stock <= 3 ? 'bg-[#f9ecee] text-[#7c232b]' : 'bg-[#f7f1e3] text-[#8c6829]' }}">
                            {{ $item->stock }} left
                        </span>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</div>

{{-- Bottom Row: Recent Orders & Recent Active Carts --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    <div class="xl:col-span-2 bg-white rounded-2xl border border-[#e6dfd3] overflow-hidden">
        <div class="px-5 sm:px-6 py-4 border-b border-[#e6dfd3] flex items-center justify-between">
            <h2 class="text-sm font-extrabold uppercase tracking-wider text-[#141414]">Recent Orders</h2>
            <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-[#9b742e] hover:underline">View All Orders →</a>
        </div>

        {{-- Mobile Card List --}}
        <div class="md:hidden divide-y divide-[#efe9dd]">
            @forelse($recentOrders as $order)
                <a href="{{ route('admin.orders.show', $order) }}" class="block p-4 hover:bg-[#faf7f2]/60 space-y-1.5">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-extrabold text-[#141414]">{{ $order->order_number }}</span>
                        <span class="text-xs font-extrabold text-[#141414]">₹{{ number_format($order->total) }}</span>
                    </div>
                    <div class="flex items-center justify-between text-[11px] text-neutral-600">
                        <span>{{ $order->customer_name }} · {{ $order->city }} ({{ $order->items_count }} pcs)</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $order->status === 'delivered' ? 'bg-emerald-50 text-emerald-700' : ($order->status === 'cancelled' ? 'bg-[#f9ecee] text-[#7c232b]' : 'bg-[#f7f1e3] text-[#8c6829]') }}">
                            {{ $order->status }}
                        </span>
                    </div>
                </a>
            @empty
                <p class="p-6 text-center text-xs text-neutral-400">No orders yet.</p>
            @endforelse
        </div>

        {{-- Desktop Table --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#faf7f2] text-neutral-500 uppercase tracking-wider text-[10px] border-b border-[#e6dfd3]">
                    <tr>
                        <th class="px-5 py-3">Order #</th>
                        <th class="px-5 py-3">Customer</th>
                        <th class="px-5 py-3">Items</th>
                        <th class="px-5 py-3">Total</th>
                        <th class="px-5 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#efe9dd]">
                    @forelse($recentOrders as $order)
                        <tr class="hover:bg-[#faf7f2]/60">
                            <td class="px-5 py-3.5 font-extrabold text-[#141414]">
                                <a href="{{ route('admin.orders.show', $order) }}" class="hover:text-[#9b742e] hover:underline">{{ $order->order_number }}</a>
                            </td>
                            <td class="px-5 py-3.5">
                                <p class="font-semibold text-[#141414]">{{ $order->customer_name }}</p>
                                <p class="text-[11px] text-neutral-500">{{ $order->city }}</p>
                            </td>
                            <td class="px-5 py-3.5 text-neutral-600">{{ $order->items_count }} pcs</td>
                            <td class="px-5 py-3.5 font-bold text-[#141414]">₹{{ number_format($order->total) }}</td>
                            <td class="px-5 py-3.5">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                    {{ $order->status === 'delivered' ? 'bg-emerald-50 text-emerald-700' :
                                       ($order->status === 'cancelled' ? 'bg-[#f9ecee] text-[#7c232b]' : 'bg-[#f7f1e3] text-[#8c6829]') }}">
                                    {{ $order->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-8 text-center text-neutral-400">No orders yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-[#e6dfd3] overflow-hidden flex flex-col justify-between">
        <div>
            <div class="px-5 sm:px-6 py-4 border-b border-[#e6dfd3] flex items-center justify-between">
                <h2 class="text-sm font-extrabold uppercase tracking-wider text-[#141414]">Recent Carts &amp; Inquiries</h2>
                <a href="{{ route('admin.carts.index') }}" class="text-xs font-bold text-[#9b742e] hover:underline">All Carts →</a>
            </div>
            <div class="divide-y divide-[#efe9dd]">
                @forelse($recentCarts as $cart)
                    <a href="{{ route('admin.carts.show', $cart) }}" class="flex items-center justify-between px-5 py-3.5 hover:bg-[#faf7f2]/70">
                        <div>
                            <p class="text-xs font-bold text-[#141414]">{{ $cart->customer_name ?: 'Guest Shopper' }}</p>
                            <p class="text-[11px] text-neutral-500">{{ $cart->item_count }} items · {{ ucfirst($cart->status) }}</p>
                        </div>
                        <span class="text-xs font-extrabold text-[#141414]">₹{{ number_format($cart->subtotal) }}</span>
                    </a>
                @empty
                    <p class="px-5 py-6 text-xs text-neutral-400 text-center">No tracked carts yet.</p>
                @endforelse
            </div>
        </div>
        @if($recentInquiries->isNotEmpty())
            <div class="px-5 py-3.5 bg-[#faf7f2] border-t border-[#e6dfd3] flex items-center justify-between">
                <span class="text-[11px] font-bold text-neutral-600">{{ $openInquiriesCount }} open inquiry(s)</span>
                <a href="{{ route('admin.inquiries.index') }}" class="text-[11px] font-bold text-[#9b742e] hover:underline">Review Inquiries →</a>
            </div>
        @endif
    </div>
</div>
@endsection
