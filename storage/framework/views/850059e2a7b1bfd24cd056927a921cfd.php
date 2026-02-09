<?php $__env->startSection('title', 'Products Management'); ?>

<?php $__env->startSection('content'); ?>
<!-- Main Container -->
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-purple-50 to-indigo-50 p-6">
    
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="flex items-center space-x-4">
                <div class="flex items-center justify-center w-14 h-14 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-2xl shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-black text-gray-900 tracking-tight">Products Management</h1>
                    <p class="mt-1 text-sm text-gray-600">Manage your product inventory with advanced controls</p>
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
                <a href="<?php echo e(route('admin.products.create.step1')); ?>" class="inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white text-sm font-bold rounded-xl hover:from-purple-700 hover:to-indigo-700 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Product
                </a>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if(session('success')): ?>
        <div class="mb-6 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 p-4 rounded-xl shadow-md animate-slideIn">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-semibold text-green-800"><?php echo e(session('success')); ?></p>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-green-600 hover:text-green-800">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
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
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Statistics Dashboard -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Products -->
        <div class="bg-gradient-to-br from-purple-500 to-indigo-600 rounded-2xl shadow-xl p-6 transform hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center justify-center w-12 h-12 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <span class="px-3 py-1 bg-white bg-opacity-20 backdrop-blur-sm rounded-full text-xs font-bold text-white">Total</span>
            </div>
            <div class="text-white">
                <p class="text-4xl font-black mb-1"><?php echo e($totalProducts ?? $products->total()); ?></p>
                <p class="text-purple-100 text-sm font-medium">Total Products</p>
            </div>
        </div>

        <!-- Active Products -->
        <div class="bg-gradient-to-br from-emerald-500 to-green-600 rounded-2xl shadow-xl p-6 transform hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center justify-center w-12 h-12 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="px-3 py-1 bg-white bg-opacity-20 backdrop-blur-sm rounded-full text-xs font-bold text-white">Live</span>
            </div>
            <div class="text-white">
                <p class="text-4xl font-black mb-1"><?php echo e($activeProducts ?? $products->where('status', 'active')->count()); ?></p>
                <p class="text-emerald-100 text-sm font-medium">Active Products</p>
            </div>
        </div>

        <!-- Featured Products -->
        <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl shadow-xl p-6 transform hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center justify-center w-12 h-12 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </div>
                <span class="px-3 py-1 bg-white bg-opacity-20 backdrop-blur-sm rounded-full text-xs font-bold text-white">Featured</span>
            </div>
            <div class="text-white">
                <p class="text-4xl font-black mb-1"><?php echo e($featuredProducts ?? $products->where('is_featured', true)->count()); ?></p>
                <p class="text-amber-100 text-sm font-medium">Featured Products</p>
            </div>
        </div>

        <!-- Out of Stock -->
        <div class="bg-gradient-to-br from-gray-500 to-slate-600 rounded-2xl shadow-xl p-6 transform hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center justify-center w-12 h-12 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="px-3 py-1 bg-white bg-opacity-20 backdrop-blur-sm rounded-full text-xs font-bold text-white">Alert</span>
            </div>
            <div class="text-white">
                <p class="text-4xl font-black mb-1"><?php echo e($outOfStockProducts ?? $products->where('stock_status', 'out_of_stock')->count()); ?></p>
                <p class="text-gray-100 text-sm font-medium">Out of Stock</p>
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
                        Products Data Table
                    </h2>
                    <p class="text-sm text-gray-600 mt-1 ml-11">Comprehensive inventory management with advanced filtering and bulk actions</p>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="text-sm font-medium text-gray-600">Quick Actions:</span>
                    <select id="bulkAction" class="px-3 py-2 bg-white border-2 border-gray-300 rounded-lg text-sm font-medium focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        <option value="">Select Action</option>
                        <option value="activate">Activate</option>
                        <option value="deactivate">Deactivate</option>
                        <option value="feature">Mark Featured</option>
                        <option value="unfeature">Remove Featured</option>
                        <option value="in_stock">Mark In Stock</option>
                        <option value="out_of_stock">Mark Out of Stock</option>
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
            <form method="GET" action="<?php echo e(route('admin.products')); ?>" id="filtersForm">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    <!-- Search -->
                    <div class="lg:col-span-2">
                        <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">Search Products</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input type="text" name="search" id="globalSearch" class="block w-full pl-10 pr-3 py-3 border-2 border-gray-300 rounded-xl bg-white text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all" placeholder="Search by name, SKU, or description..." value="<?php echo e($search); ?>">
                        </div>
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">Status Filter</label>
                        <select name="filter_status" id="statusFilter" class="block w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            <option value="">All Status</option>
                            <option value="active" <?php echo e($filterStatus == 'active' ? 'selected' : ''); ?>>✓ Active</option>
                            <option value="inactive" <?php echo e($filterStatus == 'inactive' ? 'selected' : ''); ?>>✗ Inactive</option>
                            <option value="draft" <?php echo e($filterStatus == 'draft' ? 'selected' : ''); ?>>📝 Draft</option>
                        </select>
                    </div>

                    <!-- Category Filter -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">Category</label>
                        <select name="filter_category" id="categoryFilter" class="block w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            <option value="">All Categories</option>
                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($category->id); ?>" <?php echo e($filterCategory == $category->id ? 'selected' : ''); ?>>
                                    <?php echo e($category->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <!-- Items Per Page -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">Show Entries</label>
                        <select name="per_page" id="itemsPerPage" class="block w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            <option value="10" <?php echo e($perPage == 10 ? 'selected' : ''); ?>>10 per page</option>
                            <option value="25" <?php echo e($perPage == 25 ? 'selected' : ''); ?>>25 per page</option>
                            <option value="50" <?php echo e($perPage == 50 ? 'selected' : ''); ?>>50 per page</option>
                            <option value="100" <?php echo e($perPage == 100 ? 'selected' : ''); ?>>100 per page</option>
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
                        <span class="ml-2 text-sm font-semibold text-purple-600">items selected</span>
                    </div>
                </div>
            </form>
        </div>

        <!-- Data Table -->
        <div class="overflow-x-auto">
            <table id="productsTable" class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-slate-100 to-gray-100">
                    <tr>
                        <th class="px-4 py-4 text-center w-12">
                            <input type="checkbox" id="selectAll" class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-2 focus:ring-purple-500">
                        </th>
                        <th class="px-4 py-4 text-center w-16">
                            <span class="text-xs font-black text-gray-700 uppercase">ID</span>
                        </th>
                        <th class="px-4 py-4 text-left min-w-[250px]">
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
                        <th class="px-4 py-4 text-center w-28">
                            <span class="text-xs font-black text-gray-700 uppercase">Status</span>
                        </th>
                        <th class="px-4 py-4 text-center w-24">
                            <span class="text-xs font-black text-gray-700 uppercase">Featured</span>
                        </th>
                        <th class="px-4 py-4 text-center w-32">
                            <span class="text-xs font-black text-gray-700 uppercase">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="group hover:bg-gradient-to-r hover:from-purple-50 hover:to-indigo-50 transition-all duration-200" 
                        data-id="<?php echo e($product->id); ?>" 
                        data-status="<?php echo e($product->status); ?>" 
                        data-featured="<?php echo e($product->is_featured ? '1' : '0'); ?>" 
                        data-stock="<?php echo e($product->stock_status); ?>">
                        
                        <!-- Checkbox -->
                        <td class="px-4 py-4 text-center">
                            <input type="checkbox" class="row-select w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-2 focus:ring-purple-500" value="<?php echo e($product->id); ?>">
                        </td>

                        <!-- ID -->
                        <td class="px-4 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 border border-gray-300">#<?php echo e($product->id); ?></span>
                        </td>

                        <!-- Product Info -->
                        <td class="px-4 py-4">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0 w-12 h-12">
                                    <?php
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
                                    ?>
                                    <?php if($firstImage && is_string($firstImage)): ?>
                                        <img src="<?php echo e($firstImage); ?>" alt="<?php echo e($product->name); ?>" class="w-12 h-12 rounded-xl object-cover border-2 border-gray-200 shadow-sm">
                                    <?php else: ?>
                                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center border-2 border-gray-200">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-sm font-bold text-gray-900 mb-1"><?php echo e($product->name); ?></h3>
                                    <p class="text-xs text-gray-500 leading-relaxed"><?php echo e(Str::limit($product->description ?? 'No description', 60)); ?></p>
                                    <div class="flex items-center space-x-2 mt-2">
                                        <?php if($product->is_featured): ?>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-300">
                                                ⭐ Featured
                                            </span>
                                        <?php endif; ?>
                                        <?php if($product->is_new): ?>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-300">
                                                🆕 New
                                            </span>
                                        <?php endif; ?>
                                        <?php if($product->is_sale): ?>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-300">
                                                🔥 Sale
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- SKU -->
                        <td class="px-4 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-gradient-to-r from-blue-100 to-cyan-100 text-blue-700 border border-blue-300">
                                <?php echo e($product->sku ?? 'N/A'); ?>

                            </span>
                        </td>

                        <!-- Category -->
                        <td class="px-4 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-gradient-to-r from-purple-100 to-indigo-100 text-purple-700 border border-purple-300">
                                <?php echo e($product->category?->name ?? 'Uncategorized'); ?>

                            </span>
                        </td>

                        <!-- Price -->
                        <td class="px-4 py-4 text-center">
                            <div class="text-sm font-bold text-gray-900">
                                ₹<?php echo e(number_format($product->price ?? 0, 2)); ?>

                                <?php if($product->sale_price && $product->sale_price < $product->price): ?>
                                    <br>
                                    <span class="text-xs text-red-600 line-through">₹<?php echo e(number_format($product->sale_price, 2)); ?></span>
                                <?php endif; ?>
                            </div>
                        </td>

                        <!-- Stock -->
                        <td class="px-4 py-4 text-center">
                            <div class="flex flex-col items-center">
                                <span class="text-sm font-bold text-gray-900"><?php echo e($product->stock ?? 0); ?></span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium mt-1
                                    <?php echo e($product->stock_status === 'in_stock' ? 'bg-green-100 text-green-800 border border-green-300' : 'bg-red-100 text-red-800 border border-red-300'); ?>">
                                    <?php echo e($product->stock_status === 'in_stock' ? '📦 In Stock' : '❌ Out of Stock'); ?>

                                </span>
                            </div>
                        </td>

                        <!-- Status Toggle -->
                        <td class="px-4 py-4 text-center">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer status-toggle" data-id="<?php echo e($product->id); ?>" data-field="status" <?php echo e($product->status === 'active' ? 'checked' : ''); ?>>
                                <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gradient-to-r peer-checked:from-green-400 peer-checked:to-emerald-500"></div>
                            </label>
                        </td>

                        <!-- Featured Toggle -->
                        <td class="px-4 py-4 text-center">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer featured-toggle" data-id="<?php echo e($product->id); ?>" data-field="is_featured" <?php echo e($product->is_featured ? 'checked' : ''); ?>>
                                <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-yellow-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gradient-to-r peer-checked:from-yellow-400 peer-checked:to-amber-500"></div>
                            </label>
                        </td>

                        <!-- Actions -->
                        <td class="px-4 py-4">
                            <div class="flex items-center justify-center space-x-2">
                                <button class="view-btn inline-flex items-center justify-center w-9 h-9 text-blue-600 bg-blue-50 border-2 border-blue-200 rounded-lg hover:bg-blue-100 hover:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all"
                                        title="View Product"
                                        data-id="<?php echo e($product->id); ?>">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                                <button class="edit-btn inline-flex items-center justify-center w-9 h-9 text-purple-600 bg-purple-50 border-2 border-purple-200 rounded-lg hover:bg-purple-100 hover:border-purple-400 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-all"
                                        title="Edit Product"
                                        data-id="<?php echo e($product->id); ?>">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <button class="copy-btn inline-flex items-center justify-center w-9 h-9 text-cyan-600 bg-cyan-50 border-2 border-cyan-200 rounded-lg hover:bg-cyan-100 hover:border-cyan-400 focus:outline-none focus:ring-2 focus:ring-cyan-500 transition-all"
                                        title="Duplicate Product"
                                        data-id="<?php echo e($product->id); ?>"
                                        data-name="<?php echo e($product->name); ?>">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                </button>
                                <button type="button" class="delete-btn inline-flex items-center justify-center w-9 h-9 text-red-600 bg-red-50 border-2 border-red-200 rounded-lg hover:bg-red-100 hover:border-red-400 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all" 
                                        title="Delete Product"
                                        data-id="<?php echo e($product->id); ?>"
                                        data-name="<?php echo e($product->name); ?>">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr id="emptyState">
                        <td colspan="10" class="px-6 py-20">
                            <div class="text-center">
                                <div class="mx-auto flex items-center justify-center w-24 h-24 bg-gradient-to-br from-purple-100 to-indigo-200 rounded-full mb-6">
                                    <svg class="w-12 h-12 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">No Products Found</h3>
                                <p class="text-gray-500 mb-6 max-w-md mx-auto">Start building your inventory by adding your first product.</p>
                                <a href="<?php echo e(route('admin.products.create.step1')); ?>" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white text-sm font-bold rounded-xl hover:from-purple-700 hover:to-indigo-700 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Create First Product
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Table Footer -->
        <div class="bg-gradient-to-r from-gray-50 to-slate-50 px-6 py-4 border-t border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="dataTables_info text-sm font-medium text-gray-700">
                    Showing <span class="font-bold text-purple-600"><?php echo e($products->firstItem() ?? 0); ?></span> to <span class="font-bold text-purple-600"><?php echo e($products->lastItem() ?? 0); ?></span> of <span class="font-bold text-purple-600"><?php echo e($products->total()); ?></span> entries
                </div>
                <div class="dataTables_paginate">
                    <?php if($products->hasPages()): ?>
                        <?php echo e($products->appends(request()->query())->links('admin.pagination.custom')); ?>

                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $__env->make('admin.products.modal-new', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('admin.products.scripts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\ESS_Project1\resources\views/admin/products/index.blade.php ENDPATH**/ ?>