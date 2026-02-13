@extends('seller.layouts.app')

@section('title', 'Pending Approval - Products')

@section('content')
<!-- Main Container -->
<div class="min-h-screen bg-gradient-to-br from-yellow-50 via-orange-50 to-amber-50 p-6">
    
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="flex items-center space-x-4">
                <div class="flex items-center justify-center w-14 h-14 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-2xl shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-black text-gray-900 tracking-tight">Pending Approval</h1>
                    <p class="mt-1 text-sm text-gray-600">Products waiting for admin review and approval</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('seller.products.index') }}" class="inline-flex items-center px-5 py-2.5 bg-white text-gray-700 text-sm font-semibold rounded-xl border-2 border-gray-200 hover:border-gray-400 hover:bg-gray-50 transition-all duration-200 shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to All Products
                </a>
                <a href="{{ route('seller.products.create.step1') }}" class="inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-yellow-500 to-orange-600 text-white text-sm font-bold rounded-xl hover:from-yellow-600 hover:to-orange-700 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Product
                </a>
            </div>
        </div>
    </div>

    <!-- Info Banner -->
    <div class="mb-6 bg-gradient-to-r from-yellow-50 to-orange-50 border-l-4 border-yellow-500 p-5 rounded-xl shadow-md">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-4 flex-1">
                <h3 class="text-sm font-bold text-yellow-800 mb-1">Under Review</h3>
                <p class="text-sm text-yellow-700">
                    These products are currently under review by our admin team. You'll receive a notification once they are approved or if any changes are required. Approved products will be visible to customers.
                </p>
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
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Pending -->
        <div class="bg-gradient-to-br from-yellow-500 to-orange-600 rounded-2xl shadow-xl p-6 transform hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center justify-center w-12 h-12 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="px-3 py-1 bg-white bg-opacity-20 backdrop-blur-sm rounded-full text-xs font-bold text-white">Pending</span>
            </div>
            <div class="text-white">
                <p class="text-4xl font-black mb-1">{{ $totalPending ?? $products->total() }}</p>
                <p class="text-yellow-100 text-sm font-medium">Total Pending</p>
            </div>
        </div>

        <!-- Awaiting Review -->
        <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl shadow-xl p-6 transform hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center justify-center w-12 h-12 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <span class="px-3 py-1 bg-white bg-opacity-20 backdrop-blur-sm rounded-full text-xs font-bold text-white">Queue</span>
            </div>
            <div class="text-white">
                <p class="text-4xl font-black mb-1">{{ $products->count() }}</p>
                <p class="text-blue-100 text-sm font-medium">In Review Queue</p>
            </div>
        </div>

        <!-- Est. Approval Time -->
        <div class="bg-gradient-to-br from-emerald-500 to-green-600 rounded-2xl shadow-xl p-6 transform hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center justify-center w-12 h-12 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="px-3 py-1 bg-white bg-opacity-20 backdrop-blur-sm rounded-full text-xs font-bold text-white">Timeline</span>
            </div>
            <div class="text-white">
                <p class="text-4xl font-black mb-1">24-48h</p>
                <p class="text-emerald-100 text-sm font-medium">Est. Approval Time</p>
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
                        <span class="flex items-center justify-center w-8 h-8 bg-yellow-100 text-yellow-600 rounded-lg mr-3">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                        </span>
                        Pending Products Queue
                    </h2>
                    <p class="text-sm text-gray-600 mt-1 ml-11">Products currently awaiting admin approval</p>
                </div>
                <div class="flex items-center space-x-2">
                    <button id="refreshBtn" class="inline-flex items-center justify-center w-11 h-11 bg-white text-gray-700 rounded-xl border-2 border-gray-200 hover:border-yellow-400 hover:bg-yellow-50 hover:text-yellow-700 transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Advanced Filters Section -->
        <div class="bg-gray-50 px-6 py-5 border-b border-gray-200">
            <form method="GET" action="{{ route('seller.products.pending') }}" id="filtersForm">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div class="lg:col-span-2">
                        <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">Search Products</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input type="text" name="search" id="globalSearch" class="block w-full pl-10 pr-3 py-3 border-2 border-gray-300 rounded-xl bg-white text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-all" placeholder="Search by name, SKU, or description..." value="{{ request('search') }}">
                        </div>
                    </div>

                    <!-- Category Filter -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">Category</label>
                        <select name="category" id="categoryFilter" class="block w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                            <option value="">All Categories</option>
                            @foreach($categories ?? [] as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Items Per Page -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">Show Entries</label>
                        <select name="per_page" id="itemsPerPage" class="block w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 per page</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 per page</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 per page</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 per page</option>
                        </select>
                    </div>
                </div>

                <!-- Filter Actions -->
                <div class="flex items-center gap-3 mt-4">
                    <button type="submit" class="px-4 py-2.5 bg-gradient-to-r from-yellow-500 to-orange-500 text-white text-sm font-bold rounded-lg hover:from-yellow-600 hover:to-orange-600 transition-all flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        Apply Filters
                    </button>

                    <button type="button" id="resetFilters" class="px-3 py-2.5 bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 text-sm font-bold rounded-lg hover:from-gray-200 hover:to-gray-300 transition-all flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Reset
                    </button>
                </div>
            </form>
        </div>

        <!-- Data Table -->
        <div class="overflow-x-auto">
            <table id="productsTable" class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-slate-100 to-gray-100">
                    <tr>
                        <th class="px-4 py-4 text-center w-16">
                            <span class="text-xs font-black text-gray-700 uppercase">ID</span>
                        </th>
                        <th class="px-4 py-4 text-left min-w-[280px]">
                            <span class="text-xs font-black text-gray-700 uppercase">Product</span>
                        </th>
                        <th class="px-4 py-4 text-center w-32">
                            <span class="text-xs font-black text-gray-700 uppercase">SKU</span>
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
                            <span class="text-xs font-black text-gray-700 uppercase">Submitted</span>
                        </th>
                        <th class="px-4 py-4 text-center w-28">
                            <span class="text-xs font-black text-gray-700 uppercase">Status</span>
                        </th>
                        <th class="px-4 py-4 text-center w-32">
                            <span class="text-xs font-black text-gray-700 uppercase">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($products as $product)
                    <tr class="group hover:bg-gradient-to-r hover:from-yellow-50 hover:to-orange-50 transition-all duration-200" data-id="{{ $product->id }}">
                        
                        <!-- ID -->
                        <td class="px-4 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 border border-gray-300">#{{ $product->id }}</span>
                        </td>

                        <!-- Product Info -->
                        <td class="px-4 py-4">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0 w-14 h-14">
                                    @if($product->getFirstImageUrl())
                                        <img src="{{ $product->getFirstImageUrl() }}" alt="{{ $product->name }}" class="w-14 h-14 rounded-xl object-cover border-2 border-gray-200 shadow-sm">
                                    @else
                                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center border-2 border-gray-200">
                                            <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-sm font-bold text-gray-900 mb-1">{{ $product->name }}</h3>
                                    <p class="text-xs text-gray-500 leading-relaxed">{{ Str::limit($product->description ?? 'No description', 60) }}</p>
                                    <div class="flex items-center space-x-2 mt-2">
                                        @if($product->is_featured)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-300">
                                                ⭐ Featured
                                            </span>
                                        @endif
                                        @if($product->is_new)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-300">
                                                🆕 New
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- SKU -->
                        <td class="px-4 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-gradient-to-r from-blue-100 to-cyan-100 text-blue-700 border border-blue-300">
                                {{ $product->sku ?? 'N/A' }}
                            </span>
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
                                    {{ $product->stock_status === 'in_stock' ? 'bg-green-100 text-green-800 border border-green-300' : 'bg-red-100 text-red-800 border border-red-300' }}">
                                    {{ $product->stock_status === 'in_stock' ? '📦 In Stock' : '❌ Out of Stock' }}
                                </span>
                            </div>
                        </td>

                        <!-- Submitted Date -->
                        <td class="px-4 py-4 text-center">
                            <div class="flex flex-col items-center">
                                <span class="text-sm font-medium text-gray-900">{{ $product->created_at->format('M d, Y') }}</span>
                                <span class="text-xs text-gray-500 mt-1">{{ $product->created_at->diffForHumans() }}</span>
                            </div>
                        </td>

                        <!-- Status Badge -->
                        <td class="px-4 py-4 text-center">
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800 border-2 border-yellow-300">
                                <svg class="w-3 h-3 mr-1 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                </svg>
                                Pending
                            </span>
                        </td>

                        <!-- Actions -->
                        <td class="px-4 py-4">
                            <div class="flex items-center justify-center space-x-2">
                                <button class="view-btn inline-flex items-center justify-center w-9 h-9 text-blue-600 bg-blue-50 border-2 border-blue-200 rounded-lg hover:bg-blue-100 hover:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all"
                                        title="View Product"
                                        data-id="{{ $product->id }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                                <a href="{{ route('seller.products.edit', $product->id) }}" 
                                   class="inline-flex items-center justify-center w-9 h-9 text-indigo-600 bg-indigo-50 border-2 border-indigo-200 rounded-lg hover:bg-indigo-100 hover:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all"
                                   title="Edit Product">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <button type="button" class="delete-btn inline-flex items-center justify-center w-9 h-9 text-red-600 bg-red-50 border-2 border-red-200 rounded-lg hover:bg-red-100 hover:border-red-400 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all" 
                                        title="Delete Product"
                                        data-id="{{ $product->id }}"
                                        data-name="{{ $product->name }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr id="emptyState">
                        <td colspan="9" class="px-6 py-20">
                            <div class="text-center">
                                <div class="mx-auto flex items-center justify-center w-24 h-24 bg-gradient-to-br from-yellow-100 to-orange-200 rounded-full mb-6">
                                    <svg class="w-12 h-12 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">No Pending Products</h3>
                                <p class="text-gray-500 mb-6 max-w-md mx-auto">You don't have any products waiting for approval. All your products have been reviewed.</p>
                                <div class="flex justify-center space-x-3">
                                    <a href="{{ route('seller.products.index') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 text-sm font-bold rounded-xl hover:from-gray-200 hover:to-gray-300 transition-all">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                        </svg>
                                        View All Products
                                    </a>
                                    <a href="{{ route('seller.products.create.step1') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-yellow-500 to-orange-500 text-white text-sm font-bold rounded-xl hover:from-yellow-600 hover:to-orange-600 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        Add New Product
                                    </a>
                                </div>
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
                    Showing <span class="font-bold text-yellow-600">{{ $products->firstItem() ?? 0 }}</span> to <span class="font-bold text-yellow-600">{{ $products->lastItem() ?? 0 }}</span> of <span class="font-bold text-yellow-600">{{ $products->total() }}</span> entries
                </div>
                <div class="dataTables_paginate">
                    {{ $products->appends(request()->query())->links('pagination::tailwind') }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Product View Modal -->
@include('seller.products.modal-view')

@push('scripts')
<script>
/**
 * Pending Products Management Script
 */

document.addEventListener('DOMContentLoaded', function() {
    initializeFilters();
    initializeActionButtons();
    initializeModal();
});

// Filters
function initializeFilters() {
    const globalSearch = document.getElementById('globalSearch');
    const categoryFilter = document.getElementById('categoryFilter');
    const itemsPerPage = document.getElementById('itemsPerPage');
    const resetFiltersBtn = document.getElementById('resetFilters');
    const refreshBtn = document.getElementById('refreshBtn');

    // Debounce for search
    let searchTimeout;
    globalSearch?.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            document.getElementById('filtersForm').submit();
        }, 500);
    });

    globalSearch?.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            clearTimeout(searchTimeout);
            document.getElementById('filtersForm').submit();
        }
    });

    // Auto-submit filters on change
    categoryFilter?.addEventListener('change', () => document.getElementById('filtersForm').submit());
    itemsPerPage?.addEventListener('change', () => document.getElementById('filtersForm').submit());

    // Reset Logic
    resetFiltersBtn?.addEventListener('click', function() {
        window.location.href = '{{ route("seller.products.pending") }}';
    });

    // Refresh Button
    refreshBtn?.addEventListener('click', function() {
        window.location.reload();
    });
}

