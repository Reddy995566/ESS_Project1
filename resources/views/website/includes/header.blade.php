<!-- ================================
         LUXURY HEADER - PREMIUM DESIGN
         ================================ -->

<header class="sticky top-0 z-50 w-full shadow-md">
    <!-- 1️⃣ TOP ANNOUNCEMENT BAR - FIXED -->
    <div class="w-full flex items-center justify-center" style="height: 40px; background-color: var(--color-bg-primary);">
        <p class="font-medium" style="font-size: 13px; font-weight: 500; color: var(--color-text-primary);">
            Welcome! Log in now—grab 10% off + free shipping across India!
        </p>
    </div>

    <!-- 2️⃣ MAIN NAVBAR BAR - ALL IN ONE LINE -->
    <nav class="w-full h-16 md:h-[70px] relative" style="background-color: var(--color-header-bg);">
        <div class="max-w-[1800px] mx-auto h-full px-4 md:px-6 lg:px-8">
            <div class="flex items-center justify-between h-full gap-4 lg:gap-6">

                <!-- ⬅ LEFT: Brand Logo -->
                <div class="flex-shrink-0">
                    <a href="{{ route('home') }}">
                        @if(!empty($siteSettings['site_logo']))
                            <img src="{{ $siteSettings['site_logo'] }}" alt="{{ $siteSettings['site_name'] ?? 'Fashion Store' }}" class="h-8 sm:h-10 md:h-12 w-auto object-contain">
                        @else
                            <h1 class="font-serif-elegant font-semibold text-lg sm:text-xl md:text-2xl tracking-[0.08em] md:tracking-[0.12em]" style="color: var(--color-header-text);">
                                {{ strtoupper($siteSettings['site_name'] ?? 'Fashion Store') }}
                            </h1>
                        @endif
                    </a>
                </div>

                <!-- ⬆ CENTER-LEFT: Search Bar - DESKTOP ONLY -->
                <div class="hidden lg:flex items-center flex-1 max-w-xl">
                    <div class="relative w-full">
                        <div class="flex items-center w-full rounded-sm overflow-hidden" style="background-color: rgba(255, 255, 255, 0.95); box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                            <input 
                                type="text" 
                                id="desktop-search-input"
                                placeholder="Search for products..."
                                class="w-full px-3 py-2 text-sm outline-none border-none"
                                style="color: #000; background: transparent;"
                                autocomplete="off"
                            >
                            <button 
                                type="button"
                                id="desktop-search-btn"
                                class="px-4 py-2 hover:opacity-90 transition-opacity"
                                style="background-color: var(--color-accent-gold); color: #fff;"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </button>
                        </div>
                        
                        <!-- Search Suggestions Dropdown -->
                        <div 
                            id="desktop-search-suggestions" 
                            class="absolute top-full left-0 right-0 mt-1 rounded-sm shadow-lg hidden overflow-hidden z-50"
                            style="background-color: #fff; max-height: 400px; overflow-y: auto;"
                        >
                            <!-- Suggestions will be populated here -->
                        </div>
                    </div>
                </div>

                <!-- ⬆ CENTER-RIGHT: Navigation Links - DESKTOP ONLY -->
                <div class="hidden lg:flex items-center gap-5">
                    <a href="{{ route('home') }}"
                        class="font-medium hover:opacity-80 transition-opacity duration-200 text-sm whitespace-nowrap" style="color: var(--color-header-text);">
                        Home
                    </a>

                    <!-- Shop Dropdown -->
                    <div class="relative shop-dropdown-container" style="z-index: 50;">
                        <button type="button"
                            class="font-medium flex items-center hover:opacity-80 transition-opacity duration-200 text-sm gap-1 whitespace-nowrap" style="color: var(--color-header-text);">
                            <span>Shop</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7">
                                </path>
                            </svg>
                        </button>
                    </div>

                    <a href="{{ route('bulk-orders') }}"
                        class="font-medium hover:opacity-80 transition-opacity duration-200 text-sm whitespace-nowrap" style="color: var(--color-header-text);">
                        Bulk Orders
                    </a>
                    <a href="{{ route('about') }}"
                        class="font-medium hover:opacity-80 transition-opacity duration-200 text-sm whitespace-nowrap" style="color: var(--color-header-text);">
                        About
                    </a>
                    <a href="{{ route('contact') }}"
                        class="font-medium hover:opacity-80 transition-opacity duration-200 text-sm whitespace-nowrap" style="color: var(--color-header-text);">
                        Contact
                    </a>
                </div>

                <!-- ➡ RIGHT: Action Icons -->
                <div class="flex items-center justify-end gap-3 md:gap-4 flex-shrink-0">
                    <!-- Become a Seller Dropdown -->
                    <div class="relative hidden md:block" x-data="{ open: false }" style="z-index: 9999;">
                        <button @click="open = !open" @click.away="open = false"
                            class="flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-lg transition-all duration-200"
                            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <span>Become a Seller</span>
                            <svg class="w-4 h-4 transition-transform" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="open" 
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-2xl border border-gray-200 overflow-hidden"
                            style="z-index: 99999;">
                            <div class="py-2">
                                <a href="{{ route('seller.register') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                    </svg>
                                    <span>Register as Seller</span>
                                </a>
                                <a href="{{ route('seller.login') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                    </svg>
                                    <span>Seller Login</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    @auth
                        <!-- Logged In - Go to Dashboard -->
                        <a href="{{ route('user.dashboard') }}"
                            class="hover:opacity-80 transition-opacity duration-200 hidden md:block" style="color: var(--color-header-text);">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </a>
                    @else
                        <!-- Not Logged In -->
                        <a href="{{ route('login') }}"
                            class="hover:opacity-80 transition-opacity duration-200 hidden md:block" style="color: var(--color-header-text);">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </a>
                    @endauth
                    <a href="#" id="search-btn" class="hover:opacity-80 transition-opacity duration-200 lg:hidden" style="color: var(--color-header-text);">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </a>
                    <button onclick="toggleWishlistDrawer()"
                        class="hover:opacity-80 transition-opacity duration-200 relative" style="color: var(--color-header-text);">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                            </path>
                        </svg>
                    </button>
                    <button onclick="toggleCartDrawer()"
                        class="hover:opacity-80 transition-opacity duration-200 relative" style="color: var(--color-header-text);">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </button>
                    <!-- Mobile Menu Button -->
                    <button class="lg:hidden" id="mobile-menu-btn" onclick="toggleMobileMenu()" style="color: var(--color-header-text);">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>

            </div>
        </div>

        <!-- Mega Menu Dropdown (Outside container but inside nav) -->
        <div class="shop-mega-menu absolute left-0 right-0 opacity-0 invisible transition-all duration-300 pointer-events-none"
            style="top: 100%; z-index: 9999;">
            <div class="shadow-2xl mt-0 overflow-hidden" style="background-color: var(--color-header-bg); border-top: 2px solid var(--color-accent-gold);">
                <div class="max-w-7xl mx-auto px-4 md:px-6 lg:px-8">
                    <div class="grid gap-8 py-8"
                        style="grid-template-columns: repeat({{ $megaMenuCategories->count() + ($megaMenuFabrics->isNotEmpty() ? 1 : 0) }}, minmax(0, 1fr));">
                        @forelse($megaMenuCategories as $category)
                            <!-- {{ $category->name }} -->
                            <div>
                                <h3 class="font-semibold text-sm mb-4 tracking-wider uppercase" style="color: var(--color-accent-gold);">
                                    {{ $category->name }}
                                </h3>
                                <ul class="space-y-2.5">
                                    @foreach($category->children->take(6) as $subcategory)
                                        <li>
                                            <a href="{{ route('shop', ['category' => $subcategory->slug]) }}"
                                                class="text-sm hover:opacity-80 transition-colors duration-200" style="color: var(--color-header-text);">
                                                {{ $subcategory->name }}
                                            </a>
                                        </li>
                                    @endforeach
                                    <li>
                                        <a href="{{ route('shop', ['category' => $category->slug]) }}"
                                            class="text-sm hover:opacity-80 transition-colors duration-200 font-medium" style="color: var(--color-header-text);">
                                            View All
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        @empty
                            @if($megaMenuFabrics->isEmpty())
                                <!-- Fallback if no categories and no fabrics -->
                                <div class="col-span-3 text-center py-4">
                                    <p class="text-sm" style="color: var(--color-header-text);">No categories available</p>
                                </div>
                            @endif
                        @endforelse

                        <!-- SHOP BY FABRIC -->
                        @if($megaMenuFabrics->isNotEmpty())
                            <div>
                                <h3 class="font-semibold text-sm mb-4 tracking-wider uppercase" style="color: var(--color-accent-gold);">SHOP BY
                                    FABRIC</h3>
                                <ul class="space-y-2.5">
                                    @foreach($megaMenuFabrics->take(6) as $fabric)
                                        <li>
                                            <a href="{{ route('shop', ['fabric' => $fabric->slug]) }}"
                                                class="text-sm hover:opacity-80 transition-colors duration-200" style="color: var(--color-header-text);">
                                                {{ $fabric->name }}
                                            </a>
                                        </li>
                                    @endforeach
                                    <li>
                                        <a href="{{ route('shop') }}"
                                            class="text-sm hover:opacity-80 transition-colors duration-200 font-medium" style="color: var(--color-header-text);">
                                            View All
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>

