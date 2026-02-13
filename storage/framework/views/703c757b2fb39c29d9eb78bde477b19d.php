<?php
    $isEditMode = isset($product) && $product !== null;
    $pageTitle = $isEditMode ? 'Edit Product' : 'Create Product';
    $pageSubtitle = $isEditMode ? 'Update your product details' : 'Build your product catalog';
?>

<?php $__env->startSection('title', $pageTitle . ' - Step ' . ($currentStep ?? 1) . ' of 6'); ?>

<?php $__env->startPush('styles'); ?>
<style>
/* Quill Editor Custom Styles */
.quill-editor-wrapper {
    border: 2px solid #d1d5db;
    border-radius: 0.5rem;
    overflow: hidden;
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
    border-color: #6366f1;
    background-color: #f8fafc;
}

.image-upload-area.dragover {
    border-color: #6366f1;
    background-color: #eef2ff;
    transform: scale(1.02);
}

/* Progress Animation */
.progress-line {
    transition: all 0.3s ease;
}

.progress-line.active {
    background: linear-gradient(to right, #6366f1, #4f46e5);
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
    $isEditMode = isset($product) && $product !== null;
    $productId = $isEditMode ? $product->id : null;
?>

<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-md border-b border-gray-200">
        <div class="w-full px-6 py-5">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-5">
                    <a href="<?php echo e(route('seller.products.index')); ?>" class="p-2.5 hover:bg-indigo-50 rounded-xl transition-all duration-200 hover:scale-105 group">
                        <svg class="w-5 h-5 text-gray-600 group-hover:text-indigo-600 transition-colors" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.707 14.707a1 1 0 01-1.414 0L2.586 11 6.293 7.293a1 1 0 111.414 1.414L5.414 11l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 tracking-tight"><?php echo e($pageTitle); ?></h1>
                        <p class="text-gray-600 font-medium"><?php echo e($pageSubtitle); ?></p>
                    </div>
                </div>

                <div class="flex items-center space-x-3">
                    <?php if($isEditMode): ?>
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-semibold">Pending Approval</span>
                    <?php endif; ?>
                    <?php if($currentStep > 1): ?>
                    <a href="<?php echo e($prevStepRoute ?? ($isEditMode ? route('seller.products.edit', ['id' => $productId, 'step' => $currentStep - 1]) : '#')); ?>" class="px-5 py-2.5 border-2 border-gray-300 text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-400 rounded-lg font-semibold transition-all duration-200 hover:shadow-sm">
                        ← Previous
                    </a>
                    <?php endif; ?>
                    <?php if(!$isEditMode): ?>
                    <button type="button" id="clearSessionBtn" class="px-5 py-2.5 border-2 border-red-300 text-red-700 bg-white hover:bg-red-50 hover:border-red-400 rounded-lg font-semibold transition-all duration-200 hover:shadow-sm">
                        🗑️ Clear & Reset
                    </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Steps -->
    <div class="bg-gradient-to-r from-gray-50 to-indigo-50 border-b border-gray-200">
        <div class="w-full px-4 sm:px-6 py-4 sm:py-6">
            <div class="overflow-x-auto">
                <div class="flex items-center justify-center">
                    <div class="flex items-center space-x-4 sm:space-x-6 lg:space-x-8 xl:space-x-10">
                        <?php
                            $steps = [
                                ['number' => 1, 'actual' => 1, 'title' => 'Basic Info', 'description' => 'Product details'],
                                ['number' => 2, 'actual' => 2, 'title' => 'Variants', 'description' => 'Colors & sizes'],
                                // ['number' => 2, 'actual' => 2, 'title' => 'Media', 'description' => 'Images & videos'], // Step 2 hidden
                                ['number' => 3, 'actual' => 3, 'title' => 'Categories', 'description' => 'Organization'],
                                ['number' => 4, 'actual' => 4, 'title' => 'Inventory', 'description' => 'Stock & pricing'],
                                ['number' => 5, 'actual' => 5, 'title' => 'SEO', 'description' => 'Optimization'],
                                ['number' => 6, 'actual' => 6, 'title' => 'Settings', 'description' => 'Configuration']
                            ];
                            $current = $currentStep;
                        ?>
                        
                        <?php $__currentLoopData = $steps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex flex-col items-center space-y-1 sm:space-y-2 cursor-pointer group step-indicator <?php echo e($step['actual'] == $current ? 'active' : ''); ?> <?php echo e($step['actual'] < $current ? 'completed' : ''); ?>" 
                                 onclick="navigateToStep(<?php echo e($step['actual']); ?>)">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center text-xs sm:text-sm font-bold shadow-lg group-hover:scale-110 transition-transform duration-200
                                    <?php echo e($step['actual'] == $current ? 'bg-gradient-to-r from-indigo-600 to-indigo-700 text-white' : ''); ?>

                                    <?php echo e($step['actual'] < $current ? 'bg-gradient-to-r from-green-600 to-green-700 text-white' : ''); ?>

                                    <?php echo e($step['actual'] > $current ? 'bg-gray-300 text-gray-600' : ''); ?>">
                                    <?php echo e($step['actual'] < $current ? '✓' : $step['number']); ?>

                                </div>
                                <div class="text-center">
                                    <div class="text-xs sm:text-sm font-semibold <?php echo e($step['actual'] == $current ? 'text-gray-900' : ($step['actual'] < $current ? 'text-green-700' : 'text-gray-600')); ?>"><?php echo e($step['title']); ?></div>
                                    <div class="text-xs <?php echo e($step['actual'] == $current ? 'text-gray-500' : ($step['actual'] < $current ? 'text-green-500' : 'text-gray-400')); ?> hidden sm:block"><?php echo e($step['description']); ?></div>
                                </div>
                            </div>

                            <?php if($index < count($steps) - 1): ?>
                                <div class="w-8 sm:w-12 lg:w-16 h-0.5 rounded-full progress-line <?php echo e($step['actual'] < $current ? 'active bg-gradient-to-r from-green-500 to-green-600' : 'bg-gray-300'); ?>"></div>
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
                <?php if($currentStep < 6): ?>
                    <button type="button" id="nextBtn" class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white rounded-lg font-semibold transition-all duration-200 shadow-lg hover:shadow-xl">
                        Continue to Step <?php echo e($currentStep + 1); ?> →
                    </button>
                <?php else: ?>
                    <button type="button" id="nextBtn" class="px-8 py-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white rounded-lg font-semibold transition-all duration-200 shadow-lg hover:shadow-xl">
                        ✨ Submit for Approval
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<!-- Alpine.js -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<!-- SortableJS for drag & drop -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<script>
const isEditMode = <?php echo e($isEditMode ? 'true' : 'false'); ?>;
const editProductId = <?php echo e($productId ?? 'null'); ?>;

function navigateToStep(stepNumber) {
    if (isEditMode && editProductId) {
        // Edit mode: go to edit route
        window.location.href = '/seller/products/' + editProductId + '/edit?step=' + stepNumber;
    } else {
        // Create mode: use predefined routes
        const routes = {
            1: '<?php echo e(route("seller.products.create.step1")); ?>',
            2: '<?php echo e(route("seller.products.create.step2")); ?>',
            3: '<?php echo e(route("seller.products.create.step3")); ?>',
            4: '<?php echo e(route("seller.products.create.step4")); ?>',
            5: '<?php echo e(route("seller.products.create.step5")); ?>',
            6: '<?php echo e(route("seller.products.create.step6")); ?>'
        };
        if (routes[stepNumber]) {
            window.location.href = routes[stepNumber];
        }
    }
}

document.getElementById('clearSessionBtn')?.addEventListener('click', function() {
    if (confirm('Are you sure you want to clear all progress and start over? This cannot be undone.')) {
        fetch('<?php echo e(route("seller.products.create.clear-session")); ?>', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        }).then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Progress cleared! Redirecting to Step 1...', 'success');
                setTimeout(() => {
                    window.location.href = '<?php echo e(route("seller.products.create.step1")); ?>';
                }, 1000);
            } else {
                showNotification('Failed to clear progress', 'error');
            }
        }).catch(error => {
            showNotification('Failed to clear progress', 'error');
        });
    }
});

// Next button handler - submit the form
document.getElementById('nextBtn')?.addEventListener('click', function() {
    const form = document.getElementById('stepForm');
    if (form) {
        form.submit();
    }
});

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg text-white font-semibold z-50 transition-all duration-300 ${type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-indigo-500'}`;
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

<?php echo $__env->make('seller.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\ESS_Project1\resources\views/seller/products/create/_layout.blade.php ENDPATH**/ ?>