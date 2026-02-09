@extends('seller.products.edit._layout')

@section('step_title', 'Step 2: Media & Images')
@section('step_description', 'Upload product images and media')

@push('styles')
<style>
.sortable-ghost { opacity: 0.5; transform: rotate(2deg); }
.sortable-chosen { transform: scale(1.05); box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15); border: 2px solid #3b82f6 !important; }
.sortable-drag { transform: rotate(5deg) scale(1.1); z-index: 1000; }
.image-item { transition: all 0.2s ease; }
.image-item:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); }
.image-upload-area { transition: all 0.3s ease; }
.image-upload-area.dragover { transform: scale(1.02); border-color: #3b82f6; background-color: #eff6ff; }
</style>
@endpush

@section('step_content')
@php
    $currentStep = 2;
    $isEditMode = isset($product) && $product !== null;
    $prevStepRoute = $isEditMode ? route('seller.products.edit', ['id' => $product->id, 'step' => 1]) : route('seller.products.create.step1');
    
    // Load existing main image
    $mainImage = '';
    if ($isEditMode && $product) {
        $mainImage = $product->image ?? '';
    }
    
    // Load existing additional images - safe parsing
    $existingImagesJson = '[]';
    if ($isEditMode && $product) {
        try {
            if (is_string($product->images) && !empty($product->images)) {
                $decoded = json_decode($product->images, true);
                if (is_array($decoded) && count($decoded) > 0) {
                    $existingImagesJson = json_encode($decoded);
                }
            } elseif (is_array($product->images) && count($product->images) > 0) {
                $existingImagesJson = json_encode($product->images);
            }
        } catch (\Exception $e) {
            $existingImagesJson = '[]';
        }
    }
@endphp

<form id="stepForm">
    @csrf
    
    <div class="bg-white rounded-xl shadow-lg border border-gray-200">
        <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-pink-50">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-gradient-to-r from-indigo-600 to-pink-600 rounded-lg flex items-center justify-center shadow-lg">
                    <span class="text-white text-xl">📸</span>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Product Media</h2>
                    <p class="text-gray-600 font-medium">Upload high-quality product images</p>
                </div>
            </div>
        </div>
        
        <div class="p-8 space-y-8">
            <!-- Main Image -->
            <div>
                <label class="block text-sm font-semibold text-gray-800 mb-3">Featured Image <span class="text-red-500">*</span></label>
                <input type="hidden" name="image" id="mainImageUrl" value="{{ $mainImage }}">
                <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-blue-500 transition-all cursor-pointer image-upload-area" id="mainImageUpload">
                    <input type="file" id="mainImageInput" accept="image/*" class="hidden">
                    <div id="mainImagePlaceholder" class="space-y-4">
                        @if($mainImage)
                            <img src="{{ $mainImage }}" class="max-h-48 mx-auto rounded-lg object-contain" alt="Featured image" onerror="this.style.display='none'">
                            <div class="flex items-center justify-center space-x-2">
                                <span class="text-green-600 text-sm">✓ Featured image loaded</span>
                            </div>
                        @else
                            <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-lg font-semibold text-gray-700">Click to upload featured image</p>
                                <p class="text-sm text-gray-500">First image will be main display image</p>
                                <p class="text-xs text-gray-400">PNG, JPG, GIF up to 10MB</p>
                            </div>
                        @endif
                    </div>
                    <div id="mainImagePreview" class="hidden">
                        <img id="mainImagePreviewImg" src="" class="max-h-48 mx-auto rounded-lg object-contain">
                        <button type="button" onclick="removeMainImage()" class="mt-2 text-red-500 hover:text-red-700 text-sm">Remove</button>
                    </div>
                </div>
            </div>

            <!-- Additional Images -->
            <div>
                <label class="block text-sm font-semibold text-gray-800 mb-3">Additional Images <span class="text-gray-500 text-sm">(Optional - up to 8 images)</span></label>
                <input type="hidden" name="images" id="additionalImagesData" value="{{ $existingImagesJson }}">
                <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-blue-500 transition-all cursor-pointer image-upload-area" id="additionalImagesUpload">
                    <input type="file" id="additionalImagesInput" accept="image/*" multiple class="hidden">
                    <div id="additionalImagesPlaceholder" class="space-y-4">
                        <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-lg font-semibold text-gray-700">Click to upload additional images</p>
                            <p class="text-sm text-gray-500">Select multiple images at once</p>
                            <p class="text-xs text-gray-400">PNG, JPG, GIF up to 10MB each</p>
                        </div>
                    </div>
                </div>
                
                <!-- Additional Images Preview Grid -->
                <div id="additionalImagesGrid" class="hidden mt-6">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-sm font-semibold text-gray-800">Uploaded Images (<span id="imageCount">0</span>)</h4>
                        <p class="text-xs text-gray-500">Drag to reorder • First image will be featured</p>
                    </div>
                    <div id="sortableImages" class="grid grid-cols-2 md:grid-cols-4 gap-4"></div>
                </div>
            </div>

            <!-- Upload tips -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h3 class="font-semibold text-blue-800 mb-2">📋 Media Guidelines</h3>
                <ul class="text-sm text-blue-700 space-y-1">
                    <li>• Upload product from multiple angles for better conversions</li>
                    <li>• First additional image becomes the featured image</li>
                    <li>• Drag & drop to reorder images</li>
                    <li>• Maximum 8 images allowed</li>
                    <li>• <strong>Supported:</strong> JPG, PNG, GIF, WebP (max 10MB each)</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <div class="flex justify-between items-center mt-6">
        <a href="{{ $prevStepRoute }}" class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-semibold transition-all">← Previous</a>
        <button type="button" id="nextBtn" class="px-8 py-3 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white rounded-lg font-semibold transition-all shadow-lg">Continue to Step 3 →</button>
    </div>
</form>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
const uploadRoute = '{{ route("seller.upload.image") }}';
let additionalImages = [];
let sortable = null;

document.addEventListener('DOMContentLoaded', function() {
    // Load existing main image
    const mainImageUrl = document.getElementById('mainImageUrl').value;
    if (mainImageUrl && mainImageUrl.trim() !== '') {
        const img = new Image();
        img.onload = function() {
            showMainImagePreview(mainImageUrl);
        };
        img.onerror = function() {
            console.log('Main image failed to load:', mainImageUrl);
            // Reset the field if image doesn't exist
            document.getElementById('mainImageUrl').value = '';
        };
        img.src = mainImageUrl;
    }
    
    // Load existing additional images with error handling
    try {
        const existingImagesData = document.getElementById('additionalImagesData').value;
        if (existingImagesData && existingImagesData.trim() !== '' && existingImagesData !== '[]') {
            const parsed = JSON.parse(existingImagesData);
            if (Array.isArray(parsed) && parsed.length > 0) {
                additionalImages = [];
                parsed.forEach(function(img) {
                    if (img && img.url && typeof img.url === 'string') {
                        // Verify image exists
                        const testImg = new Image();
                        testImg.onload = function() {
                            additionalImages.push({
                                url: img.url,
                                fileId: img.fileId || 'unknown',
                                size: img.size || 0,
                                width: img.width || 360,
                                height: img.height || 459,
                                name: img.name || 'image'
                            });
                            if (additionalImages.length === parsed.length) {
                                updateAdditionalImagesGrid();
                            }
                        };
                        testImg.onerror = function() {
                            console.log('Image failed to load:', img.url);
                            // Skip this image but continue with others
                            if (additionalImages.length + parsed.filter(function(i) { return i && i.url; }).length >= parsed.length) {
                                if (additionalImages.length > 0) {
                                    updateAdditionalImagesGrid();
                                }
                            }
                        };
                        testImg.src = img.url;
                    }
                });
            }
        }
    } catch (e) {
        console.log('Error parsing existing images:', e);
        additionalImages = [];
    }
    
    initializeUploads();
});

function initializeUploads() {
    // Main image upload
    const mainImageUpload = document.getElementById('mainImageUpload');
    const mainImageInput = document.getElementById('mainImageInput');
    if (mainImageUpload && mainImageInput) {
        mainImageUpload.addEventListener('click', function() {
            mainImageInput.click();
        });
        mainImageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                uploadMainImage(file);
            }
        });
    }
    
    // Additional images upload
    const additionalUpload = document.getElementById('additionalImagesUpload');
    const additionalInput = document.getElementById('additionalImagesInput');
    if (additionalUpload && additionalInput) {
        additionalUpload.addEventListener('click', function() {
            additionalInput.click();
        });
        additionalInput.addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            if (files.length > 0) {
                if (additionalImages.length + files.length > 8) {
                    showNotification('Maximum 8 images allowed. You can upload ' + (8 - additionalImages.length) + ' more.', 'error');
                    return;
                }
                uploadAdditionalImages(files);
            }
        });
    }
    
    // Drag and drop
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(function(eventName) {
        [mainImageUpload, additionalUpload].forEach(function(el) {
            if (el) {
                el.addEventListener(eventName, preventDefaults, false);
            }
        });
    });
    
    ['dragenter', 'dragover'].forEach(function(eventName) {
        [mainImageUpload, additionalUpload].forEach(function(el) {
            if (el) {
                el.addEventListener(eventName, highlight, false);
            }
        });
    });
    
    ['dragleave', 'drop'].forEach(function(eventName) {
        [mainImageUpload, additionalUpload].forEach(function(el) {
            if (el) {
                el.addEventListener(eventName, unhighlight, false);
            }
        });
    });
    
    if (additionalUpload) {
        additionalUpload.addEventListener('drop', handleAdditionalDrop, false);
    }
}

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

