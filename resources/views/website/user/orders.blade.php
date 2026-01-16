@extends('website.layouts.master')

@section('title', 'My Orders')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#FAF5ED] to-[#E5E0D8] py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            
            <!-- Sidebar -->
            @include('website.user.partials.sidebar')

            <!-- Main Content -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h2 class="font-serif-elegant text-3xl font-bold text-[#3D0C1F] mb-6">My Orders</h2>
                    
                    <!-- Empty State (Only if no orders) -->
                    @if($orders->isEmpty())
                    <div class="text-center py-16">
                        <svg class="w-24 h-24 mx-auto text-gray-300 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        <h3 class="text-2xl font-semibold text-gray-700 mb-2">No Orders Yet</h3>
                        <p class="text-gray-500 mb-6">You haven't placed any orders. Start shopping now!</p>
                        <a href="{{ route('shop') }}" class="inline-block px-8 py-3 bg-[#3D0C1F] text-white font-medium rounded-lg hover:bg-[#2a0815] transition-all shadow-lg">
                            Browse Products
                        </a>
                    </div>
                    @else
                    
                    <!-- Orders List -->
                    <div class="space-y-6" id="ordersList">
                        @foreach($orders as $order)
                        <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                            <div class="flex flex-col sm:flex-row justify-between items-start mb-4 gap-4">
                                <div>
                                    <h3 class="font-semibold text-lg text-gray-900">{{ $order->order_number }}</h3>
                                    <p class="text-sm text-gray-500">Placed on {{ $order->created_at->format('F j, Y') }}</p>
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
                                <span class="px-4 py-1 {{ $statusClass }} text-sm font-medium rounded-full capitalize">{{ $order->status }}</span>
                            </div>
                            
                            <!-- Order Items (Show first 2 items preview) -->
                            <div class="space-y-4 mb-4">
                                @foreach($order->items->take(2) as $item)
                                <div class="flex gap-4">
                                    <img src="{{ $item->product->image_url ?? 'https://via.placeholder.com/100' }}" alt="{{ $item->product_name }}" class="w-20 h-20 object-cover rounded-lg bg-gray-50" loading="lazy">
                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-900 line-clamp-1">{{ $item->product_name }}</h4>
                                        <p class="text-sm text-gray-500">
                                            @if($item->variant_name) Color: {{ $item->variant_name }} | @endif
                                            Qty: {{ $item->quantity }}
                                        </p>
                                        <p class="text-lg font-semibold text-[#3D0C1F] mt-1">Rs. {{ number_format($item->price) }}</p>
                                    </div>
                                </div>
                                @endforeach
                                
                                @if($order->items->count() > 2)
                                    <p class="text-sm text-gray-500">+ {{ $order->items->count() - 2 }} more items</p>
                                @endif
                            </div>
                            
                            <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-200">
                                <a href="{{ route('user.orders.show', $order->id) }}" class="text-center px-4 py-2 border border-[#3D0C1F] text-[#3D0C1F] rounded-lg hover:bg-[#3D0C1F] hover:text-white transition-all">
                                    View Details
                                </a>
                                {{-- <button class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all">
                                    Track Order
                                </button> --}}
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
