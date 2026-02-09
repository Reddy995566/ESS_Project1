<!-- Product View Modal for Seller Panel -->
<div id="productModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Backdrop -->
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div id="modalOverlay" class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm"></div>
        
        <!-- Modal Container -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
            
            <!-- Modal Header - Sticky -->
            <div class="sticky top-0 z-10 bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4 shadow-lg">
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
                            <p class="text-indigo-100 text-sm">Complete product information</p>
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
                                <!-- Main Image Display -->
                                <div class="relative bg-gray-50 rounded-2xl overflow-hidden mb-4 group">
                                    <img id="modalProductMainImage" src="" alt="" class="w-full h-80 object-contain transition-transform duration-500">
                                </div>
                                
                                <!-- Thumbnail Gallery -->
                                <div id="modalImageThumbnails" class="flex space-x-3 overflow-x-auto pb-2">
                                    <!-- Thumbnails will be added here -->
                                </div>
                            </div>
                            
                            <!-- RIGHT COLUMN: Product Information -->
                            <div class="bg-white p-8 overflow-y-auto max-h-[600px]">
                                <!-- Product Title & SKU -->
                                <div class="mb-6">
                                    <div class="flex items-start justify-between mb-2">
                                        <h1 id="modalProductName" class="text-2xl font-black text-gray-900 leading-tight"></h1>
                                    </div>
                                    
                                    <div class="flex items-center space-x-3 text-sm">
                                        <span id="modalProductSku" class="text-gray-500 font-medium"></span>
                                        <span class="text-gray-300">•</span>
                                        <span id="modalProductId" class="text-indigo-600 font-semibold"></span>
                                    </div>
                                </div>
                                
                                <!-- Price Section -->
                                <div class="bg-emerald-50 rounded-xl p-5 mb-6 border border-emerald-200">
                                    <div class="flex items-baseline space-x-3 mb-2">
                                        <span class="text-3xl font-black text-gray-900" id="modalProductPrice"></span>
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
                                                <p id="modalProductStockStatus" class="text-xs"></p>
                                            </div>
                                        </div>
                                        <span id="modalProductStock" class="text-2xl font-black text-gray-900"></span>
                                    </div>
                                </div>
                                
                                <!-- Category -->
                                <div class="mb-6">
                                    <div class="flex flex-wrap gap-2 mb-3">
                                        <span class="text-xs font-bold text-gray-500">CATEGORY:</span>
                                        <span id="modalProductCategory" class="bg-indigo-100 text-indigo-700 text-xs font-bold px-3 py-1 rounded-full"></span>
                                    </div>
                                </div>
                                
                                <!-- Product Status -->
                                <div class="flex items-center space-x-4 mb-6">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-sm font-bold text-gray-600">Status:</span>
                                        <span id="modalProductStatus" class="font-bold"></span>
                                    </div>
                                </div>
                                
                                <!-- Description -->
                                <div class="border-t border-gray-200 pt-6">
                                    <h3 class="text-lg font-bold text-gray-900 mb-3">Description</h3>
                                    <div id="modalProductDescription" class="text-gray-700 leading-relaxed text-sm"></div>
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
/* Custom Scrollbar for modal */
#productModal .overflow-y-auto::-webkit-scrollbar {
    width: 6px;
}

#productModal .overflow-y-auto::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

#productModal .overflow-y-auto::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}

#productModal .overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>
<?php /**PATH C:\xampp\htdocs\ESS_Project1\resources\views/seller/products/modal-view.blade.php ENDPATH**/ ?>