function highlight(e) {
    e.currentTarget.classList.add('dragover');
}

function unhighlight(e) {
    e.currentTarget.classList.remove('dragover');
}

async function uploadMainImage(file) {
    showMainImageLoading();
    try {
        const formData = new FormData();
        formData.append('image', file);
        formData.append('folder', 'products');
        const response = await fetch(uploadRoute, { 
            method: 'POST', 
            body: formData, 
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        });
        const result = await response.json();
        if (result.success) {
            document.getElementById('mainImageUrl').value = result.url;
            showMainImagePreview(result.url);
            showNotification('Main image uploaded successfully!', 'success');
        } else {
            throw new Error(result.message || 'Upload failed');
        }
    } catch (error) {
        showNotification('Failed to upload main image: ' + error.message, 'error');
        hideMainImageLoading();
    }
}

async function uploadAdditionalImages(files) {
    showAdditionalImagesLoading(files.length);
    const uploadPromises = Array.from(files).map(async function(file) {
        const formData = new FormData();
        formData.append('image', file);
        formData.append('folder', 'products');
        try {
            const response = await fetch(uploadRoute, { 
                method: 'POST', 
                body: formData, 
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
            });
            const result = await response.json();
            if (result.success) {
                return {
                    url: result.url,
                    fileId: result.fileId || 'unknown',
                    size: result.size || file.size,
                    width: result.width || 360,
                    height: result.height || 459,
                    name: result.name || file.name
                };
            }
            throw new Error(result.message || 'Upload failed');
        } catch (error) {
            showNotification('Failed to upload: ' + error.message, 'error');
            return null;
        }
    });
    
    const uploaded = await Promise.all(uploadPromises);
    const valid = uploaded.filter(function(img) { return img !== null; });
    if (valid.length > 0) {
        additionalImages = additionalImages.concat(valid);
        updateAdditionalImagesGrid();
        updateAdditionalImagesInput();
        showNotification('Successfully uploaded ' + valid.length + ' image(s)!', 'success');
    }
}

