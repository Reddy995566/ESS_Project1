<?php $__env->startSection('title', 'Products Management'); ?>

<?php $__env->startSection('content'); ?>
<!-- Main Container -->
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-indigo-50 to-purple-50 p-6">
    
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="flex items-center space-x-4">
                <div class="flex items-center justify-center w-14 h-14 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl shadow-lg">
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
                <button id="refreshBtn" class="inline-flex items-center justify-center w-11 h-11 bg-white text-gray-700 rounded-xl border-2 border-gray-200 hover:border-indigo-400 hover:bg-indigo-50 hover:text-indigo-700 transition-all duration-200 shadow-sm hover:shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </button>
                <a href="<?php echo e(route('seller.products.create.step1')); ?>" class="inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-sm font-bold rounded-xl hover:from-indigo-700 hover:to-purple-700 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
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
        <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl shadow-xl p-6 transform hover:scale-105 transition-all duration-300">
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
                <p class="text-indigo-100 text-sm font-medium">Total Products</p>
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
                <p class="text-4xl font-black mb-1"><?php echo e($activeProducts ?? 0); ?></p>
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
                <p class="text-4xl font-black mb-1"><?php echo e($featuredProducts ?? 0); ?></p>
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
                <p class="text-4xl font-black mb-1"><?php echo e($outOfStockProducts ?? 0); ?></p>
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
                        <span class="flex items-center justify-center w-8 h-8 bg-indigo-100 text-indigo-600 rounded-lg mr-3">
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
                    <select id="bulkAction" class="px-3 py-2 bg-white border-2 border-gray-300 rounded-lg text-sm font-medium focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Select Action</option>
                        <option value="activate">Activate</option>
                        <option value="deactivate">Deactivate</option>
                        <option value="feature">Mark Featured</option>
                        <option value="unfeature">Remove Featured</option>
                        <option value="in_stock">Mark In Stock</option>
                        <option value="out_of_stock">Mark Out of Stock</option>
                        <option value="delete" class="text-red-600">Delete Selected</option>
                    </select>
                    <button id="applyBulkAction" disabled class="px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed transition-all">
                        Apply
                    </button>
                </div>
            </div>
        </div>

        <!-- Advanced Filters Section -->
        <div class="bg-gray-50 px-6 py-5 border-b border-gray-200">
            <form method="GET" action="<?php echo e(route('seller.products.index')); ?>" id="filtersForm">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                    <!-- Search -->
                    <div class="lg:col-span-2">
                        <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">Search Products</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input type="text" name="search" id="globalSearch" class="block w-full pl-10 pr-3 py-3 border-2 border-gray-300 rounded-xl bg-white text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" placeholder="Search by name, SKU, or description..." value="<?php echo e(request('search')); ?>">
                        </div>
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">Status Filter</label>
                        <select name="status" id="statusFilter" class="block w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">All Status</option>
                            <option value="active" <?php echo e(request('status') == 'active' ? 'selected' : ''); ?>>✓ Active</option>
                            <option value="inactive" <?php echo e(request('status') == 'inactive' ? 'selected' : ''); ?>>✗ Inactive</option>
                            <option value="draft" <?php echo e(request('status') == 'draft' ? 'selected' : ''); ?>>📝 Draft</option>
                        </select>
                    </div>

                    <!-- Approval Status Filter -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">Approval</label>
                        <select name="approval_status" id="approvalFilter" class="block w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">All Approval</option>
                            <option value="approved" <?php echo e(request('approval_status') == 'approved' ? 'selected' : ''); ?>>✅ Approved</option>
                            <option value="pending" <?php echo e(request('approval_status') == 'pending' ? 'selected' : ''); ?>>⏳ Pending</option>
                            <option value="rejected" <?php echo e(request('approval_status') == 'rejected' ? 'selected' : ''); ?>>❌ Rejected</option>
                            <option value="draft" <?php echo e(request('approval_status') == 'draft' ? 'selected' : ''); ?>>📝 Draft</option>
                        </select>
                    </div>

                    <!-- Category Filter -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">Category</label>
                        <select name="category" id="categoryFilter" class="block w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">All Categories</option>
                            <?php $__currentLoopData = $categories ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($category->id); ?>" <?php echo e(request('category') == $category->id ? 'selected' : ''); ?>>
                                    <?php echo e($category->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <!-- Items Per Page -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">Show Entries</label>
                        <select name="per_page" id="itemsPerPage" class="block w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="10" <?php echo e(request('per_page') == 10 ? 'selected' : ''); ?>>10 per page</option>
                            <option value="25" <?php echo e(request('per_page') == 25 ? 'selected' : ''); ?>>25 per page</option>
                            <option value="50" <?php echo e(request('per_page') == 50 ? 'selected' : ''); ?>>50 per page</option>
                            <option value="100" <?php echo e(request('per_page') == 100 ? 'selected' : ''); ?>>100 per page</option>
                        </select>
                    </div>
                </div>

                <!-- Additional Filters Row -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-4">
                    <button type="submit" class="px-4 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-sm font-bold rounded-lg hover:from-indigo-700 hover:to-purple-700 transition-all flex items-center justify-center">
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
                    <div class="flex items-center h-12 px-4 bg-gradient-to-r from-indigo-50 to-purple-50 border-2 border-indigo-200 rounded-xl col-span-2">
                        <svg class="w-5 h-5 text-indigo-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span id="selectedCount" class="text-2xl font-black text-indigo-700">0</span>
                        <span class="ml-2 text-sm font-semibold text-indigo-600">items selected</span>
                    </div>
                </div>
            </form>
        </div>

        <!-- Data Table - Desktop View -->
        <div class="hidden lg:block overflow-x-auto">
            <table id="productsTable" class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-slate-100 to-gray-100">
                    <tr>
                        <th class="px-4 py-4 text-center w-12">
                            <input type="checkbox" id="selectAll" class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-2 focus:ring-indigo-500">
                        </th>
                        <th class="px-4 py-4 text-center w-16">
                            <span class="text-xs font-black text-gray-700 uppercase">ID</span>
                        </th>
                        <th class="px-4 py-4 text-left min-w-[250px]">
                            <span class="text-xs font-black text-gray-700 uppercase">Product</span>
                        </th>
                        <th class="px-4 py-4 text-center w-32 hidden md:table-cell">
                            <span class="text-xs font-black text-gray-700 uppercase">SKU</span>
                        </th>
                        <th class="px-4 py-4 text-center w-32 hidden sm:table-cell">
                            <span class="text-xs font-black text-gray-700 uppercase">Category</span>
                        </th>
                        <th class="px-4 py-4 text-center w-28">
                            <span class="text-xs font-black text-gray-700 uppercase">Price</span>
                        </th>
                        <th class="px-4 py-4 text-center w-24 hidden sm:table-cell">
                            <span class="text-xs font-black text-gray-700 uppercase">Stock</span>
                        </th>
                        <th class="px-4 py-4 text-center w-28 hidden md:table-cell">
                            <span class="text-xs font-black text-gray-700 uppercase">Status</span>
                        </th>
                        <th class="px-4 py-4 text-center w-32 hidden md:table-cell">
                            <span class="text-xs font-black text-gray-700 uppercase">Approval</span>
                        </th>
                        <th class="px-4 py-4 text-center w-24 hidden md:table-cell">
                            <span class="text-xs font-black text-gray-700 uppercase">Featured</span>
                        </th>
                        <th class="px-4 py-4 text-center w-32">
                            <span class="text-xs font-black text-gray-700 uppercase">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="group hover:bg-gradient-to-r hover:from-indigo-50 hover:to-purple-50 transition-all duration-200" 
                        data-id="<?php echo e($product->id); ?>" 
                        data-status="<?php echo e($product->status); ?>" 
                        data-featured="<?php echo e($product->is_featured ? '1' : '0'); ?>" 
                        data-stock="<?php echo e($product->stock_status); ?>">
                        
                        <!-- Checkbox -->
                        <td class="px-4 py-4 text-center">
                            <input type="checkbox" class="row-select w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-2 focus:ring-indigo-500" value="<?php echo e($product->id); ?>">
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
                                        if(!$firstImage && method_exists($product, 'variants')) {
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
                        <td class="px-4 py-4 text-center hidden md:table-cell">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-gradient-to-r from-blue-100 to-cyan-100 text-blue-700 border border-blue-300">
                                <?php echo e($product->sku ?? 'N/A'); ?>

                            </span>
                        </td>

                        <!-- Category -->
                        <td class="px-4 py-4 text-center hidden sm:table-cell">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-gradient-to-r from-indigo-100 to-purple-100 text-indigo-700 border border-indigo-300">
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
                        <td class="px-4 py-4 text-center hidden sm:table-cell">
                            <div class="flex flex-col items-center">
                                <span class="text-sm font-bold text-gray-900"><?php echo e($product->stock ?? 0); ?></span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium mt-1
                                    <?php echo e($product->stock_status === 'in_stock' ? 'bg-green-100 text-green-800 border border-green-300' : 'bg-red-100 text-red-800 border border-red-300'); ?>">
                                    <?php echo e($product->stock_status === 'in_stock' ? '📦 In Stock' : '❌ Out of Stock'); ?>

                                </span>
                            </div>
                        </td>

                        <!-- Status Toggle -->
                        <td class="px-4 py-4 text-center hidden md:table-cell">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer status-toggle" data-id="<?php echo e($product->id); ?>" data-field="status" <?php echo e($product->status === 'active' ? 'checked' : ''); ?>>
                                <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gradient-to-r peer-checked:from-green-400 peer-checked:to-emerald-500"></div>
                            </label>
                        </td>

                        <!-- Approval Status -->
                        <td class="px-4 py-4 text-center hidden md:table-cell">
                            <?php
                                $approvalStatus = $product->approval_status ?? 'pending';
                                $statusConfig = [
                                    'approved' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'border' => 'border-green-300', 'icon' => '✅', 'label' => 'Approved'],
                                    'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'border' => 'border-yellow-300', 'icon' => '⏳', 'label' => 'Pending'],
                                    'rejected' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'border' => 'border-red-300', 'icon' => '❌', 'label' => 'Rejected'],
                                    'draft' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'border' => 'border-gray-300', 'icon' => '📝', 'label' => 'Draft']
                                ];
                                $config = $statusConfig[$approvalStatus] ?? $statusConfig['pending'];
                            ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold <?php echo e($config['bg']); ?> <?php echo e($config['text']); ?> border <?php echo e($config['border']); ?>">
                                <?php echo e($config['icon']); ?> <?php echo e($config['label']); ?>

                            </span>
                        </td>

                        <!-- Featured Toggle -->
                        <td class="px-4 py-4 text-center hidden md:table-cell">
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
                                <button class="edit-btn inline-flex items-center justify-center w-9 h-9 text-indigo-600 bg-indigo-50 border-2 border-indigo-200 rounded-lg hover:bg-indigo-100 hover:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all"
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
                        <td colspan="6" class="px-6 py-20">
                            <div class="text-center">
                                <div class="mx-auto flex items-center justify-center w-24 h-24 bg-gradient-to-br from-indigo-100 to-purple-200 rounded-full mb-6">
                                    <svg class="w-12 h-12 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">No Products Found</h3>
                                <p class="text-gray-500 mb-6 max-w-md mx-auto">Start building your inventory by adding your first product.</p>
                                <a href="<?php echo e(route('seller.products.create.step1')); ?>" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-sm font-bold rounded-xl hover:from-indigo-700 hover:to-purple-700 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all">
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

        <!-- Mobile Card View -->
        <div class="lg:hidden space-y-4">
            <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bg-white rounded-xl shadow-md border border-gray-200 p-4 hover:shadow-lg transition-all duration-200"
                 data-id="<?php echo e($product->id); ?>" 
                 data-status="<?php echo e($product->status); ?>" 
                 data-featured="<?php echo e($product->is_featured ? '1' : '0'); ?>" 
                 data-stock="<?php echo e($product->stock_status); ?>">
                
                <!-- Mobile Card Header -->
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center space-x-3">
                        <input type="checkbox" class="row-select w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-2 focus:ring-indigo-500" value="<?php echo e($product->id); ?>">
                        <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-bold bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 border border-gray-300">#<?php echo e($product->id); ?></span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <!-- Status Toggle -->
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer status-toggle" data-id="<?php echo e($product->id); ?>" data-field="status" <?php echo e($product->status === 'active' ? 'checked' : ''); ?>>
                            <div class="w-9 h-5 bg-gray-300 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-gradient-to-r peer-checked:from-green-400 peer-checked:to-emerald-500"></div>
                        </label>
                        <!-- Featured Toggle -->
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer featured-toggle" data-id="<?php echo e($product->id); ?>" data-field="is_featured" <?php echo e($product->is_featured ? 'checked' : ''); ?>>
                            <div class="w-9 h-5 bg-gray-300 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-yellow-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-gradient-to-r peer-checked:from-yellow-400 peer-checked:to-amber-500"></div>
                        </label>
                    </div>
                </div>

                <!-- Product Info -->
                <div class="flex items-start space-x-4 mb-4">
                    <div class="flex-shrink-0 w-16 h-16">
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
                        ?>
                        <?php if($firstImage && is_string($firstImage)): ?>
                            <img src="<?php echo e($firstImage); ?>" alt="<?php echo e($product->name); ?>" class="w-16 h-16 rounded-xl object-cover border-2 border-gray-200 shadow-sm">
                        <?php else: ?>
                            <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center border-2 border-gray-200">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-base font-bold text-gray-900 mb-1 truncate"><?php echo e($product->name); ?></h3>
                        <div class="flex flex-wrap items-center gap-1 mb-2">
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
                        <div class="text-lg font-bold text-gray-900">
                            ₹<?php echo e(number_format($product->price ?? 0, 2)); ?>

                            <?php if($product->sale_price && $product->sale_price < $product->price): ?>
                                <span class="text-sm text-red-600 line-through ml-2">₹<?php echo e(number_format($product->sale_price, 2)); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Product Details Grid -->
                <div class="grid grid-cols-2 gap-3 mb-4 text-sm">
                    <div>
                        <span class="text-gray-500 font-medium">SKU:</span>
                        <div class="mt-1">
                            <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-bold bg-gradient-to-r from-blue-100 to-cyan-100 text-blue-700 border border-blue-300">
                                <?php echo e($product->sku ?? 'N/A'); ?>

                            </span>
                        </div>
                    </div>
                    <div>
                        <span class="text-gray-500 font-medium">Category:</span>
                        <div class="mt-1">
                            <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-bold bg-gradient-to-r from-indigo-100 to-purple-100 text-indigo-700 border border-indigo-300">
                                <?php echo e($product->category?->name ?? 'Uncategorized'); ?>

                            </span>
                        </div>
                    </div>
                    <div>
                        <span class="text-gray-500 font-medium">Stock:</span>
                        <div class="mt-1 flex items-center space-x-2">
                            <span class="text-sm font-bold text-gray-900"><?php echo e($product->stock ?? 0); ?></span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                <?php echo e($product->stock_status === 'in_stock' ? 'bg-green-100 text-green-800 border border-green-300' : 'bg-red-100 text-red-800 border border-red-300'); ?>">
                                <?php echo e($product->stock_status === 'in_stock' ? '📦 In Stock' : '❌ Out of Stock'); ?>

                            </span>
                        </div>
                    </div>
                    <div>
                        <span class="text-gray-500 font-medium">Status:</span>
                        <div class="mt-1">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                <?php echo e($product->status === 'active' ? 'bg-green-100 text-green-800 border border-green-300' : 'bg-gray-100 text-gray-800 border border-gray-300'); ?>">
                                <?php echo e(ucfirst($product->status)); ?>

                            </span>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-between pt-3 border-t border-gray-200">
                    <div class="flex items-center space-x-2">
                        <button class="view-btn inline-flex items-center justify-center w-8 h-8 text-blue-600 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition-all"
                                title="View Product"
                                data-id="<?php echo e($product->id); ?>">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                        <button class="edit-btn inline-flex items-center justify-center w-8 h-8 text-indigo-600 bg-indigo-50 border border-indigo-200 rounded-lg hover:bg-indigo-100 transition-all"
                                title="Edit Product"
                                data-id="<?php echo e($product->id); ?>">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                        <button class="copy-btn inline-flex items-center justify-center w-8 h-8 text-cyan-600 bg-cyan-50 border border-cyan-200 rounded-lg hover:bg-cyan-100 transition-all"
                                title="Duplicate Product"
                                data-id="<?php echo e($product->id); ?>"
                                data-name="<?php echo e($product->name); ?>">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                        </button>
                        <button type="button" class="delete-btn inline-flex items-center justify-center w-8 h-8 text-red-600 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition-all" 
                                title="Delete Product"
                                data-id="<?php echo e($product->id); ?>"
                                data-name="<?php echo e($product->name); ?>">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                    <div class="text-xs text-gray-500">
                        ID: #<?php echo e($product->id); ?>

                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="bg-white rounded-xl shadow-md border border-gray-200 p-8">
                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center w-20 h-20 bg-gradient-to-br from-indigo-100 to-purple-200 rounded-full mb-4">
                        <svg class="w-10 h-10 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">No Products Found</h3>
                    <p class="text-gray-500 mb-4 text-sm">Start building your inventory by adding your first product.</p>
                    <a href="<?php echo e(route('seller.products.create.step1')); ?>" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-sm font-bold rounded-lg hover:from-indigo-700 hover:to-purple-700 shadow-lg transition-all">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Create First Product
                    </a>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Table Footer -->
        <div class="bg-gradient-to-r from-gray-50 to-slate-50 px-6 py-4 border-t border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="dataTables_info text-sm font-medium text-gray-700">
                    Showing <span class="font-bold text-indigo-600"><?php echo e($products->firstItem() ?? 0); ?></span> to <span class="font-bold text-indigo-600"><?php echo e($products->lastItem() ?? 0); ?></span> of <span class="font-bold text-indigo-600"><?php echo e($products->total()); ?></span> entries
                </div>
                <div class="dataTables_paginate">
                    <?php echo e($products->appends(request()->query())->links('pagination::tailwind')); ?>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Product View Modal -->
