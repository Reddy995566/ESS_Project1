<?php $__env->startSection('step_title', 'Step 6: Final Settings'); ?>
<?php $__env->startSection('step_description', 'Complete product configuration and publish settings'); ?>

<?php $__env->startSection('step_content'); ?>
    <?php
        $currentStep = 6;
        $prevStepRoute = '/seller/products/' . $product->id . '/edit?step=5';
    ?>

    <form id="stepForm" action="<?php echo e(route('seller.products.update', $product->id)); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        <input type="hidden" name="step" value="6">
        <?php echo csrf_field(); ?>

        <div class="bg-white rounded-xl shadow-lg border border-gray-200">
            <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-purple-50">
                <div class="flex items-center space-x-3">
                    <div
                        class="w-12 h-12 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg flex items-center justify-center shadow-lg">
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
                            <label class="block text-sm font-semibold text-gray-800 mb-2">Product Status <span
                                    class="text-red-500">*</span></label>
                            <select name="status" required
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 hover:border-gray-400">
                                <option value="">Select status</option>
                                <option value="active" <?php echo e(old('status', $product->status ?? '') == 'active' ? 'selected' : ''); ?>>Active - Live on website</option>
                                <option value="draft" <?php echo e(old('status', $product->status ?? '') == 'draft' ? 'selected' : ''); ?>>Draft - Save for later</option>
                                <option value="inactive" <?php echo e(old('status', $product->status ?? '') == 'inactive' ? 'selected' : ''); ?>>Inactive - Hidden from website</option>
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
                            <label class="block text-sm font-semibold text-gray-800 mb-2">Visibility <span
                                    class="text-red-500">*</span></label>
                            <select name="visibility" required
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 hover:border-gray-400">
                                <option value="">Select visibility</option>
                                <option value="visible" <?php echo e(old('visibility', $product->visibility ?? '') == 'visible' ? 'selected' : ''); ?>>Visible - Everywhere</option>
                                <option value="catalog" <?php echo e(old('visibility', $product->visibility ?? '') == 'catalog' ? 'selected' : ''); ?>>Catalog Only</option>
                                <option value="search" <?php echo e(old('visibility', $product->visibility ?? '') == 'search' ? 'selected' : ''); ?>>Search Only</option>
                                <option value="hidden" <?php echo e(old('visibility', $product->visibility ?? '') == 'hidden' ? 'selected' : ''); ?>>Hidden</option>
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

                <!-- Homepage Display Options -->
                <div class="space-y-6">
                    <h3 class="text-lg font-bold text-gray-900 border-b border-gray-200 pb-2">🏠 Homepage Display Options</h3>
                    <p class="text-sm text-gray-600">Control where your product appears on the homepage</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Show as New Arrival -->
                        <div class="flex items-start space-x-3 p-4 border-2 border-gray-200 rounded-lg hover:border-blue-400 transition-all">
                            <input type="checkbox" name="is_new" id="is_new" value="1" 
                                <?php echo e(old('is_new', $product->is_new ?? false) ? 'checked' : ''); ?>

                                class="mt-1 w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <div>
                                <label for="is_new" class="font-semibold text-gray-800 cursor-pointer">🆕 New Arrival</label>
                                <p class="text-xs text-gray-600 mt-1">Show in "New Arrivals" section</p>
                            </div>
                        </div>

                        <!-- Show as Best Seller -->
                        <div class="flex items-start space-x-3 p-4 border-2 border-gray-200 rounded-lg hover:border-yellow-400 transition-all">
                            <input type="checkbox" name="is_bestseller" id="is_bestseller" value="1" 
                                <?php echo e(old('is_bestseller', $product->is_bestseller ?? false) ? 'checked' : ''); ?>

                                class="mt-1 w-5 h-5 text-yellow-600 border-gray-300 rounded focus:ring-yellow-500">
                            <div>
                                <label for="is_bestseller" class="font-semibold text-gray-800 cursor-pointer">⭐ Best Seller</label>
                                <p class="text-xs text-gray-600 mt-1">Mark as best selling product</p>
                            </div>
                        </div>

                        <!-- Show as Featured -->
                        <div class="flex items-start space-x-3 p-4 border-2 border-gray-200 rounded-lg hover:border-purple-400 transition-all">
                            <input type="checkbox" name="is_featured" id="is_featured" value="1" 
                                <?php echo e(old('is_featured', $product->is_featured ?? false) ? 'checked' : ''); ?>

                                class="mt-1 w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                            <div>
                                <label for="is_featured" class="font-semibold text-gray-800 cursor-pointer">✨ Featured</label>
                                <p class="text-xs text-gray-600 mt-1">Highlight as featured product</p>
                            </div>
                        </div>

                        <!-- Show as Trending -->
                        <div class="flex items-start space-x-3 p-4 border-2 border-gray-200 rounded-lg hover:border-pink-400 transition-all">
                            <input type="checkbox" name="is_trending" id="is_trending" value="1" 
                                <?php echo e(old('is_trending', $product->is_trending ?? false) ? 'checked' : ''); ?>

                                class="mt-1 w-5 h-5 text-pink-600 border-gray-300 rounded focus:ring-pink-500">
                            <div>
                                <label for="is_trending" class="font-semibold text-gray-800 cursor-pointer">🔥 Trending</label>
                                <p class="text-xs text-gray-600 mt-1">Show in trending products</p>
                            </div>
                        </div>

                        <!-- Show on Homepage -->
                        <div class="flex items-start space-x-3 p-4 border-2 border-gray-200 rounded-lg hover:border-green-400 transition-all">
                            <input type="checkbox" name="show_in_homepage" id="show_in_homepage" value="1" 
                                <?php echo e(old('show_in_homepage', $product->show_in_homepage ?? false) ? 'checked' : ''); ?>

                                class="mt-1 w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-green-500">
                            <div>
                                <label for="show_in_homepage" class="font-semibold text-gray-800 cursor-pointer">🏠 Show on Homepage</label>
                                <p class="text-xs text-gray-600 mt-1">Display in homepage sections</p>
                            </div>
                        </div>

                        <!-- Show as Sale -->
                        <div class="flex items-start space-x-3 p-4 border-2 border-gray-200 rounded-lg hover:border-red-400 transition-all">
                            <input type="checkbox" name="is_sale" id="is_sale" value="1" 
                                <?php echo e(old('is_sale', $product->is_sale ?? false) ? 'checked' : ''); ?>

                                class="mt-1 w-5 h-5 text-red-600 border-gray-300 rounded focus:ring-red-500">
                            <div>
                                <label for="is_sale" class="font-semibold text-gray-800 cursor-pointer">🏷️ On Sale</label>
                                <p class="text-xs text-gray-600 mt-1">Mark as sale product</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <p class="text-sm text-blue-800">
                            <strong>💡 Tip:</strong> Select multiple options to increase product visibility. Products marked as "New Arrival" or "Best Seller" will appear in special homepage sections.
                        </p>
                    </div>
                </div>

                <!-- Summary Section -->


                <!-- Final Action Buttons -->
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <h3 class="font-semibold text-green-800 mb-2">🎉 Ready to Update!</h3>
                    <p class="text-sm text-green-700 mb-4">You've completed all the steps. Review the summary above and
                        click "Update Product" to save your changes.</p>
                </div>
            </div>
        </div>
    </form>

    <?php $__env->startPush('scripts'); ?>
        <script>
            // Load summary from product data
            document.addEventListener('DOMContentLoaded', function () {
                // Product data from model
                const productData = <?php echo json_encode($product ?? [], 15, 512) ?>;

                // Handle Update Button Click
                const updateBtn = document.getElementById('updateBtn');
                const form = document.getElementById('stepForm');

                if (updateBtn && form) {
                    updateBtn.addEventListener('click', function (e) {
                        e.preventDefault();

                        // Show loading state
                        const originalText = updateBtn.innerHTML;
                        updateBtn.innerHTML = '<span>⏳ Updating...</span>';
                        updateBtn.disabled = true;
                        updateBtn.classList.add('opacity-75', 'cursor-not-allowed');

                        const formData = new FormData(form);

                        fetch(form.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json'
                            },
                            body: formData
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    if (window.showNotification) {
                                        window.showNotification('✅ Product updated successfully!', 'success');
                                    } else {
                                        alert('Product updated successfully!');
                                    }

                                    // Redirect to products list after a short delay
                                    setTimeout(() => {
                                        window.location.href = "<?php echo e(route('seller.products.index')); ?>";
                                    }, 1500);
                                } else {
                                    const errorMsg = data.message || 'Update failed';
                                    if (window.showNotification) {
                                        window.showNotification('❌ ' + errorMsg, 'error');
                                    } else {
                                        alert(errorMsg);
                                    }

                                    if (data.errors) {
                                        console.error('Validation errors:', data.errors);
                                        // Convert errors object to readable string
                                        let errorDetails = Object.values(data.errors).flat().join('\n');
                                        if (errorDetails) alert('Details:\n' + errorDetails);
                                    }
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                if (window.showNotification) {
                                    window.showNotification('❌ An error occurred. Check console for details.', 'error');
                                } else {
                                    alert('An error occurred. Check console for details.');
                                }
                            })
                            .finally(() => {
                                // Reset button
                                updateBtn.innerHTML = originalText;
                                updateBtn.disabled = false;
                                updateBtn.classList.remove('opacity-75', 'cursor-not-allowed');
                            });
                    });
                }
            });
        </script>
    <?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('seller.products.edit._layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\ESS_Project1\resources\views/seller/products/edit/step6.blade.php ENDPATH**/ ?>