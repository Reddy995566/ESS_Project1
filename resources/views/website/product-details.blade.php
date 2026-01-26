@extends('website.layouts.master')

@section('title', $product->meta_title ?? $product->name . ' - Buy Online')

@section('meta')
    <!-- SEO Meta Tags -->
    <meta name="description" content="{{ $product->meta_description ?? Str::limit(strip_tags($product->description), 160) }}">
    <meta name="keywords" content="{{ $product->meta_keywords ?? $product->name . ', ' . ($product->category->name ?? 'fashion') }}">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="product">
    <meta property="og:url" content="{{ route('product.show', $product->slug) }}">
    <meta property="og:title" content="{{ $product->meta_title ?? $product->name }}">
    <meta property="og:description" content="{{ $product->meta_description ?? Str::limit(strip_tags($product->description), 160) }}">
    <meta property="og:image" content="{{ $product->image_url }}">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="product:price:amount" content="{{ $product->sale_price > 0 ? $product->sale_price : $product->price }}">
    <meta property="product:price:currency" content="INR">
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ route('product.show', $product->slug) }}">
    <meta name="twitter:title" content="{{ $product->meta_title ?? $product->name }}">
    <meta name="twitter:description" content="{{ $product->meta_description ?? Str::limit(strip_tags($product->description), 160) }}">
    <meta name="twitter:image" content="{{ $product->image_url }}">
    
    <!-- WhatsApp -->
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="{{ route('product.show', $product->slug) }}">
@endsection

