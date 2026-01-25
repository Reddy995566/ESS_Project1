@extends('website.layouts.master')

@section('title', 'Collection - ' . ($siteSettings['site_name'] ?? 'Fashion Store'))

@section('content')

<main class="w-full pt-8 pb-16 min-h-screen bg-white">
    <div class="max-w-[1800px] mx-auto px-4 md:px-8 lg:px-12">

        <!-- Mobile Filter Button -->
        <div class="md:hidden mb-4 flex items-center justify-between">
            <p class="text-[#441227] text-sm font-normal">{{ $products->total() }} products</p>
            <button onclick="toggleMobileFilters()" 
                class="flex items-center gap-2 px-4 py-2 bg-[#441227] text-white rounded text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4">
                    </path>
                </svg>
                Filters
            </button>
        </div>

        <!-- Desktop Layout: LEFT Sidebar + RIGHT Content -->
        <div class="flex flex-col md:flex-row gap-6 lg:gap-8">
            
            <!-- LEFT SIDEBAR - DESKTOP (Sticky) -->
            <aside class="hidden md:block w-full md:w-1/4 lg:w-1/5">
                <div class="sticky top-[110px] max-h-[calc(100vh-130px)] overflow-y-auto pr-2">
                    
                    <!-- Filter Header -->
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-[#441227]/10">
                        <h2 class="text-lg font-semibold text-[#441227]">Filters</h2>
                        <a href="{{ route('shop') }}" class="text-xs text-[#441227]/60 hover:text-[#441227] underline">Clear All</a>
                    </div>

                    <!-- Categories Filter -->
                    @if($categories->count() > 0)
                    <div class="mb-6 pb-6 border-b border-[#441227]/10">
                        <h3 class="text-sm font-semibold text-[#441227] uppercase tracking-wide mb-3">Categories</h3>
                        <div class="space-y-2">
                            @foreach($categories as $category)
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="checkbox" name="category[]" value="{{ $category->slug }}" 
                                       class="category-checkbox form-checkbox w-4 h-4 text-[#441227] rounded border-gray-300 focus:ring-[#441227]"
                                       onchange="toggleCategory('{{ $category->slug }}', this.checked)">
                                <span class="text-sm text-[#441227]/80 group-hover:text-[#441227]">{{ $category->name }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Price Range Filter -->
                    <div class="mb-6 pb-6 border-b border-[#441227]/10">
                        <h3 class="text-sm font-semibold text-[#441227] uppercase tracking-wide mb-3">Price</h3>
                        <p class="text-xs text-[#441227]/60 mb-3">Max: Rs. {{ number_format($maxPrice) }}</p>
                        <div class="flex items-center gap-2 mb-4">
                            <input type="number" id="min_price_desktop" value="{{ request('min_price', $minPrice) }}" 
                                   placeholder="Min"
                                   class="w-full text-sm border border-[#441227]/20 rounded px-3 py-2 focus:border-[#441227] focus:ring-1 focus:ring-[#441227]">
                            <span class="text-[#441227]/40">-</span>
                            <input type="number" id="max_price_desktop" value="{{ request('max_price', $maxPrice) }}"
                                   placeholder="Max"
                                   class="w-full text-sm border border-[#441227]/20 rounded px-3 py-2 focus:border-[#441227] focus:ring-1 focus:ring-[#441227]">
                        </div>
                        
                        <button onclick="applyPriceFilter('desktop')" 
                            class="w-full bg-[#441227] text-white text-sm py-2 rounded hover:bg-[#5C1F33] transition-colors">
                            Apply
                        </button>
                    </div>

                    <!-- Availability Filter -->
                    <div class="mb-6">
                        <h3 class="text-sm font-semibold text-[#441227] uppercase tracking-wide mb-3">Availability</h3>
                        <div class="space-y-2">
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="checkbox" name="availability" value="in_stock" 
                                       {{ request('availability') == 'in_stock' ? 'checked' : '' }}
                                       onchange="updateFilter('availability', this.checked ? 'in_stock' : '')"
                                       class="form-checkbox w-4 h-4 text-[#441227] rounded border-gray-300 focus:ring-[#441227]">
                                <span class="text-sm text-[#441227]/80 group-hover:text-[#441227]">In Stock ({{ $inStockCount }})</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="checkbox" name="availability" value="out_of_stock"
                                       {{ request('availability') == 'out_of_stock' ? 'checked' : '' }}
                                       onchange="updateFilter('availability', this.checked ? 'out_of_stock' : '')"
                                       class="form-checkbox w-4 h-4 text-[#441227] rounded border-gray-300 focus:ring-[#441227]">
                                <span class="text-sm text-[#441227]/80 group-hover:text-[#441227]">Out of Stock ({{ $outStockCount }})</span>
                            </label>
                        </div>
                    </div>

                </div>
            </aside>

            <!-- RIGHT CONTENT AREA -->
            <div class="w-full md:w-3/4 lg:w-4/5">
                
                <!-- Top Bar -->
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-[#441227]/10">
                    <p id="product-count" class="text-[#441227] text-sm md:text-base font-normal">{{ $products->total() }} products</p>
                    
                    <!-- Sort Dropdown -->
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open"
                            class="flex items-center gap-2 px-4 py-2 bg-white border border-[#441227]/30 rounded text-[#441227] text-sm hover:border-[#441227] transition-colors">
                            Sort by
                            <svg class="w-3 h-3 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        
                        <div x-show="open" style="display: none;"
                             class="absolute top-full right-0 mt-2 w-56 bg-white border border-[#441227]/10 rounded shadow-lg z-30 py-1">
                             @foreach([
                                 'title-ascending' => 'Alphabetically, A-Z',
                                 'title-descending' => 'Alphabetically, Z-A',
                                 'price_low' => 'Price, low to high',
                                 'price_high' => 'Price, high to low',
                                 'oldest' => 'Date, old to new',
                                 'newest' => 'Date, new to old',
                             ] as $key => $label)
                             <a href="javascript:void(0)" onclick="updateFilter('sort', '{{ $key }}')" 
                                class="block px-4 py-2 text-sm text-[#441227] hover:bg-[#FAF5ED] {{ $sort == $key ? 'font-bold bg-[#FAF5ED]' : '' }}">
                                {{ $label }}
                             </a>
                             @endforeach
                        </div>
                    </div>
                </div>

                <!-- Products Grid -->
                <div id="products-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                    @forelse($products as $product)
                    <div class="flex flex-col group items-center">
                        <div class="relative w-full h-auto rounded-lg overflow-hidden bg-gray-100 group">
                            
                            <div class="swiper product-swiper w-full h-auto">
                                <div class="swiper-wrapper">
                                    @php $displayImages = $product->display_images; @endphp
                                    @if(count($displayImages) > 0)
                                        @foreach($displayImages as $img)
                                        <div class="swiper-slide">
                                            <a href="{{ route('product.show', $product->slug) }}" class="block w-full h-auto">
                                                <img src="{{ $img }}" alt="{{ $product->name }}" class="w-full h-auto object-cover aspect-[3/4]" loading="lazy" />
                                            </a>
                                        </div>
                                        @endforeach
                                    @else
                                        <div class="swiper-slide">
                                            <a href="{{ route('product.show', $product->slug) }}" class="block w-full h-auto">
                                                <img src="{{ $product->image_url ?? asset('website/assets/images/placeholder.jpg') }}" alt="{{ $product->name }}" class="w-full h-auto object-cover aspect-[3/4]" />
                                            </a>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="swiper-button-next !w-6 !h-6 !text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300 drop-shadow-md" style="--swiper-navigation-size: 20px;"></div>
                                <div class="swiper-button-prev !w-6 !h-6 !text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300 drop-shadow-md" style="--swiper-navigation-size: 20px;"></div>
                            </div>

                            @if($product->compare_price && $product->compare_price > $product->price)
                            <div class="absolute top-2 right-2 md:top-3 md:right-3 z-10 bg-[#441227] text-white text-[10px] font-bold px-2 py-1 uppercase tracking-wider">
                                SAVE RS. {{ number_format($product->compare_price - $product->price) }}
                            </div>
                            @endif

                            <button onclick="addToWishlist({{ $product->id }})" data-product-id="{{ $product->id }}" class="absolute bottom-2 right-2 md:bottom-3 md:right-3 z-10 text-white text-lg md:text-xl p-2 rounded-full hover:bg-white/20 transition-colors">
                                <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            </button>
                        </div>

                        <div class="text-center mt-2 md:mt-3 pb-2">
                            <a href="{{ route('product.show', $product->slug) }}">
                                <h3 class="text-[#441227] font-medium text-sm md:text-lg hover:text-[#5C1F33] transition-colors truncate px-1 md:px-2">{{ $product->name }}</h3>
                            </a>
                            <div class="flex items-center justify-center gap-1 md:gap-2 mt-1">
                                <p class="text-[#441227]/80 text-xs md:text-sm font-medium">Rs. {{ number_format($product->price) }}</p>
                                @if($product->compare_price > $product->price)
                                    <p class="text-gray-400 text-[10px] md:text-xs line-through">Rs. {{ number_format($product->compare_price) }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full text-center py-20 text-gray-500">
                        <p class="text-xl">No products found.</p>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="mt-12 flex justify-center">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            </div>

        </div>
    </div>
</main>

<!-- Mobile Filter Drawer -->
<div id="mobile-filter-drawer" class="fixed inset-0 z-50 transform translate-x-full transition-transform duration-300 ease-in-out hidden">
    <div class="absolute inset-0 bg-black/50" onclick="toggleMobileFilters()"></div>
    <div class="absolute top-0 left-0 h-full w-full max-w-[320px] bg-white shadow-xl overflow-y-auto">
        <div class="p-5">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-[#441227]/10">
                <h2 class="text-xl font-semibold text-[#441227]">Filters</h2>
                <button onclick="toggleMobileFilters()" class="text-[#441227]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            @if($categories->count() > 0)
            <div class="mb-6 pb-6 border-b border-[#441227]/10">
                <h3 class="text-sm font-semibold text-[#441227] uppercase tracking-wide mb-3">Categories</h3>
                <div class="space-y-3">
                    @foreach($categories as $category)
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" name="category_mobile[]" value="{{ $category->slug }}" 
                               class="category-checkbox form-checkbox w-5 h-5 text-[#441227] rounded border-gray-300 focus:ring-[#441227]"
                               onchange="toggleCategory('{{ $category->slug }}', this.checked)">
                        <span class="text-sm text-[#441227]/80 group-hover:text-[#441227]">{{ $category->name }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="mb-6 pb-6 border-b border-[#441227]/10">
                <h3 class="text-sm font-semibold text-[#441227] uppercase tracking-wide mb-3">Price</h3>
                <p class="text-xs text-[#441227]/60 mb-3">Max: Rs. {{ number_format($maxPrice) }}</p>
                <div class="flex items-center gap-2 mb-4">
                    <input type="number" id="min_price_mobile" value="{{ request('min_price', $minPrice) }}" 
                           placeholder="Min"
                           class="w-full text-sm border border-[#441227]/20 rounded px-3 py-2 focus:border-[#441227] focus:ring-1 focus:ring-[#441227]">
                    <span class="text-[#441227]/40">-</span>
                    <input type="number" id="max_price_mobile" value="{{ request('max_price', $maxPrice) }}"
                           placeholder="Max"
                           class="w-full text-sm border border-[#441227]/20 rounded px-3 py-2 focus:border-[#441227] focus:ring-1 focus:ring-[#441227]">
                </div>
                
                <button onclick="applyPriceFilter('mobile')" 
                    class="w-full bg-[#441227] text-white text-sm py-2 rounded hover:bg-[#5C1F33] transition-colors">
                    Apply
                </button>
            </div>

            <div class="mb-6">
                <h3 class="text-sm font-semibold text-[#441227] uppercase tracking-wide mb-3">Availability</h3>
                <div class="space-y-3">
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" name="availability_mobile" value="in_stock" 
                               {{ request('availability') == 'in_stock' ? 'checked' : '' }}
                               onchange="updateFilter('availability', this.checked ? 'in_stock' : '')"
                               class="form-checkbox w-5 h-5 text-[#441227] rounded border-gray-300 focus:ring-[#441227]">
                        <span class="text-sm text-[#441227]/80 group-hover:text-[#441227]">In Stock ({{ $inStockCount }})</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" name="availability_mobile" value="out_of_stock"
                               {{ request('availability') == 'out_of_stock' ? 'checked' : '' }}
                               onchange="updateFilter('availability', this.checked ? 'out_of_stock' : '')"
                               class="form-checkbox w-5 h-5 text-[#441227] rounded border-gray-300 focus:ring-[#441227]">
                        <span class="text-sm text-[#441227]/80 group-hover:text-[#441227]">Out of Stock ({{ $outStockCount }})</span>
                    </label>
                </div>
            </div>

            <div class="mt-8 pt-4 border-t border-[#441227]/10 space-y-3">
                <a href="{{ route('shop') }}" class="block w-full text-center bg-white border border-[#441227] text-[#441227] py-2.5 rounded font-medium hover:bg-[#FAF5ED] transition-colors">
                    Clear all filters
                </a>
                <button onclick="toggleMobileFilters()" class="block w-full text-center bg-[#441227] text-white py-2.5 rounded font-medium hover:bg-[#5C1F33] transition-colors">
                    View products
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Multi-filter state management
let activeFilters = {
    categories: [], // Array for multiple categories
    availability: null,
    min_price: null,
    max_price: null,
    sort: '{{ $sort ?? "newest" }}',
    collection: '{{ request("collection") }}'
};

// Initialize filters from URL on page load
function initializeFilters() {
    const urlParams = new URLSearchParams(window.location.search);
    
    // Get categories from URL (can be multiple)
    const categoriesParam = urlParams.get('categories');
    if (categoriesParam) {
        activeFilters.categories = categoriesParam.split(',');
    }
    
    // Get collection from URL
    if (urlParams.get('collection')) {
        activeFilters.collection = urlParams.get('collection');
    }
    
    // Get availability
    if (urlParams.get('availability')) {
        activeFilters.availability = urlParams.get('availability');
    }
    
    // Get price range
    if (urlParams.get('min_price')) {
        activeFilters.min_price = urlParams.get('min_price');
    }
    if (urlParams.get('max_price')) {
        activeFilters.max_price = urlParams.get('max_price');
    }
    
    // Get sort
    if (urlParams.get('sort')) {
        activeFilters.sort = urlParams.get('sort');
    }
}

// Toggle category in multi-select
function toggleCategory(slug, isChecked) {
    if (isChecked) {
        // Add category if not already present
        if (!activeFilters.categories.includes(slug)) {
            activeFilters.categories.push(slug);
        }
    } else {
        // Remove category
        activeFilters.categories = activeFilters.categories.filter(cat => cat !== slug);
    }
    
    updateURLAndFetch();
}

// Update filter and fetch products
function updateFilter(key, value) {
    if (key === 'availability') {
        activeFilters.availability = value;
    } else if (key === 'sort') {
        activeFilters.sort = value;
    }
    
    // Update URL and fetch
    updateURLAndFetch();
}

// Apply price filter
function applyPriceFilter(device) {
    const min = document.getElementById(`min_price_${device}`).value;
    const max = document.getElementById(`max_price_${device}`).value;
    
    activeFilters.min_price = min || null;
    activeFilters.max_price = max || null;
    
    updateURLAndFetch();
    
    if (device === 'mobile') {
        setTimeout(() => toggleMobileFilters(), 300);
    }
}

// Update URL and fetch products via AJAX
function updateURLAndFetch() {
    const url = new URL(window.location.href);
    
    // Clear all params first
    url.search = '';
    
    // Add active filters
    if (activeFilters.collection) url.searchParams.set('collection', activeFilters.collection);
    
    // Add multiple categories as comma-separated
    if (activeFilters.categories.length > 0) {
        url.searchParams.set('categories', activeFilters.categories.join(','));
    }
    
    if (activeFilters.availability) url.searchParams.set('availability', activeFilters.availability);
    if (activeFilters.min_price) url.searchParams.set('min_price', activeFilters.min_price);
    if (activeFilters.max_price) url.searchParams.set('max_price', activeFilters.max_price);
    if (activeFilters.sort) url.searchParams.set('sort', activeFilters.sort);
    
    // Update browser URL without reload
    window.history.pushState({}, '', url.toString());
    
    // Fetch filtered products
    fetchFilteredProducts(url.toString());
}

// Fetch products via AJAX
function fetchFilteredProducts(url) {
    const productsGrid = document.getElementById('products-grid');
    const productCount = document.getElementById('product-count');
    
    // Show loading state
    productsGrid.style.opacity = '0.5';
    productsGrid.style.pointerEvents = 'none';
    
    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'text/html'
        }
    })
    .then(response => response.text())
    .then(html => {
        // Parse the HTML response
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        
        // Update products grid
        const newGrid = doc.getElementById('products-grid');
        if (newGrid) {
            productsGrid.innerHTML = newGrid.innerHTML;
        }
        
        // Update product count
        const newCount = doc.getElementById('product-count');
        if (newCount && productCount) {
            productCount.textContent = newCount.textContent;
        }
        
        // Re-initialize Swiper for new products
        initProductSwipers();
        
        // Remove loading state
        productsGrid.style.opacity = '1';
        productsGrid.style.pointerEvents = 'auto';
        
        // Scroll to top of products
        window.scrollTo({ top: 0, behavior: 'smooth' });
    })
    .catch(error => {
        console.error('Error fetching products:', error);
        productsGrid.style.opacity = '1';
        productsGrid.style.pointerEvents = 'auto';
    });
}

// Toggle mobile filters
function toggleMobileFilters() {
    const drawer = document.getElementById('mobile-filter-drawer');
    if (drawer.classList.contains('hidden')) {
        drawer.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        setTimeout(() => drawer.classList.remove('translate-x-full'), 10);
    } else {
        drawer.classList.add('translate-x-full');
        document.body.classList.remove('overflow-hidden');
        setTimeout(() => drawer.classList.add('hidden'), 300);
    }
}

// Initialize Swiper for product images
function initProductSwipers() {
    document.querySelectorAll('.product-swiper').forEach(swiperEl => {
        if (swiperEl.swiper) return;

        const swiper = new Swiper(swiperEl, {
            slidesPerView: 1,
            spaceBetween: 0,
            loop: true,
            autoplay: {
                delay: 1500,
                disableOnInteraction: false,
                enabled: false
            },
            navigation: {
                nextEl: swiperEl.querySelector('.swiper-button-next'),
                prevEl: swiperEl.querySelector('.swiper-button-prev'),
            },
        });

        swiperEl.parentElement.addEventListener('mouseenter', () => swiper.autoplay.start());
        swiperEl.parentElement.addEventListener('mouseleave', () => swiper.autoplay.stop());
    });
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    initializeFilters();
    initProductSwipers();
});
</script>
@endpush
