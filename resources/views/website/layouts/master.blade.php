<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $themeColors = \App\Models\SiteSetting::where('group', 'colors')->pluck('value', 'key')->toArray();
        $themeFonts = \App\Models\SiteSetting::where('group', 'fonts')->pluck('value', 'key')->toArray();
        $fontHeading = $themeFonts['font_heading'] ?? 'Playfair Display';
        $fontBody = $themeFonts['font_body'] ?? 'Inter';
    @endphp
    <meta name="theme-color" content="{{ $themeColors['color_primary'] ?? '#5C1F33' }}">
    <title>@yield('title', ($siteSettings['site_name'] ?? 'Fashion Store') . ' - Premium Fashion Store')</title>

    <!-- Favicon -->
    @if(!empty($siteSettings['site_favicon']))
    <link rel="icon" type="image/png" href="{{ $siteSettings['site_favicon'] }}">
    @else
    <link rel="icon" type="image/png" href="{{ asset('website/assets/images/favicon.png') }}">
    @endif

    <!-- Dynamic Theme CSS Variables -->
    <style>
        :root {
            --color-primary: {{ $themeColors['color_primary'] ?? '#5C1F33' }};
            --color-primary-dark: {{ $themeColors['color_primary_dark'] ?? '#4B0F27' }};
            --color-primary-light: {{ $themeColors['color_primary_light'] ?? '#7A2D45' }};
            --color-bg-primary: {{ $themeColors['color_bg_primary'] ?? '#FAF5ED' }};
            --color-bg-secondary: {{ $themeColors['color_bg_secondary'] ?? '#EDE5DA' }};
            --color-bg-tertiary: {{ $themeColors['color_bg_tertiary'] ?? '#EEDDD0' }};
            --color-text-primary: {{ $themeColors['color_text_primary'] ?? '#2B2B2B' }};
            --color-text-secondary: {{ $themeColors['color_text_secondary'] ?? '#4B4B4B' }};
            --color-text-muted: {{ $themeColors['color_text_muted'] ?? '#6B6B6B' }};
            --color-accent-gold: {{ $themeColors['color_accent_gold'] ?? '#E6B873' }};
            --color-accent-success: {{ $themeColors['color_accent_success'] ?? '#495530' }};
            --color-btn-primary: {{ $themeColors['color_btn_primary'] ?? '#3D0C1F' }};
            --color-btn-primary-hover: {{ $themeColors['color_btn_primary_hover'] ?? '#2a0815' }};
            --color-btn-secondary: {{ $themeColors['color_btn_secondary'] ?? '#495530' }};
            --color-btn-secondary-hover: {{ $themeColors['color_btn_secondary_hover'] ?? '#384225' }};
            --color-header-bg: {{ $themeColors['color_header_bg'] ?? '#FAF5ED' }};
            --color-header-text: {{ $themeColors['color_header_text'] ?? '#441227' }};
            --color-footer-bg: {{ $themeColors['color_footer_bg'] ?? '#FAF5ED' }};
            --color-footer-text: {{ $themeColors['color_footer_text'] ?? '#441227' }};
            --color-border: {{ $themeColors['color_border'] ?? '#D6B27A' }};
            --font-heading: '{{ $fontHeading }}', serif;
            --font-body: '{{ $fontBody }}', sans-serif;
        }
    </style>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Tailwind Config -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'cream': '#FAF5ED',
                        'wine': '#5C1F33',
                        'wine-dark': '#4A1828',
                        'gold': '#D6B27A',
                        'dark-brown': '#2A1810',
                    }
                }
            }
        }
    </script>

    <!-- Google Fonts - Dynamic Loading -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    @php
        $fontsToLoad = collect([$fontHeading, $fontBody])->unique()->map(function($font) {
            return str_replace(' ', '+', $font) . ':wght@400;500;600;700';
        })->implode('&family=');
    @endphp
    <link href="https://fonts.googleapis.com/css2?family={{ $fontsToLoad }}&display=swap" rel="stylesheet">

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>


    <style>
        .font-serif-elegant {
            font-family: var(--font-heading);
        }

        .font-sans-premium {
            font-family: var(--font-body);
        }

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        
        /* Lazy Loading Styles */
        .lazy-image {
            opacity: 0;
            transition: opacity 0.4s ease-in-out;
        }
        
        .lazy-image.loaded {
            opacity: 1;
        }
        
        /* Skeleton placeholder for lazy images */
        .lazy-wrapper {
            position: relative;
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }
        
        .lazy-wrapper.loaded {
            background: none;
            animation: none;
        }
        
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        
        /* Native lazy loading fallback */
        img[loading="lazy"] {
            opacity: 1;
        }
        
        /* Apply fonts globally */
        body {
            font-family: var(--font-body);
        }
        
        h1, h2, h3, h4, h5, h6, .heading {
            font-family: var(--font-heading);
        }
    </style>
