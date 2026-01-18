@extends('website.layouts.master')

@section('title', 'Order #' . $order->order_number)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#FAF5ED] to-[#E5E0D8] py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <a href="{{ route('user.orders') }}" class="flex items-center text-[#4b0f27] hover:underline font-medium">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back to Orders
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-6 border-b border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-gray-50">
                <div>
                    <h1 class="text-2xl font-bold text-[#3D0C1F] font-serif-elegant">Order {{ $order->order_number }}</h1>
                    <p class="text-sm text-gray-500 mt-1">Placed on {{ $order->created_at->format('F j, Y \a\t g:i A') }}</p>
                </div>
                @php
                    $statusColors = [
                        'pending' => 'bg-yellow-100 text-yellow-800',
                        'processing' => 'bg-blue-100 text-blue-800',
                        'shipped' => 'bg-indigo-100 text-indigo-800',
                        'delivered' => 'bg-green-100 text-green-800',
                        'cancelled' => 'bg-red-100 text-red-800',
                    ];
                    $statusClass = $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800';
                @endphp
                <div class="flex items-center gap-3">
                    <span class="px-4 py-1.5 {{ $statusClass }} font-medium rounded-full capitalize">{{ $order->status }}</span>
                    {{-- <button class="px-4 py-2 text-sm border border-gray-300 rounded hover:bg-gray-50 bg-white">Download Invoice</button> --}}
                </div>
            </div>

            <div class="p-6 md:p-8">
                <div class="flex flex-col lg:flex-row gap-8">
                    <!-- Order Items (Left) -->
                    <div class="flex-1 space-y-6">
                        <h2 class="text-lg font-semibold text-gray-900 border-b border-gray-100 pb-3">Items ({{ $order->items->count() }})</h2>
                        
                        <div class="space-y-6">
                            @foreach($order->items as $item)
                            <div class="flex gap-4">
                                <div class="w-20 h-24 flex-shrink-0 border border-gray-200 rounded-lg overflow-hidden bg-gray-50">
                                    <img src="{{ $item->product->image_url ?? 'https://via.placeholder.com/100' }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover" loading="lazy">
                                </div>
                                <div class="flex-1">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="font-medium text-gray-900 text-lg">{{ $item->product_name }}</h3>
                                            @if($item->variant_name)
                                                <p class="text-sm text-gray-500 mt-0.5">Color: {{ $item->variant_name }}</p>
                                            @endif
                                        </div>
                                        <p class="font-semibold text-[#3D0C1F]">Rs. {{ number_format($item->total) }}</p>
                                    </div>
                                    <div class="flex items-center justify-between mt-4">
                                        <div class="text-sm text-gray-500">
                                            Qty: {{ $item->quantity }} &times; Rs. {{ number_format($item->price) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Order Summary & Info (Right) -->
                    <div class="lg:w-1/3 space-y-8">
                        
                        <!-- Shipping Address -->
                        <div class="bg-gray-50 rounded-xl p-6">
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-4">Shipping Address</h3>
                            <div class="text-sm text-gray-900 leading-relaxed">
                                <p class="font-semibold text-base mb-1">{{ $order->first_name }}{{ $order->last_name ? ' ' . $order->last_name : '' }}</p>
                                <p>{{ $order->address }}</p>
                                @if($order->address_line_2) <p>{{ $order->address_line_2 }}</p> @endif
                                <p>{{ $order->city }}, {{ $order->state }} - {{ $order->zipcode }}</p>
                                <p>{{ $order->country }}</p>
                                <p class="mt-2 text-gray-600">Phone: {{ $order->phone }}</p>
                            </div>
                        </div>

                        <!-- Payment Info -->
                        <div class="bg-gray-50 rounded-xl p-6">
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-4">Payment Information</h3>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-600">Method</span>
                                <span class="text-sm font-medium text-gray-900 capitalize">{{ $order->payment_method == 'cod' ? 'Cash on Delivery' : 'Online Payment' }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Status</span>
                                <span class="text-xs px-2 py-1 {{ $order->payment_status == 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }} rounded uppercase font-medium">{{ $order->payment_status }}</span>
                            </div>
                        </div>

                        <!-- Cost Summary -->
                        <div class="bg-[#fdf2f2] rounded-xl p-6 border border-[#4b0f27] border-opacity-10">
                            <h3 class="text-sm font-medium text-[#4b0f27] uppercase tracking-wider mb-4">Order Summary</h3>
                            <div class="space-y-2 text-sm text-gray-600">
                                <div class="flex justify-between">
                                    <span>Subtotal</span>
                                    <span>Rs. {{ number_format($order->subtotal) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Shipping</span>
                                    <span>{{ $order->shipping > 0 ? 'Rs. ' . number_format($order->shipping) : 'Free' }}</span>
                                </div>
                                @if($order->discount > 0)
                                <div class="flex justify-between text-green-600">
                                    <span>Discount</span>
                                    <span>- Rs. {{ number_format($order->discount) }}</span>
                                </div>
                                @endif
                                @if($order->tax > 0)
                                <div class="flex justify-between">
                                    <span>Tax</span>
                                    <span>Rs. {{ number_format($order->tax) }}</span>
                                </div>
                                @endif
                            </div>
                            <div class="flex justify-between items-center pt-4 mt-4 border-t border-[#4b0f27] border-opacity-10">
                                <span class="font-bold text-[#3D0C1F] text-lg">Total</span>
                                <span class="font-bold text-[#3D0C1F] text-lg">Rs. {{ number_format($order->total) }}</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
