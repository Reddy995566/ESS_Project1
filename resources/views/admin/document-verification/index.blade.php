@extends('admin.layouts.app')

@section('title', 'Document Verification')

@section('content')
<!-- Main Container -->
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-indigo-50 p-6">
    
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="flex items-center space-x-4">
                <div class="flex items-center justify-center w-14 h-14 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-black text-gray-900 tracking-tight">Document Verification</h1>
                    <p class="mt-1 text-sm text-gray-600">Review and verify seller documents</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <button id="bulkApproveBtn" class="inline-flex items-center px-5 py-2.5 bg-green-600 text-white text-sm font-semibold rounded-xl hover:bg-green-700 transition-all duration-200 shadow-sm hover:shadow-md" style="display: none;">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Approve Selected
                </button>
                <button id="bulkRejectBtn" class="inline-flex items-center px-5 py-2.5 bg-red-600 text-white text-sm font-semibold rounded-xl hover:bg-red-700 transition-all duration-200 shadow-sm hover:shadow-md" style="display: none;">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Reject Selected
                </button>
                <button id="refreshBtn" class="inline-flex items-center justify-center w-11 h-11 bg-white text-gray-700 rounded-xl border-2 border-gray-200 hover:border-blue-400 hover:bg-blue-50 hover:text-blue-700 transition-all duration-200 shadow-sm hover:shadow-md">
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
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Documents -->
        <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl shadow-xl p-6 transform hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center justify-center w-12 h-12 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <span class="px-3 py-1 bg-white bg-opacity-20 backdrop-blur-sm rounded-full text-xs font-bold text-white">Total</span>
            </div>
            <div class="text-white">
                <p class="text-4xl font-black mb-1">{{ number_format($stats['total']) }}</p>
                <p class="text-blue-100 text-sm font-medium">Total Documents</p>
            </div>
        </div>

        <!-- Pending Review -->
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
                <p class="text-4xl font-black mb-1">{{ number_format($stats['pending']) }}</p>
                <p class="text-amber-100 text-sm font-medium">Pending Review</p>
            </div>
        </div>

        <!-- Approved -->
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
                <p class="text-4xl font-black mb-1">{{ number_format($stats['approved']) }}</p>
                <p class="text-emerald-100 text-sm font-medium">Approved</p>
            </div>
        </div>

        <!-- Rejected -->
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
                <p class="text-4xl font-black mb-1">{{ number_format($stats['rejected']) }}</p>
                <p class="text-red-100 text-sm font-medium">Rejected</p>
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
                        <span class="flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-600 rounded-lg mr-3">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                            </svg>
                        </span>
                        Documents Data Table
                    </h2>
                    <p class="text-sm text-gray-600 mt-1 ml-11">Review and verify seller documents with advanced filtering</p>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="text-sm font-medium text-gray-600">Quick Actions:</span>
                    <select id="bulkAction" class="px-3 py-2 bg-white border-2 border-gray-300 rounded-lg text-sm font-medium focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Action</option>
                        <option value="approve">Approve Selected</option>
                        <option value="reject" class="text-red-600">Reject Selected</option>
                    </select>
                    <button id="applyBulkAction" disabled class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-all">
                        Apply
                    </button>
                </div>
            </div>
        </div>

        <!-- Advanced Filters Section -->
        <div class="bg-gray-50 px-6 py-5 border-b border-gray-200">
            <form method="GET" action="{{ route('admin.document-verification.index') }}" id="filtersForm">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    <!-- Status Filter -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">Status</label>
                        <select name="status" id="statusFilter" class="block w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>✅ Approved</option>
                            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>❌ Rejected</option>
                        </select>
                    </div>

                    <!-- Document Type Filter -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">Document Type</label>
                        <select name="document_type" id="documentTypeFilter" class="block w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Types</option>
                            @foreach(\App\Models\SellerDocument::DOCUMENT_TYPES as $key => $name)
                                <option value="{{ $key }}" {{ request('document_type') === $key ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Search -->
                    <div class="lg:col-span-2">
                        <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">Search Seller</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input type="text" name="search" id="globalSearch" class="block w-full pl-10 pr-3 py-3 border-2 border-gray-300 rounded-xl bg-white text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" placeholder="Business name or contact person..." value="{{ request('search') }}">
                        </div>
                    </div>

                    <!-- Items Per Page -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">Show Entries</label>
                        <select name="per_page" id="itemsPerPage" class="block w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 per page</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 per page</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 per page</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 per page</option>
                        </select>
                    </div>
                </div>

                <!-- Additional Filters Row -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-4">
                    <button type="submit" class="px-4 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-bold rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all flex items-center justify-center">
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
                    <div class="flex items-center h-12 px-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-xl col-span-2">
                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span id="selectedCount" class="text-2xl font-black text-blue-700">0</span>
                        <span class="ml-2 text-sm font-semibold text-blue-600">documents selected</span>
                    </div>
                </div>
            </form>
        </div>
        <!-- Data Table - Desktop View -->
        <div class="hidden lg:block overflow-x-auto">
            <table id="documentsTable" class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-slate-100 to-gray-100">
                    <tr>
                        <th class="px-4 py-4 text-center w-12">
                            <input type="checkbox" id="selectAll" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500">
                        </th>
                        <th class="px-4 py-4 text-left min-w-[200px]">
                            <span class="text-xs font-black text-gray-700 uppercase">Seller</span>
                        </th>
                        <th class="px-4 py-4 text-center w-32">
                            <span class="text-xs font-black text-gray-700 uppercase">Document Type</span>
                        </th>
                        <th class="px-4 py-4 text-left min-w-[200px]">
                            <span class="text-xs font-black text-gray-700 uppercase">Document Name</span>
                        </th>
                        <th class="px-4 py-4 text-center w-28">
                            <span class="text-xs font-black text-gray-700 uppercase">Status</span>
                        </th>
                        <th class="px-4 py-4 text-center w-32">
                            <span class="text-xs font-black text-gray-700 uppercase">Uploaded</span>
                        </th>
                        <th class="px-4 py-4 text-center w-32">
                            <span class="text-xs font-black text-gray-700 uppercase">Verified By</span>
                        </th>
                        <th class="px-4 py-4 text-center w-32">
                            <span class="text-xs font-black text-gray-700 uppercase">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($documents as $document)
                    <tr class="group hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all duration-200" 
                        data-id="{{ $document->id }}" 
                        data-status="{{ $document->verification_status }}">
                        
                        <!-- Checkbox -->
                        <td class="px-4 py-4 text-center">
                            @if($document->verification_status === 'pending')
                                <input type="checkbox" class="row-select w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500" value="{{ $document->id }}">
                            @endif
                        </td>

                        <!-- Seller Info -->
                        <td class="px-4 py-4">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0 w-12 h-12">
                                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center border-2 border-blue-200">
                                        <span class="text-sm font-bold text-blue-700">{{ substr($document->seller->business_name, 0, 1) }}</span>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-sm font-bold text-gray-900 mb-1">{{ $document->seller->business_name }}</h3>
                                    <p class="text-xs text-gray-500">{{ $document->seller->contact_person }}</p>
                                </div>
                            </div>
                        </td>

                        <!-- Document Type -->
                        <td class="px-4 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-gradient-to-r from-blue-100 to-cyan-100 text-blue-700 border border-blue-300">
                                {{ $document->document_type_name }}
                            </span>
                        </td>

                        <!-- Document Name -->
                        <td class="px-4 py-4">
                            <div>
                                <h4 class="text-sm font-bold text-gray-900 mb-1">{{ $document->document_name }}</h4>
                                <p class="text-xs text-gray-500">{{ $document->file_size_formatted }}</p>
                            </div>
                        </td>

                        <!-- Status -->
                        <td class="px-4 py-4 text-center">
                            @php
                                $statusConfig = [
                                    'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'border' => 'border-yellow-300', 'icon' => '⏳', 'label' => 'Pending'],
                                    'approved' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'border' => 'border-green-300', 'icon' => '✅', 'label' => 'Approved'],
                                    'rejected' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'border' => 'border-red-300', 'icon' => '❌', 'label' => 'Rejected']
                                ];
                                $config = $statusConfig[$document->verification_status] ?? $statusConfig['pending'];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold {{ $config['bg'] }} {{ $config['text'] }} border {{ $config['border'] }}">
                                {{ $config['icon'] }} {{ $config['label'] }}
                            </span>
                        </td>

                        <!-- Uploaded -->
                        <td class="px-4 py-4 text-center">
                            <div class="text-sm text-gray-900">{{ $document->created_at->format('M d, Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $document->created_at->format('H:i') }}</div>
                        </td>

                        <!-- Verified By -->
                        <td class="px-4 py-4 text-center">
                            @if($document->verifiedBy)
                                <div class="text-sm font-medium text-gray-900">{{ $document->verifiedBy->name }}</div>
                                <div class="text-xs text-gray-500">{{ $document->verified_at->format('M d, Y') }}</div>
                            @else
                                <span class="text-xs text-gray-500">Not verified</span>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="px-4 py-4">
                            <div class="flex items-center justify-center space-x-2">
                                <button class="view-btn inline-flex items-center justify-center w-9 h-9 text-blue-600 bg-blue-50 border-2 border-blue-200 rounded-lg hover:bg-blue-100 hover:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all"
                                        title="View Document"
                                        data-id="{{ $document->id }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                                @if($document->verification_status === 'pending')
                                    <button class="approve-btn inline-flex items-center justify-center w-9 h-9 text-green-600 bg-green-50 border-2 border-green-200 rounded-lg hover:bg-green-100 hover:border-green-400 focus:outline-none focus:ring-2 focus:ring-green-500 transition-all"
                                            title="Approve Document"
                                            data-id="{{ $document->id }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </button>
                                    <button class="reject-btn inline-flex items-center justify-center w-9 h-9 text-red-600 bg-red-50 border-2 border-red-200 rounded-lg hover:bg-red-100 hover:border-red-400 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all"
                                            title="Reject Document"
                                            data-id="{{ $document->id }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-500">
                                <svg class="w-16 h-16 mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 mb-1">No documents found</h3>
                                <p class="text-sm text-gray-500">No documents match your current filters.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($documents->hasPages())
            <div class="bg-white px-6 py-4 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Showing {{ $documents->firstItem() }} to {{ $documents->lastItem() }} of {{ $documents->total() }} results
                    </div>
                    <div class="flex items-center space-x-2">
                        {{ $documents->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
<!-- Approve Modal -->
<div id="approveModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-900">Approve Document</h3>
        </div>
        <form id="approveForm">
            <div class="px-6 py-4">
                <div class="mb-4">
                    <label for="approveNotes" class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                    <textarea id="approveNotes" name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" placeholder="Add any notes about the approval..."></textarea>
                </div>
            </div>
            <div class="px-6 py-4 bg-gray-50 rounded-b-2xl flex justify-end space-x-3">
                <button type="button" id="cancelApprove" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">Approve Document</button>
            </div>
        </form>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-900">Reject Document</h3>
        </div>
        <form id="rejectForm">
            <div class="px-6 py-4">
                <div class="mb-4">
                    <label for="rejectionReason" class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason <span class="text-red-500">*</span></label>
                    <textarea id="rejectionReason" name="rejection_reason" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Please provide a clear reason for rejection..." required></textarea>
                </div>
            </div>
            <div class="px-6 py-4 bg-gray-50 rounded-b-2xl flex justify-end space-x-3">
                <button type="button" id="cancelReject" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">Reject Document</button>
            </div>
        </form>
    </div>
</div>

<!-- Bulk Reject Modal -->
<div id="bulkRejectModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-900">Bulk Reject Documents</h3>
        </div>
        <form id="bulkRejectForm">
            <div class="px-6 py-4">
                <div class="mb-4">
                    <label for="bulkRejectionReason" class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason <span class="text-red-500">*</span></label>
                    <textarea id="bulkRejectionReason" name="rejection_reason" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Please provide a clear reason for rejection..." required></textarea>
                </div>
            </div>
            <div class="px-6 py-4 bg-gray-50 rounded-b-2xl flex justify-end space-x-3">
                <button type="button" id="cancelBulkReject" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">Reject Selected Documents</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
/**
 * Document Verification Management Script
 * Enterprise-level JavaScript functionality for document verification
 */

let currentDocumentId = null;

document.addEventListener('DOMContentLoaded', function() {
    // Initialize components
    initializeSelectionControls();
    initializeBulkActions();
    initializeActionButtons();
    initializeFilters();
    initializeModals();
});

// Selection Controls
function initializeSelectionControls() {
    const selectAll = document.getElementById('selectAll');
    const rowSelects = document.querySelectorAll('.row-select');
    const selectedCount = document.getElementById('selectedCount');
    const bulkActionSelect = document.getElementById('bulkAction');
    const applyBulkActionBtn = document.getElementById('applyBulkAction');

    // Select all functionality
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            const isChecked = this.checked;
            rowSelects.forEach(checkbox => {
                checkbox.checked = isChecked;
            });
            updateSelectionCount();
        });
    }

    // Individual row selection
    rowSelects.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectionCount();
            
            // Update select all state
            const checkedBoxes = document.querySelectorAll('.row-select:checked').length;
            selectAll.checked = checkedBoxes === rowSelects.length && checkedBoxes > 0;
            selectAll.indeterminate = checkedBoxes > 0 && checkedBoxes < rowSelects.length;
        });
    });

    function updateSelectionCount() {
        const checkedBoxes = document.querySelectorAll('.row-select:checked');
        selectedCount.textContent = checkedBoxes.length;
        
        // Enable/disable bulk actions
        const hasSelection = checkedBoxes.length > 0;
        applyBulkActionBtn.disabled = !hasSelection || !bulkActionSelect.value;
        
        // Show/hide bulk action buttons
        const bulkApproveBtn = document.getElementById('bulkApproveBtn');
        const bulkRejectBtn = document.getElementById('bulkRejectBtn');
        
        if (hasSelection) {
            bulkApproveBtn.style.display = 'inline-flex';
            bulkRejectBtn.style.display = 'inline-flex';
        } else {
            bulkApproveBtn.style.display = 'none';
            bulkRejectBtn.style.display = 'none';
            bulkActionSelect.value = '';
        }
    }

    // Enable/disable apply button based on action selection
    if (bulkActionSelect) {
        bulkActionSelect.addEventListener('change', function() {
            const checkedBoxes = document.querySelectorAll('.row-select:checked');
            applyBulkActionBtn.disabled = !this.value || checkedBoxes.length === 0;
        });
    }
}

