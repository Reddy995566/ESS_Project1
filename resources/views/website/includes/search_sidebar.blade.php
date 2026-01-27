<!-- SEARCH SIDEBAR & OVERLAY -->
<!-- Overlay -->
<div id="search-overlay" class="fixed inset-0 bg-black/50 z-40 hidden transition-opacity duration-300 opacity-0">
</div>

<!-- Sidebar -->
<div id="search-sidebar"
    class="fixed inset-y-0 right-0 w-full max-w-[400px] z-50 transform translate-x-full transition-transform duration-300 shadow-2xl overflow-y-auto" style="background-color: var(--color-bg-primary);">

    <!-- Header -->
    <div class="flex items-center justify-between px-6 py-4" style="border-bottom: 1px solid var(--color-border);">
        <h2 class="text-lg font-medium" style="color: var(--color-primary);">Search</h2>
        <button id="close-search-btn" class="hover:opacity-70" style="color: var(--color-primary);">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                </path>
            </svg>
        </button>
    </div>

    <!-- Content -->
    <div class="p-6">

        <!-- Search Input -->
        <div class="relative mb-6">
            <input type="text" id="search-input" placeholder="Search our store..."
                class="w-full pl-4 pr-10 py-3 bg-transparent rounded-sm focus:outline-none focus:ring-1" style="border: 1px solid var(--color-primary); color: var(--color-primary);">
            <svg id="search-icon-static"
                class="w-5 h-5 absolute right-3 top-1/2 transform -translate-y-1/2" fill="none"
                stroke="currentColor" viewBox="0 0 24 24" style="color: var(--color-primary);">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <!-- Loader -->
            <div id="search-loader" class="absolute right-3 top-1/2 transform -translate-y-1/2 hidden">
                <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" style="color: var(--color-primary);">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
            </div>
        </div>

        <!-- Start Typing Text -->
        <p id="start-typing-text" class="text-center text-sm mb-6" style="color: var(--color-primary);">Start typing to see results</p>

        <!-- Search Skeleton Loader -->
        <div id="search-skeleton" class="space-y-6 hidden animate-pulse">
            <!-- Suggestions Skeleton -->
            <div class="space-y-2">
                <div class="h-4 bg-gray-200 rounded w-1/4"></div>
                <div class="h-8 bg-gray-200 rounded w-full"></div>
                <div class="h-8 bg-gray-200 rounded w-full"></div>
                <div class="h-8 bg-gray-200 rounded w-3/4"></div>
            </div>
             <!-- Products Skeleton -->
             <div class="space-y-4">
                <div class="h-4 bg-gray-200 rounded w-1/3 mb-2"></div>
                <!-- Product Item Skeleton x 3 -->
                <div class="flex gap-4">
                    <div class="w-24 h-32 bg-gray-200 rounded-sm"></div>
                    <div class="flex-1 space-y-2 py-2">
                         <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                         <div class="h-3 bg-gray-200 rounded w-1/4"></div>
                         <div class="h-4 bg-gray-200 rounded w-1/2"></div>
                         <div class="h-8 bg-gray-200 rounded w-full mt-2"></div>
                    </div>
                </div>
                 <div class="flex gap-4">
                    <div class="w-24 h-32 bg-gray-200 rounded-sm"></div>
                    <div class="flex-1 space-y-2 py-2">
                         <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                         <div class="h-3 bg-gray-200 rounded w-1/4"></div>
                         <div class="h-4 bg-gray-200 rounded w-1/2"></div>
                         <div class="h-8 bg-gray-200 rounded w-full mt-2"></div>
                    </div>
                </div>
                 <div class="flex gap-4">
                    <div class="w-24 h-32 bg-gray-200 rounded-sm"></div>
                    <div class="flex-1 space-y-2 py-2">
                         <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                         <div class="h-3 bg-gray-200 rounded w-1/4"></div>
                         <div class="h-4 bg-gray-200 rounded w-1/2"></div>
                         <div class="h-8 bg-gray-200 rounded w-full mt-2"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Results Logic Container -->
        <div id="search-results-container" class="space-y-6 hidden">
            <!-- Suggestions -->
            <div id="suggestions-container" class="hidden">
                <h3
                    class="text-sm font-medium uppercase tracking-wide pb-2 mb-2 inline-block" style="color: var(--color-text-primary); border-bottom: 1px solid var(--color-border);">
                    Suggestions</h3>
                <ul id="suggestions-list" class="space-y-1">
                    <!-- JS Populated -->
                </ul>
            </div>

            <!-- Products -->
            <div id="products-container" class="hidden">
                <h3
                    class="text-sm font-medium uppercase tracking-wide pb-2 mb-4 inline-block" style="color: var(--color-text-primary); border-bottom: 1px solid var(--color-border);">
                    Products (<span id="products-count">0</span>)
                </h3>
                <div id="products-list" class="space-y-4">
                    <!-- JS Populated -->
                </div>
            </div>
        </div>

        <!-- Default/Empty State (Optional, logic handles this) -->

        <!-- Existing Recommendation Block (Keep if input is empty or maybe hide when searching?) -->
        <!-- We will hide existing static content when searching -->
        <div id="static-search-content">
            <!-- Continue Shopping Button -->
            <button id="continue-shopping-btn"
                class="w-full py-2.5 text-sm font-medium transition-colors duration-200 mb-8 rounded-sm" style="border: 1px solid var(--color-primary); color: var(--color-primary);">
                Continue Shopping
            </button>

            <!-- Recommendations Header -->
            <div class="pt-6 mb-4" style="border-top: 1px solid var(--color-border);">
                <div class="flex items-center justify-between cursor-pointer group">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--color-primary);">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                            </path>
                        </svg>
                        <span class="font-medium text-sm" style="color: var(--color-primary);">Explore Our Best Sellers</span>
                    </div>
                    <svg class="w-4 h-4 transform transition-transform group-hover:translate-y-0.5"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--color-primary);">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
            </div>

            <!-- Product List (Static Best Sellers) -->
            <div class="space-y-4 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                <!-- Keep static items as fallback or initial view -->
                <!-- Product Item 1 -->
                <div class="flex gap-3">
                    <div class="w-20 h-24 bg-gray-100 flex-shrink-0 lazy-wrapper">
                        <img data-src="{{ asset('website/assets/images/product-1.jpg') }}" alt="Patola Linen Cream Red Saree"
                            class="w-full h-full object-cover lazy-image" loading="lazy">
                    </div>
                    <div class="flex-1 flex flex-col justify-between py-1">
                        <div>
                            <h4 class="text-sm font-medium leading-tight mb-1" style="color: var(--color-primary);">Patola Linen Cream Red
                                Saree</h4>
                            <p class="text-sm" style="color: var(--color-primary);">Rs. 1,399</p>
                        </div>
                        <button
                            class="w-full py-1.5 text-xs font-medium transition-colors rounded-sm" style="background-color: var(--color-bg-secondary); color: var(--color-primary);">
                            Add to cart
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@include('website.includes.quick_view_modal')

