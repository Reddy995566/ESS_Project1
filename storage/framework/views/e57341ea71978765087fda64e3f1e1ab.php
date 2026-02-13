<?php
    // Auto-detect active menu from current route
    $routeName = request()->route()->getName() ?? '';
    $activeMenu = 'dashboard';
    $productMenuOpen = false;

    if (str_contains($routeName, 'dashboard')) {
        $activeMenu = 'dashboard';
    } elseif (str_contains($routeName, 'product') || str_contains($routeName, 'products')) {
        $activeMenu = 'products';
        $productMenuOpen = true;
    } elseif (str_contains($routeName, 'categories') || str_starts_with($routeName, 'seller.categories')) {
        $activeMenu = 'products';
        $productMenuOpen = true;
    } elseif (str_contains($routeName, 'colors') || str_starts_with($routeName, 'seller.colors')) {
        $activeMenu = 'products';
        $productMenuOpen = true;
    } elseif (str_contains($routeName, 'fabrics') || str_starts_with($routeName, 'seller.fabrics')) {
        $activeMenu = 'products';
        $productMenuOpen = true;
    } elseif (str_contains($routeName, 'sizes') || str_starts_with($routeName, 'seller.sizes')) {
        $activeMenu = 'products';
        $productMenuOpen = true;
    } elseif (str_contains($routeName, 'brands') || str_starts_with($routeName, 'seller.brands')) {
        $activeMenu = 'products';
        $productMenuOpen = true;
    } elseif (str_contains($routeName, 'collections') || str_starts_with($routeName, 'seller.collections')) {
        $activeMenu = 'products';
        $productMenuOpen = true;
    } elseif (str_contains($routeName, 'tags') || str_starts_with($routeName, 'seller.tags')) {
        $activeMenu = 'products';
        $productMenuOpen = true;
    } elseif (str_contains($routeName, 'order')) {
        $activeMenu = 'orders';
    } elseif (str_contains($routeName, 'wallet')) {
        $activeMenu = 'wallet';
    } elseif (str_contains($routeName, 'returns')) {
        $activeMenu = 'returns';
    } elseif (str_contains($routeName, 'payout')) {
        $activeMenu = 'payouts';
    // } elseif (str_contains($routeName, 'analytics')) {
    //     $activeMenu = 'analytics';
    } elseif (str_contains($routeName, 'notification')) {
        $activeMenu = 'notifications';
    // } elseif (str_contains($routeName, 'documents')) {
    //     $activeMenu = 'documents';
    } elseif (str_contains($routeName, 'setting')) {
        $activeMenu = 'settings';
    } elseif (str_contains($routeName, 'profile')) {
        $activeMenu = 'profile';
    }
?>

