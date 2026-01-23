@extends('website.layouts.master')

@section('title', 'Contact Information - ' . ($siteSettings['site_name'] ?? 'The Trusted Store'))

@section('content')
<!-- Contact Information Header -->
<div class="bg-wine py-12 md:py-16">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-3xl md:text-4xl font-serif-elegant text-white mb-3">Get in Touch with {{ $siteSettings['site_name'] ?? 'The Trusted Store' }}</h1>
        <p class="text-white/80 text-sm md:text-base">We'd love to hear from you!</p>
    </div>
</div>

<!-- Contact Information Content -->
<div class="bg-cream py-12 md:py-16">
    <div class="container mx-auto px-4 max-w-4xl">
        <div class="bg-white rounded-lg shadow-sm p-6 md:p-10">
            
            <!-- Intro Text -->
            <div class="text-center mb-10">
                <p class="text-wine-dark/80 text-sm md:text-base leading-relaxed">
                    If you have any questions, concerns, or feedback, feel free to reach out to our customer support team. We are here to assist you in any way possible.
                </p>
            </div>

            <!-- Contact Details Grid -->
            <div class="grid md:grid-cols-2 gap-6 mb-10">
                
                <!-- Trade Name -->
                <div class="flex items-start gap-4 p-4 bg-cream/50 rounded-lg">
                    <div class="w-12 h-12 bg-wine/10 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-wine" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-medium text-wine-dark mb-1">Trade Name</h3>
                        <p class="text-wine-dark/70">{{ $siteSettings['site_name'] ?? 'The Trusted Store' }}</p>
                    </div>
                </div>

                <!-- Email -->
                <div class="flex items-start gap-4 p-4 bg-cream/50 rounded-lg">
                    <div class="w-12 h-12 bg-wine/10 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-wine" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-medium text-wine-dark mb-1">Email</h3>
                        @if(!empty($siteSettings['site_email']))
                        <a href="mailto:{{ $siteSettings['site_email'] }}" class="text-wine hover:underline">{{ $siteSettings['site_email'] }}</a>
                        @else
                        <p class="text-wine-dark/70">Not available</p>
                        @endif
                    </div>
                </div>

                <!-- Phone -->
                <div class="flex items-start gap-4 p-4 bg-cream/50 rounded-lg">
                    <div class="w-12 h-12 bg-wine/10 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-wine" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-medium text-wine-dark mb-1">Phone</h3>
                        @if(!empty($siteSettings['site_phone']))
                        <a href="tel:{{ $siteSettings['site_phone'] }}" class="text-wine hover:underline">{{ $siteSettings['site_phone'] }}</a>
                        @else
                        <p class="text-wine-dark/70">Not available</p>
                        @endif
                    </div>
                </div>

                <!-- GST Number -->
                <div class="flex items-start gap-4 p-4 bg-cream/50 rounded-lg">
                    <div class="w-12 h-12 bg-wine/10 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-wine" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-medium text-wine-dark mb-1">GST Number</h3>
                        <p class="text-wine-dark/70">27XXXXX1234X1ZX</p>
                    </div>
                </div>

            </div>

            <!-- Address -->
            <div class="flex items-start gap-4 p-4 bg-cream/50 rounded-lg mb-10">
                <div class="w-12 h-12 bg-wine/10 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-wine" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-medium text-wine-dark mb-1">Address</h3>
                    @if(!empty($siteSettings['site_address']))
                    <p class="text-wine-dark/70">{!! nl2br(e($siteSettings['site_address'])) !!}</p>
                    @else
                    <p class="text-wine-dark/70">Not available</p>
                    @endif
                </div>
            </div>

            <!-- Business Hours -->
            <div class="border border-wine/20 rounded-lg p-6 mb-10">
                <div class="flex items-center gap-3 mb-4">
                    <svg class="w-6 h-6 text-wine" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-lg font-serif-elegant text-wine-dark">Business Hours</h3>
                </div>
                <div class="space-y-2 text-wine-dark/80">
                    <div class="flex justify-between items-center py-2 border-b border-wine/10">
                        <span>Monday - Friday</span>
                        <span class="font-medium text-wine-dark">9:00 AM - 6:00 PM</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span>Saturday - Sunday</span>
                        <span class="font-medium text-red-600">Closed</span>
                    </div>
                </div>
            </div>

            <!-- Contact Form CTA -->
            <div class="text-center bg-wine/5 rounded-lg p-6">
                <p class="text-wine-dark/80 mb-4">For quick assistance, you can also fill out the contact form below, and we'll get back to you as soon as possible.</p>
                <a href="{{ route('contact') }}" class="inline-block px-8 py-3 bg-wine text-white font-medium rounded-sm hover:bg-wine-dark transition-colors">
                    Go to Contact Form
                </a>
            </div>

        </div>
    </div>
</div>
@endsection
