<!-- ================================
         LUXURY HEADER - PREMIUM DESIGN
         ================================ -->

<header class="sticky top-0 z-50 w-full shadow-md">
    <!-- 1️⃣ TOP ANNOUNCEMENT BAR - FIXED -->
    <div class="w-full flex items-center justify-center" style="height: 40px; background-color: var(--color-bg-primary);">
        <p class="font-medium" style="font-size: 13px; font-weight: 500; color: var(--color-text-primary);">
            Free Shipping Across India
        </p>
    </div>

    <!-- 2️⃣ MAIN NAVBAR BAR - RESPONSIVE -->
    <nav class="w-full h-16 md:h-[64px] relative" style="background-color: var(--color-header-bg);">
        <div class="max-w-[1800px] mx-auto h-full px-4 md:px-6 lg:px-12">
            <div class="flex items-center justify-between h-full">

                <!-- ⬅ LEFT: Brand Logo -->
                <div class="flex-shrink-0 lg:w-1/4">
                    <a href="{{ route('home') }}">
                        @if(!empty($siteSettings['site_logo']))
                            <img src="{{ $siteSettings['site_logo'] }}" alt="{{ $siteSettings['site_name'] ?? 'Fashion Store' }}" class="h-8 sm:h-10 md:h-12 lg:h-14 w-auto object-contain">
                        @else
                            <h1 class="font-serif-elegant font-semibold text-lg sm:text-xl md:text-2xl lg:text-3xl tracking-[0.08em] md:tracking-[0.12em]" style="color: var(--color-header-text);">
                                {{ strtoupper($siteSettings['site_name'] ?? 'FASHION STORE') }}
                            </h1>
                        @endif
                    </a>
                </div>

                <!-- ⬆ CENTER: Main Navigation - DESKTOP ONLY -->
                <div class="hidden lg:flex items-center justify-center gap-7 flex-1">
                    <a href="{{ route('home') }}"
                        class="font-medium border rounded-full hover:bg-white hover:bg-opacity-10 transition-all duration-200 text-sm px-4 py-1.5" style="color: var(--color-header-text); border-color: var(--color-header-text);">
                        Home
                    </a>

                    <!-- Shop Dropdown -->
                    <div class="relative shop-dropdown-container" style="z-index: 50;">
                        <button type="button"
                            class="font-medium flex items-center hover:opacity-80 transition-opacity duration-200 text-sm gap-1" style="color: var(--color-header-text);">
                            <span>Shop</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7">
                                </path>
                            </svg>
                        </button>
                    </div>

                    <a href="{{ route('bulk-orders') }}"
                        class="font-medium hover:opacity-80 transition-opacity duration-200 text-sm" style="color: var(--color-header-text);">
                        Bulk Orders
                    </a>
                    <a href="{{ route('about') }}"
                        class="font-medium hover:opacity-80 transition-opacity duration-200 text-sm" style="color: var(--color-header-text);">
                        About Us
                    </a>
                    <a href="{{ route('contact') }}"
                        class="font-medium hover:opacity-80 transition-opacity duration-200 text-sm" style="color: var(--color-header-text);">
                        Contact Us
                    </a>
                </div>

                <!-- ➡ RIGHT: Action Icons -->
                <div class="flex items-center justify-end gap-4 md:gap-5 lg:w-1/4">
                    <!-- Install App Button (Desktop) -->
                    <button id="installAppBtn" class="hidden hover:opacity-80 transition-opacity duration-200" style="color: var(--color-header-text);">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                    </button>
                    
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
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </a>
                    @endauth
                    <a href="#" id="search-btn" class="hover:opacity-80 transition-opacity duration-200" style="color: var(--color-header-text);">
                        <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </a>
                    <button onclick="toggleWishlistDrawer()"
                        class="hover:opacity-80 transition-opacity duration-200 relative" style="color: var(--color-header-text);">
                        <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                            </path>
                        </svg>
                    </button>
                    <button onclick="toggleCartDrawer()"
                        class="hover:opacity-80 transition-opacity duration-200 relative" style="color: var(--color-header-text);">
                        <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor"
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
<div id="mobile-menu" class="hidden lg:hidden fixed top-[104px] left-0 right-0 z-40 shadow-lg" style="background-color: var(--color-header-bg); border-top: 1px solid rgba(0,0,0,0.1);">
    <div class="px-4 py-4 space-y-1 max-h-[calc(100vh-104px)] overflow-y-auto">
        <!-- Install App Button (Mobile) -->
        <button id="installAppBtnMobile" class="hidden w-full text-left font-medium text-sm py-3 flex items-center gap-2" style="color: var(--color-header-text); border-bottom: 1px solid rgba(0,0,0,0.1);">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
            </svg>
            Install App
        </button>
        
        <!-- Store Buttons -->
        <div id="storeButtons" class="hidden py-3 space-y-2" style="border-bottom: 1px solid rgba(0,0,0,0.1);">
            <a href="#" class="flex items-center gap-2 text-sm" style="color: var(--color-header-text);">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M3,20.5V3.5C3,2.91 3.34,2.39 3.84,2.15L13.69,12L3.84,21.85C3.34,21.6 3,21.09 3,20.5M16.81,15.12L6.05,21.34L14.54,12.85L16.81,15.12M20.16,10.81C20.5,11.08 20.75,11.5 20.75,12C20.75,12.5 20.53,12.9 20.18,13.18L17.89,14.5L15.39,12L17.89,9.5L20.16,10.81M6.05,2.66L16.81,8.88L14.54,11.15L6.05,2.66Z"/>
                </svg>
                Google Play
            </a>
            <a href="#" class="flex items-center gap-2 text-sm" style="color: var(--color-header-text);">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M18.71,19.5C17.88,20.74 17,21.95 15.66,21.97C14.32,22 13.89,21.18 12.37,21.18C10.84,21.18 10.37,21.95 9.1,22C7.79,22.05 6.8,20.68 5.96,19.47C4.25,17 2.94,12.45 4.7,9.39C5.57,7.87 7.13,6.91 8.82,6.88C10.1,6.86 11.32,7.75 12.11,7.75C12.89,7.75 14.37,6.68 15.92,6.84C16.57,6.87 18.39,7.1 19.56,8.82C19.47,8.88 17.39,10.1 17.41,12.63C17.44,15.65 20.06,16.66 20.09,16.67C20.06,16.74 19.67,18.11 18.71,19.5M13,3.5C13.73,2.67 14.94,2.04 15.94,2C16.07,3.17 15.6,4.35 14.9,5.19C14.21,6.04 13.07,6.7 11.95,6.61C11.8,5.46 12.36,4.26 13,3.5Z"/>
                </svg>
                App Store
            </a>
        </div>
        
        <a href="{{ route('home') }}" class="block font-medium text-sm py-3" style="color: var(--color-header-text); border-bottom: 1px solid rgba(0,0,0,0.1);">Home</a>
        <a href="{{ route('shop') }}" class="block font-medium text-sm py-3" style="color: var(--color-header-text); border-bottom: 1px solid rgba(0,0,0,0.1);">Shop</a>
        <a href="{{ route('bulk-orders') }}" class="block font-medium text-sm py-3" style="color: var(--color-header-text); border-bottom: 1px solid rgba(0,0,0,0.1);">Bulk Orders</a>
        <a href="{{ route('about') }}" class="block font-medium text-sm py-3" style="color: var(--color-header-text); border-bottom: 1px solid rgba(0,0,0,0.1);">About Us</a>
        <a href="{{ route('contact') }}" class="block font-medium text-sm py-3" style="color: var(--color-header-text); border-bottom: 1px solid rgba(0,0,0,0.1);">Contact Us</a>
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
    });