<!-- Mobile Menu -->
<div id="mobile-menu" class="hidden lg:hidden fixed top-[110px] left-0 right-0 z-40 shadow-lg" style="background-color: var(--color-header-bg); border-top: 1px solid rgba(0,0,0,0.1);">
    <div class="px-4 py-4 space-y-1 max-h-[calc(100vh-110px)] overflow-y-auto">
        <a href="{{ route('home') }}" class="block font-medium text-sm py-3" style="color: var(--color-header-text); border-bottom: 1px solid rgba(0,0,0,0.1);">Home</a>
        <a href="{{ route('shop') }}" class="block font-medium text-sm py-3" style="color: var(--color-header-text); border-bottom: 1px solid rgba(0,0,0,0.1);">Shop</a>
        <a href="{{ route('bulk-orders') }}" class="block font-medium text-sm py-3" style="color: var(--color-header-text); border-bottom: 1px solid rgba(0,0,0,0.1);">Bulk Orders</a>
        <a href="{{ route('about') }}" class="block font-medium text-sm py-3" style="color: var(--color-header-text); border-bottom: 1px solid rgba(0,0,0,0.1);">About Us</a>
        <a href="{{ route('contact') }}" class="block font-medium text-sm py-3" style="color: var(--color-header-text); border-bottom: 1px solid rgba(0,0,0,0.1);">Contact Us</a>
        
        <!-- Seller Links -->
        <div class="py-3" style="border-bottom: 1px solid rgba(0,0,0,0.1);">
            <p class="font-bold text-sm mb-2" style="color: var(--color-accent-gold);">🏪 Become a Seller</p>
            <a href="{{ route('seller.register') }}" class="block text-sm py-2 pl-4" style="color: var(--color-header-text);">→ Register as Seller</a>
            <a href="{{ route('seller.login') }}" class="block text-sm py-2 pl-4" style="color: var(--color-header-text);">→ Seller Login</a>
        </div>
        
        @auth
        <a href="{{ route('user.dashboard') }}" class="block font-medium text-sm py-3" style="color: var(--color-header-text); border-bottom: 1px solid rgba(0,0,0,0.1);">My Account</a>
        @else
        <a href="{{ route('login') }}" class="block font-medium text-sm py-3" style="color: var(--color-header-text); border-bottom: 1px solid rgba(0,0,0,0.1);">Login / Register</a>
        @endauth
    </div>
