@push('scripts')
<!-- Quill Editor -->
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

<script>
// Blogs data from server
let blogsData = @json($blogs);
let currentPage = 1;
let itemsPerPage = 25;
let filteredData = [...blogsData];

// Debug: Check if images are loaded
if (blogsData.length > 0) {
    }

// Initialize Quill editor
let contentQuill = new Quill('#contentEditor', {
    modules: {
        toolbar: [
            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
            ['bold', 'italic', 'underline', 'strike'],
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            [{ 'color': [] }, { 'background': [] }],
            [{ 'align': [] }],
            ['link', 'image', 'video'],
            ['clean']
        ]
    },
    theme: 'snow',
    placeholder: 'Write your blog content here...'
});

// Update hidden field on content change
contentQuill.on('text-change', function() {
    document.getElementById('content').value = contentQuill.root.innerHTML;
});

// Auto-generate slug from title
document.getElementById('title')?.addEventListener('input', function() {
    const slug = this.value.toLowerCase()
        .replace(/[^\w\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-');
    document.getElementById('slug').value = slug;
});

// Image upload with preview
document.getElementById('imageInput')?.addEventListener('change', async function(e) {
    const file = e.target.files[0];
    if (!file) return;

    // Show file size
    const fileSizeKB = (file.size / 1024).toFixed(2);
    const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
    const displaySize = file.size > 1024 * 1024 ? `${fileSizeMB} MB` : `${fileSizeKB} KB`;

    const formData = new FormData();
    formData.append('image', file);
    formData.append('_token', '{{ csrf_token() }}');

    // Show progress bar
    const progressContainer = document.getElementById('uploadProgress');
    const progressBar = document.getElementById('progressBar');
    const uploadPercentage = document.getElementById('uploadPercentage');
    const uploadedSize = document.getElementById('uploadedSize');
    const totalSize = document.getElementById('totalSize');
    
    progressContainer.classList.remove('hidden');
    totalSize.textContent = displaySize;
    
    // Reset progress
    progressBar.style.width = '0%';
    uploadPercentage.textContent = '0%';
    uploadedSize.textContent = '0 KB';

    // Simulated progress for better UX (starts immediately)
    let simulatedProgress = 0;
    let actualProgress = 0;
    let uploadComplete = false;
    
    const simulateProgress = setInterval(() => {
        if (!uploadComplete && simulatedProgress < 90) {
            // Slow down as we approach 90%
            const increment = simulatedProgress < 30 ? 5 : 
                            simulatedProgress < 60 ? 3 : 
                            simulatedProgress < 80 ? 2 : 1;
            simulatedProgress += increment;
            
            // Use the higher of simulated or actual progress
            const displayProgress = Math.max(simulatedProgress, actualProgress);
            progressBar.style.width = displayProgress + '%';
            uploadPercentage.textContent = displayProgress + '%';
            
            // Calculate uploaded size based on progress
            const uploadedBytes = (file.size * displayProgress) / 100;
            const uploadedKB = (uploadedBytes / 1024).toFixed(2);
            const uploadedMB = (uploadedBytes / (1024 * 1024)).toFixed(2);
            const displayUploaded = uploadedBytes > 1024 * 1024 ? `${uploadedMB} MB` : `${uploadedKB} KB`;
            uploadedSize.textContent = displayUploaded;
        }
    }, 200); // Update every 200ms for smooth animation

    try {
        const xhr = new XMLHttpRequest();

        // Track actual upload progress
        xhr.upload.addEventListener('progress', function(e) {
            if (e.lengthComputable) {
                actualProgress = Math.round((e.loaded / e.total) * 100);
                
                // If actual progress is higher than simulated, use actual
                if (actualProgress > simulatedProgress) {
                    simulatedProgress = actualProgress;
                    progressBar.style.width = actualProgress + '%';
                    uploadPercentage.textContent = actualProgress + '%';
                    
                    const uploadedKB = (e.loaded / 1024).toFixed(2);
                    const uploadedMB = (e.loaded / (1024 * 1024)).toFixed(2);
                    const displayUploaded = e.loaded > 1024 * 1024 ? `${uploadedMB} MB` : `${uploadedKB} KB`;
                    uploadedSize.textContent = displayUploaded;
                }
            }
        });

        // Handle completion
        xhr.addEventListener('load', function() {
            uploadComplete = true;
            clearInterval(simulateProgress);
            
            // Animate to 100%
            progressBar.style.width = '100%';
            uploadPercentage.textContent = '100%';
            uploadedSize.textContent = displaySize;
            
            if (xhr.status === 200) {
                const data = JSON.parse(xhr.responseText);
                
                if (data.success) {
                    // Store ImageKit data
                    document.getElementById('featured_image').value = data.url;
                    document.getElementById('imagekit_file_id').value = data.file_id;
                    document.getElementById('imagekit_url').value = data.url;
                    document.getElementById('imagekit_thumbnail_url').value = data.thumbnail_url || data.url;
                    document.getElementById('imagekit_file_path').value = data.file_path || '';
                    
                    // Hide progress bar
                    setTimeout(() => {
                        progressContainer.classList.add('hidden');
                    }, 500);
                    
                    document.getElementById('imagePreview').innerHTML = `
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-300 rounded-xl p-4 mb-3">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-sm font-semibold text-green-800">Upload Successful</span>
                                </div>
                                <span class="text-xs font-medium text-green-700">${displaySize}</span>
                            </div>
                        </div>
                        <div class="relative inline-block">
                            <img src="${data.url}" class="rounded-xl border-2 border-gray-300 shadow-md" style="max-width: 300px; max-height: 200px;">
                            <button type="button" onclick="removeImage()" class="absolute -top-2 -right-2 w-8 h-8 bg-red-500 text-white rounded-full hover:bg-red-600 transition-all shadow-lg">
                                <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    `;
                    showToast('success', 'Image uploaded successfully!');
                } else {
                    progressContainer.classList.add('hidden');
                    showToast('error', data.message || 'Failed to upload image');
                }
            } else {
                progressContainer.classList.add('hidden');
                showToast('error', 'Upload failed');
            }
        });

        // Handle errors
        xhr.addEventListener('error', function() {
            uploadComplete = true;
            clearInterval(simulateProgress);
            progressContainer.classList.add('hidden');
            showToast('error', 'Error uploading image');
        });
        
        // Handle abort
        xhr.addEventListener('abort', function() {
            uploadComplete = true;
            clearInterval(simulateProgress);
            progressContainer.classList.add('hidden');
            showToast('warning', 'Upload cancelled');
        });

        // Send request
        xhr.open('POST', '{{ route("admin.blogs.upload-image") }}');
        xhr.send(formData);

    } catch (error) {
        uploadComplete = true;
        if (typeof simulateProgress !== 'undefined') {
            clearInterval(simulateProgress);
        }
        progressContainer.classList.add('hidden');
        showToast('error', 'Error uploading image');
        }
});

// Remove image
function removeImage() {
    document.getElementById('featured_image').value = '';
    document.getElementById('imagekit_file_id').value = '';
    document.getElementById('imagekit_url').value = '';
    document.getElementById('imagekit_thumbnail_url').value = '';
    document.getElementById('imagekit_file_path').value = '';
    document.getElementById('imagePreview').innerHTML = '';
    document.getElementById('imageInput').value = '';
}

// Open add modal
function openAddModal() {
    document.getElementById('modalTitle').textContent = 'Add New Blog';
    document.getElementById('blogForm').reset();
    document.getElementById('blogId').value = '';
    document.getElementById('methodField').value = 'POST';
    document.getElementById('imagePreview').innerHTML = '';
    document.getElementById('is_active').checked = true;
    
    // Reset Quill editor
    contentQuill.setText('');
    document.getElementById('content').value = '';
    
    document.getElementById('blogModal').classList.remove('hidden');
}

// Fill dummy data for testing
function fillDummyData() {
    const titles = [
        'Top 10 Fashion Trends for 2024',
        'How to Style Your Winter Wardrobe',
        'The Ultimate Guide to Sustainable Fashion',
        'Best Accessories to Elevate Your Look'
    ];
    
    const excerpts = [
        'Discover the latest fashion trends taking the world by storm.',
        'Learn how to create stunning outfits with your winter collection.',
        'Everything about making eco-friendly fashion choices.',
        'Transform your outfit with these must-have accessories.'
    ];
    
    const content = `<h2>Introduction</h2><p>Fashion is constantly evolving. This guide will help you stay ahead.</p><h3>Key Points</h3><ul><li>Latest trends</li><li>Style tips</li><li>Wardrobe essentials</li></ul><p><strong>Pro Tip:</strong> Quality over quantity!</p>`;
    
    const idx = Math.floor(Math.random() * titles.length);
    
    document.getElementById('title').value = titles[idx];
    document.getElementById('slug').value = titles[idx].toLowerCase().replace(/[^\w\s-]/g, '').replace(/\s+/g, '-');
    document.getElementById('excerpt').value = excerpts[idx];
    contentQuill.root.innerHTML = content;
    document.getElementById('content').value = content;
    document.getElementById('author').value = 'Admin';
    document.getElementById('published_at').value = new Date().toISOString().split('T')[0];
    document.getElementById('is_featured').checked = true;
    document.getElementById('is_active').checked = true;
    
    showToast('success', 'Dummy data filled!');
}

// Edit blog (initial page load)
document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        const blog = blogsData.find(b => b.id == id);
        if (!blog) return;

        document.getElementById('modalTitle').textContent = 'Edit Blog';
        document.getElementById('blogId').value = blog.id;
        document.getElementById('methodField').value = 'PUT';
        document.getElementById('title').value = blog.title;
        document.getElementById('slug').value = blog.slug;
        document.getElementById('excerpt').value = blog.excerpt || '';
        
        // Set Quill content
        if (blog.content) {
            contentQuill.root.innerHTML = blog.content;
            document.getElementById('content').value = blog.content;
        } else {
            contentQuill.setText('');
            document.getElementById('content').value = '';
        }
        
        // Set all image fields
        document.getElementById('featured_image').value = blog.featured_image || blog.imagekit_url || '';
        document.getElementById('imagekit_file_id').value = blog.imagekit_file_id || '';
        document.getElementById('imagekit_url').value = blog.imagekit_url || '';
        document.getElementById('imagekit_thumbnail_url').value = blog.imagekit_thumbnail_url || '';
        document.getElementById('imagekit_file_path').value = blog.imagekit_file_path || '';
        
        document.getElementById('author').value = blog.author || '';
        document.getElementById('published_at').value = blog.published_at || '';
        document.getElementById('is_featured').checked = blog.is_featured;
        document.getElementById('is_active').checked = blog.is_active;

        // Show image preview - check multiple sources
        const imageUrl = blog.featured_image_url || blog.imagekit_url || blog.featured_image;
        if (imageUrl) {
            document.getElementById('imagePreview').innerHTML = `
                <div class="relative inline-block">
                    <img src="${imageUrl}" class="rounded-xl border-2 border-gray-300 shadow-md" style="max-width: 300px; max-height: 200px;">
                    <button type="button" onclick="removeImage()" class="absolute -top-2 -right-2 w-8 h-8 bg-red-500 text-white rounded-full hover:bg-red-600 transition-all">
                        <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            `;
        } else {
            document.getElementById('imagePreview').innerHTML = '';
        }

        // Load gallery images
        loadGalleryImages(blog);

        document.getElementById('blogModal').classList.remove('hidden');
    });
});

