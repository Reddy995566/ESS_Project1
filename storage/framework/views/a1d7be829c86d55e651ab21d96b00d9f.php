<?php $__env->startSection('step_title', 'Step 1: Basic Information'); ?>
<?php $__env->startSection('step_description', 'Enter essential product details'); ?>

<?php $__env->startSection('step_content'); ?>
<?php
    $currentStep = 1;
    $prevStepRoute = null;
?>

<form id="stepForm" action="<?php echo e(route('seller.products.create.step1.process')); ?>" method="POST">
    <?php echo csrf_field(); ?>
    
    <div class="bg-white rounded-xl shadow-lg border border-gray-200">
        <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg flex items-center justify-center shadow-lg">
                    <span class="text-white text-xl">📝</span>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Basic Information</h2>
                    <p class="text-gray-600 font-medium">Enter essential product details</p>
                </div>
            </div>
        </div>
        
        <div class="p-8 space-y-6">
            <!-- Product Name & Slug (Side by Side) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Product Name -->
                <div>
                    <label class="block text-sm font-semibold text-gray-800 mb-2">Product Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="productName" required 
                        value="<?php echo e(old('name', $productData['name'] ?? '')); ?>"
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 hover:border-gray-400"
                        placeholder="Enter product name"
                        oninput="updateSlug()">
                    <?php $__errorArgs = ['name'];
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

                <!-- URL Slug -->
                <div>
                    <label class="block text-sm font-semibold text-gray-800 mb-2">URL Slug <span class="text-blue-500 text-xs">(Auto-generated)</span></label>
                    <input type="text" name="slug" id="productSlug" readonly
                        value="<?php echo e(old('slug', $productData['slug'] ?? '')); ?>"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed"
                        placeholder="will-be-generated-from-name">
                    <p class="text-xs text-blue-500 mt-1">✨ Automatically generated from product name</p>
                    <?php $__errorArgs = ['slug'];
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

            <!-- Detailed Description -->
            <div>
                <label class="block text-sm font-semibold text-gray-800 mb-2">Detailed Description <span class="text-red-500">*</span></label>
                <div class="quill-editor-wrapper" id="detailedDescription">
                    <div id="detailedDescriptionEditor" style="height: 300px;"></div>
                </div>
                <textarea name="description" id="detailedDescriptionHidden" class="hidden" required><?php echo e(old('description', $productData['description'] ?? '')); ?></textarea>
                <p class="text-xs text-gray-500 mt-1">Complete product description with features, benefits, and specifications</p>
                <?php $__errorArgs = ['description'];
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

            <!-- Washing Instructions -->
            <div>
                <label class="block text-sm font-semibold text-gray-800 mb-2">Washing Instructions <span class="text-gray-500 text-xs">(Optional)</span></label>
                <div class="quill-editor-wrapper" id="washingInstructions">
                    <div id="washingInstructionsEditor" style="height: 150px;"></div>
                </div>
                <textarea name="washing_instructions" id="washingInstructionsHidden" class="hidden"><?php echo e(old('washing_instructions', $productData['washing_instructions'] ?? '')); ?></textarea>
                <p class="text-xs text-gray-500 mt-1">Care and washing instructions for the product</p>
                <?php $__errorArgs = ['washing_instructions'];
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

            <!-- Shipping Information -->
            <div>
                <label class="block text-sm font-semibold text-gray-800 mb-2">Shipping Information <span class="text-gray-500 text-xs">(Optional)</span></label>
                <div class="quill-editor-wrapper" id="shippingInformation">
                    <div id="shippingInformationEditor" style="height: 150px;"></div>
                </div>
                <textarea name="shipping_information" id="shippingInformationHidden" class="hidden"><?php echo e(old('shipping_information', $productData['shipping_information'] ?? '')); ?></textarea>
                <p class="text-xs text-gray-500 mt-1">Details about shipping, delivery times, and costs</p>
                <?php $__errorArgs = ['shipping_information'];
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

            <!-- Returns & Refunds -->
            <div>
                <label class="block text-sm font-semibold text-gray-800 mb-2">Returns & Refunds <span class="text-gray-500 text-xs">(Optional)</span></label>
                <div class="quill-editor-wrapper" id="returnsRefunds">
                    <div id="returnsRefundsEditor" style="height: 150px;"></div>
                </div>
                <textarea name="returns_refunds" id="returnsRefundsHidden" class="hidden"><?php echo e(old('returns_refunds', $productData['returns_refunds'] ?? '')); ?></textarea>
                <p class="text-xs text-gray-500 mt-1">Policy regarding returns, exchanges, and refunds</p>
                <?php $__errorArgs = ['returns_refunds'];
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
</form>

