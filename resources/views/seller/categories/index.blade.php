@extends('seller.layouts.app')

@section('title', 'Categories Management')

@section('content')
    <!-- Main Container -->
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-indigo-50 p-6">
        
        <!-- Page Header -->
        <div class="mb-8">
            <!-- Header Section -->
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="flex items-center justify-center w-14 h-14 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-black text-gray-900 tracking-tight">Categories Management</h1>
                        <p class="mt-1 text-sm text-gray-600">Organize and manage your product categories with advanced controls</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <button id="exportBtn" class="inline-flex items-center px-5 py-2.5 bg-white text-gray-700 text-sm font-semibold rounded-xl border-2 border-gray-200 hover:border-green-400 hover:bg-green-50 hover:text-green-700 transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Export
                    </button>
                    <button id="refreshBtn" class="inline-flex items-center justify-center w-11 h-11 bg-white text-gray-700 rounded-xl border-2 border-gray-200 hover:border-blue-400 hover:bg-blue-50 hover:text-blue-700 transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </button>
                    <button onclick="openAddModal()" class="inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-bold rounded-xl hover:from-blue-700 hover:to-indigo-700 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add Category
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
            <!-- Total Categories -->
            <div class="bg-blue-600 rounded-2xl shadow-xl p-6 transform hover:scale-105 transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center justify-center w-12 h-12 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                        </div>
                        <span class="px-3 py-1 bg-white bg-opacity-20 backdrop-blur-sm rounded-full text-xs font-bold text-white">Total</span>
                    </div>
                <div class="text-white">
                    <p class="text-4xl font-black mb-1">{{ $totalCategories }}</p>
                    <p class="text-blue-100 text-sm font-medium">Total Categories</p>
                </div>
            </div>

            <!-- Active Categories -->
            <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl shadow-xl p-6 transform hover:scale-105 transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center justify-center w-12 h-12 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="px-3 py-1 bg-white bg-opacity-20 backdrop-blur-sm rounded-full text-xs font-bold text-white">Live</span>
                    </div>
                    <div class="text-white">
                        <p class="text-4xl font-black mb-1">{{ $activeCategories }}</p>
                        <p class="text-green-100 text-sm font-medium">Active Categories</p>
                    </div>
            </div>

            <!-- Featured Categories -->
            <div class="bg-gradient-to-br from-yellow-400 to-orange-500 rounded-2xl shadow-xl p-6 transform hover:scale-105 transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center justify-center w-12 h-12 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </div>
                        <span class="px-3 py-1 bg-white bg-opacity-20 backdrop-blur-sm rounded-full text-xs font-bold text-white">Featured</span>
                    </div>
                    <div class="text-white">
                        <p class="text-4xl font-black mb-1">{{ $featuredCategories }}</p>
                        <p class="text-yellow-100 text-sm font-medium">Featured Items</p>
                    </div>
            </div>

            <!-- Parent Categories -->
            <div class="bg-indigo-600 rounded-2xl shadow-xl p-6 transform hover:scale-105 transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center justify-center w-12 h-12 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                            </svg>
                        </div>
                        <span class="px-3 py-1 bg-white bg-opacity-20 backdrop-blur-sm rounded-full text-xs font-bold text-white">Parent</span>
                    </div>
                <div class="text-white">
                    <p class="text-4xl font-black mb-1">{{ $parentCategoriesCount }}</p>
                    <p class="text-indigo-100 text-sm font-medium">Parent Categories</p>
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
                            Categories Data Table
                        </h2>
                        <p class="text-sm text-gray-600 mt-1 ml-11">Comprehensive view with advanced filtering and bulk actions</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm font-medium text-gray-600">Quick Actions:</span>
                        <select id="bulkAction" class="px-3 py-2 bg-white border-2 border-gray-300 rounded-lg text-sm font-medium focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Action</option>
                            <option value="activate">Activate</option>
                            <option value="deactivate">Deactivate</option>
                            <option value="feature">Mark Featured</option>
                            <option value="unfeature">Remove Featured</option>
                            <option value="delete" class="text-red-600">Delete Selected</option>
                        </select>
                        <button id="applyBulkAction" disabled class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-all">
                            Apply
                        </button>
                    </div>
                </div>
            </div>

            <!-- Advanced Filters Section -->
            <div class="bg-gray-50 px-6 py-5 border-b border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div class="lg:col-span-2">
                        <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">Search Categories</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input type="text" id="globalSearch" class="block w-full pl-10 pr-3 py-3 border-2 border-gray-300 rounded-xl bg-white text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" placeholder="Search by name, slug, or description...">
                        </div>
                    </div>

                    <!-- Items Per Page -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">Show Entries</label>
                        <select id="itemsPerPage" class="block w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="10">10 per page</option>
                            <option value="25" selected>25 per page</option>
                            <option value="50">50 per page</option>
                            <option value="100">100 per page</option>
                            <option value="-1">Show All</option>
                        </select>
                    </div>

                    <!-- Selected Count -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">Selected Items</label>
                        <div class="flex items-center h-12 px-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-xl">
                            <svg class="w-5 h-5 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span id="selectedCount" class="text-2xl font-black text-blue-700">0</span>
                            <span class="ml-2 text-sm font-semibold text-blue-600">items</span>
                        </div>
                    </div>
                </div>

                <!-- Additional Filters Row -->
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 mt-4">
                    <select id="statusFilter" class="px-3 py-2.5 bg-white border-2 border-gray-300 rounded-lg text-sm font-medium focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Status</option>
                        <option value="1">✓ Active</option>
                        <option value="0">✗ Inactive</option>
                    </select>

                    <select id="featuredFilter" class="px-3 py-2.5 bg-white border-2 border-gray-300 rounded-lg text-sm font-medium focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Featured Filter</option>
                        <option value="1">⭐ Featured</option>
                        <option value="0">Regular</option>
                    </select>

                    <select id="parentFilter" class="px-3 py-2.5 bg-white border-2 border-gray-300 rounded-lg text-sm font-medium focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Types</option>
                        <option value="null">📁 Parent Only</option>
                        @foreach(App\Models\Category::whereNull('parent_id')->orderBy('name')->get() as $parent)
                            <option value="{{ $parent->id }}">↳ {{ $parent->name }}</option>
                        @endforeach
                    </select>

                    <select id="navbarFilter" class="px-3 py-2.5 bg-white border-2 border-gray-300 rounded-lg text-sm font-medium focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Navbar</option>
                        <option value="1">🔗 In Navbar</option>
                        <option value="0">Hidden</option>
                    </select>

                    <select id="homepageFilter" class="px-3 py-2.5 bg-white border-2 border-gray-300 rounded-lg text-sm font-medium focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Homepage</option>
                        <option value="1">🏠 On Homepage</option>
                        <option value="0">Hidden</option>
                    </select>

                    <button id="resetFilters" class="px-3 py-2.5 bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 text-sm font-bold rounded-lg hover:from-gray-200 hover:to-gray-300 transition-all flex items-center justify-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Reset
                    </button>
                </div>
            </div>

            <!-- Data Table -->
            <div class="overflow-x-auto">
                <table id="categoriesTable" class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-slate-100 to-gray-100">
                        <tr>
                            <th class="px-4 py-4 text-center w-12">
                                <input type="checkbox" id="selectAll" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500">
                            </th>
                            <th class="px-4 py-4 text-center w-16">
                                <span class="text-xs font-black text-gray-700 uppercase">ID</span>
                            </th>
                            <th class="px-4 py-4 text-center w-24">
                                <span class="text-xs font-black text-gray-700 uppercase">Image</span>
                            </th>
                            <th class="px-4 py-4 text-left min-w-[250px]">
                                <span class="text-xs font-black text-gray-700 uppercase">Category Details</span>
                            </th>
                            <th class="px-4 py-4 text-center w-32">
                                <span class="text-xs font-black text-gray-700 uppercase">Parent</span>
                            </th>
                            <th class="px-4 py-4 text-center w-24">
                                <span class="text-xs font-black text-gray-700 uppercase">Products</span>
                            </th>
                            <th class="px-4 py-4 text-center w-20">
                                <span class="text-xs font-black text-gray-700 uppercase">Order</span>
                            </th>
                            <th class="px-4 py-4 text-center w-28">
                                <span class="text-xs font-black text-gray-700 uppercase">Status</span>
                            </th>
                            <th class="px-4 py-4 text-center w-24">
                                <span class="text-xs font-black text-gray-700 uppercase">Featured</span>
                            </th>
                            <th class="px-4 py-4 text-center w-24">
                                <span class="text-xs font-black text-gray-700 uppercase">Navbar</span>
                            </th>
                            <th class="px-4 py-4 text-center w-28">
                                <span class="text-xs font-black text-gray-700 uppercase">Homepage</span>
                            </th>
                            <th class="px-4 py-4 text-center w-24">
                                <span class="text-xs font-black text-gray-700 uppercase">Circle</span>
                            </th>
                            <th class="px-4 py-4 text-center w-28">
                                <span class="text-xs font-black text-gray-700 uppercase">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($categories as $category)
                        <tr class="group hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all duration-200"
                            data-status="{{ $category->is_active ? '1' : '0' }}"
                            data-featured="{{ $category->is_featured ? '1' : '0' }}"
                            data-parent="{{ $category->parent_id ?? 'null' }}"
                            data-navbar="{{ $category->show_in_navbar ? '1' : '0' }}"
                            data-homepage="{{ $category->show_in_homepage ? '1' : '0' }}">
                            
                            <!-- Checkbox -->
                            <td class="px-4 py-4 text-center">
                                <input type="checkbox" class="row-select w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500" value="{{ $category->id }}">
                            </td>

                            <!-- ID -->
                            <td class="px-4 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 border border-gray-300">#{{ $category->id }}</span>
                            </td>

                            <!-- Image -->
                            <td class="px-4 py-4 text-center">
                                @if($category->hasImageKitImage())
                                    <div class="flex justify-center">
                                        <div class="relative group/img">
                                            <img src="{{ $category->getListImageUrl() }}" alt="{{ $category->name }}" class="w-16 h-16 rounded-xl object-cover shadow-md border-2 border-gray-200 group-hover/img:border-blue-400 transition-all">
                                            <div class="absolute -top-1 -right-1 w-5 h-5 bg-green-500 rounded-full flex items-center justify-center border-2 border-white">
                                                <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="flex justify-center">
                                        <div class="w-16 h-16 bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl flex items-center justify-center border-2 border-gray-300">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    </div>
                                @endif
                            </td>

                            <!-- Category Details -->
                            <td class="px-4 py-4">
                                <div class="flex items-start space-x-3">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center space-x-2">
                                            <p class="text-sm font-bold text-gray-900 truncate group-hover:text-blue-700">{{ $category->name }}</p>
                                            @if($category->is_featured)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-gradient-to-r from-yellow-400 to-orange-500 text-white">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                    Star
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-gray-500 font-mono mt-1">{{ $category->slug }}</p>
                                        @if($category->description)
                                            <p class="text-xs text-gray-600 mt-1 line-clamp-2">{{ Str::limit($category->description, 80) }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <!-- Parent -->
                            <td class="px-4 py-4 text-center">
                                @if($category->parent)
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-700 border border-blue-300">
                                        <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                        </svg>
                                        {{ Str::limit($category->parent->name, 12) }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold bg-gradient-to-r from-indigo-100 to-pink-100 text-indigo-700 border border-purple-300">
                                        <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                        </svg>
                                        Root
                                    </span>
                                @endif
                            </td>

                            <!-- Products Count -->
                            <td class="px-4 py-4 text-center">
                                <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl text-sm font-black bg-gradient-to-br from-indigo-100 to-indigo-100 text-indigo-700 border-2 border-indigo-200">
                                    {{ $category->products()->count() }}
                                </span>
                            </td>

                            <!-- Sort Order -->
                            <td class="px-4 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-gradient-to-r from-indigo-100 to-pink-100 text-indigo-700 border border-purple-300">
                                    {{ $category->sort_order }}
                                </span>
                            </td>

                            <!-- Status -->
                            <td class="px-4 py-4 text-center">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="sr-only peer feature-toggle" data-id="{{ $category->id }}" data-field="is_active" {{ $category->is_active ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gradient-to-r peer-checked:from-green-400 peer-checked:to-emerald-500"></div>
                                </label>
                            </td>

                            <!-- Featured Toggle -->
                            <td class="px-4 py-4 text-center">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="sr-only peer feature-toggle" data-id="{{ $category->id }}" data-field="is_featured" {{ $category->is_featured ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-yellow-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gradient-to-r peer-checked:from-yellow-400 peer-checked:to-orange-500"></div>
                                </label>
                            </td>

                            <!-- Navbar Toggle -->
                            <td class="px-4 py-4 text-center">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="sr-only peer feature-toggle" data-id="{{ $category->id }}" data-field="show_in_navbar" {{ $category->show_in_navbar ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gradient-to-r peer-checked:from-blue-400 peer-checked:to-indigo-500"></div>
                                </label>
                            </td>

                            <!-- Homepage Toggle -->
                            <td class="px-4 py-4 text-center">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="sr-only peer feature-toggle" data-id="{{ $category->id }}" data-field="show_in_homepage" {{ $category->show_in_homepage ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gradient-to-r peer-checked:from-green-400 peer-checked:to-emerald-500"></div>
                                </label>
                            </td>

                            <!-- Circle Toggle -->
                            <td class="px-4 py-4 text-center">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="sr-only peer feature-toggle" data-id="{{ $category->id }}" data-field="show_in_circle" {{ $category->show_in_circle ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gradient-to-r peer-checked:from-indigo-400 peer-checked:to-pink-500"></div>
                                </label>
                            </td>

                            <!-- Actions -->
                            <td class="px-4 py-4">
                                <div class="flex items-center justify-center space-x-2">
                                    <button class="edit-btn inline-flex items-center justify-center w-9 h-9 text-blue-600 bg-blue-50 border-2 border-blue-200 rounded-lg hover:bg-blue-100 hover:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all"
                                            title="Edit Category"
                                            data-id="{{ $category->id }}"
                                            data-name="{{ $category->name }}"
                                            data-slug="{{ $category->slug }}"
                                            data-description="{{ $category->description }}"
                                            data-parent-id="{{ $category->parent_id }}"
                                            data-sort-order="{{ $category->sort_order }}"
                                            data-is-active="{{ $category->is_active ? 'true' : 'false' }}"
                                            data-is-featured="{{ $category->is_featured ? 'true' : 'false' }}"
                                            data-show-in-navbar="{{ $category->show_in_navbar ? 'true' : 'false' }}"
                                            data-show-in-homepage="{{ $category->show_in_homepage ? 'true' : 'false' }}"
                                            data-show-in-circle="{{ $category->show_in_circle ? 'true' : 'false' }}"
                                            data-circle-type="{{ $category->circle_type }}"
                                            data-circle-text="{{ $category->circle_text }}"
                                            data-circle-link="{{ $category->circle_link }}"
                                            data-circle-bg-color="{{ $category->circle_bg_color }}"
                                            data-circle-text-color="{{ $category->circle_text_color }}"
                                            data-circle-order="{{ $category->circle_order }}"
                                            data-meta-title="{{ $category->meta_title }}"
                                            data-meta-description="{{ $category->meta_description }}"
                                            data-meta-keywords="{{ $category->meta_keywords }}"
                                            data-image-url="{{ $category->getImageUrl() }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button type="button" class="delete-btn inline-flex items-center justify-center w-9 h-9 text-red-600 bg-red-50 border-2 border-red-200 rounded-lg hover:bg-red-100 hover:border-red-400 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all" 
                                            title="Delete Category"
                                            data-id="{{ $category->id }}"
                                            data-name="{{ $category->name }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr id="emptyState">
                            <td colspan="12" class="px-6 py-20">
                                <div class="text-center">
                                    <div class="mx-auto flex items-center justify-center w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full mb-6">
                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">No Categories Found</h3>
                                    <p class="text-gray-500 mb-6 max-w-md mx-auto">Start building your catalog by creating your first category. Categories help organize your products effectively.</p>
                                    <button onclick="openAddModal()" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-bold rounded-xl hover:from-blue-700 hover:to-indigo-700 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        Create First Category
                                    </button>
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
                        Showing <span class="font-bold text-blue-600">{{ $categories->firstItem() ?? 0 }}</span> to <span class="font-bold text-blue-600">{{ $categories->lastItem() ?? 0 }}</span> of <span class="font-bold text-blue-600">{{ $categories->total() }}</span> entries
                    </div>
                    <div class="dataTables_paginate">
                        @if ($categories->hasPages())
                            <nav class="flex items-center space-x-2">
                                {{-- Previous Page Link --}}
                                @if ($categories->onFirstPage())
                                    <span class="px-3 py-2 text-sm font-semibold text-gray-400 bg-gray-100 border border-gray-200 rounded-lg cursor-not-allowed">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                        </svg>
                                    </span>
                                @else
                                    <a href="{{ $categories->previousPageUrl() }}" class="px-3 py-2 text-sm font-semibold text-blue-600 bg-white border-2 border-blue-200 rounded-lg hover:bg-blue-50 hover:border-blue-400 transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                        </svg>
                                    </a>
                                @endif

                                {{-- Pagination Elements --}}
                                @foreach ($categories->links()->elements[0] as $page => $url)
                                    @if ($page == $categories->currentPage())
                                        <span class="px-4 py-2 text-sm font-bold text-white bg-gradient-to-r from-blue-600 to-indigo-600 border-2 border-blue-600 rounded-lg shadow-md">
                                            {{ $page }}
                                        </span>
                                    @else
                                        <a href="{{ $url }}" class="px-4 py-2 text-sm font-semibold text-gray-700 bg-white border-2 border-gray-200 rounded-lg hover:bg-blue-50 hover:border-blue-400 hover:text-blue-600 transition-all">
                                            {{ $page }}
                                        </a>
                                    @endif
                                @endforeach

                                {{-- Next Page Link --}}
                                @if ($categories->hasMorePages())
                                    <a href="{{ $categories->nextPageUrl() }}" class="px-3 py-2 text-sm font-semibold text-blue-600 bg-white border-2 border-blue-200 rounded-lg hover:bg-blue-50 hover:border-blue-400 transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                @else
                                    <span class="px-3 py-2 text-sm font-semibold text-gray-400 bg-gray-100 border border-gray-200 rounded-lg cursor-not-allowed">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </span>
                                @endif
                            </nav>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div id="categoryModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div id="modalOverlay" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity animate-fadeIn"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all duration-300 ease-out animate-slideUp sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <form id="categoryForm" method="POST" action="{{ route('seller.categories.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="methodField" name="_method" value="POST">
                    <input type="hidden" id="categoryId" name="category_id">
                    
                    <!-- Modal Header -->
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <h3 id="modalTitle" class="text-xl font-bold text-gray-900">Add New Category</h3>
                                <button type="button" id="fillUpBtn" class="inline-flex items-center px-3 py-1.5 bg-blue-500 text-white text-xs font-bold rounded-lg hover:bg-blue-600 shadow-sm transform hover:scale-105 transition-all" title="Auto fill form with sample data">
                                    <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                    Fill Up
                                </button>
                            </div>
                            <button type="button" id="closeModalBtn" class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg p-2 transition-all">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Modal Body -->
                    <div class="px-6 py-6 space-y-6 max-h-[calc(100vh-250px)] overflow-y-auto">
                        <!-- Basic Info -->
                        <div class="space-y-4">
                            <h4 class="text-lg font-bold text-gray-900 border-b border-gray-200 pb-2">Basic Information</h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Category Name *</label>
                                    <input type="text" name="name" id="name" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" placeholder="e.g., Men's Fashion">
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Slug</label>
                                    <input type="text" name="slug" id="slug" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" placeholder="auto-generated">
                                </div>
                            </div>

                            <div class="mt-4">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Description</label>
                                <textarea name="description" rows="3" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" placeholder="Brief description of the category"></textarea>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                                <div x-data="modalParentCategoryDropdown()">
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Parent Category</label>
                                    <div class="relative w-full">
                                        <button @click="open = !open" type="button" class="w-full px-4 py-3 text-left border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 flex justify-between items-center bg-white hover:border-gray-400 transition-all">
                                            <span x-text="selectedText || 'None (Root)'" class="truncate text-sm"></span>
                                            <svg class="w-4 h-4 ml-2 text-gray-500 transition-transform duration-200 flex-shrink-0" :class="{'rotate-180': open}" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 011.08 1.04l-4.25 4.25a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                        <div x-show="open" @click.away="open = false" class="absolute z-50 mt-2 w-full bg-white border-2 border-gray-200 rounded-xl shadow-2xl max-h-60 overflow-y-auto">
                                            <input type="text" placeholder="Search category..." x-model="search" class="w-full px-3 py-2 border-b-2 border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" @click.stop>
                                            <template x-for="option in filteredOptions" :key="option.value">
                                                <div @click="selectCategory(option)" class="px-4 py-2 cursor-pointer hover:bg-blue-50 text-sm" :class="{ 'bg-blue-100 font-semibold': selectedValue === option.value }" x-html="option.label"></div>
                                            </template>
                                            <div x-show="filteredOptions.length === 0" class="px-4 py-3 text-gray-400 text-sm text-center">No results found.</div>
                                        </div>
                                        <input type="hidden" name="parent_id" x-ref="hiddenInput" id="modalParentId" value="">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Sort Order *</label>
                                    <input type="number" name="sort_order" min="0" value="0" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Options</label>
                                    <div class="space-y-2">
                                        <label class="flex items-center">
                                            <input type="checkbox" name="is_active" value="1" checked class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500">
                                            <span class="ml-2 text-sm font-medium text-gray-700">Active</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" name="is_featured" value="1" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500">
                                            <span class="ml-2 text-sm font-medium text-gray-700">Featured</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" name="show_in_navbar" value="1" checked class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500">
                                            <span class="ml-2 text-sm font-medium text-gray-700">Show in Navbar</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" name="show_in_homepage" value="1" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500">
                                            <span class="ml-2 text-sm font-medium text-gray-700">Show on Homepage</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" name="show_in_circle" value="1" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500">
                                            <span class="ml-2 text-sm font-medium text-gray-700">Show in Circle (Header)</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Circle Settings (shown when show_in_circle is checked) -->
                                <div id="circleSettings" class="hidden border-t border-gray-200 pt-4 mt-4">
                                    <h5 class="text-sm font-bold text-gray-700 mb-3">Circle Settings</h5>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-600 mb-1">Circle Type</label>
                                            <select name="circle_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                                                <option value="image">Image</option>
                                                <option value="text">Text Only</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-600 mb-1">Circle Order</label>
                                            <input type="number" name="circle_order" value="0" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-600 mb-1">Circle Text (for text type)</label>
                                            <input type="text" name="circle_text" placeholder="e.g., SALE" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-600 mb-1">Custom Link (optional)</label>
                                            <input type="text" name="circle_link" placeholder="/collection?sale=true" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-600 mb-1">BG Color</label>
                                            <input type="color" name="circle_bg_color" value="#4A0F23" class="w-full h-10 border border-gray-300 rounded-lg cursor-pointer">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-600 mb-1">Text Color</label>
                                            <input type="color" name="circle_text_color" value="#D6B27A" class="w-full h-10 border border-gray-300 rounded-lg cursor-pointer">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Image Upload -->
                        <div class="space-y-4">
                            <h4 class="text-lg font-bold text-gray-900 border-b border-gray-200 pb-2">Category Image</h4>
                            
                            <input type="file" name="image" id="imageInput" accept="image/*" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <input type="hidden" name="uploaded_image_data" id="uploadedImageData">
                            <p class="text-xs text-gray-500 mt-2">Recommended: 540x689px • Will be compressed and uploaded automatically</p>
                            
                            <!-- Upload Progress -->
                            <div id="uploadProgress" class="mt-4 hidden">
                                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-xl p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-bold text-blue-900">Uploading...</span>
                                        <span id="uploadPercent" class="text-sm font-bold text-blue-600">0%</span>
                                    </div>
                                    <div class="w-full bg-blue-200 rounded-full h-2.5">
                                        <div id="uploadProgressBar" class="bg-gradient-to-r from-blue-500 to-indigo-600 h-2.5 rounded-full transition-all duration-300" style="width: 0%"></div>
                                    </div>
                                    <p id="uploadStatus" class="text-xs text-blue-700 mt-2">Preparing image...</p>
                                </div>
                            </div>
                            
                            <!-- Upload Success -->
                            <div id="uploadSuccess" class="mt-4 hidden">
                                <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-200 rounded-xl p-4">
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0">
                                            <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="text-sm font-bold text-green-900">Image Uploaded Successfully!</h4>
                                            <div class="mt-2 grid grid-cols-3 gap-2 text-xs">
                                                <div>
                                                    <span class="text-green-700 font-semibold">Original:</span>
                                                    <span id="originalSize" class="text-green-600 ml-1">-</span>
                                                </div>
                                                <div>
                                                    <span class="text-green-700 font-semibold">Compressed:</span>
                                                    <span id="compressedSize" class="text-green-600 ml-1">-</span>
                                                </div>
                                                <div>
                                                    <span class="text-green-700 font-semibold">Saved:</span>
                                                    <span id="savedSize" class="text-green-600 ml-1 font-bold">-</span>
                                                </div>
                                            </div>
                                            <div class="mt-2 text-xs">
                                                <span class="text-green-700 font-semibold">Dimensions:</span>
                                                <span id="imageDimensions" class="text-green-600 ml-1">-</span>
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <img id="uploadedPreview" src="" alt="" class="w-20 h-25 object-cover rounded-lg border-2 border-green-300">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div id="imagePreview" class="mt-4 hidden">
                                <div class="relative inline-block">
                                    <img id="previewImg" src="" alt="Preview" class="w-32 h-40 object-cover rounded-xl border-2 border-gray-200 shadow-md">
                                    <button type="button" id="removePreview" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-7 h-7 flex items-center justify-center text-sm hover:bg-red-600 transition-all">×</button>
                                </div>
                            </div>
                        </div>

                        <!-- SEO Settings -->
                        <div class="space-y-4">
                            <h4 class="text-lg font-bold text-gray-900 border-b border-gray-200 pb-2">SEO Settings</h4>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Meta Title</label>
                                    <input type="text" name="meta_title" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" placeholder="SEO meta title">
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Meta Description</label>
                                    <textarea name="meta_description" rows="2" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" placeholder="SEO meta description"></textarea>
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Meta Keywords</label>
                                    <input type="text" name="meta_keywords" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" placeholder="keyword1, keyword2, keyword3">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                        <button type="button" id="cancelModalBtn" class="px-6 py-3 border-2 border-gray-300 text-gray-700 text-sm font-bold rounded-xl hover:bg-gray-100 transition-all">
                            Cancel
                        </button>
                        <button type="submit" id="submitBtn" class="inline-flex items-center px-6 py-3 bg-black text-white text-sm font-bold rounded-xl hover:bg-gray-800 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none disabled:hover:scale-100">
                            <svg id="loadingSpinner" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span id="submitBtnText">Create Category</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Minimal Modal Functions
        function openModal() {
            document.getElementById('categoryModal').classList.remove('hidden');
        }
        
        function closeModal() {
            document.getElementById('categoryModal').classList.add('hidden');
        }
        
        function openAddModal() {
            document.getElementById('modalTitle').textContent = 'Add New Category';
            document.getElementById('submitBtnText').textContent = 'Create Category';
            document.getElementById('categoryForm').action = '{{ route('seller.categories.store') }}';
            document.getElementById('methodField').value = 'POST';
            document.getElementById('categoryId').value = '';
            document.getElementById('categoryForm').reset();
            document.getElementById('imagePreview')?.classList.add('hidden');
            document.getElementById('uploadSuccess')?.classList.add('hidden');
            document.getElementById('uploadProgress')?.classList.add('hidden');
            openModal();
        }
        
        function openEditModal(data) {
            document.getElementById('modalTitle').textContent = 'Edit Category';
            document.getElementById('submitBtnText').textContent = 'Update Category';
            document.getElementById('categoryForm').action = '/seller/categories/' + data.id;
            document.getElementById('methodField').value = 'PUT';
            document.getElementById('categoryId').value = data.id;
            
            document.querySelector('input[name="name"]').value = data.name;
            document.querySelector('input[name="slug"]').value = data.slug;
            document.querySelector('textarea[name="description"]').value = data.description || '';
            document.querySelector('input[name="sort_order"]').value = data.sortOrder;
            document.querySelector('input[name="is_active"]').checked = data.isActive;
            document.querySelector('input[name="is_featured"]').checked = data.isFeatured;
            document.querySelector('input[name="show_in_navbar"]').checked = data.showInNavbar;
            document.querySelector('input[name="show_in_homepage"]').checked = data.showInHomepage;
            document.querySelector('input[name="show_in_circle"]').checked = data.showInCircle;
            document.querySelector('input[name="meta_title"]').value = data.metaTitle || '';
            document.querySelector('textarea[name="meta_description"]').value = data.metaDescription || '';
            document.querySelector('input[name="meta_keywords"]').value = data.metaKeywords || '';
            
            // Circle settings
            if (data.showInCircle) {
                document.getElementById('circleSettings').classList.remove('hidden');
            } else {
                document.getElementById('circleSettings').classList.add('hidden');
            }
            document.querySelector('select[name="circle_type"]').value = data.circleType || 'image';
            document.querySelector('input[name="circle_text"]').value = data.circleText || '';
            document.querySelector('input[name="circle_link"]').value = data.circleLink || '';
            document.querySelector('input[name="circle_bg_color"]').value = data.circleBgColor || '#4A0F23';
            document.querySelector('input[name="circle_text_color"]').value = data.circleTextColor || '#D6B27A';
            document.querySelector('input[name="circle_order"]').value = data.circleOrder || 0;
            
            if (data.imageUrl && data.imageUrl !== 'null' && data.imageUrl !== '') {
                document.getElementById('previewImg').src = data.imageUrl;
                document.getElementById('imagePreview')?.classList.remove('hidden');
            }
            
            openModal();
        }
        
        function showToastNotification(message, type = 'success') {
            let toastContainer = document.getElementById('toast-container');
            if (!toastContainer) {
                toastContainer = document.createElement('div');
                toastContainer.id = 'toast-container';
                toastContainer.className = 'fixed top-4 right-4 z-50 space-y-2';
                document.body.appendChild(toastContainer);
            }
            
            const toast = document.createElement('div');
            const bgColor = type === 'success' ? 'from-green-500 to-emerald-600' : 'from-red-500 to-pink-600';
            
            toast.className = `bg-gradient-to-r ${bgColor} text-white px-6 py-4 rounded-xl shadow-2xl flex items-center space-x-3 transform transition-all duration-300 translate-x-full`;
            toast.innerHTML = `
                <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="font-semibold">${message}</span>
                <button onclick="this.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            `;
            
            toastContainer.appendChild(toast);
            setTimeout(() => toast.classList.remove('translate-x-full'), 100);
            setTimeout(() => {
                toast.classList.add('translate-x-full');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
        
        // Close modal on button clicks
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('closeModalBtn')?.addEventListener('click', closeModal);
            document.getElementById('cancelModalBtn')?.addEventListener('click', closeModal);
            document.getElementById('modalOverlay')?.addEventListener('click', closeModal);
        });
        
        // Alpine.js Parent Category Dropdown for Modal
        function modalParentCategoryDropdown() {
            return {
                open: false,
                search: '',
                selectedText: '',
                selectedValue: '',
                options: [
                    { value: '', label: 'None (Root)' },
                    @php
                    $allCategories = App\Models\Category::whereNull('parent_id')->with(['children' => function($query) {
                        $query->with('children')->orderBy('name');
                    }])->orderBy('name')->get();
                    @endphp
                    @foreach($allCategories as $cat)
                        { value: '{{ $cat->id }}', label: '{{ $cat->name }}' },
                        @foreach($cat->children as $child)
                            { value: '{{ $child->id }}', label: '&nbsp;&nbsp;&nbsp;→ {{ $child->name }}' },
                            @foreach($child->children as $grandchild)
                                { value: '{{ $grandchild->id }}', label: '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;→→ {{ $grandchild->name }}' },
                            @endforeach
                        @endforeach
                    @endforeach
                ],
                
                init() {
                    if (this.selectedValue) {
                        const selected = this.options.find(opt => opt.value == this.selectedValue);
                        if (selected) {
                            this.selectedText = selected.label.replace(/&nbsp;/g, ' ');
                        }
                    }
                    if (this.$refs.hiddenInput) this.$refs.hiddenInput.value = this.selectedValue;
                },
                
                get filteredOptions() {
                    if (!this.search) return this.options;
                    const searchLower = this.search.toLowerCase();
                    return this.options.filter(option =>
                        option.label.toLowerCase().replace(/&nbsp;/g, '').replace(/→/g, '').includes(searchLower)
                    );
                },
                
                selectCategory(option) {
                    this.selectedValue = option.value;
                    this.selectedText = option.label.replace(/&nbsp;/g, ' ');
                    if (this.$refs.hiddenInput) this.$refs.hiddenInput.value = option.value;
                    this.open = false;
                    this.search = '';
                },
                
                reset() {
                    this.selectedValue = '';
                    this.selectedText = '';
                    this.search = '';
                    this.open = false;
                    if (this.$refs.hiddenInput) this.$refs.hiddenInput.value = '';
                }
            };
        }
        
        // Auto-generate slug from name
        document.addEventListener('DOMContentLoaded', function() {
            const nameInput = document.getElementById('name');
            const slugInput = document.getElementById('slug');
            
            if (nameInput && slugInput) {
                nameInput.addEventListener('input', function() {
                    const name = this.value;
                    const slug = name
                        .toLowerCase()
                        .replace(/[^a-z0-9 -]/g, '')
                        .replace(/\s+/g, '-')
                        .replace(/-+/g, '-')
                        .replace(/^-+|-+$/g, '');
                    slugInput.value = slug;
                });
            }
            
            // Image upload preview
            const imageInput = document.getElementById('imageInput');
            if (imageInput) {
                imageInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            document.getElementById('previewImg').src = e.target.result;
                            document.getElementById('imagePreview').classList.remove('hidden');
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
            
            // Remove image preview
            const removePreview = document.getElementById('removePreview');
            if (removePreview) {
                removePreview.addEventListener('click', function() {
                    document.getElementById('imageInput').value = '';
                    document.getElementById('imagePreview').classList.add('hidden');
                });
            }
            
            // Show/hide circle settings based on checkbox
            const showInCircleCheckbox = document.querySelector('input[name="show_in_circle"]');
            const circleSettings = document.getElementById('circleSettings');
            if (showInCircleCheckbox && circleSettings) {
                showInCircleCheckbox.addEventListener('change', function() {
                    if (this.checked) {
                        circleSettings.classList.remove('hidden');
                    } else {
                        circleSettings.classList.add('hidden');
                    }
                });
            }
        });
    </script>

    @include('seller.categories.scripts')
@endsection