// Close modal
document.getElementById('closeModalBtn')?.addEventListener('click', closeModal);
document.getElementById('cancelBtn')?.addEventListener('click', closeModal);
document.getElementById('modalOverlay')?.addEventListener('click', closeModal);

function closeModal() {
    document.getElementById('blogModal').classList.add('hidden');
}

// Submit form
document.getElementById('blogForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();

    // Update content from Quill editor
    document.getElementById('content').value = contentQuill.root.innerHTML;

    const blogId = document.getElementById('blogId').value;
    const method = document.getElementById('methodField').value;
    const url = blogId 
        ? `{{ url('admin/blogs') }}/${blogId}`
        : '{{ route("admin.blogs.store") }}';

    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());
    
    // Handle checkboxes
    data.is_featured = document.getElementById('is_featured').checked ? 1 : 0;
    data.is_active = document.getElementById('is_active').checked ? 1 : 0;
    
    // Ensure ImageKit fields are included
    data.featured_image = document.getElementById('featured_image').value || '';
    data.imagekit_file_id = document.getElementById('imagekit_file_id').value || '';
    data.imagekit_url = document.getElementById('imagekit_url').value || '';
    data.imagekit_thumbnail_url = document.getElementById('imagekit_thumbnail_url').value || '';
    data.imagekit_file_path = document.getElementById('imagekit_file_path').value || '';
    
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const originalText = submitText.textContent;

    submitBtn.disabled = true;
    submitText.textContent = 'Saving...';

    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-HTTP-Method-Override': method
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (result.success) {
            // Save gallery images if any
            if (galleryImages.length > 0) {
                await saveGalleryImages(result.blog.id);
            }
            
            showToast('success', result.message);
            
            // Add/Update blog in table without refresh
            if (blogId) {
                // Update existing row
                updateBlogRow(result.blog);
            } else {
                // Add new row
                addBlogRow(result.blog);
            }
            
            // Close modal and reset
            closeModal();
            document.getElementById('blogForm').reset();
            contentQuill.setText('');
            document.getElementById('imagePreview').innerHTML = '';
            document.getElementById('galleryPreview').innerHTML = '';
            galleryImages = [];
        } else {
            showToast('error', result.message || 'Something went wrong');
        }
    } catch (error) {
        showToast('error', 'Error saving blog');
        } finally {
        submitBtn.disabled = false;
        submitText.textContent = originalText;
    }
});

