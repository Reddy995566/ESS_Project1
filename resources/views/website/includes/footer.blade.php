    <!-- Footer Section - RESPONSIVE -->
    <footer class="w-full text-white relative pt-16 pb-0 overflow-hidden" style="background-color: var(--color-footer-bg); color: var(--color-footer-text);">

        <div class="relative z-10 max-w-[1800px] mx-auto px-4 md:px-8 lg:px-12">

            <!-- Newsletter Section -->
            <div class="mb-16">
                <h3 class="text-center text-xl md:text-2xl font-medium mb-3" style="color: var(--color-footer-text);">Newsletter Subscribe</h3>
                <p class="text-center text-sm mb-8 md:mb-10 max-w-2xl mx-auto" style="color: var(--color-footer-text); opacity: 0.8;">Be the first to know about
                    our latest offers and get exclusive discounts.</p>

                <div class="w-full relative rounded-md px-4 py-3 bg-transparent" style="border: 1px solid rgba(68, 18, 39, 0.3);">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3 flex-1">
                            <svg class="w-5 h-5" style="color: var(--color-footer-text); opacity: 0.7;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                </path>
                            </svg>
                            <input type="email" placeholder="Enter your email address..."
                                class="w-full bg-transparent border-none outline-none text-base" style="color: var(--color-footer-text);">
                        </div>
                        <button class="hover:opacity-80 transition-colors" style="color: var(--color-footer-text);">
                            <svg class="w-5 h-5 transform -rotate-45" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z">
                                </path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Footer Links Grid -->
            <div class="hidden md:grid grid-cols-1 md:grid-cols-4 gap-10 md:gap-16 lg:gap-20 mb-12">

                <!-- Contact Info -->
                <div class="flex flex-col items-start px-4 md:px-0">
                    <div class="mb-6">
                        @if(!empty($siteSettings['site_logo']))
                            <img src="{{ $siteSettings['site_logo'] }}" alt="{{ $siteSettings['site_name'] ?? 'Fashion Store' }}" class="h-12 w-auto object-contain mb-4" style="filter: brightness(0) invert(0.2);">
                        @else
                            <h3 class="text-xl font-bold tracking-wider uppercase" style="color: var(--color-footer-text);">{{ $siteSettings['site_name'] ?? 'Fashion Store' }}</h3>
                        @endif
                    </div>
                    <ul class="space-y-3">
                        @if(!empty($siteSettings['site_address']))
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 mt-0.5 flex-shrink-0" style="color: var(--color-footer-text); opacity: 0.7;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="text-sm" style="color: var(--color-footer-text); opacity: 0.9;">{{ $siteSettings['site_address'] }}</span>
                        </li>
                        @endif
                        @if(!empty($siteSettings['site_phone']))
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 flex-shrink-0" style="color: var(--color-footer-text); opacity: 0.7;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <a href="tel:{{ $siteSettings['site_phone'] }}" class="text-sm hover:opacity-80 transition-opacity" style="color: var(--color-footer-text); opacity: 0.9;">{{ $siteSettings['site_phone'] }}</a>
                        </li>
                        @endif
                        @if(!empty($siteSettings['site_email']))
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 flex-shrink-0" style="color: var(--color-footer-text); opacity: 0.7;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <a href="mailto:{{ $siteSettings['site_email'] }}" class="text-sm hover:opacity-80 transition-opacity" style="color: var(--color-footer-text); opacity: 0.9;">{{ $siteSettings['site_email'] }}</a>
                        </li>
                        @endif
                    </ul>
                    <!-- Social Media Links -->
                    @if(!empty($siteSettings['social_facebook']) || !empty($siteSettings['social_instagram']) || !empty($siteSettings['social_twitter']) || !empty($siteSettings['social_youtube']))
                    <div class="flex items-center gap-4 mt-6">
                        @if(!empty($siteSettings['social_facebook']))
                        <a href="{{ $siteSettings['social_facebook'] }}" target="_blank" class="hover:opacity-80 transition-opacity" style="color: var(--color-footer-text);">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        @endif
                        @if(!empty($siteSettings['social_instagram']))
                        <a href="{{ $siteSettings['social_instagram'] }}" target="_blank" class="hover:opacity-80 transition-opacity" style="color: var(--color-footer-text);">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                        @endif
                        @if(!empty($siteSettings['social_twitter']))
                        <a href="{{ $siteSettings['social_twitter'] }}" target="_blank" class="hover:opacity-80 transition-opacity" style="color: var(--color-footer-text);">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                        @endif
                        @if(!empty($siteSettings['social_youtube']))
                        <a href="{{ $siteSettings['social_youtube'] }}" target="_blank" class="hover:opacity-80 transition-opacity" style="color: var(--color-footer-text);">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                        </a>
                        @endif
                    </div>
                    @endif
                </div>

                <!-- Main Menu -->
                <div class="flex flex-col items-start px-4 md:px-0">
                    <div
                        class="text-xs font-bold px-4 py-1.5 rounded-sm uppercase tracking-wide mb-6" style="background-color: var(--color-footer-text); color: var(--color-footer-bg);">
                        MAIN MENU
                    </div>
                    <ul class="space-y-3 pl-6 border-l border-dashed" style="border-color: rgba(68, 18, 39, 0.3);">
                        <li><a href="#" class="text-sm font-medium hover:opacity-80 transition-opacity block" style="color: var(--color-footer-text);">Search</a>
                        </li>
                        <li><a href="{{ route('home') }}" class="text-sm font-medium hover:opacity-80 transition-opacity block" style="color: var(--color-footer-text);">Home</a>
                        </li>
                        <li><a href="{{ route('shop') }}" class="text-sm font-medium hover:opacity-80 transition-opacity block" style="color: var(--color-footer-text);">Shop</a>
                        </li>
                        <li><a href="{{ route('about') }}" class="text-sm font-medium hover:opacity-80 transition-opacity block" style="color: var(--color-footer-text);">About
                                Us</a></li>
                        <li><a href="#" class="text-sm font-medium hover:opacity-80 transition-opacity block" style="color: var(--color-footer-text);">Track
                                Order</a></li>
                        <li><a href="{{ route('contact') }}" class="text-sm font-medium hover:opacity-80 transition-opacity block" style="color: var(--color-footer-text);">Contact
                                Us</a></li>
                        <li><a href="{{ route('faqs') }}" class="text-sm font-medium hover:opacity-80 transition-opacity block" style="color: var(--color-footer-text);">FAQs</a>
                        </li>
                    </ul>
                </div>

                <!-- Bestsellers -->
                <div class="flex flex-col items-start px-4 md:px-0">
                    <div
                        class="text-xs font-bold px-4 py-1.5 rounded-sm uppercase tracking-wide mb-6" style="background-color: var(--color-footer-text); color: var(--color-footer-bg);">
                        BESTSELLERS
                    </div>
                    <ul class="space-y-3 pl-6 border-l border-dashed" style="border-color: rgba(68, 18, 39, 0.3);">
                        @php
                            $bestsellers = \App\Models\Product::where('status', 'active')
                                ->orderBy('price', 'desc')
                                ->inRandomOrder()
                                ->limit(5)
                                ->get();
                        @endphp
                        @forelse($bestsellers as $product)
                        <li><a href="{{ route('product.show', $product->slug) }}" class="text-sm font-medium hover:opacity-80 transition-opacity block" style="color: var(--color-footer-text);">{{ $product->name }}</a></li>
                        @empty
                        <li><span class="text-sm font-medium opacity-60" style="color: var(--color-footer-text);">No products available</span></li>
                        @endforelse
                    </ul>
                </div>

                <!-- Policies -->
                <div class="flex flex-col items-start px-4 md:px-0">
                    <div
                        class="text-xs font-bold px-4 py-1.5 rounded-sm uppercase tracking-wide mb-6" style="background-color: var(--color-footer-text); color: var(--color-footer-bg);">
                        POLICIES
                    </div>
                    <ul class="space-y-3 pl-6 border-l border-dashed" style="border-color: rgba(68, 18, 39, 0.3);">
                        <li><a href="{{ route('privacy-policy') }}" class="text-sm font-medium hover:opacity-80 transition-opacity block" style="color: var(--color-footer-text);">Privacy
                                Policy</a></li>
                        <li><a href="{{ route('refund-policy') }}" class="text-sm font-medium hover:opacity-80 transition-opacity block" style="color: var(--color-footer-text);">Refund
                                Policy</a></li>
                        <li><a href="{{ route('shipping-policy') }}" class="text-sm font-medium hover:opacity-80 transition-opacity block" style="color: var(--color-footer-text);">Shipping
                                Policy</a></li>
                        <li><a href="{{ route('terms-of-service') }}" class="text-sm font-medium hover:opacity-80 transition-opacity block" style="color: var(--color-footer-text);">Terms of
                                Service</a></li>
                        <li><a href="{{ route('contact-information') }}" class="text-sm font-medium hover:opacity-80 transition-opacity block" style="color: var(--color-footer-text);">Contact
                                Information</a></li>
                    </ul>
                </div>

            </div>

            <!-- Mobile Accordion Menu -->
            <div class="md:hidden mb-8 mx-0" style="border-top: 1px solid rgba(255,255,255,0.2);">

                <!-- Main Menu Accordion -->
                <div style="border-bottom: 1px solid rgba(255,255,255,0.2);">
                    <button onclick="toggleFooterAccordion(this)"
                        class="w-full flex items-center justify-between py-4 text-left focus:outline-none group transition-all duration-300" style="color: var(--color-footer-text);">
                        <span class="text-sm font-bold uppercase tracking-wide">MAIN MENU</span>
                        <svg class="w-4 h-4 transform transition-transform duration-300" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>
                    <div
                        class="hidden pb-4 space-y-2 pl-6 border-l border-dashed ml-2 accordion-content" style="border-color: rgba(255,255,255,0.3);">
                        <a href="#" class="block text-sm py-1" style="color: var(--color-footer-text); opacity: 0.8;">Search</a>
                        <a href="{{ route('home') }}" class="block text-sm py-1" style="color: var(--color-footer-text); opacity: 0.8;">Home</a>
                        <a href="{{ route('shop') }}" class="block text-sm py-1" style="color: var(--color-footer-text); opacity: 0.8;">Shop</a>
                        <a href="{{ route('about') }}" class="block text-sm py-1" style="color: var(--color-footer-text); opacity: 0.8;">About Us</a>
                        <a href="#" class="block text-sm py-1" style="color: var(--color-footer-text); opacity: 0.8;">Track Order</a>
                        <a href="{{ route('contact') }}" class="block text-sm py-1" style="color: var(--color-footer-text); opacity: 0.8;">Contact Us</a>
                        <a href="{{ route('faqs') }}" class="block text-sm py-1" style="color: var(--color-footer-text); opacity: 0.8;">FAQs</a>
                    </div>
                </div>

                <!-- Bestsellers Accordion -->
                <div style="border-bottom: 1px solid rgba(255,255,255,0.2);">
                    <button onclick="toggleFooterAccordion(this)"
                        class="w-full flex items-center justify-between py-4 text-left focus:outline-none group transition-all duration-300" style="color: var(--color-footer-text);">
                        <span class="text-sm font-bold uppercase tracking-wide">BESTSELLERS</span>
                        <svg class="w-4 h-4 transform transition-transform duration-300" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>
                    <div
                        class="hidden pb-4 space-y-2 pl-6 border-l border-dashed ml-2 accordion-content" style="border-color: rgba(255,255,255,0.3);">
                        @forelse($bestsellers as $product)
                        <a href="{{ route('product.show', $product->slug) }}" class="block text-sm py-1" style="color: var(--color-footer-text); opacity: 0.8;">{{ $product->name }}</a>
                        @empty
                        <span class="block text-sm py-1" style="color: var(--color-footer-text); opacity: 0.6;">No products available</span>
                        @endforelse
                    </div>
                </div>

                <!-- Policies Accordion -->
                <div style="border-bottom: 1px solid rgba(255,255,255,0.2);">
                    <button onclick="toggleFooterAccordion(this)"
                        class="w-full flex items-center justify-between py-4 text-left focus:outline-none group transition-all duration-300" style="color: var(--color-footer-text);">
                        <span class="text-sm font-bold uppercase tracking-wide">POLICIES</span>
                        <svg class="w-4 h-4 transform transition-transform duration-300" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>
                    <div
                        class="hidden pb-4 space-y-2 pl-6 border-l border-dashed ml-2 accordion-content" style="border-color: rgba(255,255,255,0.3);">
                        <a href="{{ route('privacy-policy') }}" class="block text-sm py-1" style="color: var(--color-footer-text); opacity: 0.8;">Privacy Policy</a>
                        <a href="{{ route('refund-policy') }}" class="block text-sm py-1" style="color: var(--color-footer-text); opacity: 0.8;">Refund Policy</a>
                        <a href="{{ route('shipping-policy') }}" class="block text-sm py-1" style="color: var(--color-footer-text); opacity: 0.8;">Shipping Policy</a>
                        <a href="{{ route('terms-of-service') }}" class="block text-sm py-1" style="color: var(--color-footer-text); opacity: 0.8;">Terms of Service</a>
                        <a href="{{ route('contact-information') }}" class="block text-sm py-1" style="color: var(--color-footer-text); opacity: 0.8;">Contact Information</a>
                    </div>
                </div>

            </div>

            <!-- Copyright & Back to Top -->
            <div class="flex flex-row items-end justify-between pb-8 md:pb-12 relative z-20 px-4 pt-4">
                <div class="text-left">
                    <p class="text-xs" style="color: var(--color-footer-text); opacity: 0.9;">© {{ date('Y') }} {{ $siteSettings['site_name'] ?? 'Fashion Store' }}.</p>
                    <p class="text-xs" style="color: var(--color-footer-text); opacity: 0.9;">All rights reserved.</p>
                </div>

                <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})"
                    class="flex items-center gap-2 text-xs font-medium hover:opacity-80 transition-opacity" style="color: var(--color-footer-text);">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                    </svg>
                    Back to top
                </button>
            </div>

        </div>

        <script>
            function toggleFooterAccordion(button) {
                const content = button.nextElementSibling;
                const arrow = button.querySelector('svg');
                const isHidden = content.classList.contains('hidden');

                // Toggle content visibility
                content.classList.toggle('hidden');

                if (isHidden) {
                    // Opening - use CSS variables
                    button.style.backgroundColor = 'var(--color-footer-text)';
                    button.style.color = 'var(--color-footer-bg)';
                    button.classList.add('px-4');
                    arrow.classList.add('rotate-180');
                } else {
                    // Closing - reset to default
                    button.style.backgroundColor = 'transparent';
                    button.style.color = 'var(--color-footer-text)';
                    button.classList.remove('px-4');
                    arrow.classList.remove('rotate-180');
                }
            }
        </script>

    </footer>

    <!-- Cart Drawer -->
    @include('website.includes.cart_drawer')

    <!-- Wishlist Drawer -->
    @include('website.includes.wishlist_drawer')
