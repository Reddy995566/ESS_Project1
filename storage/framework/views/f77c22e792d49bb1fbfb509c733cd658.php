<?php $__env->startSection('title'); ?>
Edit Product - Step <?php echo e($currentStep ?? 1); ?> of 6
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<!-- Alpine.js -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<!-- Sortable.js for drag and drop -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('styles'); ?>
<style>
/* Quill Editor Custom Styles */
.quill-editor-wrapper {
    border: 2px solid #d1d5db;
    border-radius: 0.5rem;
    overflow: hidden;
}

/* Sortable.js Custom Styles */
.sortable-ghost {
    opacity: 0.3;
}

.sortable-chosen {
    transform: scale(1.05);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    border-color: #9333ea !important;
    z-index: 999;
}

.sortable-drag {
    transform: rotate(3deg) scale(1.1);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
    opacity: 0.9;
}

.sortable-fallback {
    opacity: 0.8;
}

.ql-toolbar.ql-snow {
    border: none !important;
    border-bottom: 2px solid #e5e7eb !important;
    background: #f9fafb !important;
    padding: 12px !important;
}

.ql-container.ql-snow {
    border: none !important;
    font-size: 14px;
}

.ql-editor {
    min-height: 350px !important;
    max-height: 600px !important;
    overflow-y: auto !important;
    padding: 16px !important;
}

#shortDescription .ql-editor {
    min-height: 300px !important;
    max-height: 450px !important;
}

#detailedDescription .ql-editor {
    min-height: 500px !important;
    max-height: 800px !important;
}

.ql-editor.ql-blank::before {
    color: #9ca3af;
    font-style: normal;
}

/* Image Upload Styles */
.image-upload-area {
    border: 2px dashed #d1d5db;
    border-radius: 0.5rem;
    transition: all 0.2s ease;
}

.image-upload-area:hover {
    border-color: #3b82f6;
    background-color: #f8fafc;
}

.image-upload-area.dragover {
    border-color: #3b82f6;
    background-color: #eff6ff;
    transform: scale(1.02);
}

/* Progress Animation */
.progress-line {
    transition: all 0.3s ease;
}

