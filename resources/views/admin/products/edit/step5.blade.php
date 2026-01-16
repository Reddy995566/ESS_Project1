@extends('admin.products.edit._layout')

@section('step_title', 'Step 5: Product Variants')
@section('step_description', 'Configure colors, sizes and variant-specific options')

@section('step_content')
@php
    $currentStep = 5;
    $prevStepRoute = route('admin.products.edit.step4', $product->id);
    $colors = App\Models\Color::all();
    $sizes = App\Models\Size::all();
@endphp

<form id="stepForm" action="{{ route('admin.products.edit.step5.process', $product->id) }}" method="POST">
    @csrf
    
    <div class="bg-white rounded-xl shadow-lg border border-gray-200">
        <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-pink-50">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-gradient-to-r from-purple-600 to-pink-600 rounded-lg flex items-center justify-center shadow-lg">
                    <span class="text-white text-xl">🎨</span>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Product Variants</h2>
                    <p class="text-gray-600 font-medium">Configure colors, sizes & variant-specific options</p>
                </div>
            </div>
        </div>
        
        <div class="p-8 space-y-8">
            <!-- Variant Type Selection -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 border-2 border-blue-200">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Variant Configuration</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="checkbox" id="hasColorVariants" class="h-5 w-5 text-purple-600 border-gray-300 rounded">
                            <div class="flex items-center space-x-2">
                                <span class="text-2xl">🎨</span>
                                <div>
                                    <div class="font-semibold text-gray-900">Color Variants</div>
                                    <div class="text-sm text-gray-600">Different colors with specific images</div>
                                </div>
                            </div>
                        </label>
                    </div>
                    {{-- 
                    <div>
                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="checkbox" id="hasSizeVariants" class="h-5 w-5 text-purple-600 border-gray-300 rounded">
                            <div class="flex items-center space-x-2">
                                <span class="text-2xl">📏</span>
                                <div>
                                    <div class="font-semibold text-gray-900">Size Variants</div>
                                    <div class="text-sm text-gray-600">Different sizes with stock/price</div>
                                </div>
                            </div>
                        </label>
                    </div>
                    --}}
                </div>
            </div>

            <!-- Color Variants Section -->
            <div id="colorVariantsSection" class="bg-white border-2 border-purple-200 rounded-xl p-6" style="display: none;">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center space-x-2">
                    <span class="text-purple-600">🎨</span>
                    <span>Color Variants</span>
                </h3>
                
                <!-- Available Colors -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-800 mb-3">Select Colors</label>
                    <div class="mb-4">
                        <input type="text" id="colorSearch" placeholder="Search colors..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors">
                    </div>
                    <div class="grid grid-cols-3 md:grid-cols-6 lg:grid-cols-8 gap-4" id="colorsGrid">
                        @foreach($colors as $color)
                        <label class="cursor-pointer group">
                            <input type="checkbox" name="selected_colors[]" value="{{ $color->id }}" class="hidden color-checkbox" data-color-id="{{ $color->id }}" data-color-name="{{ $color->name }}" data-color-hex="{{ $color->hex_code }}">
                            <div class="relative">
                                <div class="w-16 h-16 rounded-lg shadow-lg border-4 border-gray-300 transition-all duration-200 group-hover:scale-110 color-box" 
                                     style="background-color: {{ $color->hex_code }};"
                                     id="colorBox_{{ $color->id }}"></div>
                                <!-- Edit Button -->
                                <button type="button" onclick="openEditColorModal({{ $color->id }}, '{{ addslashes($color->name) }}', '{{ $color->hex_code }}', event)" 
                                        class="absolute top-0 right-0 p-1 bg-white rounded-full shadow-md text-gray-500 hover:text-blue-600 hover:bg-blue-50 transition-all opacity-0 group-hover:opacity-100 z-10"
                                        title="Edit Color">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                                <div class="absolute -top-2 -right-2 hidden color-checkmark" style="z-index: 5;">
                                    <div class="w-6 h-6 bg-purple-500 text-white rounded-full flex items-center justify-center text-xs font-bold">✓</div>
                                </div>
                            </div>
                            <div class="text-xs font-medium text-center mt-2 text-gray-700" id="colorName_{{ $color->id }}">{{ $color->name }}</div>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- Color-Specific Images Upload -->
                <div id="colorImagesSection" style="display: none;">
                    <h4 class="text-md font-bold text-gray-900 mb-4">📸 Upload Color-Specific Images</h4>
                    <div id="colorImageContainers" class="space-y-6"></div>
                </div>
            </div>

            <!-- Size Variants Section -->
            {{-- 
            <div id="sizeVariantsSection" class="bg-white border-2 border-indigo-200 rounded-xl p-6" style="display: none;">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center space-x-2">
                    <span class="text-indigo-600">📏</span>
                    <span>Size Variants</span>
                </h3>
                
                <!-- Available Sizes -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-800 mb-3">Select Sizes</label>
                    <div class="grid grid-cols-4 md:grid-cols-8 lg:grid-cols-12 gap-3">
                        @foreach($sizes as $size)
                        <label class="cursor-pointer">
                            <input type="checkbox" name="selected_sizes[]" value="{{ $size->id }}" class="hidden size-checkbox">
                            <div class="relative">
                                <div class="w-16 h-12 rounded-lg border-2 border-gray-300 transition-all duration-200 flex items-center justify-center font-bold text-lg bg-white text-gray-700 hover:border-indigo-300 size-box">
                                    {{ $size->abbreviation ?? substr($size->name, 0, 2) }}
                                </div>
                                <div class="absolute -top-1 -right-1 hidden size-checkmark">
                                    <div class="w-5 h-5 bg-indigo-500 text-white rounded-full flex items-center justify-center text-xs font-bold">✓</div>
                                </div>
                            </div>
                            <div class="text-xs font-medium text-center mt-1 text-gray-700">{{ $size->name }}</div>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>
            --}}

            <!-- Hidden inputs for variants -->
            <input type="hidden" name="has_variants" id="hasVariantsInput" value="{{ old('has_variants', isset($productData['has_variants']) && $productData['has_variants'] ? '1' : '0') }}">
            <input type="hidden" name="variant_colors" id="variantColorsInput" value="{{ old('variant_colors', $productData['variant_colors'] ?? '[]') }}">
            <input type="hidden" name="variant_sizes" id="variantSizesInput" value="{{ old('variant_sizes', $productData['variant_sizes'] ?? '[]') }}">
            <input type="hidden" name="variant_images" id="variantImagesInput" value="{{ old('variant_images', $productData['variant_images'] ?? '{}') }}">
        </div>
    </div>
