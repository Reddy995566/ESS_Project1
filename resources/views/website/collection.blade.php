@extends('website.layouts.master')

@section('title', 'Collection - ' . ($siteSettings['site_name'] ?? 'The Trusted Store'))

@section('content')

    <!-- ================================
                 COLLECTION CONTENT
                 ================================ -->

    <main class="w-full pt-8 pb-16 min-h-screen">
        <div class="max-w-[1800px] mx-auto px-4 md:px-8 lg:px-12">

            <!-- Filters & Sort Bar -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <!-- Count -->
                <p id="product-count" class="text-[#441227] text-sm md:text-base font-normal">{{ $products->total() }} products</p>

                <!-- Filter Buttons - Desktop -->
                <div class="hidden md:flex flex-wrap items-center gap-3">
                    
                    <!-- Availability Dropdown -->
                    <div class="relative group" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open"
                            class="flex items-center gap-2 px-4 py-2 bg-transparent border border-[#441227]/30 rounded text-[#441227] text-sm hover:border-[#441227] transition-colors"
                            :class="{ 'bg-[#FAF5ED]': open }">
                            Availability
                            <svg class="w-3 h-3 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        
                        <!-- Dropdown Content -->
                        <div x-show="open" 
                             style="display: none;"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute top-full left-0 mt-2 w-56 bg-white border border-[#441227]/10 rounded shadow-lg z-30 p-4">
                             
                             <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-[#441227]">0 selected</span>
                                <button type="button" onclick="updateFilter('availability', '')" class="text-xs text-[#441227]/60 underline">Reset</button>
                             </div>
                             
                             <div class="space-y-2 border-t border-[#441227]/10 pt-2">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="availability" value="in_stock" 
                                           {{ request('availability') == 'in_stock' ? 'checked' : '' }}
                                           onchange="updateFilter('availability', this.checked ? 'in_stock' : '')"
                                           class="form-checkbox text-[#441227] rounded border-gray-300 focus:ring-[#441227]">
                                    <span class="text-sm text-[#441227]">In stock ({{ $inStockCount }})</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="availability" value="out_of_stock"
                                           {{ request('availability') == 'out_of_stock' ? 'checked' : '' }}
                                           onchange="updateFilter('availability', this.checked ? 'out_of_stock' : '')"
                                           class="form-checkbox text-[#441227] rounded border-gray-300 focus:ring-[#441227]">
                                    <span class="text-sm text-[#441227]">Out of stock ({{ $outStockCount }})</span>
                                </label>
                             </div>
                        </div>
                    </div>

                    <!-- Price Dropdown -->
                    <div class="relative group" id="price-dropdown">
                        <button onclick="togglePriceDropdown()"
                            id="price-dropdown-btn"
                            class="flex items-center gap-2 px-4 py-2 bg-transparent border border-[#441227]/30 rounded text-[#441227] text-sm hover:border-[#441227] transition-colors">
                            Price
                            <svg id="price-dropdown-arrow" class="w-3 h-3 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                         <!-- Dropdown Content -->
                         <div id="price-dropdown-content"
                              style="display: none;"
                             class="absolute top-full left-0 mt-2 w-80 bg-white border border-[#441227]/10 rounded shadow-lg z-30 p-4">
                             
                             <div class="flex items-center justify-between mb-4">
                                <span class="text-sm font-medium text-[#441227]">The highest price is Rs. {{ number_format($maxPrice) }}</span>
                                <button type="button" onclick="updatePriceFilter('', '')" class="text-xs text-[#441227]/60 underline">Reset</button>
                             </div>
                             
                             <div class="flex items-center gap-4 mb-6">
                                 <div class="w-1/2">
                                     <label class="text-xs text-[#441227]/60 block mb-1">Rs.</label>
                                     <input type="number" id="min_price_input" value="{{ request('min_price', $minPrice) }}" 
                                            class="w-full text-sm border border-[#441227]/20 rounded px-2 py-1 text-right"
                                            oninput="syncSlider('min')">
                                 </div>
                                 <div class="w-1/2">
                                     <label class="text-xs text-[#441227]/60 block mb-1">Rs.</label>
                                     <input type="number" id="max_price_input" value="{{ request('max_price', $maxPrice) }}"
                                            class="w-full text-sm border border-[#441227]/20 rounded px-2 py-1 text-right"
                                            oninput="syncSlider('max')">
                                 </div>
                             </div>

                             <!-- Visual Slider -->
                             <div class="relative w-full h-1 bg-gray-200 rounded mb-6">
                                 <div id="slider-track" class="absolute h-full bg-[#441227] rounded"></div>
                                 <input type="range" id="min_range" min="{{ $minPrice }}" max="{{ $maxPrice }}" step="1" 
                                        value="{{ request('min_price', $minPrice) }}" 
                                        class="absolute w-full h-1 appearance-none bg-transparent z-10 slider-thumb"
                                        oninput="syncInputs('min')">
                                 <input type="range" id="max_range" min="{{ $minPrice }}" max="{{ $maxPrice }}" step="1" 
                                        value="{{ request('max_price', $maxPrice) }}" 
                                        class="absolute w-full h-1 appearance-none bg-transparent z-10 slider-thumb"
                                        oninput="syncInputs('max')">
                             </div>

                             <!-- Apply Button -->
                             <button onclick="applyPriceFilter()" class="w-full bg-[#441227] text-white text-sm py-2 rounded">Apply</button>
                        </div>
                    </div>

                    <!-- Show all / Filters Sidebar Trigger -->
                    <div class="relative group">
                        <button onclick="toggleFilterSidebar()"
                            class="flex items-center gap-2 px-4 py-2 bg-transparent border border-[#441227]/30 rounded text-[#441227] text-sm hover:border-[#441227]">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4">
                                </path>
                            </svg>
                            Show all
                        </button>
                    </div>

                    <!-- Sort by -->
                    <div class="relative group" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open"
                            class="flex items-center gap-2 px-4 py-2 bg-transparent border border-[#441227]/30 rounded text-[#441227] text-sm hover:border-[#441227] transition-colors"
                            :class="{ 'bg-[#FAF5ED]': open }">
                            Sort by
                            <svg class="w-3 h-3 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        
                        <!-- Dropdown Content -->
                        <div x-show="open" 
                            style="display: none;"
                             class="absolute top-full right-0 mt-2 w-56 bg-white border border-[#441227]/10 rounded shadow-lg z-30 py-1">
                             
                             <!-- Categories Section -->
                             @if($categories->count() > 0)
                             <div class="px-4 py-2 text-xs font-semibold text-[#441227]/60 uppercase tracking-wider">Categories</div>
                             @foreach($categories as $category)
                             <a href="javascript:void(0)" onclick="updateFilter('category', '{{ $category->slug }}')" 
                                class="block px-4 py-2 text-sm text-[#441227] hover:bg-[#FAF5ED] {{ request('category') == $category->slug ? 'font-bold bg-[#FAF5ED]' : '' }}">
                                {{ $category->name }}
                             </a>
                             @endforeach
                             <div class="border-t border-[#441227]/10 my-1"></div>
                             @endif
                             
                             <!-- Sort Options -->
                             <div class="px-4 py-2 text-xs font-semibold text-[#441227]/60 uppercase tracking-wider">Sort By</div>
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

                <!-- Mobile Filter & Sort Buttons -->
                <div class="flex md:hidden items-center gap-2">
                    <button onclick="toggleFilterSidebar()"
                        class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 bg-transparent border border-[#441227]/30 rounded text-[#441227] text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4">
                            </path>
                        </svg>
                        Filter
                    </button>
                    <button onclick="toggleSortSidebar()"
                        class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 bg-transparent border border-[#441227]/30 rounded text-[#441227] text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"></path>
                        </svg>
                        Sort
                    </button>
                </div>
            </div>

            <!-- Skeleton Loader (Hidden by default) -->
            <div id="skeleton-loader" class="hidden grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-3 md:gap-8">
                @for($i = 0; $i < 6; $i++)
                <div class="flex flex-col group items-center animate-pulse">
                    <div class="relative w-full h-auto rounded-lg overflow-hidden bg-gray-200">
                        <div class="w-full aspect-[3/4] bg-gray-300"></div>
                    </div>
                    <div class="text-center mt-3 pb-2 w-full">
                        <div class="h-4 md:h-5 bg-gray-200 rounded w-3/4 mx-auto mb-2"></div>
                        <div class="h-3 md:h-4 bg-gray-200 rounded w-1/2 mx-auto"></div>
                    </div>
                </div>
                @endfor
            </div>

            <!-- Products Grid -->
            <div id="products-grid" class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-3 md:gap-8">
                @forelse($products as $product)
                <div class="flex flex-col group items-center">
                    <div class="relative w-full h-auto rounded-lg overflow-hidden bg-gray-100 group">
                        
                        <!-- Product Image Slider -->
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
                            
                            <!-- Slider Navigation Buttons (Visible on Hover) -->
                            <div class="swiper-button-next !w-6 !h-6 !text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300 drop-shadow-md" style="--swiper-navigation-size: 20px;"></div>
                            <div class="swiper-button-prev !w-6 !h-6 !text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300 drop-shadow-md" style="--swiper-navigation-size: 20px;"></div>
                        </div>

                        <!-- Badge -->
                        @if($product->compare_price && $product->compare_price > $product->price)
                        <div class="absolute top-2 right-2 md:top-3 md:right-3 z-10 bg-[#441227] text-white text-[10px] font-bold px-2 py-1 uppercase tracking-wider">
                            SAVE RS. {{ number_format($product->compare_price - $product->price) }}
                        </div>
                        @endif

                        <!-- Wishlist Button -->
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
                    <p class="text-xl">No products found in this collection.</p>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-12 flex justify-center">
                {{ $products->appends(request()->query())->links() }}
            </div>
        </div>
    </main>

    <!-- Filter Sidebar (Off-canvas) -->
    <div id="filter-sidebar" class="fixed inset-0 z-50 transform translate-x-full transition-transform duration-300 ease-in-out hidden">
        <div class="absolute inset-0 bg-black/50" onclick="toggleFilterSidebar()"></div>
        <div class="absolute top-0 right-0 h-full w-full max-w-[320px] bg-[#FAF5ED] shadow-xl overflow-y-auto p-5 relative z-10">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-medium text-[#441227]">Filters</h2>
                <button onclick="toggleFilterSidebar()" class="text-[#441227]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <!-- Mobile Filters Content -->
            <div class="space-y-6">
                 <!-- Availability -->
                 <div>
                     <h3 class="font-medium text-[#441227] mb-3">Availability</h3>
                     <div class="space-y-3">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="availability_mobile" value="in_stock" 
                                   {{ request('availability') == 'in_stock' ? 'checked' : '' }}
                                   onchange="updateFilter('availability', this.checked ? 'in_stock' : '')"
                                   class="form-checkbox w-5 h-5 text-[#441227] rounded border-gray-300 focus:ring-[#441227]">
                            <span class="text-sm text-[#441227]">In stock ({{ $inStockCount }})</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="availability_mobile" value="out_of_stock"
                                   {{ request('availability') == 'out_of_stock' ? 'checked' : '' }}
                                   onchange="updateFilter('availability', this.checked ? 'out_of_stock' : '')"
                                   class="form-checkbox w-5 h-5 text-[#441227] rounded border-gray-300 focus:ring-[#441227]">
                            <span class="text-sm text-[#441227]">Out of stock ({{ $outStockCount }})</span>
                        </label>
                     </div>
                 </div>

                 <!-- Price -->
                 <div>
                     <h3 class="font-medium text-[#441227] mb-3">Price</h3>
                     <p class="text-xs text-[#441227]/60 mb-3">The highest price is Rs. {{ number_format($maxPrice) }}</p>
                     <div class="flex items-center gap-3 mb-4">
                         <div class="flex-1">
                             <label class="text-xs text-[#441227]/60 block mb-1">From</label>
                             <input type="number" id="min_price_mobile" value="{{ request('min_price', $minPrice) }}" 
                                    class="w-full text-sm border border-[#441227]/20 rounded px-3 py-2">
                         </div>
                         <div class="flex-1">
                             <label class="text-xs text-[#441227]/60 block mb-1">To</label>
                             <input type="number" id="max_price_mobile" value="{{ request('max_price', $maxPrice) }}"
                                    class="w-full text-sm border border-[#441227]/20 rounded px-3 py-2">
                         </div>
                     </div>
                     <button onclick="applyPriceFilterMobile()" class="w-full bg-[#441227] text-white text-sm py-2.5 rounded">Apply Price</button>
                 </div>

                 <!-- Categories -->
                 @if($categories->count() > 0)
                 <div>
                     <h3 class="font-medium text-[#441227] mb-3">Categories</h3>
                     <div class="space-y-2">
                        @foreach($categories as $category)
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="radio" name="category_mobile" value="{{ $category->slug }}" 
                                   {{ request('category') == $category->slug ? 'checked' : '' }}
                                   onchange="updateFilter('category', '{{ $category->slug }}')"
                                   class="form-radio w-4 h-4 text-[#441227] border-gray-300 focus:ring-[#441227]">
                            <span class="text-sm text-[#441227]">{{ $category->name }}</span>
                        </label>
                        @endforeach
                     </div>
                 </div>
                 @endif
            </div>

            <div class="mt-8 border-t border-[#441227]/10 pt-4 space-y-3">
                 <a href="{{ route('shop') }}" class="block w-full text-center bg-[#441227]/10 text-[#441227] py-2.5 rounded font-medium">Clear all filters</a>
                 <button onclick="toggleFilterSidebar()" class="block w-full text-center bg-[#441227] text-white py-2.5 rounded font-medium">View products</button>
            </div>
        </div>
    </div>

    <!-- Sort Sidebar (Off-canvas) - Mobile -->
    <div id="sort-sidebar" class="fixed inset-0 z-50 transform translate-x-full transition-transform duration-300 ease-in-out hidden">
        <div class="absolute inset-0 bg-black/50" onclick="toggleSortSidebar()"></div>
        <div class="absolute top-0 right-0 h-full w-full max-w-[320px] bg-[#FAF5ED] shadow-xl overflow-y-auto p-5 relative z-10">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-medium text-[#441227]">Sort By</h2>
                <button onclick="toggleSortSidebar()" class="text-[#441227]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <!-- Sort Options -->
            <div class="space-y-1">
                @foreach([
                    'title-ascending' => 'Alphabetically, A-Z',
                    'title-descending' => 'Alphabetically, Z-A',
                    'price_low' => 'Price, low to high',
                    'price_high' => 'Price, high to low',
                    'oldest' => 'Date, old to new',
                    'newest' => 'Date, new to old',
                ] as $key => $label)
                <button onclick="updateFilter('sort', '{{ $key }}'); toggleSortSidebar();" 
                   class="block w-full text-left px-4 py-3 text-sm text-[#441227] rounded {{ $sort == $key ? 'font-bold bg-[#441227]/10' : 'hover:bg-[#441227]/5' }}">
                    {{ $label }}
                </button>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // AJAX Filter Functions
    function updateFilter(key, value) {
        const url = new URL(window.location.href);
        if (value) {
            url.searchParams.set(key, value);
        } else {
            url.searchParams.delete(key);
        }
        
        // Update URL without reload
        window.history.pushState({}, '', url.toString());
        
        // Fetch filtered products
        fetchFilteredProducts();
    }

    function applyPriceFilter() {
        const min = document.getElementById('min_price_input').value;
        const max = document.getElementById('max_price_input').value;
        const url = new URL(window.location.href);
        
        if (min) url.searchParams.set('min_price', min);
        else url.searchParams.delete('min_price');
        
        if (max) url.searchParams.set('max_price', max);
        else url.searchParams.delete('max_price');
        
        // Update URL without reload
        window.history.pushState({}, '', url.toString());
        
        // Fetch filtered products
        fetchFilteredProducts();
    }

    function applyPriceFilterMobile() {
        const min = document.getElementById('min_price_mobile').value;
        const max = document.getElementById('max_price_mobile').value;
        const url = new URL(window.location.href);
        
        if (min) url.searchParams.set('min_price', min);
        else url.searchParams.delete('min_price');
        
        if (max) url.searchParams.set('max_price', max);
        else url.searchParams.delete('max_price');
        
        // Update URL without reload
        window.history.pushState({}, '', url.toString());
        
        // Fetch filtered products
        fetchFilteredProducts();
    }
    
    function toggleFilterSidebar() {
        const sidebar = document.getElementById('filter-sidebar');
        if (sidebar.classList.contains('hidden')) {
            sidebar.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            // Small delay to allow transition to work
            setTimeout(() => {
                sidebar.classList.remove('translate-x-full');
            }, 10);
        } else {
            sidebar.classList.add('translate-x-full');
            setTimeout(() => {
                sidebar.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }, 300);
        }
    }

    function toggleSortSidebar() {
        const sidebar = document.getElementById('sort-sidebar');
        if (sidebar.classList.contains('hidden')) {
            sidebar.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            // Small delay to allow transition to work
            setTimeout(() => {
                sidebar.classList.remove('translate-x-full');
            }, 10);
        } else {
            sidebar.classList.add('translate-x-full');
            setTimeout(() => {
                sidebar.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }, 300);
        }
    }

    // Toggle Price Dropdown
    function togglePriceDropdown() {
        const content = document.getElementById('price-dropdown-content');
        const btn = document.getElementById('price-dropdown-btn');
        const arrow = document.getElementById('price-dropdown-arrow');
        
        if (content.style.display === 'none') {
            content.style.display = 'block';
            btn.classList.add('bg-[#FAF5ED]');
            arrow.classList.add('rotate-180');
        } else {
            content.style.display = 'none';
            btn.classList.remove('bg-[#FAF5ED]');
            arrow.classList.remove('rotate-180');
        }
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('price-dropdown');
        const content = document.getElementById('price-dropdown-content');
        const btn = document.getElementById('price-dropdown-btn');
        const arrow = document.getElementById('price-dropdown-arrow');
        
        if (dropdown && !dropdown.contains(event.target)) {
            content.style.display = 'none';
            btn.classList.remove('bg-[#FAF5ED]');
            arrow.classList.remove('rotate-180');
        }
    });

    // Fetch Filtered Products via AJAX
    function fetchFilteredProducts() {
        const skeleton = document.getElementById('skeleton-loader');
        const productsGrid = document.getElementById('products-grid');
        const productCount = document.getElementById('product-count');
        
        // Show skeleton, hide products
        skeleton.classList.remove('hidden');
        productsGrid.classList.add('hidden');
        
        // Build filter URL
        const params = new URLSearchParams(window.location.search);
        const filterUrl = '{{ route("collection.filter") }}?' + params.toString();
        
        // Fetch data
        fetch(filterUrl, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Update product count
            productCount.textContent = data.pagination.total + ' products';
            
            // Update products grid
            updateProductsGrid(data.products);
            
            // Hide skeleton, show products
            skeleton.classList.add('hidden');
            productsGrid.classList.remove('hidden');
            
            // Reinitialize Swiper for new products
            setTimeout(() => {
                initProductSwipers();
            }, 100);
        })
        .catch(error => {
            console.error('Error fetching products:', error);
            skeleton.classList.add('hidden');
            productsGrid.classList.remove('hidden');
        });
    }
    
    // Update Products Grid HTML
    function updateProductsGrid(products) {
        const productsGrid = document.getElementById('products-grid');
        
        if (products.length === 0) {
            productsGrid.innerHTML = `
                <div class="col-span-full text-center py-20 text-gray-500">
                    <p class="text-xl">No products found in this collection.</p>
                </div>
            `;
            return;
        }
        
        let html = '';
        products.forEach(product => {
            const images = product.display_images || [];
            const imageUrl = images.length > 0 ? images[0] : (product.image_url || '/website/assets/images/placeholder.jpg');
            const hasDiscount = product.compare_price && product.compare_price > product.price;
            const discount = hasDiscount ? product.compare_price - product.price : 0;
            
            html += `
                <div class="flex flex-col group items-center">
                    <div class="relative w-full h-auto rounded-lg overflow-hidden bg-gray-100 group">
                        
                        <!-- Product Image Slider -->
                        <div class="swiper product-swiper w-full h-auto">
                            <div class="swiper-wrapper">
                                ${images.length > 0 ? images.map(img => `
                                    <div class="swiper-slide">
                                        <a href="/product/${product.slug}" class="block w-full h-auto">
                                            <img src="${img}" alt="${product.name}" class="w-full h-auto object-cover aspect-[3/4]" />
                                        </a>
                                    </div>
                                `).join('') : `
                                    <div class="swiper-slide">
                                        <a href="/product/${product.slug}" class="block w-full h-auto">
                                            <img src="${imageUrl}" alt="${product.name}" class="w-full h-auto object-cover aspect-[3/4]" />
                                        </a>
                                    </div>
                                `}
                            </div>
                            
                            <!-- Slider Navigation Buttons -->
                            <div class="swiper-button-next !w-6 !h-6 !text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300 drop-shadow-md" style="--swiper-navigation-size: 20px;"></div>
                            <div class="swiper-button-prev !w-6 !h-6 !text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300 drop-shadow-md" style="--swiper-navigation-size: 20px;"></div>
                        </div>

                        ${hasDiscount ? `
                        <div class="absolute top-2 right-2 md:top-3 md:right-3 z-10 bg-[#441227] text-white text-[10px] font-bold px-2 py-1 uppercase tracking-wider">
                            SAVE RS. ${Math.round(discount).toLocaleString()}
                        </div>
                        ` : ''}

                        <!-- Wishlist Button -->
                        <button onclick="addToWishlist(${product.id})" data-product-id="${product.id}" class="absolute bottom-2 right-2 md:bottom-3 md:right-3 z-10 text-white text-lg md:text-xl p-2 rounded-full hover:bg-white/20 transition-colors">
                            <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="text-center mt-2 md:mt-3 pb-2">
                        <a href="/product/${product.slug}">
                            <h3 class="text-[#441227] font-medium text-sm md:text-lg hover:text-[#5C1F33] transition-colors truncate px-1 md:px-2">${product.name}</h3>
                        </a>
                        <div class="flex items-center justify-center gap-1 md:gap-2 mt-1">
                            <p class="text-[#441227]/80 text-xs md:text-sm font-medium">Rs. ${Math.round(product.price).toLocaleString()}</p>
                            ${hasDiscount ? `<p class="text-gray-400 text-[10px] md:text-xs line-through">Rs. ${Math.round(product.compare_price).toLocaleString()}</p>` : ''}
                        </div>
                    </div>
                </div>
            `;
        });
        
        productsGrid.innerHTML = html;
    }

    // Slider Logic
    function syncInputs(source) {
        const minRange = document.getElementById('min_range');
        const maxRange = document.getElementById('max_range');
        const minInput = document.getElementById('min_price_input');
        const maxInput = document.getElementById('max_price_input');
        
        // Prevent crossover
        if (parseInt(minRange.value) > parseInt(maxRange.value)) {
            if (source === 'min') minRange.value = maxRange.value;
            else maxRange.value = minRange.value;
        }

        minInput.value = minRange.value;
        maxInput.value = maxRange.value;
        updateSliderTrack();
    }

    function syncSlider(source) {
        const minRange = document.getElementById('min_range');
        const maxRange = document.getElementById('max_range');
        const minInput = document.getElementById('min_price_input');
        const maxInput = document.getElementById('max_price_input');

        // Validation
        if (parseInt(minInput.value) > parseInt(maxInput.value)) {
            return;
        }
        
        minRange.value = minInput.value;
        maxRange.value = maxInput.value;
        updateSliderTrack();
    }

    function updateSliderTrack() {
        const minRange = document.getElementById('min_range');
        const maxRange = document.getElementById('max_range');
        const track = document.getElementById('slider-track');
        const min = parseInt(minRange.min);
        const max = parseInt(minRange.max);
        
        const percent1 = ((minRange.value - min) / (max - min)) * 100;
        const percent2 = ((maxRange.value - min) / (max - min)) * 100;
        
        track.style.left = percent1 + '%';
        track.style.width = (percent2 - percent1) + '%';
    }
    
    // Init Slider on Load
    document.addEventListener('DOMContentLoaded', () => {
        updateSliderTrack();
    });
</script>

<style>
    /* Custom Slider Styles */
    .slider-thumb::-webkit-slider-thumb {
        -webkit-appearance: none;
        pointer-events: all;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: #fff;
        border: 2px solid #441227;
        cursor: pointer;
        margin-top: -6px; /* center vertically if needed */
        position: relative;
        z-index: 20;
    }
    .slider-thumb::-moz-range-thumb {
        pointer-events: all;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: #fff;
        border: 2px solid #441227;
        cursor: pointer;
        z-index: 20;
    }
    
    /* Hide the default track */
    .slider-thumb::-webkit-slider-runnable-track {
        background: transparent;
        height: 4px;
    }
    .slider-thumb::-moz-range-track {
        background: transparent;
        height: 4px;
    }
</style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            initProductSwipers();
        });

        function initProductSwipers() {
            document.querySelectorAll('.product-swiper').forEach(swiperEl => {
                if (swiperEl.swiper) return; // Prevention

                const swiper = new Swiper(swiperEl, {
                    slidesPerView: 1,
                    spaceBetween: 0,
                    loop: true,
                    observer: true,
                    observeParents: true,
                    autoplay: {
                        delay: 1500,
                        disableOnInteraction: false,
                        enabled: false // Start stopped
                    },
                    navigation: {
                        nextEl: swiperEl.querySelector('.swiper-button-next'),
                        prevEl: swiperEl.querySelector('.swiper-button-prev'),
                    },
                    pagination: false,
                });

                // Hover Autoplay Logic
                swiperEl.parentElement.addEventListener('mouseenter', () => {
                    swiper.autoplay.start();
                });
                swiperEl.parentElement.addEventListener('mouseleave', () => {
                    swiper.autoplay.stop();
                });
            });
        }
    </script>
@endpush