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
    <title>@yield('title', $siteSettings['site_name'] ?? 'Fashion Store')</title>

    <!-- SEO Meta Tags -->
    @yield('meta')

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

    <style>
        .font-serif-elegant {
            font-family: var(--font-heading);
        }

        .font-sans-premium {
            font-family: var(--font-body);
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

    @yield('content')

    @stack('scripts')

</body>

</html>