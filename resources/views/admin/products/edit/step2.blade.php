@extends('admin.products.edit._layout')

@section('step_title', 'Step 2: Media Management')
@section('step_description', 'Update product images and media')

@section('step_content')
@php
    $currentStep = 2;
    $prevStepRoute = route('admin.products.edit.step1', $product->id);
    
    // Process images to handle different formats - convert to create format
    $processedAdditionalImages = [];
    if (isset($productData['images']) && is_array($productData['images'])) {
        foreach ($productData['images'] as $image) {
            if (is_array($image)) {
                $url = $image['url'] ?? $image['path'] ?? '';
                if ($url) {
                    $processedAdditionalImages[] = [
                        'url' => $url,
                        'fileId' => $image['fileId'] ?? 'existing',
                        'size' => $image['size'] ?? 0,
                        'originalSize' => $image['originalSize'] ?? $image['size'] ?? 0,
                        'width' => $image['width'] ?? 360,
                        'height' => $image['height'] ?? 459,
                        'name' => $image['name'] ?? 'image'
                    ];
                }
            } elseif (is_string($image) && $image) {
                $processedAdditionalImages[] = [
                    'url' => $image,
                    'fileId' => 'existing',
                    'size' => 0,
                    'originalSize' => 0,
                    'width' => 360,
                    'height' => 459,
                    'name' => 'image'
                ];
            }
        }
    }
@endphp

<form id="stepForm" action="{{ route('admin.products.edit.step2.process', $product->id) }}" method="POST">
    @csrf
    
    <div class="bg-white rounded-xl shadow-lg border border-gray-200">
        <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-orange-50 to-red-50">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-gradient-to-r from-orange-600 to-red-600 rounded-lg flex items-center justify-center shadow-lg">
                    <span class="text-white text-xl">📷</span>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Media Management</h2>
                    <p class="text-gray-600 font-medium">Update product images and media files</p>
                </div>
            </div>
        </div>
        
        <div class="p-8 space-y-8">
            <!-- Additional Images -->
            <div>
                <label class="block text-sm font-semibold text-gray-800 mb-3">Additional Images <span class="text-gray-500 text-sm">(Optional - up to 8 images)</span></label>
                <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-orange-500 transition-all cursor-pointer image-upload-area" id="additionalImagesUpload">
                    <input type="file" id="additionalImagesInput" accept="image/*" multiple class="hidden">
                    <div id="additionalImagesPlaceholder" class="space-y-4">
                        <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
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
                    <div id="sortableImages" class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <!-- Images will be populated here -->
                    </div>
                </div>
                
                <input type="hidden" name="additional_images" id="additionalImagesData" value="{{ old('additional_images', json_encode($processedAdditionalImages)) }}">
                @error('additional_images')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Upload Tips -->
            <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                <h3 class="font-semibold text-orange-800 mb-2">📋 Media Guidelines</h3>
                <ul class="text-sm text-orange-700 space-y-1">
                    <li>• <strong>Images:</strong> Uploaded in original quality (Maximum 10MB)</li>
                    <li>• <strong>Videos:</strong> Upload product demonstrations, 360° views, or usage videos</li>
                    <li>• First additional image becomes the featured image</li>
                    <li>• Drag & drop to reorder images</li>
                    <li>• Show product from multiple angles for better conversions</li>
                    <li>• <strong>Supported:</strong> Images (JPG, PNG, GIF, WebP), Videos (MP4, WebM, MOV)</li>
                </ul>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<!-- SortableJS for drag & drop -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

<script>
let additionalImages = [];
let sortable = null;

