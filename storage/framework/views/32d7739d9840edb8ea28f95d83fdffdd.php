<header class="bg-white border-b border-gray-200 shadow-sm">
    <div class="flex items-center justify-between px-6 py-4">
        <!-- Left Section -->
        <div class="flex items-center">
            <h2 class="text-xl font-semibold text-gray-900">
                <?php if(request()->routeIs('admin.dashboard')): ?>
                    Dashboard
                <?php elseif(request()->routeIs('admin.categories.*')): ?>
                    Categories
                <?php elseif(request()->routeIs('admin.products*')): ?>
                    Products
                <?php elseif(request()->routeIs('admin.settings*')): ?>
                    Settings
                <?php else: ?>
                    Admin Panel
                <?php endif; ?>
            </h2>
        </div>

        <!-- Right Section -->
        <div class="flex items-center space-x-4">
            <!-- Profile -->
            <div class="flex items-center">
                <div class="w-8 h-8 bg-gradient-to-br from-gray-800 to-black rounded-lg flex items-center justify-center">
                    <span class="text-white font-semibold text-sm"><?php echo e(substr(session('admin_name', 'A'), 0, 1)); ?></span>
                </div>
            </div>
        </div>
    </div>
</header><?php /**PATH C:\xampp\htdocs\ESS_Project1\resources\views/admin/components/topbar.blade.php ENDPATH**/ ?>