function showMainImagePreview(imageUrl) {
    document.getElementById('mainImagePreviewImg').src = imageUrl;
    document.getElementById('mainImagePlaceholder').classList.add('hidden');
    document.getElementById('mainImagePreview').classList.remove('hidden');
}

function removeMainImage() {
    document.getElementById('mainImageUrl').value = '';
    document.getElementById('mainImagePlaceholder').classList.remove('hidden');
    document.getElementById('mainImagePreview').classList.add('hidden');
}

function showMainImageLoading() {
    document.getElementById('mainImagePlaceholder').innerHTML = '<div class="mx-auto w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center animate-pulse"><svg class="w-8 h-8 text-blue-500 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg></div><p class="text-lg font-semibold text-blue-600">Uploading...</p>';
}

function hideMainImageLoading() {
    document.getElementById('mainImagePlaceholder').innerHTML = '<div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center"><svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div><p class="text-lg font-semibold text-gray-700">Click to upload main image</p><p class="text-sm text-gray-500">PNG, JPG, GIF up to 10MB</p>';
}

function showAdditionalImagesLoading(fileCount) {
    document.getElementById('additionalImagesPlaceholder').innerHTML = '<div class="space-y-4"><div class="mx-auto w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center animate-pulse"><svg class="w-8 h-8 text-blue-500 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg></div><p class="text-lg font-semibold text-blue-600">Uploading ' + fileCount + ' image(s)...</p></div>';
}