// Add new blog row to table
function addBlogRow(blog) {
    const tbody = document.querySelector('#blogsTable tbody');
    const newRow = document.createElement('tr');
    newRow.className = 'group hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all duration-200';
    newRow.setAttribute('data-status', blog.is_active ? '1' : '0');
    newRow.setAttribute('data-id', blog.id);
    
    // Get image URL with fallback
    const imageUrl = blog.featured_image_url || blog.imagekit_url || blog.featured_image || '{{ asset("assets/images/blog/default.svg") }}';
    
    newRow.innerHTML = `
        <td class="px-4 py-4 text-center">
            <input type="checkbox" class="row-select w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500" value="${blog.id}">
        </td>
        <td class="px-4 py-4 text-center">
            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 border border-gray-300">#${blog.id}</span>
        </td>
        <td class="px-4 py-4 text-center">
            <img src="${imageUrl}" alt="${blog.title}" class="w-16 h-16 rounded-lg object-cover mx-auto border-2 border-gray-200" onerror="this.src='{{ asset("assets/images/blog/default.svg") }}'">
        </td>
        <td class="px-4 py-4">
            <p class="text-sm font-bold text-gray-900 group-hover:text-blue-700">${blog.title}</p>
            <p class="text-xs text-gray-500 mt-1">${blog.slug}</p>
        </td>
        <td class="px-4 py-4">
            <span class="text-sm text-gray-700">${blog.author || 'N/A'}</span>
        </td>
        <td class="px-4 py-4 text-center">
            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold bg-gradient-to-r from-purple-100 to-pink-100 text-purple-700 border border-purple-300">
                ${blog.views || 0}
            </span>
        </td>
        <td class="px-4 py-4 text-center">
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" class="sr-only peer status-toggle" data-id="${blog.id}" data-field="is_featured" ${blog.is_featured ? 'checked' : ''}>
                <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-yellow-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gradient-to-r peer-checked:from-yellow-400 peer-checked:to-orange-500"></div>
            </label>
        </td>
        <td class="px-4 py-4 text-center">
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" class="sr-only peer status-toggle" data-id="${blog.id}" data-field="is_active" ${blog.is_active ? 'checked' : ''}>
                <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gradient-to-r peer-checked:from-green-400 peer-checked:to-emerald-500"></div>
            </label>
        </td>
        <td class="px-4 py-4">
            <div class="flex items-center justify-center space-x-2">
                <button class="edit-btn inline-flex items-center justify-center w-9 h-9 text-blue-600 bg-blue-50 border-2 border-blue-200 rounded-lg hover:bg-blue-100 hover:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all"
                        title="Edit Blog" data-id="${blog.id}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </button>
                <button type="button" class="delete-btn inline-flex items-center justify-center w-9 h-9 text-red-600 bg-red-50 border-2 border-red-200 rounded-lg hover:bg-red-100 hover:border-red-400 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all" 
                        title="Delete Blog" data-id="${blog.id}" data-title="${blog.title}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </div>
        </td>
    `;
    
    // Add to beginning of table
    tbody.insertBefore(newRow, tbody.firstChild);
    
    // Add event listeners to new row
    attachRowEventListeners(newRow);
    
    // Update blogsData array
    blogsData.unshift(blog);
    
    // Hide empty state if visible
    const emptyState = document.getElementById('emptyState');
    if (emptyState) emptyState.style.display = 'none';
}

