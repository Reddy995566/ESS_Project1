@extends('admin.layouts.app')

@section('title', 'Rejected Products')

@section('content')
<!-- Main Container -->
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-red-50 to-pink-50 p-6">
    
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="flex items-center space-x-4">
                <div class="flex items-center justify-center w-14 h-14 bg-gradient-to-br from-red-500 to-pink-600 rounded-2xl shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-black text-gray-900 tracking-tight">Rejected Products</h1>
                    <p class="mt-1 text-sm text-gray-600">Products that have been rejected by admin</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <button id="refreshBtn" class="inline-flex items-center justify-center w-11 h-11 bg-white text-gray-700 rounded-xl border-2 border-gray-200 hover:border-red-400 hover:bg-red-50 hover:text-red-700 transition-all duration-200 shadow-sm hover:shadow-md">
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
    @if ($errors->any())
        <div class="mb-6 bg-gradient-to-r from-red-50 to-pink-50 border-l-4 border-red-500 p-4 rounded-xl shadow-md animate-slideIn">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <h3 class="text-sm font-semibold text-red-800 mb-1">Error! Please fix the following:</h3>
                    <ul class="list-disc list-inside space-y-1 text-sm text-red-700">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Statistics Dashboard -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Rejected -->
        <div class="bg-gradient-to-br from-red-500 to-pink-600 rounded-2xl shadow-xl p-6 transform hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center justify-center w-12 h-12 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="px-3 py-1 bg-white bg-opacity-20 backdrop-blur-sm rounded-full text-xs font-bold text-white">Rejected</span>
            </div>
            <div class="text-white">
                <p class="text-4xl font-black mb-1">{{ $products->total() }}</p>
                <p class="text-red-100 text-sm font-medium">Total Rejected</p>
            </div>
        </div>

        <!-- Today's Rejections -->
        <div class="bg-gradient-to-br from-gray-500 to-slate-600 rounded-2xl shadow-xl p-6 transform hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center justify-center w-12 h-12 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <span class="px-3 py-1 bg-white bg-opacity-20 backdrop-blur-sm rounded-full text-xs font-bold text-white">Today</span>
            </div>
            <div class="text-white">
                <p class="text-4xl font-black mb-1">{{ $products->where('updated_at', '>=', today())->count() }}</p>
                <p class="text-gray-100 text-sm font-medium">Today's Rejections</p>
            </div>
        </div>
        <!-- This Week -->
        <div class="bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl shadow-xl p-6 transform hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center justify-center w-12 h-12 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <span class="px-3 py-1 bg-white bg-opacity-20 backdrop-blur-sm rounded-full text-xs font-bold text-white">Week</span>
            </div>
            <div class="text-white">
                <p class="text-4xl font-black mb-1">{{ $products->where('updated_at', '>=', now()->startOfWeek())->count() }}</p>
                <p class="text-orange-100 text-sm font-medium">This Week</p>
            </div>
        </div>

        <!-- Need Review -->
        <div class="bg-gradient-to-br from-purple-500 to-indigo-600 rounded-2xl shadow-xl p-6 transform hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center justify-center w-12 h-12 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <span class="px-3 py-1 bg-white bg-opacity-20 backdrop-blur-sm rounded-full text-xs font-bold text-white">Review</span>
            </div>
            <div class="text-white">
                <p class="text-4xl font-black mb-1">{{ $products->whereNotNull('rejection_reason')->count() }}</p>
                <p class="text-purple-100 text-sm font-medium">With Reason</p>
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
                        <span class="flex items-center justify-center w-8 h-8 bg-red-100 text-red-600 rounded-lg mr-3">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
                            </svg>
                        </span>
                        Rejected Products Review
                    </h2>
                    <p class="text-sm text-gray-600 mt-1 ml-11">Products that have been rejected with reasons</p>
                </div>
            </div>
        </div>
        <!-- Data Table -->
        <div class="overflow-x-auto">
            <table id="rejectedProductsTable" class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-slate-100 to-gray-100">
                    <tr>
                        <th class="px-4 py-4 text-center w-16">
                            <span class="text-xs font-black text-gray-700 uppercase">ID</span>
                        </th>
                        <th class="px-4 py-4 text-left min-w-[250px]">
                            <span class="text-xs font-black text-gray-700 uppercase">Product</span>
                        </th>
                        <th class="px-4 py-4 text-left w-48">
                            <span class="text-xs font-black text-gray-700 uppercase">Seller</span>
                        </th>
                        <th class="px-4 py-4 text-center w-32">
                            <span class="text-xs font-black text-gray-700 uppercase">Category</span>
                        </th>
                        <th class="px-4 py-4 text-center w-28">
                            <span class="text-xs font-black text-gray-700 uppercase">Price</span>
                        </th>
                        <th class="px-4 py-4 text-center w-24">
                            <span class="text-xs font-black text-gray-700 uppercase">Stock</span>
                        </th>
                        <th class="px-4 py-4 text-center w-32">
                            <span class="text-xs font-black text-gray-700 uppercase">Rejected</span>
                        </th>
                        <th class="px-4 py-4 text-center w-40">
                            <span class="text-xs font-black text-gray-700 uppercase">Reason</span>
                        </th>
                        <th class="px-4 py-4 text-center w-32">
                            <span class="text-xs font-black text-gray-700 uppercase">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($products as $product)
                    <tr class="group hover:bg-gradient-to-r hover:from-red-50 hover:to-pink-50 transition-all duration-200" 
                        data-id="{{ $product->id }}">
                        
                        <!-- ID -->
                        <td class="px-4 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 border border-gray-300">#{{ $product->id }}</span>
                        </td>

                        <!-- Product Info -->
                        <td class="px-4 py-4">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0 w-12 h-12">
                                    @php
                                        $firstImage = null;
                                        // Try to get from main images first
                                        if(is_array($product->images) && count($product->images) > 0) {
                                            $firstImage = is_string($product->images[0]) ? $product->images[0] : (is_array($product->images[0]) && isset($product->images[0]['url']) ? $product->images[0]['url'] : null);
                                        } elseif(is_string($product->images) && !empty($product->images)) {
                                            $imagesArray = json_decode($product->images, true);
                                            if(is_array($imagesArray) && count($imagesArray) > 0) {
                                                $firstImage = is_string($imagesArray[0]) ? $imagesArray[0] : (is_array($imagesArray[0]) && isset($imagesArray[0]['url']) ? $imagesArray[0]['url'] : null);
                                            }
                                        }
                                        
                                        // Fallback: Try to get from product variants if no main image
                                        if(!$firstImage) {
                                            $firstVariant = $product->variants()->first();
                                            if($firstVariant && !empty($firstVariant->images) && is_array($firstVariant->images) && count($firstVariant->images) > 0) {
                                                $firstImage = $firstVariant->images[0];
                                            }
                                        }
                                    @endphp
                                    @if($firstImage && is_string($firstImage))
                                        <img src="{{ $firstImage }}" alt="{{ $product->name }}" class="w-12 h-12 rounded-xl object-cover border-2 border-gray-200 shadow-sm">
                                    @else
                                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center border-2 border-gray-200">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-sm font-bold text-gray-900 mb-1">{{ $product->name }}</h3>
                                    <p class="text-xs text-gray-500 leading-relaxed">SKU: {{ $product->sku ?? 'N/A' }}</p>
                                    <div class="flex items-center space-x-2 mt-2">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-300">
                                            ❌ Rejected
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- Seller Info -->
                        <td class="px-4 py-4">
                            <div class="flex flex-col">
                                @if($product->seller)
                                    <h4 class="text-sm font-bold text-gray-900 mb-1">{{ $product->seller->business_name }}</h4>
                                    <p class="text-xs text-gray-500">{{ $product->seller->business_email ?? ($product->seller->user->email ?? 'N/A') }}</p>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-300 mt-1 w-fit">
                                        {{ ucfirst($product->seller->status) }}
                                    </span>
                                @else
                                    <span class="text-sm text-gray-500">No Seller</span>
                                @endif
                            </div>
                        </td>

                        <!-- Category -->
                        <td class="px-4 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-gradient-to-r from-purple-100 to-indigo-100 text-purple-700 border border-purple-300">
                                {{ $product->category?->name ?? 'Uncategorized' }}
                            </span>
                        </td>

                        <!-- Price -->
                        <td class="px-4 py-4 text-center">
                            <div class="text-sm font-bold text-gray-900">
                                ₹{{ number_format($product->price ?? 0, 2) }}
                                @if($product->sale_price && $product->sale_price < $product->price)
                                    <br>
                                    <span class="text-xs text-red-600 line-through">₹{{ number_format($product->sale_price, 2) }}</span>
                                @endif
                            </div>
                        </td>

                        <!-- Stock -->
                        <td class="px-4 py-4 text-center">
                            <div class="flex flex-col items-center">
                                <span class="text-sm font-bold text-gray-900">{{ $product->stock ?? 0 }}</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium mt-1
                                    {{ ($product->stock ?? 0) > 0 ? 'bg-green-100 text-green-800 border border-green-300' : 'bg-red-100 text-red-800 border border-red-300' }}">
                                    {{ ($product->stock ?? 0) > 0 ? '📦 In Stock' : '❌ Out of Stock' }}
                                </span>
                            </div>
                        </td>

                        <!-- Rejected Date -->
                        <td class="px-4 py-4 text-center">
                            @php
                                $updatedDate = is_string($product->updated_at) ? \Carbon\Carbon::parse($product->updated_at) : $product->updated_at;
                            @endphp
                            <div class="text-sm font-medium text-gray-900">{{ $updatedDate->format('M d, Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $updatedDate->diffForHumans() }}</div>
                        </td>

                        <!-- Rejection Reason -->
                        <td class="px-4 py-4 text-center">
                            @if($product->rejection_reason)
                                <div class="bg-red-50 border border-red-200 rounded-lg p-2">
                                    <p class="text-xs text-red-700 font-medium">{{ Str::limit($product->rejection_reason, 50) }}</p>
                                </div>
                            @else
                                <span class="text-xs text-gray-500">No reason provided</span>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="px-4 py-4">
                            <div class="flex items-center justify-center space-x-2">
                                <button class="view-btn inline-flex items-center justify-center w-9 h-9 text-blue-600 bg-blue-50 border-2 border-blue-200 rounded-lg hover:bg-blue-100 hover:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all"
                                        title="View Product"
                                        onclick="viewProduct({{ $product->id }})">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr id="emptyState">
                        <td colspan="9" class="px-6 py-20">
                            <div class="text-center">
                                <div class="mx-auto flex items-center justify-center w-24 h-24 bg-gradient-to-br from-red-100 to-pink-200 rounded-full mb-6">
                                    <svg class="w-12 h-12 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">No Rejected Products</h3>
                                <p class="text-gray-500 mb-6 max-w-md mx-auto">No products have been rejected yet.</p>
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
                    Showing <span class="font-bold text-red-600">{{ $products->firstItem() ?? 0 }}</span> to <span class="font-bold text-red-600">{{ $products->lastItem() ?? 0 }}</span> of <span class="font-bold text-red-600">{{ $products->total() }}</span> entries
                </div>
                <div class="dataTables_paginate">
                    @if($products->hasPages())
                        {{ $products->appends(request()->query())->links('admin.pagination.custom') }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Product Details Modal -->
<div class="fixed inset-0 bg-black bg-opacity-50 hidden z-50" id="productModal">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-red-500 to-pink-600 px-6 py-4 flex items-center justify-between">
                <h3 class="text-xl font-bold text-white">Product Details</h3>
                <button onclick="closeModal()" class="text-white hover:text-gray-200 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="p-6 overflow-y-auto max-h-[70vh]" id="productDetails">
                <div class="flex items-center justify-center py-12">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-red-600"></div>
                </div>
            </div>
            
            <!-- Modal Footer -->
            <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3 border-t border-gray-200">
                <button onclick="closeModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let currentProductId = null;

    function closeModal() {
        const modal = document.getElementById('productModal');
        if (modal) {
            modal.classList.add('hidden');
        }
        currentProductId = null;
    }

    function viewProduct(productId) {
        console.log('Opening product modal for ID:', productId);
        currentProductId = productId;
        const modal = document.getElementById('productModal');
        if (modal) {
            modal.classList.remove('hidden');
        } else {
            console.error('Product modal element not found');
            return;
        }
        
        // Load product details via fetch
        fetch(`/admin/products/${productId}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => {
                console.log('Product fetch response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Product data received:', data);
                const response = data.product || data; // Handle both formats
                const detailsContainer = document.getElementById('productDetails');
                if (detailsContainer) {
                    // Display product details with modern styling
                    detailsContainer.innerHTML = `
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div class="aspect-square bg-gray-100 rounded-xl overflow-hidden">
                                    <img src="${response.image || response.images?.[0] || '/placeholder.png'}" class="w-full h-full object-cover" alt="${response.name || 'Product'}">
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <h4 class="text-2xl font-bold text-gray-900 mb-2">${response.name || 'Unknown Product'}</h4>
                                    <div class="flex items-center space-x-2 mb-4">
                                        <span class="px-3 py-1 bg-red-100 text-red-800 text-sm font-medium rounded-full">Rejected</span>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-gray-50 p-3 rounded-lg">
                                        <p class="text-xs font-semibold text-gray-500 uppercase">SKU</p>
                                        <p class="text-sm font-bold text-gray-900">${response.sku || 'N/A'}</p>
                                    </div>
                                    <div class="bg-gray-50 p-3 rounded-lg">
                                        <p class="text-xs font-semibold text-gray-500 uppercase">Category</p>
                                        <p class="text-sm font-bold text-gray-900">${response.category?.name || 'N/A'}</p>
                                    </div>
                                    <div class="bg-gray-50 p-3 rounded-lg">
                                        <p class="text-xs font-semibold text-gray-500 uppercase">Price</p>
                                        <p class="text-sm font-bold text-gray-900">₹${response.price || 0}</p>
                                    </div>
                                    <div class="bg-gray-50 p-3 rounded-lg">
                                        <p class="text-xs font-semibold text-gray-500 uppercase">Stock</p>
                                        <p class="text-sm font-bold text-gray-900">${response.stock || 0}</p>
                                    </div>
                                </div>
                                
                                ${response.sale_price && response.sale_price > 0 ? `
                                    <div class="bg-red-50 p-3 rounded-lg border border-red-200">
                                        <p class="text-xs font-semibold text-red-600 uppercase">Sale Price</p>
                                        <p class="text-lg font-bold text-red-700">₹${response.sale_price}</p>
                                    </div>
                                ` : ''}
                                
                                ${response.rejection_reason ? `
                                    <div class="bg-red-50 p-3 rounded-lg border border-red-200">
                                        <p class="text-xs font-semibold text-red-600 uppercase">Rejection Reason</p>
                                        <p class="text-sm text-red-700">${response.rejection_reason}</p>
                                    </div>
                                ` : ''}
                                
                                <div>
                                    <p class="text-sm font-semibold text-gray-700 mb-2">Description:</p>
                                    <div class="bg-gray-50 p-3 rounded-lg text-sm text-gray-700">${response.description || 'No description available'}</div>
                                </div>
                            </div>
                        </div>
                    `;
                } else {
                    console.error('Product details container not found');
                }
            })
            .catch(error => {
                console.error('Error loading product details:', error);
                const detailsContainer = document.getElementById('productDetails');
                if (detailsContainer) {
                    detailsContainer.innerHTML = `
                        <div class="text-center py-8">
                            <div class="text-red-600 mb-2">
                                <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <p class="text-red-600 font-medium">Error loading product details: ${error.message}</p>
                        </div>
                    `;
                }
            });
    }

    // Wait for DOM to be ready
    document.addEventListener('DOMContentLoaded', function() {
        // Refresh button functionality
        const refreshBtn = document.getElementById('refreshBtn');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', function() {
                location.reload();
            });
        }

        // Close modal on outside click
        const productModal = document.getElementById('productModal');
        if (productModal) {
            productModal.addEventListener('click', function(e) {
                if(e.target === this) {
                    closeModal();
                }
            });
        }
    });
</script>
@endsection