<?php echo $__env->make('seller.products.modal-view', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
/**
 * Products Management Script for Seller Panel
 * Enterprise-level JavaScript functionality for the products management page
 */

// Route templates
const editRouteTemplate = '<?php echo e(route("seller.products.edit", ":id")); ?>';

document.addEventListener('DOMContentLoaded', function() {
    // Initialize components
    initializeSelectionControls();
    initializeBulkActions();
    initializeToggleButtons();
    initializeActionButtons();
    initializeFilters();
    initializeExport();
    initializeModal();
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
        
        // Reset bulk action select if no items selected
        if (!hasSelection) {
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
    
    if (applyBulkActionBtn) {
        applyBulkActionBtn.addEventListener('click', function() {
            const action = document.getElementById('bulkAction').value;
            const selectedIds = Array.from(document.querySelectorAll('.row-select:checked'))
                .map(cb => cb.value);
            
            if (!action || selectedIds.length === 0) {
                showToast('Please select an action and at least one product.', 'error');
                return;
            }

            // Confirm destructive actions
            if (action === 'delete') {
                const confirmMessage = `Are you sure you want to delete ${selectedIds.length} product(s)? This action cannot be undone.`;
                if (!confirm(confirmMessage)) {
                    return;
                }
            }

            performBulkAction(action, selectedIds);
        });
    }
}

async function performBulkAction(action, productIds) {
    showLoading('Processing bulk action...');
    
    try {
        const response = await fetch(`/seller/products/bulk-action`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                action: action,
                ids: productIds
            })
        });

        const data = await response.json();

        if (data.success) {
            showToast(data.message, 'success');
            
            // Update UI based on action
            updateRowsAfterBulkAction(action, productIds);
            
            // Reset selections
            resetSelections();
        } else {
            showToast(data.message || 'Bulk action failed', 'error');
        }
    } catch (error) {
        showToast('An error occurred while performing bulk action.', 'error');
    } finally {
        hideLoading();
    }
}