// Update existing blog row
function updateBlogRow(blog) {
    const row = document.querySelector(`tr[data-id="${blog.id}"]`);
    if (!row) return;
    
    // Update row data
    const imgElement = row.querySelector('img');
    if (imgElement) {
        // Get image URL with fallback
        const imageUrl = blog.featured_image_url || blog.imagekit_url || blog.featured_image || '{{ asset("assets/images/blog/default.svg") }}';
        imgElement.src = imageUrl;
        imgElement.alt = blog.title;
        imgElement.onerror = function() {
            this.src = '{{ asset("assets/images/blog/default.svg") }}';
        };
    }
    
    const titleElement = row.querySelector('.text-sm.font-bold');
    if (titleElement) titleElement.textContent = blog.title;
    
    const slugElement = row.querySelector('.text-xs.text-gray-500');
    if (slugElement) slugElement.textContent = blog.slug;
    
    const authorElement = row.querySelector('td:nth-child(5) span');
    if (authorElement) authorElement.textContent = blog.author || 'N/A';
    
    // Update toggle states
    const featuredToggle = row.querySelector('[data-field="is_featured"]');
    if (featuredToggle) featuredToggle.checked = blog.is_featured;
    
    const activeToggle = row.querySelector('[data-field="is_active"]');
    if (activeToggle) activeToggle.checked = blog.is_active;
    
    // Update blogsData array
    const index = blogsData.findIndex(b => b.id === blog.id);
    if (index !== -1) {
        blogsData[index] = blog;
    }
}

