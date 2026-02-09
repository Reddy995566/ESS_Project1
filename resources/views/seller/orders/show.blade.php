@extends('seller.layouts.app')

@section('title', 'Order Details')

@section('content')
    <div class="mb-6">
        <a href="{{ route('seller.orders.index') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">
            ← Back to Orders
        </a>
    </div>

    <!-- Order Header -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Order #{{ $order->order_number }}</h1>
                <p class="text-gray-600 mt-1">Placed on {{ $order->created_at->format('M d, Y h:i A') }}</p>
            </div>
            <div class="flex items-center space-x-3">
                <span class="px-4 py-2 inline-flex text-sm font-semibold rounded-full 
                    {{ $order->status === 'delivered' ? 'bg-green-100 text-green-800' : 
                       ($order->status === 'shipped' ? 'bg-blue-100 text-blue-800' : 
                       ($order->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800')) }}">
                    {{ ucfirst($order->status) }}
                </span>
                
                <a href="{{ route('seller.orders.invoice', $order->id) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white text-sm font-semibold rounded-lg hover:from-indigo-700 hover:to-indigo-800 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Print Invoice
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
            <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-2">Customer</h3>
                <p class="text-gray-900">{{ $order->user ? $order->user->name : ($order->first_name . ' ' . $order->last_name) }}</p>
                <p class="text-gray-600 text-sm">{{ $order->email }}</p>
                <p class="text-gray-600 text-sm">{{ $order->phone }}</p>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-2">Shipping Address</h3>
                <p class="text-gray-900">{{ $order->address }}</p>
                <p class="text-gray-600 text-sm">{{ $order->city }}, {{ $order->state }} {{ $order->pincode }}</p>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-2">Payment</h3>
                <p class="text-gray-900">{{ ucfirst($order->payment_method) }}</p>
                <p class="text-gray-600 text-sm">{{ ucfirst($order->payment_status) }}</p>
            </div>
        </div>
    </div>

    <!-- Order Items -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-lg font-bold text-gray-900">Your Items in this Order</h2>
        </div>

        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Variant</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Commission</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Your Earning</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($order->items as $item)
                <tr>
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            @if($item->product && $item->product->main_image_url)
                                <img src="{{ $item->product->main_image_url }}" class="w-12 h-12 rounded object-cover mr-3">
                            @endif
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $item->product_name }}</div>
                                <div class="text-xs text-gray-500">SKU: {{ $item->product->sku ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        {{ $item->variant_name ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        ₹{{ number_format($item->price, 2) }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        {{ $item->quantity }}
                    </td>
                    <td class="px-6 py-4 text-sm text-red-600">
                        -₹{{ number_format($item->commission_amount ?? 0, 2) }}
                        <span class="text-xs text-gray-500">({{ $item->commission_rate ?? 0 }}%)</span>
                    </td>
                    <td class="px-6 py-4 text-sm font-semibold text-green-600">
                        ₹{{ number_format($item->seller_amount ?? 0, 2) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-gray-50">
                <tr>
                    <td colspan="5" class="px-6 py-4 text-right text-sm font-semibold text-gray-900">
                        Total Earning:
                    </td>
                    <td class="px-6 py-4 text-sm font-bold text-green-600">
                        ₹{{ number_format($order->items->sum('seller_amount'), 2) }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
@endsection
