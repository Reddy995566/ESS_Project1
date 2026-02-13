@extends('admin.products.edit._layout')

@section('step_title', 'Step 2: Product Variants')
@section('step_description', 'Add color, size, and stock variants for your product')

@section('step_content')
@php
    $currentStep = 2;
    $prevStepRoute = route('admin.products.edit.step1', $product->id);
@endphp

<form id="stepForm" action="{{ route('admin.products.edit.step2.process', $product->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="bg-white rounded-xl shadow-lg border border-gray-200">
        <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-pink-50">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-gradient-to-r from-purple-600 to-pink-600 rounded-lg flex items-center justify-center shadow-lg">
                        <span class="text-white text-xl">🎨</span>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Product Variants</h2>
                        <p class="text-gray-600 font-medium">Add color, size, stock, and images for each variant</p>
                    </div>
                </div>
                <button type="button" id="addVariantBtn" class="px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white rounded-lg font-semibold transition-all duration-200 shadow-lg hover:shadow-xl flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Add Variant</span>
                </button>
            </div>
        </div>
        
        <div class="p-8">
            <!-- Variants Container -->
            <div id="variantsContainer" class="space-y-6">
                <!-- Variants will be appended here -->
            </div>
            
            <!-- Empty State -->
            <div id="emptyState" class="text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">No Variants Added</h3>
                <p class="text-gray-500 mb-4">Click "Add Variant" to create your first product variant</p>
            </div>
            
            <!-- Hidden Inputs for Form Submission -->
            <input type="hidden" name="variants_data" id="variantsDataInput">
        </div>
    </div>
</form>

@push('scripts')
<script>
// Global state
let variants = [];
let variantIdCounter = 1;

// Available colors and sizes from server
const availableColors = @json($colors);
const availableSizes = @json($sizes);

// DOM Elements
const variantsContainer = document.getElementById('variantsContainer');
const emptyState = document.getElementById('emptyState');
const addVariantBtn = document.getElementById('addVariantBtn');
const variantsDataInput = document.getElementById('variantsDataInput');

// Add Variant Button Click
addVariantBtn.addEventListener('click', function() {
    addVariant();
});

// Add Variant Function
function addVariant(data = null) {
    const variantId = variantIdCounter++;
    
    const variant = {
        id: variantId,
        color_id: data?.color_id || '',
        size_id: data?.size_id || '',
        stock: data?.stock || 10,
        images: data?.images || []
    };
    
    variants.push(variant);
    renderVariant(variant);
    updateEmptyState();
    updateHiddenInput();
}

// Render Variant Row
function renderVariant(variant) {
    const variantDiv = document.createElement('div');
    variantDiv.id = `variant_${variant.id}`;
    variantDiv.className = 'bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-6 border-2 border-gray-200 shadow-sm hover:shadow-md transition-all';
    
    variantDiv.innerHTML = `
        <div class="flex items-start justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-800 flex items-center space-x-2">
                <span class="w-8 h-8 bg-purple-600 text-white rounded-full flex items-center justify-center text-sm font-bold">${variants.length}</span>
                <span>Variant #${variant.id}</span>
            </h3>
            <button type="button" onclick="removeVariant(${variant.id})" class="text-red-600 hover:text-red-800 hover:bg-red-50 p-2 rounded-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </button>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <!-- Color Dropdown -->
            <div>
                <label class="block text-sm font-semibold text-gray-800 mb-2">Color <span class="text-red-500">*</span></label>
                <select onchange="updateVariantField(${variant.id}, 'color_id', this.value)" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all">
                    <option value="">Select Color</option>
                    ${availableColors.map(color => `
                        <option value="${color.id}" ${variant.color_id == color.id ? 'selected' : ''}>
                            ${color.name}
                        </option>
                    `).join('')}
                </select>
            </div>
            
            <!-- Size Dropdown -->
            <div>
                <label class="block text-sm font-semibold text-gray-800 mb-2">Size <span class="text-gray-500 text-xs">(Optional)</span></label>
                <select onchange="updateVariantField(${variant.id}, 'size_id', this.value)" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all">
                    <option value="">Select Size</option>
                    ${availableSizes.map(size => `
                        <option value="${size.id}" ${variant.size_id == size.id ? 'selected' : ''}>
                            ${size.name}
                        </option>
                    `).join('')}
                </select>
            </div>
            
            <!-- Stock Input -->
            <div>
                <label class="block text-sm font-semibold text-gray-800 mb-2">Stock Quantity <span class="text-red-500">*</span></label>
                <input type="number" 
                       value="${variant.stock}" 
                       onchange="updateVariantField(${variant.id}, 'stock', this.value)"
                       min="0" 
                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all"
                       placeholder="Enter stock quantity">
            </div>
        </div>
        
        <!-- Images Section -->
        <div class="bg-white rounded-lg p-4 border border-gray-200">
            <div class="flex items-center justify-between mb-3">
                <label class="text-sm font-semibold text-gray-800">Variant Images <span class="text-gray-500 text-xs">(Optional)</span></label>
                <button type="button" onclick="triggerImageUpload(${variant.id})" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Add Images</span>
                </button>
                <input type="file" 
                       id="imageInput_${variant.id}" 
                       accept="image/*" 
                       multiple 
                       onchange="handleImageUpload(${variant.id}, this.files)"
                       class="hidden">
            </div>
            
            <!-- Images Grid -->
            <div id="imagesGrid_${variant.id}" class="grid grid-cols-4 gap-3 mt-3">
                ${renderVariantImages(variant)}
            </div>
            
            <div id="imagesEmpty_${variant.id}" class="${variant.images.length > 0 ? 'hidden' : ''} text-center py-6 text-gray-400 text-sm">
                No images uploaded yet
            </div>
        </div>
    `;
    
    variantsContainer.appendChild(variantDiv);
}