// Attach event listeners to row
function attachRowEventListeners(row) {
    // Edit button
    row.querySelector('.edit-btn')?.addEventListener('click', function() {
        const id = this.dataset.id;
        const blog = blogsData.find(b => b.id == id);
        if (blog) {
            document.getElementById('modalTitle').textContent = 'Edit Blog';
            document.getElementById('blogId').value = blog.id;
            document.getElementById('methodField').value = 'PUT';
            document.getElementById('title').value = blog.title;
            document.getElementById('slug').value = blog.slug;
            document.getElementById('excerpt').value = blog.excerpt || '';
            
            if (blog.content) {
                contentQuill.root.innerHTML = blog.content;
                document.getElementById('content').value = blog.content;
            } else {
                contentQuill.setText('');
                document.getElementById('content').value = '';
            }
            
            // Set all image fields
            document.getElementById('featured_image').value = blog.featured_image || blog.imagekit_url || '';
            document.getElementById('imagekit_file_id').value = blog.imagekit_file_id || '';
            document.getElementById('imagekit_url').value = blog.imagekit_url || '';
            document.getElementById('imagekit_thumbnail_url').value = blog.imagekit_thumbnail_url || '';
            document.getElementById('imagekit_file_path').value = blog.imagekit_file_path || '';
            
            document.getElementById('author').value = blog.author || '';
            document.getElementById('published_at').value = blog.published_at || '';
            document.getElementById('is_featured').checked = blog.is_featured;
            document.getElementById('is_active').checked = blog.is_active;

            // Show image preview - check multiple sources
            const imageUrl = blog.featured_image_url || blog.imagekit_url || blog.featured_image;
            if (imageUrl) {
                document.getElementById('imagePreview').innerHTML = `
                    <div class="relative inline-block">
                        <img src="${imageUrl}" class="rounded-xl border-2 border-gray-300 shadow-md" style="max-width: 300px; max-height: 200px;">
                        <button type="button" onclick="removeImage()" class="absolute -top-2 -right-2 w-8 h-8 bg-red-500 text-white rounded-full hover:bg-red-600 transition-all">
                            <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                `;
            } else {
                document.getElementById('imagePreview').innerHTML = '';
            }

            // Load gallery images
            loadGalleryImages(blog);

            document.getElementById('blogModal').classList.remove('hidden');
        }
    });
    
    // Delete button  
    row.querySelector('.delete-btn')?.addEventListener('click', function() {
        const id = this.dataset.id;
        const title = this.dataset.title;
        deleteBlogConfirm(id, title);
    });
    
    // Status toggles
    row.querySelectorAll('.status-toggle').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const id = this.dataset.id;
            const field = this.dataset.field;
            const value = this.checked ? 1 : 0;
            updateBlogStatus(id, field, value, this);
        });
    });
    
    // Row select checkbox
    row.querySelector('.row-select')?.addEventListener('change', function() {
        updateSelectedCount();
        updateBulkActionButton();
    });
}

// Update blog status (for toggles)
async function updateBlogStatus(id, field, value, toggleElement) {
    try {
        const response = await fetch(`{{ url('admin/blogs') }}/${id}/toggle`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ field, value })
        });

        const data = await response.json();
        
        if (data.success) {
            showToast('success', data.message);
            
            // Update blogsData
            const blog = blogsData.find(b => b.id == id);
            if (blog) {
                blog[field] = value;
            }
        } else {
            showToast('error', data.message);
            toggleElement.checked = !toggleElement.checked;
        }
    } catch (error) {
        showToast('error', 'Error updating status');
        toggleElement.checked = !toggleElement.checked;
        }
}


// Toggle status (for initial page load)
document.querySelectorAll('.status-toggle').forEach(toggle => {
    toggle.addEventListener('change', async function() {
        const id = this.dataset.id;
        const field = this.dataset.field;
        const value = this.checked ? 1 : 0;
        updateBlogStatus(id, field, value, this);
    });
});

