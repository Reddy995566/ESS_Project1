@extends('website.layouts.master')

@section('title', 'About Us - ' . ($siteSettings['site_name'] ?? 'Fashion Store'))

@section('content')

    <!-- ABOUT US CONTENT -->
    <section class="w-full py-12 md:py-20">
        <div class="max-w-[700px] mx-auto px-4 md:px-0">

            <h1 class="text-3xl md:text-5xl font-medium text-[#2B2B2B] mb-2 font-handwritten" style="font-family: 'Playfair Display', serif;">
                About Us
            </h1>

            <p class="text-sm md:text-base font-medium text-[#2B2B2B] mb-8">
                Welcome to Fashion Store!
            </p>

            <div class="space-y-6 text-sm md:text-base text-[#2B2B2B] leading-relaxed">
                <p>
                    At Fashion Store, we believe that fabric is more than just material—it's an expression of culture,
                    elegance, and individuality.
                    Rooted in tradition yet inspired by contemporary tastes, Fashion Store brings you a curated collection of
                    exquisite sarees, suits, and dupattas that celebrate the art of Indian textiles.
                </p>

                <p>
                    Each piece at Fashion Store is thoughtfully crafted, blending timeless craftsmanship with modern
                    aesthetics. From handpicked weaves to delicate embellishments, our collections are designed to make
                    every moment special—whether it's a festive gathering, a family occasion, or your everyday elegance.
                </p>

                <p>
                    Discover fabrics that tell stories. Wrap yourself in heritage. Create your own statement with
                    Fashion Store.
                </p>
            </div>

        </div>
    </section>

@endsection
