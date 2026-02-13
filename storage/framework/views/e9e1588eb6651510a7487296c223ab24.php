<!-- Cart Drawer Overlay -->
<div id="cartDrawerOverlay" onclick="toggleCartDrawer()"
    class="fixed inset-0 bg-black/50 z-[1050] hidden transition-opacity duration-300 opacity-0"></div>

<!-- Cart Drawer -->
<div id="cartDrawer"
    class="fixed top-0 right-0 h-full w-full sm:w-[420px] z-[1060] transform translate-x-full transition-transform duration-300 shadow-2xl flex flex-col font-sans-premium" style="background-color: var(--color-bg-primary);">

    <!-- Drawer Header -->
    <div class="px-5 py-4 flex items-center justify-between" style="background-color: var(--color-bg-primary); border-bottom: 1px solid var(--color-border);">
        <h2 class="font-serif-elegant text-xl tracking-wide" style="color: var(--color-text-primary);">Cart (<span id="cartCount">0</span>)</h2>
        <button onclick="toggleCartDrawer()"
            class="text-gray-500 transition-colors focus:outline-none" style="color: var(--color-text-muted);">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Free Shipping Banner -->
    <div class="px-5 py-3">
        <div class="text-white text-center text-sm font-medium py-2 rounded-sm shadow-sm" style="background-color: var(--color-primary);">
            FREE Shipping Across India
        </div>
    </div>
    
    <!-- Stock Error Message (Hidden by default) -->
    <div id="cartDrawerStockError" class="hidden mx-5 mb-3 p-3 bg-red-50 border border-red-300 rounded-lg">
        <div class="flex items-start gap-2">
            <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="flex-1">
                <p class="text-xs font-semibold text-red-800">Stock Limit</p>
                <p id="cartDrawerStockErrorText" class="text-xs text-red-700 mt-0.5"></p>
            </div>
            <button onclick="hideCartDrawerStockError()" class="text-red-600 hover:text-red-800">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Drawer Body -->
    <div class="flex-1 overflow-y-auto px-5 scrollbar-hide">
        <!-- Cart Items -->
        <div id="cartItemsContainer" class="space-y-6 pb-6" style="border-bottom: 1px solid var(--color-border);">
            <!-- Items will be injected here via JS -->
            <div class="text-center py-10" style="color: var(--color-text-muted);">Your cart is empty</div>
        </div>

        <!-- Cart Skeleton (Hidden by default) -->
        <div id="cart-skeleton" class="space-y-6 pb-6 hidden animate-pulse" style="border-bottom: 1px solid var(--color-border);">
            <!-- Skeleton Item x 3 -->
            <div class="flex gap-4">
                <div class="h-28 w-24 bg-gray-200 rounded"></div>
                <div class="flex-1 flex flex-col justify-between py-1">
                    <div class="space-y-2">
                        <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                        <div class="h-3 bg-gray-200 rounded w-1/4"></div>
                    </div>
                    <div class="flex justify-between items-end">
                        <div class="h-8 w-20 bg-gray-200 rounded"></div>
                        <div class="h-4 w-16 bg-gray-200 rounded"></div>
                    </div>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="h-28 w-24 bg-gray-200 rounded"></div>
                <div class="flex-1 flex flex-col justify-between py-1">
                    <div class="space-y-2">
                        <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                        <div class="h-3 bg-gray-200 rounded w-1/4"></div>
                    </div>
                    <div class="flex justify-between items-end">
                        <div class="h-8 w-20 bg-gray-200 rounded"></div>
                        <div class="h-4 w-16 bg-gray-200 rounded"></div>
                    </div>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="h-28 w-24 bg-gray-200 rounded"></div>
                <div class="flex-1 flex flex-col justify-between py-1">
                    <div class="space-y-2">
                        <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                        <div class="h-3 bg-gray-200 rounded w-1/4"></div>
                    </div>
                    <div class="flex justify-between items-end">
                        <div class="h-8 w-20 bg-gray-200 rounded"></div>
                        <div class="h-4 w-16 bg-gray-200 rounded"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Drawer Footer -->
    <div class="p-5" style="border-top: 1px solid var(--color-border); background-color: var(--color-bg-primary);">
        <div class="flex justify-between items-center mb-4">
            <span class="text-lg" style="color: var(--color-text-muted);">Subtotal:</span>
            <span class="font-bold font-serif-elegant text-xl tracking-wide flex items-center gap-2" style="color: var(--color-text-primary);">
                Rs. <span id="cartSubtotal">0.00</span>
                <span id="cartSubtotalSkeleton" class="h-6 w-24 bg-gray-200 rounded animate-pulse hidden"></span>
            </span>
        </div>
        <div class="space-y-2">
            <a href="<?php echo e(route('cart')); ?>" id="viewCartBtn"
                class="w-full py-3 text-center font-medium text-base rounded-[4px] transition-all border-2 block disabled:opacity-50 disabled:cursor-not-allowed" style="border-color: var(--color-btn-primary); color: var(--color-btn-primary);">
                View Cart
            </a>
            <a href="<?php echo e(route('checkout')); ?>" id="checkoutBtn"
                class="w-full py-3.5 text-white font-medium text-lg rounded-[4px] transition-all shadow-md flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed" style="background-color: var(--color-btn-primary);">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                Checkout
            </a>
        </div>
    </div>