.progress-line.active {
    background: linear-gradient(to right, #3b82f6, #1d4ed8);
}

.step-indicator.active {
    transform: scale(1.1);
}

.step-indicator.completed {
    background: linear-gradient(to right, #059669, #047857) !important;
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<?php
    $currentStep = $currentStep ?? 1;
    $prevStepRoute = $prevStepRoute ?? null;
?>

<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-md border-b border-gray-200">
        <div class="w-full px-6 py-5">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-5">
                    <a href="<?php echo e(route('admin.products')); ?>" class="p-2.5 hover:bg-blue-50 rounded-xl transition-all duration-200 hover:scale-105 group">
                        <svg class="w-5 h-5 text-gray-600 group-hover:text-blue-600 transition-colors" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.707 14.707a1 1 0 01-1.414 0L2.586 11 6.293 7.293a1 1 0 111.414 1.414L5.414 11l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 tracking-tight"><?php echo $__env->yieldContent('step_title', 'Edit Product'); ?></h1>
                        <p class="text-gray-600 font-medium"><?php echo $__env->yieldContent('step_description', 'Update your product information'); ?></p>
                        <?php if(isset($product)): ?>
                            <p class="text-sm text-indigo-600 font-semibold">Editing: <?php echo e($product->name); ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="flex items-center space-x-3">
                    <?php if($currentStep > 1): ?>
                    <a href="<?php echo e($prevStepRoute ?? '#'); ?>" class="px-5 py-2.5 border-2 border-gray-300 text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-400 rounded-lg font-semibold transition-all duration-200 hover:shadow-sm">
                        ← Previous
                    </a>
                    <?php endif; ?>
                    <a href="<?php echo e(route('admin.products')); ?>" class="px-5 py-2.5 border-2 border-red-300 text-red-700 bg-white hover:bg-red-50 hover:border-red-400 rounded-lg font-semibold transition-all duration-200 hover:shadow-sm">
                        Cancel Edit
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Steps -->
    <div class="bg-gradient-to-r from-gray-50 to-orange-50 border-b border-gray-200">
        <div class="w-full px-4 sm:px-6 py-4 sm:py-6">
            <div class="overflow-x-auto">
                <div class="flex items-center justify-center">
                    <div class="flex items-center space-x-4 sm:space-x-6 lg:space-x-8 xl:space-x-10">
                        <?php
                            $steps = [
                                ['number' => 1, 'title' => 'Basic Info', 'description' => 'Product details', 'route' => 'admin.products.edit.step1'],
                                ['number' => 2, 'title' => 'Variants', 'description' => 'Colors & sizes', 'route' => 'admin.products.edit.step2'],
                                ['number' => 3, 'title' => 'Pricing', 'description' => 'Price & inventory', 'route' => 'admin.products.edit.step3'],
                                ['number' => 4, 'title' => 'Categories', 'description' => 'Organization', 'route' => 'admin.products.edit.step4'],
                                ['number' => 5, 'title' => 'SEO', 'description' => 'Optimization', 'route' => 'admin.products.edit.step5'],
                                ['number' => 6, 'title' => 'Settings', 'description' => 'Status & publish', 'route' => 'admin.products.edit.step6']
                            ];
                            $current = $currentStep;
                        ?>
                        
                        <?php $__currentLoopData = $steps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <!-- Step <?php echo e($step['number']); ?> -->
                            <div class="flex flex-col items-center space-y-1 sm:space-y-2 cursor-pointer group step-indicator <?php echo e($step['number'] == $current ? 'active' : ''); ?> <?php echo e($step['number'] < $current ? 'completed' : ''); ?>" 
                                 onclick="navigateToStep(<?php echo e($step['number']); ?>)" title="Click to go to <?php echo e($step['title']); ?>">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center text-xs sm:text-sm font-bold shadow-lg group-hover:scale-110 group-hover:shadow-xl transition-all duration-200
                                    <?php echo e($step['number'] == $current ? 'bg-gradient-to-r from-orange-600 to-orange-700 text-white ring-4 ring-orange-200' : ''); ?>

                                    <?php echo e($step['number'] < $current ? 'bg-gradient-to-r from-green-600 to-green-700 text-white' : ''); ?>

                                    <?php echo e($step['number'] > $current ? 'bg-gradient-to-r from-blue-400 to-blue-500 text-white hover:from-blue-500 hover:to-blue-600' : ''); ?>">
                                    <?php echo e($step['number'] < $current ? '✓' : $step['number']); ?>

                                </div>
                                <div class="text-center">
                                    <div class="text-xs sm:text-sm font-semibold <?php echo e($step['number'] == $current ? 'text-gray-900' : ($step['number'] < $current ? 'text-green-700' : 'text-blue-700 group-hover:text-blue-900')); ?>"><?php echo e($step['title']); ?></div>
                                    <div class="text-xs <?php echo e($step['number'] == $current ? 'text-gray-500' : ($step['number'] < $current ? 'text-green-500' : 'text-blue-500 group-hover:text-blue-700')); ?> hidden sm:block"><?php echo e($step['description']); ?></div>
                                </div>
                            </div>

                            <?php if($index < count($steps) - 1): ?>
                                <div class="w-8 sm:w-12 lg:w-16 h-0.5 rounded-full progress-line <?php echo e($step['number'] < $current ? 'active bg-gradient-to-r from-green-500 to-green-600' : 'bg-blue-300'); ?>"></div>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Area -->
    <div class="w-full px-6 py-6">
        <?php echo $__env->yieldContent('step_content'); ?>
        
        <!-- Navigation Buttons -->
        <div class="mt-8 flex justify-between items-center">
            <div>
                <?php if($currentStep > 1): ?>
                    <button type="button" id="prevBtn" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-semibold transition-all duration-200">
                        ← Previous Step
                    </button>
                <?php endif; ?>
            </div>
            <div class="flex space-x-3">
                <button type="button" id="nextBtn" class="px-8 py-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white rounded-lg font-semibold transition-all duration-200 shadow-lg hover:shadow-xl">
                    ✅ Update Product
                </button>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<!-- Product Edit AJAX Utilities -->
<script src="<?php echo e(asset('admin_assets/js/product-edit-ajax.js')); ?>"></script>

<script>
function navigateToStep(stepNumber) {
    const routes = {
        1: '<?php echo e(route("admin.products.edit.step1", $product->id ?? 0)); ?>',
        2: '<?php echo e(route("admin.products.edit.step2", $product->id ?? 0)); ?>',
        3: '<?php echo e(route("admin.products.edit.step3", $product->id ?? 0)); ?>',
        4: '<?php echo e(route("admin.products.edit.step4", $product->id ?? 0)); ?>',
        5: '<?php echo e(route("admin.products.edit.step5", $product->id ?? 0)); ?>',
        6: '<?php echo e(route("admin.products.edit.step6", $product->id ?? 0)); ?>'
    };
    if (routes[stepNumber]) {
        window.location.href = routes[stepNumber];
    }
}

function showNotification(message, type = 'info') {
    // Simple notification system
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg text-white font-semibold z-50 transition-all duration-300 ${
        type === 'success' ? 'bg-green-500' : 
        type === 'error' ? 'bg-red-500' : 'bg-orange-500'
    }`;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.opacity = '0';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\ESS_Project1\resources\views/admin/products/edit/_layout.blade.php ENDPATH**/ ?>