// Bulk Actions
function initializeBulkActions() {
    const applyBulkActionBtn = document.getElementById('applyBulkAction');
    const bulkApproveBtn = document.getElementById('bulkApproveBtn');
    const bulkRejectBtn = document.getElementById('bulkRejectBtn');
    
    if (applyBulkActionBtn) {
        applyBulkActionBtn.addEventListener('click', function() {
            const action = document.getElementById('bulkAction').value;
            const selectedIds = Array.from(document.querySelectorAll('.row-select:checked'))
                .map(cb => cb.value);
            
            if (!action || selectedIds.length === 0) {
                showToast('Please select an action and at least one document.', 'error');
                return;
            }

            if (action === 'approve') {
                performBulkAction('approve', selectedIds);
            } else if (action === 'reject') {
                showBulkRejectModal();
            }
        });
    }

    if (bulkApproveBtn) {
        bulkApproveBtn.addEventListener('click', function() {
            const selectedIds = Array.from(document.querySelectorAll('.row-select:checked'))
                .map(cb => cb.value);
            
            if (selectedIds.length === 0) {
                showToast('Please select documents to approve.', 'error');
                return;
            }
            
            if (confirm(`Are you sure you want to approve ${selectedIds.length} document(s)?`)) {
                performBulkAction('approve', selectedIds);
            }
        });
    }

    if (bulkRejectBtn) {
        bulkRejectBtn.addEventListener('click', function() {
            const selectedIds = Array.from(document.querySelectorAll('.row-select:checked'))
                .map(cb => cb.value);
            
            if (selectedIds.length === 0) {
                showToast('Please select documents to reject.', 'error');
                return;
            }
            
            showBulkRejectModal();
        });
    }
}
// Action Buttons
function initializeActionButtons() {
    // View buttons
    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const documentId = this.dataset.id;
            window.open(`/admin/document-verification/${documentId}`, '_blank');
        });
    });

    // Approve buttons
    document.querySelectorAll('.approve-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const documentId = this.dataset.id;
            showApproveModal(documentId);
        });
    });

    // Reject buttons
    document.querySelectorAll('.reject-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const documentId = this.dataset.id;
            showRejectModal(documentId);
        });
    });
}