</form>

<!-- Edit Color Modal -->
<div id="editColorModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeEditColorModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Edit Color</h3>
                        <div class="mt-4 space-y-4">
                            <input type="hidden" id="editColorId">
                            <div>
                                <label for="editColorName" class="block text-sm font-medium text-gray-700">Color Name</label>
                                <input type="text" id="editColorName" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="editColorHex" class="block text-sm font-medium text-gray-700">Hex Code</label>
                                <div class="flex items-center space-x-2">
                                    <input type="color" id="editColorHexPicker" class="h-9 w-9 border border-gray-300 rounded shadow-sm p-0 overflow-hidden cursor-pointer" oninput="document.getElementById('editColorHex').value = this.value">
                                    <input type="text" id="editColorHex" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm uppercase" oninput="document.getElementById('editColorHexPicker').value = this.value">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="saveColor()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Save Changes
                </button>
                <button type="button" onclick="closeEditColorModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
/* SortableJS Custom Styles */
.sortable-ghost { opacity: 0.5; transform: rotate(2deg); }
.sortable-chosen { transform: scale(1.05); box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15); border: 2px solid #3b82f6 !important; }
.sortable-drag { transform: rotate(5deg) scale(1.1); z-index: 1000; cursor: grabbing; }
.cursor-move { cursor: move; }
</style>
@endpush