// Delete blog without refresh
async function deleteBlogConfirm(id, title) {
    if (!confirm(`Are you sure you want to delete "${title}"?`)) return;
    
    try {
        const response = await fetch(`{{ url('admin/blogs') }}/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Remove row from table with animation
            const row = document.querySelector(`tr[data-id="${id}"]`);
            if (row) {
                row.style.transform = 'scale(0.8)';
                row.style.opacity = '0';
                row.style.transition = 'all 0.3s ease';
                setTimeout(() => row.remove(), 300);
            }
            
            // Remove from blogsData
            const index = blogsData.findIndex(b => b.id == id);
            if (index !== -1) blogsData.splice(index, 1);
            
            showToast('success', data.message);
        } else {
            showToast('error', data.message);
        }
    } catch (error) {
        showToast('error', 'Error deleting blog');
        }
}

// Delete blog (for initial page load)
document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        const title = this.dataset.title;
        deleteBlogConfirm(id, title);
    });
});

// Select all checkboxes
document.getElementById('selectAll')?.addEventListener('change', function() {
    document.querySelectorAll('.row-select').forEach(cb => {
        cb.checked = this.checked;
    });
    updateSelectedCount();
    updateBulkActionButton();
});

// Update selected count
document.querySelectorAll('.row-select').forEach(cb => {
    cb.addEventListener('change', function() {
        updateSelectedCount();
        updateBulkActionButton();
    });
});

function updateSelectedCount() {
    const count = document.querySelectorAll('.row-select:checked').length;
    document.getElementById('selectedCount').textContent = count;
}

function updateBulkActionButton() {
    const count = document.querySelectorAll('.row-select:checked').length;
    document.getElementById('applyBulkAction').disabled = count === 0;
}

// Bulk actions without refresh
document.getElementById('applyBulkAction')?.addEventListener('click', async function() {
    const action = document.getElementById('bulkAction').value;
    if (!action) {
        showToast('warning', 'Please select an action');
        return;
    }

    const selectedIds = Array.from(document.querySelectorAll('.row-select:checked'))
        .map(cb => cb.value);

    if (selectedIds.length === 0) {
        showToast('warning', 'Please select at least one blog');
        return;
    }

    if (action === 'delete' && !confirm(`Are you sure you want to delete ${selectedIds.length} blog(s)?`)) {
        return;
    }

    try {
        const response = await fetch('{{ route("admin.blogs.bulk-action") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ action, ids: selectedIds })
        });

        const data = await response.json();
        
        if (data.success) {
            showToast('success', data.message);
            
            // Update UI without refresh
            if (action === 'delete') {
                // Remove rows with animation
                selectedIds.forEach(id => {
                    const row = document.querySelector(`tr[data-id="${id}"]`);
                    if (row) {
                        row.style.transform = 'scale(0.8)';
                        row.style.opacity = '0';
                        row.style.transition = 'all 0.3s ease';
                        setTimeout(() => row.remove(), 300);
                    }
                    
                    // Remove from blogsData
                    const index = blogsData.findIndex(b => b.id == id);
                    if (index !== -1) blogsData.splice(index, 1);
                });
            } else if (action === 'activate' || action === 'deactivate') {
                // Update toggle states
                const newValue = action === 'activate' ? 1 : 0;
                selectedIds.forEach(id => {
                    const row = document.querySelector(`tr[data-id="${id}"]`);
                    if (row) {
                        const toggle = row.querySelector('[data-field="is_active"]');
                        if (toggle) toggle.checked = newValue;
                        
                        // Update blogsData
                        const blog = blogsData.find(b => b.id == id);
                        if (blog) blog.is_active = newValue;
                    }
                });
            }
            
            // Uncheck all checkboxes
            document.querySelectorAll('.row-select:checked').forEach(cb => cb.checked = false);
            document.getElementById('selectAll').checked = false;
            updateSelectedCount();
            updateBulkActionButton();
        } else {
            showToast('error', data.message);
        }
    } catch (error) {
        showToast('error', 'Error performing bulk action');
        }
});

// Search functionality
document.getElementById('globalSearch')?.addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    filteredData = blogsData.filter(blog => 
        blog.title.toLowerCase().includes(searchTerm) ||
        (blog.author && blog.author.toLowerCase().includes(searchTerm)) ||
        blog.slug.toLowerCase().includes(searchTerm)
    );
    currentPage = 1;
    renderTable();
});

// Status filter
document.getElementById('statusFilter')?.addEventListener('change', function() {
    const status = this.value;
    if (status === '') {
        filteredData = [...blogsData];
    } else {
        filteredData = blogsData.filter(blog => blog.is_active == status);
    }
    currentPage = 1;
    renderTable();
});

// Items per page
document.getElementById('itemsPerPage')?.addEventListener('change', function() {
    itemsPerPage = parseInt(this.value);
    currentPage = 1;
    renderTable();
});

// Reset filters
document.getElementById('resetFilters')?.addEventListener('click', function() {
    document.getElementById('globalSearch').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('itemsPerPage').value = '25';
    filteredData = [...blogsData];
    itemsPerPage = 25;
    currentPage = 1;
    renderTable();
});

// Refresh button
document.getElementById('refreshBtn')?.addEventListener('click', function() {
    location.reload();
});

// Render table (basic implementation)
function renderTable() {
    // This is a simplified version
    // In production, you'd want to implement full DataTables functionality
    }

// Toast notification
function showToast(type, message) {
    const bgColors = {
        success: 'bg-green-500',
        error: 'bg-red-500',
        warning: 'bg-yellow-500',
        info: 'bg-blue-500'
    };

    const toastHtml = `
        <div class="fixed top-4 right-4 z-50 animate-slideIn">
            <div class="${bgColors[type]} text-white px-6 py-4 rounded-xl shadow-2xl flex items-center space-x-3">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    ${type === 'success' ? '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>' : ''}
                    ${type === 'error' ? '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>' : ''}
                </svg>
                <span class="font-semibold">${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', toastHtml);
    
    setTimeout(() => {
        const toast = document.querySelector('.fixed.top-4.right-4');
        if (toast) toast.remove();
    }, 5000);
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    updateSelectedCount();
    updateBulkActionButton();
});

// ============ GALLERY IMAGES UPLOAD ============
let galleryImages = [];

document.getElementById('galleryInput')?.addEventListener('change', function(e) {
    const files = Array.from(e.target.files);
    if (files.length === 0) return;
    
    showToast('info', `Uploading ${files.length} image(s)...`);
    
    files.forEach((file, index) => {
        setTimeout(() => uploadGalleryImage(file, index), index * 100);
    });
    
    // Clear input
    this.value = '';
});

function uploadGalleryImage(file, index) {
    // Validate
    if (!file.type.match('image.*')) {
        showToast('error', 'Only images allowed');
        return;
    }
    
    if (file.size > 5 * 1024 * 1024) {
        showToast('error', `${file.name} exceeds 5MB`);
        return;
    }
    
    const fileSize = file.size > 1024 * 1024 
        ? `${(file.size / (1024 * 1024)).toFixed(2)} MB` 
        : `${(file.size / 1024).toFixed(2)} KB`;
    
    const previewId = `gallery-${Date.now()}-${index}`;
    const galleryPreview = document.getElementById('galleryPreview');
    
    // Create preview card
    const card = document.createElement('div');
    card.id = previewId;
    card.className = 'relative bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl p-3 border-2 border-purple-300 animate-slideIn';
    card.innerHTML = `
        <div class="aspect-square bg-white rounded-lg mb-2 flex items-center justify-center">
            <svg class="w-10 h-10 text-purple-400 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2.5 mb-2 overflow-hidden">
            <div class="progress-bar bg-gradient-to-r from-purple-500 to-pink-600 h-2.5 rounded-full transition-all duration-300" style="width: 0%"></div>
        </div>
        <div class="text-xs text-center font-bold progress-text text-purple-700">0%</div>
        <div class="text-xs text-gray-500 text-center mt-1 truncate">${file.name}</div>
    `;
    
    galleryPreview.appendChild(card);
    
    const progressBar = card.querySelector('.progress-bar');
    const progressText = card.querySelector('.progress-text');
    
    // Upload
    const formData = new FormData();
    formData.append('image', file);
    formData.append('_token', '{{ csrf_token() }}');
    
    const xhr = new XMLHttpRequest();
    
    // Simulated progress
    let simProgress = 0;
    const progressInterval = setInterval(() => {
        if (simProgress < 85) {
            simProgress += Math.random() * 15;
            const display = Math.min(simProgress, 85);
            progressBar.style.width = display + '%';
            progressText.textContent = Math.round(display) + '%';
        }
    }, 150);
    
    xhr.upload.addEventListener('progress', function(e) {
        if (e.lengthComputable) {
            const percent = Math.round((e.loaded / e.total) * 100);
            if (percent > simProgress) {
                simProgress = percent;
                progressBar.style.width = percent + '%';
                progressText.textContent = percent + '%';
            }
        }
    });
    
    xhr.addEventListener('load', function() {
        clearInterval(progressInterval);
        progressBar.style.width = '100%';
        progressText.textContent = '100%';
        
        if (xhr.status === 200) {
            const data = JSON.parse(xhr.responseText);
            
            if (data.success) {
                galleryImages.push({
                    url: data.url,
                    file_id: data.file_id,
                    file_path: data.file_path || '',
                    thumbnail_url: data.thumbnail_url || data.url
                });
                
                // Update card with image
                setTimeout(() => {
                    card.className = 'relative bg-white rounded-xl overflow-hidden border-2 border-green-300 shadow-md animate-slideIn';
                    card.innerHTML = `
                        <div class="relative group">
                            <img src="${data.url}" class="w-full aspect-square object-cover">
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition-all duration-200 flex items-center justify-center">
                                <button type="button" onclick="removeGalleryImg('${previewId}', ${galleryImages.length - 1})" 
                                        class="opacity-0 group-hover:opacity-100 w-8 h-8 bg-red-500 text-white rounded-full hover:bg-red-600 transition-all shadow-lg transform hover:scale-110">
                                    <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                            <div class="absolute top-2 right-2 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                                ✓
                            </div>
                        </div>
                        <div class="p-2 bg-gradient-to-r from-green-50 to-emerald-50">
                            <div class="text-xs text-gray-700 truncate font-medium">${file.name}</div>
                            <div class="text-xs text-green-600 font-bold">${fileSize}</div>
                        </div>
                    `;
                }, 300);
            } else {
                card.remove();
                showToast('error', data.message || 'Upload failed');
            }
        } else {
            card.remove();
            showToast('error', 'Upload failed');
        }
    });
    
    xhr.addEventListener('error', function() {
        clearInterval(progressInterval);
        card.remove();
        showToast('error', `Failed to upload ${file.name}`);
    });
    
    xhr.open('POST', '{{ route("admin.blogs.upload-image") }}');
    xhr.send(formData);
}

function removeGalleryImg(cardId, imgIndex) {
    const card = document.getElementById(cardId);
    if (card) {
        card.style.transform = 'scale(0)';
        card.style.opacity = '0';
        setTimeout(() => card.remove(), 300);
    }
    galleryImages.splice(imgIndex, 1);
    showToast('success', 'Image removed');
}

// Save gallery images to database
async function saveGalleryImages(blogId) {
    if (galleryImages.length === 0) return;
    
    try {
        const response = await fetch(`{{ url('admin/blogs') }}/${blogId}/save-gallery`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ images: galleryImages })
        });
        
        const data = await response.json();
        
        if (data.success) {
            showToast('success', 'Gallery images saved!');
        } else {
            showToast('error', 'Failed to save gallery images');
        }
    } catch (error) {
        showToast('error', 'Error saving gallery images');
    }
}