// Filters
function initializeFilters() {
    const globalSearch = document.getElementById('globalSearch');
    const statusFilter = document.getElementById('statusFilter');
    const documentTypeFilter = document.getElementById('documentTypeFilter');
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

    // Auto-submit filters on change
    statusFilter?.addEventListener('change', () => document.getElementById('filtersForm').submit());
    documentTypeFilter?.addEventListener('change', () => document.getElementById('filtersForm').submit());
    itemsPerPage?.addEventListener('change', () => document.getElementById('filtersForm').submit());

    // Reset Logic
    resetFiltersBtn?.addEventListener('click', function() {
        window.location.href = '{{ route("admin.document-verification.index") }}';
    });

    // Refresh Button
    refreshBtn?.addEventListener('click', function() {
        window.location.reload();
    });
}

// Modals
function initializeModals() {
    // Approve modal
    const approveModal = document.getElementById('approveModal');
    const cancelApprove = document.getElementById('cancelApprove');
    const approveForm = document.getElementById('approveForm');

    cancelApprove?.addEventListener('click', () => hideModal('approveModal'));
    approveForm?.addEventListener('submit', handleApproveSubmit);

    // Reject modal
    const rejectModal = document.getElementById('rejectModal');
    const cancelReject = document.getElementById('cancelReject');
    const rejectForm = document.getElementById('rejectForm');

    cancelReject?.addEventListener('click', () => hideModal('rejectModal'));
    rejectForm?.addEventListener('submit', handleRejectSubmit);

    // Bulk reject modal
    const bulkRejectModal = document.getElementById('bulkRejectModal');
    const cancelBulkReject = document.getElementById('cancelBulkReject');
    const bulkRejectForm = document.getElementById('bulkRejectForm');

    cancelBulkReject?.addEventListener('click', () => hideModal('bulkRejectModal'));
    bulkRejectForm?.addEventListener('submit', handleBulkRejectSubmit);

    // Close modals on outside click
    [approveModal, rejectModal, bulkRejectModal].forEach(modal => {
        modal?.addEventListener('click', function(e) {
            if (e.target === this) {
                hideModal(this.id);
            }
        });
    });

    // ESC key handler
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            hideModal('approveModal');
            hideModal('rejectModal');
            hideModal('bulkRejectModal');
        }
    });
}

