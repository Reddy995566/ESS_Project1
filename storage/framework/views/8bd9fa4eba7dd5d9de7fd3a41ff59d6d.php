<?php $__env->startSection('step_title', 'Step 3: Categories & Organization'); ?>
<?php $__env->startSection('step_description', 'Organize your product with categories, brands, and tags'); ?>

<?php $__env->startSection('step_content'); ?>
    <?php
        $currentStep = 3;
        $prevStepRoute = '/seller/products/' . $product->id . '/edit?step=2';
    ?>

    <form id="stepForm" action="<?php echo e(route('seller.products.update', $product->id)); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        <input type="hidden" name="step" value="3">

        <div class="bg-white rounded-xl shadow-lg border border-gray-200">
            <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-orange-50 to-yellow-50">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-gradient-to-r from-orange-600 to-yellow-600 rounded-lg flex items-center justify-center shadow-lg">
                        <span class="text-white text-xl">🏷️</span>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Categories & Organization</h2>
                        <p class="text-gray-600 font-medium">Organize your product for better discovery</p>
                    </div>
                </div>
            </div>

            <div class="p-8 space-y-6">
                <!-- Category -->
                <div>
                    <label class="block text-sm font-semibold text-gray-800 mb-2">Category <span class="text-red-500">*</span></label>
                    <select name="category_id" id="categorySelect" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Category</option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($cat->id); ?>" <?php echo e(old('category_id', $product->category_id) == $cat->id ? 'selected' : ''); ?>>
                                <?php echo e($cat->name); ?>

                            </option>
                            <?php $__currentLoopData = $cat->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($child->id); ?>" <?php echo e(old('category_id', $product->category_id) == $child->id ? 'selected' : ''); ?>>
                                    &nbsp;&nbsp;&nbsp;→ <?php echo e($child->name); ?>

                                </option>
                                <?php $__currentLoopData = $child->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grandchild): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($grandchild->id); ?>" <?php echo e(old('category_id', $product->category_id) == $grandchild->id ? 'selected' : ''); ?>>
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;→→ <?php echo e($grandchild->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Choose main category</p>
                    <?php $__errorArgs = ['category_id'];
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

                <!-- Fabric -->
                <div>
                    <label class="block text-sm font-semibold text-gray-800 mb-2">Fabric <span class="text-gray-500 text-xs">(Optional)</span></label>
                    <select name="fabric_id" id="fabricSelect" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Fabric</option>
                        <?php $__currentLoopData = $fabrics; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fabric): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($fabric->id); ?>" <?php echo e(old('fabric_id', $product->fabric_id) == $fabric->id ? 'selected' : ''); ?>>
                                <?php echo e($fabric->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Optional fabric type</p>
                    <?php $__errorArgs = ['fabric_id'];
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

                <!-- Brand -->
                <div>
                    <label class="block text-sm font-semibold text-gray-800 mb-2">Brand <span class="text-gray-500 text-xs">(Optional)</span></label>
                    <select name="brand_id" id="brandSelect" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Brand</option>
                        <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($brand->id); ?>" <?php echo e(old('brand_id', $product->brand_id) == $brand->id ? 'selected' : ''); ?>>
                                <?php echo e($brand->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Optional brand</p>
                    <?php $__errorArgs = ['brand_id'];
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

                <!-- SKU -->
                <div>
                    <label class="block text-sm font-semibold text-gray-800 mb-2">SKU <span class="text-blue-500 text-xs">(Auto)</span></label>
                    <input type="text" name="sku" id="productSku" readonly value="<?php echo e(old('sku', $product->sku ?? '')); ?>" class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed" placeholder="PRD-XXXXXXXX">
                    <p class="text-xs text-blue-500 mt-1">✨ Auto-generated code</p>
                    <?php $__errorArgs = ['sku'];
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

                <!-- Collections (Multiple Select) -->
                <div>
                    <label class="block text-sm font-semibold text-gray-800 mb-2">Collections <span class="text-gray-500 text-xs">(Multiple - Hold Ctrl/Cmd to select multiple)</span></label>
                    <select name="collections[]" id="collectionsSelect" multiple size="5" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <?php $__currentLoopData = $collections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $collection): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($collection->id); ?>" 
                                <?php echo e(in_array($collection->id, old('collections', optional($product->collections)->pluck('id')->toArray() ?? [])) ? 'selected' : ''); ?>>
                                <?php echo e($collection->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Hold Ctrl (Windows) or Cmd (Mac) to select multiple collections</p>
                    <div id="selectedCollectionsDisplay" class="mt-2 flex flex-wrap gap-2"></div>
                </div>

                <!-- Tags (Multiple Select) -->
                <div>
                    <label class="block text-sm font-semibold text-gray-800 mb-2">Tags <span class="text-gray-500 text-xs">(Multiple - Hold Ctrl/Cmd to select multiple)</span></label>
                    <select name="tags[]" id="tagsSelect" multiple size="5" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <?php $__currentLoopData = $tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($tag->id); ?>" 
                                <?php echo e(in_array($tag->id, old('tags', optional($product->tags)->pluck('id')->toArray() ?? [])) ? 'selected' : ''); ?>>
                                <?php echo e($tag->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Hold Ctrl (Windows) or Cmd (Mac) to select multiple tags</p>
                    <div id="selectedTagsDisplay" class="mt-2 flex flex-wrap gap-2"></div>
                </div>
            </div>
        </div>
    </form>

    <?php $__env->startPush('scripts'); ?>
        <script>
            // Pure Vanilla JavaScript - No Alpine.js
            document.addEventListener('DOMContentLoaded', function() {
                // Display selected collections
                const collectionsSelect = document.getElementById('collectionsSelect');
                const collectionsDisplay = document.getElementById('selectedCollectionsDisplay');
                
                function updateCollectionsDisplay() {
                    const selected = Array.from(collectionsSelect.selectedOptions);
                    collectionsDisplay.innerHTML = '';
                    
                    selected.forEach(option => {
                        const badge = document.createElement('span');
                        badge.className = 'px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-sm font-medium';
                        badge.textContent = option.text;
                        collectionsDisplay.appendChild(badge);
                    });
                    
                    console.log('Collections selected:', selected.map(o => o.value));
                }
                
                collectionsSelect.addEventListener('change', updateCollectionsDisplay);
                updateCollectionsDisplay(); // Initial display
                
                // Display selected tags
                const tagsSelect = document.getElementById('tagsSelect');
                const tagsDisplay = document.getElementById('selectedTagsDisplay');
                
                function updateTagsDisplay() {
                    const selected = Array.from(tagsSelect.selectedOptions);
                    tagsDisplay.innerHTML = '';
                    
                    selected.forEach(option => {
                        const badge = document.createElement('span');
                        badge.className = 'px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-medium';
                        badge.textContent = option.text;
                        tagsDisplay.appendChild(badge);
                    });
                    
                    console.log('Tags selected:', selected.map(o => o.value));
                }
                
                tagsSelect.addEventListener('change', updateTagsDisplay);
                updateTagsDisplay(); // Initial display
                
                // Before form submit - ensure all selected options are properly set
                window.addEventListener('beforeFormSubmit', function() {
                    console.log('Before form submit - ensuring selections are set');
                    
                    // Collections
                    const collectionsSelected = Array.from(collectionsSelect.selectedOptions);
                    console.log('Collections to submit:', collectionsSelected.map(o => o.value));
                    
                    // Tags
                    const tagsSelected = Array.from(tagsSelect.selectedOptions);
                    console.log('Tags to submit:', tagsSelected.map(o => o.value));
                });
            });
        </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('seller.products.edit._layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\ESS_Project1\resources\views/seller/products/edit/step3.blade.php ENDPATH**/ ?>