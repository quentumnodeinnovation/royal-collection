@extends('layouts.admin')

@section('title', 'Products')
@section('page_title', 'Products Catalog (Mens & Kids)')
@section('page_subtitle', 'Create, update, price, stock, and highlight Royal Collection Mens & Kids apparel.')

@section('top_actions')
    <a href="{{ route('admin.products.create') }}"
       class="px-4 py-2 rounded-xl bg-[#141414] text-[#e3c587] text-xs font-bold hover:bg-[#2a2722] transition-colors whitespace-nowrap">
        + New Product
    </a>
@endsection

@section('content')
<div class="bg-white rounded-2xl border border-[#e6dfd3] p-4 shadow-[0_10px_25px_-20px_rgba(20,20,20,0.12)]">
    <form action="{{ route('admin.products.index') }}" method="GET"
          class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-[180px_200px_165px_1fr_auto_auto] gap-2.5">
        <select name="audience" class="h-10 px-3 rounded-xl border border-[#d9d0c1] text-xs font-semibold text-[#141414] bg-[#faf7f2] focus:bg-white focus:outline-none focus:border-[#141414]">
            <option value="">All Audiences (Mens &amp; Kids)</option>
            <option value="men" {{ $audience === 'men' ? 'selected' : '' }}>Mens Only</option>
            <option value="kids" {{ $audience === 'kids' ? 'selected' : '' }}>Kids Only</option>
        </select>

        <select name="category_id" class="h-10 px-3 rounded-xl border border-[#d9d0c1] text-xs font-semibold text-[#141414] bg-[#faf7f2] focus:bg-white focus:outline-none focus:border-[#141414]">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ (string) $categoryId === (string) $cat->id ? 'selected' : '' }}>
                    [{{ strtoupper($cat->audience) }}] {{ $cat->name }}
                </option>
            @endforeach
        </select>

        <select name="stock" class="h-10 px-3 rounded-xl border border-[#d9d0c1] text-xs font-semibold text-[#141414] bg-[#faf7f2] focus:bg-white focus:outline-none focus:border-[#141414]">
            <option value="">Any Stock Level</option>
            <option value="low" {{ $stockState === 'low' ? 'selected' : '' }}>Low Stock (1–10)</option>
            <option value="out" {{ $stockState === 'out' ? 'selected' : '' }}>Out of Stock (0)</option>
        </select>

        <input type="text" name="q" value="{{ $search }}" placeholder="Search name or SKU..."
               class="h-10 px-3.5 rounded-xl border border-[#d9d0c1] bg-[#faf7f2] text-xs text-[#141414] focus:bg-white focus:outline-none focus:border-[#141414] sm:col-span-2 lg:col-span-1">

        <button type="submit" class="h-10 px-5 rounded-xl bg-[#141414] text-white text-xs font-bold hover:bg-[#2a2722] transition-colors cursor-pointer">
            Filter
        </button>

        @if($audience || $categoryId || $stockState || $search)
            <a href="{{ route('admin.products.index') }}"
               class="h-10 px-4 rounded-xl border border-[#d9d0c1] text-xs font-semibold flex items-center justify-center text-[#5c564e] hover:border-[#c69d54]">
                Reset
            </a>
        @endif
    </form>
</div>

