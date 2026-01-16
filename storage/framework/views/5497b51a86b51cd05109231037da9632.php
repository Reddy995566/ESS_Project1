<div id="quick-view-modal" class="fixed inset-0 z-[60] hidden" aria-labelledby="modal-title" role="dialog"
    aria-modal="true">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/60 transition-opacity backdrop-blur-sm" id="quick-view-backdrop"></div>

    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div
            class="relative transform overflow-hidden rounded-lg bg-[#faf5ed] text-left shadow-xl transition-all sm:my-8 w-full max-w-[1100px] max-h-[90vh] overflow-y-auto custom-scrollbar">

            <!-- Close Button -->
            <button type="button" id="close-quick-view"
                class="absolute right-4 top-4 z-20 text-gray-500 hover:text-gray-800 focus:outline-none bg-white/50 rounded-full p-2">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <!-- Modal Content (Grid) -->
            <div class="p-6 md:p-8">

                <!-- Skeleton Loader -->
                <div id="qv-skeleton" class="animate-pulse grid grid-cols-1 lg:grid-cols-12 gap-8 hidden">
                    <!-- LEFT Column Skeleton -->
                    <div class="lg:col-span-6 flex flex-col gap-4">
                        <div class="aspect-[3/4] w-full rounded-md bg-gray-200"></div>
                        <div class="grid grid-cols-5 gap-3">
                            <div class="aspect-square bg-gray-200 rounded"></div>
                            <div class="aspect-square bg-gray-200 rounded"></div>
                            <div class="aspect-square bg-gray-200 rounded"></div>
                            <div class="aspect-square bg-gray-200 rounded"></div>
                            <div class="aspect-square bg-gray-200 rounded"></div>
                        </div>
                    </div>

                    <!-- RIGHT Column Skeleton -->
                    <div class="lg:col-span-6 flex flex-col gap-5">
                        <div class="flex gap-2">
                            <div class="h-6 w-20 bg-gray-200 rounded"></div>
                            <div class="h-6 w-20 bg-gray-200 rounded"></div>
                        </div>
                        <div class="h-8 w-3/4 bg-gray-200 rounded mb-2"></div>
                        <div class="flex gap-2 items-center mb-4">
                            <div class="h-4 w-24 bg-gray-200 rounded"></div>
                            <div class="h-4 w-16 bg-gray-200 rounded"></div>
                        </div>
                        <div class="h-6 w-1/2 bg-gray-200 rounded-full mb-4"></div>
                        <div class="h-10 w-1/3 bg-gray-200 rounded mb-4"></div>
                        <div class="h-24 w-full bg-gray-200 rounded mb-4"></div>
                        <div class="h-10 w-full bg-gray-200 rounded mb-4"></div>
                        <div class="flex gap-4 h-11 mb-4">
                            <div class="w-32 bg-gray-200 rounded"></div>
                            <div class="flex-1 bg-gray-200 rounded"></div>
                        </div>
                        <div class="h-11 w-full bg-gray-200 rounded mb-6"></div>

                        <div class="space-y-4">
                            <div class="h-12 w-full bg-gray-200 rounded"></div>
                            <div class="h-12 w-full bg-gray-200 rounded"></div>
                            <div class="h-12 w-full bg-gray-200 rounded"></div>
                        </div>
                    </div>
                </div>

                <!-- Actual Content (Hidden by default) -->
                <div id="qv-content" class="grid grid-cols-1 lg:grid-cols-12 gap-8 hidden">

                    <!-- LEFT Column: Images (6 cols) -->
                    <div class="lg:col-span-6 flex flex-col gap-4 relative">
                        <!-- Image Skeleton (Hidden by default) -->
                        <div id="qv-image-skeleton"
                            class="flex flex-col gap-4 absolute inset-0 z-10 bg-[#faf5ed] hidden animate-pulse">
                            <div class="aspect-[3/4] w-full rounded-md bg-gray-200"></div>
                            <div class="grid grid-cols-5 gap-3">
                                <div class="aspect-square bg-gray-200 rounded"></div>
                                <div class="aspect-square bg-gray-200 rounded"></div>
                                <div class="aspect-square bg-gray-200 rounded"></div>
                                <div class="aspect-square bg-gray-200 rounded"></div>
                                <div class="aspect-square bg-gray-200 rounded"></div>
                            </div>
                        </div>
                        <!-- Main Image with Swiper -->
                        <div class="relative aspect-[3/4] w-full overflow-hidden rounded-md bg-[#f0ece4]">
                            <!-- Swiper Container -->
                            <div class="swiper qv-main-swiper w-full h-full" id="qv-swiper-container">
                                <div class="swiper-wrapper" id="qv-swiper-wrapper">
                                    <!-- Slides will be populated by JS -->
                                </div>
                                
                                <!-- Navigation Arrows -->
                                <div id="qv-swiper-prev" class="swiper-button-prev !w-10 !h-10 !bg-white/80 !rounded-full !text-[#4b0f27] hover:!bg-white transition-all !left-2" style="--swiper-navigation-size: 20px;"></div>
                                <div id="qv-swiper-next" class="swiper-button-next !w-10 !h-10 !bg-white/80 !rounded-full !text-[#4b0f27] hover:!bg-white transition-all !right-2" style="--swiper-navigation-size: 20px;"></div>
                            </div>
                        </div>

                        <!-- Thumbnails -->
                        <div class="grid grid-cols-5 gap-3" id="qv-thumbnails">
                            <!-- JS Populated -->
                        </div>
                    </div>

                    <!-- RIGHT Column: Details (6 cols) -->
                    <div class="lg:col-span-6 flex flex-col gap-5">

                        <!-- Badges -->
                        <div class="flex flex-wrap gap-2">
                            <span
                                class="rounded bg-[#fef3c7] px-2 py-1 text-[10px] uppercase font-bold tracking-wide text-[#92400e]">
                                🚚 Free Shipping
                            </span>
                            <span
                                class="rounded bg-[#ffedd5] px-2 py-1 text-[10px] uppercase font-bold tracking-wide text-[#9a3412]">
                                🎧 24/7 Support
                            </span>
                            <span
                                class="rounded bg-[#fef3c7] px-2 py-1 text-[10px] uppercase font-bold tracking-wide text-[#92400e]">
                                ↩ 3 Days Return
                            </span>
                        </div>

                        <!-- Title -->
                        <div>
                            <h2 id="qv-title"
                                class="font-serif-elegant text-[26px] font-semibold leading-tight text-[#4b0f27] tracking-wide">
                            </h2>
                            <!-- Rating -->
                            <div class="mt-1 flex items-center gap-1">
                                <div class="flex text-[#fbbf24]" id="qv-rating-stars"></div>
                                <span class="text-xs text-gray-500" id="qv-reviews-count"></span>
                            </div>
                        </div>

                        <!-- Bought Badge -->
                        <div id="qv-bought-badge" class="w-fit rounded-full bg-[#fae8e8] px-3 py-1 text-xs font-medium text-[#4b0f27] hidden">
                            ⚡ <span id="qv-bought-count"></span>
                        </div>

                        <!-- Price -->
                        <div class="font-serif-elegant text-[24px] font-bold text-[#4b0f27] tracking-wide">
                            <span id="qv-price"></span>
                            <span id="qv-original-price"
                                class="text-lg text-gray-500 line-through ml-2 font-normal"></span>
                        </div>

                        <!-- Offer Box -->
                        <div class="relative overflow-hidden rounded border border-[#4b0f27] bg-[#fcecc6] p-3">
                            <div
                                class="relative z-10 flex flex-col gap-1 text-[11px] font-bold uppercase tracking-wide text-[#4b0f27]">
                                <p class="flex items-center gap-1">
                                    <svg class="h-3 w-3 fill-current" viewBox="0 0 24 24">
                                        <path
                                            d="M2.5 12a.999.999 0 0 1-.293-.707V3a1 1 0 0 1 1-1h8.293a1 1 0 0 1 .707.293l7.5 7.5a1 1 0 0 1 0 1.414l-8.293 8.293a1 1 0 0 1-1.414 0l-7.5-7.5A.999.999 0 0 1 2.5 12zm3.5-5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z" />
                                    </svg>
                                    BUY ANY 2 & SAVE 5%
                                </p>
                                <p class="flex items-center gap-1">
                                    <svg class="h-3 w-3 fill-current" viewBox="0 0 24 24">
                                        <path
                                            d="M2.5 12a.999.999 0 0 1-.293-.707V3a1 1 0 0 1 1-1h8.293a1 1 0 0 1 .707.293l7.5 7.5a1 1 0 0 1 0 1.414l-8.293 8.293a1 1 0 0 1-1.414 0l-7.5-7.5A.999.999 0 0 1 2.5 12zm3.5-5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z" />
                                    </svg>
                                    BUY ANY 4 OR MORE & SAVE 10%
                                </p>
                            </div>
                        </div>

                        <!-- Pay Online Stripe -->
                        <div
                            class="rounded bg-[#ffdb89] py-2 text-center text-xs font-bold text-black uppercase tracking-wider">
                            Pay Online & Get Extra ₹100 Off
                        </div>

                        <!-- Hidden Inputs -->
                        <input type="hidden" id="qv-product-id">
                        <input type="hidden" id="qv-selected-color">
                        <input type="hidden" id="qv-selected-color-name">

                        <!-- Quantity & Buttons -->
                        <div class="mt-4 flex flex-col gap-3">
                            <div class="flex gap-4 h-11">
                                <!-- Qty -->
                                <div
                                    class="w-32 flex items-center justify-between rounded border border-[#2A1810]/20 bg-[#FAF5ED] px-1">
                                    <button type="button" id="qv-qty-minus"
                                        class="h-full px-3 text-xl text-[#2A1810] hover:bg-black/5 transition flex items-center justify-center">-</button>
                                    <input type="text" id="qv-qty-input" value="1"
                                        class="h-full w-full border-none bg-transparent text-center text-base font-medium text-[#2A1810] focus:ring-0 p-0"
                                        readonly>
                                    <button type="button" id="qv-qty-plus"
                                        class="h-full px-3 text-xl text-[#2A1810] hover:bg-black/5 transition flex items-center justify-center">+</button>
                                </div>
                                <!-- Add to Cart -->
                                <button type="button" id="qv-add-to-cart-btn"
                                    class="flex-1 rounded bg-[#3D0C1F] text-sm font-semibold text-white transition hover:bg-[#2a0815] uppercase tracking-wider flex items-center justify-center">
                                    Add to cart
                                </button>
                            </div>
                            <!-- Buy Now -->
                            <button type="button" id="qv-buy-now-btn"
                                class="h-11 w-full rounded bg-[#495530] text-sm font-semibold text-white transition hover:bg-[#384225] uppercase tracking-wider shadow-sm">
                                Buy it now
                            </button>
                        </div>

                        <!-- More Colors -->
                        <div class="mt-4 hidden" id="qv-colors-wrapper">
                            <h3 class="mb-3 text-sm font-semibold text-gray-800">More Colors</h3>
                            <div class="grid grid-cols-4 gap-2" id="qv-more-colors">
                                <!-- JS Populated -->
                            </div>
                        </div>

                        <!-- Accordions (Static content for now, matching Product Page) -->
                        <div class="mt-6 border-t border-[#4b0f27]/10">
                            <!-- Product Details -->
                            <div class="border-b border-[#4b0f27]/10">
                                <button
                                    onclick="this.nextElementSibling.classList.toggle('max-h-0'); this.nextElementSibling.classList.toggle('opacity-0'); this.nextElementSibling.classList.toggle('py-4')"
                                    class="w-full py-4 flex items-center justify-between text-left group focus:outline-none select-none">
                                    <span class="font-bold text-[#4b0f27] uppercase tracking-wide text-sm">Product
                                        Details</span>
                                    <svg class="w-5 h-5 text-[#4b0f27]" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div class="overflow-hidden transition-all duration-300 max-h-0 opacity-0">
                                    <div class="pb-4 text-sm text-[#4b0f27]/80 leading-relaxed font-sans-premium"
                                        id="qv-description">
                                        <!-- JS Populated -->
                                    </div>
                                </div>
                            </div>
                            <!-- Shipping -->
                            <div class="border-b border-[#4b0f27]/10">
                                <button
                                    onclick="this.nextElementSibling.classList.toggle('max-h-0'); this.nextElementSibling.classList.toggle('opacity-0');"
                                    class="w-full py-4 flex items-center justify-between text-left group focus:outline-none select-none">
                                    <span class="font-bold text-[#4b0f27] uppercase tracking-wide text-sm">Shipping
                                        Information</span>
                                    <svg class="w-5 h-5 text-[#4b0f27]" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div class="overflow-hidden transition-all duration-300 max-h-0 opacity-0">
                                    <div class="pb-4 text-sm text-[#4b0f27]/80 leading-relaxed font-sans-premium">
                                        <p>Standard shipping: 3-5 business days. Express available.</p>
                                    </div>
                                </div>
                            </div>
                            <!-- Returns -->
                            <div class="border-b border-[#4b0f27]/10">
                                <button
                                    onclick="this.nextElementSibling.classList.toggle('max-h-0'); this.nextElementSibling.classList.toggle('opacity-0');"
                                    class="w-full py-4 flex items-center justify-between text-left group focus:outline-none select-none">
                                    <span class="font-bold text-[#4b0f27] uppercase tracking-wide text-sm">Returns &
                                        Refunds</span>
                                    <svg class="w-5 h-5 text-[#4b0f27]" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div class="overflow-hidden transition-all duration-300 max-h-0 opacity-0">
                                    <div class="pb-4 text-sm text-[#4b0f27]/80 leading-relaxed font-sans-premium">
                                        <p>7-day return policy for unused items with original tags.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div><?php /**PATH C:\xampp\htdocs\my personal project 1111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111\ecom\resources\views/website/includes/quick_view_modal.blade.php ENDPATH**/ ?>