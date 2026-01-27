@extends('website.layouts.master')

@section('title', 'FAQs - ' . ($siteSettings['site_name'] ?? 'Fashion Store'))

@section('content')
<!-- FAQ Header -->
<div class="bg-wine py-12 md:py-16">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-3xl md:text-4xl font-serif-elegant text-white mb-3">FAQs</h1>
        <p class="text-white/80 text-sm md:text-base">Still need to reach us? Please <a href="{{ route('contact') }}" class="underline hover:text-white">contact us</a> anytime</p>
    </div>
</div>

<!-- FAQ Content -->
<div class="bg-cream py-12 md:py-16">
    <div class="container mx-auto px-4 max-w-3xl">
        <div class="space-y-4" x-data="{ openFaq: null }">
            <!-- FAQ Item 1 -->
            <div class="border border-wine/20 rounded-lg overflow-hidden">
                <button 
                    @click="openFaq = openFaq === 1 ? null : 1"
                    class="w-full flex items-center justify-between px-6 py-4 bg-white hover:bg-cream/50 transition-colors text-left"
                >
                    <span class="font-medium text-wine-dark">Where do you ship?</span>
                    <svg class="w-5 h-5 text-wine transition-transform duration-300" :class="{ 'rotate-180': openFaq === 1 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="openFaq === 1" x-collapse class="px-6 py-4 bg-white border-t border-wine/10">
                    <p class="text-wine-dark/80 text-sm leading-relaxed">
                        We ship all over India. We also ship internationally to select countries. Please check our shipping policy for more details on delivery times and charges.
                    </p>
                </div>
            </div>

            <!-- FAQ Item 2 -->
            <div class="border border-wine/20 rounded-lg overflow-hidden">
                <button 
                    @click="openFaq = openFaq === 2 ? null : 2"
                    class="w-full flex items-center justify-between px-6 py-4 bg-white hover:bg-cream/50 transition-colors text-left"
                >
                    <span class="font-medium text-wine-dark">How long will it take to receive my order?</span>
                    <svg class="w-5 h-5 text-wine transition-transform duration-300" :class="{ 'rotate-180': openFaq === 2 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="openFaq === 2" x-collapse class="px-6 py-4 bg-white border-t border-wine/10">
                    <p class="text-wine-dark/80 text-sm leading-relaxed">
                        Orders are typically processed within 1-2 business days. Delivery times vary based on your location - domestic orders usually arrive within 5-7 business days, while international orders may take 10-15 business days.
                    </p>
                </div>
            </div>

            <!-- FAQ Item 3 -->
            <div class="border border-wine/20 rounded-lg overflow-hidden">
                <button 
                    @click="openFaq = openFaq === 3 ? null : 3"
                    class="w-full flex items-center justify-between px-6 py-4 bg-white hover:bg-cream/50 transition-colors text-left"
                >
                    <span class="font-medium text-wine-dark">What payment methods do you support?</span>
                    <svg class="w-5 h-5 text-wine transition-transform duration-300" :class="{ 'rotate-180': openFaq === 3 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="openFaq === 3" x-collapse class="px-6 py-4 bg-white border-t border-wine/10">
                    <p class="text-wine-dark/80 text-sm leading-relaxed">
                        We accept all major credit and debit cards (Visa, MasterCard, American Express), UPI payments, Net Banking, and popular wallets like Paytm, PhonePe, and Google Pay. All transactions are secured with industry-standard encryption.
                    </p>
                </div>
            </div>

            <!-- FAQ Item 4 -->
            <div class="border border-wine/20 rounded-lg overflow-hidden">
                <button 
                    @click="openFaq = openFaq === 4 ? null : 4"
                    class="w-full flex items-center justify-between px-6 py-4 bg-white hover:bg-cream/50 transition-colors text-left"
                >
                    <span class="font-medium text-wine-dark">How can I track my order?</span>
                    <svg class="w-5 h-5 text-wine transition-transform duration-300" :class="{ 'rotate-180': openFaq === 4 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="openFaq === 4" x-collapse class="px-6 py-4 bg-white border-t border-wine/10">
                    <p class="text-wine-dark/80 text-sm leading-relaxed">
                        Once your order is shipped, you will receive an email with a tracking number and link. You can also track your order by logging into your account and visiting the "My Orders" section.
                    </p>
                </div>
            </div>

            <!-- FAQ Item 5 -->
            <div class="border border-wine/20 rounded-lg overflow-hidden">
                <button 
                    @click="openFaq = openFaq === 5 ? null : 5"
                    class="w-full flex items-center justify-between px-6 py-4 bg-white hover:bg-cream/50 transition-colors text-left"
                >
                    <span class="font-medium text-wine-dark">Can I order as a gift for someone else?</span>
                    <svg class="w-5 h-5 text-wine transition-transform duration-300" :class="{ 'rotate-180': openFaq === 5 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="openFaq === 5" x-collapse class="px-6 py-4 bg-white border-t border-wine/10">
                    <p class="text-wine-dark/80 text-sm leading-relaxed">
                        Absolutely! During checkout, you can enter a different shipping address for the recipient. You can also add a personalized gift message that will be included with the package.
                    </p>
                </div>
            </div>

            <!-- FAQ Item 6 -->
            <div class="border border-wine/20 rounded-lg overflow-hidden">
                <button 
                    @click="openFaq = openFaq === 6 ? null : 6"
                    class="w-full flex items-center justify-between px-6 py-4 bg-white hover:bg-cream/50 transition-colors text-left"
                >
                    <span class="font-medium text-wine-dark">How can I get a refund for my order?</span>
                    <svg class="w-5 h-5 text-wine transition-transform duration-300" :class="{ 'rotate-180': openFaq === 6 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="openFaq === 6" x-collapse class="px-6 py-4 bg-white border-t border-wine/10">
                    <p class="text-wine-dark/80 text-sm leading-relaxed">
                        If you're not satisfied with your purchase, you can request a return within 7 days of delivery. Once we receive the returned item in its original condition, we will process your refund within 5-7 business days. Please contact our customer support team to initiate a return.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Alpine.js collapse plugin for smooth animations
    document.addEventListener('alpine:init', () => {
        Alpine.directive('collapse', (el, { expression }, { effect, evaluateLater }) => {
            let show = evaluateLater(expression || 'true');
            
            effect(() => {
                show(value => {
                    if (value) {
                        el.style.height = 'auto';
                        el.style.overflow = 'visible';
                    } else {
                        el.style.height = '0px';
                        el.style.overflow = 'hidden';
                    }
                });
            });
        });
    });
</script>
@endpush