</div>

<script>
    function toggleCartDrawer() {
        const drawer = document.getElementById('cartDrawer');
        const overlay = document.getElementById('cartDrawerOverlay');
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

            // Trigger skeleton/fetch
            if (typeof fetchCart === 'function') fetchCart();
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

    // --- Dynamic Cart Logic ---

    document.addEventListener('DOMContentLoaded', function () {
        fetchCart();
    });

    async function fetchCart() {
        const skeleton = document.getElementById('cart-skeleton');
        const container = document.getElementById('cartItemsContainer');

        // Show Skeleton
        if (skeleton) skeleton.classList.remove('hidden');
        if (container) container.classList.add('hidden');

        try {
            const response = await fetch('<?php echo e(route("cart.data")); ?>');
            const data = await response.json();

            // Hide Skeleton
            if (skeleton) skeleton.classList.add('hidden');
            if (container) container.classList.remove('hidden');

            if (data.success) {
                updateCartDrawerUI(data);
            }
        } catch (e) {
            console.error('Failed to load cart', e);
            // Hide Skeleton on error too
            if (skeleton) skeleton.classList.add('hidden');
            if (container) container.classList.remove('hidden');
        }
    }

    function updateCartDrawerUI(data) {
        // Update Count
        document.getElementById('cartCount').innerText = data.count || 0;
        document.getElementById('cartSubtotal').innerText = data.totals.subtotal_formatted;

        const container = document.getElementById('cartItemsContainer');
        const checkoutBtn = document.getElementById('checkoutBtn');
        const viewCartBtn = document.getElementById('viewCartBtn');
        container.innerHTML = '';

        if (!data.cart_items || data.cart_items.length === 0) {
            container.innerHTML = '<div class="text-center py-10 text-gray-500">Your cart is empty</div>';
            // Disable buttons
            if (checkoutBtn) {
                checkoutBtn.style.opacity = '0.5';
                checkoutBtn.style.cursor = 'not-allowed';
                checkoutBtn.style.pointerEvents = 'none';
            }
            if (viewCartBtn) {
                viewCartBtn.style.opacity = '0.5';
                viewCartBtn.style.cursor = 'not-allowed';
                viewCartBtn.style.pointerEvents = 'none';
            }
            return;
        }

        // Enable buttons
        if (checkoutBtn) {
            checkoutBtn.style.opacity = '1';
            checkoutBtn.style.cursor = 'pointer';
            checkoutBtn.style.pointerEvents = 'auto';
        }
        if (viewCartBtn) {
            viewCartBtn.style.opacity = '1';
            viewCartBtn.style.cursor = 'pointer';
            viewCartBtn.style.pointerEvents = 'auto';
        }

        // Generate HTML using cart_items which now includes 'key'
        data.cart_items.forEach(item => {
            const key = item.key;

            const html = `
            <div class="flex gap-4 group">
                <div class="h-28 w-24 flex-shrink-0 overflow-hidden rounded transform transition hover:scale-105 duration-300 border border-gray-200 lazy-wrapper">
                    <img src="${item.image || 'https://via.placeholder.com/100'}" alt="${item.name}" class="h-full w-full object-cover" loading="lazy">
                </div>

                <div class="flex-1 flex flex-col justify-between py-1">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-sm font-medium pr-2 leading-snug line-clamp-2" style="color: var(--color-text-primary);">${item.name}</h3>
                            ${item.color_name || item.size_abbr || item.size_name ? `<p class="text-xs mt-1" style="color: var(--color-text-muted);">
                                ${item.color_name ? `Color: ${item.color_name}` : ''}
                                ${item.color_name && (item.size_abbr || item.size_name) ? ' | ' : ''}
                                ${item.size_abbr ? `Size: ${item.size_abbr}` : (item.size_name ? `Size: ${item.size_name}` : '')}
                            </p>` : ''}
                        </div>
                        <button onclick="removeCartItem('${key}')" class="text-gray-400 hover:text-red-600 transition-colors p-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                    
                    <div class="flex items-end justify-between mt-2">
                        <!-- Qty -->
                        <div class="flex items-center rounded bg-transparent h-8" style="border: 1px solid var(--color-border);">
                            <button onclick="updateCartItemQty('${key}', ${item.quantity - 1})" class="px-2 hover:bg-black/5 transition h-full flex items-center justify-center disabled:opacity-50" style="color: var(--color-text-muted);" ${item.quantity <= 1 ? 'disabled' : ''}>-</button>
                            <span class="px-2 text-sm font-medium min-w-[20px] text-center" style="color: var(--color-text-primary);">${item.quantity}</span>
                            <button onclick="updateCartItemQty('${key}', ${item.quantity + 1})" class="px-2 hover:bg-black/5 transition h-full flex items-center justify-center" style="color: var(--color-text-muted);">+</button>
                        </div>
                        <!-- Price -->
                        <div class="text-right">
                            ${item.original_price ? `<div class="text-[10px] text-red-500 line-through">Rs. ${new Intl.NumberFormat().format(item.original_price * item.quantity)}</div>` : ''}
                            <span class="text-sm font-medium" style="color: var(--color-text-primary);">Rs. ${new Intl.NumberFormat().format(item.price * item.quantity)}</span>
                            ${item.quantity > 1 ? `<div class="text-[10px]" style="color: var(--color-text-muted);">Rs. ${new Intl.NumberFormat().format(item.price)} each</div>` : ''}
                        </div>
                    </div>
                </div>
            </div>`;
            container.innerHTML += html;
        });
    }

    async function removeCartItem(key) {
        if (!confirm('Remove this item?')) return;
        try {
            const response = await fetch('<?php echo e(route("cart.remove")); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                },
                body: JSON.stringify({ key: key })
            });
            const data = await response.json();
            if (data.success) {
                updateCartDrawerUI(data);
                
                // Update cart count badge in header
                if (typeof window.updateCartCount === 'function') {
                    window.updateCartCount();
                }
            }
        } catch (e) {
            console.error(e);
        }
    }

    async function updateCartItemQty(key, newQty) {
        if (newQty < 1) return;

        // Show Price Skeleton
        const priceText = document.getElementById('cartSubtotal');
        const priceSkeleton = document.getElementById('cartSubtotalSkeleton');

        if (priceSkeleton) priceSkeleton.classList.remove('hidden');
        if (priceText) priceText.classList.add('hidden');

        try {
            const response = await fetch('<?php echo e(route("cart.update")); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                },
                body: JSON.stringify({ key: key, quantity: newQty })
            });
            const data = await response.json();

            // Hide Skeleton Logic is handled by updateCartDrawerUI which overwrites innerHTML or updates text
            // But we should ensure skeleton is hidden if update fails or before updateCartDrawerUI runs

            if (priceSkeleton) priceSkeleton.classList.add('hidden');
            if (priceText) priceText.classList.remove('hidden');

            if (data.success) {
                updateCartDrawerUI(data);
                
                // Update cart count badge in header
                if (typeof window.updateCartCount === 'function') {
                    window.updateCartCount();
                }
                hideCartDrawerStockError(); // Hide error on success
            } else {
                // Show inline error message
                showCartDrawerStockError(data.message || 'Failed to update quantity');
            }
        } catch (e) {
            console.error(e);
            // Fallback hide
            if (priceSkeleton) priceSkeleton.classList.add('hidden');
            if (priceText) priceText.classList.remove('hidden');
            showCartDrawerStockError('Failed to update quantity. Please try again.');
        }
    }
    
    function showCartDrawerStockError(message) {
        const errorDiv = document.getElementById('cartDrawerStockError');
        const errorText = document.getElementById('cartDrawerStockErrorText');
        
        if (errorDiv && errorText) {
            errorText.textContent = message;
            errorDiv.classList.remove('hidden');
            
            // Auto hide after 5 seconds
            setTimeout(() => {
                errorDiv.classList.add('hidden');
            }, 5000);
        }
    }
    
    function hideCartDrawerStockError() {
        const errorDiv = document.getElementById('cartDrawerStockError');
        if (errorDiv) {
            errorDiv.classList.add('hidden');
        }
    }
</script><?php /**PATH C:\xampp\htdocs\ESS_Project1\resources\views/website/includes/cart_drawer.blade.php ENDPATH**/ ?>