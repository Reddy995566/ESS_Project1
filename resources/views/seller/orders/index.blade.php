@extends('seller.layouts.app')

@section('title', 'Orders Management')

@section('content')
    <!-- Main Container -->
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-indigo-50 to-blue-50 p-6">
        
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="flex items-center justify-center w-14 h-14 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-2xl shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-black text-gray-900 tracking-tight">Orders Management</h1>
                        <p class="mt-1 text-sm text-gray-600">Manage and track your customer orders</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <button id="exportBtn" class="inline-flex items-center px-5 py-2.5 bg-white text-gray-700 text-sm font-semibold rounded-xl border-2 border-gray-200 hover:border-green-400 hover:bg-green-50 hover:text-green-700 transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Export
                    </button>
                    <button id="refreshBtn" class="inline-flex items-center justify-center w-11 h-11 bg-white text-gray-700 rounded-xl border-2 border-gray-200 hover:border-indigo-400 hover:bg-indigo-50 hover:text-indigo-700 transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        @if (session('success'))
            <div class="mb-6 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 p-4 rounded-xl shadow-md animate-slideIn">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-semibold text-green-800">{{ session('success') }}</p>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-green-600 hover:text-green-800">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            </div>
        @endif

        <!-- Statistics Dashboard -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Total Orders -->
            <div class="bg-indigo-600 rounded-2xl shadow-xl p-6 transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center justify-center w-12 h-12 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </div>
                    <span class="px-3 py-1 bg-white bg-opacity-20 backdrop-blur-sm rounded-full text-xs font-bold text-white">Total</span>
                </div>
                <div class="text-white">
                    <p class="text-4xl font-black mb-1">{{ $totalOrders ?? 0 }}</p>
                    <p class="text-indigo-100 text-sm font-medium">Total Orders</p>
                </div>
            </div>

            <!-- Pending Orders -->
            <div class="bg-orange-600 rounded-2xl shadow-xl p-6 transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center justify-center w-12 h-12 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="px-3 py-1 bg-white bg-opacity-20 backdrop-blur-sm rounded-full text-xs font-bold text-white">Pending</span>
                </div>
                <div class="text-white">
                    <p class="text-4xl font-black mb-1">{{ $pendingOrders ?? 0 }}</p>
                    <p class="text-orange-100 text-sm font-medium">Pending Orders</p>
                </div>
            </div>

            <!-- Completed Orders -->
            <div class="bg-emerald-600 rounded-2xl shadow-xl p-6 transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center justify-center w-12 h-12 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="px-3 py-1 bg-white bg-opacity-20 backdrop-blur-sm rounded-full text-xs font-bold text-white">Done</span>
                </div>
                <div class="text-white">
                    <p class="text-4xl font-black mb-1">{{ $completedOrders ?? 0 }}</p>
                    <p class="text-emerald-100 text-sm font-medium">Completed Orders</p>
                </div>
            </div>

            <!-- Total Earnings -->
            <div class="bg-purple-600 rounded-2xl shadow-xl p-6 transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center justify-center w-12 h-12 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="px-3 py-1 bg-white bg-opacity-20 backdrop-blur-sm rounded-full text-xs font-bold text-white">Earnings</span>
                </div>
                <div class="text-white">
                    <p class="text-4xl font-black mb-1">₹{{ number_format($totalEarnings ?? 0, 0) }}</p>
                    <p class="text-purple-100 text-sm font-medium">Total Earnings</p>
                </div>
            </div>
        </div>

        <!-- Main Data Table Card -->
        <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 overflow-hidden">
            
            <!-- Table Toolbar -->
            <div class="bg-gradient-to-r from-gray-50 to-slate-50 px-6 py-5 border-b border-gray-200">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 flex items-center">
                            <span class="flex items-center justify-center w-8 h-8 bg-indigo-100 text-indigo-600 rounded-lg mr-3">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                                </svg>
                            </span>
                            Your Orders Data Table
                        </h2>
                        <p class="text-sm text-gray-600 mt-1 ml-11">Manage all your customer orders and track earnings</p>
                    </div>
                </div>
            </div>

            <!-- Advanced Filters Section -->
            <div class="bg-gray-50 px-6 py-5 border-b border-gray-200">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Search -->
                    <div class="md:col-span-1">
                        <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">Search Orders</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}" class="block w-full pl-10 pr-3 py-3 border-2 border-gray-300 rounded-xl bg-white text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" placeholder="Search by order number, customer...">
                        </div>
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">Order Status</label>
                        <select name="status" class="block w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>🔄 Processing</option>
                            <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>🚚 Shipped</option>
                            <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>✅ Delivered</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>❌ Cancelled</option>
                        </select>
                    </div>

                    <!-- Action Buttons -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">Actions</label>
                        <div class="flex items-center space-x-2">
                            <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-3 rounded-xl font-semibold transition-all">
                                Filter
                            </button>
                            <a href="{{ route('seller.orders.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-3 rounded-xl font-semibold transition-all">
                                Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Data Table -->
            <div class="overflow-x-auto">
                <table id="ordersTable" class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-slate-100 to-gray-100">
                        <tr>
                            <th class="px-4 py-4 text-left min-w-[150px]">
                                <span class="text-xs font-black text-gray-700 uppercase">Order Number</span>
                            </th>
                            <th class="px-4 py-4 text-left min-w-[180px]">
                                <span class="text-xs font-black text-gray-700 uppercase">Customer</span>
                            </th>
                            <th class="px-4 py-4 text-center w-32">
                                <span class="text-xs font-black text-gray-700 uppercase">Items</span>
                            </th>
                            <th class="px-4 py-4 text-center w-32">
                                <span class="text-xs font-black text-gray-700 uppercase">Your Earning</span>
                            </th>
                            <th class="px-4 py-4 text-center w-32">
                                <span class="text-xs font-black text-gray-700 uppercase">Payment</span>
                            </th>
                            <th class="px-4 py-4 text-center w-32">
                                <span class="text-xs font-black text-gray-700 uppercase">Status</span>
                            </th>
                            <th class="px-4 py-4 text-center w-40">
                                <span class="text-xs font-black text-gray-700 uppercase">Date</span>
                            </th>
                            <th class="px-4 py-4 text-center w-28">
                                <span class="text-xs font-black text-gray-700 uppercase">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($orders as $order)
                        <tr class="group hover:bg-gradient-to-r hover:from-indigo-50 hover:to-blue-50 transition-all duration-200"
                            data-status="{{ $order->status }}"
                            data-payment="{{ $order->payment_method }}"
                            data-id="{{ $order->id }}">

                            <!-- Order Number -->
                            <td class="px-4 py-4">
                                <a href="{{ route('seller.orders.show', $order->id) }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-800 hover:underline">
                                    #{{ $order->order_number }}
                                </a>
                            </td>

                            <!-- Customer -->
                            <td class="px-4 py-4">
                                <div>
                                    <p class="text-sm font-bold text-gray-900">{{ $order->user->name ?? ($order->first_name . ($order->last_name ? ' ' . $order->last_name : '')) }}</p>
                                    <p class="text-xs text-gray-500">{{ $order->user->email ?? $order->email }}</p>
                                </div>
                            </td>

                            <!-- Items -->
                            <td class="px-4 py-4 text-center">
                                <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-bold bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-700 border border-blue-300">
                                    {{ $order->items->count() }} item(s)
                                </span>
                            </td>

                            <!-- Your Earning -->
                            <td class="px-4 py-4 text-center">
                                <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-bold bg-gradient-to-r from-green-100 to-emerald-100 text-green-700 border border-green-300">
                                    ₹{{ number_format($order->items->sum('seller_amount'), 2) }}
                                </span>
                            </td>

                            <!-- Payment Method -->
                            <td class="px-4 py-4 text-center">
                                @if($order->payment_method === 'cod')
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold bg-gradient-to-r from-yellow-100 to-amber-100 text-yellow-700 border border-yellow-300">
                                        💵 COD
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-700 border border-blue-300">
                                        💳 Online
                                    </span>
                                @endif
                            </td>

                            <!-- Status -->
                            <td class="px-4 py-4 text-center">
                                @php
                                    $statusColors = [
                                        'pending' => 'from-orange-100 to-amber-100 text-orange-700 border-orange-300',
                                        'processing' => 'from-blue-100 to-indigo-100 text-blue-700 border-blue-300',
                                        'shipped' => 'from-purple-100 to-pink-100 text-purple-700 border-purple-300',
                                        'delivered' => 'from-green-100 to-emerald-100 text-green-700 border-green-300',
                                        'cancelled' => 'from-red-100 to-pink-100 text-red-700 border-red-300',
                                    ];
                                    $statusIcons = [
                                        'pending' => '⏳',
                                        'processing' => '🔄',
                                        'shipped' => '🚚',
                                        'delivered' => '✅',
                                        'cancelled' => '❌',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold bg-gradient-to-r {{ $statusColors[$order->status] ?? 'from-gray-100 to-gray-200 text-gray-700 border-gray-300' }} border">
                                    {{ $statusIcons[$order->status] ?? '' }} {{ ucfirst($order->status) }}
                                </span>
                            </td>

                            <!-- Date -->
                            <td class="px-4 py-4 text-center">
                                <p class="text-sm font-medium text-gray-900">{{ $order->created_at->format('d M, Y') }}</p>
                                <p class="text-xs text-gray-500">{{ $order->created_at->format('h:i A') }}</p>
                            </td>

                            <!-- Actions -->
                            <td class="px-4 py-4">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ route('seller.orders.show', $order->id) }}" class="inline-flex items-center justify-center w-9 h-9 text-indigo-600 bg-indigo-50 border-2 border-indigo-200 rounded-lg hover:bg-indigo-100 hover:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all" title="View Order">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr id="emptyState">
                            <td colspan="8" class="px-6 py-20">
                                <div class="text-center">
                                    <div class="mx-auto flex items-center justify-center w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full mb-6">
                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">No Orders Found</h3>
                                    <p class="text-gray-500 mb-6 max-w-md mx-auto">You haven't received any customer orders yet. Once customers purchase your products, they'll appear here.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Table Footer -->
            <div class="bg-gradient-to-r from-gray-50 to-slate-50 px-6 py-4 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="dataTables_info text-sm font-medium text-gray-700">
                        Showing <span class="font-bold text-indigo-600">{{ $orders->firstItem() ?? 0 }}</span> to <span class="font-bold text-indigo-600">{{ $orders->lastItem() ?? 0 }}</span> of <span class="font-bold text-indigo-600">{{ $orders->total() }}</span> entries
                    </div>
                    <div class="dataTables_paginate">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Export functionality
    document.getElementById('exportBtn')?.addEventListener('click', function() {
        // Add export logic here
        alert('Export functionality will be implemented');
    });

    // Refresh functionality
    document.getElementById('refreshBtn')?.addEventListener('click', function() {
        window.location.reload();
    });
});
</script>
@endpush