<div class="w-64 h-screen flex flex-col bg-gray-50 rounded-r-3xl relative"
    style="box-shadow: 8px 0 20px rgba(0, 0, 0, 0.08), 12px 0 35px rgba(0, 0, 0, 0.06), 16px 0 50px rgba(0, 0, 0, 0.04); z-index: 50;">
    <!-- Logo/Header -->
    <div class="px-6 py-6 bg-white">
        <div class="flex items-center space-x-3">
            <div class="w-12 h-12 bg-gradient-to-br from-indigo-600 to-indigo-500 rounded-2xl flex items-center justify-center">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-bold text-gray-900">
                    <?php echo e(config('app.name')); ?>

                </h1>
                <p class="text-xs text-gray-500">Seller Portal</p>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto py-4 px-4">
        <ul class="space-y-1">
            <!-- Dashboard -->
            <li>
                <a href="<?php echo e(route('seller.dashboard')); ?>"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg <?php echo e($activeMenu === 'dashboard' ? 'bg-indigo-600 text-white' : 'text-gray-700 hover:bg-white'); ?> transition-all duration-150">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="text-sm font-medium">Dashboard</span>
                </a>
            </li>

            <!-- Product Management -->
            <li>
                <button onclick="toggleProductMenu()"
                    class="flex items-center justify-between w-full px-4 py-3 rounded-lg <?php echo e($activeMenu === 'products' ? 'bg-indigo-600 text-white' : 'text-gray-700 hover:bg-white'); ?> transition-all duration-150">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        <span class="text-sm font-medium">Products</span>
                    </div>
                    <svg id="productMenuIcon"
                        class="w-4 h-4 transition-transform duration-200 <?php echo e($productMenuOpen ? 'rotate-180' : ''); ?>"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- Submenu -->
                <ul id="productSubmenu" class="mt-2 space-y-1 <?php echo e($productMenuOpen ? '' : 'hidden'); ?>">
                    <li>
                        <a href="<?php echo e(route('seller.products.index')); ?>"
                            class="flex items-center space-x-3 px-4 py-2.5 pl-12 rounded-lg text-sm <?php echo e(request()->routeIs('seller.products.index') ? 'bg-indigo-500 text-white' : 'text-gray-700 hover:bg-white hover:text-gray-900'); ?> transition-all duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                            </svg>
                            <span class="font-medium">All Products</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('seller.products.create')); ?>"
                            class="flex items-center space-x-3 px-4 py-2.5 pl-12 rounded-lg text-sm <?php echo e(request()->routeIs('seller.products.create') ? 'bg-indigo-500 text-white' : 'text-gray-700 hover:bg-white hover:text-gray-900'); ?> transition-all duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            <span class="font-medium">Add Product</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('seller.products.pending')); ?>"
                            class="flex items-center space-x-3 px-4 py-2.5 pl-12 rounded-lg text-sm <?php echo e(request()->routeIs('seller.products.pending') ? 'bg-indigo-500 text-white' : 'text-gray-700 hover:bg-white hover:text-gray-900'); ?> transition-all duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="font-medium">Pending Approval</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('seller.products.rejected')); ?>"
                            class="flex items-center space-x-3 px-4 py-2.5 pl-12 rounded-lg text-sm <?php echo e(request()->routeIs('seller.products.rejected') ? 'bg-indigo-500 text-white' : 'text-gray-700 hover:bg-white hover:text-gray-900'); ?> transition-all duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            <span class="font-medium">Rejected</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('seller.categories.index')); ?>"
                            class="flex items-center space-x-3 px-4 py-2.5 pl-12 rounded-lg text-sm <?php echo e(request()->routeIs('seller.categories.*') ? 'bg-indigo-500 text-white' : 'text-gray-700 hover:bg-white hover:text-gray-900'); ?> transition-all duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            <span class="font-medium">Categories</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('seller.colors.index')); ?>"
                            class="flex items-center space-x-3 px-4 py-2.5 pl-12 rounded-lg text-sm <?php echo e(request()->routeIs('seller.colors.*') ? 'bg-indigo-500 text-white' : 'text-gray-700 hover:bg-white hover:text-gray-900'); ?> transition-all duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                            </svg>
                            <span class="font-medium">Colors</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('seller.fabrics.index')); ?>"
                            class="flex items-center space-x-3 px-4 py-2.5 pl-12 rounded-lg text-sm <?php echo e(request()->routeIs('seller.fabrics.*') ? 'bg-indigo-500 text-white' : 'text-gray-700 hover:bg-white hover:text-gray-900'); ?> transition-all duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                            </svg>
                            <span class="font-medium">Fabrics</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('seller.sizes.index')); ?>"
                            class="flex items-center space-x-3 px-4 py-2.5 pl-12 rounded-lg text-sm <?php echo e(request()->routeIs('seller.sizes.*') ? 'bg-indigo-500 text-white' : 'text-gray-700 hover:bg-white hover:text-gray-900'); ?> transition-all duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                            </svg>
                            <span class="font-medium">Sizes</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('seller.brands.index')); ?>"
                            class="flex items-center space-x-3 px-4 py-2.5 pl-12 rounded-lg text-sm <?php echo e(request()->routeIs('seller.brands.*') ? 'bg-indigo-500 text-white' : 'text-gray-700 hover:bg-white hover:text-gray-900'); ?> transition-all duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                            </svg>
                            <span class="font-medium">Brands</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('seller.collections.index')); ?>"
                            class="flex items-center space-x-3 px-4 py-2.5 pl-12 rounded-lg text-sm <?php echo e(request()->routeIs('seller.collections.*') ? 'bg-indigo-500 text-white' : 'text-gray-700 hover:bg-white hover:text-gray-900'); ?> transition-all duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            <span class="font-medium">Collections</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('seller.tags.index')); ?>"
                            class="flex items-center space-x-3 px-4 py-2.5 pl-12 rounded-lg text-sm <?php echo e(request()->routeIs('seller.tags.*') ? 'bg-indigo-500 text-white' : 'text-gray-700 hover:bg-white hover:text-gray-900'); ?> transition-all duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            <span class="font-medium">Tags</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Orders -->
            <li>
                <a href="<?php echo e(route('seller.orders.index')); ?>"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg <?php echo e($activeMenu === 'orders' ? 'bg-indigo-600 text-white' : 'text-gray-700 hover:bg-white'); ?> transition-all duration-150">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                    <span class="text-sm font-medium">Orders</span>
                </a>
            </li>

            <!-- Cancelled Orders -->
            <li>
                <a href="<?php echo e(route('seller.orders.cancelled')); ?>"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg <?php echo e(request()->routeIs('seller.orders.cancelled') ? 'bg-indigo-600 text-white' : 'text-gray-700 hover:bg-white'); ?> transition-all duration-150">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-sm font-medium">Cancelled Orders</span>
                </a>
            </li>

            <!-- Wallet -->
            <li>
                <a href="<?php echo e(route('seller.wallet.index')); ?>"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg <?php echo e($activeMenu === 'wallet' ? 'bg-indigo-600 text-white' : 'text-gray-700 hover:bg-white'); ?> transition-all duration-150">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="text-sm font-medium">Wallet</span>
                    <span class="ml-auto bg-green-100 text-green-800 text-xs font-bold px-2 py-1 rounded-full">
                        ₹<?php echo e(number_format(auth('seller')->user()->wallet->balance ?? 0, 0)); ?>

                    </span>
                </a>
            </li>

            <!-- Returns -->
            <li>
                <a href="<?php echo e(route('seller.returns.index')); ?>"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg <?php echo e($activeMenu === 'returns' ? 'bg-indigo-600 text-white' : 'text-gray-700 hover:bg-white'); ?> transition-all duration-150">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z" />
                    </svg>
                    <span class="text-sm font-medium">Returns</span>
                </a>
            </li>

            <!-- Payouts & Commissions -->
            <li>
                <a href="<?php echo e(route('seller.payouts.index')); ?>"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg <?php echo e($activeMenu === 'payouts' ? 'bg-indigo-600 text-white' : 'text-gray-700 hover:bg-white'); ?> transition-all duration-150">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-sm font-medium">Payouts</span>
                </a>
            </li>

            <!-- Analytics -->
            

            <!-- Notifications -->
            <li>
                <a href="<?php echo e(route('seller.notifications.index')); ?>"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg <?php echo e($activeMenu === 'notifications' ? 'bg-indigo-600 text-white' : 'text-gray-700 hover:bg-white'); ?> transition-all duration-150">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <span class="text-sm font-medium">Notifications</span>
                    <?php
                        $unreadCount = auth('seller')->user() ? auth('seller')->user()->notifications()->where('is_read', false)->count() : 0;
                    ?>
                    <?php if($unreadCount > 0): ?>
                        <span class="ml-auto bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                            <?php echo e($unreadCount); ?>

                        </span>
                    <?php endif; ?>
                </a>
            </li>

            <!-- Document Verification -->
            

            <!-- Settings -->
            <li>
                <a href="<?php echo e(route('seller.settings.index')); ?>"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg <?php echo e($activeMenu === 'settings' ? 'bg-indigo-600 text-white' : 'text-gray-700 hover:bg-white'); ?> transition-all duration-150">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="text-sm font-medium">Settings</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- User Profile & Logout -->
    <div class="border-t border-gray-100 p-4">
        <div class="flex items-center space-x-3 px-3 py-3 mb-3 bg-gray-50 rounded-xl">
            <div class="w-11 h-11 bg-gradient-to-br from-indigo-600 to-indigo-500 rounded-xl flex items-center justify-center shadow-sm">
                <span class="text-white font-bold text-base">
                    <?php echo e(auth('seller')->user() ? substr(auth('seller')->user()->business_name, 0, 1) : 'S'); ?>

                </span>
            </div>
            <div class="flex-1">
                <p class="text-sm font-bold text-gray-900">
                    <?php echo e(auth('seller')->user() ? auth('seller')->user()->business_name : 'Seller'); ?>

                </p>
                <p class="text-xs text-gray-500 truncate">
                    <?php echo e(auth('seller')->user() ? auth('seller')->user()->user->email : ''); ?>

                </p>
            </div>
        </div>
        <a href="<?php echo e(route('seller.logout')); ?>"
            class="flex items-center justify-center space-x-2 w-full px-4 py-2.5 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition-all duration-150">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            <span class="text-sm font-medium">Logout</span>
        </a>
    </div>
</div>

<script>
    function toggleProductMenu() {
        const submenu = document.getElementById('productSubmenu');
        const icon = document.getElementById('productMenuIcon');
        submenu.classList.toggle('hidden');
        icon.classList.toggle('rotate-180');
    }
</script>
<?php /**PATH C:\xampp\htdocs\ESS_Project1\resources\views/seller/components/sidebar.blade.php ENDPATH**/ ?>