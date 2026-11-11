(() => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const appBase = (document.querySelector('meta[name="app-base"]')?.getAttribute('content') || '').replace(/\/+$/, '');
    const rcUrl = (path) => appBase + (path.startsWith('/') ? path : '/' + path);

    // --- Left Mega Drawer ---
    const menuBackdrop = document.getElementById('menuDrawerBackdrop');
    const menuDrawer = document.getElementById('menuDrawer');
    const openMenuBtn = document.getElementById('openMenuDrawer');
    const closeMenuBtn = document.getElementById('closeMenuDrawer');

    function setMenuDrawer(open) {
        if (!menuDrawer || !menuBackdrop) return;
        menuDrawer.classList.toggle('open', open);
        menuBackdrop.classList.toggle('open', open);
        document.body.classList.toggle('overflow-hidden', open);
    }

    openMenuBtn?.addEventListener('click', () => setMenuDrawer(true));
    closeMenuBtn?.addEventListener('click', () => setMenuDrawer(false));
    menuBackdrop?.addEventListener('click', () => setMenuDrawer(false));

    // Mobile & tablet search bar toggle
    const toggleMobileSearchBtn = document.getElementById('toggleMobileSearch');
    const mobileSearchBar = document.getElementById('mobileSearchBar');
    toggleMobileSearchBtn?.addEventListener('click', () => {
        if (!mobileSearchBar) return;
        mobileSearchBar.classList.toggle('hidden');
        if (!mobileSearchBar.classList.contains('hidden')) {
            mobileSearchBar.querySelector('input')?.focus();
        }
    });

    // Drawer audience tabs (MEN | KIDS)
    const drawerTabs = document.querySelectorAll('[data-drawer-tab]');
    const drawerPanels = document.querySelectorAll('[data-drawer-panel]');
    drawerTabs.forEach((tab) => {
        tab.addEventListener('click', () => {
            const target = tab.getAttribute('data-drawer-tab');
            drawerTabs.forEach((btn) => {
                const isActive = btn.getAttribute('data-drawer-tab') === target;
                btn.classList.toggle('text-[#141414]', isActive);
                btn.classList.toggle('border-b-2', isActive);
                btn.classList.toggle('border-[#9b742e]', isActive);
                btn.classList.toggle('text-neutral-500', !isActive);
            });
            drawerPanels.forEach((panel) => {
                panel.classList.toggle('hidden', panel.getAttribute('data-drawer-panel') !== target);
            });
        });
    });

    // --- Right Cart Drawer ---
    const cartBackdrop = document.getElementById('cartDrawerBackdrop');
    const cartDrawer = document.getElementById('cartDrawer');
    const openCartBtns = document.querySelectorAll('[data-open-cart]');
    const closeCartBtn = document.getElementById('closeCartDrawer');

    function setCartDrawer(open) {
        if (!cartDrawer || !cartBackdrop) return;
        cartDrawer.classList.toggle('open', open);
        cartBackdrop.classList.toggle('open', open);
        document.body.classList.toggle('overflow-hidden', open);
    }

    openCartBtns.forEach((btn) => btn.addEventListener('click', () => setCartDrawer(true)));
    closeCartBtn?.addEventListener('click', () => setCartDrawer(false));
    cartBackdrop?.addEventListener('click', () => setCartDrawer(false));

    // Toast notification helper
    const toastEl = document.getElementById('rcToast');
    let toastTimer = null;
    function showToast(msg) {
        if (!toastEl) return;
        toastEl.textContent = msg;
        toastEl.classList.remove('opacity-0', 'translate-y-3', 'pointer-events-none');
        clearTimeout(toastTimer);
        toastTimer = setTimeout(() => {
            toastEl.classList.add('opacity-0', 'translate-y-3', 'pointer-events-none');
        }, 2800);
    }

    // Format INR currency
    function formatINR(num) {
        return '₹ ' + Number(num).toLocaleString('en-IN');
    }

    // Render live cart drawer contents from AJAX JSON payload
    function renderCartDrawer(cart) {
        const badgeEls = document.querySelectorAll('.js-cart-count');
        badgeEls.forEach((el) => {
            el.textContent = cart.count;
            el.classList.toggle('hidden', cart.count === 0);
        });

        const body = document.getElementById('cartDrawerBody');
        const footer = document.getElementById('cartDrawerFooter');
        const shippingText = document.getElementById('cartShippingBarText');
        const shippingProgress = document.getElementById('cartShippingProgress');
        const subtotalEl = document.getElementById('cartDrawerSubtotal');

        if (shippingText && shippingProgress) {
            if (cart.subtotal === 0) {
                shippingText.textContent = 'Free Express Delivery on orders above ₹ 1,499';
                shippingProgress.style.width = '0%';
            } else if (cart.freeShippingRemaining > 0) {
                shippingText.textContent = `Add ${formatINR(cart.freeShippingRemaining)} more for FREE Express Shipping`;
                const pct = Math.min(100, Math.round((cart.subtotal / 1499) * 100));
                shippingProgress.style.width = `${pct}%`;
            } else {
                shippingText.textContent = 'You unlocked FREE Express Shipping!';
                shippingProgress.style.width = '100%';
            }
        }

        if (!body || !footer) return;

        if (!cart.items || cart.items.length === 0) {
            body.innerHTML = `
                <div class="py-16 text-center px-6">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-neutral-100 flex items-center justify-center text-neutral-400">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    </div>
                    <h4 class="font-bold text-neutral-900 mb-1">Your Royal Bag is Empty</h4>
                    <p class="text-xs text-neutral-500 mb-5">Explore heavyweight Menswear and soft Kidswear essentials.</p>
                    <div class="flex gap-2 justify-center">
                        <a href="${rcUrl('/men')}" class="px-4 py-2 bg-neutral-900 text-white text-xs font-bold uppercase tracking-wider rounded">Shop Men</a>
                        <a href="${rcUrl('/kids')}" class="px-4 py-2 border border-neutral-900 text-neutral-900 text-xs font-bold uppercase tracking-wider rounded">Shop Kids</a>
                    </div>
                </div>
            `;
            footer.classList.add('hidden');
            return;
        }

        footer.classList.remove('hidden');
        if (subtotalEl) subtotalEl.textContent = formatINR(cart.subtotal);

        body.innerHTML = cart.items.map((item) => `
            <div class="flex gap-3.5 py-4 border-b border-neutral-100">
                <a href="${rcUrl('/product/' + item.slug)}" class="w-20 h-24 rounded bg-neutral-100 overflow-hidden shrink-0">
                    <img src="${item.image}" alt="${item.name}" class="w-full h-full object-cover object-top">
                </a>
                <div class="flex-1 min-w-0">
                    <div class="flex justify-between items-start gap-2">
                        <a href="${rcUrl('/product/' + item.slug)}" class="text-xs font-bold text-neutral-900 line-clamp-2 hover:underline">${item.name}</a>
                        <button type="button" class="text-neutral-400 hover:text-red-600 text-xs js-cart-remove" data-key="${item.key}" title="Remove">✕</button>
                    </div>
                    <p class="text-[11px] text-neutral-500 mt-1">Size: <span class="font-semibold text-neutral-800">${item.size}</span></p>
                    <div class="flex items-center justify-between mt-3">
                        <div class="inline-flex items-center border border-neutral-300 rounded">
                            <button type="button" class="px-2 py-0.5 text-xs font-bold text-neutral-700 hover:bg-neutral-100 js-cart-qty" data-key="${item.key}" data-qty="${item.quantity - 1}">−</button>
                            <span class="px-2.5 py-0.5 text-xs font-bold text-neutral-900">${item.quantity}</span>
                            <button type="button" class="px-2 py-0.5 text-xs font-bold text-neutral-700 hover:bg-neutral-100 js-cart-qty" data-key="${item.key}" data-qty="${item.quantity + 1}">+</button>
                        </div>
                        <span class="text-sm font-bold text-neutral-900">${formatINR(item.line_total)}</span>
                    </div>
                </div>
            </div>
        `).join('');
    }

    // Delegate Cart Add (Quick-Add size buttons + Detail page AJAX add form)
    document.addEventListener('click', async (e) => {
        const quickBtn = e.target.closest('[data-quick-add]');
        if (quickBtn) {
            e.preventDefault();
            const productId = quickBtn.getAttribute('data-product-id');
            const size = quickBtn.getAttribute('data-size');
            quickBtn.disabled = true;
            try {
                const res = await fetch(rcUrl('/cart/add'), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ product_id: productId, size, quantity: 1 }),
                });
                const data = await res.json();
                if (data.ok) {
                    renderCartDrawer(data.cart);
                    setCartDrawer(true);
                    showToast(data.message);
                }
            } catch (err) {
                console.error(err);
            } finally {
                quickBtn.disabled = false;
            }
            return;
        }

        const qtyBtn = e.target.closest('.js-cart-qty');
        if (qtyBtn) {
            const key = qtyBtn.getAttribute('data-key');
            const quantity = Number(qtyBtn.getAttribute('data-qty'));
            const res = await fetch(rcUrl('/cart/update'), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ key, quantity }),
            });
            const data = await res.json();
            if (data.ok) renderCartDrawer(data.cart);
            return;
        }

        const removeBtn = e.target.closest('.js-cart-remove');
        if (removeBtn) {
            const key = removeBtn.getAttribute('data-key');
            const res = await fetch(rcUrl('/cart/remove'), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ key }),
            });
            const data = await res.json();
            if (data.ok) renderCartDrawer(data.cart);
            return;
        }

        // Wishlist heart toggle
        const wishBtn = e.target.closest('[data-wishlist-toggle]');
        if (wishBtn) {
            e.preventDefault();
            const productId = wishBtn.getAttribute('data-product-id');
            try {
                const res = await fetch(rcUrl('/wishlist/toggle'), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ product_id: productId }),
                });
                const data = await res.json();
                if (data.ok) {
                    document.querySelectorAll(`[data-wishlist-toggle][data-product-id="${productId}"]`).forEach((b) => {
                        const svg = b.querySelector('svg');
                        b.classList.toggle('text-[#7c232b]', data.saved);
                        b.classList.toggle('text-neutral-700', !data.saved);
                        if (svg) svg.setAttribute('fill', data.saved ? 'currentColor' : 'none');
                    });
                    document.querySelectorAll('.js-wishlist-count').forEach((el) => {
                        el.textContent = data.count;
                        el.classList.toggle('hidden', data.count === 0);
                    });
                    showToast(data.saved ? 'Saved to your Royal Wishlist' : 'Removed from Wishlist');
                }
            } catch (err) {
                console.error(err);
            }
        }
    });

    // Detail page AJAX Add to Bag interception
    const detailCartForm = document.getElementById('detailAddToBagForm');
    if (detailCartForm) {
        detailCartForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(detailCartForm);
            const size = formData.get('size');
            if (!size) {
                showToast('Please select a size first.');
                return;
            }
            const res = await fetch(rcUrl('/cart/add'), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({
                    product_id: formData.get('product_id'),
                    size,
                    quantity: Number(formData.get('quantity') || 1),
                }),
            });
            const data = await res.json();
            if (data.ok) {
                renderCartDrawer(data.cart);
                setCartDrawer(true);
                showToast(data.message);
            }
        });
    }

    // Home page live tag filter pills (matching The Souled Store filter strip)
    const homeFilterPills = document.querySelectorAll('[data-home-filter]');
    const homeFilterCards = document.querySelectorAll('[data-filter-tags]');
    if (homeFilterPills.length && homeFilterCards.length) {
        homeFilterPills.forEach((pill) => {
            pill.addEventListener('click', () => {
                const tag = pill.getAttribute('data-home-filter');
                homeFilterPills.forEach((p) => p.classList.toggle('active', p === pill));
                let visibleCount = 0;
                homeFilterCards.forEach((card) => {
                    const tags = (card.getAttribute('data-filter-tags') || '').split(',');
                    const match = tag === 'trending' ? tags.includes('trending') : tags.includes(tag);
                    card.classList.toggle('hidden', !match);
                    if (match) visibleCount++;
                });
                const counter = document.getElementById('homeFilterCount');
                if (counter) counter.textContent = `${visibleCount} Styles`;
            });
        });
    }

    // Hero Slider
    const slides = document.querySelectorAll('[data-hero-slide]');
    const dots = document.querySelectorAll('[data-hero-dot]');
    if (slides.length > 1) {
        let currentSlide = 0;
        const showSlide = (idx) => {
            currentSlide = (idx + slides.length) % slides.length;
            slides.forEach((s, i) => {
                s.classList.toggle('opacity-100', i === currentSlide);
                s.classList.toggle('opacity-0', i !== currentSlide);
                s.classList.toggle('pointer-events-none', i !== currentSlide);
            });
            dots.forEach((d, i) => {
                d.classList.toggle('bg-white', i === currentSlide);
                d.classList.toggle('w-7', i === currentSlide);
                d.classList.toggle('bg-white/45', i !== currentSlide);
                d.classList.toggle('w-2.5', i !== currentSlide);
            });
        };
        dots.forEach((d, i) => d.addEventListener('click', () => showSlide(i)));
        document.getElementById('heroPrev')?.addEventListener('click', () => showSlide(currentSlide - 1));
        document.getElementById('heroNext')?.addEventListener('click', () => showSlide(currentSlide + 1));
        setInterval(() => showSlide(currentSlide + 1), 6000);
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            setMenuDrawer(false);
            setCartDrawer(false);
            document.getElementById('sizeGuideModal')?.classList.add('hidden');
        }
    });
})();
