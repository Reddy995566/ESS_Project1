@extends('seller.layouts.app')

@section('title', 'Sales Analytics')

@section('content')
    <!-- Main Container -->
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-indigo-50 to-blue-50 p-6">
        
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="flex items-center justify-center w-14 h-14 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-black text-gray-900 tracking-tight">Sales Analytics</h1>
                        <p class="mt-1 text-sm text-gray-600">Track your sales performance and revenue</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <!-- Date Range Filter -->
                    <form method="GET" class="flex items-center space-x-2">
                        <input type="date" name="start_date" value="{{ request('start_date', $startDate->format('Y-m-d')) }}" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <span class="text-gray-500">to</span>
                        <input type="date" name="end_date" value="{{ request('end_date', $endDate->format('Y-m-d')) }}" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all text-sm font-medium">
                            Apply
                        </button>
                    </form>
                    <button id="exportBtn" class="inline-flex items-center px-4 py-2 bg-white text-gray-700 text-sm font-semibold rounded-xl border-2 border-gray-200 hover:border-indigo-400 hover:bg-indigo-50 hover:text-indigo-700 transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Export
                    </button>
                </div>
            </div>
        </div>

        <!-- Analytics Navigation -->
        <div class="mb-8">
            <nav class="flex space-x-1 bg-white rounded-xl p-1 shadow-sm border border-gray-200">
                <a href="{{ route('seller.analytics.sales') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-lg bg-indigo-100 text-indigo-700 border border-indigo-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Sales
                </a>
                <a href="{{ route('seller.analytics.products') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition-all">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    Products
                </a>
                <a href="{{ route('seller.analytics.customers') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition-all">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    Customers
                </a>
            </nav>
        </div>

        <!-- Key Metrics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Total Revenue -->
            <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl shadow-xl p-6 transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center justify-center w-12 h-12 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="px-3 py-1 bg-white bg-opacity-20 backdrop-blur-sm rounded-full text-xs font-bold text-white">Revenue</span>
                </div>
                <div class="text-white">
                    <p class="text-4xl font-black mb-1">₹{{ number_format($metrics['total_revenue'] ?? 0, 0) }}</p>
                    <p class="text-green-100 text-sm font-medium">Total Revenue</p>
                </div>
            </div>

            <!-- Total Orders -->
            <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl shadow-xl p-6 transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center justify-center w-12 h-12 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <span class="px-3 py-1 bg-white bg-opacity-20 backdrop-blur-sm rounded-full text-xs font-bold text-white">Orders</span>
                </div>
                <div class="text-white">
                    <p class="text-4xl font-black mb-1">{{ number_format($metrics['total_orders'] ?? 0) }}</p>
                    <p class="text-blue-100 text-sm font-medium">Total Orders</p>
                </div>
            </div>

            <!-- Average Order Value -->
            <div class="bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl shadow-xl p-6 transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center justify-center w-12 h-12 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                    <span class="px-3 py-1 bg-white bg-opacity-20 backdrop-blur-sm rounded-full text-xs font-bold text-white">AOV</span>
                </div>
                <div class="text-white">
                    <p class="text-4xl font-black mb-1">₹{{ number_format($metrics['average_order_value'] ?? 0, 0) }}</p>
                    <p class="text-purple-100 text-sm font-medium">Avg Order Value</p>
                </div>
            </div>

            <!-- Items Sold -->
            <div class="bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl shadow-xl p-6 transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center justify-center w-12 h-12 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <span class="px-3 py-1 bg-white bg-opacity-20 backdrop-blur-sm rounded-full text-xs font-bold text-white">Items</span>
                </div>
                <div class="text-white">
                    <p class="text-4xl font-black mb-1">{{ number_format($metrics['total_items_sold'] ?? 0) }}</p>
                    <p class="text-orange-100 text-sm font-medium">Items Sold</p>
                </div>
            </div>
        </div>
        <!-- Advanced Analytics Dashboard -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            
            <!-- Interactive Sales Trend Chart -->
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-slate-50 px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-bold text-gray-900 flex items-center">
                            <span class="flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-600 rounded-lg mr-3">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </span>
                            Sales Performance Trend
                        </h2>
                        <div class="flex items-center space-x-2">
                            <select id="chartPeriod" class="text-sm border border-gray-300 rounded-lg px-3 py-1 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="7">Last 7 days</option>
                                <option value="30" selected>Last 30 days</option>
                                <option value="90">Last 90 days</option>
                                <option value="365">Last year</option>
                            </select>
                            <button id="refreshChart" class="p-1 text-gray-500 hover:text-indigo-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div id="chartLoading" class="hidden text-center py-8">
                        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
                        <p class="mt-2 text-sm text-gray-500">Loading chart data...</p>
                    </div>
                    <canvas id="salesTrendChart" width="400" height="200"></canvas>
                </div>
            </div>

            <!-- Real-time Performance Metrics -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-slate-50 px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-bold text-gray-900 flex items-center">
                        <span class="flex items-center justify-center w-8 h-8 bg-purple-100 text-purple-600 rounded-lg mr-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </span>
                        Performance Insights
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <!-- Growth Rate -->
                    <div class="flex items-center justify-between p-3 bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg border border-green-200">
                        <div>
                            <p class="text-sm font-medium text-gray-700">Revenue Growth</p>
                            <p class="text-lg font-bold text-green-700" id="revenueGrowth">
                                @if(isset($analytics['overview']['revenue']['change']))
                                    {{ $analytics['overview']['revenue']['change'] > 0 ? '+' : '' }}{{ number_format($analytics['overview']['revenue']['change'], 1) }}%
                                @else
                                    +0.0%
                                @endif
                            </p>
                        </div>
                        <div class="text-green-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Order Growth -->
                    <div class="flex items-center justify-between p-3 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-200">
                        <div>
                            <p class="text-sm font-medium text-gray-700">Order Growth</p>
                            <p class="text-lg font-bold text-blue-700" id="orderGrowth">
                                @if(isset($analytics['overview']['orders']['change']))
                                    {{ $analytics['overview']['orders']['change'] > 0 ? '+' : '' }}{{ number_format($analytics['overview']['orders']['change'], 1) }}%
                                @else
                                    +0.0%
                                @endif
                            </p>
                        </div>
                        <div class="text-blue-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Conversion Rate -->
                    <div class="flex items-center justify-between p-3 bg-gradient-to-r from-purple-50 to-pink-50 rounded-lg border border-purple-200">
                        <div>
                            <p class="text-sm font-medium text-gray-700">Conversion Rate</p>
                            <p class="text-lg font-bold text-purple-700" id="conversionRate">
                                {{ number_format($analytics['performance_metrics']['conversion_rate'] ?? 0, 1) }}%
                            </p>
                        </div>
                        <div class="text-purple-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Return Rate -->
                    <div class="flex items-center justify-between p-3 bg-gradient-to-r from-orange-50 to-red-50 rounded-lg border border-orange-200">
                        <div>
                            <p class="text-sm font-medium text-gray-700">Return Rate</p>
                            <p class="text-lg font-bold text-orange-700" id="returnRate">
                                {{ number_format($analytics['performance_metrics']['return_rate'] ?? 0, 1) }}%
                            </p>
                        </div>
                        <div class="text-orange-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue Breakdown and Category Analysis -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            
            <!-- Revenue by Category with Interactive Features -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-slate-50 px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-bold text-gray-900 flex items-center">
                            <span class="flex items-center justify-center w-8 h-8 bg-green-100 text-green-600 rounded-lg mr-3">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                                </svg>
                            </span>
                            Revenue by Category
                        </h2>
                        <button id="toggleChartType" class="text-sm bg-green-100 text-green-700 px-3 py-1 rounded-lg hover:bg-green-200 transition-colors">
                            Switch to Bar Chart
                        </button>
                    </div>
                </div>
                <div class="p-6">
                    <canvas id="categoryChart" width="400" height="200"></canvas>
                    <div class="mt-4 space-y-2" id="categoryLegend">
                        @foreach($revenueByCategory as $index => $category)
                        <div class="flex items-center justify-between text-sm">
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full mr-2" style="background-color: {{ ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#06B6D4', '#84CC16', '#F97316', '#EC4899', '#6B7280'][$index % 10] }}"></div>
                                <span class="font-medium">{{ $category->name }}</span>
                            </div>
                            <span class="text-gray-600">₹{{ number_format($category->revenue, 0) }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Advanced Report Generation -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-slate-50 px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-bold text-gray-900 flex items-center">
                        <span class="flex items-center justify-center w-8 h-8 bg-indigo-100 text-indigo-600 rounded-lg mr-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </span>
                        Generate Reports
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Report Type</label>
                            <select id="reportType" class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="sales">Sales Report</option>
                                <option value="products">Product Report</option>
                                <option value="customers">Customer Report</option>
                                <option value="inventory">Inventory Report</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Format</label>
                            <select id="reportFormat" class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="pdf">PDF</option>
                                <option value="excel">Excel</option>
                                <option value="csv">CSV</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                            <input type="date" id="reportStartDate" value="{{ $startDate->format('Y-m-d') }}" class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                            <input type="date" id="reportEndDate" value="{{ $endDate->format('Y-m-d') }}" class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>

                    <button id="generateReport" class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold py-3 px-4 rounded-lg hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Generate Report
                    </button>

                    <div id="reportStatus" class="hidden">
                        <div class="flex items-center p-3 bg-blue-50 border border-blue-200 rounded-lg">
                            <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-600 mr-3"></div>
                            <span class="text-sm text-blue-700">Generating report...</span>
                        </div>
                    </div>

                    <div id="reportSuccess" class="hidden">
                        <div class="flex items-center justify-between p-3 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-sm text-green-700">Report generated successfully!</span>
                            </div>
                            <a id="downloadLink" href="#" class="text-sm bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 transition-colors">
                                Download
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Products Table -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-gray-50 to-slate-50 px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-bold text-gray-900 flex items-center">
                    <span class="flex items-center justify-center w-8 h-8 bg-purple-100 text-purple-600 rounded-lg mr-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </span>
                    Top Performing Products
                </h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Units Sold</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Revenue</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($topProducts as $product)
                        <tr class="hover:bg-gray-50 transition-all">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-12 w-12">
                                        @if($product->images && count($product->images) > 0)
                                            <img class="h-12 w-12 rounded-lg object-cover" src="{{ $product->images[0]['url'] ?? '/placeholder.jpg' }}" alt="{{ $product->name }}">
                                        @else
                                            <div class="h-12 w-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                        <div class="text-sm text-gray-500">SKU: {{ $product->sku ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    {{ number_format($product->units_sold ?? 0) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    ₹{{ number_format($product->revenue ?? 0, 0) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-sm text-gray-900">{{ $product->category->name ?? 'Uncategorized' }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                    <p class="text-lg font-medium">No sales data available</p>
                                    <p class="text-sm">Sales data will appear here once you start making sales.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Chart.js and Advanced Analytics Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
    <script>
        // Global variables for charts
        let salesTrendChart;
        let categoryChart;
        let isBarChart = false;
        
        // Initialize charts
        document.addEventListener('DOMContentLoaded', function() {
            initializeSalesTrendChart();
            initializeCategoryChart();
            setupEventListeners();
            startRealTimeUpdates();
        });

        // Enhanced Sales Trend Chart with Animation and Interactivity
        function initializeSalesTrendChart() {
            const salesTrendCtx = document.getElementById('salesTrendChart').getContext('2d');
            const salesTrendData = @json($salesTrend);
            
            salesTrendChart = new Chart(salesTrendCtx, {
                type: 'line',
                data: {
                    labels: salesTrendData.map(item => new Date(item.date).toLocaleDateString()),
                    datasets: [{
                        label: 'Revenue (₹)',
                        data: salesTrendData.map(item => item.total),
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: 'rgb(59, 130, 246)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 8
                    }, {
                        label: 'Orders',
                        data: salesTrendData.map(item => item.orders),
                        borderColor: 'rgb(16, 185, 129)',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        tension: 0.4,
                        yAxisID: 'y1',
                        pointBackgroundColor: 'rgb(16, 185, 129)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 2000,
                        easing: 'easeInOutQuart'
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 20
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderColor: 'rgba(255, 255, 255, 0.1)',
                            borderWidth: 1,
                            cornerRadius: 8,
                            displayColors: true,
                            callbacks: {
                                label: function(context) {
                                    if (context.datasetIndex === 0) {
                                        return 'Revenue: ₹' + new Intl.NumberFormat('en-IN').format(context.parsed.y);
                                    } else {
                                        return 'Orders: ' + context.parsed.y;
                                    }
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#6B7280'
                            }
                        },
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            title: {
                                display: true,
                                text: 'Revenue (₹)',
                                color: '#374151',
                                font: {
                                    weight: 'bold'
                                }
                            },
                            grid: {
                                color: 'rgba(107, 114, 128, 0.1)'
                            },
                            ticks: {
                                color: '#6B7280',
                                callback: function(value) {
                                    return '₹' + new Intl.NumberFormat('en-IN', {
                                        notation: 'compact',
                                        compactDisplay: 'short'
                                    }).format(value);
                                }
                            }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            title: {
                                display: true,
                                text: 'Orders',
                                color: '#374151',
                                font: {
                                    weight: 'bold'
                                }
                            },
                            grid: {
                                drawOnChartArea: false,
                            },
                            ticks: {
                                color: '#6B7280'
                            }
                        }
                    }
                }
            });
        }

        // Enhanced Category Chart with Toggle Functionality
        function initializeCategoryChart() {
            const categoryCtx = document.getElementById('categoryChart').getContext('2d');
            const categoryData = @json($revenueByCategory);
            
            categoryChart = new Chart(categoryCtx, {
                type: 'doughnut',
                data: {
                    labels: categoryData.map(item => item.name),
                    datasets: [{
                        data: categoryData.map(item => item.revenue),
                        backgroundColor: [
                            '#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6',
                            '#06B6D4', '#84CC16', '#F97316', '#EC4899', '#6B7280'
                        ],
                        borderWidth: 3,
                        borderColor: '#fff',
                        hoverBorderWidth: 5,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        animateRotate: true,
                        animateScale: true,
                        duration: 2000
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderColor: 'rgba(255, 255, 255, 0.1)',
                            borderWidth: 1,
                            cornerRadius: 8,
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((context.parsed / total) * 100).toFixed(1);
                                    return context.label + ': ₹' + new Intl.NumberFormat('en-IN').format(context.parsed) + ' (' + percentage + '%)';
                                }
                            }
                        }
                    }
                }
            });
        }

        // Setup Event Listeners for Interactive Features
        function setupEventListeners() {
            // Chart period selector
            document.getElementById('chartPeriod').addEventListener('change', function() {
                updateChartData(this.value);
            });

            // Refresh chart button
            document.getElementById('refreshChart').addEventListener('click', function() {
                refreshChartData();
            });

            // Toggle chart type
            document.getElementById('toggleChartType').addEventListener('click', function() {
                toggleCategoryChartType();
            });

            // Report generation
            document.getElementById('generateReport').addEventListener('click', function() {
                generateAdvancedReport();
            });

            // Export functionality
            document.getElementById('exportBtn').addEventListener('click', function() {
                showExportOptions();
            });
        }

        // Real-time Updates
        function startRealTimeUpdates() {
            // Update metrics every 30 seconds
            setInterval(function() {
                updateRealTimeMetrics();
            }, 30000);
        }

        // Update chart data based on period selection
        function updateChartData(period) {
            showChartLoading(true);
            
            fetch(`{{ route('seller.analytics.getAnalyticsData') }}?type=sales_chart&range=${period}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateSalesTrendChart(data.data);
                }
                showChartLoading(false);
            })
            .catch(error => {
                console.error('Error updating chart:', error);
                showChartLoading(false);
            });
        }

        // Refresh chart data
        function refreshChartData() {
            const period = document.getElementById('chartPeriod').value;
            updateChartData(period);
            
            // Show refresh animation
            const refreshBtn = document.getElementById('refreshChart');
            refreshBtn.classList.add('animate-spin');
            setTimeout(() => {
                refreshBtn.classList.remove('animate-spin');
            }, 1000);
        }

        // Toggle category chart type
        function toggleCategoryChartType() {
            const button = document.getElementById('toggleChartType');
            
            if (isBarChart) {
                categoryChart.config.type = 'doughnut';
                button.textContent = 'Switch to Bar Chart';
                isBarChart = false;
            } else {
                categoryChart.config.type = 'bar';
                button.textContent = 'Switch to Pie Chart';
                isBarChart = true;
                
                // Update options for bar chart
                categoryChart.options.scales = {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₹' + new Intl.NumberFormat('en-IN', {
                                    notation: 'compact'
                                }).format(value);
                            }
                        }
                    }
                };
            }
            
            categoryChart.update('active');
        }

        // Update sales trend chart
        function updateSalesTrendChart(data) {
            salesTrendChart.data.labels = data.labels;
            salesTrendChart.data.datasets[0].data = data.datasets[0].data;
            salesTrendChart.data.datasets[1].data = data.datasets[1].data;
            salesTrendChart.update('active');
        }

        // Show/hide chart loading
        function showChartLoading(show) {
            const loading = document.getElementById('chartLoading');
            const chart = document.getElementById('salesTrendChart');
            
            if (show) {
                loading.classList.remove('hidden');
                chart.style.opacity = '0.5';
            } else {
                loading.classList.add('hidden');
                chart.style.opacity = '1';
            }
        }

        // Update real-time metrics
        function updateRealTimeMetrics() {
            fetch(`{{ route('seller.analytics.getAnalyticsData') }}?type=overview&range=1`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateMetricCards(data.data);
                }
            })
            .catch(error => {
                console.error('Error updating metrics:', error);
            });
        }

        // Update metric cards
        function updateMetricCards(data) {
            // Update growth percentages with animation
            const revenueGrowth = document.getElementById('revenueGrowth');
            const orderGrowth = document.getElementById('orderGrowth');
            
            if (revenueGrowth && data.revenue && data.revenue.change !== undefined) {
                animateValue(revenueGrowth, parseFloat(revenueGrowth.textContent), data.revenue.change, '%');
            }
            
            if (orderGrowth && data.orders && data.orders.change !== undefined) {
                animateValue(orderGrowth, parseFloat(orderGrowth.textContent), data.orders.change, '%');
            }
        }

        // Animate value changes
        function animateValue(element, start, end, suffix = '') {
            const duration = 1000;
            const startTime = performance.now();
            
            function update(currentTime) {
                const elapsed = currentTime - startTime;
                const progress = Math.min(elapsed / duration, 1);
                
                const current = start + (end - start) * progress;
                const sign = current > 0 ? '+' : '';
                element.textContent = sign + current.toFixed(1) + suffix;
                
                if (progress < 1) {
                    requestAnimationFrame(update);
                }
            }
            
            requestAnimationFrame(update);
        }

        // Advanced Report Generation
        function generateAdvancedReport() {
            const reportType = document.getElementById('reportType').value;
            const reportFormat = document.getElementById('reportFormat').value;
            const startDate = document.getElementById('reportStartDate').value;
            const endDate = document.getElementById('reportEndDate').value;
            
            // Show loading state
            const generateBtn = document.getElementById('generateReport');
            const reportStatus = document.getElementById('reportStatus');
            const reportSuccess = document.getElementById('reportSuccess');
            
            generateBtn.disabled = true;
            generateBtn.innerHTML = '<div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>Generating...';
            reportStatus.classList.remove('hidden');
            reportSuccess.classList.add('hidden');
            
            // Make API request
            fetch(`{{ route('seller.analytics.generateReport') }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    type: reportType,
                    format: reportFormat,
                    start_date: startDate,
                    end_date: endDate
                })
            })
            .then(response => response.json())
            .then(data => {
                reportStatus.classList.add('hidden');
                
                if (data.success) {
                    reportSuccess.classList.remove('hidden');
                    document.getElementById('downloadLink').href = data.download_url;
                    document.getElementById('downloadLink').download = data.filename;
                } else {
                    alert('Error generating report: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                reportStatus.classList.add('hidden');
                alert('Error generating report. Please try again.');
            })
            .finally(() => {
                generateBtn.disabled = false;
                generateBtn.innerHTML = '<svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>Generate Report';
            });
        }

        // Show export options
        function showExportOptions() {
            const options = [
                { label: 'Export Sales Data (CSV)', url: '{{ route("seller.analytics.export") }}?type=sales&format=csv' },
                { label: 'Export Sales Data (Excel)', url: '{{ route("seller.analytics.export") }}?type=sales&format=excel' },
                { label: 'Export Chart as Image', action: 'exportChart' }
            ];
            
            // Create modal or dropdown for export options
            // For now, just redirect to CSV export
            window.location.href = options[0].url;
        }

        // Export chart as image
        function exportChart() {
            const canvas = document.getElementById('salesTrendChart');
            const url = canvas.toDataURL('image/png');
            const link = document.createElement('a');
            link.download = 'sales-chart-' + new Date().toISOString().split('T')[0] + '.png';
            link.href = url;
            link.click();
        }

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey || e.metaKey) {
                switch(e.key) {
                    case 'r':
                        e.preventDefault();
                        refreshChartData();
                        break;
                    case 'e':
                        e.preventDefault();
                        showExportOptions();
                        break;
                    case 'g':
                        e.preventDefault();
                        generateAdvancedReport();
                        break;
                }
            }
        });

        // Add tooltips for keyboard shortcuts
        document.addEventListener('DOMContentLoaded', function() {
            const refreshBtn = document.getElementById('refreshChart');
            const exportBtn = document.getElementById('exportBtn');
            const generateBtn = document.getElementById('generateReport');
            
            refreshBtn.title = 'Refresh Chart (Ctrl+R)';
            exportBtn.title = 'Export Data (Ctrl+E)';
            generateBtn.title = 'Generate Report (Ctrl+G)';
        });
    </script>
@endsection