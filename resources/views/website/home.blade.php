@extends('website.layouts.master')

@section('content')

    <!-- Mobile Menu -->
    <!-- (Normally handled in header, but ensuring structure) -->

    <!-- Hero Banner Section - Dynamic -->
    @if(isset($heroBanners) && $heroBanners->count() > 0)
    <section class="hero-banner">
        <div class="swiper hero-swiper">
            <div class="swiper-wrapper">
                @foreach($heroBanners as $index => $banner)
                <div class="swiper-slide">
                    @if($banner->link)
                    <a href="{{ $banner->link }}" class="block w-full">
                    @endif
                        <!-- Desktop Image -->
                        <img 
                            @if($index === 0)
                            src="{{ $banner->getDesktopImageUrl() }}" 
                            @else
                            src="{{ $banner->getDesktopImageUrl() }}" 
                            @endif
                            alt="Banner" 
                            class="hidden md:block w-full h-auto object-cover"
                        >
                        <!-- Mobile Image -->
                        <img 
                            @if($index === 0)
                            src="{{ $banner->getMobileImageUrl() }}" 
                            @else
                            src="{{ $banner->getMobileImageUrl() }}" 
                            @endif
                            alt="Banner" 
                            class="block md:hidden w-full h-auto object-cover"
                        >
                    @if($banner->link)
                    </a>
                    @endif
                </div>
                @endforeach
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </section>
    @else
    <!-- Fallback Static Banner -->
    <section class="hero-banner">
        <div class="swiper hero-swiper">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <img src="{{ asset('website/assets/images/banner-1.jpg') }}" alt="Banner" class="w-full" loading="eager">
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Category Circles Section - DYNAMIC (After Banner) -->
    @if(isset($circleCategories) && $circleCategories->count() > 0)
    <section class="w-full py-6 md:py-8" style="background-color: var(--color-bg-primary);">
        <div class="max-w-[1800px] mx-auto px-4 md:px-6 lg:px-8">
            
            <!-- Header: Category + See All -->
            <div class="flex items-center justify-between mb-4 md:mb-6">
                <h2 class="text-xl md:text-2xl font-serif-elegant font-medium" style="color: var(--color-text-primary);">Category</h2>
                <a href="{{ route('categories') }}" class="text-sm md:text-base font-medium hover:underline" style="color: var(--color-primary);">See All</a>
            </div>
            
            <!-- Circles Grid -->
            <div class="flex items-center justify-start md:justify-center gap-4 md:gap-6 lg:gap-8 overflow-x-auto pb-2 scrollbar-hide">

                @foreach($circleCategories as $circle)
                <a href="{{ $circle->circle_link ?? route('shop', ['category' => $circle->slug]) }}" class="flex flex-col items-center flex-shrink-0 group">
                    @if($circle->circle_type === 'text')
                    {{-- Text Type Circle (like SALE) --}}
                    <div class="rounded-full flex items-center justify-center w-16 h-16 md:w-20 md:h-20 lg:w-24 lg:h-24 transition-transform group-hover:scale-105" style="background-color: {{ $circle->circle_bg_color }}">
                        <span class="font-medium text-sm md:text-base lg:text-xl tracking-wider" style="color: {{ $circle->circle_text_color }}">{{ $circle->circle_text ?? $circle->name }}</span>
                    </div>
                    @else
                    {{-- Image Type Circle --}}
                    <div class="rounded-full overflow-hidden w-16 h-16 md:w-20 md:h-20 lg:w-24 lg:h-24 lazy-wrapper transition-transform group-hover:scale-105" style="background-color: var(--color-bg-secondary);">
                        <img data-src="{{ $circle->imagekit_url ?? asset('website/assets/images/product-1.jpg') }}" alt="{{ $circle->name }}" class="w-full h-full object-cover object-top lazy-image" loading="lazy">
                    </div>
                    @endif
                    <p class="text-xs md:text-sm mt-2 transition-colors text-center" style="color: var(--color-text-secondary);">{{ $circle->name }}</p>
                </a>
                @endforeach

            </div>
        </div>
    </section>
    @endif
    
    <!-- Hero Banner Swiper Init -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
             var swiper = new Swiper(".hero-swiper", {
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },
                autoplay: {
                    delay: 5000,
                },
                loop: true,
            });
        });
    </script>

    <!-- Promotional Banner - After Hero Banner -->
    @if(isset($promotionalBanners['after_hero_banner']) && $promotionalBanners['after_hero_banner']->count() > 0)
    @foreach($promotionalBanners['after_hero_banner'] as $banner)
    <section class="w-full relative overflow-hidden lazy-wrapper" style="background-color: var(--color-bg-tertiary);">
        @if($banner->link)
        <a href="{{ $banner->link }}" class="block w-full">
        @endif
            <!-- Desktop Image -->
            <img data-src="{{ $banner->desktop_imagekit_url }}" alt="{{ $banner->title ?? 'Promotional Banner' }}" class="hidden md:block w-full h-auto object-cover lazy-image" loading="lazy">
            <!-- Mobile Image -->
            <img data-src="{{ $banner->mobile_imagekit_url ?? $banner->desktop_imagekit_url }}" alt="{{ $banner->title ?? 'Promotional Banner' }}" class="md:hidden w-full h-auto object-cover lazy-image" loading="lazy">
        @if($banner->link)
        </a>
        @endif
    </section>
    @endforeach
    @endif

    <!-- All-Time Favorites Section - RESPONSIVE -->
    <section class="w-full pt-8 md:pt-12 pb-12 md:pb-16" style="background-color: var(--color-bg-primary);">

        <!-- Header -->
        <div class="max-w-[1800px] mx-auto px-4 sm:px-6 md:px-8 mb-8 md:mb-10">
            <div class="flex flex-col items-center">
                <h2 class="text-xl md:text-2xl font-medium tracking-wide mb-3 md:mb-4" style="color: var(--color-text-primary);">
                    All-Time Favorites
                </h2>

                <div class="flex items-center gap-4 md:gap-6">
                    @foreach($mainCategories as $index => $category)
                    <button onclick="switchTab({{ $index }})" 
                            id="tab-btn-{{ $index }}"
                            class="{{ $index === 0 ? 'border-b-2 pb-1' : '' }} text-xs md:text-sm font-semibold transition-all uppercase"
                            style="color: {{ $index === 0 ? 'var(--color-text-primary)' : 'var(--color-text-muted)' }}; border-color: var(--color-text-primary);">
                        {{ $category->name }}
                    </button>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="max-w-[1800px] mx-auto px-4 sm:px-6 md:px-8">
            @foreach($mainCategories as $index => $category)
            <div id="tab-content-{{ $index }}" class="{{ $index === 0 ? 'block' : 'hidden' }}">
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 lg:gap-8">
                    @forelse($category->products as $product)
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
                                                <img src="{{ $img }}" alt="{{ $product->name }}" class="w-full h-auto" loading="lazy" />
                                            </a>
                                        </div>
                                        @endforeach
                                    @else
                                        <div class="swiper-slide">
                                            <a href="{{ route('product.show', $product->slug) }}" class="block w-full h-auto">
                                                <img src="{{ asset('website/assets/images/product-1.jpg') }}" alt="{{ $product->name }}" class="w-full h-auto" />
                                            </a>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Slider Navigation Buttons (Visible on Hover) -->
                                <div class="swiper-button-next !w-6 !h-6 !text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300 drop-shadow-md" style="--swiper-navigation-size: 20px;"></div>
                                <div class="swiper-button-prev !w-6 !h-6 !text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300 drop-shadow-md" style="--swiper-navigation-size: 20px;"></div>
                            </div>

                            <!-- Wishlist Button -->
                            <button onclick="addToWishlist({{ $product->id }})" data-product-id="{{ $product->id }}" class="absolute bottom-2 right-2 md:bottom-3 md:right-3 z-10 text-white text-lg md:text-xl p-2 rounded-full hover:bg-white/20 transition-colors">
                                <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            </button>
                        </div>

                        <div class="text-center mt-2 md:mt-3 pb-2">
                            <a href="{{ route('product.show', $product->slug) }}">
                                <p class="text-xs md:text-sm font-normal truncate px-2 transition-colors" style="color: var(--color-text-primary);">
                                    {{ $product->name }}
                                </p>
                            </a>
                            <div class="flex items-center justify-center gap-2 mt-1">
                                @if($product->sale_price > 0)
                                    <p class="text-[10px] md:text-xs text-red-500 line-through">
                                        Rs. {{ number_format($product->price) }}
                                    </p>
                                    <p class="text-xs md:text-sm font-bold" style="color: var(--color-text-primary);">
                                        Rs. {{ number_format($product->sale_price) }}
                                    </p>
                                @else
                                    <p class="text-xs md:text-sm font-medium" style="color: var(--color-text-primary);">
                                        Rs. {{ number_format($product->price) }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full text-center text-gray-500 py-10">
                        No products found in {{ $category->name }}.
                    </div>
                    @endforelse
                </div>
            </div>
            @endforeach
        </div>

    </section>

    <!-- Promotional Banner - After All-Time Favorites -->
    @if(isset($promotionalBanners['after_all_time_favorites']) && $promotionalBanners['after_all_time_favorites']->count() > 0)
    @foreach($promotionalBanners['after_all_time_favorites'] as $banner)
    <section class="w-full relative overflow-hidden lazy-wrapper" style="background-color: var(--color-bg-tertiary);">
        @if($banner->link)
        <a href="{{ $banner->link }}" class="block w-full">
        @endif
            <!-- Desktop Image -->
            <img data-src="{{ $banner->desktop_imagekit_url }}" alt="{{ $banner->title ?? 'Promotional Banner' }}" class="hidden md:block w-full h-auto object-cover lazy-image" loading="lazy">
            <!-- Mobile Image -->
            <img data-src="{{ $banner->mobile_imagekit_url ?? $banner->desktop_imagekit_url }}" alt="{{ $banner->title ?? 'Promotional Banner' }}" class="md:hidden w-full h-auto object-cover lazy-image" loading="lazy">
        @if($banner->link)
        </a>
        @endif
    </section>
    @endforeach
    @endif


    <!-- Hot Collection Section - RESPONSIVE -->
    <section class="w-full py-10 md:py-14" style="background-color: var(--color-bg-primary);">
        <h2 class="text-center font-medium text-lg md:text-xl mb-6 md:mb-10" style="color: var(--color-text-primary);">Hot Collection</h2>

        <div class="max-w-[1800px] mx-auto px-4 md:px-6 lg:px-12">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">

                @foreach($collections as $collection)
                <a href="{{ route('shop') }}?collection={{ $collection->slug }}" class="relative rounded-lg overflow-hidden h-80 md:h-96 lg:h-[600px] group cursor-pointer block lazy-wrapper">
                    <img data-src="{{ $collection->image_url }}" alt="{{ $collection->name }}"
                        class="w-full h-full object-cover object-center transition-transform duration-300 group-hover:scale-105 lazy-image" loading="lazy">
                </a>
                @endforeach

                @if($collections->count() == 0)
                    <div class="col-span-full text-center py-10 text-gray-500">
                        No collections to display.
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Promotional Banner - After Hot Collection -->
    @if(isset($promotionalBanners['after_hot_collection']) && $promotionalBanners['after_hot_collection']->count() > 0)
    @foreach($promotionalBanners['after_hot_collection'] as $banner)
    <section class="w-full relative overflow-hidden lazy-wrapper" style="background-color: var(--color-bg-tertiary);">
        @if($banner->link)
        <a href="{{ $banner->link }}" class="block w-full">
        @endif
            <!-- Desktop Image -->
            <img data-src="{{ $banner->desktop_imagekit_url }}" alt="{{ $banner->title ?? 'Promotional Banner' }}" class="hidden md:block w-full h-auto object-cover lazy-image" loading="lazy">
            <!-- Mobile Image -->
            <img data-src="{{ $banner->mobile_imagekit_url ?? $banner->desktop_imagekit_url }}" alt="{{ $banner->title ?? 'Promotional Banner' }}" class="md:hidden w-full h-auto object-cover lazy-image" loading="lazy">
        @if($banner->link)
        </a>
        @endif
    </section>
    @endforeach
    @endif

    <!-- Shop By Budget Section - DYNAMIC -->
    @if(isset($budgetCards) && $budgetCards->count() > 0)
    <section class="w-full py-10 md:py-14" style="background-color: var(--color-bg-primary);">
        <h2 class="text-center font-medium text-lg md:text-xl mb-6 md:mb-10" style="color: var(--color-text-primary);">Shop By Budget</h2>

        <div class="max-w-[1800px] mx-auto px-4 md:px-6 lg:px-12">
            <div class="grid grid-cols-2 lg:grid-cols-{{ min($budgetCards->count(), 4) }} gap-4 md:gap-6">

                @foreach($budgetCards as $card)
                <a href="{{ $card->shop_link }}"
                    class="relative rounded-2xl overflow-hidden h-56 md:h-72 lg:h-[360px] cursor-pointer group block" style="background: linear-gradient(to bottom right, var(--color-primary), var(--color-primary-light), var(--color-primary));">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div
                            class="w-40 h-40 md:w-48 md:h-48 lg:w-[200px] lg:h-[200px] flex items-center justify-center rotate-45 group-hover:scale-105 transition-transform duration-300" style="border: 2px solid var(--color-accent-gold);">
                            <div class="flex flex-col items-center justify-center -rotate-45">
                                <p class="text-white uppercase font-semibold text-sm md:text-lg lg:text-xl">{{ $card->title }}</p>
                                <p class="text-white uppercase font-medium text-xs md:text-base lg:text-lg mt-1">{{ $card->subtitle }}</p>
                                <p class="font-bold text-2xl md:text-3xl lg:text-4xl mt-2" style="color: var(--color-accent-gold);">₹ {{ number_format($card->price) }}</p>
                            </div>
                        </div>
                    </div>
                    <p class="absolute bottom-3 md:bottom-6 left-0 right-0 text-center font-medium text-xs md:text-sm group-hover:underline" style="color: var(--color-accent-gold);">
                        Shop Now</p>
                </a>
                @endforeach

            </div>
        </div>
    </section>
    @endif

    <!-- Promotional Banner - After Shop By Budget -->
    @if(isset($promotionalBanners['after_shop_by_budget']) && $promotionalBanners['after_shop_by_budget']->count() > 0)
    @foreach($promotionalBanners['after_shop_by_budget'] as $banner)
    <section class="w-full relative overflow-hidden lazy-wrapper" style="background-color: var(--color-bg-tertiary);">
        @if($banner->link)
        <a href="{{ $banner->link }}" class="block w-full">
        @endif
            <!-- Desktop Image -->
            <img data-src="{{ $banner->desktop_imagekit_url }}" alt="{{ $banner->title ?? 'Promotional Banner' }}" class="hidden md:block w-full h-auto object-cover lazy-image" loading="lazy">
            <!-- Mobile Image -->
            <img data-src="{{ $banner->mobile_imagekit_url ?? $banner->desktop_imagekit_url }}" alt="{{ $banner->title ?? 'Promotional Banner' }}" class="md:hidden w-full h-auto object-cover lazy-image" loading="lazy">
        @if($banner->link)
        </a>
        @endif
    </section>
    @endforeach
    @endif

    <!-- Shop By Fabric Section - RESPONSIVE -->
    @if(isset($homepageFabrics) && $homepageFabrics->count() > 0)
    <section class="w-full pt-8 md:pt-12 pb-12 md:pb-16" style="background-color: var(--color-bg-primary);">

        <!-- Header -->
        <div class="max-w-[1800px] mx-auto px-4 sm:px-6 md:px-8 mb-8 md:mb-10">
            <div class="flex flex-col items-center">
                <h2 class="text-xl md:text-2xl font-medium tracking-wide mb-3 md:mb-4" style="color: var(--color-text-primary);">
                    Shop By Fabric
                </h2>

                <div class="flex items-center gap-4 md:gap-6">
                    @foreach($homepageFabrics as $index => $fabric)
                    <button onclick="switchFabricTab({{ $index }})" 
                            id="fabric-tab-btn-{{ $index }}"
                            class="{{ $index === 0 ? 'border-b-2 pb-1' : '' }} text-xs md:text-sm font-semibold transition-all uppercase"
                            style="color: {{ $index === 0 ? 'var(--color-text-primary)' : 'var(--color-text-muted)' }}; border-color: var(--color-text-primary);">
                        {{ $fabric->name }}
                    </button>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="max-w-[1800px] mx-auto px-4 sm:px-6 md:px-8">
            @foreach($homepageFabrics as $index => $fabric)
            <div id="fabric-tab-content-{{ $index }}" class="{{ $index === 0 ? 'block' : 'hidden' }}">
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 lg:gap-8">
                    @forelse($fabric->products as $product)
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
                                                <img src="{{ $img }}" alt="{{ $product->name }}" class="w-full h-auto" loading="lazy" />
                                            </a>
                                        </div>
                                        @endforeach
                                    @else
                                        <div class="swiper-slide">
                                            <a href="{{ route('product.show', $product->slug) }}" class="block w-full h-auto">
                                                <img src="{{ asset('website/assets/images/product-1.jpg') }}" alt="{{ $product->name }}" class="w-full h-auto" />
                                            </a>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Slider Navigation Buttons (Visible on Hover) -->
                                <div class="swiper-button-next !w-6 !h-6 !text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300 drop-shadow-md" style="--swiper-navigation-size: 20px;"></div>
                                <div class="swiper-button-prev !w-6 !h-6 !text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300 drop-shadow-md" style="--swiper-navigation-size: 20px;"></div>
                            </div>

                            <!-- Wishlist Button -->
                            <button onclick="addToWishlist({{ $product->id }})" data-product-id="{{ $product->id }}" class="absolute bottom-2 right-2 md:bottom-3 md:right-3 z-10 text-white text-lg md:text-xl p-2 rounded-full hover:bg-white/20 transition-colors">
                                <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            </button>
                        </div>

                        <div class="text-center mt-2 md:mt-3 pb-2">
                            <a href="{{ route('product.show', $product->slug) }}">
                                <p class="text-xs md:text-sm font-normal truncate px-2 transition-colors" style="color: var(--color-text-primary);">
                                    {{ $product->name }}
                                </p>
                            </a>
                            <div class="flex items-center justify-center gap-2 mt-1">
                                @if($product->sale_price > 0)
                                    <p class="text-[10px] md:text-xs text-red-500 line-through">
                                        Rs. {{ number_format($product->price) }}
                                    </p>
                                    <p class="text-xs md:text-sm font-bold" style="color: var(--color-text-primary);">
                                        Rs. {{ number_format($product->sale_price) }}
                                    </p>
                                @else
                                    <p class="text-xs md:text-sm font-medium" style="color: var(--color-text-primary);">
                                        Rs. {{ number_format($product->price) }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full text-center text-gray-500 py-10">
                        No products found in {{ $fabric->name }}.
                    </div>
                    @endforelse
                </div>
            </div>
            @endforeach
        </div>

    </section>

    <!-- Fabric Tab Switching Script -->
    <script>
        function switchFabricTab(index) {
            // Hide all fabric tab contents
            document.querySelectorAll('[id^="fabric-tab-content-"]').forEach(el => el.classList.add('hidden'));
            // Show selected fabric tab content
            document.getElementById('fabric-tab-content-' + index).classList.remove('hidden');
            
            // Update fabric tab button styles
            document.querySelectorAll('[id^="fabric-tab-btn-"]').forEach(btn => {
                btn.classList.remove('border-b-2', 'pb-1');
                btn.style.color = 'var(--color-text-muted)';
                btn.style.borderColor = 'transparent';
            });
            const activeBtn = document.getElementById('fabric-tab-btn-' + index);
            activeBtn.classList.add('border-b-2', 'pb-1');
            activeBtn.style.color = 'var(--color-text-primary)';
            activeBtn.style.borderColor = 'var(--color-text-primary)';

            // Re-initialize Swiper for newly visible products
            setTimeout(() => {
                document.querySelectorAll('#fabric-tab-content-' + index + ' .product-swiper').forEach(el => {
                    if (!el.swiper) {
                        new Swiper(el, {
                            loop: true,
                            navigation: {
                                nextEl: el.querySelector('.swiper-button-next'),
                                prevEl: el.querySelector('.swiper-button-prev'),
                            },
                        });
                    }
                });
            }, 100);
        }
    </script>
    @endif

    <!-- Video Reels Section - Dynamic with Swiper -->
    @if(isset($videoReels) && $videoReels->count() > 0)
    <section class="w-full py-10 md:py-14" style="background-color: var(--color-bg-primary);">
        <h2 class="text-center font-medium text-lg md:text-xl mb-6 md:mb-10" style="color: var(--color-text-primary);">Shop The Reels</h2>
        
        <div class="max-w-[1800px] mx-auto px-0 md:px-6 lg:px-12 relative">
            <div class="swiper reels-swiper">
                <div class="swiper-wrapper">
                    @foreach($videoReels as $index => $reel)
                    <div class="swiper-slide">
                        <div class="relative bg-white md:rounded-2xl overflow-hidden mx-auto"
                            style="width: 100%; max-width: 280px; height: 420px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                            <!-- Video covers full card -->
                            <video id="reel-video-{{ $index }}" src="{{ $reel->video_url }}" class="absolute inset-0 w-full h-full object-cover" muted loop playsinline {{ $reel->autoplay ? 'autoplay' : '' }} data-autoplay="{{ $reel->autoplay ? 'true' : 'false' }}"></video>
                            
                            <!-- Play/Pause Button - Bottom left -->
                            <button onclick="toggleReelVideo({{ $index }})" class="absolute bottom-20 left-3 z-20">
                                <div class="w-9 h-9 rounded-full bg-white bg-opacity-90 flex items-center justify-center shadow-lg hover:scale-110 transition-transform">
                                    <svg id="play-icon-{{ $index }}" class="w-4 h-4 ml-0.5 {{ $reel->autoplay ? 'hidden' : '' }}" fill="currentColor" viewBox="0 0 24 24" style="color: var(--color-primary);">
                                        <path d="M8 5v14l11-7z"/>
                                    </svg>
                                    <svg id="pause-icon-{{ $index }}" class="w-4 h-4 {{ $reel->autoplay ? '' : 'hidden' }}" fill="currentColor" viewBox="0 0 24 24" style="color: var(--color-primary);">
                                        <path d="M6 4h4v16H6V4zm8 0h4v16h-4V4z"/>
                                    </svg>
                                </div>
                            </button>

                            @if($reel->badge)
                            <div class="absolute top-2 left-2 text-white uppercase px-2 py-1 rounded-md z-10 text-[10px] font-semibold" style="background-color: {{ $reel->badge_color }}">{{ $reel->badge }}</div>
                            @endif
                            
                            <div class="absolute top-2 right-2 bg-black bg-opacity-60 text-white px-2 py-1 rounded-full flex items-center z-10 text-[10px] gap-1">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                </svg>
                                {{ $reel->formatted_views }}
                            </div>

                            <!-- Product Info Card -->
                            <div class="absolute bottom-2 left-2 right-2 bg-white rounded-xl p-2 z-10 shadow-md">
                                <div class="flex items-center gap-2">
                                    @php $productImage = $reel->product->display_images[0] ?? asset('website/assets/images/product-1.jpg'); @endphp
                                    <img src="{{ $productImage }}" alt="{{ $reel->product->name ?? 'Product' }}"
                                        class="w-9 h-9 rounded object-cover flex-shrink-0">
                                    <div class="flex-1 min-w-0">
                                        <p class="font-medium truncate text-[11px]" style="color: var(--color-text-primary);">
                                            {{ $reel->product->name ?? 'Product Name' }}</p>
                                        <div class="flex items-center gap-1">
                                            @if($reel->product && $reel->product->sale_price > 0)
                                                <p class="text-[9px] text-red-500 line-through">
                                                    Rs. {{ number_format($reel->product->price) }}</p>
                                                <p class="font-bold text-xs" style="color: var(--color-text-primary);">
                                                    Rs. {{ number_format($reel->product->sale_price) }}</p>
                                            @else
                                                <p class="font-semibold text-xs" style="color: var(--color-text-primary);">
                                                    Rs. {{ number_format($reel->product->price ?? 0) }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <button onclick="window.openQuickView(event, {{ $reel->product->id ?? 0 }})" class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center flex-shrink-0 hover:bg-gray-200 transition-colors">
                                        <svg class="w-3.5 h-3.5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Navigation Arrows -->
            <button class="reels-prev absolute left-0 top-1/2 -translate-y-1/2 z-10 w-10 h-10 bg-white rounded-full shadow-lg flex items-center justify-center hover:bg-gray-50 transition-colors hidden md:flex">
                <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>
            <button class="reels-next absolute right-0 top-1/2 -translate-y-1/2 z-10 w-10 h-10 bg-white rounded-full shadow-lg flex items-center justify-center hover:bg-gray-50 transition-colors hidden md:flex">
                <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Reels Swiper
            new Swiper('.reels-swiper', {
                slidesPerView: 1.15,
                spaceBetween: 0,
                centeredSlides: true,
                loop: false,
                navigation: {
                    nextEl: '.reels-next',
                    prevEl: '.reels-prev',
                },
                breakpoints: {
                    480: {
                        slidesPerView: 2,
                        spaceBetween: 16,
                        centeredSlides: false,
                    },
                    640: {
                        slidesPerView: 2.5,
                        spaceBetween: 16,
                        centeredSlides: false,
                    },
                    768: {
                        slidesPerView: 3,
                        spaceBetween: 20,
                        centeredSlides: false,
                    },
                    1024: {
                        slidesPerView: 4,
                        spaceBetween: 20,
                        centeredSlides: false,
                    },
                    1280: {
                        slidesPerView: 5,
                        spaceBetween: 24,
                        centeredSlides: false,
                    },
                    1536: {
                        slidesPerView: 6,
                        spaceBetween: 24,
                        centeredSlides: false,
                    }
                }
            });
        });

        function toggleReelVideo(index) {
            const video = document.getElementById('reel-video-' + index);
            const playIcon = document.getElementById('play-icon-' + index);
            const pauseIcon = document.getElementById('pause-icon-' + index);
            
            if (video.paused) {
                // Pause all other videos first
                document.querySelectorAll('video[id^="reel-video-"]').forEach(function(v) {
                    if (v.id !== 'reel-video-' + index) {
                        v.pause();
                        const vIndex = v.id.replace('reel-video-', '');
                        const vPlayIcon = document.getElementById('play-icon-' + vIndex);
                        const vPauseIcon = document.getElementById('pause-icon-' + vIndex);
                        if (vPlayIcon) vPlayIcon.classList.remove('hidden');
                        if (vPauseIcon) vPauseIcon.classList.add('hidden');
                    }
                });
                
                video.play();
                playIcon.classList.add('hidden');
                pauseIcon.classList.remove('hidden');
            } else {
                video.pause();
                playIcon.classList.remove('hidden');
                pauseIcon.classList.add('hidden');
            }
        }
    </script>
    @endif

    <!-- Promotional Banner - After Video Reels -->
    @if(isset($promotionalBanners['after_video_reels']) && $promotionalBanners['after_video_reels']->count() > 0)
    @foreach($promotionalBanners['after_video_reels'] as $banner)
    <section class="w-full relative overflow-hidden lazy-wrapper" style="background-color: var(--color-bg-tertiary);">
        @if($banner->link)
        <a href="{{ $banner->link }}" class="block w-full">
        @endif
            <!-- Desktop Image -->
            <img data-src="{{ $banner->desktop_imagekit_url }}" alt="{{ $banner->title ?? 'Promotional Banner' }}" class="hidden md:block w-full h-auto object-cover lazy-image" loading="lazy">
            <!-- Mobile Image -->
            <img data-src="{{ $banner->mobile_imagekit_url ?? $banner->desktop_imagekit_url }}" alt="{{ $banner->title ?? 'Promotional Banner' }}" class="md:hidden w-full h-auto object-cover lazy-image" loading="lazy">
        @if($banner->link)
        </a>
        @endif
    </section>
    @endforeach
    @endif

    <!-- Testimonials Section -->
    @include('website.includes.testimonials')

@endsection

@push('scripts')
    @include('website.includes.search_sidebar')
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initProductSwipers();
        });

        function initProductSwipers() {
            document.querySelectorAll('.product-swiper').forEach(swiperEl => {
                if (swiperEl.swiper) return; // Prevention

                const swiper = new Swiper(swiperEl, {
                    slidesPerView: 1,
                    spaceBetween: 0,
                    loop: true,
                    // effect: 'slide', // Default is slide
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
                    // Optional: swiper.slideTo(0); // If you want to reset
                });
            });
        }

        function switchTab(index) {
            // Reset all buttons
            document.querySelectorAll('[id^="tab-btn-"]').forEach(btn => {
                btn.className = 'text-xs md:text-sm font-semibold transition-all uppercase';
                btn.style.color = 'var(--color-text-muted)';
                btn.style.borderColor = 'transparent';
            });
            // Activate clicked button
            const activeBtn = document.getElementById('tab-btn-' + index);
            activeBtn.className = 'text-xs md:text-sm font-semibold border-b-2 pb-1 transition-all uppercase';
            activeBtn.style.color = 'var(--color-text-primary)';
            activeBtn.style.borderColor = 'var(--color-text-primary)';

            // Hide all contents
            document.querySelectorAll('[id^="tab-content-"]').forEach(content => {
                content.classList.add('hidden');
                content.classList.remove('block');
            });
            // Show clicked content
            const activeContent = document.getElementById('tab-content-' + index);
            activeContent.classList.remove('hidden');
            activeContent.classList.add('block');
            
            // Re-init or Update swipers in the newly visible tab 
            // (Observer should handle it, but calling initSafe is good)
            setTimeout(initProductSwipers, 10);
        }
    </script>
@endpush
