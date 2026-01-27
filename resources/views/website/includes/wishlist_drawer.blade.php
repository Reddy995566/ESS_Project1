<!-- Wishlist Drawer Overlay -->
<div id="wishlistDrawerOverlay" onclick="toggleWishlistDrawer()" class="fixed inset-0 bg-black/50 z-[1050] hidden transition-opacity duration-300 opacity-0"></div>

<!-- Wishlist Drawer -->
<div id="wishlistDrawer" class="fixed top-0 right-0 h-full w-full sm:w-[420px] z-[1060] transform translate-x-full transition-transform duration-300 shadow-2xl flex flex-col font-sans-premium" style="background-color: var(--color-bg-primary);">
    
    <!-- Drawer Header -->
    <div class="px-5 py-4 flex items-center justify-between" style="border-bottom: 1px solid var(--color-border); background-color: var(--color-bg-primary);">
        <h2 class="font-serif-elegant text-xl tracking-wide" style="color: var(--color-text-primary);">Wishlist (<span id="wishlistCount">0</span>)</h2>
        <button onclick="toggleWishlistDrawer()" class="transition-colors focus:outline-none" style="color: var(--color-text-muted);">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    <!-- Drawer Body -->
    <div class="flex-1 overflow-y-auto px-5 py-4 scrollbar-hide">
        <!-- Skeleton Loader -->
        <div id="wishlistSkeleton" class="hidden animate-pulse">
            <!-- Wishlist Items Skeleton -->
            <div class="space-y-4">
                <!-- Skeleton Item 1 -->
                <div class="flex gap-4 items-start pb-4 border-b border-[#D6B27A]/20">
                    <div class="w-24 h-32 bg-gray-200 rounded-sm flex-shrink-0"></div>
                    <div class="flex-1 space-y-2 py-1">
                        <div class="flex justify-between">
                            <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                            <div class="h-4 w-4 bg-gray-200 rounded"></div>
                        </div>
                        <div class="h-3 bg-gray-200 rounded w-1/4"></div>
                        <div class="h-4 bg-gray-200 rounded w-1/3"></div>
                        <div class="h-8 bg-gray-200 rounded w-full mt-2"></div>
                    </div>
                </div>
                <!-- Skeleton Item 2 -->
                <div class="flex gap-4 items-start pb-4 border-b border-[#D6B27A]/20">
                    <div class="w-24 h-32 bg-gray-200 rounded-sm flex-shrink-0"></div>
                    <div class="flex-1 space-y-2 py-1">
                        <div class="flex justify-between">
                            <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                            <div class="h-4 w-4 bg-gray-200 rounded"></div>
                        </div>
                        <div class="h-3 bg-gray-200 rounded w-1/4"></div>
                        <div class="h-4 bg-gray-200 rounded w-1/3"></div>
                        <div class="h-8 bg-gray-200 rounded w-full mt-2"></div>
                    </div>
                </div>
                <!-- Skeleton Item 3 -->
                <div class="flex gap-4 items-start pb-4">
                    <div class="w-24 h-32 bg-gray-200 rounded-sm flex-shrink-0"></div>
                    <div class="flex-1 space-y-2 py-1">
                        <div class="flex justify-between">
                            <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                            <div class="h-4 w-4 bg-gray-200 rounded"></div>
                        </div>
                        <div class="h-3 bg-gray-200 rounded w-1/4"></div>
                        <div class="h-4 bg-gray-200 rounded w-1/3"></div>
                        <div class="h-8 bg-gray-200 rounded w-full mt-2"></div>
                    </div>
                </div>
            </div>
            
            <!-- Best Sellers Skeleton -->
            <div class="mt-6 border-t border-[#441227]/10 pt-6">
                <!-- Header Skeleton -->
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-gray-200 rounded"></div>
                        <div class="h-4 bg-gray-200 rounded w-40"></div>
                    </div>
                    <div class="w-4 h-4 bg-gray-200 rounded"></div>
                </div>
                
                <!-- Best Sellers Items Skeleton -->
                <div class="space-y-4">
                    <!-- Skeleton Item 1 -->
                    <div class="flex gap-4 items-start pb-4 border-b border-[#D6B27A]/20">
                        <div class="w-24 h-32 bg-gray-200 rounded-sm flex-shrink-0"></div>
                        <div class="flex-1 space-y-2 py-1">
                            <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                            <div class="h-4 bg-gray-200 rounded w-1/3"></div>
                            <div class="h-8 bg-gray-200 rounded w-full mt-2"></div>
                        </div>
                    </div>
                    <!-- Skeleton Item 2 -->
                    <div class="flex gap-4 items-start pb-4 border-b border-[#D6B27A]/20">
                        <div class="w-24 h-32 bg-gray-200 rounded-sm flex-shrink-0"></div>
                        <div class="flex-1 space-y-2 py-1">
                            <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                            <div class="h-4 bg-gray-200 rounded w-1/3"></div>
                            <div class="h-8 bg-gray-200 rounded w-full mt-2"></div>
                        </div>
                    </div>
                    <!-- Skeleton Item 3 -->
                    <div class="flex gap-4 items-start pb-4 border-b border-[#D6B27A]/20">
                        <div class="w-24 h-32 bg-gray-200 rounded-sm flex-shrink-0"></div>
                        <div class="flex-1 space-y-2 py-1">
                            <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                            <div class="h-4 bg-gray-200 rounded w-1/3"></div>
                            <div class="h-8 bg-gray-200 rounded w-full mt-2"></div>
                        </div>
                    </div>
                    <!-- Skeleton Item 4 -->
                    <div class="flex gap-4 items-start pb-4 border-b border-[#D6B27A]/20">
                        <div class="w-24 h-32 bg-gray-200 rounded-sm flex-shrink-0"></div>
                        <div class="flex-1 space-y-2 py-1">
                            <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                            <div class="h-4 bg-gray-200 rounded w-1/3"></div>
                            <div class="h-8 bg-gray-200 rounded w-full mt-2"></div>
                        </div>
                    </div>
                    <!-- Skeleton Item 5 -->
                    <div class="flex gap-4 items-start pb-4">
                        <div class="w-24 h-32 bg-gray-200 rounded-sm flex-shrink-0"></div>
                        <div class="flex-1 space-y-2 py-1">
                            <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                            <div class="h-4 bg-gray-200 rounded w-1/3"></div>
                            <div class="h-8 bg-gray-200 rounded w-full mt-2"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Wishlist Items -->
        <div id="wishlistItemsContainer" class="space-y-4">
            <!-- Items will be injected here via JS -->
             <div class="text-center py-10 text-gray-500">Your wishlist is empty</div>
        </div>
        
        <!-- Explore Our Best Sellers Section -->
        <div id="bestSellersSection" class="mt-6 pt-6 hidden" style="border-top: 1px solid var(--color-border);">
            <div class="flex items-center justify-between cursor-pointer group mb-4" onclick="toggleBestSellers()">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--color-primary);">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                        </path>
                    </svg>
                    <span class="font-medium text-sm" style="color: var(--color-primary);">Explore Our Best Sellers</span>
                </div>
                <svg id="bestSellersArrow" class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--color-primary);">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                </svg>
            </div>
            
            <!-- Best Sellers Products List -->
            <div id="bestSellersList" class="space-y-4">
                <!-- Products will be injected here via JS -->
            </div>
        </div>
    </div>