<!-- Search Sidebar Script -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const searchBtn = document.getElementById('search-btn');
        const searchSidebar = document.getElementById('search-sidebar');
        const searchOverlay = document.getElementById('search-overlay');
        const closeSearchBtn = document.getElementById('close-search-btn');
        const continueShoppingBtn = document.getElementById('continue-shopping-btn');

        // Search Elements
        const searchInput = document.getElementById('search-input');
        const searchResultsContainer = document.getElementById('search-results-container');
        const staticSearchContent = document.getElementById('static-search-content');
        const startTypingText = document.getElementById('start-typing-text');
        const suggestionsList = document.getElementById('suggestions-list');
        const productsList = document.getElementById('products-list');
        const productsCount = document.getElementById('products-count');
        const searchLoader = document.getElementById('search-loader');
        const searchIconStatic = document.getElementById('search-icon-static');
        const searchSkeleton = document.getElementById('search-skeleton');

        function openSearch() {
            searchOverlay.classList.remove('hidden');

            // Calculate scrollbar width
            const scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;
            document.body.style.paddingRight = `${scrollbarWidth}px`;

            // Small delay to allow display:block to apply before opacity transition
            setTimeout(() => {
                searchOverlay.classList.remove('opacity-0');
                searchSidebar.classList.remove('translate-x-full');
                if (searchInput) searchInput.focus();
            }, 10);
            document.body.style.overflow = 'hidden';
        }

        function closeSearch() {
            searchOverlay.classList.add('opacity-0');
            searchSidebar.classList.add('translate-x-full');

            setTimeout(() => {
                searchOverlay.classList.add('hidden');
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';

                // Reset Search State
                if (searchInput) searchInput.value = '';
                if (searchResultsContainer) searchResultsContainer.classList.add('hidden');
                if (staticSearchContent) staticSearchContent.classList.remove('hidden');
                if (startTypingText) startTypingText.classList.remove('hidden');
            }, 300);
        }

        if (searchBtn) {
            searchBtn.addEventListener('click', (e) => {
                e.preventDefault();
                openSearch();
            });
        }

        if (closeSearchBtn) {
            closeSearchBtn.addEventListener('click', closeSearch);
        }

        if (continueShoppingBtn) {
            continueShoppingBtn.addEventListener('click', closeSearch);
        }

        if (searchOverlay) {
            searchOverlay.addEventListener('click', closeSearch);
        }

        // AJAX Search Logic
        let timeout = null;

        if (searchInput) {
            searchInput.addEventListener('input', function () {
                const query = this.value.trim();

                // Toggle visibility of static/dynamic content
                if (query.length > 0) {
                    staticSearchContent.classList.add('hidden');
                    startTypingText.classList.add('hidden');
                    // Show Skeleton instead of spinner
                    searchSkeleton.classList.remove('hidden');
                    searchIconStatic.classList.add('hidden'); 
                    searchResultsContainer.classList.add('hidden'); // Hide any old results
                } else {
                    staticSearchContent.classList.remove('hidden');
                    startTypingText.classList.remove('hidden');
                    searchResultsContainer.classList.add('hidden');
                    searchSkeleton.classList.add('hidden');
                    searchIconStatic.classList.remove('hidden');
                    return;
                }

                // Debounce
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    performSearch(query);
                }, 300);
            });
        }

        function performSearch(query) {
            const url = `{{ route('search.ajax') }}?q=${encodeURIComponent(query)}`;
            console.log('Fetching:', url);
            
            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
                .then(response => {
                    console.log('Response status:', response.status);
                    console.log('Response headers:', response.headers.get('content-type'));
                    
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    
                    return response.text();
                })
                .then(text => {
                    console.log('Response text:', text.substring(0, 200));
                    
                    try {
                        const data = JSON.parse(text);
                        searchSkeleton.classList.add('hidden');
                        searchIconStatic.classList.remove('hidden');

                        if (data.suggestions.length > 0 || data.products.length > 0) {
                            searchResultsContainer.classList.remove('hidden');
                            renderSuggestions(data.suggestions, query);
                            renderProducts(data.products, data.total_products);
                        } else {
                            searchResultsContainer.classList.add('hidden');
                        }
                    } catch (e) {
                        console.error('JSON parse error:', e);
                        console.error('Response was:', text);
                        searchSkeleton.classList.add('hidden');
                        searchIconStatic.classList.remove('hidden');
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    searchSkeleton.classList.add('hidden');
                    searchIconStatic.classList.remove('hidden');
                });
        }

        function renderSuggestions(suggestions, query) {
            const container = document.getElementById('suggestions-container');
            if (suggestions.length === 0) {
                container.classList.add('hidden');
                return;
            }

            container.classList.remove('hidden');
            suggestionsList.innerHTML = suggestions.map(s => `
                    <li class="py-2 px-2 hover:bg-gray-50 cursor-pointer text-sm font-medium transition-colors" style="color: var(--color-primary);" onclick="window.location.href='{{ route('shop') }}?search=${encodeURIComponent(s)}'">
                        ${s.replace(new RegExp(query, 'gi'), match => `<span class="font-bold">${match}</span>`)}
                    </li>
                `).join('');
        }

        function renderProducts(products, total) {
            const container = document.getElementById('products-container');
            if (products.length === 0) {
                container.classList.add('hidden');
                return;
            }

            container.classList.remove('hidden');
            productsCount.textContent = total;

            productsList.innerHTML = products.map(product => `
                    <div class="flex gap-4 group items-start pb-4 last:border-0 last:pb-0" style="border-bottom: 1px solid var(--color-border);">
                        <div class="w-24 h-32 bg-gray-100 flex-shrink-0 relative overflow-hidden rounded-sm lazy-wrapper">
                            <img src="${product.image || 'https://placehold.co/600x800'}" alt="${product.name}"
                                class="w-full h-full object-cover" loading="lazy">
                        </div>
                        <div class="flex-1 flex flex-col h-full justify-between min-w-0">
                            <div>
                                <h4 class="text-[15px] font-normal leading-tight mb-1 truncate" style="color: var(--color-text-primary);">
                                    <a href="/product/${product.slug}">${product.name}</a>
                                </h4>
                                <!-- Reviews -->
                                <div class="flex items-center gap-1 mb-1.5">
                                    <div class="flex" style="color: var(--color-accent-gold);">
                                        ${renderStars(product.rating || 5)}
                                    </div>
                                    <span class="text-xs ml-1" style="color: var(--color-text-muted);">${product.reviews_count || 0} reviews</span>
                                </div>
                                <div class="text-[15px] font-medium" style="color: var(--color-text-primary);">
                                    Rs. ${Number(product.price).toLocaleString()}
                                    ${product.original_price ? `<span class="text-xs text-gray-500 line-through ml-2">Rs. ${Number(product.original_price).toLocaleString()}</span>` : ''}
                                </div>
                            </div>
                            <button onclick="window.openQuickView(event, ${product.id})" 
                                class="w-full py-2 mt-3 text-center text-xs uppercase tracking-wider font-medium transition-colors rounded-[2px] block" style="border: 1px solid var(--color-primary); color: var(--color-primary);">
                                Quick view
                            </button>
                        </div>
                    </div>
                `).join('');
        }

        function renderStars(rating) {
            let stars = '';
            for (let i = 1; i <= 5; i++) {
                if (i <= rating) {
                    stars += '<svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20" style="color: var(--color-accent-gold);"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>';
                } else {
                    stars += '<svg class="w-3.5 h-3.5 text-gray-300" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>';
                }
            }
            return stars;
        }

        // Quick View Modal Logic
        const quickViewModal = document.getElementById('quick-view-modal');
        const closeQuickViewBtn = document.getElementById('close-quick-view');
        const quickViewBackdrop = document.getElementById('quick-view-backdrop');

        function closeQuickView() {
            quickViewModal.classList.add('hidden');
        }

        if (closeQuickViewBtn) closeQuickViewBtn.addEventListener('click', closeQuickView);
        if (quickViewBackdrop) quickViewBackdrop.addEventListener('click', closeQuickView);

        // Expose openQuickView to window so onclick works
        // Store swiper instance globally for quick view modal
        window.qvSwiper = null;
        
        // Function to update active thumbnail - GLOBAL SCOPE
        window.qvUpdateActiveThumbnail = function(index) {
            const thumbnails = document.querySelectorAll('#qv-thumbnails .gallery-thumb');
            thumbnails.forEach((thumb, i) => {
                if (i === index) {
                    thumb.classList.remove('border-gray-200');
                    thumb.classList.add('border-[#4b0f27]');
                } else {
                    thumb.classList.remove('border-[#4b0f27]');
                    thumb.classList.add('border-gray-200');
                }
            });
        };
        
        // Function to go to specific slide - GLOBAL SCOPE
        window.qvGoToSlide = function(index) {
            console.log('qvGoToSlide called with index:', index);
            
            // Get swiper instance from DOM element
            const swiperEl = document.getElementById('qv-swiper-container');
            if (swiperEl && swiperEl.swiper) {
                console.log('Using swiper from DOM element');
                swiperEl.swiper.slideTo(index, 300);
                window.qvUpdateActiveThumbnail(index);
            } else if (window.qvSwiper && typeof window.qvSwiper.slideTo === 'function') {
                console.log('Using window.qvSwiper');
                window.qvSwiper.slideTo(index, 300);
                window.qvUpdateActiveThumbnail(index);
            } else {
                console.log('Swiper not found, manually updating');
                // Fallback: manually show the slide
                const slides = document.querySelectorAll('#qv-swiper-wrapper .swiper-slide');
                slides.forEach((slide, i) => {
                    if (i === index) {
                        slide.style.display = 'block';
                    } else {
                        slide.style.display = 'none';
                    }
                });
                window.qvUpdateActiveThumbnail(index);
            }
        };
        
        // Helper to render thumbnails with Swiper - GLOBAL SCOPE
        window.qvRenderThumbnails = function(images) {
            console.log('qvRenderThumbnails called with', images.length, 'images');
            
            // Destroy existing swiper if any
            if (window.qvSwiper && typeof window.qvSwiper.destroy === 'function') {
                window.qvSwiper.destroy(true, true);
                window.qvSwiper = null;
            }
            
            // Get the specific swiper container by ID
            const swiperContainer = document.getElementById('qv-swiper-container');
            const swiperWrapper = document.getElementById('qv-swiper-wrapper');
            
            if (!swiperContainer || !swiperWrapper) {
                console.error('Swiper container not found');
                return;
            }
            
            // Build swiper slides
            swiperWrapper.innerHTML = images.map((img, index) => `
                <div class="swiper-slide">
                    <img src="${img}" alt="Product Image" class="w-full h-full object-cover">
                </div>
            `).join('');
            
            // Build thumbnails
            const thumbnailsHtml = images.map((img, index) => `
                <div class="gallery-thumb cursor-pointer rounded-lg border-2 ${index === 0 ? 'border-[#4b0f27]' : 'border-gray-200'} p-1 bg-white hover:border-[#4b0f27] transition-colors duration-200" 
                    onclick="window.qvGoToSlide(${index})">
                    <div class="aspect-square w-full overflow-hidden rounded-md">
                        <img src="${img}" class="h-full w-full object-cover">
                    </div>
                </div>
            `).join('');
            document.getElementById('qv-thumbnails').innerHTML = thumbnailsHtml;
            
            // Initialize Swiper with ID-based selectors
            const swiperInstance = new Swiper('#qv-swiper-container', {
                loop: false, // Disable loop to avoid index confusion
                slidesPerView: 1,
                spaceBetween: 0,
                navigation: {
                    nextEl: '#qv-swiper-next',
                    prevEl: '#qv-swiper-prev',
                },
                on: {
                    slideChange: function() {
                        const activeIndex = this.activeIndex;
                        console.log('Swiper slideChange, activeIndex:', activeIndex);
                        window.qvUpdateActiveThumbnail(activeIndex);
                    }
                }
            });
            
            // Store swiper instance
            window.qvSwiper = swiperInstance;
            console.log('Swiper initialized:', !!window.qvSwiper, 'slideTo:', typeof window.qvSwiper.slideTo);
            
            // Ensure first thumbnail is highlighted after render
            setTimeout(() => {
                window.qvUpdateActiveThumbnail(0);
            }, 100);
        };
        
        window.openQuickView = function (event, id) {
            e = event || window.event;
            e.preventDefault();

            // Show Modal with Loader or Placeholder
            quickViewModal.classList.remove('hidden');

            // Reset fields
            document.getElementById('qv-title').textContent = 'Loading...';
            document.getElementById('qv-price').textContent = '';
            document.getElementById('qv-original-price').textContent = '';
            // No need to set qv-main-image src anymore, swiper will handle it
            document.getElementById('qv-thumbnails').innerHTML = '';

            document.getElementById('qv-more-colors').innerHTML = '';
            document.getElementById('qv-colors-wrapper').classList.add('hidden');

            // Reset Modal State (Show Skeleton, Hide Content)
            const qvSkeleton = document.getElementById('qv-skeleton');
            const qvContent = document.getElementById('qv-content');

            qvSkeleton.classList.remove('hidden');
            qvContent.classList.add('hidden');

            // Fetch Data
            fetch(`/search/quick-view/${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                        closeQuickView();
                        return;
                    }

                    // Populate Data
                    document.getElementById('qv-product-id').value = data.id;
                    document.getElementById('qv-qty-input').value = 1;
                    document.getElementById('qv-selected-color').value = '';
                    document.getElementById('qv-selected-color-name').value = '';

                    document.getElementById('qv-title').textContent = data.name;
                    document.getElementById('qv-price').textContent = `Rs. ${Number(data.price).toLocaleString()}`;
                    if (data.original_price) {
                        document.getElementById('qv-original-price').textContent = `Rs. ${Number(data.original_price).toLocaleString()}`;
                    } else {
                        document.getElementById('qv-original-price').textContent = '';
                    }

                    document.getElementById('qv-reviews-count').textContent = `${data.reviews_count} reviews`;
                    document.getElementById('qv-rating-stars').innerHTML = renderStars(data.rating);

                    // Recent Purchases Badge
                    const boughtBadge = document.getElementById('qv-bought-badge');
                    const boughtCount = document.getElementById('qv-bought-count');
                    if (data.recent_purchases && data.recent_purchases > 0) {
                        boughtCount.textContent = `${data.recent_purchases} Bought this in 24 hours`;
                        boughtBadge.classList.remove('hidden');
                    } else {
                        boughtBadge.classList.add('hidden');
                    }

                    // Description
                    document.getElementById('qv-description').innerHTML = data.description || 'No description available.';

                    // Initial Render
                    window.qvRenderThumbnails(data.images);

                    // Colors Logic
                    const colorsContainer = document.getElementById('qv-more-colors');
                    const colorsWrapper = document.getElementById('qv-colors-wrapper');
                    const groupedImages = data.grouped_images || {};
                    
                    // Store first color ID for initial hiding
                    let initialColorId = null;

                    let colorsHtml = '';
                    if (data.colors && data.colors.length > 0) {
                        // Store first color ID
                        if (data.colors.length > 0) {
                            initialColorId = data.colors[0].id;
                        }
                        
                        colorsHtml = data.colors.map((color, index) => `
                             <div class="cursor-pointer group flex flex-col items-center color-swatch qv-color-swatch qv-more-color-item" 
                                data-color-id="${color.id}"
                                data-color-name="${color.name}"
                                data-color-image="${color.image || ''}">
                                <div class="aspect-[3/4] w-full overflow-hidden rounded border border-transparent transition group-hover:border-gray-400 ${index === 0 ? '' : ''}">
                                    ${color.image ? `<img src="${color.image}" class="h-full w-full object-cover">` : `<div class="h-full w-full" style="background-color: ${color.hex_code || '#ccc'}"></div>`}
                                </div>
                                <span class="mt-1 text-[10px] text-gray-600">${color.name}</span>
                            </div>
                        `).join('');
                    } else if (data.more_colors && data.more_colors.length > 0) {
                        // Fallback to related products as colors
                        colorsHtml = data.more_colors.map(p => `
                             <a href="/product/${p.slug}" class="cursor-pointer group flex flex-col items-center color-swatch">
                                <div class="aspect-[3/4] w-full overflow-hidden rounded border border-transparent transition group-hover:border-gray-400">
                                    <img src="${p.image}" class="h-full w-full object-cover">
                                </div>
                                <span class="mt-1 text-[10px] text-gray-600">${p.name}</span>
                            </a>
                        `).join('');
                    }

                    if (colorsHtml) {
                        // Only show More Colors if there are more than 1 color
                        if (data.colors && data.colors.length > 1) {
                            colorsWrapper.classList.remove('hidden');
                            colorsContainer.innerHTML = colorsHtml;

                            // Function to update More Colors visibility
                            function qvUpdateMoreColors(selectedColorId) {
                                const moreColorItems = document.querySelectorAll('.qv-more-color-item');
                                
                                moreColorItems.forEach(item => {
                                    const colorId = parseInt(item.getAttribute('data-color-id'));
                                    
                                    if (colorId === selectedColorId) {
                                        // Hide the selected color
                                        item.style.display = 'none';
                                    } else {
                                        // Show other colors
                                        item.style.display = 'flex';
                                    }
                                });
                            }
                            
                            // Hide initially selected color
                            if (initialColorId) {
                                qvUpdateMoreColors(initialColorId);
                            }

                            // Attach Event Listeners (Safe Scope)
                            document.querySelectorAll('#qv-more-colors .qv-color-swatch').forEach(el => {
                                el.addEventListener('click', function () {
                                    const colorId = parseInt(this.dataset.colorId);
                                    const colorName = this.dataset.colorName;
                                    const colorImage = this.dataset.colorImage;
                                    const qvImageSkeleton = document.getElementById('qv-image-skeleton');

                                    // Store Selection
                                    document.getElementById('qv-selected-color').value = colorId;
                                    document.getElementById('qv-selected-color-name').value = colorName;

                                // Show Skeleton
                                if (qvImageSkeleton) qvImageSkeleton.classList.remove('hidden');

                                // 1. Update Gallery
                                if (groupedImages[colorId] && groupedImages[colorId].length > 0) {
                                    window.qvRenderThumbnails(groupedImages[colorId]);
                                    // Hide skeleton after swiper initializes
                                    setTimeout(() => {
                                        if(qvImageSkeleton) qvImageSkeleton.classList.add('hidden');
                                    }, 200);
                                } else if (colorImage) {
                                    // If only single image, create array and render
                                    window.qvRenderThumbnails([colorImage]);
                                    setTimeout(() => {
                                        if(qvImageSkeleton) qvImageSkeleton.classList.add('hidden');
                                    }, 200);
                                } else {
                                    // No image, hide skeleton immediately
                                    if(qvImageSkeleton) qvImageSkeleton.classList.add('hidden');
                                }

                                // 2. Update Selection UI
                                document.querySelectorAll('.qv-color-swatch div').forEach(div => div.classList.remove('border-gray-800'));
                                this.querySelector('div').classList.add('border-gray-800');
                                
                                // 3. Update More Colors visibility
                                qvUpdateMoreColors(colorId);
                            });
                        });
                        } else {
                            // Only 1 color or fallback colors - hide the section
                            colorsWrapper.classList.add('hidden');
                        }
                    } else {
                        colorsWrapper.classList.add('hidden');
                    }

                    // Show Content, Hide Skeleton
                    qvSkeleton.classList.add('hidden');
                    qvContent.classList.remove('hidden');

                })
                .catch(err => {
                    console.error(err);
                    alert('Failed to load product details');
                    closeQuickView();
                });
        };
    });

    document.addEventListener('DOMContentLoaded', function () {
        // Quantity Logic
        const qtyInput = document.getElementById('qv-qty-input');
        const qtyMinusBtn = document.getElementById('qv-qty-minus');
        const qtyPlusBtn = document.getElementById('qv-qty-plus');

        if (qtyMinusBtn) {
            qtyMinusBtn.onclick = function () {
                let val = parseInt(qtyInput.value) || 1;
                if (val > 1) qtyInput.value = val - 1;
            };
        }

        if (qtyPlusBtn) {
            qtyPlusBtn.onclick = function () {
                let val = parseInt(qtyInput.value) || 1;
                qtyInput.value = val + 1;
            };
        }

        // Add to Cart / Buy Now Logic
        async function qvProcessCart(action) {
            const btn = action === 'buy_now' ? document.getElementById('qv-buy-now-btn') : document.getElementById('qv-add-to-cart-btn');
            const originalText = btn.innerHTML;

            btn.innerHTML = 'Processing...';
            btn.disabled = true;

            const productId = document.getElementById('qv-product-id').value;
            const qty = document.getElementById('qv-qty-input').value;
            const colorName = document.getElementById('qv-selected-color-name').value;
            // Note: If color is mandatory but not selected, backend might handle it or we check here.
            // For now, passing empty if not selected.

            try {
                const response = await fetch('{{ route("cart.add") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: qty,
                        color_name: colorName
                    })
                });

                const data = await response.json();

                if (data.success) {
                    if (action === 'buy_now') {
                        window.location.href = '{{ route("checkout") }}';
                    } else {
                        // Open Cart Drawer
                        if (typeof fetchCart === 'function') {
                            await fetchCart(); // Refresh drawer content

                            // Open drawer if closed
                            const drawer = document.getElementById('cartDrawer');
                            if (drawer && drawer.classList.contains('translate-x-full')) {
                                toggleCartDrawer();
                            }
                        } else {
                            // Fallback if drawer logic missing
                            alert('Added to cart successfully!');
                        }
                    }
                } else {
                    alert(data.message || 'Failed to add to cart');
                }
            } catch (error) {
                console.error(error);
                alert('Something went wrong');
            } finally {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        }

        const addToCartBtn = document.getElementById('qv-add-to-cart-btn');
        if (addToCartBtn) addToCartBtn.onclick = () => qvProcessCart('add');

        const buyNowBtn = document.getElementById('qv-buy-now-btn');
        if (buyNowBtn) buyNowBtn.onclick = () => qvProcessCart('buy_now');
    });
</script>