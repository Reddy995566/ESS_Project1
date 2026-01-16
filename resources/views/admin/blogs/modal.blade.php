<!-- Add/Edit Modal -->
<div id="blogModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div id="modalOverlay" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity animate-fadeIn"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all duration-300 ease-out animate-slideUp sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
            <form id="blogForm" method="POST" action="{{ route('admin.blogs.store') }}">
                @csrf
                <input type="hidden" id="methodField" name="_method" value="POST">
                <input type="hidden" id="blogId" name="blog_id">
                <input type="hidden" id="imagekit_file_id" name="imagekit_file_id">
                <input type="hidden" id="imagekit_url" name="imagekit_url">
                <input type="hidden" id="imagekit_thumbnail_url" name="imagekit_thumbnail_url">
                <input type="hidden" id="imagekit_file_path" name="imagekit_file_path">
                
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <h3 id="modalTitle" class="text-xl font-bold text-gray-900">Add New Blog</h3>
                        </div>
                        <button type="button" id="closeModalBtn" class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg p-2 transition-all">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="px-6 py-6 space-y-6 max-h-[70vh] overflow-y-auto">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Title *</label>
                            <input type="text" name="title" id="title" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" placeholder="Enter blog title">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Slug</label>
                            <input type="text" name="slug" id="slug" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" placeholder="auto-generated-from-title">
                            <p class="text-xs text-gray-500 mt-1">Leave empty to auto-generate from title</p>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Short Description</label>
                            <textarea name="excerpt" id="excerpt" rows="3" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" placeholder="Brief summary of the blog post..."></textarea>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Content</label>
                            <div class="quill-editor-wrapper">
                                <div id="contentEditor" style="height: 300px;"></div>
                            </div>
                            <textarea name="content" id="content" class="hidden"></textarea>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Featured Image</label>
                            <input type="file" id="imageInput" accept="image/*" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <input type="hidden" id="featured_image" name="featured_image">
                            
                            <!-- Upload Progress -->
                            <div id="uploadProgress" class="hidden mt-3">
                                <div class="bg-gray-100 rounded-xl p-4 border-2 border-blue-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-semibold text-gray-700">Uploading...</span>
                                        <span id="uploadPercentage" class="text-sm font-bold text-blue-600">0%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                                        <div id="progressBar" class="bg-gradient-to-r from-blue-500 to-indigo-600 h-3 rounded-full transition-all duration-300" style="width: 0%"></div>
                                    </div>
                                    <div class="flex items-center justify-between mt-2 text-xs text-gray-600">
                                        <span id="uploadedSize">0 KB</span>
                                        <span id="totalSize">0 KB</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div id="imagePreview" class="mt-3"></div>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Gallery Images (Multiple)</label>
                            <input type="file" id="galleryInput" accept="image/*" multiple class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all">
                            <p class="text-xs text-gray-500 mt-1">Select multiple images for blog gallery</p>
                            
                            <!-- Gallery Preview -->
                            <div id="galleryPreview" class="mt-3 grid grid-cols-2 md:grid-cols-4 gap-3"></div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Author</label>
                            <input type="text" name="author" id="author" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" placeholder="Author name">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Published Date</label>
                            <input type="date" name="published_at" id="published_at" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Featured Blog</label>
                            <label class="flex items-center mt-3">
                                <input type="checkbox" name="is_featured" id="is_featured" value="1" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500">
                                <span class="ml-2 text-sm font-medium text-gray-700">Mark as featured</span>
                            </label>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Status</label>
                            <label class="flex items-center mt-3">
                                <input type="checkbox" name="is_active" id="is_active" value="1" checked class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500">
                                <span class="ml-2 text-sm font-medium text-gray-700">Active</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-t border-gray-200 flex justify-between items-center">
                    <button type="button" onclick="fillDummyData()" class="px-4 py-2.5 bg-gradient-to-r from-yellow-400 to-orange-500 text-white text-sm font-bold rounded-xl hover:from-yellow-500 hover:to-orange-600 shadow-md hover:shadow-lg transition-all">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        Fill Up
                    </button>
                    <div class="flex space-x-3">
                        <button type="button" id="cancelBtn" class="px-6 py-2.5 bg-white text-gray-700 text-sm font-semibold rounded-xl border-2 border-gray-300 hover:bg-gray-50 transition-all">
                            Cancel
                        </button>
                        <button type="submit" id="submitBtn" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-bold rounded-xl hover:from-blue-700 hover:to-indigo-700 shadow-lg hover:shadow-xl transition-all">
                            <span id="submitText">Save Blog</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
