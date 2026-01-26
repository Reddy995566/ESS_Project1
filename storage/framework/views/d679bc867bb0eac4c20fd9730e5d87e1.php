

<?php $__env->startSection('title', 'Search Results for "' . $query . '"'); ?>

<?php $__env->startSection('content'); ?>

<!-- Search Results Page with Sidebar Filters -->
<div class="min-h-screen py-8 md:py-12 bg-white">
    <div class="max-w-[1800px] mx-auto px-4 md:px-8 lg:px-12">
        
        <div class="flex flex-col lg:flex-row gap-6">
            
            <!-- LEFT SIDEBAR: Filters (25% width on desktop) -->
            <aside class="lg:w-1/4 lg:sticky lg:top-[110px] lg:self-start" style="max-height: calc(100vh - 130px); overflow-y: auto;">
                <div class="bg-white rounded-lg p-4 md:p-6">
                    
                    <!-- Filter Header -->
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-[#441227]/10">
                        <h2 class="text-lg font-semibold text-[#441227]">Filters</h2>
                        <button id="clear-filters" class="text-xs text-[#441227]/60 hover:text-[#441227] underline hidden">
                            Clear All
                        </button>
                    </div>

                    <!-- Categories Filter -->
                    <div class="mb-6 pb-6 border-b border-[#441227]/10">
                        <h3 class="text-sm font-semibold text-[#441227] uppercase tracking-wide mb-3">Categories</h3>
                        <div class="space-y-2" id="categories-filter">
                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input 
                                        type="checkbox" 
                                        name="category" 
                                        value="<?php echo e($category->id); ?>"
                                        class="w-4 h-4 rounded border-gray-300 text-[#441227] focus:ring-[#441227] category-checkbox"
                                    >
                                    <span class="text-sm text-[#441227]/80 group-hover:text-[#441227]"><?php echo e($category->name); ?></span>
                                </label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    <!-- Price Range Filter -->
                    <div class="mb-6 pb-6 border-b border-[#441227]/10">
                        <h3 class="text-sm font-semibold text-[#441227] uppercase tracking-wide mb-3">Price</h3>
                        <p class="text-xs text-[#441227]/60 mb-4">Max: Rs. <?php echo e(number_format($maxPrice)); ?></p>
                        
                        <!-- Price Slider -->
                        <div id="price-slider" class="mb-6"></div>
                        
                        <!-- Price Inputs -->
                        <div class="flex items-center gap-2 mb-4">
                            <input 
                                type="number" 
                                id="min-price" 
                                placeholder="Min"
                                value="<?php echo e(request('min_price', 0)); ?>"
                                class="w-full text-sm border border-[#441227]/20 rounded px-3 py-2 focus:border-[#441227] focus:ring-1 focus:ring-[#441227]"
                            >
                            <span class="text-[#441227]/40">-</span>
                            <input 
                                type="number" 
                                id="max-price" 
                                placeholder="Max"
                                value="<?php echo e(request('max_price', $maxPrice)); ?>"
                                class="w-full text-sm border border-[#441227]/20 rounded px-3 py-2 focus:border-[#441227] focus:ring-1 focus:ring-[#441227]"
                            >
                        </div>
                        <button 
                            id="apply-price-btn"
                            class="w-full bg-[#441227] text-white text-sm py-2 rounded hover:bg-[#5C1F33] transition-colors"
                        >
                            Apply
                        </button>
                    </div>

                    <!-- noUiSlider CSS -->
                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.1/nouislider.min.css">
                    
                    <style>
                        /* Custom noUiSlider styling */
                        #price-slider {
                            height: 6px;
                        }
                        
                        #price-slider .noUi-connect {
                            background: #441227;
                        }
                        
                        #price-slider .noUi-handle {
                            width: 18px;
                            height: 18px;
                            border-radius: 50%;
                            border: 2px solid #441227;
                            background: white;
                            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
                            cursor: pointer;
                        }
                        
                        #price-slider .noUi-handle:before,
                        #price-slider .noUi-handle:after {
                            display: none;
                        }
                        
                        #price-slider .noUi-horizontal {
                            height: 6px;
                        }
                        
                        #price-slider .noUi-horizontal .noUi-handle {
                            width: 18px;
                            height: 18px;
                            right: -9px;
                            top: -6px;
                        }
                    </style>

                    <!-- Availability Filter -->
                    <div class="mb-6">
                        <h3 class="text-sm font-semibold text-[#441227] uppercase tracking-wide mb-3">Availability</h3>
                        <div class="space-y-2">
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input 
                                    type="radio" 
                                    name="availability" 
                                    value="all"
                                    class="w-4 h-4 border-gray-300 text-[#441227] focus:ring-[#441227]"
                                    checked
                                >
                                <span class="text-sm text-[#441227]/80 group-hover:text-[#441227]">All Products</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input 
                                    type="radio" 
                                    name="availability" 
                                    value="in_stock"
                                    class="w-4 h-4 border-gray-300 text-[#441227] focus:ring-[#441227]"
                                >
                                <span class="text-sm text-[#441227]/80 group-hover:text-[#441227]">In Stock</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input 
                                    type="radio" 
                                    name="availability" 
                                    value="out_of_stock"
                                    class="w-4 h-4 border-gray-300 text-[#441227] focus:ring-[#441227]"
                                >
                                <span class="text-sm text-[#441227]/80 group-hover:text-[#441227]">Out of Stock</span>
                            </label>
                        </div>
                    </div>

                </div>
            </aside>

            <!-- RIGHT CONTENT: Products (75% width on desktop) -->
            <main class="lg:w-3/4">
                
                <!-- Search Header -->
                <div class="mb-6 bg-white rounded-lg shadow-sm p-4 md:p-6">
                    <h1 class="text-2xl md:text-3xl font-semibold mb-2 text-gray-900">
                        Search Results for "<?php echo e($query); ?>"
                    </h1>
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-gray-600" id="product-count"><?php echo e($total); ?> <?php echo e($total == 1 ? 'product' : 'products'); ?> found</p>
                        
                        <!-- Sort Dropdown -->
                        <select id="sort-select" class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Sort By</option>
                            <option value="price_low">Price: Low to High</option>
                            <option value="price_high">Price: High to Low</option>
                            <option value="title-ascending">Name: A to Z</option>
                            <option value="title-descending">Name: Z to A</option>
                            <option value="created-descending">Newest First</option>
                        </select>
                    </div>
                </div>

                <!-- Products Grid -->
                <div id="products-container">
                    <?php if($products->count() > 0): ?>
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6">
                            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php echo $__env->make('website.partials.product-card', ['product' => $product], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <?php echo $__env->make('website.partials.no-products', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php endif; ?>
                </div>

                <!-- Loading Indicator -->
                <div id="loading-indicator" class="hidden text-center py-8">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-gray-900"></div>
                    <p class="mt-2 text-sm text-gray-600">Loading products...</p>
                </div>

            </main>

        </div>

    </div>
</div>

<!-- noUiSlider JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.1/nouislider.min.js"></script>

<!-- Fill search bar with query -->
<script>
// Fill header search bar with current search query
(function() {
    const searchQuery = '<?php echo e($query); ?>';
    const desktopSearchInput = document.getElementById('desktop-search-input');
    
    if (desktopSearchInput && searchQuery) {
        desktopSearchInput.value = searchQuery;
    }
})();
</script>

<!-- Vanilla JS for Filters -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const categoryCheckboxes = document.querySelectorAll('.category-checkbox');
    const minPriceInput = document.getElementById('min-price');
    const maxPriceInput = document.getElementById('max-price');
    const applyPriceBtn = document.getElementById('apply-price-btn');
    const availabilityRadios = document.querySelectorAll('input[name="availability"]');
    const sortSelect = document.getElementById('sort-select');
    const productsContainer = document.getElementById('products-container');
    const productCount = document.getElementById('product-count');
    const loadingIndicator = document.getElementById('loading-indicator');
    const clearFiltersBtn = document.getElementById('clear-filters');
    
    const searchQuery = '<?php echo e($query); ?>';
    const maxPrice = <?php echo e($maxPrice); ?>;
    const minPrice = 0;
    let filterTimeout;
    let priceFilterApplied = false; // Track if price filter was explicitly applied

    // Initialize noUiSlider
    const priceSlider = document.getElementById('price-slider');
    
    noUiSlider.create(priceSlider, {
        start: [
            <?php echo e(request('min_price', 0)); ?>, 
            <?php echo e(request('max_price', $maxPrice)); ?>

        ],
        connect: true,
        range: {
            'min': minPrice,
            'max': maxPrice
        },
        step: 100,
        format: {
            to: function(value) {
                return Math.round(value);
            },
            from: function(value) {
                return Number(value);
            }
        }
    });

    // Update inputs when slider changes
    priceSlider.noUiSlider.on('update', function(values, handle) {
        if (handle === 0) {
            minPriceInput.value = values[0];
        } else {
            maxPriceInput.value = values[1];
        }
    });

    // Update slider when inputs change
    minPriceInput.addEventListener('change', function() {
        priceSlider.noUiSlider.set([this.value, null]);
    });

    maxPriceInput.addEventListener('change', function() {
        priceSlider.noUiSlider.set([null, this.value]);
    });

    // Apply filters (for categories, availability, sort)
    function applyFilters() {
        clearTimeout(filterTimeout);
        filterTimeout = setTimeout(() => {
            fetchProducts();
        }, 500);
    }

    // Apply price filter on button click
    if (applyPriceBtn) {
        applyPriceBtn.addEventListener('click', function() {
            priceFilterApplied = true; // Mark that price filter was applied
            fetchProducts();
        });
    }

    // Get selected categories
    function getSelectedCategories() {
        const selected = [];
        categoryCheckboxes.forEach(checkbox => {
            if (checkbox.checked) {
                selected.push(checkbox.value);
            }
        });
        return selected;
    }

    // Get availability
    function getAvailability() {
        const selected = document.querySelector('input[name="availability"]:checked');
        return selected ? selected.value : 'all';
    }

    // Fetch products with AJAX
    function fetchProducts() {
        const categories = getSelectedCategories();
        const minPriceVal = priceFilterApplied ? minPriceInput.value : null;
        const maxPriceVal = priceFilterApplied ? maxPriceInput.value : null;
        const availability = getAvailability();
        const sort = sortSelect ? sortSelect.value : '';

        // Build URL
        const params = new URLSearchParams();
        params.append('q', searchQuery);
        params.append('ajax', '1');
        
        if (categories.length > 0) {
            params.append('categories', categories.join(','));
        }
        if (minPriceVal && minPriceVal > 0) params.append('min_price', minPriceVal);
        if (maxPriceVal && maxPriceVal < maxPrice) params.append('max_price', maxPriceVal);
        if (availability !== 'all') params.append('availability', availability);
        if (sort) params.append('sort', sort);

        const url = `<?php echo e(route('search')); ?>?${params.toString()}`;
        console.log('Fetching URL:', url);
        console.log('Filters:', { categories, minPriceVal, maxPriceVal, availability, sort });
        console.log('Full URL with params:', url);

        // Show loading
        loadingIndicator.classList.remove('hidden');
        productsContainer.style.opacity = '0.5';

        // Fetch
        fetch(url)
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                updateProducts(data.html);
                updateProductCount(data.total);
                updateClearButton();
                
                // Hide loading
                loadingIndicator.classList.add('hidden');
                productsContainer.style.opacity = '1';
            })
            .catch(error => {
                console.error('Filter error:', error);
                loadingIndicator.classList.add('hidden');
                productsContainer.style.opacity = '1';
            });
    }

    // Update products HTML
    function updateProducts(html) {
        productsContainer.innerHTML = html;
    }

    // Update product count
    function updateProductCount(total) {
        const text = total === 1 ? 'product' : 'products';
        productCount.textContent = `${total} ${text} found`;
    }

    // Update clear button visibility
    function updateClearButton() {
        const hasFilters = getSelectedCategories().length > 0 || 
                          priceFilterApplied || 
                          getAvailability() !== 'all' ||
                          (sortSelect && sortSelect.value);
        
        if (hasFilters) {
            clearFiltersBtn.classList.remove('hidden');
        } else {
            clearFiltersBtn.classList.add('hidden');
        }
    }

    // Clear all filters
    clearFiltersBtn.addEventListener('click', function() {
        categoryCheckboxes.forEach(cb => cb.checked = false);
        priceSlider.noUiSlider.set([minPrice, maxPrice]);
        priceFilterApplied = false; // Reset price filter flag
        document.querySelector('input[name="availability"][value="all"]').checked = true;
        if (sortSelect) sortSelect.value = '';
        applyFilters();
    });

    // Event listeners
    categoryCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', applyFilters);
    });

    availabilityRadios.forEach(radio => {
        radio.addEventListener('change', applyFilters);
    });

    if (sortSelect) {
        sortSelect.addEventListener('change', applyFilters);
    }
    
    // Initial check for clear button
    updateClearButton();
});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('website.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\ESS_Project1\resources\views/website/search.blade.php ENDPATH**/ ?>