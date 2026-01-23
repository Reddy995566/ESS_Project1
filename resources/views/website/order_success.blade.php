@extends('website.layouts.master')

@section('title', 'Order Confirmed - ' . ($siteSettings['site_name'] ?? 'The Trusted Store'))

@section('content')
<div class="min-h-screen bg-[#fff7ec] py-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="p-8 text-center">
            
            <!-- Success Icon -->
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>

            <h1 class="font-serif-elegant text-3xl font-bold text-[#3D0C1F] mb-4">Order Placed Successfully!</h1>
            <p class="text-gray-600 mb-8">Thank you for your purchase. Your order has been received and is being processed.</p>
            
            <div class="inline-block bg-[#fdf2f2] border border-[#4b0f27] border-opacity-20 px-6 py-3 rounded-lg mb-8">
                <span class="text-gray-600">Order Number:</span>
                <span class="font-bold text-[#3D0C1F] ml-2">{{ $order->order_number }}</span>
            </div>

            <div class="border-t border-b border-gray-100 py-8 mb-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-left">
                    <div>
                        <h3 class="font-medium text-gray-900 mb-2">Shipping To:</h3>
                        <p class="text-gray-600 text-sm leading-relaxed">
                            {{ $order->first_name }}{{ $order->last_name ? ' ' . $order->last_name : '' }}<br>
                            {{ $order->address }}<br>
                            @if($order->address_line_2) {{ $order->address_line_2 }}<br> @endif
                            {{ $order->city }}, {{ $order->state }} - {{ $order->zipcode }}<br>
                            {{ $order->country }}<br>
                            Phone: {{ $order->phone }}
                        </p>
                    </div>
                    <div>
                        <h3 class="font-medium text-gray-900 mb-2">Order Summary:</h3>
                        <div class="space-y-1 text-sm text-gray-600">
                            <div class="flex justify-between">
                                <span>Subtotal:</span>
                                <span>Rs. {{ number_format($order->subtotal) }}</span>
                            </div>
                            <div class="flex justify-between font-medium text-[#3D0C1F] pt-2 mt-2 border-t border-gray-100">
                                <span>Total:</span>
                                <span>Rs. {{ number_format($order->total) }}</span>
                            </div>
                            <div class="pt-2 text-xs text-gray-400">
                                Payment Method: {{ ucfirst($order->payment_method) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('user.orders') }}" class="px-8 py-3 bg-[#4b0f27] text-white rounded-lg hover:bg-[#3a0a1e] transition-colors shadow-lg font-medium">
                    View My Orders
                </a>
                <a href="{{ route('home') }}" class="px-8 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                    Continue Shopping
                </a>
            </div>

        </div>
    </div>
</div>
@endsection