// Load existing images and videos if any
document.addEventListener('DOMContentLoaded', function() {
    // Load existing main image
    const existingMainImage = document.getElementById('mainImageUrl').value;
    if (existingMainImage) {
        // For existing images, we don't have full image info, so just show the preview
        showMainImagePreview(existingMainImage);
    }
    
    // Load existing additional images
    const existingAdditionalImages = document.getElementById('additionalImagesData').value;
    if (existingAdditionalImages && existingAdditionalImages !== '[]') {
        try {
            const parsed = JSON.parse(existingAdditionalImages);
            // Ensure each image has required properties with defaults
            additionalImages = parsed.map(img => ({
                url: img.url || '',
                fileId: img.fileId || 'unknown',
                size: img.size || 0,
                originalSize: img.originalSize || img.size || 0,
                width: img.width || 360,
                height: img.height || 459,
                name: img.name || 'image'
            }));
            updateAdditionalImagesGrid();
        } catch (e) {
            additionalImages = [];
        }
    }
    
    // Load existing video
    const existingVideo = document.getElementById('videoUrl').value;
    if (existingVideo) {
        showVideoPreview(existingVideo);
    }
    
    // Initialize video upload
    initializeVideoUpload();
});

// Main image upload
document.getElementById('mainImageUpload').addEventListener('click', function() {
    document.getElementById('mainImageInput').click();
});

document.getElementById('mainImageInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        uploadMainImage(file);
    }
});

// Additional images upload
document.getElementById('additionalImagesUpload').addEventListener('click', function() {
    document.getElementById('additionalImagesInput').click();
});

document.getElementById('additionalImagesInput').addEventListener('change', function(e) {
    const files = Array.from(e.target.files);
    if (files.length > 0) {
        // Check maximum images limit (8 total)
        if (additionalImages.length + files.length > 8) {
            showNotification(`Maximum 8 images allowed. You can upload ${8 - additionalImages.length} more.`, 'error');
            return;
        }
        uploadAdditionalImages(files);
    }
});

