<footer class="bg-[#141414] text-neutral-300 mt-16 border-t border-[#c69d54]/30">
    {{-- Brand Trust Bar --}}
    <div class="border-b border-neutral-800 bg-[#1a1815]">
        <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 py-6 grid grid-cols-2 lg:grid-cols-4 gap-6 text-center">
            <div>
                <p class="text-[11px] sm:text-xs font-extrabold uppercase tracking-widest text-[#e3c587]">240+ GSM Luxury Cotton</p>
                <p class="text-[11px] text-neutral-400 mt-1">Heavy gauge &amp; skin-safe bio-wash fabrics</p>
            </div>
            <div>
                <p class="text-[11px] sm:text-xs font-extrabold uppercase tracking-widest text-[#e3c587]">Mens &amp; Kids Exclusives</p>
                <p class="text-[11px] text-neutral-400 mt-1">Tailored silhouettes from 2Y to XXL</p>
            </div>
            <div>
                <p class="text-[11px] sm:text-xs font-extrabold uppercase tracking-widest text-[#e3c587]">7-Day Easy Exchanges</p>
                <p class="text-[11px] text-neutral-400 mt-1">Hassle-free doorstep size replacement</p>
            </div>
            <div>
                <p class="text-[11px] sm:text-xs font-extrabold uppercase tracking-widest text-[#e3c587]">Free Express Shipping</p>
                <p class="text-[11px] text-neutral-400 mt-1">On all Royal Collection orders above ₹1,499</p>
            </div>
        </div>
    </div>

    {{-- Main Footer Columns --}}
    <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 py-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-10">
        {{-- Brand Column featuring User's Royal Collection Logo --}}
        <div class="space-y-4">
            <a href="{{ route('home') }}" class="inline-block bg-white px-4 py-2.5 rounded-xl shadow-sm">
                <img src="{{ asset('images/royal-collection-logo.png') }}" alt="Royal Collection Luxury Apparel" class="h-11 w-auto object-contain">
            </a>
            <p class="text-xs leading-relaxed text-neutral-400">
                Royal Collection crafts elevated everyday luxury apparel exclusively for Men and Kids — blending heavyweight street silhouettes with tailored craftsmanship.
            </p>
        </div>

        {{-- Mens Categories --}}
        <div>
            <h4 class="text-xs font-extrabold uppercase tracking-widest text-[#e3c587] mb-4">Mens Collection</h4>
            <ul class="space-y-2.5 text-xs">
                @foreach($navMenCategories as $cat)
                    <li>
                        <a href="{{ route('category.show', $cat->slug) }}" class="hover:text-white transition-colors">{{ $cat->name }}</a>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- Kids Categories --}}
        <div>
            <h4 class="text-xs font-extrabold uppercase tracking-widest text-[#e3c587] mb-4">Kids Collection</h4>
            <ul class="space-y-2.5 text-xs">
                @foreach($navKidsCategories as $cat)
                    <li>
                        <a href="{{ route('category.show', $cat->slug) }}" class="hover:text-white transition-colors">{{ $cat->name }}</a>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- Storefront Pages & Concierge --}}
        <div>
            <h4 class="text-xs font-extrabold uppercase tracking-widest text-[#e3c587] mb-4">Storefront &amp; Support</h4>
            <ul class="space-y-2.5 text-xs">
                <li><a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a></li>
                <li><a href="{{ route('shop') }}" class="hover:text-white transition-colors">Shop All</a></li>
                <li><a href="{{ route('wishlist.index') }}" class="hover:text-white transition-colors">Wishlist</a></li>
                <li><a href="{{ route('cart.index') }}" class="hover:text-white transition-colors">Shopping Bag</a></li>
                <li><a href="{{ auth()->check() ? route('account') : route('login') }}" class="hover:text-white transition-colors">{{ auth()->check() ? 'My Account' : 'Sign In / Register' }}</a></li>
                <li><a href="{{ route('contact') }}" class="text-[#e3c587] font-bold hover:underline">Contact Us</a></li>
                <li><a href="{{ route('admin.dashboard') }}" class="text-neutral-400 hover:text-white transition-colors">Admin Panel</a></li>
            </ul>
            <div class="mt-5 pt-4 border-t border-neutral-800 text-[11px] text-neutral-400 space-y-1">
                <p>Concierge: <span class="text-neutral-200">care@royalcollection.in</span></p>
                <p>Studio Line: <span class="text-neutral-200">+91 98200 11400</span></p>
                <p>Mon–Sat · 10:00 AM – 7:30 PM IST</p>
            </div>
        </div>
    </div>

    <div class="border-t border-neutral-800 py-5 px-4 lg:px-8 text-center text-[11px] text-neutral-500">
        &copy; {{ date('Y') }} <span class="text-[#e3c587] font-bold">ROYAL COLLECTION — LUXURY APPAREL</span>. All rights reserved.
    </div>
</footer>