function updateAdditionalImagesGrid() {
    const grid = document.getElementById('additionalImagesGrid');
    const placeholder = document.getElementById('additionalImagesPlaceholder');
    const container = document.getElementById('sortableImages');
    const count = document.getElementById('imageCount');
    
    if (!additionalImages || additionalImages.length === 0) {
        grid.classList.add('hidden');
        placeholder.classList.remove('hidden');
        return;
    }
    
    grid.classList.remove('hidden');
    placeholder.classList.add('hidden');
    count.textContent = additionalImages.length;
    
    let html = '';
    additionalImages.forEach(function(img, index) {
        if (!img || !img.url) return;
        html += '<div class="image-item relative group border-2 rounded-lg overflow-hidden ' + (index === 0 ? 'border-blue-500 ring-2 ring-blue-200' : 'border-gray-200') + '" data-index="' + index + '">';
        html += '<img src="' + img.url + '" class="w-full h-32 object-cover cursor-move" onerror="this.src=\'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTUwIiBoZWlnaHQ9IjE1MCIgdmlld0JveD0iMCAwIDE1MCAxNTAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHJlY3Qgd2lkdGg9IjE1MCIgaGVpZ2h0PSIxNTAiIGZpbGw9IiNmM2Y0ZjYiLz48dGV4dCB4PSI3NSIgeT0iNzUiIGZvbnQtZmFtaWx5PSJzYW5zLXNlcmlmIiBmb250LXNpemU9IjE0IiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBmaWxsPSIjOTNhM2ZiIj5JbWFnZSBub3QgZm91bmQ8L3RleHQ+PC9zdmc+\'">';
        if (index === 0) {
            html += '<div class="absolute top-1 left-1 bg-blue-500 text-white text-xs px-2 py-1 rounded">Featured</div>';
        }
        html += '<div class="p-2 bg-white"><div class="flex justify-between items-center"><span class="text-xs text-gray-600">' + formatFileSize(img.size) + '</span><button type="button" onclick="removeAdditionalImage(' + index + ')" class="text-red-500 hover:text-red-700">✕</button></div></div></div>';
    });
    container.innerHTML = html;
    
    if (sortable) {
        sortable.destroy();
    }
    sortable = Sortable.create(container, { 
        animation: 150, 
        ghostClass: 'sortable-ghost', 
        chosenClass: 'sortable-chosen', 
        dragClass: 'sortable-drag', 
        onEnd: function(evt) {
            const item = additionalImages.splice(evt.oldIndex, 1)[0];
            additionalImages.splice(evt.newIndex, 0, item);
            updateAdditionalImagesGrid();
            updateAdditionalImagesInput();
            showNotification('Images reordered successfully!', 'success');
        }
    });
}

function removeAdditionalImage(index) {
    if (additionalImages[index]) {
        additionalImages.splice(index, 1);
        updateAdditionalImagesGrid();
        updateAdditionalImagesInput();
    }
}

function updateAdditionalImagesInput() {
    document.getElementById('additionalImagesData').value = JSON.stringify(additionalImages);
}

function handleAdditionalDrop(e) {
    const files = Array.from(e.dataTransfer.files).filter(function(f) { return f.type.startsWith('image/'); });
    if (files.length > 0) {
        if (additionalImages.length + files.length > 8) {
            showNotification('Maximum 8 images allowed', 'error');
            return;
        }
        uploadAdditionalImages(files);
    }
}

function formatFileSize(bytes) {
    if (!bytes || isNaN(bytes) || bytes <= 0) return '0 B';
    const k = 1024, sizes = ['B', 'KB', 'MB', 'GB'], i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[Math.min(i, sizes.length - 1)];
}

// Form submission
document.getElementById('nextBtn')?.addEventListener('click', async function() {
    const formData = new FormData();
    formData.append('image', document.getElementById('mainImageUrl').value);
    formData.append('images', document.getElementById('additionalImagesData').value);
    
    let url;
    <?php if($isEditMode && isset($product)): ?>
    formData.append('_method', 'PUT');
    url = '/seller/products/{{ $product->id }}/update';
    <?php else: ?>
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
    url = '{{ route("seller.products.create.step2.process") }}';
    <?php endif; ?>
    
    const btn = this;
    btn.disabled = true;
    btn.innerHTML = 'Saving...';
    
    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
            body: formData
        });
        const data = await response.json();
        if (data.success) {
            showNotification('Media saved successfully!', 'success');
            <?php if($isEditMode && isset($product)): ?>
            window.location.href = '/seller/products/{{ $product->id }}/edit?step=3';
            <?php else: ?>
            window.location.href = '{{ route("seller.products.create.step3") }}';
            <?php endif; ?>
        } else {
            showNotification(data.message || 'Error saving', 'error');
            btn.disabled = false;
            btn.innerHTML = 'Continue to Step 3 →';
        }
    } catch (error) {
        showNotification('An error occurred', 'error');
        btn.disabled = false;
        btn.innerHTML = 'Continue to Step 3 →';
    }
});
</script>
@endpush
@endsection