// Action Buttons
function initializeActionButtons() {
    // View buttons - Open modal
    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.dataset.id;
            openProductModal(productId);
        });
    });

    // Delete buttons
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.dataset.id;
            const productName = this.dataset.name;
            
            if (confirm(`Are you sure you want to delete "${productName}"? This action cannot be undone.`)) {
                deleteProduct(productId);
            }
        });
    });
}

async function deleteProduct(productId) {
    showLoading('Deleting product...');
    
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
        const response = await fetch(`/seller/products/${productId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken || '',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });

        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            throw new Error('Server returned non-JSON response');
        }

        const data = await response.json();

        if (data.success) {
            showToast(data.message, 'success');
            
            // Remove row from table
            const row = document.querySelector(`tr[data-id="${productId}"]`);
            if (row) {
                row.remove();
            }
            
            setTimeout(() => window.location.reload(), 1500);
        } else {
            showToast(data.message || 'Delete failed', 'error');
        }
    } catch (error) {
        showToast('An error occurred while deleting the product: ' + error.message, 'error');
        console.error('Delete error:', error);
    } finally {
        hideLoading();
    }
}

function showLoading(message = 'Loading...') {
    let overlay = document.getElementById('loadingOverlay');
    if (!overlay) {
        overlay = document.createElement('div');
        overlay.id = 'loadingOverlay';
        overlay.className = 'fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50';
        overlay.innerHTML = `
            <div class="bg-white rounded-lg p-6 flex items-center space-x-4 shadow-xl">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-yellow-500"></div>
                <span class="text-gray-700 font-medium" id="loadingMessage">${message}</span>
            </div>
        `;
        document.body.appendChild(overlay);
    } else {
        document.getElementById('loadingMessage').textContent = message;
        overlay.classList.remove('hidden');
    }
}

function hideLoading() {
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) {
        overlay.classList.add('hidden');
    }
}

function showToast(message, type = 'info') {
    let container = document.getElementById('toastContainer');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toastContainer';
        container.className = 'fixed top-4 right-4 z-50 space-y-2';
        document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
    const icon = type === 'success' ? '✓' : type === 'error' ? '✕' : 'ℹ';

    toast.className = `${bgColor} text-white px-6 py-4 rounded-lg shadow-lg flex items-center space-x-3 transform translate-x-full transition-transform duration-300 ease-in-out`;
    toast.innerHTML = `
        <span class="text-lg font-bold">${icon}</span>
        <span class="font-medium">${message}</span>
        <button onclick="this.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
        </button>
    `;

    container.appendChild(toast);

    setTimeout(() => {
        toast.classList.remove('translate-x-full');
    }, 100);

    setTimeout(() => {
        toast.classList.add('translate-x-full');
        setTimeout(() => {
            if (toast.parentElement) {
                toast.remove();
            }
        }, 300);
    }, 5000);
}

// Product Modal Functions
function initializeModal() {
    const modal = document.getElementById('productModal');
    const closeButtons = [document.getElementById('closeModalBtn'), document.getElementById('closeModal')];
    const modalOverlay = document.getElementById('modalOverlay');
    
    closeButtons.forEach(btn => {
        if (btn) {
            btn.addEventListener('click', closeProductModal);
        }
    });
    
    if (modalOverlay) {
        modalOverlay.addEventListener('click', closeProductModal);
    }
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
            closeProductModal();
        }
    });
}

function openProductModal(productId) {
    const modal = document.getElementById('productModal');
    if (!modal) {
        showToast('Modal not found', 'error');
        return;
    }
    
    const modalLoading = document.getElementById('modalLoading');
    const productContent = document.getElementById('productContent');
    
    modal.classList.remove('hidden');
    if (modalLoading) modalLoading.classList.remove('hidden');
    if (productContent) productContent.classList.add('hidden');
    
    const timestampEl = document.getElementById('viewTimestamp');
    if (timestampEl) timestampEl.textContent = new Date().toLocaleString();
    
    fetchProductData(productId);
}

async function fetchProductData(productId) {
    try {
        const response = await fetch(`/seller/products/${productId}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        
        if (data.success) {
            populateModal(data.product);
            const modalLoading = document.getElementById('modalLoading');
            const productContent = document.getElementById('productContent');
            if (modalLoading) modalLoading.classList.add('hidden');
            if (productContent) productContent.classList.remove('hidden');
        } else {
            throw new Error(data.message || 'Failed to load product');
        }
    } catch (error) {
        const modalLoading = document.getElementById('modalLoading');
        if (modalLoading) {
            modalLoading.innerHTML = `
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Error Loading Product</h3>
                    <p class="mt-2 text-sm text-gray-500">${error.message}</p>
                    <button onclick="closeProductModal()" class="mt-4 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        Close
                    </button>
                </div>
            `;
        }
    }
}