@section('content')
    <div class="bg-[#faf5ed] min-h-screen p-6 font-sans text-gray-700">
        <div class="mx-auto w-full max-w-[1600px]"> <!-- Full width but capped for sanity on huge screens -->

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">

                <!-- LEFT: IMAGE GALLERY (45%) -->
                <div class="lg:col-span-5">
                    <div class="sticky top-6">
                        <!-- Main Image Swiper -->
                        <div class="mb-4 overflow-hidden rounded-md bg-[#f0ece4] relative group">
                            <div class="swiper main-swiper w-full max-w-[635px] h-auto mx-auto" id="mainSwiperContainer">
                                <div class="swiper-wrapper">
                                    @php
                                        $groupedVariantImages = $product->grouped_variant_images;
                                        $productColorsForGallery = $product->colors()->get();
                                        
                                        // Determine initial color ID
                                        $galleryInitialColorId = null;
                                        if (isset($selectedColorId) && $selectedColorId) {
                                            $galleryInitialColorId = (int) $selectedColorId;
                                        } elseif ($productColorsForGallery->count() > 0) {
                                            $galleryInitialColorId = $productColorsForGallery->first()->id;
                                        }
                                        
                                        // Get images for initial color
                                        $initialImages = [];
                                        if ($galleryInitialColorId && isset($groupedVariantImages[$galleryInitialColorId])) {
                                            $initialImages = $groupedVariantImages[$galleryInitialColorId];
                                        }
                                        
                                        // Fallback to product's main images if no color images
                                        if (empty($initialImages)) {
                                            $initialImages = $product->images_array;
                                        }

                                        // Fallback to first variant if still empty
                                        if (empty($initialImages) && !empty($groupedVariantImages)) {
                                            $initialImages = reset($groupedVariantImages);
                                        }

                                        // Ensure we have at least something
                                        if (empty($initialImages)) {
                                            $initialImages = [$product->image_url ?? asset('images/placeholder.png')];
                                        }
                                    @endphp
                                    @foreach($initialImages as $index => $img)
                                        <div class="swiper-slide w-full h-auto relative group">
                                            <img src="{{ $img }}" alt="{{ $product->name }}" class="w-full h-auto">
                                            <button onclick="openLightbox({{ $index }})"
                                                class="absolute top-4 right-4 z-20 p-2 bg-white/50 hover:bg-white rounded-lg text-[#4b0f27] backdrop-blur-sm transition shadow-sm">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                                                </svg>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                                <!-- Pagination -->
                                <div class="swiper-pagination !bottom-4"></div>

                                <!-- Navigation Arrows (Always Visible) -->
                                <div
                                    class="swiper-button-next !w-10 !h-10 !bg-white/30 !rounded-full !backdrop-blur-sm z-10 flex items-center justify-center hover:!bg-white transition shadow-md after:!content-none">
                                    <svg class="w-4 h-4 text-[#4b0f27]" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </div>
                                <div
                                    class="swiper-button-prev !w-10 !h-10 !bg-white/30 !rounded-full !backdrop-blur-sm z-10 flex items-center justify-center hover:!bg-white transition shadow-md after:!content-none">
                                    <svg class="w-4 h-4 text-[#4b0f27]" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 19l-7-7 7-7" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Thumbnails -->
                        <div id="thumbnailGrid" class="grid grid-cols-5 gap-4 mt-4">
                            @foreach(array_slice($initialImages, 0, 5) as $index => $img)
                                <div onclick="goToSlide({{ $index }})"
                                    class="gallery-thumb cursor-pointer rounded-lg border-2 {{ $index === 0 ? 'border-[#4b0f27]' : 'border-gray-200' }} p-1 bg-white hover:border-[#4b0f27] transition-colors duration-200">
                                    <div class="aspect-square w-full overflow-hidden rounded-md">
                                        <img src="{{ $img }}" class="h-full w-full object-cover">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- CENTER: PRODUCT DETAILS (35%) -->
                <div class="lg:col-span-7">
                    <div class="flex flex-col gap-5">

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
                            <h1
                                class="font-serif-elegant text-[26px] font-semibold leading-tight text-[#4b0f27] tracking-wide">
                                {{ $product->name }}
                            </h1>
                            <!-- Rating -->
                            <div class="mt-1 flex items-center gap-1">
                                <div class="flex text-[#fbbf24]">
                                    @for($i = 0; $i < 5; $i++)
                                        <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    @endfor
                                </div>
                                <span class="text-xs text-gray-500">
                                    {{ $product->approved_reviews_count ?? 0 }} {{ ($product->approved_reviews_count ?? 0) == 1 ? 'review' : 'reviews' }}
                                </span>
                            </div>
                        </div>

                        <!-- Layout: Bought count badge -->
                        @if($recentPurchases > 0)
                        <div class="w-fit rounded-full bg-[#fae8e8] px-3 py-1 text-xs font-medium text-[#4b0f27]">
                            ⚡ {{ $recentPurchases }} Bought this in 24 hours
                        </div>
                        @endif

                        <!-- Price -->
                        <div class="flex items-center gap-3">
                            @if($product->sale_price > 0)
                                <div class="font-serif-elegant text-[24px] font-bold text-[#4b0f27] tracking-wide">
                                    Rs. {{ number_format($product->sale_price) }}
                                </div>
                                <div class="font-serif-elegant text-[18px] text-gray-400 line-through">
                                    Rs. {{ number_format($product->price) }}
                                </div>
                                <div class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">
                                    SAVE Rs. {{ number_format($product->price - $product->sale_price) }}
                                </div>
                            @else
                                <div class="font-serif-elegant text-[24px] font-bold text-[#4b0f27] tracking-wide">
                                    Rs. {{ number_format($product->price) }}
                                </div>
                            @endif
                        </div>

                        <!-- Offer Box -->
                        <div class="relative overflow-hidden rounded border border-[#4b0f27] bg-[#fcecc6] p-3">
                            <!-- Shimmer Overlay -->
                            <div class="shimmer-overlay"></div>

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

                        <!-- Quantity & Buttons Section -->
                        <div class="mt-6 flex flex-col gap-3">
                            <!-- Row 1: Quantity + Add to Cart -->
                            <div class="flex gap-4 h-11">
                                <!-- Quantity -->
                                <div
                                    class="w-32 flex items-center justify-between rounded border border-[#2A1810]/20 bg-[#FAF5ED] px-1">
                                    <button onclick="updateQty(-1)"
                                        class="h-full px-3 text-xl text-[#2A1810] hover:bg-black/5 transition flex items-center justify-center">-</button>
                                    <input type="text" id="qtyInput" value="1"
                                        class="h-full w-full border-none bg-transparent text-center text-base font-medium text-[#2A1810] focus:ring-0 p-0"
                                        readonly>
                                    <button onclick="updateQty(1)"
                                        class="h-full px-3 text-xl text-[#2A1810] hover:bg-black/5 transition flex items-center justify-center">+</button>
                                </div>

                                <!-- Add to Cart Button -->
                                <button id="addToCartBtn" onclick="addToCart()"
                                    class="flex-1 rounded bg-[#3D0C1F] text-sm font-semibold text-white transition hover:bg-[#2a0815] uppercase tracking-wider flex items-center justify-center">
                                    Add to cart
                                </button>
                            </div>

                            <!-- Row 2: Buy Now Button -->
                            <button onclick="processBuyNow()"
                                class="h-11 w-full rounded bg-[#495530] text-sm font-semibold text-white transition hover:bg-[#384225] uppercase tracking-wider shadow-sm">
                                Buy it now
                            </button>

                            <!-- Row 3: Share Button -->
                            <button onclick="toggleShareMenu()" id="shareButton"
                                class="h-11 w-full rounded border-2 border-[#3D0C1F] text-sm font-semibold text-[#3D0C1F] transition hover:bg-[#3D0C1F] hover:text-white uppercase tracking-wider flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                                </svg>
                                Share Product
                            </button>

                            <!-- Share Menu (Hidden by default) -->
                            <div id="shareMenu" class="hidden mt-2 p-4 bg-white rounded-lg border-2 border-[#3D0C1F]/20 shadow-lg">
                                <p class="text-sm font-semibold text-gray-700 mb-3">Share this product:</p>
                                <div class="grid grid-cols-4 gap-3">
                                    <!-- WhatsApp -->
                                    <button onclick="shareOn('whatsapp')" class="flex flex-col items-center gap-1 p-2 rounded-lg hover:bg-green-50 transition group">
                                        <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center group-hover:scale-110 transition">
                                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                            </svg>
                                        </div>
                                        <span class="text-xs text-gray-600">WhatsApp</span>
                                    </button>

                                    <!-- Facebook -->
                                    <button onclick="shareOn('facebook')" class="flex flex-col items-center gap-1 p-2 rounded-lg hover:bg-blue-50 transition group">
                                        <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center group-hover:scale-110 transition">
                                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                            </svg>
                                        </div>
                                        <span class="text-xs text-gray-600">Facebook</span>
                                    </button>

                                    <!-- Twitter -->
                                    <button onclick="shareOn('twitter')" class="flex flex-col items-center gap-1 p-2 rounded-lg hover:bg-sky-50 transition group">
                                        <div class="w-10 h-10 bg-sky-500 rounded-full flex items-center justify-center group-hover:scale-110 transition">
                                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                            </svg>
                                        </div>
                                        <span class="text-xs text-gray-600">Twitter</span>
                                    </button>

                                    <!-- Copy Link -->
                                    <button onclick="shareOn('copy')" class="flex flex-col items-center gap-1 p-2 rounded-lg hover:bg-gray-100 transition group">
                                        <div class="w-10 h-10 bg-gray-600 rounded-full flex items-center justify-center group-hover:scale-110 transition">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                        <span class="text-xs text-gray-600">Copy Link</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @php
                            $productColors = $product->colors()->get();
                        @endphp
                        @if($productColors->count() > 1)
                            <div class="mt-4" id="moreColorsSection">
                                <h3 class="mb-3 text-sm font-semibold text-gray-800">More Colors</h3>
                                <div class="grid grid-cols-4 gap-2" id="moreColorsGrid">
                                    @foreach($productColors as $color)
                                        @php
                                            $cImages = $groupedVariantImages[$color->id] ?? [];
                                            $cImage = $cImages[0] ?? null;
                                            // Use the same $galleryInitialColorId that was set in the gallery section
                                            $isSelected = ($color->id == $galleryInitialColorId);
                                        @endphp
                                        <div onclick="switchColor({{ $color->id }}, '{{ addslashes($color->name) }}', this)" data-color-id="{{ $color->id }}"
                                            class="cursor-pointer group flex flex-col items-center color-swatch more-color-item"
                                            style="{{ $isSelected ? 'display: none;' : 'display: flex;' }}">
                                            <div
                                                class="aspect-[3/4] w-full overflow-hidden rounded border border-transparent transition group-hover:border-gray-400">
                                                @if($cImage)
                                                    <img src="{{ $cImage }}" class="h-full w-full object-cover">
                                                @else
                                                    <div class="h-full w-full"
                                                        style="background-color: {{ $color->hex_code ?? '#ccc' }}"></div>
                                                @endif
                                            </div>
                                            <span class="mt-1 text-[10px] text-gray-600">{{ $color->name }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Accordions (No JS, using details/summary) -->
                        <!-- Accordion Sections (Animated) -->
                        <div class="mt-6 border-t border-[#4b0f27]/10">
                            <!-- Product Details -->
                            <div class="border-b border-[#4b0f27]/10">
                                <button onclick="toggleAccordion(this)"
                                    class="w-full py-4 flex items-center justify-between text-left group focus:outline-none select-none">
                                    <span class="font-bold text-[#4b0f27] uppercase tracking-wide text-sm">Product
                                        Details</span>
                                    <svg class="w-5 h-5 text-[#4b0f27] transform transition-transform duration-300 group-hover:text-opacity-80 accordion-icon"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div
                                    class="overflow-hidden transition-all duration-300 max-h-0 opacity-0 accordion-content">
                                    <div class="pb-4 text-sm text-[#4b0f27]/80 leading-relaxed font-sans-premium">
                                        <p>{!! $product->description ?? 'Premium quality fabric tailored for comfort and elegance. Perfect for any occasion.' !!}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Washing Instructions -->
                            <div class="border-b border-[#4b0f27]/10">
                                <button onclick="toggleAccordion(this)"
                                    class="w-full py-4 flex items-center justify-between text-left group focus:outline-none select-none">
                                    <span class="font-bold text-[#4b0f27] uppercase tracking-wide text-sm">Washing
                                        Instructions</span>
                                    <svg class="w-5 h-5 text-[#4b0f27] transform transition-transform duration-300 accordion-icon"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div
                                    class="overflow-hidden transition-all duration-300 max-h-0 opacity-0 accordion-content">
                                    <div class="pb-4 text-sm text-[#4b0f27]/80 leading-relaxed font-sans-premium">
                                        <ul class="list-disc pl-5 space-y-1">
                                            <li>Dry clean only recommended.</li>
                                            <li>Do not bleach.</li>
                                            <li>Iron on low heat if necessary.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Shipping Information -->
                            <div class="border-b border-[#4b0f27]/10">
                                <button onclick="toggleAccordion(this)"
                                    class="w-full py-4 flex items-center justify-between text-left group focus:outline-none select-none">
                                    <span class="font-bold text-[#4b0f27] uppercase tracking-wide text-sm">Shipping
                                        Information</span>
                                    <svg class="w-5 h-5 text-[#4b0f27] transform transition-transform duration-300 accordion-icon"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div
                                    class="overflow-hidden transition-all duration-300 max-h-0 opacity-0 accordion-content">
                                    <div class="pb-4 text-sm text-[#4b0f27]/80 leading-relaxed font-sans-premium">
                                        <p>Standard shipping: 3-5 business days. Express available.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Returns & Refunds -->
                            <div class="border-b border-[#4b0f27]/10">
                                <button onclick="toggleAccordion(this)"
                                    class="w-full py-4 flex items-center justify-between text-left group focus:outline-none select-none">
                                    <span class="font-bold text-[#4b0f27] uppercase tracking-wide text-sm">Returns &
                                        Refunds</span>
                                    <svg class="w-5 h-5 text-[#4b0f27] transform transition-transform duration-300 accordion-icon"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div
                                    class="overflow-hidden transition-all duration-300 max-h-0 opacity-0 accordion-content">
                                    <div class="pb-4 text-sm text-[#4b0f27]/80 leading-relaxed font-sans-premium">
                                        <p>7-day return policy for unused items with original tags.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- RIGHT: RECENTLY VIEWED (20% sticky) -->
                <div class="hidden">
                    <div
                        class="sticky top-6 h-[calc(100vh-48px)] overflow-hidden rounded border border-gray-300 bg-white p-0">
                        <!-- Heading -->
                        <div class="flex items-center justify-center border-b border-gray-200 border-dashed p-3">
                            <span class="text-xs font-semibold uppercase tracking-wider text-gray-800">Recently
                                Viewed</span>
                        </div>

                        <!-- Scroll Up Arrow -->
                        <div class="flex justify-center p-1 opacity-40 hover:opacity-100 cursor-pointer">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                            </svg>
                        </div>

                        <!-- List -->
                        <div class="no-scrollbar h-[calc(100vh-180px)] overflow-y-auto p-3">
                            <div class="space-y-4">
                                @if(isset($recentlyViewed))
                                    @foreach($recentlyViewed as $recent)
                                        <div class="flex gap-3">
                                            <div class="h-16 w-12 flex-shrink-0 overflow-hidden rounded bg-gray-100">
                                                <img src="{{ $recent->image }}" class="h-full w-full object-cover">
                                            </div>
                                            <div class="flex flex-col justify-center">
                                                <p class="line-clamp-1 text-[11px] font-medium text-gray-900">{{ $recent->name }}
                                                </p>
                                                <p class="text-[11px] font-bold text-[#4b0f27]">Rs.
                                                    {{ number_format($recent->price) }}
                                                </p>
                                                <p class="text-[10px] text-gray-400">{{ rand(1, 59) }} minutes ago</p>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                                <!-- Static fillers to ensure look match if empty -->
                                <div class="flex gap-3">
                                    <div class="h-16 w-12 bg-gray-200"></div>
                                    <div class="flex flex-col justify-center gap-1">
                                        <div class="h-3 w-16 bg-gray-200"></div>
                                        <div class="h-3 w-10 bg-gray-200"></div>
                                    </div>
                                </div>
                                <div class="flex gap-3">
                                    <div class="h-16 w-12 bg-gray-200"></div>
                                    <div class="flex flex-col justify-center gap-1">
                                        <div class="h-3 w-16 bg-gray-200"></div>
                                        <div class="h-3 w-10 bg-gray-200"></div>
                                    </div>
                                </div>
                                <div class="flex gap-3">
                                    <div class="h-16 w-12 bg-gray-200"></div>
                                    <div class="flex flex-col justify-center gap-1">
                                        <div class="h-3 w-16 bg-gray-200"></div>
                                        <div class="h-3 w-10 bg-gray-200"></div>
                                    </div>
                                </div>
                                <div class="flex gap-3">
                                    <div class="h-16 w-12 bg-gray-200"></div>
                                    <div class="flex flex-col justify-center gap-1">
                                        <div class="h-3 w-16 bg-gray-200"></div>
                                        <div class="h-3 w-10 bg-gray-200"></div>
                                    </div>
                                </div>
                                <div class="flex gap-3">
                                    <div class="h-16 w-12 bg-gray-200"></div>
                                    <div class="flex flex-col justify-center gap-1">
                                        <div class="h-3 w-16 bg-gray-200"></div>
                                        <div class="h-3 w-10 bg-gray-200"></div>
                                    </div>
                                </div>
                                <div class="flex gap-3">
                                    <div class="h-16 w-12 bg-gray-200"></div>
                                    <div class="flex flex-col justify-center gap-1">
                                        <div class="h-3 w-16 bg-gray-200"></div>
                                        <div class="h-3 w-10 bg-gray-200"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Scroll Down Arrow -->
                        <div class="flex justify-center p-1 opacity-40 hover:opacity-100 cursor-pointer">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"
                                    transform="rotate(180 12 12)" />
                            </svg>
                        </div>

                        <!-- Button -->
                        <div class="absolute bottom-0 left-0 right-0 border-t border-gray-200 border-dashed bg-white p-3">
                            <button
                                class="w-full rounded border border-[#4b0f27] py-2 text-[10px] font-bold uppercase tracking-wider text-[#4b0f27] hover:bg-[#4b0f27] hover:text-white transition">
                                Explore Best Sellers
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- FULL WIDTH MARQUEE SLIDER -->
    <div
        class="w-full bg-[#4b0f27] py-3 overflow-hidden border-t-2 border-[#b08d55]/30 border-b-2 border-[#b08d55]/30 relative z-10">
        <div class="marquee-container flex items-center whitespace-nowrap">
            <!-- Content will be duplicated by JS for infinite loop -->
            <div
                class="marquee-content flex items-center gap-12 text-white/90 text-[13px] font-bold tracking-[0.2em] uppercase font-sans-elegant px-6">
                <span class="flex items-center gap-12">
                    <span>SAREES FOR EVERY OCCASION</span>
                    <svg class="w-4 h-4 text-[#b08d55]" viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                        </path>
                    </svg>
                    <span>HIGH-QUALITY SAREES</span>
                    <svg class="w-4 h-4 text-[#b08d55]" viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                        </path>
                    </svg>
                    <span>BUY MORE SAVE MORE</span>
                    <svg class="w-4 h-4 text-[#b08d55]" viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                        </path>
                    </svg>
                </span>
            </div>
        </div>
    </div>
    </div>

    <!-- REVIEWS SECTION -->
    <div class="w-full bg-[#faf5ed] py-8 md:py-12">
        <div class="mx-auto w-full max-w-[1600px] px-4 md:px-6">

            <!-- Reviews Header & Summary -->
            <div class="mb-6 md:mb-8">
                <h2 class="font-serif-elegant text-2xl md:text-3xl font-bold text-[#4b0f27] mb-4 md:mb-6">Customer Reviews
                </h2>

                <div
                    class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-8 bg-white rounded-lg p-4 md:p-6 border border-[#4b0f27]/10 shadow-sm">
                    <!-- Overall Rating -->
                    <div
                        class="flex flex-col items-center justify-center pb-4 border-b lg:border-b-0 lg:border-r border-gray-200">
                        <div class="text-4xl md:text-5xl font-bold text-[#4b0f27]" id="averageRating">0.0</div>
                        <div class="flex items-center gap-1 my-2" id="averageStars">
                            <!-- Stars will be populated by JS -->
                        </div>
                        <p class="text-sm text-gray-600"><span id="totalReviews">0</span> Reviews</p>
                    </div>

                    <!-- Rating Breakdown -->
                    <div class="lg:col-span-2 space-y-2">
                        @for($i = 5; $i >= 1; $i--)
                            <div class="flex items-center gap-2 md:gap-3">
                                <span class="text-xs md:text-sm font-medium text-gray-700 w-10 md:w-12">{{ $i }} star</span>
                                <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-[#fbbf24] transition-all duration-300" data-rating="{{ $i }}"
                                        style="width: 0%"></div>
                                </div>
                                <span class="text-xs md:text-sm text-gray-600 w-8 md:w-12 text-right"
                                    data-count="{{ $i }}">0</span>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>

            <!-- Write Review Inline Form -->
            <div id="reviewFormContainer" class="mb-6 md:mb-8 hidden">
                <div class="bg-white rounded-lg p-4 md:p-6 border-2 border-[#4b0f27] shadow-lg">
                    <div class="flex items-center justify-between mb-4 md:mb-6">
                        <h3 class="font-serif-elegant text-xl md:text-2xl font-bold text-[#4b0f27]">Write a Review</h3>
                        <button onclick="closeReviewForm()" class="text-gray-500 hover:text-gray-700 transition-colors p-2">
                            <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form id="reviewForm" class="space-y-4 md:space-y-6">
                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                        <!-- Rating Stars -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Your Rating *</label>
                            <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                                <div class="flex gap-1" id="ratingStars">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg onclick="setRating({{ $i }})"
                                            class="w-8 h-8 md:w-10 md:h-10 cursor-pointer text-gray-300 hover:text-[#fbbf24] transition-colors rating-star"
                                            data-rating="{{ $i }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    @endfor
                                </div>
                                <span id="ratingText" class="text-sm text-gray-600"></span>
                            </div>
                            <input type="hidden" name="rating" id="ratingInput" required>
                        </div>

                        <!-- Name & Email Row -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Your Name *</label>
                                <input type="text" name="name" required
                                    class="w-full px-3 md:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4b0f27] focus:border-transparent text-sm md:text-base"
                                    placeholder="Enter your name">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Your Email *</label>
                                <input type="email" name="email" required
                                    class="w-full px-3 md:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4b0f27] focus:border-transparent text-sm md:text-base"
                                    placeholder="Enter your email">
                            </div>
                        </div>

                        <!-- Review Title -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Review Title</label>
                            <input type="text" name="title"
                                class="w-full px-3 md:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4b0f27] focus:border-transparent text-sm md:text-base"
                                placeholder="Give your review a title">
                        </div>

                        <!-- Review Comment -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Your Review *</label>
                            <textarea name="comment" required rows="4"
                                class="w-full px-3 md:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4b0f27] focus:border-transparent resize-none text-sm md:text-base"
                                placeholder="Share your experience with this product (minimum 10 characters)"></textarea>
                        </div>

                        <!-- Image Upload -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Add Photos (Optional)</label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 md:p-6 text-center hover:border-[#4b0f27] transition-colors cursor-pointer"
                                onclick="document.getElementById('reviewImages').click()">
                                <svg class="w-10 h-10 md:w-12 md:h-12 mx-auto text-gray-400 mb-2" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p class="text-sm text-gray-600">Click to upload images or drag and drop</p>
                                <p class="text-xs text-gray-500 mt-1">PNG, JPG, WEBP up to 5MB (Max 5 images)</p>
                            </div>
                            <input type="file" id="reviewImages" accept="image/*" multiple class="hidden"
                                onchange="handleImageSelect(event)">

                            <!-- Image Previews -->
                            <div id="imagePreviews" class="grid grid-cols-3 sm:grid-cols-5 gap-2 md:gap-3 mt-4 hidden">
                                <!-- Previews will be added here -->
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex flex-col sm:flex-row gap-3 md:gap-4 pt-2">
                            <button type="button" onclick="closeReviewForm()"
                                class="w-full sm:flex-1 border-2 border-gray-300 text-gray-700 px-4 md:px-6 py-2 md:py-3 rounded-lg font-semibold hover:bg-gray-50 transition-colors uppercase tracking-wide text-sm">
                                Cancel
                            </button>
                            <button type="submit" id="submitReviewBtn"
                                class="w-full sm:flex-1 bg-[#4b0f27] text-white px-4 md:px-6 py-2 md:py-3 rounded-lg font-semibold hover:bg-[#3d0c1f] transition-colors uppercase tracking-wide text-sm">
                                Submit Review
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Write Review Button (Shows when form is hidden) -->
            <div id="writeReviewBtn" class="mb-6 md:mb-8">
                <button onclick="openReviewForm()"
                    class="w-full sm:w-auto bg-[#4b0f27] text-white px-6 md:px-8 py-2.5 md:py-3 rounded-lg font-semibold hover:bg-[#3d0c1f] transition-colors uppercase tracking-wide text-sm shadow-md">
                    ✍️ Write a Review
                </button>
            </div>

            <!-- Reviews List -->
            <div id="reviewsList" class="space-y-4 md:space-y-6">
                <!-- Reviews will be loaded here via AJAX -->
                <div class="text-center py-8 md:py-12">
                    <p class="text-gray-500 text-sm md:text-base">Loading reviews...</p>
                </div>
            </div>

            <!-- Load More Button -->
            <div id="loadMoreContainer" class="text-center mt-6 md:mt-8 hidden">
                <button onclick="loadMoreReviews()"
                    class="w-full sm:w-auto border-2 border-[#4b0f27] text-[#4b0f27] px-6 md:px-8 py-2.5 md:py-3 rounded-lg font-semibold hover:bg-[#4b0f27] hover:text-white transition-colors uppercase tracking-wide text-sm">
                    Load More Reviews
                </button>
            </div>
        </div>
    </div>

<!-- RECOMMENDED PRODUCTS SECTION -->
@if($similarProducts && $similarProducts->count() > 0)
    <div class="w-full bg-[#FAF5ED] pt-8 md:pt-12 pb-12 md:pb-16">
        <div class="max-w-[1800px] mx-auto px-4 sm:px-6 md:px-8">
            <!-- Section Header -->
            <div class="flex flex-col items-center mb-8 md:mb-10">
                <h2 class="text-[#2B2B2B] text-xl md:text-2xl font-medium tracking-wide mb-3 md:mb-4">
                    You may also like
                </h2>
            </div>

            <!-- Products Grid -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 lg:gap-8">
                @foreach($similarProducts as $product)
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
                                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-auto" />
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
                            <p class="text-[#2B2B2B] text-xs md:text-sm font-normal truncate px-2 hover:text-[#5C1F33] transition-colors">
                                {{ $product->name }}
                            </p>
                        </a>
                        <div class="flex items-center justify-center gap-2 mt-1">
                            @if($product->sale_price > 0)
                                <p class="text-[#2B2B2B] text-xs md:text-sm font-bold">
                                    Rs. {{ number_format($product->sale_price) }}
                                </p>
                                <p class="text-gray-400 text-[10px] md:text-xs line-through">
                                    Rs. {{ number_format($product->price) }}
                                </p>
                            @else
                                <p class="text-[#2B2B2B] text-xs md:text-sm font-medium">
                                    Rs. {{ number_format($product->price) }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- View All Button -->
            <div class="text-center mt-10">
                <a href="{{ route('shop') }}" class="inline-block px-8 py-3 bg-white text-[#4b0f27] text-sm font-bold uppercase tracking-wider border-2 border-[#4b0f27] rounded-md hover:bg-[#4b0f27] hover:text-white transition-all duration-300 shadow-md hover:shadow-lg">
                    View All Products
                </a>
            </div>
        </div>
    </div>
@endif

    </div>

@endsection

<style>
    /* Custom Styling for Swiper Pagination Dots to match reference */
    .swiper-pagination-bullet {
        width: 8px;
        height: 8px;
        background: transparent;
        border: 1px solid #4b0f27;
        opacity: 0.6;
    }

    .swiper-pagination-bullet-active {
        background: #4b0f27;
        opacity: 1;
    }

    /* Shimmer Effect - Robust Implementation */
    @keyframes shimmer-move {
        from {
            transform: translateX(-150%) skewX(-15deg);
        }

        to {
            transform: translateX(150%) skewX(-15deg);
        }
    }

    .shimmer-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to right,
                rgba(255, 255, 255, 0) 20%,
                rgba(255, 255, 255, 0.95) 50%,
                rgba(255, 255, 255, 0) 80%);
        animation: shimmer-move 3s infinite linear;
        pointer-events: none;
    }

    /* Lightbox Styles */
    .lightbox-open {
        overflow: hidden;
    }

    /* Marquee Animation */
    .marquee-container {
        width: 100%;
        overflow: hidden;
        position: relative;
    }

    .marquee-content {
        animation: marquee-scroll 20s linear infinite;
        will-change: transform;
    }

    @keyframes marquee-scroll {
        from {
            transform: translateX(0);
        }

        to {
            transform: translateX(-50%);
        }
    }

    /* Pausing on hover is nice but optional */
    .marquee-container:hover .marquee-content {
        animation-play-state: paused;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const marqueeContainer = document.querySelector('.marquee-container');
        const marqueeContent = document.querySelector('.marquee-content');

        if (marqueeContainer && marqueeContent) {
            // Duplicate content enough times to fill width + buffer for smooth loop
            // We clone the content once, so we have [Original][Clone].
            // The animation moves from 0 to -50% (translating exactly the width of one set).
            // This creates a seamless loop.
            const clone = marqueeContent.cloneNode(true);
            clone.setAttribute('aria-hidden', 'true'); // accessibility: hide duplicate
            marqueeContainer.appendChild(clone);

            // If the content is very short, we might need more clones, but for this text length + font size, one clone is usually enough for standard screens.
            // If screen is huge (ultrawide), we might need more logic or just repetition in HTML.
            // For robustness, let's just ensure we have enough width.
        }
    });
</script>

<!-- Fullscreen Lightbox Modal -->
<div id="lightboxModal"
    class="fixed inset-0 z-[1000] hidden bg-black/80 backdrop-blur-sm transition-all duration-300 opacity-0">
    <!-- Close Button (Right Top) -->
    <button onclick="closeLightbox()"
        class="fixed top-6 right-6 z-[1010] p-2 text-white/70 hover:text-white transition-colors duration-200 focus:outline-none">
        <svg class="w-10 h-10 drop-shadow-md" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>

    <div class="flex h-full w-full flex-row">
        <!-- Sidebar Thumbnails (Left Side) -->
        <div class="w-32 lg:w-40 border-r border-white/10 bg-black/20 p-4 pt-16 overflow-y-auto hidden md:block">
            <div class="space-y-3">
                @foreach($initialImages as $index => $img)
                    <div onclick="setLightboxImage('{{ $img }}', {{ $index }})"
                        class="cursor-pointer rounded-md overflow-hidden border-2 border-transparent hover:border-white/50 transition opacity-60 hover:opacity-100 lightbox-thumb"
                        data-index="{{ $index }}">
                        <img src="{{ $img }}" class="w-full h-auto object-cover">
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Main Lightbox Image (Centered) -->
        <div class="flex-1 relative flex items-center justify-center p-2">
            <!-- Navigation Arrows (Subtle & Glassy) -->
            <button onclick="lightboxPrev()"
                class="absolute left-6 z-50 p-4 rounded-full text-white/70 hover:text-white hover:bg-white/10 transition duration-300 focus:outline-none">
                <svg class="w-8 h-8 drop-shadow-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <button onclick="lightboxNext()"
                class="absolute right-6 z-50 p-4 rounded-full text-white/70 hover:text-white hover:bg-white/10 transition duration-300 focus:outline-none">
                <svg class="w-8 h-8 drop-shadow-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7" />
                </svg>
            </button>

            <!-- Main Image Container -->
            <div class="relative h-full w-full flex items-center justify-center p-2">
                <img id="lightboxImage" src=""
                    class="h-[95vh] w-auto max-w-full object-contain shadow-2xl transition-transform duration-500 ease-out transform scale-90 opacity-0 rounded-md">
            </div>
        </div>
    </div>
</div>

<script>
    let swiper;
    let currentLightboxIndex = 0;
    // Make these global
    window.allGalleryImages = @json($initialImages);
    window.variantImages = @json($groupedVariantImages);

    // Auto-select first color if available (or from URL param)
    // Use the same $galleryInitialColorId that was set in the gallery section
    @php
        $firstColorId = $galleryInitialColorId ?? 'null';
        $firstColorName = 'null';
        if ($galleryInitialColorId && $product->colors()->count() > 0) {
            $selectedColorObj = $product->colors()->where('colors.id', $galleryInitialColorId)->first();
            if ($selectedColorObj) {
                $firstColorName = "'" . addslashes($selectedColorObj->name) . "'";
            }
        }
    @endphp
    window.selectedColorId = {{ $firstColorId }};
    window.selectedColorName = {!! $firstColorName !!};

    function updateQty(change) {
        const input = document.getElementById('qtyInput');
        let val = parseInt(input.value) || 1;
        val += change;
        if (val < 1) val = 1;
        input.value = val;
    }

    async function addToCart() {
        const productId = {{ $product->id }};
        const qty = parseInt(document.getElementById('qtyInput').value) || 1;

        // Basic Validation: If product has colors, maybe force selection?
        // Assuming user might want to buy base product if no color selected, OR logic forces selection.
        // Given the UI allows switching, let's assume if colors exist, one must be picked if strict, 
        // OR we just send what we have. API handles it.

        const btn = document.getElementById('addToCartBtn');
        const originalText = btn.innerText;
        btn.innerText = 'Adding...';
        btn.disabled = true;

        try {
            const response = await fetch('{{ route("cart.add") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: qty,
                    color_id: selectedColorId
                })
            });

            const result = await response.json();

            if (result.success) {
                // Update Cart Drawer via global function (we will define this in cart_drawer.blade.php)
                if (typeof updateCartDrawerUI === 'function') {
                    updateCartDrawerUI(result);
                }

                // Open Drawer
                const drawer = document.getElementById('cartDrawer');
                if (drawer && drawer.classList.contains('translate-x-full')) {
                    toggleCartDrawer();
                }
            } else {
                alert(result.message || 'Failed to add to cart');
            }

        } catch (error) {
            console.error('Cart Error:', error);
            alert('Something went wrong. Please try again.');
        } finally {
            btn.innerText = originalText;
            btn.disabled = false;
        }
    }

    function switchColor(colorId, colorName, element) {
        window.selectedColorId = colorId;
        window.selectedColorName = colorName;
        const images = variantImages[colorId];

        if (images && images.length > 0) {
            updateGallery(images);

            // Highlight active color in color swatches (if any)
            document.querySelectorAll('.color-swatch > div').forEach(el => {
                el.classList.remove('border-[#4b0f27]');
                el.classList.add('border-transparent');
            });
            if (element) {
                const box = element.querySelector('div');
                if (box) {
                    box.classList.remove('border-transparent');
                    box.classList.add('border-[#4b0f27]');
                }
            }
        }
        
        // Always update More Colors section - hide selected color, show others
        updateMoreColorsSection(colorId);
    }
    
    function updateMoreColorsSection(selectedColorId) {
        const moreColorItems = document.querySelectorAll('.more-color-item');
        
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

    // On Load: Highlight default color if selected
    document.addEventListener('DOMContentLoaded', () => {
        if (selectedColorId) {
            const swatch = document.querySelector(`.color-swatch[onclick*="switchColor(${selectedColorId}"] > div`);
            if (swatch) {
                swatch.classList.remove('border-transparent');
                swatch.classList.add('border-[#4b0f27]');
            }
            
            // Hide the initially selected color from More Colors section
            updateMoreColorsSection(selectedColorId);
        }
    });

    function updateGallery(images) {
        allGalleryImages = images;

        // Destroy existing swiper instance
        if (swiper) {
            swiper.destroy(true, true);
        }

        // Rebuild DOM for Swiper - use specific main swiper container
        const swiperWrapper = document.querySelector('#mainSwiperContainer .swiper-wrapper');
        if (!swiperWrapper) return;
        
        swiperWrapper.innerHTML = images.map((img, index) => `
            <div class="swiper-slide w-full h-auto relative group">
                <img src="${img}" alt="Product Image" class="w-full h-auto">
                <button onclick="openLightbox(${index})" class="absolute top-4 right-4 z-20 p-2 bg-white/50 hover:bg-white rounded-lg text-[#4b0f27] backdrop-blur-sm transition shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                </button>
            </div>
        `).join('');

        // Rebuild Thumbnails
        const thumbContainer = document.getElementById('thumbnailGrid');
        if (thumbContainer) {
            // Show only first 5
            const displayThumbs = images.slice(0, 5);
            thumbContainer.innerHTML = displayThumbs.map((img, index) => `
                <div onclick="goToSlide(${index})" class="gallery-thumb cursor-pointer rounded-lg border-2 ${index === 0 ? 'border-[#4b0f27]' : 'border-gray-200'} p-1 bg-white hover:border-[#4b0f27] transition-colors duration-200">
                     <div class="aspect-square w-full overflow-hidden rounded-md">
                        <img src="${img}" class="h-full w-full object-cover">
                     </div>
                </div>
            `).join('');
        }

        // Update Lightbox Sidebar
        const lightboxSidebar = document.querySelector('#lightboxModal .w-32 .space-y-3');
        if (lightboxSidebar) {
            lightboxSidebar.innerHTML = images.map((img, index) => `
                <div onclick="setLightboxImage('${img}', ${index})" class="cursor-pointer rounded-md overflow-hidden border-2 border-transparent hover:border-white/50 transition opacity-60 hover:opacity-100 lightbox-thumb" data-index="${index}">
                    <img src="${img}" class="w-full h-auto object-cover">
                </div>
             `).join('');
        }

        // Re-initialize Swiper
        initSwiper();
        
        // Ensure first thumbnail is highlighted after rebuild
        setTimeout(() => {
            updateActiveThumbnail(0);
        }, 100);
    }

    function initSwiper() {
        swiper = new Swiper('.main-swiper', {
            loop: true,
            autoHeight: true,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            on: {
                slideChange: function () {
                    const activeIndex = this.realIndex;
                    updateActiveThumbnail(activeIndex);
                },
                init: function() {
                    // Ensure first thumbnail is highlighted on init
                    updateActiveThumbnail(0);
                }
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        initSwiper();
    });

    function goToSlide(index) {
        if (swiper) {
            swiper.slideToLoop(index);
            updateActiveThumbnail(index);
        }
    }

    function updateActiveThumbnail(index) {
        const thumbnails = document.querySelectorAll('.gallery-thumb');
        thumbnails.forEach((thumb, i) => {
            if (i === index) {
                thumb.classList.remove('border-gray-200');
                thumb.classList.add('border-[#4b0f27]');
            } else {
                thumb.classList.remove('border-[#4b0f27]');
                thumb.classList.add('border-gray-200');
            }
        });
    }

    // Accordion Logic
    function toggleAccordion(button) {
        const content = button.nextElementSibling;
        const icon = button.querySelector('.accordion-icon');

        // Close other accordions (Optional - if you want one open at a time)
        /*
        document.querySelectorAll('.accordion-content').forEach(el => {
            if (el !== content) {
                el.style.maxHeight = null;
                el.classList.remove('opacity-100');
                el.classList.add('opacity-0');
                el.parentElement.querySelector('.accordion-icon').classList.remove('rotate-180');
            }
        });
        */

        if (content.style.maxHeight) {
            // Close
            content.style.maxHeight = null;
            content.classList.remove('opacity-100');
            content.classList.add('opacity-0');
            icon.classList.remove('rotate-180');
        } else {
            // Open
            content.style.maxHeight = content.scrollHeight + "px";
            content.classList.remove('opacity-0');
            content.classList.add('opacity-100');
            icon.classList.add('rotate-180');
        }
    }
</script>

@push('scripts')
    <script>
        // Make globally accessible with a unique name
        window.processBuyNow = async function () {
            const btn = document.querySelector('button[onclick="processBuyNow()"]');
            if (btn) {
                const originalText = btn.innerHTML;
                btn.innerHTML = 'Processing...';
                btn.disabled = true;
            }

            const qty = parseInt(document.getElementById('qtyInput').value);
            const productId = {{ $product->id }};

            try {
                const response = await fetch('{{ route("cart.add") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: qty,
                        color_name: selectedColorName
                    })
                });

                const data = await response.json();

                if (data.success) {
                    // Redirect directly to checkout
                    window.location.href = '{{ route("checkout") }}';
                } else {
                    showToast(data.message, 'error');
                    if (btn) {
                        btn.innerHTML = 'BUY IT NOW'; // Reset text
                        btn.disabled = false;
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Something went wrong', 'error');
                if (btn) {
                    btn.innerHTML = 'BUY IT NOW'; // Reset text
                    btn.disabled = false;
                }
            }
        }

        /* --- LIGHTBOX LOGIC --- */
        window.openLightbox = function (index) {
            currentLightboxIndex = index; // Initial index
            const modal = document.getElementById('lightboxModal');
            const img = document.getElementById('lightboxImage');

            // Show Modal
            modal.classList.remove('hidden');
            // Small delay to allow display:block to apply before opacity transition
            setTimeout(() => {
                modal.classList.remove('opacity-0', 'pointer-events-none');
                document.body.classList.add('lightbox-open');
                updateLightboxView();
            }, 10);
        }

        window.closeLightbox = function () {
            const modal = document.getElementById('lightboxModal');
            modal.classList.add('opacity-0', 'pointer-events-none');
            document.body.classList.remove('lightbox-open');

            // Wait for transition
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        function updateLightboxView() {
            const img = document.getElementById('lightboxImage');
            const src = allGalleryImages[currentLightboxIndex];

            // Immediate switch without delay
            // Reset transform slightly for effect but don't wait
            img.classList.remove('scale-100', 'opacity-100');
            img.classList.add('scale-95', 'opacity-80'); // Slight fade out 

            img.src = src;

            img.onload = () => {
                img.classList.remove('scale-95', 'opacity-80');
                img.classList.add('scale-100', 'opacity-100');
            };

            // Highlight thumbnail
            document.querySelectorAll('.lightbox-thumb').forEach((thumb, i) => {
                if (i === currentLightboxIndex) {
                    thumb.classList.add('border-white', 'opacity-100');
                    thumb.classList.remove('border-transparent', 'opacity-60');
                    thumb.scrollIntoView({ behavior: 'smooth', block: 'center' });
                } else {
                    thumb.classList.remove('border-white', 'opacity-100');
                    thumb.classList.add('border-transparent', 'opacity-60');
                }
            });
        }

        window.setLightboxImage = function (src, index) {
            currentLightboxIndex = index;
            updateLightboxView();
        }

        window.lightboxNext = function () {
            currentLightboxIndex = (currentLightboxIndex + 1) % allGalleryImages.length;
            updateLightboxView();
        }

        window.lightboxPrev = function () {
            currentLightboxIndex = (currentLightboxIndex - 1 + allGalleryImages.length) % allGalleryImages.length;
            updateLightboxView();
        }

        // Close on Escape key
        document.addEventListener('keydown', function (event) {
            if (event.key === "Escape") {
                closeLightbox();
            }
        });

        /* ===== REVIEW SYSTEM JAVASCRIPT ===== */

        // Global variables for review system
        let currentPage = 1;
        let uploadedImages = [];
        let selectedRating = 0;

        // Load reviews on page load
        document.addEventListener('DOMContentLoaded', function () {
            loadReviews();
        });

        // Load reviews via AJAX
        async function loadReviews(page = 1) {
            try {
                const productId = {{ $product->id }};
                const response = await fetch(`/reviews/product/${productId}?page=${page}&per_page=10`);
                const data = await response.json();

                if (data.success) {
                    displayReviews(data.reviews.data, page === 1);
                    updateReviewStats(data.stats);

                    // Show/hide load more button
                    if (data.reviews.next_page_url) {
                        document.getElementById('loadMoreContainer').classList.remove('hidden');
                        currentPage = page;
                    } else {
                        document.getElementById('loadMoreContainer').classList.add('hidden');
                    }
                }
            } catch (error) {
                console.error('Error loading reviews:', error);
                document.getElementById('reviewsList').innerHTML = '<div class="text-center py-12"><p class="text-gray-500">Failed to load reviews</p></div>';
            }
        }

        // Display reviews
        function displayReviews(reviews, clearExisting = true) {
            const container = document.getElementById('reviewsList');

            if (clearExisting) {
                container.innerHTML = '';
            }

            if (reviews.length === 0 && clearExisting) {
                container.innerHTML = '<div class="text-center py-8 md:py-12"><p class="text-gray-500 text-sm md:text-base">No reviews yet. Be the first to review this product!</p></div>';
                return;
            }

            reviews.forEach(review => {
                const reviewHTML = `
                                    <div class="bg-white rounded-lg p-4 md:p-6 border border-gray-200 hover:shadow-md transition-shadow">
                                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 mb-3">
                                            <div class="flex-1">
                                                <div class="flex flex-wrap items-center gap-2 mb-1">
                                                    <h4 class="font-semibold text-gray-900 text-sm md:text-base">${escapeHtml(review.name)}</h4>
                                                    ${review.is_verified_purchase ? '<span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded">✓ Verified</span>' : ''}
                                                </div>
                                                <div class="flex flex-wrap items-center gap-2">
                                                    <div class="flex items-center">
                                                        ${generateStars(review.rating)}
                                                    </div>
                                                    <span class="text-xs md:text-sm text-gray-500">${review.formatted_date}</span>
                                                </div>
                                            </div>
                                        </div>

                                        ${review.title ? `<h5 class="font-semibold text-gray-900 mb-2 text-sm md:text-base">${escapeHtml(review.title)}</h5>` : ''}
                                        <p class="text-gray-700 leading-relaxed mb-4 text-sm md:text-base">${escapeHtml(review.comment)}</p>

                                        ${review.images && review.images.length > 0 ? `
                                            <div class="flex gap-2 flex-wrap mb-4">
                                                ${review.images.map(img => `
                                                    <img src="${img.image_url}" alt="Review image" class="w-16 h-16 md:w-20 md:h-20 object-cover rounded-lg cursor-pointer hover:opacity-80 transition-opacity border border-gray-200" onclick="openImageModal('${img.image_url}')">
                                                `).join('')}
                                            </div>
                                        ` : ''}
                                    </div>
                                `;
                container.insertAdjacentHTML('beforeend', reviewHTML);
            });
        }

        // Update review statistics
        function updateReviewStats(stats) {
            document.getElementById('averageRating').textContent = stats.average_rating.toFixed(1);
            document.getElementById('totalReviews').textContent = stats.total_reviews;

            // Update stars
            const starsContainer = document.getElementById('averageStars');
            starsContainer.innerHTML = generateStars(stats.average_rating);

            // Update rating breakdown
            for (let i = 1; i <= 5; i++) {
                const bar = document.querySelector(`[data-rating="${i}"]`);
                const count = document.querySelector(`[data-count="${i}"]`);

                if (bar && count && stats.rating_breakdown[i]) {
                    bar.style.width = stats.rating_breakdown[i].percentage + '%';
                    count.textContent = stats.rating_breakdown[i].count;
                }
            }
        }

        // Generate star HTML
        function generateStars(rating) {
            let html = '';
            for (let i = 1; i <= 5; i++) {
                if (i <= rating) {
                    html += '<svg class="w-5 h-5 text-[#fbbf24]" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>';
                } else {
                    html += '<svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>';
                }
            }
            return html;
        }

        // Open review form (inline)
        function openReviewForm() {
            const formContainer = document.getElementById('reviewFormContainer');
            const writeBtn = document.getElementById('writeReviewBtn');

            formContainer.classList.remove('hidden');
            writeBtn.classList.add('hidden');

            // Smooth scroll to form
            setTimeout(() => {
                formContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }, 100);
        }

        // Close review form (inline)
        function closeReviewForm() {
            const formContainer = document.getElementById('reviewFormContainer');
            const writeBtn = document.getElementById('writeReviewBtn');

            formContainer.classList.add('hidden');
            writeBtn.classList.remove('hidden');

            // Reset form
            document.getElementById('reviewForm').reset();
            selectedRating = 0;
            uploadedImages = [];
            updateStarDisplay();
            document.getElementById('imagePreviews').classList.add('hidden');
            document.getElementById('imagePreviews').innerHTML = '';
        }

        // Set rating
        function setRating(rating) {
            selectedRating = rating;
            document.getElementById('ratingInput').value = rating;
            updateStarDisplay();
        }

        // Update star display
        function updateStarDisplay() {
            const stars = document.querySelectorAll('.rating-star');
            const ratingText = document.getElementById('ratingText');

            stars.forEach((star, index) => {
                if (index < selectedRating) {
                    star.classList.remove('text-gray-300');
                    star.classList.add('text-[#fbbf24]');
                } else {
                    star.classList.add('text-gray-300');
                    star.classList.remove('text-[#fbbf24]');
                }
            });

            const ratingLabels = ['', 'Poor', 'Fair', 'Good', 'Very Good', 'Excellent'];
            ratingText.textContent = selectedRating > 0 ? ratingLabels[selectedRating] : '';
        }

        // Handle image selection
        async function handleImageSelect(event) {
            const files = Array.from(event.target.files);

            if (files.length + uploadedImages.length > 5) {
                alert('You can upload maximum 5 images');
                return;
            }

            const previewContainer = document.getElementById('imagePreviews');
            previewContainer.classList.remove('hidden');

            for (const file of files) {
                if (file.size > 5 * 1024 * 1024) {
                    alert(`${file.name} is too large. Maximum size is 5MB`);
                    continue;
                }

                // Show preview immediately
                const reader = new FileReader();
                reader.onload = (e) => {
                    const previewId = 'preview_' + Date.now() + Math.random();
                    const previewHTML = `
                                                <div id="${previewId}" class="relative group">
                                                    <img src="${e.target.result}" class="w-full h-20 object-cover rounded-lg border-2 border-gray-200">
                                                    <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity rounded-lg">
                                                        <span class="text-white text-xs">Uploading...</span>
                                                    </div>
                                                </div>
                                            `;
                    previewContainer.insertAdjacentHTML('beforeend', previewHTML);
                };
                reader.readAsDataURL(file);

                // Upload to ImageKit
                try {
                    const formData = new FormData();
                    formData.append('image', file);

                    const response = await fetch('{{ route("reviews.upload-image") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    });

                    const data = await response.json();

                    if (data.success) {
                        uploadedImages.push(JSON.stringify(data.image));
                    } else {
                        alert('Failed to upload ' + file.name);
                    }
                } catch (error) {
                    console.error('Upload error:', error);
                    alert('Failed to upload ' + file.name);
                }
            }
        }

        // Submit review form
        document.getElementById('reviewForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            if (selectedRating === 0) {
                alert('Please select a rating');
                return;
            }

            const formData = new FormData(this);
            formData.set('rating', selectedRating);

            // Add uploaded images
            uploadedImages.forEach((img, index) => {
                formData.append(`images[${index}]`, img);
            });

            const submitBtn = document.getElementById('submitReviewBtn');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Submitting...';
            submitBtn.disabled = true;

            try {
                const response = await fetch('{{ route("reviews.store") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    alert(data.message);
                    closeReviewForm();
                    loadReviews(); // Reload reviews
                } else {
                    alert(data.message || 'Failed to submit review');
                }
            } catch (error) {
                console.error('Submit error:', error);
                alert('Failed to submit review. Please try again.');
            } finally {
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            }
        });

        // Helper function to escape HTML
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Open image in modal (simple version)
        function openImageModal(imageUrl) {
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 z-[1001] bg-black/90 flex items-center justify-center p-4';
            modal.onclick = () => modal.remove();
            modal.innerHTML = `<img src="${imageUrl}" class="max-w-full max-h-full object-contain">`;
            document.body.appendChild(modal);
        }

        // Quick Add to Cart for Recommended Products
        async function quickAddToCart(productId) {
            try {
                const response = await fetch('{{ route("cart.add") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: 1
                    })
                });

                const result = await response.json();

                if (result.success) {
                    // Update Cart Drawer
                    if (typeof updateCartDrawerUI === 'function') {
                        updateCartDrawerUI(result);
                    }

                    // Open Cart Drawer
                    const drawer = document.getElementById('cartDrawer');
                    if (drawer && drawer.classList.contains('translate-x-full')) {
                        toggleCartDrawer();
                    }

                    // Show success message
                    alert('Product added to cart!');
                } else {
                    alert(result.message || 'Failed to add to cart');
                }
            } catch (error) {
                console.error('Cart Error:', error);
                alert('Something went wrong. Please try again.');
            }
        }

        // Initialize Product Swipers for Recommended Products
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

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            initProductSwipers();
        });

        // Share Functions
        function toggleShareMenu() {
            const shareMenu = document.getElementById('shareMenu');
            shareMenu.classList.toggle('hidden');
        }

        function shareOn(platform) {
            const productUrl = '{{ route("product.show", $product->slug) }}';
            const productTitle = '{{ addslashes($product->name) }}';
            const productDescription = '{{ addslashes(Str::limit(strip_tags($product->description), 100)) }}';
            const productImage = '{{ $product->image_url }}';
            
            let shareUrl = '';
            
            switch(platform) {
                case 'whatsapp':
                    shareUrl = `https://wa.me/?text=${encodeURIComponent(productTitle + ' - ' + productUrl)}`;
                    window.open(shareUrl, '_blank');
                    break;
                    
                case 'facebook':
                    shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(productUrl)}`;
                    window.open(shareUrl, '_blank', 'width=600,height=400');
                    break;
                    
                case 'twitter':
                    shareUrl = `https://twitter.com/intent/tweet?url=${encodeURIComponent(productUrl)}&text=${encodeURIComponent(productTitle)}`;
                    window.open(shareUrl, '_blank', 'width=600,height=400');
                    break;
                    
                case 'copy':
                    // Fallback for older browsers
                    if (navigator.clipboard && navigator.clipboard.writeText) {
                        navigator.clipboard.writeText(productUrl).then(() => {
                            showCopySuccess();
                        }).catch(err => {
                            fallbackCopyTextToClipboard(productUrl);
                        });
                    } else {
                        fallbackCopyTextToClipboard(productUrl);
                    }
                    break;
            }
        }

        // Show copy success feedback
        function showCopySuccess() {
            const copyBtn = document.querySelector('button[onclick*="copy"]');
            if (copyBtn) {
                const span = copyBtn.querySelector('span');
                const icon = copyBtn.querySelector('div');
                const originalText = span.textContent;
                
                span.textContent = 'Copied!';
                icon.classList.remove('bg-gray-600');
                icon.classList.add('bg-green-500');
                
                setTimeout(() => {
                    span.textContent = originalText;
                    icon.classList.remove('bg-green-500');
                    icon.classList.add('bg-gray-600');
                }, 2000);
            }
        }

        // Fallback copy method for older browsers
        function fallbackCopyTextToClipboard(text) {
            const textArea = document.createElement("textarea");
            textArea.value = text;
            textArea.style.position = "fixed";
            textArea.style.top = "0";
            textArea.style.left = "0";
            textArea.style.width = "2em";
            textArea.style.height = "2em";
            textArea.style.padding = "0";
            textArea.style.border = "none";
            textArea.style.outline = "none";
            textArea.style.boxShadow = "none";
            textArea.style.background = "transparent";
            
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            
            try {
                const successful = document.execCommand('copy');
                if (successful) {
                    showCopySuccess();
                } else {
                    alert('Failed to copy link. Please copy manually: ' + text);
                }
            } catch (err) {
                alert('Failed to copy link. Please copy manually: ' + text);
            }
            
            document.body.removeChild(textArea);
        }

        // Close share menu when clicking outside
        document.addEventListener('click', function(event) {
            const shareButton = document.getElementById('shareButton');
            const shareMenu = document.getElementById('shareMenu');
            
            if (shareButton && shareMenu && !shareButton.contains(event.target) && !shareMenu.contains(event.target)) {
                shareMenu.classList.add('hidden');
            }
        });
    </script>
@endpush