function updateRowsAfterBulkAction(action, productIds) {
    productIds.forEach(id => {
        const row = document.querySelector(`tr[data-id="${id}"]`);
        if (!row) return;

        switch (action) {
            case 'delete':
                row.remove();
                break;
            case 'activate':
                updateRowStatus(row, 'active');
                break;
            case 'deactivate':
                updateRowStatus(row, 'inactive');
                break;
            case 'feature':
                updateRowFeatureStatus(row, true);
                break;
            case 'unfeature':
                updateRowFeatureStatus(row, false);
                break;
            case 'in_stock':
                updateRowStockStatus(row, 'in_stock');
                break;
            case 'out_of_stock':
                updateRowStockStatus(row, 'out_of_stock');
                break;
        }
    });
    
    // Refresh page to update statistics
    setTimeout(() => window.location.reload(), 1500);
}

// Toggle Buttons (Status and Featured)
function initializeToggleButtons() {
    // Status toggles
    document.querySelectorAll('.status-toggle').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const productId = this.dataset.id;
            const isActive = this.checked;
            updateProductField(productId, 'status', isActive ? 'active' : 'inactive', this);
        });
    });

    // Featured toggles
    document.querySelectorAll('.featured-toggle').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const productId = this.dataset.id;
            const isFeatured = this.checked;
            updateProductField(productId, 'is_featured', isFeatured, this);
        });
    });
}