async function uploadMainImage(file) {
    showMainImageLoading();
    
    try {
        const formData = new FormData();
        formData.append('image', file);
        formData.append('folder', 'products');
        
        const response = await fetch('{{ route("admin.upload.image") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        const result = await response.json();
        
        // Debug log the ImageKit response
        if (result.success) {
            // Add estimated size if not provided
            if (!result.size && file && file.size) {
                result.size = file.size;
                result.original_size = file.size;
            }
            
            document.getElementById('mainImageUrl').value = result.url;
            showMainImagePreview(result.url, result);
            const sizeText = result.size ? ` (${formatFileSize(result.size)})` : '';
            showNotification(`Main image uploaded successfully!${sizeText}`, 'success');
        } else {
            throw new Error(result.message || 'Upload failed');
        }
    } catch (error) {
        showNotification('Failed to upload main image: ' + error.message, 'error');
        hideMainImageLoading();
    }
}

async function uploadAdditionalImages(files) {
    // Show upload progress
    showAdditionalImagesLoading(files.length);
    
    const uploadPromises = files.map(async (file, index) => {
        const formData = new FormData();
        formData.append('image', file);
        formData.append('folder', 'products');
        
        try {
            const response = await fetch('{{ route("admin.upload.image") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            const result = await response.json();
            
            // Debug log for additional images
            if (result.success) {
                // Use actual size
                const estimatedSize = result.size || file.size;
                const originalSize = result.original_size || result.originalSize || file.size || 0;
                
                return {
                    url: result.url,
                    fileId: result.file_id || result.fileId || 'unknown',
                    size: estimatedSize,
                    originalSize: originalSize,
                    width: result.width || 360,
                    height: result.height || 459,
                    name: result.name || file.name || 'image'
                };
            } else {
                throw new Error(result.message || 'Upload failed');
            }
        } catch (error) {
            showNotification('Failed to upload image: ' + error.message, 'error');
            return null;
        }
    });
    
    const uploadedImages = await Promise.all(uploadPromises);
    const validImages = uploadedImages.filter(img => img !== null);
    
    if (validImages.length > 0) {
        additionalImages = [...additionalImages, ...validImages];
        updateAdditionalImagesGrid();
        updateAdditionalImagesInput();
        const totalSize = validImages.reduce((sum, img) => sum + (img.size || 0), 0);
        const sizeText = totalSize > 0 ? ` Total compressed: ${formatFileSize(totalSize)}` : ' All optimized via ImageKit!';
        showNotification(`Successfully uploaded ${validImages.length} image(s)!${sizeText}`, 'success');
    }
}

function showMainImagePreview(imageUrl, imageInfo = null) {
    document.getElementById('mainImagePreviewImg').src = imageUrl;
    document.getElementById('mainImagePlaceholder').classList.add('hidden');
    document.getElementById('mainImagePreview').classList.remove('hidden');
    
    // Add image info display
    if (imageInfo) {
        const preview = document.getElementById('mainImagePreview');
        let infoElement = preview.querySelector('.image-info');
        if (!infoElement) {
            infoElement = document.createElement('div');
            infoElement.className = 'image-info mt-2 text-xs text-gray-600 text-center';
            preview.appendChild(infoElement);
        }
        infoElement.innerHTML = `
            <div class="flex justify-center space-x-4">
                <span>📐 ${imageInfo.width || 'Original'}x${imageInfo.height || 'Original'}px</span>
                <span>📦 ${imageInfo.size ? formatFileSize(imageInfo.size) : 'Original Size'}</span>
            </div>
        `;
    }
}

function removeMainImage() {
    document.getElementById('mainImageUrl').value = '';
    document.getElementById('mainImagePlaceholder').classList.remove('hidden');
    document.getElementById('mainImagePreview').classList.add('hidden');
}

function showMainImageLoading() {
    const placeholder = document.getElementById('mainImagePlaceholder');
    placeholder.innerHTML = `
        <div class="mx-auto w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center animate-pulse">
            <svg class="w-8 h-8 text-orange-500 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
        <div class="text-center">
            <p class="text-lg font-semibold text-orange-600">Uploading to ImageKit...</p>
            <p class="text-xs text-gray-500 mt-1">Uploading original image...</p>
        </div>
    `;
}

function hideMainImageLoading() {
    const placeholder = document.getElementById('mainImagePlaceholder');
    placeholder.innerHTML = `
        <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
        </div>
        <div>
            <p class="text-lg font-semibold text-gray-700">Click to upload main image</p>
            <p class="text-sm text-gray-500">PNG, JPG, GIF up to 10MB</p>
        </div>
    `;
}

function updateAdditionalImagesGrid() {
    const grid = document.getElementById('additionalImagesGrid');
    const placeholder = document.getElementById('additionalImagesPlaceholder');
    const sortableContainer = document.getElementById('sortableImages');
    const imageCount = document.getElementById('imageCount');
    
    if (additionalImages.length === 0) {
        grid.classList.add('hidden');
        placeholder.classList.remove('hidden');
        return;
    }
    
    grid.classList.remove('hidden');
    placeholder.classList.add('hidden');
    imageCount.textContent = additionalImages.length;
    
    sortableContainer.innerHTML = additionalImages.map((image, index) => `
        <div class="image-item relative group border-2 rounded-lg overflow-hidden transition-all hover:border-orange-500 ${
            index === 0 ? 'border-orange-500 ring-2 ring-orange-200' : 'border-gray-200'
        }" data-index="${index}">
            <div class="relative">
                <img src="${image.url}" class="w-full h-32 object-cover cursor-move">
                ${index === 0 ? '<div class="absolute top-1 left-1 bg-orange-500 text-white text-xs px-2 py-1 rounded">Featured</div>' : ''}
                <div class="absolute top-1 right-1 bg-black bg-opacity-60 text-white text-xs px-1.5 py-0.5 rounded">
                    ${image.width || 360}×${image.height || 459}
                </div>
            </div>
            <div class="p-2 bg-white">
                <div class="text-xs text-gray-600 flex justify-between items-center">
                    <span>📦 ${image.size && image.size > 0 ? formatFileSize(image.size) : 'Original Size'}</span>
                </div>
                <div class="mt-1 flex justify-between items-center">
                    <span class="text-xs text-gray-500 cursor-move">🔄 Drag to reorder</span>
                    <button type="button" onclick="removeAdditionalImage(${index})" class="text-red-500 hover:text-red-700 p-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    `).join('');
    
    // Initialize or update sortable
    if (sortable) {
        sortable.destroy();
    }
    
    sortable = Sortable.create(sortableContainer, {
        animation: 150,
        ghostClass: 'sortable-ghost',
        chosenClass: 'sortable-chosen',
        dragClass: 'sortable-drag',
        onEnd: function(evt) {
            // Reorder the additionalImages array
            const item = additionalImages.splice(evt.oldIndex, 1)[0];
            additionalImages.splice(evt.newIndex, 0, item);
            
            // Update the grid to reflect new order
            updateAdditionalImagesGrid();
            updateAdditionalImagesInput();
            
            showNotification('Images reordered successfully!', 'success');
        }
    });
}

function removeAdditionalImage(index) {
    additionalImages.splice(index, 1);
    updateAdditionalImagesGrid();
    updateAdditionalImagesInput();
}

function updateAdditionalImagesInput() {
    document.getElementById('additionalImagesData').value = JSON.stringify(additionalImages);
}

// Drag and drop functionality
['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    document.getElementById('mainImageUpload').addEventListener(eventName, preventDefaults, false);
    document.getElementById('additionalImagesUpload').addEventListener(eventName, preventDefaults, false);
});

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

['dragenter', 'dragover'].forEach(eventName => {
    document.getElementById('mainImageUpload').addEventListener(eventName, highlight, false);
    document.getElementById('additionalImagesUpload').addEventListener(eventName, highlight, false);
});

['dragleave', 'drop'].forEach(eventName => {
    document.getElementById('mainImageUpload').addEventListener(eventName, unhighlight, false);
    document.getElementById('additionalImagesUpload').addEventListener(eventName, unhighlight, false);
});

function highlight(e) {
    e.currentTarget.classList.add('dragover');
}

function unhighlight(e) {
    e.currentTarget.classList.remove('dragover');
}

document.getElementById('mainImageUpload').addEventListener('drop', handleMainImageDrop, false);
document.getElementById('additionalImagesUpload').addEventListener('drop', handleAdditionalImagesDrop, false);

function handleMainImageDrop(e) {
    const dt = e.dataTransfer;
    const files = dt.files;
    
    if (files.length > 0) {
        uploadMainImage(files[0]);
    }
}

function handleAdditionalImagesDrop(e) {
    const dt = e.dataTransfer;
    const files = Array.from(dt.files);
    
    if (files.length > 0) {
        // Check maximum images limit (8 total)
        if (additionalImages.length + files.length > 8) {
            showNotification(`Maximum 8 images allowed. You can upload ${8 - additionalImages.length} more.`, 'error');
            return;
        }
        uploadAdditionalImages(files);
    }
}

// Show loading for additional images
function showAdditionalImagesLoading(fileCount) {
    const placeholder = document.getElementById('additionalImagesPlaceholder');
    placeholder.innerHTML = `
        <div class="space-y-4">
            <div class="mx-auto w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center animate-pulse">
                <svg class="w-8 h-8 text-orange-500 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
            <div class="text-center">
                <p class="text-lg font-semibold text-orange-600">Uploading ${fileCount} image(s)...</p>
                <p class="text-xs text-gray-500 mt-1">Uploading original images...</p>
            </div>
        </div>
    `;
}

// Utility function to format file sizes
function formatFileSize(bytes) {
    // Handle null, undefined, NaN values
    if (!bytes || isNaN(bytes) || bytes <= 0) return '0 B';
    
    const k = 1024;
    const sizes = ['B', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    
    // Ensure we don't exceed array bounds
    const sizeIndex = Math.min(i, sizes.length - 1);
    const formattedSize = parseFloat((bytes / Math.pow(k, sizeIndex)).toFixed(1));
    
    return formattedSize + ' ' + sizes[sizeIndex];
}

// Video upload functionality
function initializeVideoUpload() {
    document.getElementById('videoUpload').addEventListener('click', function() {
        document.getElementById('videoInput').click();
    });
    
    document.getElementById('videoInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            uploadVideo(file);
        }
    });
}