</div>

<!-- Mobile Menu Overlay -->
<div id="mobile-menu-overlay" class="hidden fixed inset-0 bg-black/50 z-30 lg:hidden" onclick="toggleMobileMenu()"></div>

<script>
    // Mobile Menu Toggle
    function toggleMobileMenu() {
        const mobileMenu = document.getElementById('mobile-menu');
        const overlay = document.getElementById('mobile-menu-overlay');
        const menuBtn = document.getElementById('mobile-menu-btn');
        
        if (mobileMenu.classList.contains('hidden')) {
            // Show menu
            mobileMenu.classList.remove('hidden');
            overlay.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            // Change icon to X
            menuBtn.innerHTML = `<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>`;
        } else {
            // Hide menu
            mobileMenu.classList.add('hidden');
            overlay.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            // Change icon back to hamburger
            menuBtn.innerHTML = `<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>`;
        }
    }

    // Shop Mega Menu Toggle (Click-based)
    document.addEventListener('DOMContentLoaded', function () {
        const shopDropdownContainer = document.querySelector('.shop-dropdown-container');
        const shopButton = shopDropdownContainer?.querySelector('button');
        const megaMenu = document.querySelector('.shop-mega-menu');

        if (!shopButton || !megaMenu) return;

        // Toggle mega menu on button click
        shopButton.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            const isVisible = megaMenu.classList.contains('mega-menu-visible');

            if (isVisible) {
                // Hide menu
                megaMenu.classList.remove('mega-menu-visible');
                megaMenu.classList.add('opacity-0', 'invisible', 'pointer-events-none');
            } else {
                // Show menu
                megaMenu.classList.add('mega-menu-visible');
                megaMenu.classList.remove('opacity-0', 'invisible', 'pointer-events-none');
            }
        });

        // Close mega menu when clicking outside
        document.addEventListener('click', function (e) {
            if (!shopDropdownContainer.contains(e.target) && !megaMenu.contains(e.target)) {
                megaMenu.classList.remove('mega-menu-visible');
                megaMenu.classList.add('opacity-0', 'invisible', 'pointer-events-none');
            }
        });

        // Prevent mega menu from closing when clicking inside it
        megaMenu.addEventListener('click', function (e) {
            e.stopPropagation();
        });

        // User Dropdown Toggle
        const userDropdownContainer = document.querySelector('.user-dropdown-container');
        const userButton = userDropdownContainer?.querySelector('button');
        const userDropdown = document.querySelector('.user-dropdown-menu');

        if (userButton && userDropdown) {
            userButton.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();

                const isVisible = userDropdown.classList.contains('user-menu-visible');

                if (isVisible) {
                    userDropdown.classList.remove('user-menu-visible');
                    userDropdown.classList.add('opacity-0', 'invisible', 'pointer-events-none');
                } else {
                    userDropdown.classList.add('user-menu-visible');
                    userDropdown.classList.remove('opacity-0', 'invisible', 'pointer-events-none');
                }
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function (e) {
                if (!userDropdownContainer.contains(e.target) && !userDropdown.contains(e.target)) {
                    userDropdown.classList.remove('user-menu-visible');
                    userDropdown.classList.add('opacity-0', 'invisible', 'pointer-events-none');
                }
            });
        }

        // ========================================
        // DESKTOP SEARCH FUNCTIONALITY
        // ========================================
        const searchInput = document.getElementById('desktop-search-input');
        const searchBtn = document.getElementById('desktop-search-btn');
        const suggestionsBox = document.getElementById('desktop-search-suggestions');
        
        let searchTimeout;

        // Search on button click
        if (searchBtn) {
            searchBtn.addEventListener('click', function() {
                performSearch();
            });
        }

        // Search on Enter key
        if (searchInput) {
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    performSearch();
                }
            });

            // Live search suggestions
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                const query = this.value.trim();

                if (query.length < 2) {
                    hideSuggestions();
                    return;
                }

                searchTimeout = setTimeout(() => {
                    fetchSuggestions(query);
                }, 300);
            });

            // Close suggestions when clicking outside
            document.addEventListener('click', function(e) {
                if (!searchInput.contains(e.target) && !suggestionsBox.contains(e.target)) {
                    hideSuggestions();
                }
            });
        }

        function performSearch() {
            const query = searchInput.value.trim();
            if (query.length > 0) {
                window.location.href = '{{ route("search") }}?q=' + encodeURIComponent(query);
            }
        }

        function fetchSuggestions(query) {
            fetch('{{ route("search") }}?q=' + encodeURIComponent(query) + '&ajax=1', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.products && data.products.length > 0) {
                    displaySuggestions(data.products, query);
                } else {
                    showNoResults(query);
                }
            })
            .catch(error => {
                console.error('Search error:', error);
                hideSuggestions();
            });
        }

        function displaySuggestions(products, query) {
            let html = '<div class="py-2">';
            
            // Show up to 8 suggestions
            products.slice(0, 8).forEach(product => {
                html += `
                    <a href="${product.url}" class="flex items-center gap-3 px-4 py-2.5 hover:bg-gray-50 transition-colors">
                        <div class="w-12 h-12 flex-shrink-0 rounded overflow-hidden bg-gray-100">
                            ${product.image ? `<img src="${product.image}" alt="${product.name}" class="w-full h-full object-cover">` : '<div class="w-full h-full flex items-center justify-center text-gray-400"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div>'}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">${highlightQuery(product.name, query)}</p>
                            <p class="text-xs text-gray-500 mt-0.5">₹${product.price}</p>
                        </div>
                    </a>
                `;
            });

            if (products.length > 8) {
                html += `
                    <div class="px-4 py-2 text-center border-t">
                        <a href="{{ route('search') }}?q=${encodeURIComponent(query)}" class="text-sm font-medium hover:underline" style="color: var(--color-accent-gold);">
                            View all ${products.length} results
                        </a>
                    </div>
                `;
            }

            html += '</div>';
            suggestionsBox.innerHTML = html;
            showSuggestions();
        }

        function showNoResults(query) {
            suggestionsBox.innerHTML = `
                <div class="px-4 py-6 text-center">
                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <p class="text-sm text-gray-600">No products found for "${query}"</p>
                    <p class="text-xs text-gray-400 mt-1">Try different keywords</p>
                </div>
            `;
            showSuggestions();
        }

        function highlightQuery(text, query) {
            const regex = new RegExp(`(${query})`, 'gi');
            return text.replace(regex, '<strong>$1</strong>');
        }

        function showSuggestions() {
            suggestionsBox.classList.remove('hidden');
        }

        function hideSuggestions() {
            suggestionsBox.classList.add('hidden');
        }
    });
</script>