<?php $__env->startPush('scripts'); ?>
<!-- Quill Editor -->
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

<script>
// Initialize Quill editors
let detailedDescriptionQuill = new Quill('#detailedDescriptionEditor', {
    theme: 'snow',
    placeholder: 'Enter detailed product description...',
    modules: {
        toolbar: [
            [{ 'header': [1, 2, 3, false] }],
            ['bold', 'italic', 'underline', 'strike'],
            [{ 'color': [] }, { 'background': [] }],
            ['link'],
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            [{ 'indent': '-1'}, { 'indent': '+1' }],
            ['clean']
        ]
    }
});

let washingInstructionsQuill = new Quill('#washingInstructionsEditor', {
    theme: 'snow',
    placeholder: 'Enter washing instructions...',
    modules: {
        toolbar: [
            ['bold', 'italic', 'underline'],
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            ['clean']
        ]
    }
});

let shippingInformationQuill = new Quill('#shippingInformationEditor', {
    theme: 'snow',
    placeholder: 'Enter shipping information...',
    modules: {
        toolbar: [
            ['bold', 'italic', 'underline'],
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            ['clean']
        ]
    }
});

let returnsRefundsQuill = new Quill('#returnsRefundsEditor', {
    theme: 'snow',
    placeholder: 'Enter returns & refunds policy...',
    modules: {
        toolbar: [
            ['bold', 'italic', 'underline'],
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            ['clean']
        ]
    }
});

// Set existing content
if (document.getElementById('detailedDescriptionHidden').value) {
    detailedDescriptionQuill.root.innerHTML = document.getElementById('detailedDescriptionHidden').value;
}

if (document.getElementById('washingInstructionsHidden').value) {
    washingInstructionsQuill.root.innerHTML = document.getElementById('washingInstructionsHidden').value;
}

if (document.getElementById('shippingInformationHidden').value) {
    shippingInformationQuill.root.innerHTML = document.getElementById('shippingInformationHidden').value;
}

if (document.getElementById('returnsRefundsHidden').value) {
    returnsRefundsQuill.root.innerHTML = document.getElementById('returnsRefundsHidden').value;
}

// Update hidden fields on content change
detailedDescriptionQuill.on('text-change', function() {
    document.getElementById('detailedDescriptionHidden').value = detailedDescriptionQuill.root.innerHTML;
});

washingInstructionsQuill.on('text-change', function() {
    document.getElementById('washingInstructionsHidden').value = washingInstructionsQuill.root.innerHTML;
});

shippingInformationQuill.on('text-change', function() {
    document.getElementById('shippingInformationHidden').value = shippingInformationQuill.root.innerHTML;
});

returnsRefundsQuill.on('text-change', function() {
    document.getElementById('returnsRefundsHidden').value = returnsRefundsQuill.root.innerHTML;
});

// Auto-generate slug from product name
function updateSlug() {
    const name = document.getElementById('productName').value;
    const slug = name
        .toLowerCase()
        .replace(/[^a-z0-9\s-]/g, '') // Remove special characters except letters, numbers, spaces, hyphens
        .replace(/\s+/g, '-') // Replace spaces with hyphens
        .replace(/-+/g, '-') // Replace multiple hyphens with single
        .replace(/^-+|-+$/g, '') // Remove leading/trailing hyphens
        .trim();
    
    document.getElementById('productSlug').value = slug || 'product-slug';
    
    // Store product name and slug in session storage for SKU generation and SEO preview
    sessionStorage.setItem('productName', name);
    sessionStorage.setItem('productSlug', slug || 'product-slug');
}

// Store initial product name on page load
window.addEventListener('DOMContentLoaded', function() {
    const productNameField = document.getElementById('productName');
    if (productNameField && productNameField.value) {
        sessionStorage.setItem('productName', productNameField.value);
    }
});

// Form submission handler
document.getElementById('stepForm').addEventListener('submit', function(e) {
    // Update hidden fields before submission
    document.getElementById('detailedDescriptionHidden').value = detailedDescriptionQuill.root.innerHTML;
    document.getElementById('washingInstructionsHidden').value = washingInstructionsQuill.root.innerHTML;
    document.getElementById('shippingInformationHidden').value = shippingInformationQuill.root.innerHTML;
    document.getElementById('returnsRefundsHidden').value = returnsRefundsQuill.root.innerHTML;
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('seller.products.create._layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\ESS_Project1\resources\views/seller/products/create/step1.blade.php ENDPATH**/ ?>