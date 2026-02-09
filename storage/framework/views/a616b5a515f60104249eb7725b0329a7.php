

<?php $__env->startSection('title', 'Return Management'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 shadow-lg">
        <div class="px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-white">Return Management</h1>
                    <p class="text-indigo-100 mt-1">Manage customer return requests for your products</p>
                </div>
            </div>
        </div>
    </div>

    <div class="px-4 sm:px-6 lg:px-8 py-8">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Returns</p>
                        <p class="text-2xl font-bold text-gray-900"><?php echo e(number_format($stats['total'])); ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Pending</p>
                        <p class="text-2xl font-bold text-gray-900"><?php echo e(number_format($stats['pending'])); ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Approved</p>
                        <p class="text-2xl font-bold text-gray-900"><?php echo e(number_format($stats['approved'])); ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Refunded</p>
                        <p class="text-2xl font-bold text-gray-900"><?php echo e(number_format($stats['refunded'])); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">
            <div class="p-6">
                <form method="GET" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <input type="text" name="search" value="<?php echo e(request('search')); ?>" 
                               placeholder="Search by return number, customer, or order..."
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    <div class="md:w-48">
                        <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="">All Status</option>
                            <option value="pending" <?php echo e(request('status') === 'pending' ? 'selected' : ''); ?>>Pending</option>
                            <option value="approved" <?php echo e(request('status') === 'approved' ? 'selected' : ''); ?>>Approved</option>
                            <option value="rejected" <?php echo e(request('status') === 'rejected' ? 'selected' : ''); ?>>Rejected</option>
                            <option value="picked_up" <?php echo e(request('status') === 'picked_up' ? 'selected' : ''); ?>>Picked Up</option>
                            <option value="refunded" <?php echo e(request('status') === 'refunded' ? 'selected' : ''); ?>>Refunded</option>
                        </select>
                    </div>
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                        Filter
                    </button>
                    <a href="<?php echo e(route('seller.returns.index')); ?>" class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        Clear
                    </a>
                </form>
            </div>
        </div>

        <!-- Returns Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">Returns List</h2>
            </div>

            <?php if($returns->count() > 0): ?>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Return Details</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $__currentLoopData = $returns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $return): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div>
                                    <div class="text-sm font-medium text-gray-900"><?php echo e($return->return_number); ?></div>
                                    <div class="text-sm text-gray-500">Order: <?php echo e($return->order->order_number); ?></div>
                                    <div class="text-sm text-gray-500">Reason: <?php echo e($return->reason_label); ?></div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div>
                                    <div class="text-sm font-medium text-gray-900"><?php echo e($return->user->name); ?></div>
                                    <div class="text-sm text-gray-500"><?php echo e($return->user->email); ?></div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <img src="<?php echo e($return->orderItem->product->image_url ?? 'https://via.placeholder.com/50'); ?>" 
                                         alt="<?php echo e($return->orderItem->product_name); ?>" 
                                         class="w-10 h-10 rounded-lg object-cover mr-3">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900"><?php echo e(Str::limit($return->orderItem->product_name, 30)); ?></div>
                                        <div class="text-sm text-gray-500">Qty: <?php echo e($return->quantity); ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">Rs. <?php echo e(number_format($return->amount)); ?></div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-medium rounded-full <?php echo e($return->status_badge); ?>">
                                    <?php echo e(ucfirst($return->status)); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900"><?php echo e($return->requested_at->format('M j, Y')); ?></div>
                                <div class="text-sm text-gray-500"><?php echo e($return->requested_at->format('g:i A')); ?></div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <a href="<?php echo e(route('seller.returns.show', $return->id)); ?>" 
                                       class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                        View
                                    </a>
                                    <?php if($return->status === 'pending' && !$return->processed_by_seller): ?>
                                    <button onclick="acknowledgeReturn(<?php echo e($return->id); ?>)" 
                                            class="text-green-600 hover:text-green-900 text-sm font-medium">
                                        Acknowledge
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if($returns->hasPages()): ?>
            <div class="px-6 py-4 border-t border-gray-100">
                <?php echo e($returns->appends(request()->query())->links()); ?>

            </div>
            <?php endif; ?>
            <?php else: ?>
            <div class="p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No returns found</h3>
                <p class="text-gray-500">No return requests found for your products.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
// Acknowledge return
function acknowledgeReturn(returnId) {
    const notes = prompt('Add seller notes (optional):');
    
    fetch(`/seller/returns/${returnId}/acknowledge`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            seller_notes: notes
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Failed to acknowledge return');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while acknowledging the return');
    });
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('seller.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\ESS_Project1\resources\views/seller/returns/index.blade.php ENDPATH**/ ?>