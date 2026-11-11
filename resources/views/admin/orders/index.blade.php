@extends('layouts.admin')

@section('title', 'Orders')
@section('page_title', 'Orders Management')
@section('page_subtitle', 'Track customer purchases, update fulfilment statuses, and inspect line items.')

@section('content')
<div class="bg-white rounded-2xl border border-[#e6dfd3] p-4 flex flex-col lg:flex-row lg:items-center justify-between gap-3">
    <div class="flex items-center gap-2 overflow-x-auto no-scrollbar pb-1 lg:pb-0">
        <a href="{{ route('admin.orders.index', ['q' => $search]) }}"
           class="px-3.5 py-1.5 rounded-full text-xs font-bold border whitespace-nowrap {{ !$statusFilter ? 'bg-[#141414] text-[#e3c587] border-[#141414]' : 'border-[#d9d0c1] text-neutral-700 hover:bg-[#faf7f2]' }}">
            All ({{ $totalCount }})
        </a>
        @foreach($statuses as $st)
            <a href="{{ route('admin.orders.index', ['status' => $st, 'q' => $search]) }}"
               class="px-3.5 py-1.5 rounded-full text-xs font-bold capitalize border whitespace-nowrap {{ $statusFilter === $st ? 'bg-[#141414] text-[#e3c587] border-[#141414]' : 'border-[#d9d0c1] text-neutral-700 hover:bg-[#faf7f2]' }}">
                {{ $st }} ({{ $countsByStatus[$st] ?? 0 }})
            </a>
        @endforeach
    </div>

    <form action="{{ route('admin.orders.index') }}" method="GET" class="flex items-center gap-2 w-full lg:w-auto">
        @if($statusFilter)<input type="hidden" name="status" value="{{ $statusFilter }}">@endif
        <input type="text" name="q" value="{{ $search }}" placeholder="Search order #, customer, city..."
               class="h-9 px-3.5 rounded-xl border border-[#d9d0c1] text-xs flex-1 lg:w-64 focus:outline-none focus:border-[#9b742e]">
        <button type="submit" class="h-9 px-4 rounded-xl bg-[#141414] text-[#e3c587] text-xs font-bold shrink-0 cursor-pointer">Search</button>
    </form>
</div>

<div class="bg-white rounded-2xl border border-[#e6dfd3] overflow-hidden">
    {{-- Mobile Stacked Order Cards --}}
    <div class="md:hidden divide-y divide-[#efe9dd]">
        @forelse($orders as $order)
            <div class="p-4 space-y-3">
                <div class="flex items-start justify-between gap-2">
                    <div>
                        <a href="{{ route('admin.orders.show', $order) }}" class="text-sm font-extrabold text-[#141414] hover:text-[#9b742e]">{{ $order->order_number }}</a>
                        <p class="text-xs font-semibold text-neutral-800 mt-0.5">{{ $order->customer_name }}</p>
                        <p class="text-[11px] text-neutral-500">{{ $order->email }} · {{ $order->city }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-extrabold text-[#141414]">₹{{ number_format($order->total) }}</p>
                        <p class="text-[11px] text-neutral-500 uppercase">{{ $order->payment_method }} · {{ $order->items_count }} pcs</p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center justify-between gap-2 pt-2 border-t border-[#efe9dd]">
                    <form action="{{ route('admin.orders.status', $order) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <select name="status" onchange="this.form.submit()" class="h-8 px-2.5 rounded-lg border border-[#d9d0c1] bg-[#faf7f2] text-xs font-bold capitalize">
                            @foreach($statuses as $st)
                                <option value="{{ $st }}" {{ $order->status === $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                            @endforeach
                        </select>
                    </form>
                    <div class="flex items-center gap-3 text-xs font-bold">
                        <a href="{{ route('admin.orders.show', $order) }}" class="text-[#9b742e]">Inspect</a>
                        <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" onsubmit="return confirm('Delete order {{ $order->order_number }}?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-[#7c232b] cursor-pointer">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <p class="p-8 text-center text-xs text-neutral-500">No matching orders found.</p>
        @endforelse
    </div>

    {{-- Desktop Table --}}
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead class="bg-[#faf7f2] border-b border-[#e6dfd3] text-neutral-500 uppercase tracking-wider text-[10px]">
                <tr>
                    <th class="px-5 py-3.5">Order #</th>
                    <th class="px-5 py-3.5">Customer</th>
                    <th class="px-5 py-3.5">Payment</th>
                    <th class="px-5 py-3.5">Items</th>
                    <th class="px-5 py-3.5">Total</th>
                    <th class="px-5 py-3.5">Status</th>
                    <th class="px-5 py-3.5">Date</th>
                    <th class="px-5 py-3.5 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#efe9dd]">
                @forelse($orders as $order)
                    <tr class="hover:bg-[#faf7f2]/60">
                        <td class="px-5 py-4 font-extrabold text-[#141414]">
                            <a href="{{ route('admin.orders.show', $order) }}" class="hover:text-[#9b742e] hover:underline">{{ $order->order_number }}</a>
                        </td>
                        <td class="px-5 py-4">
                            <p class="font-bold text-[#141414]">{{ $order->customer_name }}</p>
                            <p class="text-[11px] text-neutral-500">{{ $order->email }} · {{ $order->phone }}</p>
                        </td>
                        <td class="px-5 py-4 uppercase font-semibold text-neutral-600">{{ $order->payment_method }}</td>
                        <td class="px-5 py-4 text-neutral-700">{{ $order->items_count }} pcs</td>
                        <td class="px-5 py-4 font-extrabold text-[#141414]">₹{{ number_format($order->total) }}</td>
                        <td class="px-5 py-4">
                            <form action="{{ route('admin.orders.status', $order) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <select name="status" onchange="this.form.submit()"
                                        class="h-8 px-2.5 rounded-lg border border-[#d9d0c1] bg-white text-[11px] font-bold capitalize focus:outline-none focus:border-[#9b742e] cursor-pointer">
                                    @foreach($statuses as $st)
                                        <option value="{{ $st }}" {{ $order->status === $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </td>
                        <td class="px-5 py-4 text-neutral-500">{{ $order->created_at->format('d M Y') }}</td>
                        <td class="px-5 py-4 text-right space-x-2 whitespace-nowrap">
                            <a href="{{ route('admin.orders.show', $order) }}" class="text-xs font-bold text-[#9b742e] hover:underline">Inspect</a>
                            <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" class="inline" onsubmit="return confirm('Delete order {{ $order->order_number }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs font-bold text-[#7c232b] hover:underline cursor-pointer">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="px-5 py-10 text-center text-neutral-500">No matching orders found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($orders->hasPages())
        <div class="px-5 py-3 border-t border-[#e6dfd3]">{{ $orders->links() }}</div>
    @endif
</div>
@endsection
