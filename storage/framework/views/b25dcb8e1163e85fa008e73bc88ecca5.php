<?php $__env->startSection('step_title', 'Step 4: Inventory & Pricing'); ?>
<?php $__env->startSection('step_description', 'Set pricing and manage stock levels'); ?>

<?php $__env->startSection('step_content'); ?>
<?php
    $currentStep = 4;
    $prevStepRoute = route('seller.products.create.step3');
?>

<form id="stepForm" action="<?php echo e(route('seller.products.create.step4.process')); ?>" method="POST">
    <?php echo csrf_field(); ?>
    
    <div class="bg-white rounded-xl shadow-lg border border-gray-200">
        <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-cyan-50 to-indigo-50">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-gradient-to-r from-cyan-600 to-indigo-600 rounded-lg flex items-center justify-center shadow-lg">
                    <span class="text-white text-xl">💰</span>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Inventory & Pricing</h2>
                    <p class="text-gray-600 font-medium">Set prices and manage stock levels</p>
                </div>
            </div>
        </div>
        
        <div class="p-8 space-y-6">
            <!-- Pricing Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-800 mb-2">Regular Price <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">₹</span>
                        <input type="number" name="price" step="0.01" min="0" required 
                            value="<?php echo e(old('price', $productData['price'] ?? '')); ?>"
                            class="w-full pl-8 pr-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 hover:border-gray-400"
                            placeholder="0.00">
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Base selling price</p>
                    <?php $__errorArgs = ['price'];
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

                <div>
                    <label class="block text-sm font-semibold text-gray-800 mb-2">Sale Price</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">₹</span>
                        <input type="number" name="sale_price" step="0.01" min="0" 
                            value="<?php echo e(old('sale_price', $productData['sale_price'] ?? '')); ?>"
                            class="w-full pl-8 pr-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 hover:border-gray-400"
                            placeholder="0.00">
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Discounted price (optional)</p>
                    <?php $__errorArgs = ['sale_price'];
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

            <!-- Stock Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-800 mb-2">Stock Quantity <span class="text-red-500">*</span></label>
                    <input type="number" name="stock" min="0" required 
                        value="<?php echo e(old('stock', $productData['stock'] ?? '')); ?>"
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 hover:border-gray-400"
                        placeholder="0">
                    <p class="text-xs text-gray-500 mt-1">Available units in stock</p>
                    <?php $__errorArgs = ['stock'];
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

                <div>
                    <label class="block text-sm font-semibold text-gray-800 mb-2">Stock Status <span class="text-red-500">*</span></label>
                    <select name="stock_status" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 hover:border-gray-400">
                        <option value="in_stock" <?php echo e(old('stock_status', $productData['stock_status'] ?? 'in_stock') == 'in_stock' ? 'selected' : ''); ?>>In Stock</option>
                        <option value="out_of_stock" <?php echo e(old('stock_status', $productData['stock_status'] ?? 'in_stock') == 'out_of_stock' ? 'selected' : ''); ?>>Out of Stock</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Current availability status</p>
                    <?php $__errorArgs = ['stock_status'];
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

            <!-- Pricing Tips -->
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <h3 class="font-semibold text-green-800 mb-2">💡 Pricing Tips</h3>
                <ul class="text-sm text-green-700 space-y-1">
                    <li>• Set competitive prices by researching similar products</li>
                    <li>• Use sale price for temporary discounts and promotions</li>
                    <li>• Ensure sale price is lower than regular price for valid discounts</li>
                    <li>• Keep adequate stock levels to avoid out-of-stock situations</li>
                </ul>
            </div>
        </div>
    </div>
</form>

<?php $__env->startPush('scripts'); ?>
<script>
// Basic price validation
document.addEventListener('DOMContentLoaded', function() {
    const priceInput = document.querySelector('input[name="price"]');
    const salePriceInput = document.querySelector('input[name="sale_price"]');
    
    function validatePricing() {
        const price = parseFloat(priceInput.value) || 0;
        const salePrice = parseFloat(salePriceInput.value) || 0;
        
        // Show warning if sale price is higher than regular price
        if (salePrice > 0 && salePrice >= price && price > 0) {
            salePriceInput.style.borderColor = '#ef4444';
            } else {
            salePriceInput.style.borderColor = '';
        }
    }
    
    salePriceInput?.addEventListener('blur', validatePricing);
    priceInput?.addEventListener('blur', validatePricing);
    
    // Handle form submission
    document.getElementById('nextBtn')?.addEventListener('click', function() {
        document.getElementById('stepForm').submit();
    });

    document.getElementById('prevBtn')?.addEventListener('click', function() {
        window.location.href = '<?php echo e(route("seller.products.create.step3")); ?>';
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('seller.products.create._layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\ESS_Project1\resources\views/seller/products/create/step4.blade.php ENDPATH**/ ?>