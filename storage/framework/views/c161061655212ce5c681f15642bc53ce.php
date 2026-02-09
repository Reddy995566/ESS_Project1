<?php
    // Auto-detect active menu from current route
    $routeName = request()->route()->getName() ?? '';
    $activeMenu = 'dashboard';
    $productMenuOpen = false;
    $sellerMenuOpen = false;

    if (str_contains($routeName, 'dashboard')) {
        $activeMenu = 'dashboard';
    } elseif (str_contains($routeName, 'seller') || str_starts_with($routeName, 'admin.sellers') || str_contains($routeName, 'pending-products') || str_contains($routeName, 'approved-products') || str_contains($routeName, 'rejected-products') || str_contains($routeName, 'document-verification')) {
        $activeMenu = 'sellers';
        $sellerMenuOpen = true;
    } elseif (str_contains($routeName, 'product') || str_contains($routeName, 'products')) {
        $activeMenu = 'products';
        $productMenuOpen = true;
    } elseif (str_contains($routeName, 'categories') || str_starts_with($routeName, 'admin.categories')) {
        $activeMenu = 'products';
        $productMenuOpen = true;
    } elseif (str_contains($routeName, 'colors') || str_starts_with($routeName, 'admin.colors')) {
        $activeMenu = 'products';
        $productMenuOpen = true;
    } elseif (str_contains($routeName, 'fabrics') || str_starts_with($routeName, 'admin.fabrics')) {
        $activeMenu = 'products';
        $productMenuOpen = true;
    } elseif (str_contains($routeName, 'sizes') || str_starts_with($routeName, 'admin.sizes')) {
        $activeMenu = 'products';
        $productMenuOpen = true;
    } elseif (str_contains($routeName, 'brands') || str_starts_with($routeName, 'admin.brands')) {
        $activeMenu = 'products';
        $productMenuOpen = true;
    } elseif (str_contains($routeName, 'collections') || str_starts_with($routeName, 'admin.collections')) {
        $activeMenu = 'products';
        $productMenuOpen = true;
    } elseif (str_contains($routeName, 'tags') || str_starts_with($routeName, 'admin.tags')) {
        $activeMenu = 'products';
        $productMenuOpen = true;
    } elseif (str_contains($routeName, 'bulk-order') || str_starts_with($routeName, 'admin.bulk-orders')) {
        $activeMenu = 'bulk-orders';
    } elseif (str_contains($routeName, 'contact') || str_starts_with($routeName, 'admin.contacts')) {
        $activeMenu = 'contacts';
    } elseif (str_contains($routeName, 'order')) {
        $activeMenu = 'orders';
    } elseif (str_contains($routeName, 'user') || str_starts_with($routeName, 'admin.users')) {
        $activeMenu = 'users';
    } elseif (str_contains($routeName, 'coupon') || str_starts_with($routeName, 'admin.coupons')) {
        $activeMenu = 'coupons';
    } elseif (str_contains($routeName, 'banner') || str_starts_with($routeName, 'admin.banners')) {
        $activeMenu = 'banners';
    } elseif (str_contains($routeName, 'admin-user') || str_contains($routeName, 'activity-log') || str_contains($routeName, 'login-history')) {
        $activeMenu = 'admin-management';
    } elseif (str_contains($routeName, 'customer')) {
        $activeMenu = 'customers';
    } elseif (str_contains($routeName, 'analytics')) {
        $activeMenu = 'analytics';
    } elseif (str_contains($routeName, 'review')) {
        $activeMenu = 'reviews';
    } elseif (str_contains($routeName, 'testimonial') || str_starts_with($routeName, 'admin.testimonials')) {
        $activeMenu = 'testimonials';
    } elseif (str_contains($routeName, 'video-reel') || str_starts_with($routeName, 'admin.video-reels')) {
        $activeMenu = 'video-reels';
    } elseif (str_contains($routeName, 'promotional-banner') || str_starts_with($routeName, 'admin.promotional-banners')) {
        $activeMenu = 'promotional-banners';
    } elseif (str_contains($routeName, 'budget-card') || str_starts_with($routeName, 'admin.budget-cards')) {
        $activeMenu = 'budget-cards';
    } elseif (str_contains($routeName, 'seller-payout') || str_contains($routeName, 'payouts')) {
        $activeMenu = 'seller-payouts';
    } elseif (str_contains($routeName, 'setting')) {
        $activeMenu = 'settings';
    }
?>