<div class="bg-white rounded-2xl border border-[#e6dfd3] overflow-hidden shadow-[0_10px_25px_-20px_rgba(20,20,20,0.12)]">
    {{-- Mobile Stacked Product Cards --}}
    <div class="md:hidden divide-y divide-[#efe9dd]">
        @forelse($products as $product)
            <div class="p-4 space-y-3">
                <div class="flex items-start gap-3">
                    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}"
                         class="w-16 h-20 rounded-xl object-cover bg-[#f2ece1] border border-[#e6dfd3] shrink-0">
                    <div class="min-w-0 flex-1">
                        <div class="flex items-start justify-between gap-2">
                            <p class="text-sm font-bold text-[#141414] leading-snug">{{ $product->name }}</p>
                            <span class="px-2 py-0.5 rounded bg-[#141414] text-[#e3c587] text-[10px] font-bold uppercase shrink-0">{{ $product->audience }}</span>
                        </div>
                        <p class="text-[11px] text-[#787166] mt-0.5">SKU: {{ $product->sku }} · {{ $product->category?->name }}</p>
                        <div class="flex flex-wrap items-center gap-2 mt-2">
                            <span class="text-sm font-extrabold text-[#141414]">₹{{ number_format($product->price) }}</span>
                            @if($product->mrp > $product->price)
                                <span class="text-xs text-[#8f877b] line-through">₹{{ number_format($product->mrp) }}</span>
                            @endif
                            <span class="ml-auto px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ $product->stock <= 0 ? 'bg-[#f9ecee] text-[#7c232b]' : ($product->stock <= 10 ? 'bg-[#f7f1e3] text-[#8c6829]' : 'bg-emerald-50 text-emerald-800') }}">
                                {{ $product->stock }} pcs
                            </span>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap items-center justify-between gap-2 pt-2 border-t border-[#efe9dd]">
                    <div class="flex items-center gap-1.5">
                        <form action="{{ route('admin.products.toggle', [$product, 'is_trending']) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="px-2.5 py-1 rounded-full text-[10px] font-bold border cursor-pointer {{ $product->is_trending ? 'bg-[#141414] text-[#e3c587] border-[#141414]' : 'border-[#d9d0c1] text-[#787166]' }}">
                                Trending
                            </button>
                        </form>
                        <form action="{{ route('admin.products.toggle', [$product, 'is_featured']) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="px-2.5 py-1 rounded-full text-[10px] font-bold border cursor-pointer {{ $product->is_featured ? 'bg-[#f7f1e3] text-[#8c6829] border-[#e3c587]' : 'border-[#d9d0c1] text-[#787166]' }}">
                                Featured
                            </button>
                        </form>
                    </div>

                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.products.edit', $product) }}" class="text-xs font-bold text-[#141414] hover:underline">Edit</a>
                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Delete product {{ addslashes($product->name) }}?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs font-bold text-[#7c232b] hover:underline cursor-pointer">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <p class="px-5 py-10 text-center text-xs text-[#787166]">No products match these filters.</p>
        @endforelse
    </div>

    {{-- Desktop Table --}}
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead class="bg-[#faf7f2] border-b border-[#e6dfd3] text-[#6f685e] uppercase tracking-wider text-[10px]">
                <tr>
                    <th class="px-5 py-3.5">Product</th>
                    <th class="px-5 py-3.5">Audience &amp; Category</th>
                    <th class="px-5 py-3.5">Pricing</th>
                    <th class="px-5 py-3.5">Stock</th>
                    <th class="px-5 py-3.5">Highlights</th>
                    <th class="px-5 py-3.5 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#efe9dd]">
                @forelse($products as $product)
                    <tr class="hover:bg-[#faf7f2]/70 transition-colors">
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-3">
                                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}"
                                     class="w-12 h-14 rounded-lg object-cover bg-[#f2ece1] border border-[#e6dfd3] shrink-0">
                                <div>
                                    <p class="font-bold text-[#141414]">{{ $product->name }}</p>
                                    <p class="text-[11px] text-[#787166]">SKU: {{ $product->sku }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="inline-block px-2 py-0.5 rounded bg-[#141414] text-[#e3c587] text-[10px] font-bold uppercase">{{ $product->audience }}</span>
                            <p class="text-xs font-semibold text-[#4a453e] mt-1">{{ $product->category?->name }}</p>
                        </td>
                        <td class="px-5 py-3.5">
                            <p class="font-extrabold text-[#141414]">₹{{ number_format($product->price) }}</p>
                            @if($product->mrp > $product->price)
                                <p class="text-[11px] text-[#8f877b] line-through">₹{{ number_format($product->mrp) }}</p>
                            @endif
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="px-2.5 py-1 rounded-full text-[11px] font-bold {{ $product->stock <= 0 ? 'bg-[#f9ecee] text-[#7c232b]' : ($product->stock <= 10 ? 'bg-[#f7f1e3] text-[#8c6829]' : 'bg-emerald-50 text-emerald-800') }}">
                                {{ $product->stock }} pcs
                            </span>
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-1.5">
                                <form action="{{ route('admin.products.toggle', [$product, 'is_trending']) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="px-2.5 py-1 rounded-full text-[10px] font-bold border cursor-pointer {{ $product->is_trending ? 'bg-[#141414] text-[#e3c587] border-[#141414]' : 'border-[#d9d0c1] text-[#787166] hover:text-[#141414]' }}">
                                        Trending
                                    </button>
                                </form>
                                <form action="{{ route('admin.products.toggle', [$product, 'is_featured']) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="px-2.5 py-1 rounded-full text-[10px] font-bold border cursor-pointer {{ $product->is_featured ? 'bg-[#f7f1e3] text-[#8c6829] border-[#e3c587]' : 'border-[#d9d0c1] text-[#787166] hover:text-[#141414]' }}">
                                        Featured
                                    </button>
                                </form>
                            </div>
                        </td>
                        <td class="px-5 py-3.5 text-right space-x-2.5 whitespace-nowrap">
                            <a href="{{ route('admin.products.edit', $product) }}" class="text-xs font-bold text-[#9b742e] hover:underline">Edit</a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Delete product {{ addslashes($product->name) }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs font-bold text-[#7c232b] hover:underline cursor-pointer">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-10 text-center text-[#787166]">No products match these filters.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($products->hasPages())
        <div class="px-5 py-3.5 border-t border-[#e6dfd3] bg-[#faf7f2]">{{ $products->links() }}</div>
    @endif
</div>
@endsection