// Render Variant Images
function renderVariantImages(variant) {
    if (!variant.images || variant.images.length === 0) return '';
    
    return variant.images.map((img, idx) => `
        <div class="relative group border-2 rounded-lg overflow-hidden ${idx === 0 ? 'border-blue-500 ring-2 ring-blue-200' : 'border-gray-200'}">
            <div class="aspect-square bg-gray-100">
                <img src="${img.url}" class="w-full h-full object-cover">
                ${idx === 0 ? '<div class="absolute top-1 left-1 bg-blue-500 text-white text-[10px] px-2 py-0.5 rounded font-bold">MAIN</div>' : ''}
            </div>
            <button type="button" 
                    onclick="removeVariantImage(${variant.id}, ${idx})"
                    class="absolute top-1 right-1 bg-red-500 hover:bg-red-600 text-white p-1 rounded opacity-0 group-hover:opacity-100 transition">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    `).join('');
}

// Update Variant Field
window.updateVariantField = function(variantId, field, value) {
    const variant = variants.find(v => v.id === variantId);
    if (variant) {
        variant[field] = value;
        updateHiddenInput();
    }
};

// Remove Variant
window.removeVariant = function(variantId) {
    if (!confirm('Are you sure you want to remove this variant?')) return;
    
    variants = variants.filter(v => v.id !== variantId);
    document.getElementById(`variant_${variantId}`).remove();
    updateEmptyState();
    updateHiddenInput();
    
    // Renumber remaining variants
    document.querySelectorAll('[id^="variant_"]').forEach((el, idx) => {
        const numberSpan = el.querySelector('.w-8.h-8');
        if (numberSpan) numberSpan.textContent = idx + 1;
    });
};

// Trigger Image Upload
window.triggerImageUpload = function(variantId) {
    document.getElementById(`imageInput_${variantId}`).click();
};

// Handle Image Upload
window.handleImageUpload = async function(variantId, files) {
    if (!files || files.length === 0) return;
    
    const variant = variants.find(v => v.id === variantId);
    if (!variant) return;
    
    // Show loading
    const grid = document.getElementById(`imagesGrid_${variantId}`);
    grid.innerHTML = '<div class="col-span-4 text-center py-4 text-gray-500">Uploading images...</div>';
    
    // Upload images
    const uploadPromises = Array.from(files).map(async (file) => {
        const formData = new FormData();
        formData.append('image', file);
        
        try {
            const response = await fetch('{{ route("admin.products.upload-image") }}', {
                method: 'POST',
                body: formData,
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
            });
            
            const result = await response.json();
            
            if (result.success) {
                return {
                    url: result.url,
                    fileId: result.file_id || 'unknown',
                    size: result.size || file.size,
                    name: result.name || file.name
                };
            }
            return null;
        } catch (err) {
            console.error('Upload error:', err);
            return null;
        }
    });
    
    const uploaded = await Promise.all(uploadPromises);
    const validUploads = uploaded.filter(u => u !== null);
    
    if (validUploads.length > 0) {
        variant.images = [...variant.images, ...validUploads];
        refreshVariantImages(variantId);
        updateHiddenInput();
    } else {
        alert('Failed to upload images');
        refreshVariantImages(variantId);
    }
};

// Remove Variant Image
window.removeVariantImage = function(variantId, imageIdx) {
    const variant = variants.find(v => v.id === variantId);
    if (variant && variant.images) {
        variant.images.splice(imageIdx, 1);
        refreshVariantImages(variantId);
        updateHiddenInput();
    }
};

// Refresh Variant Images Display
function refreshVariantImages(variantId) {
    const variant = variants.find(v => v.id === variantId);
    if (!variant) return;
    
    const grid = document.getElementById(`imagesGrid_${variantId}`);
    const empty = document.getElementById(`imagesEmpty_${variantId}`);
    
    if (variant.images.length === 0) {
        grid.innerHTML = '';
        empty.classList.remove('hidden');
    } else {
        grid.innerHTML = renderVariantImages(variant);
        empty.classList.add('hidden');
    }
}

// Update Empty State
function updateEmptyState() {
    if (variants.length === 0) {
        emptyState.classList.remove('hidden');
        variantsContainer.classList.add('hidden');
    } else {
        emptyState.classList.add('hidden');
        variantsContainer.classList.remove('hidden');
    }
}

// Update Hidden Input
function updateHiddenInput() {
    variantsDataInput.value = JSON.stringify(variants);
}

// Initialize - Load existing variants
document.addEventListener('DOMContentLoaded', function() {
    // Load existing variants from product
    @if($product->variants && $product->variants->count() > 0)
        const existingVariants = @json($product->variants->map(function($v) {
            return [
                'color_id' => $v->color_id,
                'size_id' => $v->size_id,
                'stock' => $v->stock,
                'images' => [] // Images will be loaded separately if needed
            ];
        }));
        
        existingVariants.forEach(v => addVariant(v));
    @endif
    
    updateEmptyState();
});

// Form Submit Handler
document.getElementById('nextBtn')?.addEventListener('click', function() {
    if (variants.length === 0) {
        alert('Please add at least one variant');
        return;
    }
    
    // Validate variants
    let isValid = true;
    variants.forEach(v => {
        if (!v.color_id) {
            alert('Please select a color for all variants');
            isValid = false;
        }
        if (!v.stock || v.stock < 0) {
            alert('Please enter valid stock quantity for all variants');
            isValid = false;
        }
    });
    
    if (isValid) {
        document.getElementById('stepForm').submit();
    }
});
</script>
@endpush

@endsection
