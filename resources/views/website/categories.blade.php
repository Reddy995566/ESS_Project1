@extends('website.layouts.master')

@section('title', 'All Categories - ' . ($siteSettings['site_name'] ?? 'Fashion Store'))

@section('content')

<!-- Hero Section -->
<section class="relative py-16 md:py-24 overflow-hidden" style="background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%);">
    <!-- Decorative Elements -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-10 left-10 w-32 h-32 border border-white rounded-full"></div>
        <div class="absolute bottom-10 right-10 w-48 h-48 border border-white rounded-full"></div>
        <div class="absolute top-1/2 left-1/4 w-20 h-20 border border-white rotate-45"></div>
    </div>
    
    <div class="max-w-[1800px] mx-auto px-4 md:px-6 lg:px-12 relative z-10">
        <div class="text-center">
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-serif-elegant font-medium text-white mb-4">
                Explore Our Collections
            </h1>
            <p class="text-white/80 text-sm md:text-base max-w-2xl mx-auto">
                It's time to design your new wardrobe with our elegant and trendy DUST Collections
            </p>
            
            <!-- Breadcrumb -->
            <nav class="mt-6 flex items-center justify-center gap-2 text-sm text-white/70">
                <a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-white">Categories</span>
            </nav>
        </div>
    </div>
</section>

<!-- Categories Grid Section -->
<section class="py-12 md:py-16" style="background-color: var(--color-bg-primary);">
    <div class="max-w-[1800px] mx-auto px-4 md:px-6 lg:px-12">
        
        <!-- Section Header -->
        <div class="text-center mb-10 md:mb-14">
            <span class="inline-block px-4 py-1.5 text-xs font-semibold uppercase tracking-wider rounded-full mb-4" style="background-color: var(--color-bg-secondary); color: var(--color-primary);">
                {{ $categories->count() }} Categories
            </span>
            <h2 class="text-2xl md:text-3xl font-serif-elegant font-medium" style="color: var(--color-text-primary);">
                Shop By Category
            </h2>
        </div>

        <!-- Categories Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 md:gap-6">
            @forelse($categories as $category)
            <a href="{{ route('shop', ['category' => $category->slug]) }}" 
               class="group relative block rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2">
                
                <!-- Category Image -->
                <div class="aspect-[4/5] relative overflow-hidden">
                    @if($category->imagekit_url)
                    <img src="{{ $category->getOptimizedImageUrl(400, 500, 85) }}" 
                         alt="{{ $category->name }}"
                         class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                         loading="lazy">
                    @else
                    <div class="w-full h-full flex items-center justify-center" style="background: linear-gradient(135deg, var(--color-primary-light) 0%, var(--color-primary) 100%);">
                        <svg class="w-16 h-16 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    @endif
                    
                    <!-- Gradient Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                </div>
                
                <!-- Category Info -->
                <div class="absolute bottom-0 left-0 right-0 p-4 md:p-5">
                    <!-- Category Name -->
                    <h3 class="text-base md:text-lg font-serif-elegant font-medium text-white mb-1 group-hover:translate-x-1 transition-transform duration-300">
                        {{ $category->name }}
                    </h3>
                    
                    <!-- Product Count -->
                    <span class="text-white/70 text-xs md:text-sm">
                        {{ $category->products_count }} {{ Str::plural('Product', $category->products_count) }}
                    </span>
                </div>
                
                <!-- Featured Badge -->
                @if($category->is_featured)
                <div class="absolute top-3 left-3">
                    <span class="inline-flex items-center gap-1 px-2 py-1 text-[10px] font-semibold rounded-full text-white" style="background-color: var(--color-accent-gold);">
                        <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        Featured
                    </span>
                </div>
                @endif
            </a>
            @empty
            <div class="col-span-full text-center py-16">
                <svg class="w-16 h-16 mx-auto mb-4" style="color: var(--color-text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                <h3 class="text-lg font-medium mb-2" style="color: var(--color-text-primary);">No Categories Found</h3>
                <p class="text-sm" style="color: var(--color-text-muted);">Categories will appear here once added.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 md:py-20 relative overflow-hidden" style="background-color: var(--color-bg-tertiary);">
    <div class="max-w-[1800px] mx-auto px-4 md:px-6 lg:px-12">
        <div class="max-w-3xl mx-auto text-center">
            <h2 class="text-2xl md:text-3xl font-serif-elegant font-medium mb-4" style="color: var(--color-text-primary);">
                Can't Find What You're Looking For?
            </h2>
            <p class="mb-8" style="color: var(--color-text-secondary);">
                Browse our complete collection or use our search to find exactly what you need
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('shop') }}" 
                   class="inline-flex items-center justify-center gap-2 px-8 py-3.5 text-white font-medium rounded-lg transition-all duration-300 hover:opacity-90 shadow-lg" style="background-color: var(--color-btn-primary);">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    Shop All Products
                </a>
                <button onclick="document.getElementById('search-btn').click()" 
                        class="inline-flex items-center justify-center gap-2 px-8 py-3.5 font-medium rounded-lg transition-all duration-300" style="border: 2px solid var(--color-primary); color: var(--color-primary);">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Search Products
                </button>
            </div>
        </div>
    </div>
</section>

@endsection