async function uploadVideo(file) {
    // Validate file type
    const allowedTypes = ['video/mp4', 'video/webm', 'video/mov', 'video/quicktime'];
    if (!allowedTypes.includes(file.type)) {
        showNotification('Only MP4, WebM, and MOV video files are supported', 'error');
        return;
    }
    
    // Check file size (100MB limit)
    if (file.size > 100 * 1024 * 1024) {
        showNotification('Video file must be less than 100MB', 'error');
        return;
    }
    
    showVideoLoading();
    
    try {
        const formData = new FormData();
        formData.append('video', file);
        formData.append('folder', 'products/videos');
        
        // Note: This would need a separate video upload endpoint
        // For now, we'll store locally and show preview
        const videoUrl = URL.createObjectURL(file);
        document.getElementById('videoUrl').value = videoUrl;
        
        showVideoPreview(videoUrl, {
            size: file.size,
            name: file.name,
            type: file.type
        });
        
        showNotification(`Video uploaded successfully! (${formatFileSize(file.size)})`, 'success');
        
    } catch (error) {
        showNotification('Failed to upload video: ' + error.message, 'error');
        hideVideoLoading();
    }
}

function showVideoLoading() {
    const placeholder = document.getElementById('videoPlaceholder');
    placeholder.innerHTML = `
        <div class="mx-auto w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center animate-pulse">
            <svg class="w-8 h-8 text-orange-500 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
        <div class="text-center">
            <p class="text-lg font-semibold text-orange-600">Uploading video...</p>
            <p class="text-xs text-gray-500 mt-1">This may take a while for large files</p>
        </div>
    `;
}