@push('scripts')
<!-- SortableJS for drag & drop -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

<script>
// Simple vanilla JavaScript implementation
document.addEventListener('DOMContentLoaded', function() {

    // Color Search Functionality
    const colorSearchInput = document.getElementById('colorSearch');
    if (colorSearchInput) {
        colorSearchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const colorLabels = document.querySelectorAll('#colorsGrid > label');

            colorLabels.forEach(label => {
                const input = label.querySelector('input.color-checkbox');
                if (input) {
                    const colorName = input.dataset.colorName.toLowerCase();
                    if (colorName.includes(searchTerm)) {
                        label.style.display = '';
                    } else {
                        label.style.display = 'none';
                    }
                }
            });
        });
    }

    let selectedColors = [];
    let selectedSizes = [];
    let colorImages = {}; // Structure: { colorId: [ {url, fileId, size, width, height, name, originalSize}, ... ] }
    
    // --- Initialization Logic ---
    
    // 1. Try to load from Laravel validation error inputs (old input)
    let loadedFromOld = false;
    try {
        const oldColorImages = document.getElementById('variantImagesInput').value;
        if (oldColorImages && oldColorImages !== '{}' && oldColorImages !== '[]') {
            colorImages = JSON.parse(oldColorImages);
            loadedFromOld = true;
        }
        
        const oldSelectedColors = document.getElementById('variantColorsInput').value;
        if (oldSelectedColors && oldSelectedColors !== '[]') {
            selectedColors = JSON.parse(oldSelectedColors);
            loadedFromOld = true;
        }
        
        const oldSelectedSizes = document.getElementById('variantSizesInput').value;
        if (oldSelectedSizes && oldSelectedSizes !== '[]') {
            selectedSizes = JSON.parse(oldSelectedSizes);
        }
    } catch(e) {}

    // 2. Fallback to Server Data if not loaded from validation feedback
    if (!loadedFromOld) {
        @if(isset($productData['variant_colors']) && $productData['variant_colors'])
        try {
            const existingColors = JSON.parse('{!! addslashes($productData['variant_colors']) !!}');
            if (Array.isArray(existingColors) && existingColors.length > 0) {
                selectedColors = existingColors.map(String);
            }
        } catch(e) {}
        @endif
        
        @if(isset($productData['variant_sizes']) && $productData['variant_sizes'])
        try {
            const existingSizes = JSON.parse('{!! addslashes($productData['variant_sizes']) !!}');
            if (Array.isArray(existingSizes) && existingSizes.length > 0) {
                selectedSizes = existingSizes.map(String);
            }
        } catch(e) {}
        @endif
        
        @if(isset($productData['variant_images']) && $productData['variant_images'])
        try {
            const existingImages = JSON.parse('{!! addslashes($productData['variant_images']) !!}');
            if (existingImages && typeof existingImages === 'object') {
                // Normalize existing images if they are simple URLs
                for (const [cId, imgs] of Object.entries(existingImages)) {
                    if (Array.isArray(imgs)) {
                        colorImages[cId] = imgs.map(img => {
                             if(typeof img === 'string') {
                                 return { url: img, width: 360, height: 459, size: 0, originalSize: 0, name: 'image' };
                             }
                             return img;
                        });
                    }
                }
            }
        } catch(e) {}
        @endif
    }

    const hasColorCheckbox = document.getElementById('hasColorVariants');
    const hasSizeCheckbox = document.getElementById('hasSizeVariants');
    const colorSection = document.getElementById('colorVariantsSection');
    const sizeSection = document.getElementById('sizeVariantsSection');
    const colorImagesSection = document.getElementById('colorImagesSection');
    const colorImageContainers = document.getElementById('colorImageContainers');
    
    // Initial UI State Setup
    const hasVariantsInput = document.getElementById('hasVariantsInput');
    const shouldCheckColor = selectedColors.length > 0 || (hasVariantsInput && hasVariantsInput.value === '1');

    if (shouldCheckColor) {
        if(hasColorCheckbox) {
            hasColorCheckbox.checked = true;
            colorSection.style.display = 'block';
        }
    }
    if (selectedSizes.length > 0) {
        if(hasSizeCheckbox) {
            hasSizeCheckbox.checked = true;
            sizeSection.style.display = 'block';
        }
    }

    // Toggle Color Variants
    if(hasColorCheckbox) {
        hasColorCheckbox.addEventListener('change', function() {
            colorSection.style.display = this.checked ? 'block' : 'none';
            if (!this.checked) {
                selectedColors = [];
                colorImages = {};
                resetColorCheckboxes();
                colorImagesSection.style.display = 'none';
            }
            updateHiddenInputs();
        });
    }
    
    // Toggle Size Variants (if exists)
    if(hasSizeCheckbox) {
        hasSizeCheckbox.addEventListener('change', function() {
            if(sizeSection) sizeSection.style.display = this.checked ? 'block' : 'none';
            if (!this.checked) {
                selectedSizes = [];
                resetSizeCheckboxes();
            }
            updateHiddenInputs();
        });
    }
    
    // Handle Color Selection
    document.querySelectorAll('.color-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const colorId = this.dataset.colorId;
            updateCheckboxUI(this, this.checked);

            if (this.checked) {
                selectedColors.push(colorId);
                if (!colorImages[colorId]) colorImages[colorId] = [];
            } else {
                selectedColors = selectedColors.filter(id => id !== colorId);
                delete colorImages[colorId];
            }
            
            updateColorImagesUI();
            updateHiddenInputs();
        });
    });

    function updateCheckboxUI(checkbox, isChecked) {
        const parent = checkbox.parentElement;
        const box = parent.querySelector('.color-box');
        const checkmark = parent.querySelector('.color-checkmark');
        
        if(isChecked) {
            box.classList.add('border-purple-500', 'ring-4', 'ring-purple-200');
            checkmark.classList.remove('hidden');
        } else {
            box.classList.remove('border-purple-500', 'ring-4', 'ring-purple-200');
            checkmark.classList.add('hidden');
        }
    }
    
    // Handle Size Selection
    document.querySelectorAll('.size-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const parent = this.parentElement;
            const sizeBox = parent.querySelector('.size-box');
            const checkmark = parent.querySelector('.size-checkmark');
            
            if (this.checked) {
                selectedSizes.push(this.value);
                sizeBox.classList.add('border-indigo-500', 'bg-indigo-50', 'text-indigo-700');
                sizeBox.classList.remove('border-gray-300', 'bg-white', 'text-gray-700');
                checkmark.classList.remove('hidden');
            } else {
                selectedSizes = selectedSizes.filter(id => id !== this.value);
                sizeBox.classList.remove('border-indigo-500', 'bg-indigo-50', 'text-indigo-700');
                sizeBox.classList.add('border-gray-300', 'bg-white', 'text-gray-700');
                checkmark.classList.add('hidden');
            }
            
            updateHiddenInputs();
        });
    });
    
    function updateColorImagesUI() {
        if (selectedColors.length > 0) {
            colorImagesSection.style.display = 'block';
            renderColorContainers();
        } else {
            colorImagesSection.style.display = 'none';
            colorImageContainers.innerHTML = '';
        }
    }
    
    function renderColorContainers() {
        colorImageContainers.innerHTML = '';
        
        selectedColors.forEach(colorId => {
            const checkbox = document.querySelector(`.color-checkbox[data-color-id="${colorId}"]`);
            if(!checkbox) return;

            const colorName = checkbox.dataset.colorName;
            const colorHex = checkbox.dataset.colorHex;
            const images = colorImages[colorId] || [];
            
            const div = document.createElement('div');
            div.className = 'bg-gray-50 rounded-xl p-6 border-2 border-gray-200';
            div.id = `container_${colorId}`;
            
            // Header
            div.innerHTML = `
                <div class="flex items-center justify-between mb-4">
                    <h5 class="text-lg font-bold text-gray-900 flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-full shadow-md" style="background-color: ${colorHex}"></div>
                        <span>${colorName}</span>
                    </h5>
                    <div class="flex items-center space-x-2">
                        <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded" id="count_${colorId}">${images.length} images</span>
                        <button type="button" onclick="transferImages(${colorId})" class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 px-2 py-1 rounded border border-gray-300 transition-colors" title="Move images to another color">
                            Move Images
                        </button>
                        <button type="button" onclick="removeColor(${colorId}, '${colorName}')" class="text-xs bg-red-100 hover:bg-red-200 text-red-700 px-2 py-1 rounded border border-red-300 transition-colors" title="Delete Color and Images">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                </div>
                
                <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-blue-500 transition-all cursor-pointer image-upload-area" 
                     onclick="document.getElementById('file_${colorId}').click()"
                     ondragover="event.preventDefault(); this.classList.add('border-blue-500', 'bg-blue-50')"
                     ondragleave="this.classList.remove('border-blue-500', 'bg-blue-50')"
                     ondrop="handleDrop(event, '${colorId}', '${colorName}')">
                     
                    <input type="file" id="file_${colorId}" accept="image/*" multiple class="hidden">
                    
                    <div class="space-y-4">
                        <div class="mx-auto w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-sm">
                            <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-lg font-semibold text-gray-700">Click or Drag & Drop to upload</p>
                            <p class="text-sm text-gray-500">for ${colorName}</p>
                            <p class="text-xs text-gray-400 mt-2">Max 6 images • Original Quality</p>
                        </div>
                    </div>
                </div>
                
                <!-- Loading State inside this container (hidden by default) -->
                <div id="loading_${colorId}" class="hidden mt-4 text-center py-4">
                     <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                     <p class="text-sm text-blue-600 mt-2">Uploading...</p>
                </div>

                <div id="grid_${colorId}" class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6 ${images.length === 0 ? 'hidden' : ''}"></div>
            `;
            
            colorImageContainers.appendChild(div);
            
            // File input listener
            document.getElementById(`file_${colorId}`).addEventListener('change', function(e) {
                handleUpload(e.target.files, colorId, colorName);
                e.target.value = ''; // Reset
            });
            
            // Initial render of images if any
            renderImages(colorId);
        });
    }

    // Expose handleDrop to window for inline onclick/ondrop to work smoothly
    window.handleDrop = function(e, colorId, colorName) {
        e.preventDefault();
        e.stopPropagation();
        e.currentTarget.classList.remove('border-blue-500', 'bg-blue-50');
        
        if (e.dataTransfer.files && e.dataTransfer.files.length > 0) {
            handleUpload(e.dataTransfer.files, colorId, colorName);
        }
    };
    
    async function handleUpload(fileList, colorId, colorName) {
        const files = Array.from(fileList);
        const currentImages = colorImages[colorId] || [];
        
        if (currentImages.length + files.length > 6) {
            alert(`Maximum 6 images per color. You can upload ${6 - currentImages.length} more.`);
            return;
        }
        
        // Show loading
        const loadingEl = document.getElementById(`loading_${colorId}`);
        if(loadingEl) loadingEl.classList.remove('hidden');

        // Process uploads sequentially or parallel. Parallel is faster.
        const uploadPromises = files.map(async (file) => {
            const formData = new FormData();
            formData.append('image', file);
            formData.append('folder', 'products/variants');
            
            try {
                const response = await fetch('{{ route("admin.upload.image") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
                });
                
                const result = await response.json();
                
                if (result.success) {
                    // Normalize result to match our structure
                     return {
                        url: result.url,
                        fileId: result.file_id || result.fileId || 'unknown',
                        size: result.size || file.size, // Actual size
                        originalSize: file.size || 0,
                        width: result.width || 360,
                        height: result.height || 459,
                        name: result.name || file.name || 'image'
                    };
                } else {
                    console.error('Upload failed for file:', file.name, result.message);
                    return null;
                }
            } catch (err) {
                console.error('Upload error:', err);
                return null;
            }
        });
        
        const uploaded = await Promise.all(uploadPromises);
        const validUploads = uploaded.filter(u => u !== null);
        
        if (validUploads.length > 0) {
            if (!colorImages[colorId]) colorImages[colorId] = [];
            colorImages[colorId] = [...colorImages[colorId], ...validUploads];
        } else {
            alert('Failed to upload images. Please check console or try again.');
        }

        // Hide loading and render
        if(loadingEl) loadingEl.classList.add('hidden');
        renderImages(colorId);
        updateHiddenInputs();
    }
    
    function renderImages(colorId) {
        const grid = document.getElementById(`grid_${colorId}`);
        const countSpan = document.getElementById(`count_${colorId}`);
        const infoDiv = document.getElementById(`info_${colorId}`);
        if (!grid) return;
        
        const images = colorImages[colorId] || [];
        
        if(countSpan) countSpan.textContent = `${images.length} images`;
        
        if (images.length === 0) {
            grid.classList.add('hidden');
            if(infoDiv) infoDiv.classList.add('hidden');
            grid.innerHTML = '';
            return;
        }
        
        grid.classList.remove('hidden');
        if(infoDiv) infoDiv.classList.remove('hidden');
        
        grid.innerHTML = images.map((img, idx) => `
            <div class="relative group border-2 rounded-lg overflow-hidden bg-white shadow-sm hover:shadow-md transition-all ${idx === 0 ? 'border-blue-500 ring-2 ring-blue-100' : 'border-gray-200'} cursor-move" data-image-index="${idx}">
                <div class="relative aspect-[3/4] overflow-hidden bg-gray-100">
                    <img src="${img.url}" class="w-full h-full object-cover">
                    ${idx === 0 ? '<div class="absolute top-1 left-1 bg-blue-500 text-white text-[10px] px-2 py-0.5 rounded font-bold uppercase tracking-wider">Main</div>' : ''}
                    <div class="absolute top-1 right-1 bg-black/60 text-white text-[10px] px-1.5 py-0.5 rounded backdrop-blur-sm">
                        ${img.width}×${img.height}
                    </div>
                </div>
                
                <div class="p-2 bg-white border-t border-gray-100">
                    <div class="flex justify-between items-center text-[10px] text-gray-500 mb-1">
                        <span>${formatFileSize(img.size)}</span>

                    </div>
                    <button type="button" onclick="removeImage('${colorId}', ${idx})" class="w-full py-1 text-center text-xs text-red-500 hover:text-white hover:bg-red-500 rounded transition-colors flex items-center justify-center space-x-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        <span>Remove</span>
                    </button>
                </div>
            </div>
        `).join('');
        
        // Initialize Sortable for drag & drop reordering
        initializeSortable(grid, colorId);
    }
    
    // Initialize Sortable.js for image reordering
    function initializeSortable(grid, colorId) {
        if (!grid || !window.Sortable) return;
        
        new Sortable(grid, {
            animation: 150,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            dragClass: 'sortable-drag',
            handle: '.cursor-move',
            onEnd: function(evt) {
                // Get the new order of images
                const oldIndex = evt.oldIndex;
                const newIndex = evt.newIndex;
                
                if (oldIndex === newIndex) return;
                
                // Reorder the colorImages array
                const images = colorImages[colorId];
                const movedImage = images.splice(oldIndex, 1)[0];
                images.splice(newIndex, 0, movedImage);
                
                // Re-render to update the "Main" badge
                renderImages(colorId);
                updateHiddenInputs();
            }
        });
    }
    
    // Make remove global
    window.removeImage = function(colorId, idx) {
        if (colorImages[colorId]) {
            colorImages[colorId].splice(idx, 1);
            renderImages(colorId);
            updateHiddenInputs();
        }
    };
    
    function updateHiddenInputs() {
        document.getElementById('hasVariantsInput').value = (hasColorCheckbox.checked || (hasSizeCheckbox && hasSizeCheckbox.checked)) ? '1' : '0';
        document.getElementById('variantColorsInput').value = JSON.stringify(selectedColors);
        document.getElementById('variantSizesInput').value = JSON.stringify(selectedSizes);
        document.getElementById('variantImagesInput').value = JSON.stringify(colorImages);
    }
    
    function resetColorCheckboxes() {
        document.querySelectorAll('.color-checkbox').forEach(cb => {
            cb.checked = false;
            updateCheckboxUI(cb, false);
        });
    }
    
    function resetSizeCheckboxes() {
        document.querySelectorAll('.size-checkbox').forEach(cb => {
            cb.checked = false;
            const parent = cb.parentElement;
            const box = parent.querySelector('.size-box');
            box.classList.remove('border-indigo-500', 'bg-indigo-50', 'text-indigo-700');
            box.classList.add('border-gray-300', 'bg-white', 'text-gray-700');
            parent.querySelector('.size-checkmark').classList.add('hidden');
        });
    }
    
    function formatFileSize(bytes) {
        if (!bytes || isNaN(bytes) || bytes <= 0) return '0 B';
        const k = 1024;
        const sizes = ['B', 'KB', 'MB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + (sizes[i] || 'GB');
    }

    // Apply Checkbox Selection State from Loaded Data
    selectedColors.forEach(colorId => {
        const checkbox = document.querySelector(`.color-checkbox[data-color-id="${colorId}"]`);
        if (checkbox) {
            checkbox.checked = true;
            updateCheckboxUI(checkbox, true);
        }
    });
    
    selectedSizes.forEach(sizeId => {
        const checkbox = document.querySelector(`.size-checkbox[value="${sizeId}"]`);
        if (checkbox) {
            checkbox.checked = true;
            const parent = checkbox.parentElement;
            const box = parent.querySelector('.size-box');
            box.classList.add('border-indigo-500', 'bg-indigo-50', 'text-indigo-700');
            box.classList.remove('border-gray-300', 'bg-white', 'text-gray-700');
            parent.querySelector('.size-checkmark').classList.remove('hidden');
        }
    });

    // Initial UI Render
    updateHiddenInputs();
    updateColorImagesUI();

    // --- Edit Color Logic ---
    window.openEditColorModal = function(id, name, hex, event) {
        if(event) {
            event.preventDefault();
            event.stopPropagation();
        }
        
        document.getElementById('editColorId').value = id;
        document.getElementById('editColorName').value = name;
        document.getElementById('editColorHex').value = hex;
        document.getElementById('editColorHexPicker').value = hex;
        
        document.getElementById('editColorModal').classList.remove('hidden');
    }

    window.closeEditColorModal = function() {
        document.getElementById('editColorModal').classList.add('hidden');
    }

    window.saveColor = async function() {
        const id = document.getElementById('editColorId').value;
        const name = document.getElementById('editColorName').value;
        const hex = document.getElementById('editColorHex').value;
        
        if(!name || !hex) {
            alert('Please fill all fields');
            return;
        }

        try {
            const response = await fetch(`/admin/colors/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    name: name,
                    hex_code: hex,
                    sort_order: 0, 
                    is_active: 1
                })
            });
            
            const result = await response.json();
            
            if(result.success) {
                // Update DOM
                const colorBox = document.getElementById(`colorBox_${id}`);
                const colorName = document.getElementById(`colorName_${id}`);
                const checkbox = document.querySelector(`.color-checkbox[data-color-id="${id}"]`);
                
                if(colorBox) colorBox.style.backgroundColor = hex;
                if(colorName) colorName.textContent = name;
                if(checkbox) {
                    checkbox.dataset.colorName = name;
                    checkbox.dataset.colorHex = hex;
                }
                
                // Close modal
                closeEditColorModal();
            } else {
                alert(result.message || 'Failed to update color');
            }
        } catch (error) {
            console.error(error);
            alert('Error updating color');
        }
    }

    // --- Transfer Images Logic ---
    window.transferImages = function(sourceColorId) {
        // Access global colorImages
        const images = colorImages[sourceColorId] || [];
        if (images.length === 0) {
            alert('No images to move!');
            return;
        }

        // Get other active colors
        const otherColors = selectedColors.filter(id => id != sourceColorId);
        if (otherColors.length === 0) {
            alert('No other colors selected. Please select another color first.');
            return;
        }

        let targetId = null;
        
        if (otherColors.length === 1) {
            const targetColorId = otherColors[0];
            const targetCheckbox = document.querySelector(`.color-checkbox[data-color-id="${targetColorId}"]`);
            const targetName = targetCheckbox ? targetCheckbox.dataset.colorName : 'Target Color';
            
            if (confirm(`Move ${images.length} images to ${targetName}?`)) {
                targetId = targetColorId;
            }
        } else {
            // Multiple targets
            let msg = "Enter the number of the color to move images to:\n";
            const options = [];
            otherColors.forEach((id, index) => {
                const cb = document.querySelector(`.color-checkbox[data-color-id="${id}"]`);
                const name = cb ? cb.dataset.colorName : id;
                msg += `${index + 1}. ${name}\n`;
                options.push({id, index: index + 1});
            });
            
            const selection = prompt(msg);
            if (selection) {
                const num = parseInt(selection);
                const found = options.find(o => o.index === num);
                if (found) targetId = found.id;
            }
        }
        
        if (targetId) {
            if (!colorImages[targetId]) colorImages[targetId] = [];
            
            // Move images
            colorImages[targetId] = colorImages[targetId].concat(images);
            
            // Clear source images
            delete colorImages[sourceColorId]; 

            // Deselect source color
            selectedColors = selectedColors.filter(id => id != sourceColorId);
            
            // Uncheck the checkbox UI
            const sourceCheckbox = document.querySelector(`.color-checkbox[data-color-id="${sourceColorId}"]`);
            if (sourceCheckbox) {
                sourceCheckbox.checked = false;
                updateCheckboxUI(sourceCheckbox, false);
            }

            // Re-render
            updateColorImagesUI(); 
            updateHiddenInputs();
        }
    }
    // End of transferImages function

    // --- Remove Color Logic ---
    window.removeColor = async function(colorId, colorName) {
        if(confirm(`Are you sure you want to remove ${colorName}? This will delete all uploaded images for this color and remove it from the database.`)) {
            
            try {
                // AJAX call to delete from database
                const productId = '{{ $product->id ?? "" }}';
                // Note: If creating a new product ($product might be null or not saved yet), we can't delete from DB.
                // But this is mainly for EDIT mode.
                
                if (productId) {
                    const response = await fetch(`/admin/products/${productId}/variant-color`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ color_id: colorId })
                    });
                    
                    const result = await response.json();
                    if (!result.success) {
                        alert('Warning: Could not delete from database: ' + result.message);
                        // We continue to remove from UI anyway? Or stop? 
                        // User wants it deleted from DB. If that fails, we should probably stop or warn.
                        // But let's assume we proceed to ensure UI consistency if it was a network glitch or already deleted.
                    }
                }

                // Remove from selectedColors
                selectedColors = selectedColors.filter(id => id != colorId);
                
                // Remove images
                delete colorImages[colorId];
                
                // Uncheck Checkbox
                const checkbox = document.querySelector(`.color-checkbox[data-color-id="${colorId}"]`);
                if(checkbox) {
                    checkbox.checked = false;
                    updateCheckboxUI(checkbox, false);
                }
                
                // Re-render
                updateColorImagesUI();
                updateHiddenInputs();

            } catch (error) {
                console.error(error);
                alert('Error deleting color variant');
            }
        }
    }

}); // End of DOMContentLoaded
</script>
@endpush

@endsection