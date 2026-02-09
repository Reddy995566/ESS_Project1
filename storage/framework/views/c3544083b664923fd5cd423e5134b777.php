<!-- Professional Product View Modal - Best UI/UX -->
<div id="productModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Backdrop -->
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div id="modalOverlay" class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm animate-fadeIn"></div>
        
        <!-- Modal Container -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-[95vw] sm:w-full animate-slideUp">
            
            <!-- Modal Header - Sticky -->
            <div class="sticky top-0 z-10 bg-indigo-600 px-6 py-4 shadow-lg">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="flex items-center justify-center w-10 h-10 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-black text-white">Product Details</h3>
                            <p class="text-purple-100 text-sm">Complete product information</p>
                        </div>
                    </div>
                    <button type="button" id="closeModalBtn" class="text-white hover:bg-white hover:bg-opacity-20 rounded-lg p-2 transition-all duration-200">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="bg-gray-50">
                <div id="modalContent">
                    <!-- Loading State -->
                    <div id="modalLoading" class="flex flex-col items-center justify-center py-20">
                        <div class="relative">
                            <div class="animate-spin rounded-full h-16 w-16 border-4 border-gray-200"></div>
                            <div class="animate-spin rounded-full h-16 w-16 border-4 border-indigo-600 border-t-transparent absolute inset-0"></div>
                        </div>
                        <p class="mt-4 text-gray-600 font-medium">Loading product details...</p>
                    </div>
                    
                    <!-- Product Content -->
                    <div id="productContent" class="hidden">
                        <!-- Main Product Section - 2 Column Layout -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
                            
                            <!-- LEFT COLUMN: Images & Gallery -->
                            <div class="bg-white p-8 border-r border-gray-200">
                                <!-- Product Badges - Floating -->
                                <div id="productBadges" class="flex flex-wrap gap-2 mb-4">
                                    <!-- Badges will be added here -->
                                </div>
                                
                                <!-- Main Image Display -->
                                <div class="relative bg-gray-50 rounded-2xl overflow-hidden mb-4 group">
                                    <img id="productMainImage" src="" alt="" class="w-full h-[500px] object-contain transition-transform duration-500 group-hover:scale-105">
                                    
                                    <!-- Image Navigation Arrows -->
                                    <button id="prevImage" class="absolute left-4 top-1/2 -translate-y-1/2 bg-white shadow-xl text-gray-800 p-3 rounded-full hover:bg-indigo-600 hover:text-white transition-all duration-200 opacity-0 group-hover:opacity-100 hidden">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                                        </svg>
                                    </button>
                                    <button id="nextImage" class="absolute right-4 top-1/2 -translate-y-1/2 bg-white shadow-xl text-gray-800 p-3 rounded-full hover:bg-indigo-600 hover:text-white transition-all duration-200 opacity-0 group-hover:opacity-100 hidden">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </button>
                                    
                                    <!-- Zoom Indicator -->
                                    <div class="absolute bottom-4 right-4 bg-black bg-opacity-50 text-white text-xs px-3 py-1 rounded-full opacity-0 group-hover:opacity-100 transition-opacity">
                                        Click to zoom
                                    </div>
                                </div>
                                
                                <!-- Thumbnail Gallery -->
                                <div id="imageThumbnails" class="flex space-x-3 overflow-x-auto pb-2 scrollbar-thin scrollbar-thumb-gray-300">
                                    <!-- Thumbnails will be added here -->
                                </div>
                                
                                <!-- Image Counter -->
                                <div id="imageCounter" class="text-center text-sm text-gray-500 mt-3 font-medium hidden">
                                    <span id="currentImageIndex">1</span> / <span id="totalImages">1</span>
                                </div>
                            </div>
                            
                            <!-- RIGHT COLUMN: Product Information -->
                            <div class="bg-white p-8 overflow-y-auto max-h-[600px] scrollbar-thin scrollbar-thumb-gray-300">
                                <!-- Product Title & SKU -->
                                <div class="mb-6">
                                    <div class="flex items-start justify-between mb-2">
                                        <h1 id="productName" class="text-3xl font-black text-gray-900 leading-tight"></h1>
                                        <button class="text-gray-400 hover:text-red-500 transition-colors">
                                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                            </svg>
                                        </button>
                                    </div>
                                    
                                    <div class="flex items-center space-x-3 text-sm">
                                        <span id="productSku" class="text-gray-500 font-medium"></span>
                                        <span class="text-gray-300">•</span>
                                        <div id="productBrand" class="text-indigo-600 font-semibold hidden"></div>
                                    </div>
                                </div>
                                
                                <!-- Price Section -->
                                <div class="bg-emerald-50 rounded-xl p-5 mb-6 border border-emerald-200">
                                    <div class="flex items-baseline space-x-3 mb-2">
                                        <span class="text-4xl font-black text-gray-900" id="productPrice"></span>
                                        <span id="productSalePrice" class="text-xl text-gray-400 line-through hidden"></span>
                                        <span id="discountPercentage" class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded hidden"></span>
                                    </div>
                                    <p class="text-sm text-emerald-700 font-medium">Inclusive of all taxes</p>
                                </div>
                                
                                <!-- Stock Status -->
                                <div class="mb-6">
                                    <div class="flex items-center justify-between bg-gray-50 rounded-lg p-4">
                                        <div class="flex items-center space-x-3">
                                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                            </svg>
                                            <div>
                                                <p class="text-sm font-bold text-gray-700">Availability</p>
                                                <p id="productStockStatus" class="text-xs"></p>
                                            </div>
                                        </div>
                                        <span id="productStock" class="text-2xl font-black text-gray-900"></span>
                                    </div>
                                    <div id="lowStockWarning" class="mt-2 bg-orange-50 border border-orange-200 rounded-lg p-3 hidden">
                                        <p class="text-sm text-orange-700 font-medium">⚠️ Only few items left in stock!</p>
                                    </div>
                                </div>
                                
                                <!-- Category & Tags -->
                                <div class="mb-6 pb-6 border-b border-gray-200">
                                    <div class="flex flex-wrap gap-2 mb-3">
                                        <span class="text-xs font-bold text-gray-500">CATEGORY:</span>
                                        <span id="productCategory" class="bg-purple-100 text-purple-700 text-xs font-bold px-3 py-1 rounded-full"></span>
                                    </div>
                                    
                                    <div id="productTags" class="hidden">
                                        <span class="text-xs font-bold text-gray-500 mb-2 block">TAGS:</span>
                                        <div id="tagsContainer" class="flex flex-wrap gap-2">
                                            <!-- Tags will be added here -->
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Product Status -->
                                <div class="flex items-center space-x-4 mb-6">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-sm font-bold text-gray-600">Status:</span>
                                        <span id="productStatus" class="font-bold"></span>
                                    </div>
                                    <div id="visibility" class="flex items-center space-x-2">
                                        <span class="text-sm font-bold text-gray-600">Visibility:</span>
                                        <span class="text-sm font-medium"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Tabbed Content Section -->
                        <div class="bg-white border-t border-gray-200">
                            <!-- Tab Navigation -->
                            <div class="border-b border-gray-200 bg-gray-50">
                                <nav class="flex space-x-1 px-8" aria-label="Tabs">
                                    <button onclick="switchTab('description')" class="modal-tab active px-6 py-4 text-sm font-bold text-gray-900 border-b-2 border-indigo-600 transition-all">
                                        Description
                                    </button>
                                    <button onclick="switchTab('variants')" id="variantsTab" class="modal-tab px-6 py-4 text-sm font-bold text-gray-500 hover:text-gray-900 border-b-2 border-transparent hover:border-gray-300 transition-all">
                                        Variants
                                    </button>
                                    <button onclick="switchTab('attributes')" class="modal-tab px-6 py-4 text-sm font-bold text-gray-500 hover:text-gray-900 border-b-2 border-transparent hover:border-gray-300 transition-all">
                                        Attributes
                                    </button>
                                    <button onclick="switchTab('details')" class="modal-tab px-6 py-4 text-sm font-bold text-gray-500 hover:text-gray-900 border-b-2 border-transparent hover:border-gray-300 transition-all">
                                        Details
                                    </button>
                                    <button onclick="switchTab('seo')" class="modal-tab px-6 py-4 text-sm font-bold text-gray-500 hover:text-gray-900 border-b-2 border-transparent hover:border-gray-300 transition-all">
                                        SEO
                                    </button>
                                </nav>
                            </div>
                            
                            <!-- Tab Content -->
                            <div class="p-8">
                                <!-- Description Tab -->
                                <div id="tab-description" class="tab-content">
                                    <div class="prose prose-lg max-w-none">
                                        <h3 class="text-xl font-bold text-gray-900 mb-4">Product Description</h3>
                                        <div id="productDescription" class="text-gray-700 leading-relaxed"></div>
                                        
                                        <div id="shortDescription" class="mt-6 hidden">
                                            <h4 class="text-lg font-bold text-gray-800 mb-2">Short Description</h4>
                                            <div id="productShortDescription" class="text-gray-600"></div>
                                        </div>
                                        
                                        <div id="additionalInfo" class="mt-6 bg-blue-50 rounded-xl p-6 border border-blue-200 hidden">
                                            <h4 class="text-lg font-bold text-gray-900 mb-3 flex items-center">
                                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                Additional Information
                                            </h4>
                                            <div id="productAdditionalInfo" class="text-gray-700"></div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Variants Tab -->
                                <div id="tab-variants" class="tab-content hidden">
                                    <div id="productVariantsSection" class="hidden">
                                        <h3 class="text-xl font-bold text-gray-900 mb-6">Product Variants</h3>
                                        
                                        <!-- Color Selector -->
                                        <div id="variantTabs" class="mb-6">
                                            <p class="text-sm font-bold text-gray-600 mb-3">SELECT COLOR:</p>
                                            <div id="colorVariantButtons" class="flex flex-wrap gap-3">
                                                <!-- Color buttons -->
                                            </div>
                                        </div>
                                        
                                        <!-- Selected Variant Details -->
                                        <div id="selectedVariantDetails" class="bg-gradient-to-r from-gray-50 to-slate-50 rounded-xl p-6 border border-gray-200">
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                                <div>
                                                    <h4 class="text-lg font-bold text-gray-800 mb-4">Variant Images</h4>
                                                    <div id="variantImagesGallery" class="grid grid-cols-2 gap-3"></div>
                                                </div>
                                                <div>
                                                    <h4 class="text-lg font-bold text-gray-800 mb-4">Variant Details</h4>
                                                    <div id="variantInfo" class="space-y-3"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Simple Attributes -->
                                    <div id="simpleAttributes" class="hidden">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                            <div id="productColors" class="hidden">
                                                <h4 class="text-lg font-bold text-gray-800 mb-4">Available Colors</h4>
                                                <div id="colorsContainer" class="flex flex-wrap gap-3"></div>
                                            </div>
                                            <div id="productSizes" class="hidden">
                                                <h4 class="text-lg font-bold text-gray-800 mb-4">Available Sizes</h4>
                                                <div id="sizesContainer" class="flex flex-wrap gap-3"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Attributes Tab -->
                                <div id="tab-attributes" class="tab-content hidden">
                                    <h3 class="text-xl font-bold text-gray-900 mb-6">Product Attributes & Features</h3>
                                    <div id="productAttributes">
                                        <div id="attributesGrid" class="grid grid-cols-2 md:grid-cols-3 gap-4"></div>
                                    </div>
                                </div>
                                
                                <!-- Details Tab -->
                                <div id="tab-details" class="tab-content hidden">
                                    <h3 class="text-xl font-bold text-gray-900 mb-6">Complete Product Details</h3>
                                    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <tbody id="productDetailsTable" class="divide-y divide-gray-200"></tbody>
                                        </table>
                                    </div>
                                </div>
                                
                                <!-- SEO Tab -->
                                <div id="tab-seo" class="tab-content hidden">
                                    <h3 class="text-xl font-bold text-gray-900 mb-6">SEO & Metadata</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="bg-green-50 rounded-xl p-6 border border-green-200">
                                            <h4 class="text-lg font-bold text-gray-900 mb-4">SEO Information</h4>
                                            <div class="space-y-4">
                                                <div>
                                                    <label class="text-sm font-bold text-gray-600">Meta Title</label>
                                                    <p id="productMetaTitle" class="text-gray-800 mt-1">-</p>
                                                </div>
                                                <div>
                                                    <label class="text-sm font-bold text-gray-600">Meta Description</label>
                                                    <p id="productMetaDescription" class="text-gray-800 mt-1">-</p>
                                                </div>
                                                <div>
                                                    <label class="text-sm font-bold text-gray-600">URL Slug</label>
                                                    <p id="productSlug" class="text-gray-800 mt-1 font-mono bg-white px-3 py-2 rounded border">-</p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="bg-orange-50 rounded-xl p-6 border border-orange-200">
                                            <h4 class="text-lg font-bold text-gray-900 mb-4">System Information</h4>
                                            <div class="space-y-4">
                                                <div>
                                                    <label class="text-sm font-bold text-gray-600">Product ID</label>
                                                    <p id="productId" class="text-gray-800 mt-1 font-mono">-</p>
                                                </div>
                                                <div>
                                                    <label class="text-sm font-bold text-gray-600">Created</label>
                                                    <p id="productCreated" class="text-gray-800 mt-1">-</p>
                                                </div>
                                                <div>
                                                    <label class="text-sm font-bold text-gray-600">Last Updated</label>
                                                    <p id="productUpdated" class="text-gray-800 mt-1">-</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="bg-gradient-to-r from-gray-50 to-slate-50 px-8 py-5 border-t border-gray-200 flex items-center justify-between">
                <div class="text-sm text-gray-500">
                    <span class="font-medium">Last viewed:</span> <span id="viewTimestamp"></span>
                </div>
                <button id="closeModal" class="px-6 py-3 border-2 border-gray-300 text-gray-700 text-sm font-bold rounded-xl hover:bg-gray-100 transition-all duration-200">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom Scrollbar */
