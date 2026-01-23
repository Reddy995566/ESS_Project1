@extends('website.layouts.master')

@section('title', 'Shipping Policy - ' . ($siteSettings['site_name'] ?? 'The Trusted Store'))

@section('content')
<!-- Shipping Policy Header -->
<div class="bg-wine py-12 md:py-16">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-3xl md:text-4xl font-serif-elegant text-white mb-3">Shipping Policy</h1>
        <p class="text-white/80 text-sm md:text-base">Fast & reliable delivery across India</p>
    </div>
</div>

<!-- Shipping Policy Content -->
<div class="bg-cream py-12 md:py-16">
    <div class="container mx-auto px-4 max-w-4xl">
        <div class="bg-white rounded-lg shadow-sm p-6 md:p-10 space-y-8 text-wine-dark/80 text-sm md:text-base leading-relaxed">
            
            <!-- Free Shipping Banner -->
            <div class="bg-wine/5 border border-wine/20 rounded-lg p-6 text-center">
                <div class="flex items-center justify-center gap-3 mb-3">
                    <svg class="w-8 h-8 text-wine" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                    </svg>
                    <h2 class="text-xl md:text-2xl font-serif-elegant text-wine-dark">Free Shipping Across India</h2>
                </div>
                <p>We are happy to offer <strong>free shipping on all orders</strong> across India. Orders are typically delivered within <strong>7 working days</strong> from the date of dispatch (excluding sale and promotional periods).</p>
            </div>

            <!-- Order Processing & Delivery -->
            <div>
                <h2 class="text-xl md:text-2xl font-serif-elegant text-wine-dark mb-4">Order Processing & Delivery</h2>
                <ul class="space-y-3">
                    <li class="flex items-start gap-3">
                        <span class="w-2 h-2 bg-wine rounded-full mt-2 flex-shrink-0"></span>
                        <span>All orders are processed within <strong>24 to 48 hours</strong> and then handed over to our trusted courier partners.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="w-2 h-2 bg-wine rounded-full mt-2 flex-shrink-0"></span>
                        <span>Once dispatched, your order will usually reach you within <strong>7 working days</strong>.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="w-2 h-2 bg-wine rounded-full mt-2 flex-shrink-0"></span>
                        <span>Please note that during peak periods such as sales, festive seasons, or special promotions, delivery timelines may be slightly extended.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="w-2 h-2 bg-wine rounded-full mt-2 flex-shrink-0"></span>
                        <span>We are not liable for delivery delays caused by courier services, unforeseen logistical challenges, or recipient unavailability at the time of delivery.</span>
                    </li>
                </ul>
            </div>

            <!-- Unboxing Recommendation -->
            <div class="bg-gold/10 border border-gold/30 rounded-lg p-6">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-wine/10 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-wine" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-serif-elegant text-wine-dark mb-2">Unboxing Recommendation</h3>
                        <p>For your convenience and to help us resolve any potential concerns, we strongly recommend <strong>recording an unboxing video</strong> when you open your package. This helps us address issues related to damage, missing items, or incorrect products more efficiently.</p>
                    </div>
                </div>
            </div>

            <!-- Thank You Note -->
            <div class="text-center pt-4 border-t border-wine/10">
                <p class="text-wine-dark font-medium">Thank you for shopping with {{ $siteSettings['site_name'] ?? 'The Trusted Store' }}.</p>
                <p class="text-wine-dark/70 mt-1">We appreciate your trust and support!</p>
            </div>

        </div>
    </div>
</div>
@endsection