// Load gallery images for edit
function loadGalleryImages(blog) {
    const galleryPreview = document.getElementById('galleryPreview');
    galleryPreview.innerHTML = '';
    galleryImages = [];
    
    if (blog.images && blog.images.length > 0) {
        blog.images.forEach((image, index) => {
            const imageUrl = image.imagekit_url || image.image_url;
            const previewId = `gallery-existing-${image.id}`;
            
            // Add to galleryImages array
            galleryImages.push({
                id: image.id,
                url: imageUrl,
                file_id: image.imagekit_file_id,
                file_path: image.imagekit_file_path || '',
                thumbnail_url: image.imagekit_thumbnail_url || imageUrl
            });
            
            // Create preview card
            const card = document.createElement('div');
            card.id = previewId;
            card.className = 'relative bg-white rounded-xl overflow-hidden border-2 border-green-300 shadow-md animate-slideIn';
            card.innerHTML = `
                <div class="relative group">
                    <img src="${imageUrl}" class="w-full aspect-square object-cover">
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition-all duration-200 flex items-center justify-center">
                        <button type="button" onclick="removeGalleryImg('${previewId}', ${index})" 
                                class="opacity-0 group-hover:opacity-100 w-8 h-8 bg-red-500 text-white rounded-full hover:bg-red-600 transition-all shadow-lg transform hover:scale-110">
                            <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <div class="absolute top-2 right-2 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                        ✓
                    </div>
                </div>
                <div class="p-2 bg-gradient-to-r from-green-50 to-emerald-50">
                    <div class="text-xs text-gray-700 truncate font-medium">Gallery Image ${index + 1}</div>
                    <div class="text-xs text-green-600 font-bold">Existing</div>
                </div>
            `;
            
            galleryPreview.appendChild(card);
        });
        
        } else {
        }
}
</script>

<style>
@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideUp {
    from {
        transform: translateY(100px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.animate-slideIn {
    animation: slideIn 0.3s ease-out;
}

.animate-fadeIn {
    animation: fadeIn 0.3s ease-out;
}

.animate-slideUp {
    animation: slideUp 0.3s ease-out;
}
</style>
@endpush
