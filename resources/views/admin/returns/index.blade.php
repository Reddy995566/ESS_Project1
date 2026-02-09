@extends('admin.layouts.app')

@section('title', 'Return Management')

@section('content')
<!-- Main Container -->
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-purple-50 to-indigo-50 p-6">
    
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="flex items-center space-x-4">
                <div class="flex items-center justify-center w-14 h-14 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-2xl shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-black text-gray-900 tracking-tight">Return Management</h1>
                    <p class="mt-1 text-sm text-gray-600">Manage customer return requests with advanced controls</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <button id="exportBtn" class="inline-flex items-center px-5 py-2.5 bg-white text-gray-700 text-sm font-semibold rounded-xl border-2 border-gray-200 hover:border-green-400 hover:bg-green-50 hover:text-green-700 transition-all duration-200 shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Export
                </button>
                <button id="refreshBtn" class="inline-flex items-center justify-center w-11 h-11 bg-white text-gray-700 rounded-xl border-2 border-gray-200 hover:border-purple-400 hover:bg-purple-50 hover:text-purple-700 transition-all duration-200 shadow-sm hover:shadow-md">
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
        <!-- Total Returns -->
        <div class="bg-gradient-to-br from-purple-500 to-indigo-600 rounded-2xl shadow-xl p-6 transform hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center justify-center w-12 h-12 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z"/>
                    </svg>
                </div>
                <span class="px-3 py-1 bg-white bg-opacity-20 backdrop-blur-sm rounded-full text-xs font-bold text-white">Total</span>
            </div>
            <div class="text-white">
                <p class="text-4xl font-black mb-1" id="totalReturns">{{ number_format($stats['total']) }}</p>
                <p class="text-purple-100 text-sm font-medium">Total Returns</p>
            </div>
        </div>

        <!-- Pending Returns -->
        <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl shadow-xl p-6 transform hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center justify-center w-12 h-12 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="px-3 py-1 bg-white bg-opacity-20 backdrop-blur-sm rounded-full text-xs font-bold text-white">Pending</span>
            </div>
            <div class="text-white">
                <p class="text-4xl font-black mb-1" id="pendingReturns">{{ number_format($stats['pending']) }}</p>
                <p class="text-amber-100 text-sm font-medium">Pending Returns</p>
            </div>
        </div>

        <!-- Approved Returns -->
        <div class="bg-gradient-to-br from-emerald-500 to-green-600 rounded-2xl shadow-xl p-6 transform hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center justify-center w-12 h-12 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="px-3 py-1 bg-white bg-opacity-20 backdrop-blur-sm rounded-full text-xs font-bold text-white">Approved</span>
            </div>
            <div class="text-white">
                <p class="text-4xl font-black mb-1" id="approvedReturns">{{ number_format($stats['approved']) }}</p>
                <p class="text-emerald-100 text-sm font-medium">Approved Returns</p>
            </div>
        </div>

        <!-- Refunded Returns -->
        <div class="bg-gradient-to-br from-blue-500 to-cyan-600 rounded-2xl shadow-xl p-6 transform hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center justify-center w-12 h-12 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                    </svg>
                </div>
                <span class="px-3 py-1 bg-white bg-opacity-20 backdrop-blur-sm rounded-full text-xs font-bold text-white">Refunded</span>
            </div>
            <div class="text-white">
                <p class="text-4xl font-black mb-1" id="refundedReturns">{{ number_format($stats['refunded']) }}</p>
                <p class="text-blue-100 text-sm font-medium">Refunded Returns</p>
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
                        <span class="flex items-center justify-center w-8 h-8 bg-purple-100 text-purple-600 rounded-lg mr-3">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                            </svg>
                        </span>
                        Returns Data Table
                    </h2>
                    <p class="text-sm text-gray-600 mt-1 ml-11">Comprehensive return management with advanced filtering and bulk actions</p>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="text-sm font-medium text-gray-600">Quick Actions:</span>
                    <select id="bulkAction" class="px-3 py-2 bg-white border-2 border-gray-300 rounded-lg text-sm font-medium focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        <option value="">Select Action</option>
                        <option value="approve">Approve</option>
                        <option value="reject">Reject</option>
                        <option value="pickup">Mark Picked Up</option>
                        <option value="refund">Process Refund</option>
                        <option value="delete" class="text-red-600">Delete Selected</option>
                    </select>
                    <button id="applyBulkAction" disabled class="px-4 py-2 bg-purple-600 text-white text-sm font-semibold rounded-lg hover:bg-purple-700 disabled:opacity-50 disabled:cursor-not-allowed transition-all">
                        Apply
                    </button>
                </div>
            </div>
        </div>

        <!-- Advanced Filters Section -->
        <div class="bg-gray-50 px-6 py-5 border-b border-gray-200">
            <form method="GET" action="{{ route('admin.returns.index') }}" id="filtersForm">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    <!-- Search -->
                    <div class="lg:col-span-2">
                        <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">Search Returns</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input type="text" name="search" id="globalSearch" class="block w-full pl-10 pr-3 py-3 border-2 border-gray-300 rounded-xl bg-white text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all" placeholder="Search by return number, customer, or order..." value="{{ request('search') }}">
                        </div>
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">Status Filter</label>
                        <select name="status" id="statusFilter" class="block w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>🕐 Pending</option>
                            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>✅ Approved</option>
                            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>❌ Rejected</option>
                            <option value="picked_up" {{ request('status') === 'picked_up' ? 'selected' : '' }}>📦 Picked Up</option>
                            <option value="refunded" {{ request('status') === 'refunded' ? 'selected' : '' }}>💰 Refunded</option>
                        </select>
                    </div>

                    <!-- Date Range Filter -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">Date Range</label>
                        <select name="date_range" id="dateRangeFilter" class="block w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            <option value="">All Time</option>
                            <option value="today" {{ request('date_range') === 'today' ? 'selected' : '' }}>Today</option>
                            <option value="yesterday" {{ request('date_range') === 'yesterday' ? 'selected' : '' }}>Yesterday</option>
                            <option value="this_week" {{ request('date_range') === 'this_week' ? 'selected' : '' }}>This Week</option>
                            <option value="last_week" {{ request('date_range') === 'last_week' ? 'selected' : '' }}>Last Week</option>
                            <option value="this_month" {{ request('date_range') === 'this_month' ? 'selected' : '' }}>This Month</option>
                            <option value="last_month" {{ request('date_range') === 'last_month' ? 'selected' : '' }}>Last Month</option>
                        </select>
                    </div>

                    <!-- Items Per Page -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">Show Entries</label>
                        <select name="per_page" id="itemsPerPage" class="block w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            <option value="10" {{ request('per_page', 20) == 10 ? 'selected' : '' }}>10 per page</option>
                            <option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20 per page</option>
                            <option value="50" {{ request('per_page', 20) == 50 ? 'selected' : '' }}>50 per page</option>
                            <option value="100" {{ request('per_page', 20) == 100 ? 'selected' : '' }}>100 per page</option>
                        </select>
                    </div>
                </div>

                <!-- Additional Filters Row -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-4">
                    <button type="submit" class="px-4 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white text-sm font-bold rounded-lg hover:from-purple-700 hover:to-indigo-700 transition-all flex items-center justify-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        Apply Filters
                    </button>

                    <button type="button" id="resetFilters" class="px-3 py-2.5 bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 text-sm font-bold rounded-lg hover:from-gray-200 hover:to-gray-300 transition-all flex items-center justify-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Reset
                    </button>

                    <!-- Selected Count -->
                    <div class="flex items-center h-12 px-4 bg-gradient-to-r from-purple-50 to-indigo-50 border-2 border-purple-200 rounded-xl col-span-2">
                        <svg class="w-5 h-5 text-purple-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span id="selectedCount" class="text-2xl font-black text-purple-700">0</span>
                        <span class="ml-2 text-sm font-semibold text-purple-600">returns selected</span>
                    </div>
                </div>
            </form>
        </div>

        <!-- Data Table -->
        <div class="overflow-x-auto">
            <table id="returnsTable" class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-slate-100 to-gray-100">
                    <tr>
                        <th class="px-4 py-4 text-center w-12">
                            <input type="checkbox" id="selectAll" class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-2 focus:ring-purple-500">
                        </th>
                        <th class="px-4 py-4 text-center w-16">
                            <span class="text-xs font-black text-gray-700 uppercase">ID</span>
                        </th>
                        <th class="px-4 py-4 text-left min-w-[200px]">
                            <span class="text-xs font-black text-gray-700 uppercase">Return Details</span>
                        </th>
                        <th class="px-4 py-4 text-left min-w-[180px]">
                            <span class="text-xs font-black text-gray-700 uppercase">Customer</span>
                        </th>
                        <th class="px-4 py-4 text-left min-w-[200px]">
                            <span class="text-xs font-black text-gray-700 uppercase">Product</span>
                        </th>
                        <th class="px-4 py-4 text-center w-28">
                            <span class="text-xs font-black text-gray-700 uppercase">Amount</span>
                        </th>
                        <th class="px-4 py-4 text-center w-28">
                            <span class="text-xs font-black text-gray-700 uppercase">Status</span>
                        </th>
                        <th class="px-4 py-4 text-center w-32">
                            <span class="text-xs font-black text-gray-700 uppercase">Date</span>
                        </th>
                        <th class="px-4 py-4 text-center w-32">
                            <span class="text-xs font-black text-gray-700 uppercase">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($returns as $return)
                    <tr class="group hover:bg-gradient-to-r hover:from-purple-50 hover:to-indigo-50 transition-all duration-200" 
                        data-id="{{ $return->id }}" 
                        data-status="{{ $return->status }}" 
                        data-amount="{{ $return->amount }}">
                        
                        <!-- Checkbox -->
                        <td class="px-4 py-4 text-center">
                            <input type="checkbox" class="row-select w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-2 focus:ring-purple-500" value="{{ $return->id }}">
                        </td>

                        <!-- ID -->
                        <td class="px-4 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 border border-gray-300">#{{ $return->id }}</span>
                        </td>

                        <!-- Return Details -->
                        <td class="px-4 py-4">
                            <div>
                                <div class="text-sm font-bold text-gray-900 mb-1">{{ $return->return_number }}</div>
                                <div class="text-xs text-gray-500 mb-1">Order: {{ $return->order->order_number }}</div>
                                <div class="text-xs text-purple-600 font-medium">{{ $return->reason_label }}</div>
                                @if($return->reason_details)
                                <div class="text-xs text-gray-400 mt-1">{{ Str::limit($return->reason_details, 50) }}</div>
                                @endif
                            </div>
                        </td>

                        <!-- Customer -->
                        <td class="px-4 py-4">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0 w-10 h-10">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-100 to-indigo-200 flex items-center justify-center border-2 border-gray-200">
                                        <span class="text-sm font-bold text-purple-600">{{ substr($return->user->name, 0, 1) }}</span>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <div class="text-sm font-bold text-gray-900">{{ $return->user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $return->user->email }}</div>
                                </div>
                            </div>
                        </td>

                        <!-- Product -->
                        <td class="px-4 py-4">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0 w-12 h-12">
                                    @if($return->orderItem->getImageUrl())
                                        <img src="{{ $return->orderItem->getImageUrl() }}" 
                                             alt="{{ $return->orderItem->product_name }}" 
                                             class="w-12 h-12 rounded-xl object-cover border-2 border-gray-200 shadow-sm">
                                    @else
                                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center border-2 border-gray-200">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <div class="text-sm font-bold text-gray-900 mb-1">{{ Str::limit($return->orderItem->product_name, 25) }}</div>
                                    <div class="text-xs text-gray-500">Qty: {{ $return->quantity }}</div>
                                    @if($return->orderItem->variant_name)
                                    <div class="text-xs text-blue-600">{{ $return->orderItem->variant_name }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <!-- Amount -->
                        <td class="px-4 py-4 text-center">
                            <div class="text-sm font-bold text-gray-900">₹{{ number_format($return->amount, 2) }}</div>
                            @if($return->refund_amount && $return->status === 'refunded')
                            <div class="text-xs text-green-600 font-medium">Refunded: ₹{{ number_format($return->refund_amount, 2) }}</div>
                            @endif
                        </td>

                        <!-- Status -->
                        <td class="px-4 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold {{ $return->status_badge }}">
                                @switch($return->status)
                                    @case('pending')
                                        🕐 Pending
                                        @break
                                    @case('approved')
                                        ✅ Approved
                                        @break
                                    @case('rejected')
                                        ❌ Rejected
                                        @break
                                    @case('picked_up')
                                        📦 Picked Up
                                        @break
                                    @case('refunded')
                                        💰 Refunded
                                        @break
                                    @default
                                        {{ ucfirst($return->status) }}
                                @endswitch
                            </span>
                        </td>

                        <!-- Date -->
                        <td class="px-4 py-4 text-center">
                            <div class="text-sm font-medium text-gray-900">{{ $return->requested_at->format('M j, Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $return->requested_at->format('g:i A') }}</div>
                            @if($return->requested_at->diffInDays(now()) <= 3)
                            <div class="text-xs text-green-600 font-medium mt-1">Within 3 days</div>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="px-4 py-4">
                            <div class="flex items-center justify-center space-x-2">
                                <button class="view-btn inline-flex items-center justify-center w-9 h-9 text-blue-600 bg-blue-50 border-2 border-blue-200 rounded-lg hover:bg-blue-100 hover:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all"
                                        title="View Return"
                                        data-id="{{ $return->id }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                                
                                @if($return->status === 'pending')
                                <button class="approve-btn inline-flex items-center justify-center w-9 h-9 text-green-600 bg-green-50 border-2 border-green-200 rounded-lg hover:bg-green-100 hover:border-green-400 focus:outline-none focus:ring-2 focus:ring-green-500 transition-all"
                                        title="Approve Return"
                                        data-id="{{ $return->id }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </button>
                                <button class="reject-btn inline-flex items-center justify-center w-9 h-9 text-red-600 bg-red-50 border-2 border-red-200 rounded-lg hover:bg-red-100 hover:border-red-400 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all"
                                        title="Reject Return"
                                        data-id="{{ $return->id }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                                @elseif($return->status === 'approved')
                                <button class="pickup-btn inline-flex items-center justify-center w-9 h-9 text-purple-600 bg-purple-50 border-2 border-purple-200 rounded-lg hover:bg-purple-100 hover:border-purple-400 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-all"
                                        title="Mark Picked Up"
                                        data-id="{{ $return->id }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                </button>
                                @elseif($return->status === 'picked_up')
                                <button class="refund-btn inline-flex items-center justify-center w-9 h-9 text-cyan-600 bg-cyan-50 border-2 border-cyan-200 rounded-lg hover:bg-cyan-100 hover:border-cyan-400 focus:outline-none focus:ring-2 focus:ring-cyan-500 transition-all"
                                        title="Process Refund"
                                        data-id="{{ $return->id }}"
                                        data-amount="{{ $return->amount }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                    </svg>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr id="emptyState">
                        <td colspan="9" class="px-6 py-20">
                            <div class="text-center">
                                <div class="mx-auto flex items-center justify-center w-24 h-24 bg-gradient-to-br from-purple-100 to-indigo-200 rounded-full mb-6">
                                    <svg class="w-12 h-12 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">No Returns Found</h3>
                                <p class="text-gray-500 mb-6 max-w-md mx-auto">No return requests match your current filters.</p>
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
                    Showing <span class="font-bold text-purple-600">{{ $returns->firstItem() ?? 0 }}</span> to <span class="font-bold text-purple-600">{{ $returns->lastItem() ?? 0 }}</span> of <span class="font-bold text-purple-600">{{ $returns->total() }}</span> entries
                </div>
                <div class="dataTables_paginate">
                    @if($returns->hasPages())
                        {{ $returns->appends(request()->query())->links() }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.returns.modals')
@include('admin.returns.scripts')
@endsection