<div class="w-64 h-screen flex flex-col bg-gray-50 rounded-r-3xl relative"
    style="box-shadow: 8px 0 20px rgba(0, 0, 0, 0.08), 12px 0 35px rgba(0, 0, 0, 0.06), 16px 0 50px rgba(0, 0, 0, 0.04); z-index: 50;">
    <!-- Logo/Header -->
    <div class="px-6 py-6 bg-white">
        <div class="flex items-center space-x-3">
            <div
                class="w-12 h-12 bg-gradient-to-br from-gray-800 to-black rounded-2xl flex items-center justify-center">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-bold text-gray-900">
                    <?php echo e(config('app.name')); ?>

                </h1>
                <p class="text-xs text-gray-500">Admin Portal</p>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto py-4 px-4">
        <ul class="space-y-1">
            <!-- Dashboard -->
            <li>
                <a href="<?php echo e(route('admin.dashboard')); ?>"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg <?php echo e($activeMenu === 'dashboard' ? 'bg-black text-white' : 'text-gray-700 hover:bg-white'); ?> transition-all duration-150">
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
                    class="flex items-center justify-between w-full px-4 py-3 rounded-lg <?php echo e($activeMenu === 'products' ? 'bg-black text-white' : 'text-gray-700 hover:bg-white'); ?> transition-all duration-150">
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
                        <a href="<?php echo e(route('admin.products')); ?>"
                            class="flex items-center space-x-3 px-4 py-2.5 pl-12 rounded-lg text-sm <?php echo e(request()->routeIs('admin.products') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-white hover:text-gray-900'); ?> transition-all duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                            </svg>
                            <span class="font-medium">Products List</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('admin.products.create')); ?>"
                            class="flex items-center space-x-3 px-4 py-2.5 pl-12 rounded-lg text-sm <?php echo e(request()->routeIs('admin.products.create') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-white hover:text-gray-900'); ?> transition-all duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            <span class="font-medium">Add Product</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('admin.categories.index')); ?>"
                            class="flex items-center space-x-3 px-4 py-2.5 pl-12 rounded-lg text-sm <?php echo e(request()->routeIs('admin.categories.*') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-white hover:text-gray-900'); ?> transition-all duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            <span class="font-medium">Categories</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('admin.colors.index')); ?>"
                            class="flex items-center space-x-3 px-4 py-2.5 pl-12 rounded-lg text-sm <?php echo e(request()->routeIs('admin.colors.*') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-white hover:text-gray-900'); ?> transition-all duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                            </svg>
                            <span class="font-medium">Colors</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('admin.fabrics.index')); ?>"
                            class="flex items-center space-x-3 px-4 py-2.5 pl-12 rounded-lg text-sm <?php echo e(request()->routeIs('admin.fabrics.*') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-white hover:text-gray-900'); ?> transition-all duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                            </svg>
                            <span class="font-medium">Fabrics</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('admin.sizes.index')); ?>"
                            class="flex items-center space-x-3 px-4 py-2.5 pl-12 rounded-lg text-sm <?php echo e(request()->routeIs('admin.sizes.*') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-white hover:text-gray-900'); ?> transition-all duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                            </svg>
                            <span class="font-medium">Sizes</span>
                        </a>
                    </li>
                    
                    <li>
                        <a href="<?php echo e(route('admin.collections.index')); ?>"
                            class="flex items-center space-x-3 px-4 py-2.5 pl-12 rounded-lg text-sm <?php echo e(request()->routeIs('admin.collections.*') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-white hover:text-gray-900'); ?> transition-all duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            <span class="font-medium">Collections</span>
                        </a>
                    </li>
                    
                </ul>
            </li>


            <!-- Coupons -->
            <li>
                <a href="<?php echo e(route('admin.coupons.index')); ?>"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg <?php echo e($activeMenu === 'coupons' ? 'bg-black text-white' : 'text-gray-700 hover:bg-white'); ?> transition-all duration-150">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                    </svg>
                    <span class="text-sm font-medium">Coupons</span>
                </a>
            </li>

            <!-- Banners -->
            <li>
                <a href="<?php echo e(route('admin.banners.index')); ?>"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg <?php echo e($activeMenu === 'banners' ? 'bg-black text-white' : 'text-gray-700 hover:bg-white'); ?> transition-all duration-150">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="text-sm font-medium">Banners</span>
                </a>
            </li>

            <!-- Lookbooks -->
            

            <!-- Blogs -->
            

            <!-- Orders -->
            <li>
                <a href="<?php echo e(route('admin.orders.index')); ?>"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg <?php echo e($activeMenu === 'orders' ? 'bg-black text-white' : 'text-gray-700 hover:bg-white'); ?> transition-all duration-150">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                    <span class="text-sm font-medium">Orders</span>
                </a>
            </li>

            <!-- Bulk Orders -->
            <li>
                <a href="<?php echo e(route('admin.bulk-orders.index')); ?>"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg <?php echo e($activeMenu === 'bulk-orders' ? 'bg-black text-white' : 'text-gray-700 hover:bg-white'); ?> transition-all duration-150">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="text-sm font-medium">Bulk Orders</span>
                </a>
            </li>

            <!-- Contacts -->
            <li>
                <a href="<?php echo e(route('admin.contacts.index')); ?>"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg <?php echo e($activeMenu === 'contacts' ? 'bg-black text-white' : 'text-gray-700 hover:bg-white'); ?> transition-all duration-150">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <span class="text-sm font-medium">Contact Inquiries</span>
                </a>
            </li>

            <!-- Users Management -->
            <li>
                <a href="<?php echo e(route('admin.users.index')); ?>"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg <?php echo e($activeMenu === 'users' ? 'bg-black text-white' : 'text-gray-700 hover:bg-white'); ?> transition-all duration-150">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span class="text-sm font-medium">Users</span>
                </a>
            </li>

            <!-- Seller Management -->
            <li>
                <button onclick="toggleSellerMenu()"
                    class="flex items-center justify-between w-full px-4 py-3 rounded-lg <?php echo e($activeMenu === 'sellers' ? 'bg-black text-white' : 'text-gray-700 hover:bg-white'); ?> transition-all duration-150">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span class="text-sm font-medium">Sellers</span>
                    </div>
                    <svg id="sellerMenuIcon"
                        class="w-4 h-4 transition-transform duration-200 <?php echo e($sellerMenuOpen ? 'rotate-180' : ''); ?>"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- Submenu -->
                <ul id="sellerSubmenu" class="mt-2 space-y-1 <?php echo e($sellerMenuOpen ? '' : 'hidden'); ?>">
                    <li>
                        <a href="<?php echo e(route('admin.sellers.index')); ?>"
                            class="flex items-center space-x-3 px-4 py-2.5 pl-12 rounded-lg text-sm <?php echo e(request()->routeIs('admin.sellers.index') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-white hover:text-gray-900'); ?> transition-all duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <span class="font-medium">All Sellers</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('admin.sellers.pending-products')); ?>"
                            class="flex items-center space-x-3 px-4 py-2.5 pl-12 rounded-lg text-sm <?php echo e(request()->routeIs('admin.sellers.pending-products') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-white hover:text-gray-900'); ?> transition-all duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="font-medium">Pending Products</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('admin.sellers.approved-products')); ?>"
                            class="flex items-center space-x-3 px-4 py-2.5 pl-12 rounded-lg text-sm <?php echo e(request()->routeIs('admin.sellers.approved-products') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-white hover:text-gray-900'); ?> transition-all duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="font-medium">Approved Products</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('admin.sellers.rejected-products')); ?>"
                            class="flex items-center space-x-3 px-4 py-2.5 pl-12 rounded-lg text-sm <?php echo e(request()->routeIs('admin.sellers.rejected-products') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-white hover:text-gray-900'); ?> transition-all duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="font-medium">Rejected Products</span>
                        </a>
                    </li>
                    
                </ul>
            </li>

            <!-- Seller Payouts -->
            <li>
                <a href="<?php echo e(route('admin.seller-payouts.index')); ?>"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg <?php echo e($activeMenu === 'seller-payouts' ? 'bg-black text-white' : 'text-gray-700 hover:bg-white'); ?> transition-all duration-150">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="text-sm font-medium">Seller Payouts</span>
                </a>
            </li>

            <!-- Returns -->
            <li>
                <a href="<?php echo e(route('admin.returns.index')); ?>"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg <?php echo e($activeMenu === 'returns' ? 'bg-black text-white' : 'text-gray-700 hover:bg-white'); ?> transition-all duration-150">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z" />
                    </svg>
                    <span class="text-sm font-medium">Returns</span>
                </a>
            </li>

            <!-- Reviews -->
            <li>
                <a href="<?php echo e(route('admin.reviews.index')); ?>"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg <?php echo e($activeMenu === 'reviews' ? 'bg-black text-white' : 'text-gray-700 hover:bg-white'); ?> transition-all duration-150">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                    <span class="text-sm font-medium">Reviews</span>
                </a>
            </li>

            <!-- Testimonials -->
            <li>
                <a href="<?php echo e(route('admin.testimonials.index')); ?>"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg <?php echo e($activeMenu === 'testimonials' ? 'bg-black text-white' : 'text-gray-700 hover:bg-white'); ?> transition-all duration-150">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                    <span class="text-sm font-medium">Testimonials</span>
                </a>
            </li>

            <!-- Video Reels -->
            <li>
                <a href="<?php echo e(route('admin.video-reels.index')); ?>"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg <?php echo e($activeMenu === 'video-reels' ? 'bg-black text-white' : 'text-gray-700 hover:bg-white'); ?> transition-all duration-150">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    <span class="text-sm font-medium">Video Reels</span>
                </a>
            </li>

            <!-- Promotional Banners -->
            <li>
                <a href="<?php echo e(route('admin.promotional-banners.index')); ?>"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg <?php echo e($activeMenu === 'promotional-banners' ? 'bg-black text-white' : 'text-gray-700 hover:bg-white'); ?> transition-all duration-150">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span class="text-sm font-medium">Promo Banners</span>
                </a>
            </li>

            <!-- Budget Cards (Shop By Budget) -->
            <li>
                <a href="<?php echo e(route('admin.budget-cards.index')); ?>"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg <?php echo e($activeMenu === 'budget-cards' ? 'bg-black text-white' : 'text-gray-700 hover:bg-white'); ?> transition-all duration-150">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-sm font-medium">Shop By Budget</span>
                </a>
            </li>

            <!-- Admin Management -->
            <li>
                <a href="<?php echo e(route('admin-users.index')); ?>"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg <?php echo e($activeMenu === 'admin-management' ? 'bg-black text-white' : 'text-gray-700 hover:bg-white'); ?> transition-all duration-150">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="text-sm font-medium">Admin Users</span>
                </a>
            </li>

            <!-- Activity Logs -->
            <li>
                <a href="<?php echo e(url('/admin/activity-logs')); ?>"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-white transition-all duration-150">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="text-sm font-medium">Activity Logs</span>
                </a>
            </li>

            <!-- Settings -->
            <li>
                <a href="<?php echo e(route('admin.settings')); ?>"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg <?php echo e($activeMenu === 'settings' ? 'bg-black text-white' : 'text-gray-700 hover:bg-white'); ?> transition-all duration-150">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="text-sm font-medium">Settings</span>
                </a>
            </li>

            <!-- Theme Settings -->
            <li>
                <a href="<?php echo e(route('admin.theme-settings.index')); ?>"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg <?php echo e(request()->routeIs('admin.theme-settings.*') ? 'bg-black text-white' : 'text-gray-700 hover:bg-white'); ?> transition-all duration-150">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                    </svg>
                    <span class="text-sm font-medium">Theme Settings</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- User Profile & Logout -->
    <div class="border-t border-gray-100 p-4">
        <div class="flex items-center space-x-3 px-3 py-3 mb-3 bg-gray-50 rounded-xl">
            <div
                class="w-11 h-11 bg-gradient-to-br from-gray-800 to-black rounded-xl flex items-center justify-center shadow-sm">
                <span class="text-white font-bold text-base">S</span>
            </div>
            <div class="flex-1">
                <p class="text-sm font-bold text-gray-900">Super Admin</p>
                <p class="text-xs text-gray-500 truncate"><?php echo e(session('admin_email')); ?></p>
            </div>
        </div>
        <a href="<?php echo e(route('admin.logout')); ?>"
            class="flex items-center justify-center space-x-2 px-4 py-3 border-2 border-gray-200 rounded-xl text-gray-700 hover:bg-gray-900 hover:border-gray-800 hover:text-white transition-all duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            <span class="text-sm font-semibold">Logout</span>
        </a>
    </div>
</div>

<script>
    function toggleProductMenu() {
        const submenu = document.getElementById('productSubmenu');
        const icon = document.getElementById('productMenuIcon');

        if (submenu.classList.contains('hidden')) {
            submenu.classList.remove('hidden');
            icon.classList.add('rotate-180');
        } else {
            submenu.classList.add('hidden');
            icon.classList.remove('rotate-180');
        }
    }

    function toggleSellerMenu() {
        const submenu = document.getElementById('sellerSubmenu');
        const icon = document.getElementById('sellerMenuIcon');

        if (submenu.classList.contains('hidden')) {
            submenu.classList.remove('hidden');
            icon.classList.add('rotate-180');
        } else {
            submenu.classList.add('hidden');
            icon.classList.remove('rotate-180');
        }
    }
</script><?php /**PATH C:\xampp\htdocs\ESS_Project1\resources\views/admin/components/sidebar.blade.php ENDPATH**/ ?>