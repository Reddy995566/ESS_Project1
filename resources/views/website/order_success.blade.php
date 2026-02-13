@extends('website.layouts.master')

@section('title', 'Order Confirmed - ' . ($siteSettings['site_name'] ?? 'Fashion Store'))

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
                <!-- Order Items -->
                <div class="mb-8">
                    <h3 class="font-medium text-gray-900 mb-4 text-left">Order Items:</h3>
                    <div class="space-y-3">
                        @foreach($order->items as $item)
                        <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
                            @if($item->image)
                            <img src="{{ $item->image }}" 
                                 alt="{{ $item->product_name }}" 
                                 class="w-20 h-20 object-cover rounded-lg border border-gray-200">
                            @elseif($item->product && $item->product->image_url)
                            <img src="{{ $item->product->image_url }}" 
                                 alt="{{ $item->product_name }}" 
                                 class="w-20 h-20 object-cover rounded-lg border border-gray-200">
                            @else
                            <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            @endif
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900">{{ $item->product_name }}</h4>
                                @if($item->variant_name)
                                <p class="text-sm text-gray-600 mt-1">{{ $item->variant_name }}</p>
                                @endif
                                <p class="text-sm text-gray-500 mt-1">Qty: {{ $item->quantity }} × Rs. {{ number_format($item->price) }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-medium text-gray-900">Rs. {{ number_format($item->total) }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-left border-t border-gray-100 pt-8">
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
                            @if($order->discount > 0)
                            <div class="flex justify-between text-green-600">
                                <span>Discount @if($order->coupon_code)({{ $order->coupon_code }})@endif:</span>
                                <span>-Rs. {{ number_format($order->discount) }}</span>
                            </div>
                            @endif
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
