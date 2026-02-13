@extends('seller.products.edit._layout')

@section('step_title', 'Step 2: Product Variants')
@section('step_description', 'Add color, size, and stock variants for your product')

@section('step_content')
@php
    $currentStep = 2;
    $prevStepRoute = '/seller/products/' . $product->id . '/edit?step=1';
@endphp

<form id="stepForm" action="{{ route('seller.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
    @method('PUT')
    <input type="hidden" name="step" value="2">
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
        size_ids: data?.size_ids || [],
        size_stocks: data?.size_stocks || {}, // Object to store stock per size
        images: data?.images || [],
        is_default: data?.is_default || false
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
            <div class="flex items-center space-x-3">
                <h3 class="text-lg font-bold text-gray-800 flex items-center space-x-2">
                    <span class="w-8 h-8 bg-purple-600 text-white rounded-full flex items-center justify-center text-sm font-bold">${variants.length}</span>
                    <span>Variant #${variant.id}</span>
                </h3>
                <button type="button" 
                        id="defaultBtn_${variant.id}"
                        onclick="setDefaultVariant(${variant.id})"
                        class="default-star-btn p-2 rounded-lg transition ${variant.is_default ? 'text-yellow-500 bg-yellow-50' : 'text-gray-400 hover:text-yellow-500 hover:bg-yellow-50'}"
                        title="${variant.is_default ? 'Default variant' : 'Set as default'}">
                    <svg class="w-6 h-6" fill="${variant.is_default ? 'currentColor' : 'none'}" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                </button>
            </div>
            <button type="button" onclick="removeVariant(${variant.id})" class="text-red-600 hover:text-red-800 hover:bg-red-50 p-2 rounded-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </button>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <!-- Color Searchable Dropdown -->
            <div>
                <label class="block text-sm font-semibold text-gray-800 mb-2">Color <span class="text-red-500">*</span></label>
                <div class="relative" id="colorDropdown_${variant.id}">
                    <input type="text" 
                           id="colorSearch_${variant.id}"
                           placeholder="Search color..." 
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all"
                           onfocus="showColorDropdown(${variant.id})"
                           oninput="filterColors(${variant.id}, this.value)">
                    <div id="colorList_${variant.id}" class="hidden absolute z-10 w-full mt-1 bg-white border-2 border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                        ${availableColors.map(color => `
                            <div class="px-4 py-2 hover:bg-purple-100 cursor-pointer flex items-center space-x-2" 
                                 data-color-id="${color.id}" 
                                 data-color-name="${color.name}"
                                 onclick="selectColor(${variant.id}, ${color.id}, '${color.name.replace(/'/g, "\\'")}')">
                                <div class="w-6 h-6 rounded border-2 border-gray-300" style="background-color: ${color.hex_code || '#ccc'}"></div>
                                <span>${color.name}</span>
                            </div>
                        `).join('')}
                    </div>
                    <input type="hidden" id="colorValue_${variant.id}" value="${variant.color_id}">
                </div>
            </div>
            
            <!-- Size Multi-Select Dropdown -->
            <div>
                <label class="block text-sm font-semibold text-gray-800 mb-2">Sizes <span class="text-gray-500 text-xs">(Select Multiple)</span></label>
                <div class="relative" id="sizeDropdown_${variant.id}">
                    <div id="sizeDisplay_${variant.id}" 
                         class="w-full min-h-[48px] px-4 py-2 border-2 border-gray-300 rounded-lg focus-within:ring-2 focus-within:ring-purple-500 focus-within:border-purple-500 transition-all cursor-pointer bg-white"
                         onclick="toggleSizeDropdown(${variant.id})">
                        <div id="selectedSizes_${variant.id}" class="flex flex-wrap gap-2">
                            <span class="text-gray-400 text-sm">Click to select sizes...</span>
                        </div>
                    </div>
                    <div id="sizeList_${variant.id}" class="hidden absolute z-10 w-full mt-1 bg-white border-2 border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                        <div class="p-2 border-b border-gray-200">
                            <input type="text" 
                                   id="sizeSearch_${variant.id}"
                                   placeholder="Search sizes..." 
                                   class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                   oninput="filterSizes(${variant.id}, this.value)"
                                   onclick="event.stopPropagation()">
                        </div>
                        ${availableSizes.map(size => `
                            <div class="px-4 py-2 hover:bg-blue-100 cursor-pointer flex items-center space-x-2" 
                                 data-size-id="${size.id}" 
                                 data-size-name="${size.abbreviation || size.name}"
                                 onclick="toggleSizeSelection(${variant.id}, ${size.id}, '${(size.abbreviation || size.name).replace(/'/g, "\\'")}', event)">
                                <input type="checkbox" 
                                       id="sizeCheck_${variant.id}_${size.id}"
                                       class="w-4 h-4 text-purple-600 rounded"
                                       onclick="event.stopPropagation()">
                                <span>${size.abbreviation || size.name}</span>
                            </div>
                        `).join('')}
                    </div>
                    <input type="hidden" id="sizeValue_${variant.id}" value="">
                </div>
            </div>
        </div>
        
        <!-- Size-wise Stock Quantities -->
        <div id="sizeStockContainer_${variant.id}" class="mb-4">
            ${renderSizeStockInputs(variant)}
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
    
    // Set initial values for searchable dropdowns
    if (variant.color_id) {
        const selectedColor = availableColors.find(c => c.id == variant.color_id);
        if (selectedColor) {
            document.getElementById(`colorSearch_${variant.id}`).value = selectedColor.name;
        }
    }
    
    // Set initial size selections (with small delay to ensure DOM is ready)
    if (variant.size_ids && variant.size_ids.length > 0) {
        setTimeout(() => {
            variant.size_ids.forEach(sizeId => {
                const checkbox = document.getElementById(`sizeCheck_${variant.id}_${sizeId}`);
                if (checkbox) {
                    checkbox.checked = true;
                } else {
                    // Checkbox not found
                }
            });
            updateSizeDisplay(variant.id);
        }, 100);
    }
    
    // Initialize sortable for images if any exist
    if (variant.images && variant.images.length > 0) {
        initializeSortable(variant.id);
    }
}

// Render Variant Images
function renderVariantImages(variant) {
    if (!variant.images || variant.images.length === 0) return '';
    
    return variant.images.map((img, idx) => `
        <div class="relative group border-2 rounded-lg overflow-hidden ${idx === 0 ? 'border-blue-500 ring-2 ring-blue-200' : 'border-gray-200'} cursor-move" data-image-index="${idx}">
            <div class="aspect-square bg-gray-100">
                <img src="${img.url}" class="w-full h-full object-cover" draggable="false">
                ${idx === 0 ? '<div class="absolute top-1 left-1 bg-blue-500 text-white text-[10px] px-2 py-0.5 rounded font-bold">MAIN</div>' : ''}
                <div class="absolute top-1 right-1 opacity-0 group-hover:opacity-100 transition-opacity">
                    <svg class="w-5 h-5 text-white drop-shadow-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
                    </svg>
                </div>
            </div>
            <button type="button" 
                    onclick="removeVariantImage(${variant.id}, ${idx})"
                    class="absolute bottom-1 right-1 bg-red-500 hover:bg-red-600 text-white p-1 rounded opacity-0 group-hover:opacity-100 transition">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    `).join('');
}

// Render Size Stock Inputs
function renderSizeStockInputs(variant) {
    if (!variant.size_ids || variant.size_ids.length === 0) {
        return '<div class="text-center py-4 text-gray-400 text-sm">Select sizes to add stock quantities</div>';
    }
    
    return `
        <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
            <label class="block text-sm font-semibold text-gray-800 mb-3">Stock Quantity per Size <span class="text-red-500">*</span></label>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                ${variant.size_ids.map(sizeId => {
                    const size = availableSizes.find(s => s.id == sizeId);
                    if (!size) return '';
                    const stockValue = variant.size_stocks[sizeId] || 0;
                    return `
                        <div class="bg-white rounded-lg p-3 border border-gray-300">
                            <label class="block text-xs font-medium text-gray-700 mb-1">${size.abbreviation || size.name}</label>
                            <input type="number" 
                                   value="${stockValue}" 
                                   onchange="updateSizeStock(${variant.id}, ${sizeId}, this.value)"
                                   min="0" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                                   placeholder="Qty">
                        </div>
                    `;
                }).join('')}
            </div>
        </div>
    `;
}

// Searchable Dropdown Functions
window.showColorDropdown = function(variantId) {
    const list = document.getElementById(`colorList_${variantId}`);
    list.classList.remove('hidden');
    
    // Close other dropdowns
    document.querySelectorAll('[id^="colorList_"]').forEach(el => {
        if (el.id !== `colorList_${variantId}`) el.classList.add('hidden');
    });
    document.querySelectorAll('[id^="sizeList_"]').forEach(el => {
        el.classList.add('hidden');
    });
};

window.toggleSizeDropdown = function(variantId) {
    const list = document.getElementById(`sizeList_${variantId}`);
    const isHidden = list.classList.contains('hidden');
    
    // Close all dropdowns first
    document.querySelectorAll('[id^="colorList_"]').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('[id^="sizeList_"]').forEach(el => el.classList.add('hidden'));
    
    // Toggle current dropdown
    if (isHidden) {
        list.classList.remove('hidden');
    }
};

window.filterColors = function(variantId, searchText) {
    const list = document.getElementById(`colorList_${variantId}`);
    const items = list.querySelectorAll('[data-color-id]');
    const search = searchText.toLowerCase();
    
    items.forEach(item => {
        const name = item.getAttribute('data-color-name').toLowerCase();
        if (name.includes(search)) {
            item.style.display = 'flex';
        } else {
            item.style.display = 'none';
        }
    });
};

window.filterSizes = function(variantId, searchText) {
    const list = document.getElementById(`sizeList_${variantId}`);
    const items = list.querySelectorAll('[data-size-id]');
    const search = searchText.toLowerCase();
    
    items.forEach(item => {
        const name = item.getAttribute('data-size-name').toLowerCase();
        if (name.includes(search)) {
            item.style.display = 'flex';
        } else {
            item.style.display = 'none';
        }
    });
};

window.selectColor = function(variantId, colorId, colorName) {
    document.getElementById(`colorSearch_${variantId}`).value = colorName;
    document.getElementById(`colorValue_${variantId}`).value = colorId;
    document.getElementById(`colorList_${variantId}`).classList.add('hidden');
    updateVariantField(variantId, 'color_id', colorId);
};

window.toggleSizeSelection = function(variantId, sizeId, sizeName, event) {
    event.stopPropagation();
    
    const variant = variants.find(v => v.id === variantId);
    if (!variant) return;
    
    // Initialize size_ids array if not exists
    if (!variant.size_ids) {
        variant.size_ids = [];
    }
    
    const checkbox = document.getElementById(`sizeCheck_${variantId}_${sizeId}`);
    const index = variant.size_ids.indexOf(sizeId);
    
    if (index > -1) {
        // Remove size
        variant.size_ids.splice(index, 1);
        checkbox.checked = false;
    } else {
        // Add size
        variant.size_ids.push(sizeId);
        checkbox.checked = true;
    }
    
    updateSizeDisplay(variantId);
    refreshSizeStockInputs(variantId);
    updateHiddenInput();
};

// Update Size Stock
window.updateSizeStock = function(variantId, sizeId, stock) {
    const variant = variants.find(v => v.id === variantId);
    if (variant) {
        if (!variant.size_stocks) {
            variant.size_stocks = {};
        }
        variant.size_stocks[sizeId] = parseInt(stock) || 0;
        updateHiddenInput();
    }
};

// Refresh Size Stock Inputs
function refreshSizeStockInputs(variantId) {
    const variant = variants.find(v => v.id === variantId);
    if (!variant) return;
    
    // Initialize size_stocks if not exists
    if (!variant.size_stocks) {
        variant.size_stocks = {};
    }
    
    // Set default stock to 0 for newly added sizes
    if (variant.size_ids) {
        variant.size_ids.forEach(sizeId => {
            if (variant.size_stocks[sizeId] === undefined || variant.size_stocks[sizeId] === null) {
                variant.size_stocks[sizeId] = 0;
            }
        });
    }
    
    const container = document.getElementById(`sizeStockContainer_${variantId}`);
    if (container) {
        container.innerHTML = renderSizeStockInputs(variant);
    }
    
    updateHiddenInput();
}

function updateSizeDisplay(variantId) {
    const variant = variants.find(v => v.id === variantId);
    if (!variant) return;
    
    const display = document.getElementById(`selectedSizes_${variantId}`);
    
    if (!variant.size_ids || variant.size_ids.length === 0) {
        display.innerHTML = '<span class="text-gray-400 text-sm">Click to select sizes...</span>';
        return;
    }
    
    const selectedSizeNames = variant.size_ids.map(sizeId => {
        const size = availableSizes.find(s => s.id == sizeId);
        return size ? (size.abbreviation || size.name) : '';
    }).filter(name => name);
    
    display.innerHTML = selectedSizeNames.map(name => `
        <span class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
            ${name}
        </span>
    `).join('');
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('[id^="colorDropdown_"]') && !e.target.closest('[id^="sizeDropdown_"]')) {
        document.querySelectorAll('[id^="colorList_"]').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('[id^="sizeList_"]').forEach(el => el.classList.add('hidden'));
    }
});

// Update Variant Field
window.updateVariantField = function(variantId, field, value) {
    const variant = variants.find(v => v.id === variantId);
    if (variant) {
        variant[field] = value;
        updateHiddenInput();
    }
};

// Set Default Variant
window.setDefaultVariant = function(variantId) {
    // Unset all defaults
    variants.forEach(v => {
        v.is_default = false;
    });
    
    // Set this variant as default
    const variant = variants.find(v => v.id === variantId);
    if (variant) {
        variant.is_default = true;
    }
    
    // Update all star buttons
    document.querySelectorAll('.default-star-btn').forEach(btn => {
        const btnVariantId = parseInt(btn.id.replace('defaultBtn_', ''));
        const isDefault = btnVariantId === variantId;
        
        if (isDefault) {
            btn.classList.add('text-yellow-500', 'bg-yellow-50');
            btn.classList.remove('text-gray-400');
            btn.title = 'Default variant';
            btn.querySelector('svg').setAttribute('fill', 'currentColor');
        } else {
            btn.classList.remove('text-yellow-500', 'bg-yellow-50');
            btn.classList.add('text-gray-400');
            btn.title = 'Set as default';
            btn.querySelector('svg').setAttribute('fill', 'none');
        }
    });
    
    updateHiddenInput();
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
    
    const grid = document.getElementById(`imagesGrid_${variantId}`);
    const empty = document.getElementById(`imagesEmpty_${variantId}`);
    
    // Show animated loading with progress
    empty.classList.add('hidden');
    grid.innerHTML = `
        <div class="col-span-4 flex flex-col items-center justify-center py-8 space-y-4">
            <div class="relative">
                <div class="w-16 h-16 border-4 border-purple-200 border-t-purple-600 rounded-full animate-spin"></div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
            <div class="text-center">
                <p class="text-gray-700 font-semibold">Uploading Images...</p>
                <p class="text-sm text-gray-500" id="uploadProgress_${variantId}">0 of ${files.length} uploaded</p>
            </div>
            <div class="w-64 bg-gray-200 rounded-full h-2 overflow-hidden">
                <div id="progressBar_${variantId}" class="bg-gradient-to-r from-purple-500 to-pink-500 h-full transition-all duration-300" style="width: 0%"></div>
            </div>
        </div>
    `;
    
    // Upload images with progress tracking
    let uploadedCount = 0;
    const uploadPromises = Array.from(files).map(async (file) => {
        const formData = new FormData();
        formData.append('image', file);
        
        try {
            const response = await fetch('{{ route("seller.upload.image") }}', {
                method: 'POST',
                body: formData,
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
            });
            
            const result = await response.json();
            
            // Update progress
            uploadedCount++;
            const progressPercent = (uploadedCount / files.length) * 100;
            const progressBar = document.getElementById(`progressBar_${variantId}`);
            const progressText = document.getElementById(`uploadProgress_${variantId}`);
            if (progressBar) progressBar.style.width = progressPercent + '%';
            if (progressText) progressText.textContent = `${uploadedCount} of ${files.length} uploaded`;
            
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
            uploadedCount++;
            const progressPercent = (uploadedCount / files.length) * 100;
            const progressBar = document.getElementById(`progressBar_${variantId}`);
            const progressText = document.getElementById(`uploadProgress_${variantId}`);
            if (progressBar) progressBar.style.width = progressPercent + '%';
            if (progressText) progressText.textContent = `${uploadedCount} of ${files.length} uploaded`;
            return null;
        }
    });
    
    const uploaded = await Promise.all(uploadPromises);
    const validUploads = uploaded.filter(u => u !== null);
    
    if (validUploads.length > 0) {
        variant.images = [...variant.images, ...validUploads];
        
        // Show success animation briefly
        grid.innerHTML = `
            <div class="col-span-4 flex flex-col items-center justify-center py-8 space-y-4">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <p class="text-green-600 font-semibold">✓ Upload Complete!</p>
                <p class="text-sm text-gray-500">${validUploads.length} image(s) uploaded successfully</p>
            </div>
        `;
        
        // Wait a moment then show images
        setTimeout(() => {
            refreshVariantImages(variantId);
            updateHiddenInput();
        }, 800);
    } else {
        // Show error
        grid.innerHTML = `
            <div class="col-span-4 flex flex-col items-center justify-center py-8 space-y-4">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <p class="text-red-600 font-semibold">Upload Failed</p>
                <p class="text-sm text-gray-500">Please try again</p>
            </div>
        `;
        
        setTimeout(() => {
            refreshVariantImages(variantId);
        }, 2000);
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
        
        // Initialize Sortable for drag and drop
        initializeSortable(variantId);
    }
}

// Initialize Sortable for image reordering
function initializeSortable(variantId) {
    const grid = document.getElementById(`imagesGrid_${variantId}`);
    if (!grid) {
        console.error('Grid not found for variant:', variantId);
        return;
    }
    
    if (typeof Sortable === 'undefined') {
        console.error('Sortable library not loaded');
        return;
    }
    
    // Destroy existing sortable instance if any
    if (grid.sortableInstance) {
        grid.sortableInstance.destroy();
    }
    
    // Create new sortable instance
    grid.sortableInstance = new Sortable(grid, {
        animation: 200,
        easing: 'cubic-bezier(0.4, 0, 0.2, 1)',
        ghostClass: 'sortable-ghost',
        chosenClass: 'sortable-chosen',
        dragClass: 'sortable-drag',
        forceFallback: true,
        fallbackClass: 'sortable-fallback',
        fallbackOnBody: true,
        swapThreshold: 0.65,
        onStart: function(evt) {
            evt.item.style.cursor = 'grabbing';
        },
        onEnd: function(evt) {
            evt.item.style.cursor = 'grab';
            
            const variant = variants.find(v => v.id === variantId);
            if (!variant || !variant.images) return;
            
            // Reorder images array
            const movedImage = variant.images.splice(evt.oldIndex, 1)[0];
            variant.images.splice(evt.newIndex, 0, movedImage);
            
            // Refresh display to update MAIN badge
            setTimeout(() => {
                refreshVariantImages(variantId);
                updateHiddenInput();
            }, 50);
        }
    });
    
    console.log('Sortable initialized for variant:', variantId);
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
    // Load existing variants from product - group by color and images
    @if($product->variants && $product->variants->count() > 0)
        @php
            // Group variants by color_id and images
            $groupedVariants = [];
            foreach ($product->variants as $v) {
                $images = [];
                if ($v->images && is_string($v->images)) {
                    $images = json_decode($v->images, true) ?? [];
                }
                $imagesKey = json_encode($images);
                $key = $v->color_id . '_' . $imagesKey;
                
                if (!isset($groupedVariants[$key])) {
                    $groupedVariants[$key] = [
                        'color_id' => $v->color_id,
                        'size_ids' => [],
                        'size_stocks' => [],
                        'images' => $images,
                        'is_default' => $v->is_default ?? false
                    ];
                }
                
                if ($v->size_id) {
                    $groupedVariants[$key]['size_ids'][] = $v->size_id;
                    $groupedVariants[$key]['size_stocks'][$v->size_id] = $v->stock ?? 0;
                }
            }
            
            $existingVariantsData = array_values($groupedVariants);
        @endphp
        const existingVariants = @json($existingVariantsData);
        
        existingVariants.forEach(v => addVariant(v));
    @else
        // No existing variants
    @endif
    
    updateEmptyState();
});

// Form Submit Handler
document.getElementById('updateBtn')?.addEventListener('click', function() {
    if (variants.length === 0) {
        alert('Please add at least one variant');
        return;
    }
    
    // Validate variants
    let isValid = true;
    
    variants.forEach((v, index) => {
        if (!v.color_id) {
            alert('Please select a color for all variants');
            isValid = false;
            return;
        }
        if (!v.size_ids || v.size_ids.length === 0) {
            alert('Please select at least one size for all variants');
            isValid = false;
            return;
        }
        
        // Initialize size_stocks if not exists
        if (!v.size_stocks) {
            v.size_stocks = {};
        }
        
        // Auto-set stock to 0 for sizes that don't have stock value
        for (let sizeId of v.size_ids) {
            if (v.size_stocks[sizeId] === undefined || v.size_stocks[sizeId] === null || v.size_stocks[sizeId] === '') {
                v.size_stocks[sizeId] = 0;
            }
            // Validate that stock is not negative
            if (v.size_stocks[sizeId] < 0) {
                alert('Stock quantity cannot be negative');
                isValid = false;
                return;
            }
        }
    });
    
    if (!isValid) return;
    
    // Update hidden input with cleaned data
    updateHiddenInput();
    
    // Submit via AJAX
    const form = document.getElementById('stepForm');
    const formData = new FormData(form);
    
    // Show loading state
    const updateBtn = document.getElementById('updateBtn');
    const originalText = updateBtn.innerHTML;
    updateBtn.disabled = true;
    updateBtn.innerHTML = '<svg class="animate-spin h-5 w-5 inline mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Updating...';
    
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success notification
            showNotification(data.message, 'success');
            
            // Reload page after 1 second
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            // Show error
            showNotification(data.message || 'Update failed', 'error');
            updateBtn.disabled = false;
            updateBtn.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred while updating', 'error');
        updateBtn.disabled = false;
        updateBtn.innerHTML = originalText;
    });
});
</script>
@endpush

@endsection
