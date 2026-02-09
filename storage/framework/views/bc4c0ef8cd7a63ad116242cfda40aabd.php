

<?php $__env->startSection('title', 'Notifications Management'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Main Container -->
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-indigo-50 to-blue-50 p-6">
        
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="flex items-center justify-center w-14 h-14 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-2xl shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 17h5l-5 5v-5zM4 19h6v-2H4v2zM4 15h8v-2H4v2zM4 11h8V9H4v2zM4 7h8V5H4v2z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-black text-gray-900 tracking-tight">Notifications Management</h1>
                        <p class="mt-1 text-sm text-gray-600">Manage and track all your notifications</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <button id="markAllReadBtn" class="inline-flex items-center px-5 py-2.5 bg-white text-gray-700 text-sm font-semibold rounded-xl border-2 border-gray-200 hover:border-green-400 hover:bg-green-50 hover:text-green-700 transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Mark All Read
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
        <!-- Statistics Dashboard -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Total Notifications -->
            <div class="bg-indigo-600 rounded-2xl shadow-xl p-6 transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center justify-center w-12 h-12 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5z"/>
                        </svg>
                    </div>
                    <span class="px-3 py-1 bg-white bg-opacity-20 backdrop-blur-sm rounded-full text-xs font-bold text-white">Total</span>
                </div>
                <div class="text-white">
                    <p class="text-4xl font-black mb-1"><?php echo e($totalNotifications ?? 0); ?></p>
                    <p class="text-indigo-100 text-sm font-medium">Total Notifications</p>
                </div>
            </div>

            <!-- Unread Notifications -->
            <div class="bg-orange-600 rounded-2xl shadow-xl p-6 transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center justify-center w-12 h-12 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                    </div>
                    <span class="px-3 py-1 bg-white bg-opacity-20 backdrop-blur-sm rounded-full text-xs font-bold text-white">Unread</span>
                </div>
                <div class="text-white">
                    <p class="text-4xl font-black mb-1"><?php echo e($unreadCount ?? 0); ?></p>
                    <p class="text-orange-100 text-sm font-medium">Unread Notifications</p>
                </div>
            </div>

            <!-- Read Notifications -->
            <div class="bg-emerald-600 rounded-2xl shadow-xl p-6 transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center justify-center w-12 h-12 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="px-3 py-1 bg-white bg-opacity-20 backdrop-blur-sm rounded-full text-xs font-bold text-white">Read</span>
                </div>
                <div class="text-white">
                    <p class="text-4xl font-black mb-1"><?php echo e($readCount ?? 0); ?></p>
                    <p class="text-emerald-100 text-sm font-medium">Read Notifications</p>
                </div>
            </div>

            <!-- Today's Notifications -->
            <div class="bg-purple-600 rounded-2xl shadow-xl p-6 transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center justify-center w-12 h-12 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <span class="px-3 py-1 bg-white bg-opacity-20 backdrop-blur-sm rounded-full text-xs font-bold text-white">Today</span>
                </div>
                <div class="text-white">
                    <p class="text-4xl font-black mb-1"><?php echo e($todayCount ?? 0); ?></p>
                    <p class="text-purple-100 text-sm font-medium">Today's Notifications</p>
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
                                    <path d="M10 2L3 7v11c0 1.1.9 2 2 2h3v-6h4v6h3c1.1 0 2-.9 2-2V7l-7-5z"/>
                                </svg>
                            </span>
                            Notifications Data Table
                        </h2>
                        <p class="text-sm text-gray-600 mt-1 ml-11">Manage all your notifications and alerts</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm font-medium text-gray-600">Quick Actions:</span>
                        <select id="bulkAction" class="px-3 py-2 bg-white border-2 border-gray-300 rounded-lg text-sm font-medium focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Select Action</option>
                            <option value="mark_read">Mark as Read</option>
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
                <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Type Filter -->
                    <div class="md:col-span-1">
                        <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">Notification Type</label>
                        <select name="type" class="block w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">All Types</option>
                            <option value="order_placed" <?php echo e(request('type') == 'order_placed' ? 'selected' : ''); ?>>🛒 New Order</option>
                            <option value="product_approved" <?php echo e(request('type') == 'product_approved' ? 'selected' : ''); ?>>✅ Product Approved</option>
                            <option value="product_rejected" <?php echo e(request('type') == 'product_rejected' ? 'selected' : ''); ?>>❌ Product Rejected</option>
                            <option value="payout_processed" <?php echo e(request('type') == 'payout_processed' ? 'selected' : ''); ?>>💰 Payout Processed</option>
                            <option value="low_stock" <?php echo e(request('type') == 'low_stock' ? 'selected' : ''); ?>>⚠️ Low Stock</option>
                        </select>
                    </div>

                    <!-- Read Status Filter -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">Read Status</label>
                        <select name="read" class="block w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">All Notifications</option>
                            <option value="unread" <?php echo e(request('read') == 'unread' ? 'selected' : ''); ?>>📬 Unread Only</option>
                            <option value="read" <?php echo e(request('read') == 'read' ? 'selected' : ''); ?>>📭 Read Only</option>
                        </select>
                    </div>

                    <!-- Action Buttons -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">Actions</label>
                        <div class="flex items-center space-x-2">
                            <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-3 rounded-xl font-semibold transition-all">
                                Filter
                            </button>
                            <a href="<?php echo e(route('seller.notifications.index')); ?>" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-3 rounded-xl font-semibold transition-all">
                                Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Data Table -->
            <div class="overflow-x-auto">
                <table id="notificationsTable" class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-slate-100 to-gray-100">
                        <tr>
                            <th class="px-4 py-4 text-center w-12">
                                <input type="checkbox" id="selectAll" class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-2 focus:ring-indigo-500">
                            </th>
                            <th class="px-4 py-4 text-left min-w-[80px]">
                                <span class="text-xs font-black text-gray-700 uppercase">Type</span>
                            </th>
                            <th class="px-4 py-4 text-left min-w-[200px]">
                                <span class="text-xs font-black text-gray-700 uppercase">Title</span>
                            </th>
                            <th class="px-4 py-4 text-left min-w-[300px]">
                                <span class="text-xs font-black text-gray-700 uppercase">Message</span>
                            </th>
                            <th class="px-4 py-4 text-center w-32">
                                <span class="text-xs font-black text-gray-700 uppercase">Status</span>
                            </th>
                            <th class="px-4 py-4 text-center w-40">
                                <span class="text-xs font-black text-gray-700 uppercase">Date</span>
                            </th>
                            <th class="px-4 py-4 text-center w-32">
                                <span class="text-xs font-black text-gray-700 uppercase">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="group hover:bg-gradient-to-r hover:from-indigo-50 hover:to-blue-50 transition-all duration-200 <?php echo e(($notification->read_at || $notification->is_read) ? '' : 'bg-blue-50'); ?>"
                            data-type="<?php echo e($notification->type); ?>"
                            data-read="<?php echo e(($notification->read_at || $notification->is_read) ? 'read' : 'unread'); ?>"
                            data-id="<?php echo e($notification->id); ?>">
                            
                            <!-- Checkbox -->
                            <td class="px-4 py-4 text-center">
                                <input type="checkbox" class="row-select w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-2 focus:ring-indigo-500" value="<?php echo e($notification->id); ?>">
                            </td>

                            <!-- Type Icon -->
                            <td class="px-4 py-4">
                                <?php
                                    $typeIcons = [
                                        'order_placed' => ['icon' => '🛒', 'color' => 'from-green-100 to-emerald-100 text-green-700 border-green-300'],
                                        'product_approved' => ['icon' => '✅', 'color' => 'from-green-100 to-emerald-100 text-green-700 border-green-300'],
                                        'product_rejected' => ['icon' => '❌', 'color' => 'from-red-100 to-pink-100 text-red-700 border-red-300'],
                                        'payout_processed' => ['icon' => '💰', 'color' => 'from-blue-100 to-indigo-100 text-blue-700 border-blue-300'],
                                        'low_stock' => ['icon' => '⚠️', 'color' => 'from-yellow-100 to-amber-100 text-yellow-700 border-yellow-300'],
                                    ];
                                    $typeData = $typeIcons[$notification->type] ?? ['icon' => '🔔', 'color' => 'from-gray-100 to-gray-200 text-gray-700 border-gray-300'];
                                ?>
                                <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-bold bg-gradient-to-r <?php echo e($typeData['color']); ?> border">
                                    <?php echo e($typeData['icon']); ?>

                                </span>
                            </td>

                            <!-- Title -->
                            <td class="px-4 py-4">
                                <p class="text-sm font-bold text-gray-900"><?php echo e($notification->title ?? ucfirst(str_replace('_', ' ', $notification->type))); ?></p>
                                <?php if(!$notification->read_at && !$notification->is_read): ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 mt-1">
                                        New
                                    </span>
                                <?php endif; ?>
                            </td>

                            <!-- Message -->
                            <td class="px-4 py-4">
                                <p class="text-sm text-gray-600"><?php echo e(Str::limit($notification->message ?? 'No message available', 100)); ?></p>
                            </td>

                            <!-- Status -->
                            <td class="px-4 py-4 text-center">
                                <?php if($notification->read_at || $notification->is_read): ?>
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold bg-gradient-to-r from-green-100 to-emerald-100 text-green-700 border border-green-300">
                                        📭 Read
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold bg-gradient-to-r from-orange-100 to-amber-100 text-orange-700 border border-orange-300">
                                        📬 Unread
                                    </span>
                                <?php endif; ?>
                            </td>

                            <!-- Date -->
                            <td class="px-4 py-4 text-center">
                                <p class="text-sm font-medium text-gray-900"><?php echo e($notification->created_at->format('d M, Y')); ?></p>
                                <p class="text-xs text-gray-500"><?php echo e($notification->created_at->format('h:i A')); ?></p>
                            </td>

                            <!-- Actions -->
                            <td class="px-4 py-4">
                                <div class="flex items-center justify-center space-x-2">
                                    <?php if(!$notification->read_at && !$notification->is_read): ?>
                                        <button class="mark-read-btn inline-flex items-center justify-center w-9 h-9 text-green-600 bg-green-50 border-2 border-green-200 rounded-lg hover:bg-green-100 hover:border-green-400 focus:outline-none focus:ring-2 focus:ring-green-500 transition-all" title="Mark as Read" data-id="<?php echo e($notification->id); ?>">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </button>
                                    <?php endif; ?>
                                    <button class="delete-btn inline-flex items-center justify-center w-9 h-9 text-red-600 bg-red-50 border-2 border-red-200 rounded-lg hover:bg-red-100 hover:border-red-400 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all" title="Delete Notification" data-id="<?php echo e($notification->id); ?>">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr id="emptyState">
                            <td colspan="7" class="px-6 py-20">
                                <div class="text-center">
                                    <div class="mx-auto flex items-center justify-center w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full mb-6">
                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5z"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">No Notifications Found</h3>
                                    <p class="text-gray-500 mb-6 max-w-md mx-auto">You don't have any notifications yet. When important events happen, they'll appear here.</p>
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
                        Showing <span class="font-bold text-indigo-600"><?php echo e($notifications->firstItem() ?? 0); ?></span> to <span class="font-bold text-indigo-600"><?php echo e($notifications->lastItem() ?? 0); ?></span> of <span class="font-bold text-indigo-600"><?php echo e($notifications->total()); ?></span> entries
                    </div>
                    <div class="dataTables_paginate">
                        <?php echo e($notifications->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>
<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select All functionality
    const selectAllCheckbox = document.getElementById('selectAll');
    const rowCheckboxes = document.querySelectorAll('.row-select');
    const bulkActionSelect = document.getElementById('bulkAction');
    const applyBulkActionBtn = document.getElementById('applyBulkAction');

    selectAllCheckbox?.addEventListener('change', function() {
        rowCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkActionButton();
    });

    rowCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActionButton);
    });

    function updateBulkActionButton() {
        const checkedBoxes = document.querySelectorAll('.row-select:checked');
        applyBulkActionBtn.disabled = checkedBoxes.length === 0;
        
        // Update select all checkbox state
        if (selectAllCheckbox) {
            selectAllCheckbox.checked = checkedBoxes.length === rowCheckboxes.length;
            selectAllCheckbox.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < rowCheckboxes.length;
        }
    }

    // Bulk Actions
    applyBulkActionBtn?.addEventListener('click', function() {
        const action = bulkActionSelect.value;
        const checkedBoxes = document.querySelectorAll('.row-select:checked');
        
        if (!action || checkedBoxes.length === 0) return;

        const ids = Array.from(checkedBoxes).map(cb => cb.value);
        
        if (action === 'mark_read') {
            markMultipleAsRead(ids);
        } else if (action === 'delete') {
            if (confirm(`Delete ${ids.length} selected notifications?`)) {
                deleteMultiple(ids);
            }
        }
    });

    // Mark All Read
    document.getElementById('markAllReadBtn')?.addEventListener('click', function() {
        if (confirm('Mark all notifications as read?')) {
            fetch('<?php echo e(route("seller.notifications.mark-all-read")); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error marking all as read');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error marking all as read');
            });
        }
    });

    // Individual Mark as Read
    document.querySelectorAll('.mark-read-btn').forEach(button => {
        button.addEventListener('click', function() {
            const notificationId = this.getAttribute('data-id');
            const row = this.closest('tr');
            
            fetch(`/seller/notifications/${notificationId}/read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update row appearance
                    row.classList.remove('bg-blue-50');
                    
                    // Update status badge
                    const statusCell = row.querySelector('td:nth-child(5)');
                    statusCell.innerHTML = '<span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold bg-gradient-to-r from-green-100 to-emerald-100 text-green-700 border border-green-300">📭 Read</span>';
                    
                    // Remove mark as read button
                    this.remove();
                    
                    // Remove "New" badge from title
                    const titleCell = row.querySelector('td:nth-child(3)');
                    const newBadge = titleCell.querySelector('.bg-blue-100');
                    if (newBadge) newBadge.remove();
                } else {
                    alert('Error marking notification as read');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error marking notification as read');
            });
        });
    });

    // Individual Delete
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            const notificationId = this.getAttribute('data-id');
            const row = this.closest('tr');
            
            if (confirm('Delete this notification?')) {
                fetch(`/seller/notifications/${notificationId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        row.style.opacity = '0';
                        setTimeout(() => row.remove(), 300);
                    } else {
                        alert('Error deleting notification');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error deleting notification');
                });
            }
        });
    });

    // Refresh functionality
    document.getElementById('refreshBtn')?.addEventListener('click', function() {
        window.location.reload();
    });

    // Helper functions
    function markMultipleAsRead(ids) {
        // Implementation for bulk mark as read
        Promise.all(ids.map(id => 
            fetch(`/seller/notifications/${id}/read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                }
            })
        )).then(() => {
            location.reload();
        }).catch(error => {
            console.error('Error:', error);
            alert('Error marking notifications as read');
        });
    }

    function deleteMultiple(ids) {
        // Implementation for bulk delete
        Promise.all(ids.map(id => 
            fetch(`/seller/notifications/${id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                }
            })
        )).then(() => {
            location.reload();
        }).catch(error => {
            console.error('Error:', error);
            alert('Error deleting notifications');
        });
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('seller.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\ESS_Project1\resources\views/seller/notifications/index.blade.php ENDPATH**/ ?>