// Modal Functions
function showModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
}

function hideModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
        
        // Reset forms
        const form = modal.querySelector('form');
        if (form) form.reset();
    }
}

function showApproveModal(documentId) {
    currentDocumentId = documentId;
    showModal('approveModal');
}

function showRejectModal(documentId) {
    currentDocumentId = documentId;
    showModal('rejectModal');
}

function showBulkRejectModal() {
    showModal('bulkRejectModal');
}

// Form Handlers
async function handleApproveSubmit(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    
    try {
        const response = await fetch(`/admin/document-verification/${currentDocumentId}/approve`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            showToast(data.message, 'success');
            hideModal('approveModal');
            setTimeout(() => location.reload(), 1500);
        } else {
            showToast(data.message, 'error');
        }
    } catch (error) {
        showToast('An error occurred. Please try again.', 'error');
    }
}

async function handleRejectSubmit(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    
    try {
        const response = await fetch(`/admin/document-verification/${currentDocumentId}/reject`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            showToast(data.message, 'success');
            hideModal('rejectModal');
            setTimeout(() => location.reload(), 1500);
        } else {
            showToast(data.message, 'error');
        }
    } catch (error) {
        showToast('An error occurred. Please try again.', 'error');
    }
}

async function handleBulkRejectSubmit(e) {
    e.preventDefault();
    
    const selectedIds = Array.from(document.querySelectorAll('.row-select:checked'))
        .map(cb => cb.value);
    const rejectionReason = document.getElementById('bulkRejectionReason').value;
    
    performBulkAction('reject', selectedIds, rejectionReason);
    hideModal('bulkRejectModal');
}

// Bulk Action Handler
async function performBulkAction(action, documentIds, rejectionReason = null) {
    showLoading('Processing documents...');
    
    try {
        const formData = new FormData();
        formData.append('action', action);
        formData.append('document_ids', JSON.stringify(documentIds));
        if (rejectionReason) {
            formData.append('rejection_reason', rejectionReason);
        }
        
        const response = await fetch('/admin/document-verification/bulk-action', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            showToast(data.message, 'success');
            setTimeout(() => location.reload(), 1500);
        } else {
            showToast(data.message, 'error');
        }
    } catch (error) {
        showToast('An error occurred. Please try again.', 'error');
    } finally {
        hideLoading();
    }
}

// Utility Functions
function showLoading(message = 'Loading...') {
    let overlay = document.getElementById('loadingOverlay');
    if (!overlay) {
        overlay = document.createElement('div');
        overlay.id = 'loadingOverlay';
        overlay.className = 'fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50';
        overlay.innerHTML = `
            <div class="bg-white rounded-lg p-6 flex items-center space-x-4 shadow-xl">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
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
</script>
@endpush
@endsection