function populateModal(product) {
    const nameElement = document.getElementById('modalProductName');
    const skuElement = document.getElementById('modalProductSku');
    const idElement = document.getElementById('modalProductId');
    
    if (nameElement) nameElement.textContent = product.name || 'Unnamed Product';
    if (skuElement) skuElement.textContent = product.sku ? `SKU: ${product.sku}` : 'No SKU';
    if (idElement) idElement.textContent = `#${product.id}`;
    
    const priceEl = document.getElementById('modalProductPrice');
    if (priceEl) {
        priceEl.textContent = `₹${parseFloat(product.price || 0).toLocaleString('en-IN', {minimumFractionDigits: 2})}`;
    }
    
    const stockEl = document.getElementById('modalProductStock');
    const stockStatusEl = document.getElementById('modalProductStockStatus');
    if (stockEl) stockEl.textContent = product.stock || 0;
    if (stockStatusEl) {
        stockStatusEl.textContent = product.stock_status === 'in_stock' ? '📦 In Stock' : '❌ Out of Stock';
        stockStatusEl.className = product.stock_status === 'in_stock' ? 'text-green-600' : 'text-red-600';
    }
    
    const statusElement = document.getElementById('modalProductStatus');
    if (statusElement) {
        statusElement.textContent = '⏳ Pending Approval';
        statusElement.className = 'text-yellow-600 font-bold';
    }
    
    const categoryEl = document.getElementById('modalProductCategory');
    if (categoryEl) {
        categoryEl.textContent = product.category?.name || 'Uncategorized';
    }
    
    const descEl = document.getElementById('modalProductDescription');
    if (descEl) {
        descEl.innerHTML = product.description || '<em class="text-gray-500">No description available</em>';
    }
    
    const mainImageEl = document.getElementById('modalProductMainImage');
    if (mainImageEl) {
        // Use image_url accessor which handles fallback to variants
        const imageUrl = product.image_url || product.image;
        if (imageUrl) {
            mainImageEl.src = imageUrl;
            mainImageEl.alt = product.name;
        } else {
            mainImageEl.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzIwIiBoZWlnaHQ9IjMwMCIgdmlld0JveD0iMCAwIDMyMCAzMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIzMjAiIGhlaWdodD0iMzAwIiBmaWxsPSIjRjNGNEY2Ii8+CjxwYXRoIGQ9Ik0xNjAgMTIwQzE3MS4wNDYgMTIwIDE4MCAzMTEuMDQ2IDE4MCAzMjJDMTgwIDEzMi45NTQgMTcxLjA0NiAxNDQgMTYwIDE0NEMxNDguOTU0IDE0NCAxNDAgMTMyLjk1NCAxNDAgMTIyQzE0MCAxMTEuMDQ2IDE0OC45NTQgMTAwIDE2MCAxMDBaIiBmaWxsPSIjOUNBM0FGIi8+CjxwYXRoIGQ9Ik0xMDAgMTgwQzEwNSAxODAgMTA5IDIwNS4yMyAxMDkgMjE2QzEwOSAyMjYuNzcgMTA1IDIzMiAxMDAgMjMySDIyMEMyMTUgMjMyIDIxMSAyMjYuNzcgMjExIDIxNkMyMTEgMjA1LjIzIDIxNSAxODAgMjIwIDE4MEgxMDBaIiBmaWxsPSIjOUNBM0FGIi8+Cjwvc3ZnPgo=';
            mainImageEl.alt = 'No image available';
        }
    }
    
    setupModalImageGallery(product);
}

