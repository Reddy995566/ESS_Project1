

<?php $__env->startSection('title', 'Cancelled Orders'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Main Container -->
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-red-50 to-pink-50 p-6">
        
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="flex items-center justify-center w-14 h-14 bg-gradient-to-br from-red-500 to-pink-600 rounded-2xl shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-black text-gray-900 tracking-tight">Cancelled Orders</h1>
                        <p class="mt-1 text-sm text-gray-600">View and manage all cancelled orders</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="<?php echo e(route('admin.orders.index')); ?>" class="inline-flex items-center px-5 py-2.5 bg-white text-gray-700 text-sm font-semibold rounded-xl border-2 border-gray-200 hover:border-blue-400 hover:bg-blue-50 hover:text-blue-700 transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        All Orders
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics Card -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-red-600 rounded-2xl shadow-xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center justify-center w-12 h-12 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="px-3 py-1 bg-white bg-opacity-20 backdrop-blur-sm rounded-full text-xs font-bold text-white">Total</span>
                </div>
                <div class="text-white">
                    <p class="text-4xl font-black mb-1"><?php echo e($orders->total()); ?></p>
                    <p class="text-red-100 text-sm font-medium">Total Cancelled</p>
                </div>
            </div>

            <div class="bg-orange-600 rounded-2xl shadow-xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center justify-center w-12 h-12 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <span class="px-3 py-1 bg-white bg-opacity-20 backdrop-blur-sm rounded-full text-xs font-bold text-white">By Customer</span>
                </div>
                <div class="text-white">
                    <p class="text-4xl font-black mb-1"><?php echo e($orders->where('cancelled_by', 'customer')->count()); ?></p>
                    <p class="text-orange-100 text-sm font-medium">Customer Cancelled</p>
                </div>
            </div>

            <div class="bg-purple-600 rounded-2xl shadow-xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center justify-center w-12 h-12 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <span class="px-3 py-1 bg-white bg-opacity-20 backdrop-blur-sm rounded-full text-xs font-bold text-white">By Admin</span>
                </div>
                <div class="text-white">
                    <p class="text-4xl font-black mb-1"><?php echo e($orders->where('cancelled_by', 'admin')->count()); ?></p>
                    <p class="text-purple-100 text-sm font-medium">Admin Cancelled</p>
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
                            <span class="flex items-center justify-center w-8 h-8 bg-red-100 text-red-600 rounded-lg mr-3">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"/>
                                </svg>
                            </span>
                            Cancelled Orders Table
                        </h2>
                        <p class="text-sm text-gray-600 mt-1 ml-11">All cancelled orders with cancellation details</p>
                    </div>
                    
                    <!-- Search -->
                    <div class="flex items-center space-x-3">
                        <div class="relative">
                            <input type="text" id="searchInput" placeholder="Search orders..." class="pl-10 pr-4 py-2.5 w-64 border-2 border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-gray-100 to-slate-100 border-b-2 border-gray-200">
                        <tr>
                            <th class="px-4 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Order #</th>
                            <th class="px-4 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Customer</th>
                            <th class="px-4 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Total</th>
                            <th class="px-4 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Cancelled By</th>
                            <th class="px-4 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Cancelled At</th>
                            <th class="px-4 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Reason</th>
                            <th class="px-4 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="group hover:bg-gradient-to-r hover:from-red-50 hover:to-pink-50 transition-all duration-200">
                            <td class="px-4 py-4">
                                <p class="text-sm font-bold text-gray-900"><?php echo e($order->order_number); ?></p>
                                <p class="text-xs text-gray-500"><?php echo e($order->created_at->format('d M, Y')); ?></p>
                            </td>
                            <td class="px-4 py-4">
                                <p class="text-sm font-medium text-gray-900"><?php echo e($order->first_name); ?> <?php echo e($order->last_name); ?></p>
                                <p class="text-xs text-gray-500"><?php echo e($order->email); ?></p>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <p class="text-sm font-bold text-gray-900">₹<?php echo e(number_format($order->total, 2)); ?></p>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold 
                                    <?php if($order->cancelled_by === 'customer'): ?> bg-orange-100 text-orange-700 border border-orange-300
                                    <?php elseif($order->cancelled_by === 'admin'): ?> bg-purple-100 text-purple-700 border border-purple-300
                                    <?php else: ?> bg-blue-100 text-blue-700 border border-blue-300
                                    <?php endif; ?>">
                                    <?php echo e(ucfirst($order->cancelled_by ?? 'N/A')); ?>

                                </span>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <p class="text-sm text-gray-900"><?php echo e($order->cancelled_at ? $order->cancelled_at->format('d M, Y') : 'N/A'); ?></p>
                                <p class="text-xs text-gray-500"><?php echo e($order->cancelled_at ? $order->cancelled_at->format('h:i A') : ''); ?></p>
                            </td>
                            <td class="px-4 py-4">
                                <p class="text-xs text-gray-600 line-clamp-2"><?php echo e($order->cancellation_reason ?? 'No reason provided'); ?></p>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <a href="<?php echo e(route('admin.orders.show', $order->id)); ?>" class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-blue-500 to-indigo-600 text-white text-xs font-semibold rounded-lg hover:from-blue-600 hover:to-indigo-700 shadow-md hover:shadow-lg transition-all">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    View
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center">
                                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                                <p class="text-gray-500 text-lg font-medium">No cancelled orders found</p>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if($orders->hasPages()): ?>
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                <?php echo e($orders->links()); ?>

            </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Search functionality
        document.getElementById('searchInput').addEventListener('keyup', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\ESS_Project1\resources\views/admin/orders/cancelled.blade.php ENDPATH**/ ?>