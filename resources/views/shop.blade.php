@extends('layouts.app')

@section('title', ($activeCategory ? $activeCategory->name . ' — ' : ($activeAudience === 'men' ? 'Mens Collection — ' : ($activeAudience === 'kids' ? 'Kids Collection — ' : 'Shop All — '))) . 'Royal Collection')

@section('content')
<div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">

    {{-- Top Page Header & Breadcrumbs --}}
    <div class="border-b border-[#e6dfd3] pb-5 mb-6 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <nav class="text-[11px] font-semibold text-neutral-500 flex flex-wrap items-center gap-1.5 mb-2">
                <a href="{{ route('home') }}" class="hover:text-[#141414]">Home</a>
                <span>/</span>
                @if($activeAudience !== 'all')
                    <a href="{{ route($activeAudience) }}" class="uppercase hover:text-[#141414]">{{ $activeAudience === 'men' ? 'Mens' : 'Kids' }}</a>
                @else
                    <span>Shop</span>
                @endif
                @if($activeCategory)
                    <span>/</span>
                    <span class="text-[#9b742e] font-bold">{{ $activeCategory->name }}</span>
                @endif
            </nav>

            <h1 class="font-editorial text-2xl sm:text-3xl lg:text-4xl font-bold tracking-tight text-[#141414]">
                @if($activeCategory)
                    {{ $activeCategory->name }}
                @elseif($activeAudience === 'men')
                    Mens Luxury Apparel
                @elseif($activeAudience === 'kids')
                    Kids Luxury Apparel
                @else
                    All Royal Collection Apparel
                @endif
            </h1>
            <p class="text-xs sm:text-sm text-neutral-500 mt-1 max-w-2xl">
                @if($activeCategory)
                    {{ $activeCategory->description }}
                @elseif($search !== '')
                    Showing search results for "<span class="font-bold text-[#141414]">{{ $search }}</span>"
                @else
                    Curated Menswear &amp; Kidswear crafted in heavyweight cotton, linen, and bio-wash fleece.
                @endif
            </p>
        </div>

        {{-- Sort Dropdown + Mobile Filter Trigger + Count --}}
        <div class="flex flex-wrap items-center justify-between md:justify-end gap-2.5 sm:gap-3 shrink-0">
            <span class="text-xs font-bold text-neutral-500">{{ $products->count() }} {{ Str::plural('Product', $products->count()) }}</span>

            <div class="flex items-center gap-2">
                <button type="button" id="toggleShopFilters"
                        class="lg:hidden h-9 px-3.5 rounded-lg border border-[#d9d0c1] bg-[#faf7f2] text-xs font-extrabold text-[#141414] flex items-center gap-1.5 cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    <span>Filters</span>
                </button>

                <form method="GET" action="{{ url()->current() }}" class="flex items-center">
                    @if($search !== '') <input type="hidden" name="q" value="{{ $search }}"> @endif
                    @if($selectedSize !== '') <input type="hidden" name="size" value="{{ $selectedSize }}"> @endif
                    @if($maxPrice) <input type="hidden" name="max_price" value="{{ $maxPrice }}"> @endif
                    @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                    @if(request('audience')) <input type="hidden" name="audience" value="{{ request('audience') }}"> @endif
                    <select name="sort" onchange="this.form.submit()" class="h-9 px-3 border border-[#d9d0c1] rounded-lg text-xs font-bold text-[#141414] bg-white focus:outline-none focus:border-[#9b742e]">
                        <option value="trending" @selected($sort === 'trending')>Sort: Trending</option>
                        <option value="newest" @selected($sort === 'newest')>Sort: Newest Drops</option>
                        <option value="price_asc" @selected($sort === 'price_asc')>Price: Low to High</option>
                        <option value="price_desc" @selected($sort === 'price_desc')>Price: High to Low</option>
                    </select>
                </form>
            </div>
        </div>
    </div>

    {{-- Top Category Filter Pills --}}
    <div class="flex items-center gap-2 overflow-x-auto no-scrollbar pb-3 mb-6 sm:mb-7">
        <a href="{{ route('shop', array_filter(['q' => $search ?: null])) }}"
           class="rc-pill {{ $activeAudience === 'all' && !$activeCategory ? 'active' : '' }}">
            All (Men &amp; Kids)
        </a>
        <a href="{{ route('men', array_filter(['q' => $search ?: null])) }}"
           class="rc-pill {{ $activeAudience === 'men' && !$activeCategory ? 'active' : '' }}">
            All Mens
        </a>
        <a href="{{ route('kids', array_filter(['q' => $search ?: null])) }}"
           class="rc-pill {{ $activeAudience === 'kids' && !$activeCategory ? 'active' : '' }}">
            All Kids
        </a>
        @foreach($availableCategories as $cat)
            <a href="{{ route('category.show', $cat->slug) }}"
               class="rc-pill {{ $activeCategory && $activeCategory->id === $cat->id ? 'active' : '' }}">
                {{ $cat->name }}
            </a>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        {{-- Left Filter Sidebar (collapsible on mobile/tablet, sticky sidebar on desktop) --}}
        <aside id="shopFilterPanel" class="hidden lg:block lg:col-span-3 space-y-6">
            <form method="GET" action="{{ route('shop') }}" class="bg-[#faf7f2] border border-[#e6dfd3] rounded-xl p-5 space-y-5 lg:sticky lg:top-24">
                <div class="flex items-center justify-between border-b border-[#e6dfd3] pb-3">
                    <h2 class="text-xs font-extrabold uppercase tracking-widest text-[#141414]">Filters</h2>
                    <a href="{{ route('shop') }}" class="text-[11px] font-bold text-[#7c232b] hover:underline">Clear All</a>
                </div>

                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-neutral-600 mb-2">Search</label>
                    <input type="text" name="q" value="{{ $search }}" placeholder="T-shirt, Linen, Hoodie..."
                           class="w-full h-9 px-3 rounded-lg border border-[#d9d0c1] bg-white text-xs focus:outline-none focus:border-[#9b742e]">
                </div>

                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-neutral-600 mb-2.5">Collection</p>
                    <div class="space-y-2 text-xs font-semibold">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="audience" value="all" @checked($activeAudience === 'all') class="accent-[#141414]">
                            <span>All (Mens &amp; Kids)</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="audience" value="men" @checked($activeAudience === 'men') class="accent-[#141414]">
                            <span>Mens Collection</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="audience" value="kids" @checked($activeAudience === 'kids') class="accent-[#141414]">
                            <span>Kids Collection</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-neutral-600 mb-2">Category</label>
                    <select name="category" class="w-full h-9 px-2.5 rounded-lg border border-[#d9d0c1] bg-white text-xs font-medium focus:outline-none focus:border-[#9b742e]">
                        <option value="">All Categories</option>
                        <optgroup label="Mens Categories">
                            @foreach($navMenCategories as $cat)
                                <option value="{{ $cat->slug }}" @selected($activeCategory && $activeCategory->id === $cat->id)>{{ $cat->name }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Kids Categories">
                            @foreach($navKidsCategories as $cat)
                                <option value="{{ $cat->slug }}" @selected($activeCategory && $activeCategory->id === $cat->id)>{{ $cat->name }}</option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>

                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-neutral-600 mb-2.5">Size</p>
                    <div class="flex flex-wrap gap-1.5">
                        <label class="cursor-pointer">
                            <input type="radio" name="size" value="" @checked($selectedSize === '') class="sr-only peer">
                            <span class="inline-block px-2.5 py-1 text-[11px] font-bold border border-[#d9d0c1] rounded-md bg-white peer-checked:bg-[#141414] peer-checked:text-[#e3c587] peer-checked:border-[#141414]">Any</span>
                        </label>
                        @foreach($availableSizes as $sz)
                            <label class="cursor-pointer">
                                <input type="radio" name="size" value="{{ $sz }}" @checked($selectedSize === $sz) class="sr-only peer">
                                <span class="inline-block px-2.5 py-1 text-[11px] font-bold border border-[#d9d0c1] rounded-md bg-white peer-checked:bg-[#141414] peer-checked:text-[#e3c587] peer-checked:border-[#141414]">{{ $sz }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-neutral-600 mb-2">Budget</label>
                    <select name="max_price" class="w-full h-9 px-2.5 rounded-lg border border-[#d9d0c1] bg-white text-xs font-medium focus:outline-none focus:border-[#9b742e]">
                        <option value="">Any Price</option>
                        <option value="999" @selected($maxPrice === 999)>Under ₹ 999</option>
                        <option value="1499" @selected($maxPrice === 1499)>Under ₹ 1,499</option>
                        <option value="1999" @selected($maxPrice === 1999)>Under ₹ 1,999</option>
                        <option value="2499" @selected($maxPrice === 2499)>Under ₹ 2,499</option>
                    </select>
                </div>

                <button type="submit" class="w-full py-2.5 bg-[#141414] hover:bg-neutral-800 text-[#e3c587] text-xs font-extrabold uppercase tracking-widest rounded-lg cursor-pointer transition-colors">
                    Apply Filters
                </button>
            </form>
        </aside>

        {{-- Main Catalog Grid --}}
        <div class="lg:col-span-9">
            @if($products->isEmpty())
                <div class="border border-[#e6dfd3] rounded-xl py-16 px-6 text-center bg-[#faf7f2]">
                    <h3 class="text-lg font-extrabold text-[#141414]">No Matching Styles Found</h3>
                    <p class="text-xs text-neutral-500 mt-1 mb-5">Try clearing a filter or exploring our Mens and Kids collections.</p>
                    <div class="flex flex-wrap gap-3 justify-center">
                        <a href="{{ route('shop') }}" class="px-5 py-2.5 bg-[#141414] text-[#e3c587] text-xs font-bold uppercase tracking-wider rounded-lg">Reset Filters</a>
                        <a href="{{ route('men') }}" class="px-5 py-2.5 border border-[#141414] text-xs font-bold uppercase tracking-wider rounded-lg">Browse Mens</a>
                        <a href="{{ route('kids') }}" class="px-5 py-2.5 border border-[#141414] text-xs font-bold uppercase tracking-wider rounded-lg">Browse Kids</a>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-x-3.5 sm:gap-x-6 gap-y-7 sm:gap-y-8">
                    @foreach($products as $product)
                        @include('partials.product-card', ['product' => $product])
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('toggleShopFilters')?.addEventListener('click', () => {
        document.getElementById('shopFilterPanel')?.classList.toggle('hidden');
    });
</script>
@endpush