.scrollbar-thin::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}

.scrollbar-thin::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.scrollbar-thin::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}

.scrollbar-thin::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

.scrollbar-thumb-gray-300::-webkit-scrollbar-thumb {
    background: #d1d5db;
}

/* Tab Active State */
.modal-tab.active {
    color: #4f46e5;
    border-bottom-color: #4f46e5;
}

/* Smooth Transitions */
.tab-content {
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes slideUp {
    from { opacity: 0; transform: translateY(50px) scale(0.95); }
    to { opacity: 1; transform: translateY(0) scale(1); }
}

.animate-slideUp {
    animation: slideUp 0.3s ease-out;
}

.animate-fadeIn {
    animation: fadeIn 0.3s ease-out;
}
</style>

<script>
// Tab Switching Function
function switchTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active class from all tabs
    document.querySelectorAll('.modal-tab').forEach(tab => {
        tab.classList.remove('active', 'text-gray-900', 'border-indigo-600');
        tab.classList.add('text-gray-500', 'border-transparent');
    });
    
    // Show selected tab content
    document.getElementById(`tab-${tabName}`).classList.remove('hidden');
    
    // Add active class to clicked tab
    event.target.classList.add('active', 'text-gray-900', 'border-indigo-600');
    event.target.classList.remove('text-gray-500', 'border-transparent');
}
</script>
<?php /**PATH C:\xampp\htdocs\ESS_Project1\resources\views/admin/products/modal-new.blade.php ENDPATH**/ ?>