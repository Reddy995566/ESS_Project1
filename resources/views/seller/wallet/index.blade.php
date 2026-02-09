@extends('seller.layouts.app')

@section('title', 'Wallet Management')

@section('content')
    <!-- Main Container -->
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-indigo-50 to-blue-50 p-6">
        
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="flex items-center justify-center w-14 h-14 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-black text-gray-900 tracking-tight">Wallet Management</h1>
                        <p class="mt-1 text-sm text-gray-600">Manage your earnings and withdrawals</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <button id="withdrawBtn" class="inline-flex items-center px-5 py-2.5 bg-white text-gray-700 text-sm font-semibold rounded-xl border-2 border-gray-200 hover:border-green-400 hover:bg-green-50 hover:text-green-700 transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Withdraw Funds
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

        @if (session('error'))
            <div class="mb-6 bg-gradient-to-r from-red-50 to-pink-50 border-l-4 border-red-500 p-4 rounded-xl shadow-md animate-slideIn">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-semibold text-red-800">{{ session('error') }}</p>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-red-600 hover:text-red-800">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            </div>
        @endif
        <!-- Wallet Balance Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Current Balance -->
            <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl shadow-xl p-6 transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center justify-center w-12 h-12 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <span class="px-3 py-1 bg-white bg-opacity-20 backdrop-blur-sm rounded-full text-xs font-bold text-white">Available</span>
                </div>
                <div class="text-white">
                    <p class="text-4xl font-black mb-1">₹{{ number_format($wallet->balance ?? 0, 2) }}</p>
                    <p class="text-green-100 text-sm font-medium">Current Balance</p>
                </div>
            </div>

            <!-- Total Earned -->
            <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl shadow-xl p-6 transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center justify-center w-12 h-12 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                    <span class="px-3 py-1 bg-white bg-opacity-20 backdrop-blur-sm rounded-full text-xs font-bold text-white">Earned</span>
                </div>
                <div class="text-white">
                    <p class="text-4xl font-black mb-1">₹{{ number_format($wallet->total_earned ?? 0, 2) }}</p>
                    <p class="text-blue-100 text-sm font-medium">Total Earned</p>
                </div>
            </div>

            <!-- Total Withdrawn -->
            <div class="bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl shadow-xl p-6 transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center justify-center w-12 h-12 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="px-3 py-1 bg-white bg-opacity-20 backdrop-blur-sm rounded-full text-xs font-bold text-white">Withdrawn</span>
                </div>
                <div class="text-white">
                    <p class="text-4xl font-black mb-1">₹{{ number_format($wallet->total_withdrawn ?? 0, 2) }}</p>
                    <p class="text-purple-100 text-sm font-medium">Total Withdrawn</p>
                </div>
            </div>

            <!-- Pending Balance -->
            <div class="bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl shadow-xl p-6 transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center justify-center w-12 h-12 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="px-3 py-1 bg-white bg-opacity-20 backdrop-blur-sm rounded-full text-xs font-bold text-white">Pending</span>
                </div>
                <div class="text-white">
                    <p class="text-4xl font-black mb-1">₹{{ number_format($wallet->pending_balance ?? 0, 2) }}</p>
                    <p class="text-orange-100 text-sm font-medium">Pending Balance</p>
                </div>
            </div>
        </div>

        <!-- Statistics Dashboard -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Total Credits -->
            <div class="bg-emerald-600 rounded-2xl shadow-xl p-6 transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center justify-center w-12 h-12 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </div>
                    <span class="px-3 py-1 bg-white bg-opacity-20 backdrop-blur-sm rounded-full text-xs font-bold text-white">Credits</span>
                </div>
                <div class="text-white">
                    <p class="text-4xl font-black mb-1">₹{{ number_format($totalCredits ?? 0, 0) }}</p>
                    <p class="text-emerald-100 text-sm font-medium">Total Credits</p>
                </div>
            </div>

            <!-- Total Debits -->
            <div class="bg-red-600 rounded-2xl shadow-xl p-6 transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center justify-center w-12 h-12 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                        </svg>
                    </div>
                    <span class="px-3 py-1 bg-white bg-opacity-20 backdrop-blur-sm rounded-full text-xs font-bold text-white">Debits</span>
                </div>
                <div class="text-white">
                    <p class="text-4xl font-black mb-1">₹{{ number_format($totalDebits ?? 0, 0) }}</p>
                    <p class="text-red-100 text-sm font-medium">Total Debits</p>
                </div>
            </div>

            <!-- Total Transactions -->
            <div class="bg-indigo-600 rounded-2xl shadow-xl p-6 transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center justify-center w-12 h-12 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <span class="px-3 py-1 bg-white bg-opacity-20 backdrop-blur-sm rounded-full text-xs font-bold text-white">Total</span>
                </div>
                <div class="text-white">
                    <p class="text-4xl font-black mb-1">{{ $totalTransactions ?? 0 }}</p>
                    <p class="text-indigo-100 text-sm font-medium">Total Transactions</p>
                </div>
            </div>

            <!-- Pending Transactions -->
            <div class="bg-yellow-600 rounded-2xl shadow-xl p-6 transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center justify-center w-12 h-12 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="px-3 py-1 bg-white bg-opacity-20 backdrop-blur-sm rounded-full text-xs font-bold text-white">Pending</span>
                </div>
                <div class="text-white">
                    <p class="text-4xl font-black mb-1">{{ $pendingTransactions ?? 0 }}</p>
                    <p class="text-yellow-100 text-sm font-medium">Pending Transactions</p>
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
                            <span class="flex items-center justify-center w-8 h-8 bg-green-100 text-green-600 rounded-lg mr-3">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zM14 6a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2h8zM4 14a2 2 0 002 2h8a2 2 0 002-2v-2H4v2z"/>
                                </svg>
                            </span>
                            Transaction History
                        </h2>
                        <p class="text-sm text-gray-600 mt-1 ml-11">Track all your wallet transactions and earnings</p>
                    </div>
                </div>
            </div>

            <!-- Advanced Filters Section -->
            <div class="bg-gray-50 px-6 py-5 border-b border-gray-200">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">Search</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}" class="block w-full pl-10 pr-3 py-3 border-2 border-gray-300 rounded-xl bg-white text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all" placeholder="Transaction ID, description...">
                        </div>
                    </div>

                    <!-- Type Filter -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">Transaction Type</label>
                        <select name="type" class="block w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <option value="">All Types</option>
                            <option value="credit" {{ request('type') == 'credit' ? 'selected' : '' }}>💰 Credit</option>
                            <option value="debit" {{ request('type') == 'debit' ? 'selected' : '' }}>💸 Debit</option>
                        </select>
                    </div>

                    <!-- Category Filter -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">Category</label>
                        <select name="category" class="block w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <option value="">All Categories</option>
                            <option value="order_commission" {{ request('category') == 'order_commission' ? 'selected' : '' }}>🛒 Order Commission</option>
                            <option value="withdrawal" {{ request('category') == 'withdrawal' ? 'selected' : '' }}>🏦 Withdrawal</option>
                            <option value="refund" {{ request('category') == 'refund' ? 'selected' : '' }}>↩️ Refund</option>
                            <option value="bonus" {{ request('category') == 'bonus' ? 'selected' : '' }}>🎁 Bonus</option>
                            <option value="penalty" {{ request('category') == 'penalty' ? 'selected' : '' }}>⚠️ Penalty</option>
                            <option value="adjustment" {{ request('category') == 'adjustment' ? 'selected' : '' }}>⚖️ Adjustment</option>
                        </select>
                    </div>

                    <!-- Action Buttons -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">Actions</label>
                        <div class="flex items-center space-x-2">
                            <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-xl font-semibold transition-all">
                                Filter
                            </button>
                            <a href="{{ route('seller.wallet.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-3 rounded-xl font-semibold transition-all">
                                Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Data Table -->
            <div class="overflow-x-auto">
                <table id="transactionsTable" class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-slate-100 to-gray-100">
                        <tr>
                            <th class="px-4 py-4 text-left min-w-[150px]">
                                <span class="text-xs font-black text-gray-700 uppercase">Transaction ID</span>
                            </th>
                            <th class="px-4 py-4 text-center w-32">
                                <span class="text-xs font-black text-gray-700 uppercase">Type</span>
                            </th>
                            <th class="px-4 py-4 text-center w-32">
                                <span class="text-xs font-black text-gray-700 uppercase">Category</span>
                            </th>
                            <th class="px-4 py-4 text-left min-w-[200px]">
                                <span class="text-xs font-black text-gray-700 uppercase">Description</span>
                            </th>
                            <th class="px-4 py-4 text-center w-32">
                                <span class="text-xs font-black text-gray-700 uppercase">Amount</span>
                            </th>
                            <th class="px-4 py-4 text-center w-32">
                                <span class="text-xs font-black text-gray-700 uppercase">Balance</span>
                            </th>
                            <th class="px-4 py-4 text-center w-32">
                                <span class="text-xs font-black text-gray-700 uppercase">Status</span>
                            </th>
                            <th class="px-4 py-4 text-center w-40">
                                <span class="text-xs font-black text-gray-700 uppercase">Date</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($transactions as $transaction)
                        <tr class="group hover:bg-gradient-to-r hover:from-green-50 hover:to-emerald-50 transition-all duration-200"
                            data-type="{{ $transaction->type }}"
                            data-category="{{ $transaction->category }}"
                            data-id="{{ $transaction->id }}">

                            <!-- Transaction ID -->
                            <td class="px-4 py-4">
                                <p class="text-sm font-bold text-gray-900">{{ $transaction->transaction_id }}</p>
                                @if($transaction->order_id)
                                    <p class="text-xs text-gray-500">Order #{{ $transaction->order->order_number ?? $transaction->order_id }}</p>
                                @endif
                            </td>

                            <!-- Type -->
                            <td class="px-4 py-4 text-center">
                                @if($transaction->type === 'credit')
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-bold bg-gradient-to-r from-green-100 to-emerald-100 text-green-700 border border-green-300">
                                        💰 Credit
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-bold bg-gradient-to-r from-red-100 to-pink-100 text-red-700 border border-red-300">
                                        💸 Debit
                                    </span>
                                @endif
                            </td>

                            <!-- Category -->
                            <td class="px-4 py-4 text-center">
                                @php
                                    $categoryData = [
                                        'order_commission' => ['icon' => '🛒', 'color' => 'from-blue-100 to-indigo-100 text-blue-700 border-blue-300'],
                                        'withdrawal' => ['icon' => '🏦', 'color' => 'from-purple-100 to-pink-100 text-purple-700 border-purple-300'],
                                        'refund' => ['icon' => '↩️', 'color' => 'from-yellow-100 to-amber-100 text-yellow-700 border-yellow-300'],
                                        'bonus' => ['icon' => '🎁', 'color' => 'from-green-100 to-emerald-100 text-green-700 border-green-300'],
                                        'penalty' => ['icon' => '⚠️', 'color' => 'from-red-100 to-pink-100 text-red-700 border-red-300'],
                                        'adjustment' => ['icon' => '⚖️', 'color' => 'from-gray-100 to-gray-200 text-gray-700 border-gray-300'],
                                    ];
                                    $catData = $categoryData[$transaction->category] ?? ['icon' => '💳', 'color' => 'from-gray-100 to-gray-200 text-gray-700 border-gray-300'];
                                @endphp
                                <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold bg-gradient-to-r {{ $catData['color'] }} border">
                                    {{ $catData['icon'] }} {{ ucfirst(str_replace('_', ' ', $transaction->category)) }}
                                </span>
                            </td>

                            <!-- Description -->
                            <td class="px-4 py-4">
                                <p class="text-sm text-gray-900">{{ $transaction->description }}</p>
                            </td>

                            <!-- Amount -->
                            <td class="px-4 py-4 text-center">
                                <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-bold {{ $transaction->type === 'credit' ? 'bg-gradient-to-r from-green-100 to-emerald-100 text-green-700 border border-green-300' : 'bg-gradient-to-r from-red-100 to-pink-100 text-red-700 border border-red-300' }}">
                                    {{ $transaction->type === 'credit' ? '+' : '-' }}₹{{ number_format($transaction->amount, 2) }}
                                </span>
                            </td>

                            <!-- Balance After -->
                            <td class="px-4 py-4 text-center">
                                <span class="text-sm font-medium text-gray-900">₹{{ number_format($transaction->balance_after, 2) }}</span>
                            </td>

                            <!-- Status -->
                            <td class="px-4 py-4 text-center">
                                @php
                                    $statusColors = [
                                        'pending' => 'from-yellow-100 to-amber-100 text-yellow-700 border-yellow-300',
                                        'completed' => 'from-green-100 to-emerald-100 text-green-700 border-green-300',
                                        'failed' => 'from-red-100 to-pink-100 text-red-700 border-red-300',
                                        'cancelled' => 'from-gray-100 to-gray-200 text-gray-700 border-gray-300',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold bg-gradient-to-r {{ $statusColors[$transaction->status] ?? 'from-gray-100 to-gray-200 text-gray-700 border-gray-300' }} border">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </td>

                            <!-- Date -->
                            <td class="px-4 py-4 text-center">
                                <p class="text-sm font-medium text-gray-900">{{ $transaction->created_at->format('d M, Y') }}</p>
                                <p class="text-xs text-gray-500">{{ $transaction->created_at->format('h:i A') }}</p>
                            </td>
                        </tr>
                        @empty
                        <tr id="emptyState">
                            <td colspan="8" class="px-6 py-20">
                                <div class="text-center">
                                    <div class="mx-auto flex items-center justify-center w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full mb-6">
                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">No Transactions Found</h3>
                                    <p class="text-gray-500 mb-6 max-w-md mx-auto">You don't have any wallet transactions yet. When you receive payments or make withdrawals, they'll appear here.</p>
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
                        Showing <span class="font-bold text-green-600">{{ $transactions->firstItem() ?? 0 }}</span> to <span class="font-bold text-green-600">{{ $transactions->lastItem() ?? 0 }}</span> of <span class="font-bold text-green-600">{{ $transactions->total() }}</span> entries
                    </div>
                    <div class="dataTables_paginate">
                        {{ $transactions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Withdrawal Modal -->
    <div id="withdrawalModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-2xl bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Withdraw Funds</h3>
                    <button id="closeModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <form action="{{ route('seller.wallet.withdraw') }}" method="POST" id="withdrawalForm">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Withdrawal Amount</label>
                            <div class="relative">
                                <span class="absolute left-3 top-3 text-gray-500">₹</span>
                                <input type="number" name="amount" min="100" max="{{ $wallet->balance ?? 0 }}" step="0.01" required 
                                    class="w-full pl-8 pr-3 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                                    placeholder="Enter amount">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Minimum: ₹100, Available: ₹{{ number_format($wallet->balance ?? 0, 2) }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Bank Account Number</label>
                            <input type="text" name="bank_account" required 
                                class="w-full px-3 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                                placeholder="Enter bank account number">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">IFSC Code</label>
                            <input type="text" name="ifsc_code" required 
                                class="w-full px-3 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                                placeholder="Enter IFSC code">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Account Holder Name</label>
                            <input type="text" name="account_holder" required 
                                class="w-full px-3 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                                placeholder="Enter account holder name">
                        </div>
                    </div>

                    <div class="flex items-center justify-end space-x-3 mt-6">
                        <button type="button" id="cancelBtn" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-xl hover:bg-gray-300 transition-all">
                            Cancel
                        </button>
                        <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-all">
                            Submit Withdrawal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const withdrawBtn = document.getElementById('withdrawBtn');
    const withdrawalModal = document.getElementById('withdrawalModal');
    const closeModal = document.getElementById('closeModal');
    const cancelBtn = document.getElementById('cancelBtn');
    const refreshBtn = document.getElementById('refreshBtn');

    // Open withdrawal modal
    withdrawBtn?.addEventListener('click', function() {
        withdrawalModal.classList.remove('hidden');
    });

    // Close modal functions
    function closeWithdrawalModal() {
        withdrawalModal.classList.add('hidden');
    }

    closeModal?.addEventListener('click', closeWithdrawalModal);
    cancelBtn?.addEventListener('click', closeWithdrawalModal);

    // Close modal when clicking outside
    withdrawalModal?.addEventListener('click', function(e) {
        if (e.target === withdrawalModal) {
            closeWithdrawalModal();
        }
    });

    // Refresh functionality
    refreshBtn?.addEventListener('click', function() {
        window.location.reload();
    });

    // Form validation
    const withdrawalForm = document.getElementById('withdrawalForm');
    withdrawalForm?.addEventListener('submit', function(e) {
        const amount = parseFloat(document.querySelector('input[name="amount"]').value);
        const maxAmount = {{ $wallet->balance ?? 0 }};
        
        if (amount < 100) {
            e.preventDefault();
            alert('Minimum withdrawal amount is ₹100');
            return;
        }
        
        if (amount > maxAmount) {
            e.preventDefault();
            alert('Insufficient balance for withdrawal');
            return;
        }
    });
});
</script>
@endpush