</script>

<!-- PWA Install Script -->
<script>
    let deferredPrompt;
    const installBtn = document.getElementById('installAppBtn');
    const installBtnMobile = document.getElementById('installAppBtnMobile');
    const storeButtons = document.getElementById('storeButtons');

    // Listen for beforeinstallprompt event
    window.addEventListener('beforeinstallprompt', (e) => {
        // Prevent the mini-infobar from appearing on mobile
        e.preventDefault();
        // Stash the event so it can be triggered later
        deferredPrompt = e;
        // Show install button
        if (installBtn) installBtn.classList.remove('hidden');
        if (installBtnMobile) installBtnMobile.classList.remove('hidden');
    });

    // Install button click handler
    function installPWA() {
        if (!deferredPrompt) {
            // If PWA already installed or not available, show store buttons
            if (storeButtons) storeButtons.classList.remove('hidden');
            return;
        }
        
        // Show the install prompt
        deferredPrompt.prompt();
        
        // Wait for the user to respond to the prompt
        deferredPrompt.userChoice.then((choiceResult) => {
            if (choiceResult.outcome === 'accepted') {
                console.log('User accepted the install prompt');
            } else {
                console.log('User dismissed the install prompt');
            }
            deferredPrompt = null;
        });
    }

    // Add click listeners
    if (installBtn) {
        installBtn.addEventListener('click', installPWA);
    }
    if (installBtnMobile) {
        installBtnMobile.addEventListener('click', installPWA);
    }

    // Hide install button if app is already installed
    window.addEventListener('appinstalled', () => {
        console.log('PWA was installed');
        if (installBtn) installBtn.classList.add('hidden');
        if (installBtnMobile) installBtnMobile.classList.add('hidden');
    });

    // For iOS - always show store buttons as PWA install is different
    const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
    if (isIOS && storeButtons) {
        storeButtons.classList.remove('hidden');
    }
</script>
