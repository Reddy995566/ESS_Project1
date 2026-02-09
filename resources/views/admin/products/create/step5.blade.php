@extends('admin.products.create._layout')

@section('step_title', 'Step 2: Product Variants')
@section('step_description', 'Configure colors, sizes and variant-specific options')

@section('step_content')
@php
    $currentStep = 2;
    $prevStepRoute = route('admin.products.create.step1');
@endphp

<form id="stepForm" action="{{ route('admin.products.create.step2.process') }}" method="POST">
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
                                     style="background-color: {{ $color->hex_code }};"></div>
                                <div class="absolute -top-2 -right-2 hidden color-checkmark">
                                    <div class="w-6 h-6 bg-purple-500 text-white rounded-full flex items-center justify-center text-xs font-bold">✓</div>
                                </div>
                            </div>
                            <div class="text-xs font-medium text-center mt-2 text-gray-700">{{ $color->name }}</div>
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
                            <input type="checkbox" name="selected_sizes[]" value="{{ $size->id }}" class="hidden size-checkbox" data-size-id="{{ $size->id }}" data-size-name="{{ $size->name }}" data-size-abbr="{{ $size->abbreviation }}">
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

            <!-- Hidden inputs for variants -->
            <input type="hidden" name="has_variants" id="hasVariantsInput" value="{{ old('has_variants', isset($productData['has_variants']) && $productData['has_variants'] ? '1' : '0') }}">
            <input type="hidden" name="variant_colors" id="variantColorsInput" value="{{ old('variant_colors', $productData['variant_colors'] ?? '[]') }}">
            <input type="hidden" name="variant_sizes" id="variantSizesInput" value="{{ old('variant_sizes', $productData['variant_sizes'] ?? '[]') }}">
            <input type="hidden" name="variant_images" id="variantImagesInput" value="{{ old('variant_images', $productData['variant_images'] ?? '{}') }}">
        </div>
    </div>
</form>

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
    
    // Check for old input data on validation failure
    try {
        const oldColorImages = document.getElementById('variantImagesInput').value;
        if (oldColorImages && oldColorImages !== '{}') {
            colorImages = JSON.parse(oldColorImages);
        }
        
        const oldSelectedColors = document.getElementById('variantColorsInput').value;
        if (oldSelectedColors && oldSelectedColors !== '[]') {
            selectedColors = JSON.parse(oldSelectedColors);
            // Re-check boxes would be handled if we had server-side rendering for checked state, 
            // but for JS only refilling:
            selectedColors.forEach(id => {
               const cb = document.querySelector(`.color-checkbox[value="${id}"]`);
               if(cb) {
                   cb.checked = true;
                   updateCheckboxUI(cb, true);
               }
            });
        }
        
        const oldSelectedSizes = document.getElementById('variantSizesInput').value;
        if (oldSelectedSizes && oldSelectedSizes !== '[]') {
            selectedSizes = JSON.parse(oldSelectedSizes);
            // Re-check size boxes
            selectedSizes.forEach(id => {
               const cb = document.querySelector(`.size-checkbox[value="${id}"]`);
               if(cb) {
                   cb.checked = true;
                   const parent = cb.parentElement;
                   const sizeBox = parent.querySelector('.size-box');
                   const checkmark = parent.querySelector('.size-checkmark');
                   sizeBox.classList.add('border-indigo-500', 'bg-indigo-50', 'text-indigo-700');
                   sizeBox.classList.remove('border-gray-300', 'bg-white', 'text-gray-700');
                   checkmark.classList.remove('hidden');
               }
            });
        }
    } catch(e) {}

    const hasColorCheckbox = document.getElementById('hasColorVariants');
    const hasSizeCheckbox = document.getElementById('hasSizeVariants');
    const colorSection = document.getElementById('colorVariantsSection');
    const sizeSection = document.getElementById('sizeVariantsSection'); // Might differ if commented out
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
    
    // Apply Checkbox Selection State from Loaded Data
    selectedColors.forEach(colorId => {
        const checkbox = document.querySelector(`.color-checkbox[data-color-id="${colorId}"]`);
        if (checkbox) {
            checkbox.checked = true;
            updateCheckboxUI(checkbox, true);
        }
    });

    if (selectedSizes.length > 0) {
        if(hasSizeCheckbox) {
            hasSizeCheckbox.checked = true;
            sizeSection.style.display = 'block';
        }
    }
    
    // Toggle Color Variants
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
            
            // We want to preserve existing UI for colors that are already there, 
            // only add new ones or remove unselected ones. 
            // But simple re-render is easier for consistency. 
            // To prevent flickering or loss of specific state if we were complex, we'd diff.
            // For now, full re-render is acceptable as inputs are file uploads.
            renderColorContainers();
        } else {
            colorImagesSection.style.display = 'none';
            colorImageContainers.innerHTML = '';
        }
    }
    
    function renderColorContainers() {
        // Clear current container but we might want to be smarter about this if performance is an issue.
        // For now, rebuild.
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
                    <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded" id="count_${colorId}">${images.length} images</span>
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

                <div class="flex justify-between items-center mt-6 mb-2 ${images.length === 0 ? 'hidden' : ''}" id="info_${colorId}">
                    <p class="text-xs text-gray-500">Drag to reorder • First image will be featured</p>
                </div>
                
                <div id="grid_${colorId}" class="grid grid-cols-2 md:grid-cols-4 gap-4 ${images.length === 0 ? 'hidden' : ''}"></div>
            `;
            
            colorImageContainers.appendChild(div);
            
            // Initialize Sortable for this grid
            const gridEl = document.getElementById(`grid_${colorId}`);
            if(gridEl) {
                new Sortable(gridEl, {
                    animation: 150,
                    ghostClass: 'sortable-ghost',
                    chosenClass: 'sortable-chosen',
                    dragClass: 'sortable-drag',
                    onEnd: function(evt) {
                        const oldIdx = evt.oldIndex;
                        const newIdx = evt.newIndex;
                        
                        if (oldIdx !== newIdx && colorImages[colorId]) {
                            // Reorder array
                            const movedItem = colorImages[colorId].splice(oldIdx, 1)[0];
                            colorImages[colorId].splice(newIdx, 0, movedItem);
                            
                            // Re-render to ensure "Main" badge is correct
                            renderImages(colorId);
                            updateHiddenInputs();
                        }
                    }
                });
            }
            
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
            <div class="relative group border-2 rounded-lg overflow-hidden bg-white shadow-sm hover:shadow-md transition-all ${idx === 0 ? 'border-blue-500 ring-2 ring-blue-100' : 'border-gray-200'} cursor-move">
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
    
    // Initial Render
    if(selectedColors.length > 0) {
        updateColorImagesUI();
    }
    
    // Handle next button (continue to step 3)
    document.getElementById('nextBtn')?.addEventListener('click', async function(e) {
        e.preventDefault();
        
        const stepForm = document.getElementById('stepForm');
        const formData = new FormData(stepForm);
        
        try {
            const response = await fetch(stepForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            
            const result = await response.json();
            
            if (result.success) {
                window.location.href = result.next_step_url || '{{ route("admin.products.create.step3") }}';
            } else {
                alert('Error: ' + (result.message || 'Failed to save step'));
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        }
    });

    document.getElementById('prevBtn')?.addEventListener('click', function(e) {
        e.preventDefault();
        window.location.href = '{{ route("admin.products.create.step1") }}';
    });
});
</script>
@endpush

@endsection
