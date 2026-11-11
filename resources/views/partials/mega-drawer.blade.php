{{-- Left Slide-Out Category Drawer (Strictly MEN & KIDS) --}}
<div id="menuDrawerBackdrop" class="rc-drawer-backdrop fixed inset-0 z-50 bg-black/55 backdrop-blur-[2px]"></div>

<aside id="menuDrawer" class="rc-drawer-left fixed top-0 left-0 bottom-0 z-50 w-[88vw] max-w-[400px] bg-white shadow-2xl flex flex-col">
    {{-- Drawer Header with Royal Collection Logo & Shop All CTA --}}
    <div class="px-4 py-3.5 border-b border-[#e6dfd3] flex items-center justify-between bg-[#faf7f2]">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/royal-collection-logo.png') }}" alt="Royal Collection" class="h-10 w-auto object-contain mix-blend-multiply">
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('shop') }}" class="px-3.5 py-1.5 bg-[#141414] rounded-lg text-xs font-bold text-[#e3c587] hover:bg-neutral-800 transition-colors">
                Shop All
            </a>
            <button type="button" id="closeMenuDrawer" aria-label="Close menu" class="p-1.5 rounded-lg text-neutral-600 hover:bg-white hover:text-[#141414] cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Obsidian & Gold Privilege Strip --}}
    <div class="bg-[#141414] text-[#e3c587] text-[11px] font-bold tracking-wider uppercase py-2 px-4 flex items-center justify-center text-center">
        <span>Royal Privilege · Free Express Delivery Above ₹1,499</span>
    </div>

    {{-- Audience Switcher Tabs: ONLY MEN & KIDS --}}
    <div class="grid grid-cols-2 border-b border-[#e6dfd3] text-center bg-white">
        <button type="button" data-drawer-tab="men"
            class="py-3 text-xs font-extrabold tracking-widest uppercase text-[#141414] border-b-2 border-[#9b742e] cursor-pointer">
            MEN
        </button>
        <button type="button" data-drawer-tab="kids"
            class="py-3 text-xs font-extrabold tracking-widest uppercase text-neutral-500 cursor-pointer">
            KIDS
        </button>
    </div>

    {{-- Scrollable Drawer Content --}}
    <div class="flex-1 overflow-y-auto px-4 py-4 space-y-6">

        {{-- MEN PANEL --}}
        <div data-drawer-panel="men" class="space-y-6">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-neutral-500">Featured Edits</span>
                    <a href="{{ route('men') }}" class="text-[11px] font-bold text-[#9b742e] hover:underline">View All Mens →</a>
                </div>
                <div class="grid grid-cols-4 gap-2">
                    <a href="{{ route('category.show', 'men-oversized-tshirts') }}" class="group text-center">
                        <div class="aspect-square rounded-lg overflow-hidden bg-[#f2ece1] mb-1.5 border border-[#e6dfd3]">
                            <img src="{{ asset('images/products/men-oversized-obsidian.jpg') }}" alt="Oversized" class="w-full h-full object-cover object-top group-hover:scale-105 transition-transform">
                        </div>
                        <span class="block text-[10.5px] font-semibold text-[#141414] leading-tight">Heavy Tees</span>
                    </a>
                    <a href="{{ route('category.show', 'men-shirts') }}" class="group text-center">
                        <div class="aspect-square rounded-lg overflow-hidden bg-[#f2ece1] mb-1.5 border border-[#e6dfd3]">
                            <img src="{{ asset('images/products/men-linen-sky.jpg') }}" alt="Linen Shirts" class="w-full h-full object-cover object-top group-hover:scale-105 transition-transform">
                        </div>
                        <span class="block text-[10.5px] font-semibold text-[#141414] leading-tight">Pure Linen</span>
                    </a>
                    <a href="{{ route('category.show', 'men-polos') }}" class="group text-center">
                        <div class="aspect-square rounded-lg overflow-hidden bg-[#f2ece1] mb-1.5 border border-[#e6dfd3]">
                            <img src="{{ asset('images/products/men-polo-zip-sand.jpg') }}" alt="Polos" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                        </div>
                        <span class="block text-[10.5px] font-semibold text-[#141414] leading-tight">Royal Polos</span>
                    </a>
                    <a href="{{ route('category.show', 'men-cargos-joggers') }}" class="group text-center">
                        <div class="aspect-square rounded-lg overflow-hidden bg-[#f2ece1] mb-1.5 border border-[#e6dfd3]">
                            <img src="{{ asset('images/products/men-cargo-olive.jpg') }}" alt="Cargos" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                        </div>
                        <span class="block text-[10.5px] font-semibold text-[#141414] leading-tight">Cargos</span>
                    </a>
                </div>
            </div>

            <div class="border-t border-[#efe9dd] pt-4">
                <h4 class="text-xs font-extrabold uppercase tracking-wider text-[#141414] mb-3">Shop All Mens Categories</h4>
                <div class="grid grid-cols-3 gap-2.5">
                    @foreach($navMenCategories as $cat)
                        <a href="{{ route('category.show', $cat->slug) }}" class="group text-center">
                            <div class="aspect-square rounded-lg overflow-hidden bg-[#f2ece1] mb-1.5 border border-[#e6dfd3]">
                                <img src="{{ asset($cat->image) }}" alt="{{ $cat->name }}" class="w-full h-full object-cover object-top group-hover:scale-105 transition-transform">
                            </div>
                            <span class="block text-[11px] font-semibold text-neutral-800 leading-tight group-hover:text-[#9b742e]">{{ $cat->name }}</span>
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="border-t border-[#efe9dd] pt-3 divide-y divide-[#efe9dd] text-xs font-bold text-[#141414]">
                <a href="{{ route('men') }}" class="py-3 flex items-center justify-between hover:text-[#9b742e]">
                    <span>All Mens Apparel</span><span>›</span>
                </a>
                <a href="{{ route('shop', ['audience' => 'men', 'trending' => 1]) }}" class="py-3 flex items-center justify-between hover:text-[#9b742e]">
                    <span>Trending in Mens</span><span>›</span>
                </a>
            </div>
        </div>

        {{-- KIDS PANEL --}}
        <div data-drawer-panel="kids" class="hidden space-y-6">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-neutral-500">Royal Kids Spotlight</span>
                    <a href="{{ route('kids') }}" class="text-[11px] font-bold text-[#9b742e] hover:underline">View All Kids →</a>
                </div>
                <div class="grid grid-cols-4 gap-2">
                    <a href="{{ route('category.show', 'kids-tshirts') }}" class="group text-center">
                        <div class="aspect-square rounded-lg overflow-hidden bg-[#f2ece1] mb-1.5 border border-[#e6dfd3]">
                            <img src="{{ asset('images/products/kids-tee-mustard.jpg') }}" alt="Kids Tees" class="w-full h-full object-cover object-top group-hover:scale-105 transition-transform">
                        </div>
                        <span class="block text-[10.5px] font-semibold text-[#141414] leading-tight">Bio Tees</span>
                    </a>
                    <a href="{{ route('category.show', 'kids-hoodies-jackets') }}" class="group text-center">
                        <div class="aspect-square rounded-lg overflow-hidden bg-[#f2ece1] mb-1.5 border border-[#e6dfd3]">
                            <img src="{{ asset('images/products/kids-hoodie-navy.jpg') }}" alt="Kids Hoodies" class="w-full h-full object-cover object-top group-hover:scale-105 transition-transform">
                        </div>
                        <span class="block text-[10.5px] font-semibold text-[#141414] leading-tight">Varsity</span>
                    </a>
                    <a href="{{ route('category.show', 'kids-coord-sets') }}" class="group text-center">
                        <div class="aspect-square rounded-lg overflow-hidden bg-[#f2ece1] mb-1.5 border border-[#e6dfd3]">
                            <img src="{{ asset('images/products/kids-coord-lime.jpg') }}" alt="Co-ord Sets" class="w-full h-full object-cover object-top group-hover:scale-105 transition-transform">
                        </div>
                        <span class="block text-[10.5px] font-semibold text-[#141414] leading-tight">Co-ords</span>
                    </a>
                    <a href="{{ route('category.show', 'kids-denim-dungarees') }}" class="group text-center">
                        <div class="aspect-square rounded-lg overflow-hidden bg-[#f2ece1] mb-1.5 border border-[#e6dfd3]">
                            <img src="{{ asset('images/products/kids-denim-mint-set.jpg') }}" alt="Denim" class="w-full h-full object-cover object-top group-hover:scale-105 transition-transform">
                        </div>
                        <span class="block text-[10.5px] font-semibold text-[#141414] leading-tight">Denim</span>
                    </a>
                </div>
            </div>

            <div class="border-t border-[#efe9dd] pt-4">
                <h4 class="text-xs font-extrabold uppercase tracking-wider text-[#141414] mb-3">Shop All Kids Categories</h4>
                <div class="grid grid-cols-3 gap-2.5">
                    @foreach($navKidsCategories as $cat)
                        <a href="{{ route('category.show', $cat->slug) }}" class="group text-center">
                            <div class="aspect-square rounded-lg overflow-hidden bg-[#f2ece1] mb-1.5 border border-[#e6dfd3]">
                                <img src="{{ asset($cat->image) }}" alt="{{ $cat->name }}" class="w-full h-full object-cover object-top group-hover:scale-105 transition-transform">
                            </div>
                            <span class="block text-[11px] font-semibold text-neutral-800 leading-tight group-hover:text-[#9b742e]">{{ $cat->name }}</span>
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="border-t border-[#efe9dd] pt-3 divide-y divide-[#efe9dd] text-xs font-bold text-[#141414]">
                <a href="{{ route('kids') }}" class="py-3 flex items-center justify-between hover:text-[#9b742e]">
                    <span>All Kids Apparel</span><span>›</span>
                </a>
                <a href="{{ route('shop', ['audience' => 'kids', 'trending' => 1]) }}" class="py-3 flex items-center justify-between hover:text-[#9b742e]">
                    <span>Bestsellers for Kids</span><span>›</span>
                </a>
            </div>
        </div>

        {{-- Storefront Pages Footer inside Drawer --}}
        <div class="border-t border-[#e6dfd3] pt-4 space-y-1 text-xs font-bold text-neutral-700">
            <a href="{{ route('home') }}" class="block py-2 hover:text-[#141414]">Home</a>
            <a href="{{ route('shop') }}" class="block py-2 hover:text-[#141414]">Shop All Products</a>
            <a href="{{ auth()->check() ? route('account') : route('login') }}" class="block py-2 hover:text-[#141414]">{{ auth()->check() ? 'My Account' : 'Sign In / Register' }}</a>
            <a href="{{ route('wishlist.index') }}" class="block py-2 hover:text-[#141414]">Saved Wishlist ({{ $wishlistCount }})</a>
            <a href="{{ route('cart.index') }}" class="block py-2 hover:text-[#141414]">Shopping Bag ({{ $cartSummary['count'] }})</a>
            <a href="{{ route('contact') }}" class="block py-2 text-[#9b742e] hover:underline">Contact Royal Collection Concierge</a>
        </div>
    </div>
</aside>
