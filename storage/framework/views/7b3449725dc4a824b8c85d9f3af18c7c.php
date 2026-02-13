<?php $__env->startSection('step_title', 'Step 6: Final Settings'); ?>
<?php $__env->startSection('step_description', 'Complete product configuration and publish settings'); ?>

<?php $__env->startSection('step_content'); ?>
<?php
    $currentStep = 6;
    $prevStepRoute = route('admin.products.edit.step5', $product->id);
?>

<form id="stepForm" action="<?php echo e(route('admin.products.edit.step6.process', $product->id)); ?>" method="POST">
    <?php echo csrf_field(); ?>
    
    <div class="bg-white rounded-xl shadow-lg border border-gray-200">
        <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-purple-50">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg flex items-center justify-center shadow-lg">
                    <span class="text-white text-xl">⚙️</span>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Final Settings</h2>
                    <p class="text-gray-600 font-medium">Complete product configuration and publish settings</p>
                </div>
            </div>
        </div>
        
        <div class="p-8 space-y-8">
            <!-- Error Messages -->
            <?php if($errors->any()): ?>
                <div class="bg-red-50 border-2 border-red-500 rounded-xl p-6">
                    <h3 class="text-lg font-bold text-red-800 mb-2">❌ Error Updating Product</h3>
                    <ul class="list-disc list-inside text-red-700">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- Product Status & Visibility -->
            <div class="space-y-6">
                <h3 class="text-lg font-bold text-gray-900 border-b border-gray-200 pb-2">📋 Publication Settings</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Product Status -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-800 mb-2">Product Status <span class="text-red-500">*</span></label>
                        <select name="status" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 hover:border-gray-400">
                            <option value="">Select status</option>
                            <option value="active" <?php echo e(old('status', $productData['status'] ?? '') == 'active' ? 'selected' : ''); ?>>Active - Live on website</option>
                            <option value="draft" <?php echo e(old('status', $productData['status'] ?? '') == 'draft' ? 'selected' : ''); ?>>Draft - Save for later</option>
                            <option value="inactive" <?php echo e(old('status', $productData['status'] ?? '') == 'inactive' ? 'selected' : ''); ?>>Inactive - Hidden from website</option>
                        </select>
                        <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Visibility -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-800 mb-2">Visibility <span class="text-red-500">*</span></label>
                        <select name="visibility" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 hover:border-gray-400">
                            <option value="">Select visibility</option>
                            <option value="visible" <?php echo e(old('visibility', $productData['visibility'] ?? '') == 'visible' ? 'selected' : ''); ?>>Visible - Everywhere</option>
                            <option value="catalog" <?php echo e(old('visibility', $productData['visibility'] ?? '') == 'catalog' ? 'selected' : ''); ?>>Catalog Only</option>
                            <option value="search" <?php echo e(old('visibility', $productData['visibility'] ?? '') == 'search' ? 'selected' : ''); ?>>Search Only</option>
                            <option value="hidden" <?php echo e(old('visibility', $productData['visibility'] ?? '') == 'hidden' ? 'selected' : ''); ?>>Hidden</option>
                        </select>
                        <?php $__errorArgs = ['visibility'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>

            <!-- Summary Section -->


            <!-- Final Action Buttons -->
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <h3 class="font-semibold text-green-800 mb-2">🎉 Ready to Update!</h3>
                <p class="text-sm text-green-700 mb-4">You've completed all the steps. Review the summary above and click "Update Product" to save your changes.</p>
            </div>
        </div>
    </div>
</form>

<?php $__env->startPush('scripts'); ?>
<script>
// Load summary from product data
document.addEventListener('DOMContentLoaded', function() {
    // This would be populated from session data or product data
    const productData = <?php echo json_encode($productData ?? [], 15, 512) ?>;
    

});
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.products.edit._layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\ESS_Project1\resources\views/admin/products/edit/step6.blade.php ENDPATH**/ ?>