async function updateProductField(productId, field, value, toggleElement) {
    const originalState = toggleElement.checked;
    
    try {
        // Get CSRF token from meta tag
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (!csrfToken) {
            throw new Error('CSRF token not found');
        }

        const response = await fetch(`/seller/products/${productId}/toggle`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                field: field,
                value: value
            })
        });

        // Check if response is JSON
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            throw new Error('Server returned non-JSON response');
        }

        const data = await response.json();

        if (data.success) {
            showToast(data.message, 'success');
            
            // Update row data attributes
            const row = document.querySelector(`tr[data-id="${productId}"]`);
            if (row) {
                if (field === 'status') {
                    row.dataset.status = value;
                } else if (field === 'is_featured') {
                    row.dataset.featured = value ? '1' : '0';
                }
            }
            
            // Update stats counters after delay
            setTimeout(updateStatsCounters, 500);
        } else {
            // Revert toggle state on error
            toggleElement.checked = !originalState;
            showToast(data.message || 'Update failed', 'error');
        }
    } catch (error) {
        // Revert toggle state
        toggleElement.checked = !originalState;
        showToast('An error occurred while updating the product: ' + error.message, 'error');
        console.error('Toggle error:', error);
    }
}

// Action Buttons (View, Edit, Delete)
function initializeActionButtons() {
    // View buttons - Open modal
    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.dataset.id;
            openProductModal(productId);
        });
    });

    // Edit buttons
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.dataset.id;
            window.location.href = editRouteTemplate.replace(':id', productId);
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

    // Copy buttons
    document.querySelectorAll('.copy-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.dataset.id;
            const productName = this.dataset.name;
            
            if (confirm(`Are you sure you want to duplicate "${productName}"?\n\nA copy will be created with "(Copy)" appended to the name and set to Draft status.`)) {
                copyProduct(productId);
            }
        });
    });
}