function setupModalImageGallery(product) {
    const thumbnailsContainer = document.getElementById('modalImageThumbnails');
    if (!thumbnailsContainer) return;
    
    thumbnailsContainer.innerHTML = '';
    
    // Use images_array accessor which properly handles all image formats
    const images = [];
    
    // Add main image first if available
    const mainImage = product.image_url || product.image;
    if (mainImage) images.push(mainImage);
    
    // Add gallery images from images_array accessor
    if (product.images_array && Array.isArray(product.images_array)) {
        product.images_array.forEach(img => {
            if (typeof img === 'string' && !images.includes(img)) {
                images.push(img);
            }
        });
    }
    
    if (images.length > 1) {
        images.forEach((image, index) => {
            const thumbnail = document.createElement('img');
            thumbnail.src = image;
            thumbnail.alt = `${product.name} - Image ${index + 1}`;
            thumbnail.className = `w-16 h-16 object-cover rounded-lg cursor-pointer border-2 transition-all duration-200 ${
                index === 0 ? 'border-yellow-500' : 'border-gray-200 hover:border-yellow-300'
            }`;
            thumbnail.addEventListener('click', () => {
                const mainImageEl = document.getElementById('modalProductMainImage');
                if (mainImageEl) mainImageEl.src = image;
                
                thumbnailsContainer.querySelectorAll('img').forEach((thumb, i) => {
                    thumb.className = `w-16 h-16 object-cover rounded-lg cursor-pointer border-2 transition-all duration-200 ${
                        i === index ? 'border-yellow-500' : 'border-gray-200 hover:border-yellow-300'
                    }`;
                });
            });
            thumbnailsContainer.appendChild(thumbnail);
        });
    }
}

function closeProductModal() {
    const modal = document.getElementById('productModal');
    if (modal) {
        modal.classList.add('hidden');
    }
}
</script>
@endpush

<style>
/* Animation for slide in */
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-slideIn {
    animation: slideIn 0.3s ease-out;
}

/* Pulse animation for pending status */
@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
</style>
@endsection
