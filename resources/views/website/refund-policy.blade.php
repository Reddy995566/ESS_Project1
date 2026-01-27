@extends('website.layouts.master')

@section('title', 'Return & Refund Policy - ' . ($siteSettings['site_name'] ?? 'Fashion Store'))

@section('content')
<!-- Refund Policy Header -->
<div class="bg-wine py-12 md:py-16">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-3xl md:text-4xl font-serif-elegant text-white mb-3">Return & Refund Policy</h1>
        <p class="text-white/80 text-sm md:text-base">At {{ $siteSettings['site_name'] ?? 'Fashion Store' }}, we value your trust and strive to ensure you are happy with every purchase.</p>
    </div>
</div>

<!-- Refund Policy Content -->
<div class="bg-cream py-12 md:py-16">
    <div class="container mx-auto px-4 max-w-4xl">
        <div class="bg-white rounded-lg shadow-sm p-6 md:p-10 space-y-8 text-wine-dark/80 text-sm md:text-base leading-relaxed">
            
            <!-- 1. Exchange for Damaged/Defective Products -->
            <div>
                <h2 class="text-xl md:text-2xl font-serif-elegant text-wine-dark mb-4">1. Exchange for Damaged/Defective Products</h2>
                <ul class="space-y-3">
                    <li class="flex items-start gap-3">
                        <span class="w-2 h-2 bg-wine rounded-full mt-2 flex-shrink-0"></span>
                        <span>If you receive a product that is damaged or defective from our side, we offer a <strong>free exchange</strong>.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="w-2 h-2 bg-wine rounded-full mt-2 flex-shrink-0"></span>
                        <span>You must notify us within <strong>3 days of delivery</strong> with clear photos/videos of the issue.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="w-2 h-2 bg-wine rounded-full mt-2 flex-shrink-0"></span>
                        <span>Once approved, a replacement will be arranged at no extra cost.</span>
                    </li>
                </ul>
            </div>

            <!-- 2. Returns for Dissatisfaction -->
            <div>
                <h2 class="text-xl md:text-2xl font-serif-elegant text-wine-dark mb-4">2. Returns for Dissatisfaction</h2>
                <ul class="space-y-3">
                    <li class="flex items-start gap-3">
                        <span class="w-2 h-2 bg-wine rounded-full mt-2 flex-shrink-0"></span>
                        <span>If you are not satisfied with your purchase, you may return it within <strong>3 days of delivery</strong>.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="w-2 h-2 bg-wine rounded-full mt-2 flex-shrink-0"></span>
                        <span>After receiving and verifying the returned item, we will issue you <strong>store credit</strong> equal to the purchase value.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="w-2 h-2 bg-wine/70 rounded-full mt-2 flex-shrink-0"></span>
                        <span class="text-wine-dark font-medium">Please note: We do not provide monetary refunds.</span>
                    </li>
                </ul>
            </div>

            <!-- 3. Conditions for Return/Exchange -->
            <div>
                <h2 class="text-xl md:text-2xl font-serif-elegant text-wine-dark mb-4">3. Conditions for Return/Exchange</h2>
                <ul class="space-y-3">
                    <li class="flex items-start gap-3">
                        <span class="w-2 h-2 bg-wine rounded-full mt-2 flex-shrink-0"></span>
                        <span>Products must be <strong>unused</strong>, in <strong>original condition</strong>, and with <strong>packaging intact</strong>.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="w-2 h-2 bg-wine rounded-full mt-2 flex-shrink-0"></span>
                        <span>Items returned beyond <strong>3 days of delivery</strong> will not be accepted.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="w-2 h-2 bg-wine rounded-full mt-2 flex-shrink-0"></span>
                        <span>Certain items (e.g., custom orders, sale items) may not be eligible for return/exchange.</span>
                    </li>
                </ul>
            </div>

            <!-- 4. Process -->
            <div>
                <h2 class="text-xl md:text-2xl font-serif-elegant text-wine-dark mb-4">4. Process</h2>
                <div class="space-y-4">
                    <div class="flex items-start gap-4">
                        <div class="w-8 h-8 bg-wine text-white rounded-full flex items-center justify-center flex-shrink-0 font-medium">1</div>
                        <p class="pt-1">Raise a Return request within <strong>3 days of delivery</strong> with your order details.</p>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-8 h-8 bg-wine text-white rounded-full flex items-center justify-center flex-shrink-0 font-medium">2</div>
                        <p class="pt-1">Our team will confirm eligibility and guide you through the return/exchange process.</p>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-8 h-8 bg-wine text-white rounded-full flex items-center justify-center flex-shrink-0 font-medium">3</div>
                        <p class="pt-1">Once the product is received and inspected, we will process your exchange or issue store credit.</p>
                    </div>
                </div>
            </div>

            <!-- Contact Us -->
            <div class="border-t border-wine/10 pt-8">
                <h2 class="text-xl md:text-2xl font-serif-elegant text-wine-dark mb-4">Contact Us</h2>
                <p>For any return or refund queries, please contact us:</p>
                <div class="mt-4 p-4 bg-cream rounded-lg space-y-2">
                    @if(!empty($siteSettings['site_email']))
                    <p><strong>Email:</strong> <a href="mailto:{{ $siteSettings['site_email'] }}" class="text-wine hover:underline">{{ $siteSettings['site_email'] }}</a></p>
                    @endif
                    @if(!empty($siteSettings['site_phone']))
                    <p><strong>WhatsApp:</strong> <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $siteSettings['site_phone']) }}" class="text-wine hover:underline">{{ $siteSettings['site_phone'] }}</a></p>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