async function copyProduct(productId) {
    showLoading('Duplicating product...');
    
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
        const response = await fetch(`/seller/products/${productId}/copy`, {
            method: 'POST',
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
            setTimeout(() => window.location.reload(), 1500);
        } else {
            showToast(data.message || 'Copy failed', 'error');
        }
    } catch (error) {
        showToast('An error occurred while copying the product: ' + error.message, 'error');
        console.error('Copy error:', error);
    } finally {
        hideLoading();
    }
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

// Filters
function initializeFilters() {
    const globalSearch = document.getElementById('globalSearch');
    const statusFilter = document.getElementById('statusFilter');
    const approvalFilter = document.getElementById('approvalFilter');
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
    statusFilter?.addEventListener('change', () => document.getElementById('filtersForm').submit());
    approvalFilter?.addEventListener('change', () => document.getElementById('filtersForm').submit());
    categoryFilter?.addEventListener('change', () => document.getElementById('filtersForm').submit());
    itemsPerPage?.addEventListener('change', () => document.getElementById('filtersForm').submit());

    // Reset Logic
    resetFiltersBtn?.addEventListener('click', function() {
        window.location.href = '<?php echo e(route("seller.products.index")); ?>';
    });

    // Refresh Button
    refreshBtn?.addEventListener('click', function() {
        window.location.reload();
    });
}

// Export functionality
function initializeExport() {
    const exportBtn = document.getElementById('exportBtn');
    
    if (exportBtn) {
        exportBtn.addEventListener('click', async function() {
            showLoading('Preparing export...');
            
            try {
                // Get current filters
                const params = new URLSearchParams(window.location.search).toString();
                
                // Trigger download
                window.location.href = `/seller/products/export?${params}`;
                
                showToast('Export started! Download will begin shortly.', 'success');
            } catch (error) {
                showToast('Export failed. Please try again.', 'error');
            } finally {
                setTimeout(hideLoading, 1000);
            }
        });
    }
}

// Utility Functions
function updateRowStatus(row, status) {
    row.dataset.status = status;
    const toggle = row.querySelector('.status-toggle');
    if (toggle) {
        toggle.checked = status === 'active';
    }
}

function updateRowFeatureStatus(row, featured) {
    row.dataset.featured = featured ? '1' : '0';
    const toggle = row.querySelector('.featured-toggle');
    if (toggle) {
        toggle.checked = featured;
    }
}

function updateRowStockStatus(row, stockStatus) {
    row.dataset.stock = stockStatus;
    const stockBadge = row.querySelector('td:nth-child(7) span:last-child');
    if (stockBadge) {
        if (stockStatus === 'in_stock') {
            stockBadge.textContent = '📦 In Stock';
            stockBadge.className = 'inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium mt-1 bg-green-100 text-green-800 border border-green-300';
        } else {
            stockBadge.textContent = '❌ Out of Stock';
            stockBadge.className = 'inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium mt-1 bg-red-100 text-red-800 border border-red-300';
        }
    }
}

function updateStatsCounters() {
    // Count products by status from visible rows
    const allRows = document.querySelectorAll('tr[data-id]');
    
    let activeCount = 0;
    let featuredCount = 0;
    
    allRows.forEach(row => {
        if (row.dataset.status === 'active') {
            activeCount++;
        }
        if (row.dataset.featured === '1') {
            featuredCount++;
        }
    });
    
    // Update stat boxes
    const activeProductsEl = document.querySelector('.from-emerald-500.to-green-600 p.text-4xl');
    const featuredProductsEl = document.querySelector('.from-amber-500.to-orange-600 p.text-4xl');
    
    if (activeProductsEl) {
        activeProductsEl.textContent = activeCount;
    }
    
    if (featuredProductsEl) {
        featuredProductsEl.textContent = featuredCount;
    }
}

function resetSelections() {
    const selectAll = document.getElementById('selectAll');
    const bulkAction = document.getElementById('bulkAction');
    const applyBulkAction = document.getElementById('applyBulkAction');
    const selectedCount = document.getElementById('selectedCount');
    
    if (selectAll) selectAll.checked = false;
    if (selectAll) selectAll.indeterminate = false;
    document.querySelectorAll('.row-select').forEach(cb => cb.checked = false);
    if (selectedCount) selectedCount.textContent = '0';
    if (bulkAction) bulkAction.value = '';
    if (applyBulkAction) applyBulkAction.disabled = true;
}

function showLoading(message = 'Loading...') {
    // Create loading overlay if it doesn't exist
    let overlay = document.getElementById('loadingOverlay');
    if (!overlay) {
        overlay = document.createElement('div');
        overlay.id = 'loadingOverlay';
        overlay.className = 'fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50';
        overlay.innerHTML = `
            <div class="bg-white rounded-lg p-6 flex items-center space-x-4 shadow-xl">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
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
    // Create toast container if it doesn't exist
    let container = document.getElementById('toastContainer');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toastContainer';
        container.className = 'fixed top-4 right-4 z-50 space-y-2';
        document.body.appendChild(container);
    }

    // Create toast
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

    // Animate in
    setTimeout(() => {
        toast.classList.remove('translate-x-full');
    }, 100);

    // Auto remove after 5 seconds
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
    
    // Close modal handlers
    closeButtons.forEach(btn => {
        if (btn) {
            btn.addEventListener('click', closeProductModal);
        }
    });
    
    // Close on overlay click
    if (modalOverlay) {
        modalOverlay.addEventListener('click', closeProductModal);
    }
    
    // ESC key handler
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
    
    // Show modal with loading state
    modal.classList.remove('hidden');
    if (modalLoading) modalLoading.classList.remove('hidden');
    if (productContent) productContent.classList.add('hidden');
    
    // Set current timestamp
    const timestampEl = document.getElementById('viewTimestamp');
    if (timestampEl) timestampEl.textContent = new Date().toLocaleString();
    
    // Fetch product data
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
    // Product basic info
    const nameElement = document.getElementById('modalProductName');
    const skuElement = document.getElementById('modalProductSku');
    const idElement = document.getElementById('modalProductId');
    
    if (nameElement) nameElement.textContent = product.name || 'Unnamed Product';
    if (skuElement) skuElement.textContent = product.sku ? `SKU: ${product.sku}` : 'No SKU';
    if (idElement) idElement.textContent = `#${product.id}`;
    
    // Pricing
    const priceEl = document.getElementById('modalProductPrice');
    if (priceEl) {
        priceEl.textContent = `₹${parseFloat(product.price || 0).toLocaleString('en-IN', {minimumFractionDigits: 2})}`;
    }
    
    // Stock
    const stockEl = document.getElementById('modalProductStock');
    const stockStatusEl = document.getElementById('modalProductStockStatus');
    if (stockEl) stockEl.textContent = product.stock || 0;
    if (stockStatusEl) {
        stockStatusEl.textContent = product.stock_status === 'in_stock' ? '📦 In Stock' : '❌ Out of Stock';
        stockStatusEl.className = product.stock_status === 'in_stock' ? 'text-green-600' : 'text-red-600';
    }
    
    // Status
    const statusElement = document.getElementById('modalProductStatus');
    if (statusElement) {
        const status = product.status;
        if (status === 'active') {
            statusElement.textContent = '✅ Active';
            statusElement.className = 'text-green-600 font-bold';
        } else if (status === 'inactive') {
            statusElement.textContent = '❌ Inactive';
            statusElement.className = 'text-red-600 font-bold';
        } else {
            statusElement.textContent = '📝 Draft';
            statusElement.className = 'text-yellow-600 font-bold';
        }
    }
    
    // Category
    const categoryEl = document.getElementById('modalProductCategory');
    if (categoryEl) {
        categoryEl.textContent = product.category?.name || 'Uncategorized';
    }
    
    // Description
    const descEl = document.getElementById('modalProductDescription');
    if (descEl) {
        descEl.innerHTML = product.description || '<em class="text-gray-500">No description available</em>';
    }
    
    // Main image
    const mainImageEl = document.getElementById('modalProductMainImage');
    if (mainImageEl) {
        if (product.image) {
            mainImageEl.src = product.image;
            mainImageEl.alt = product.name;
        } else {
            mainImageEl.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzIwIiBoZWlnaHQ9IjMwMCIgdmlld0JveD0iMCAwIDMyMCAzMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIzMjAiIGhlaWdodD0iMzAwIiBmaWxsPSIjRjNGNEY2Ii8+CjxwYXRoIGQ9Ik0xNjAgMTIwQzE3MS4wNDYgMTIwIDE4MCAzMTEuMDQ2IDE4MCAzMjJDMTgwIDEzMi45NTQgMTcxLjA0NiAxNDQgMTYwIDE0NEMxNDguOTU0IDE0NCAxNDAgMTMyLjk1NCAxNDAgMTIyQzE0MCAxMTEuMDQ2IDE0OC45NTQgMTAwIDE2MCAxMDBaIiBmaWxsPSIjOUNBM0FGIi8+CjxwYXRoIGQ9Ik0xMDAgMTgwQzEwNSAxODAgMTA5IDIwNS4yMyAxMDkgMjE2QzEwOSAyMjYuNzcgMTA1IDIzMiAxMDAgMjMySDIyMEMyMTUgMjMyIDIxMSAyMjYuNzcgMjExIDIxNkMyMTEgMjA1LjIzIDIxNSAxODAgMjIwIDE4MEgxMDBaIiBmaWxsPSIjOUNBM0FGIi8+Cjwvc3ZnPgo=';
            mainImageEl.alt = 'No image available';
        }
    }
    
    // Setup image gallery if multiple images
    setupModalImageGallery(product);
}

function setupModalImageGallery(product) {
    const thumbnailsContainer = document.getElementById('modalImageThumbnails');
    if (!thumbnailsContainer) return;
    
    thumbnailsContainer.innerHTML = '';
    
    const images = [];
    if (product.image) images.push(product.image);
    if (product.images && Array.isArray(product.images)) {
        product.images.forEach(img => {
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
                index === 0 ? 'border-indigo-500' : 'border-gray-200 hover:border-indigo-300'
            }`;
            thumbnail.addEventListener('click', () => {
                const mainImageEl = document.getElementById('modalProductMainImage');
                if (mainImageEl) mainImageEl.src = image;
                
                // Update thumbnail borders
                thumbnailsContainer.querySelectorAll('img').forEach((thumb, i) => {
                    thumb.className = `w-16 h-16 object-cover rounded-lg cursor-pointer border-2 transition-all duration-200 ${
                        i === index ? 'border-indigo-500' : 'border-gray-200 hover:border-indigo-300'
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
<?php $__env->stopPush(); ?>

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
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('seller.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\ESS_Project1\resources\views/seller/products/index.blade.php ENDPATH**/ ?>