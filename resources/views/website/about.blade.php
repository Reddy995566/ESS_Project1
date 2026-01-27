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
                Welcome to Switch2Kart!
            </p>

            <div class="space-y-6 text-sm md:text-base text-[#2B2B2B] leading-relaxed">
                <p>
                    We at Switch2Kart are committed to delivering premium-quality clothing that meets the highest standards of comfort, durability, and style. Every product we create begins with a simple belief — when quality is uncompromised, customer satisfaction follows naturally.
                </p>

                <p>
                    We work exclusively with carefully tested, premium-grade fabrics, ensuring that each garment feels superior and performs exceptionally. From stitching to finishing, every detail is thoughtfully crafted to reflect our dedication to excellence.
                </p>

                <p>
                    Specializing in men’s and kids’ wear, we focus on providing apparel that is not only stylish but also soft, safe, and long-lasting. Our fabrics are selected to suit all kinds of wear, offering comfort and confidence for everyday use as well as special occasions.
                </p>

                <p>
                    To achieve this, we are launching a new brand for men’s wear under the name <strong>DUST</strong>.
                </p>

                <p>
                    Beyond fashion, Switch2Kart.com is continuously evolving. In the near future, we will be launching a wide range of electronics and accessories, expanding our commitment to premium quality across multiple product categories.
                </p>

                <p>
                    When you choose us, you choose premium fabric, trusted quality, and timeless comfort — because nothing matters more than wearing the best.
                </p>
            </div>

        </div>
    </section>

@endsection