</div>

<script>
    function toggleWishlistDrawer() {
        const drawer = document.getElementById('wishlistDrawer');
        const overlay = document.getElementById('wishlistDrawerOverlay');
        const body = document.body;

        if (drawer.classList.contains('translate-x-full')) {
            const scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;
            body.style.paddingRight = `${scrollbarWidth}px`;
            
            overlay.classList.remove('hidden');
            setTimeout(() => {
                overlay.classList.remove('opacity-0');
                drawer.classList.remove('translate-x-full');
            }, 10);
            body.classList.add('overflow-hidden');
            
            // Fetch wishlist when opening
            fetchWishlist();
        } else {
            overlay.classList.add('opacity-0');
            drawer.classList.add('translate-x-full');
            
            setTimeout(() => {
                overlay.classList.add('hidden');
                body.classList.remove('overflow-hidden');
                body.style.paddingRight = '';
            }, 300);
        }
    }

    // --- Dynamic Wishlist Logic ---
    
    document.addEventListener('DOMContentLoaded', function() {
        fetchWishlistCount();
    });

    async function fetchWishlist() {
        const skeleton = document.getElementById('wishlistSkeleton');
        const container = document.getElementById('wishlistItemsContainer');
        
        // Show skeleton, hide content
        skeleton.classList.remove('hidden');
        container.classList.add('hidden');
        
        try {
            const response = await fetch('{{ route("wishlist.data") }}');
            const data = await response.json();
            
            // Hide skeleton, show content
            skeleton.classList.add('hidden');
            container.classList.remove('hidden');
            
            if(data.success) {
                updateWishlistDrawerUI(data);
            }
        } catch(e) {
            console.error('Failed to load wishlist', e);
            // Hide skeleton on error too
            skeleton.classList.add('hidden');
            container.classList.remove('hidden');
        }
    }

    async function fetchWishlistCount() {
        try {
            const response = await fetch('{{ route("wishlist.data") }}');
            const data = await response.json();
            if(data.success) {
                document.getElementById('wishlistCount').innerText = data.count || 0;
            }
        } catch(e) {
            console.error('Failed to load wishlist count', e);
        }
    }

    function updateWishlistDrawerUI(data) {
        // Update Count
        document.getElementById('wishlistCount').innerText = data.count || 0;

        const container = document.getElementById('wishlistItemsContainer');
        const skeleton = document.getElementById('wishlistSkeleton');
        
        // Hide skeleton, show container
        skeleton.classList.add('hidden');
        container.classList.remove('hidden');
        container.innerHTML = '';

        if (!data.items || data.items.length === 0) {
            container.innerHTML = '<div class="text-center py-10 text-gray-500">Your wishlist is empty</div>';
        } else {
            // Generate HTML - Same as search canvas
            data.items.forEach(item => {
                const colorBadge = item.color_name ? `
                    <div class="flex items-center gap-1 mb-1">
                        ${item.color_hex ? `<div class="w-3 h-3 rounded-full border border-gray-300" style="background-color: ${item.color_hex}"></div>` : ''}
                        <span class="text-xs" style="color: var(--color-text-muted);">${item.color_name}</span>
                    </div>
                ` : '';
                
                const html = `
                <div class="flex gap-4 group items-start pb-4 last:border-0 last:pb-0" style="border-bottom: 1px solid var(--color-border);">
                    <div class="w-24 h-32 bg-gray-100 flex-shrink-0 relative overflow-hidden rounded-sm lazy-wrapper">
                        <img src="${item.image || 'https://placehold.co/600x800'}" alt="${item.name}"
                            class="w-full h-full object-cover" loading="lazy">
                    </div>
                    <div class="flex-1 flex flex-col h-full justify-between min-w-0">
                        <div>
                            <div class="flex justify-between items-start gap-2">
                                <h4 class="text-[15px] font-normal leading-tight truncate flex-1" style="color: var(--color-text-primary);">
                                    <a href="/product/${item.slug}">${item.name}</a>
                                </h4>
                                <button onclick="removeWishlistItem(${item.id})" class="text-gray-400 hover:text-red-600 transition-colors flex-shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                            ${colorBadge}
                            <div class="mt-1">
                                ${item.original_price ? `<span class="text-xs text-red-500 line-through">Rs. ${Number(item.original_price).toLocaleString()}</span>` : ''}
                                <span class="text-[15px] font-bold ${item.original_price ? 'ml-2' : ''}" style="color: var(--color-text-primary);">
                                    Rs. ${Number(item.price).toLocaleString()}
                                </span>
                            </div>
                        </div>
                        <button onclick="openQuickViewFromWishlist(event, ${item.product_id})" 
                            class="w-full py-2 mt-3 text-center text-xs uppercase tracking-wider font-medium transition-colors rounded-[2px] block" style="border: 1px solid var(--color-primary); color: var(--color-primary);">
                            Quick view
                        </button>
                    </div>
                </div>`;
                container.innerHTML += html;
            });
        }
        
        // Render Best Sellers section
        if (data.recommended && data.recommended.length > 0) {
            renderBestSellers(data.recommended);
        }
    }
    
    // Render Best Sellers Section
    function renderBestSellers(products) {
        const section = document.getElementById('bestSellersSection');
        const list = document.getElementById('bestSellersList');
        
        if (!products || products.length === 0) {
            section.classList.add('hidden');
            return;
        }
        
        section.classList.remove('hidden');
        list.innerHTML = '';
        
        products.forEach(product => {
            const html = `
            <div class="flex gap-4 items-start pb-4 last:border-0 last:pb-0" style="border-bottom: 1px solid var(--color-border);">
                <div class="w-24 h-32 bg-gray-100 flex-shrink-0 overflow-hidden rounded-sm lazy-wrapper">
                    <a href="/product/${product.slug}">
                        <img src="${product.image || 'https://placehold.co/600x800'}" alt="${product.name}"
                            class="w-full h-full object-cover hover:scale-105 transition-transform duration-300" loading="lazy">
                    </a>
                </div>
                <div class="flex-1 flex flex-col h-full justify-between min-w-0">
                    <div>
                        <h4 class="text-[15px] font-normal leading-tight truncate" style="color: var(--color-text-primary);">
                            <a href="/product/${product.slug}">${product.name}</a>
                        </h4>
                        <div class="mt-1">
                            ${product.original_price ? `<span class="text-xs text-red-500 line-through">Rs. ${Number(product.original_price).toLocaleString()}</span>` : ''}
                            <span class="text-[15px] font-bold ${product.original_price ? 'ml-2' : ''}" style="color: var(--color-text-primary);">
                                Rs. ${Number(product.price).toLocaleString()}
                            </span>
                        </div>
                    </div>
                    <button onclick="openQuickViewFromWishlist(event, ${product.id})" 
                        class="w-full py-2 mt-3 text-center text-xs uppercase tracking-wider font-medium transition-colors rounded-[2px]" style="border: 1px solid var(--color-primary); color: var(--color-primary);">
                        Quick view
                    </button>
                </div>
            </div>`;
            list.innerHTML += html;
        });
    }
    
    // Toggle Best Sellers collapse/expand
    function toggleBestSellers() {
        const list = document.getElementById('bestSellersList');
        const arrow = document.getElementById('bestSellersArrow');
        
        if (list.classList.contains('hidden')) {
            list.classList.remove('hidden');
            arrow.classList.remove('rotate-180');
        } else {
            list.classList.add('hidden');
            arrow.classList.add('rotate-180');
        }
    }
    
    // Add best seller product to cart
    async function addBestSellerToCart(productId) {
        try {
            const response = await fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ 
                    product_id: productId,
                    quantity: 1
                })
            });
            const data = await response.json();
            
            if(data.success) {
                showToast('Added to cart!', 'success');
                // Update cart count if function exists
                if (typeof updateCartCount === 'function') {
                    updateCartCount();
                }
            } else {
                showToast(data.message || 'Failed to add to cart', 'error');
            }
        } catch(e) {
            console.error(e);
            showToast('Failed to add to cart', 'error');
        }
    }

    // Open quick view and close wishlist drawer
    window.openQuickViewFromWishlist = function(event, productId) {
        // Close wishlist drawer first
        toggleWishlistDrawer();
        
        // Small delay to allow drawer to close, then open quick view
        setTimeout(() => {
            if (typeof window.openQuickView === 'function') {
                window.openQuickView(event, productId);
            }
        }, 300);
    };

    async function removeWishlistItem(wishlistId) {
        if(!confirm('Remove from wishlist?')) return;
        try {
            const response = await fetch('{{ route("wishlist.remove") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ wishlist_id: wishlistId })
            });
            const data = await response.json();
            if(data.success) {
                fetchWishlist();
                fetchWishlistCount();
            }
        } catch(e) {
            console.error(e);
        }
    }

    // Global function to add to wishlist (can be called from product pages)
    async function addToWishlist(productId, colorId = null) {
        // If colorId not provided, try to get from window.selectedColorId
        if (!colorId && typeof window.selectedColorId !== 'undefined') {
            colorId = window.selectedColorId;
        }
        
        const heartBtn = document.querySelector(`[data-product-id="${productId}"]`);
        const originalHTML = heartBtn ? heartBtn.innerHTML : '';
        
        // Show spinner
        if(heartBtn) {
            heartBtn.innerHTML = `
                <svg class="w-5 h-5 md:w-6 md:h-6 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            `;
            heartBtn.disabled = true;
        }
        
        try {
            const response = await fetch('{{ route("wishlist.add") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ 
                    product_id: productId,
                    color_id: colorId
                })
            });
            const data = await response.json();
            
            // Restore button
            if(heartBtn) {
                heartBtn.innerHTML = originalHTML;
                heartBtn.disabled = false;
            }
            
            if(data.success) {
                // Show success message
                showToast(data.message, 'success');
                fetchWishlistCount();
                // Update heart icon if exists
                updateHeartIcon(productId, true);
                
                // Auto-open wishlist drawer (instant)
                setTimeout(() => {
                    toggleWishlistDrawer();
                }, 100);
            } else {
                // Even if already in wishlist, open drawer to show it (no toast)
                if(data.message && data.message.includes('Already')) {
                    setTimeout(() => {
                        toggleWishlistDrawer();
                    }, 100);
                } else {
                    showToast(data.message, 'error');
                }
            }
        } catch(e) {
            // Restore button on error
            if(heartBtn) {
                heartBtn.innerHTML = originalHTML;
                heartBtn.disabled = false;
            }
            console.error(e);
            showToast('Failed to add to wishlist', 'error');
        }
    }

    function updateHeartIcon(productId, inWishlist) {
        const heartBtn = document.querySelector(`[data-product-id="${productId}"]`);
        if(heartBtn) {
            if(inWishlist) {
                heartBtn.classList.add('text-red-500');
                heartBtn.classList.remove('text-gray-400');
            } else {
                heartBtn.classList.remove('text-red-500');
                heartBtn.classList.add('text-gray-400');
            }
        }
    }

    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 z-[9999] transform transition-all duration-300 ease-in-out px-6 py-4 rounded-lg shadow-lg flex items-center gap-3 text-white`;
        toast.style.backgroundColor = type === 'success' ? 'var(--color-btn-primary)' : 'var(--color-primary)';
        
        toast.innerHTML = `
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                ${type === 'success' 
                    ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>'
                    : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>'
                }
            </svg>
            <span>${message}</span>
        `;
        
        document.body.appendChild(toast);
        
        setTimeout(() => toast.classList.add('translate-x-0'), 10);
        setTimeout(() => {
            toast.classList.add('opacity-0', 'translate-x-full');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
</script>


@include('website.includes.quick_view_modal')