</head>

<body class="font-serif-elegant tracking-wide bg-cream text-wine-dark antialiased">

    @include('website.includes.header')

    @include('website.includes.cart_drawer')
    @include('website.includes.wishlist_drawer')
    @include('website.includes.search_sidebar')

    @yield('content')

    @include('website.includes.footer')

    <!-- JavaScript Files -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <!-- Premium Header JavaScript -->
    <script>
        // Pure Vanilla JavaScript for Premium Header
        document.addEventListener('DOMContentLoaded', function () {

            // Smooth scroll for navigation links
            document.querySelectorAll('nav a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    const href = this.getAttribute('href');
                    if (href !== '#' && document.querySelector(href)) {
                        e.preventDefault();
                        document.querySelector(href).scrollIntoView({
                            behavior: 'smooth'
                        });
                    }
                });
            });

            // Add active state to current nav item
            const currentPath = window.location.pathname;
            document.querySelectorAll('nav a').forEach(link => {
                if (link.getAttribute('href') === currentPath ||
                    (currentPath === '/' && link.textContent.trim() === 'Home')) {
                    link.classList.add('border', 'border-white', 'rounded-full', 'px-6', 'py-2');
                }
            });
            
            // Initialize Lazy Loading
            initLazyLoading();
        });
        
        // Professional Lazy Loading with Intersection Observer
        function initLazyLoading() {
            // Check for native lazy loading support
            if ('loading' in HTMLImageElement.prototype) {
                // Browser supports native lazy loading
                document.querySelectorAll('img[data-src]').forEach(img => {
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                    img.classList.add('loaded');
                    if (img.parentElement.classList.contains('lazy-wrapper')) {
                        img.parentElement.classList.add('loaded');
                    }
                });
            } else {
                // Fallback to Intersection Observer
                const lazyImageObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            if (img.dataset.src) {
                                img.src = img.dataset.src;
                                img.removeAttribute('data-src');
                            }
                            img.classList.add('loaded');
                            if (img.parentElement.classList.contains('lazy-wrapper')) {
                                img.parentElement.classList.add('loaded');
                            }
                            observer.unobserve(img);
                        }
                    });
                }, {
                    rootMargin: '50px 0px',
                    threshold: 0.01
                });
                
                document.querySelectorAll('img[data-src]').forEach(img => {
                    lazyImageObserver.observe(img);
                });
            }
        }
        
        // Re-initialize lazy loading for dynamically added images
        window.refreshLazyLoading = function() {
            const images = document.querySelectorAll('img[data-src]:not(.loaded)');
            if ('loading' in HTMLImageElement.prototype) {
                images.forEach(img => {
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                    img.classList.add('loaded');
                    if (img.parentElement.classList.contains('lazy-wrapper')) {
                        img.parentElement.classList.add('loaded');
                    }
                });
            } else {
                const lazyImageObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            if (img.dataset.src) {
                                img.src = img.dataset.src;
                                img.removeAttribute('data-src');
                            }
                            img.classList.add('loaded');
                            if (img.parentElement.classList.contains('lazy-wrapper')) {
                                img.parentElement.classList.add('loaded');
                            }
                            observer.unobserve(img);
                        }
                    });
                }, { rootMargin: '50px 0px', threshold: 0.01 });
                
                images.forEach(img => lazyImageObserver.observe(img));
            }
        };
    </script>

    @stack('scripts')
</body>

</html>