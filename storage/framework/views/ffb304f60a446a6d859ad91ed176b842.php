

<?php $__env->startSection('title', 'Rejected Products'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Rejected Products</h1>
            <p class="text-gray-600 mt-1">Products that need revision</p>
        </div>
        <a href="<?php echo e(route('seller.products.index')); ?>" 
            class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-3 rounded-lg font-medium transition-all flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Back to All Products</span>
        </a>
    </div>

    <!-- Info Banner -->
    <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 rounded-lg">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-red-700">
                    These products were rejected by the admin. Please review the rejection reasons and make necessary changes before resubmitting.
                </p>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-all border-2 border-red-200">
            <!-- Product Image -->
            <div class="h-48 bg-gray-200 relative">
                <?php if($product->getFirstImageUrl()): ?>
                    <img src="<?php echo e($product->getFirstImageUrl()); ?>" alt="<?php echo e($product->name); ?>" class="w-full h-full object-cover">
                <?php else: ?>
                    <div class="w-full h-full flex items-center justify-center">
                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                <?php endif; ?>
                <div class="absolute top-2 right-2">
                    <span class="px-3 py-1 bg-red-500 text-white text-xs font-semibold rounded-full">
                        Rejected
                    </span>
                </div>
            </div>

            <!-- Product Info -->
            <div class="p-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-2"><?php echo e(Str::limit($product->name, 40)); ?></h3>
                <p class="text-sm text-gray-500 mb-3"><?php echo e($product->category->name ?? 'N/A'); ?></p>
                
                <!-- Rejection Reason -->
                <?php if($product->rejection_reason): ?>
                <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-4">
                    <p class="text-xs font-semibold text-red-800 mb-1">Rejection Reason:</p>
                    <p class="text-sm text-red-700"><?php echo e($product->rejection_reason); ?></p>
                </div>
                <?php endif; ?>

                <div class="flex items-center justify-between mb-4">
                    <div>
                        <span class="text-xl font-bold text-gray-900">₹<?php echo e(number_format($product->sale_price ?? $product->price, 2)); ?></span>
                        <?php if($product->sale_price): ?>
                            <span class="text-sm text-gray-500 line-through ml-2">₹<?php echo e(number_format($product->price, 2)); ?></span>
                        <?php endif; ?>
                    </div>
                    <span class="text-sm text-gray-600">Stock: <?php echo e($product->stock); ?></span>
                </div>

                <div class="text-xs text-gray-500 mb-3">
                    Rejected: <?php echo e($product->updated_at->diffForHumans()); ?>

                </div>

                <!-- Actions -->
                <div class="flex space-x-2">
                    <a href="<?php echo e(route('seller.products.edit', $product->id)); ?>" 
                        class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white text-center px-4 py-2 rounded-lg text-sm font-medium transition-all">
                        Edit & Resubmit
                    </a>
                    <button onclick="deleteProduct(<?php echo e($product->id); ?>)" 
                        class="bg-red-50 hover:bg-red-100 text-red-600 px-4 py-2 rounded-lg text-sm font-medium transition-all">
                        Delete
                    </button>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-span-full text-center py-12">
            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-gray-500 text-lg">No rejected products</p>
        </div>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if($products->hasPages()): ?>
    <div class="mt-6">
        <?php echo e($products->links()); ?>

    </div>
    <?php endif; ?>

    <?php $__env->startPush('scripts'); ?>
    <script>
        function deleteProduct(id) {
            if (!confirm('Are you sure you want to delete this product?')) {
                return;
            }

            fetch(`/seller/products/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    window.location.reload();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while deleting the product');
            });
        }
    </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('seller.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\ESS_Project1\resources\views/seller/products/rejected.blade.php ENDPATH**/ ?>