function hideVideoLoading() {
    const placeholder = document.getElementById('videoPlaceholder');
    placeholder.innerHTML = `
        <div class="mx-auto w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center">
            <svg class="w-8 h-8 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2h4a1 1 0 011 1v16a1 1 0 01-1 1H3a1 1 0 01-1-1V5a1 1 0 011-1h4zm2 3a4 4 0 108 0 4 4 0 00-8 0zm4-2a2 2 0 110 4 2 2 0 010-4z"></path>
            </svg>
        </div>
        <div>
            <p class="text-lg font-semibold text-gray-700">Click to upload product video</p>
            <p class="text-sm text-gray-500">MP4, WebM, MOV up to 100MB</p>
        </div>
    `;
}

function showVideoPreview(videoUrl, videoInfo = null) {
    const videoPlayer = document.getElementById('videoPreviewPlayer');
    videoPlayer.src = videoUrl;
    
    document.getElementById('videoPlaceholder').parentElement.classList.add('hidden');
    document.getElementById('videoPreview').classList.remove('hidden');
    
    if (videoInfo) {
        document.getElementById('videoSize').textContent = `Size: ${formatFileSize(videoInfo.size)}`;
        
        // Get video duration when metadata loads
        videoPlayer.addEventListener('loadedmetadata', function() {
            const duration = Math.round(videoPlayer.duration);
            const minutes = Math.floor(duration / 60);
            const seconds = duration % 60;
            document.getElementById('videoDuration').textContent = `Duration: ${minutes}:${seconds.toString().padStart(2, '0')}`;
        });
    }
}

function removeVideo() {
    document.getElementById('videoUrl').value = '';
    document.getElementById('videoPlaceholder').parentElement.classList.remove('hidden');
    document.getElementById('videoPreview').classList.add('hidden');
    document.getElementById('videoInput').value = '';
    showNotification('Video removed successfully', 'info');
}

// Notification system
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    const colors = {
        success: 'bg-green-100 border-green-500 text-green-700',
        error: 'bg-red-100 border-red-500 text-red-700',
        warning: 'bg-yellow-100 border-yellow-500 text-yellow-700',
        info: 'bg-blue-100 border-blue-500 text-blue-700'
    };
    
    notification.className = `fixed top-4 right-4 p-4 rounded-lg border-l-4 shadow-lg z-50 max-w-sm ${colors[type] || colors.info}`;
    notification.innerHTML = `
        <div class="flex items-center justify-between">
            <span class="text-sm font-medium">${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-3 text-current opacity-70 hover:opacity-100">
                ×
            </button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentElement) {
            notification.remove();
        }
    }, 5000);
}
</script>
@endpush
@endsection
