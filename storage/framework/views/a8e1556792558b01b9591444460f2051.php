<style>
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-slideIn {
        animation: slideIn 0.3s ease-out forwards;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(100px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .animate-fadeIn {
        animation: fadeIn 0.2s ease-out forwards;
    }

    .animate-slideUp {
        animation: slideUp 0.3s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    }

    /* Scrollbar Styling */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    }

    /* Pagination Styling */
    .paginate_button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 2.5rem;
        height: 2.5rem;
        padding: 0 0.75rem;
        margin: 0 0.125rem;
        border: 2px solid #e5e7eb;
        background: white;
        border-radius: 0.75rem;
        color: #6b7280;
        font-size: 0.875rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .paginate_button:hover:not(.disabled) {
        background: linear-gradient(135deg, #e0e7ff 0%, #ede9fe 100%);
        border-color: #6366f1;
        color: #4338ca;
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(99, 102, 241, 0.3);
    }

    .paginate_button.current {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        color: white;
        border-color: #4f46e5;
        box-shadow: 0 4px 6px -1px rgba(99, 102, 241, 0.5);
        font-weight: 700;
    }

    .paginate_button.disabled {
        opacity: 0.4;
        cursor: not-allowed;
        background: #f9fafb;
    }

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>

<script>
    // Core Functions
    function openModal() {
        document.getElementById('collectionModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('collectionModal').classList.add('hidden');
        const submitBtn = document.getElementById('submitBtn');
        const spinner = document.getElementById('loadingSpinner');
        if (submitBtn) {
            submitBtn.disabled = false;
        }
        if (spinner) {
            spinner.classList.add('hidden');
        }
    }

    function resetForm() {
        document.getElementById('modalTitle').textContent = 'Add New Collection';
        document.getElementById('submitBtnText').textContent = 'Create Collection';
        document.getElementById('collectionForm').action = '<?php echo e(route('seller.collections.store')); ?>';
        document.getElementById('methodField').value = 'POST';
        document.getElementById('collectionId').value = '';
        document.getElementById('collectionForm').reset();
        
        // Reset image preview
        document.getElementById('imagePreview').classList.add('hidden');
        document.getElementById('uploadIcon').classList.remove('hidden');
        document.getElementById('imageUrl').value = '';
        
        // Reset upload progress and success
        document.getElementById('uploadProgress')?.classList.add('hidden');
        document.getElementById('uploadSuccess')?.classList.add('hidden');
        
        // Reset slug tracking
        if (typeof resetSlugTracking === 'function') {
            resetSlugTracking();
        }
    }

    function openAddModal() {
        resetForm();
        openModal();
    }

    function openEditModal(data) {
        document.getElementById('modalTitle').textContent = 'Edit Collection';
        document.getElementById('submitBtnText').textContent = 'Update Collection';
        document.getElementById('collectionForm').action = '/admin/collections/' + data.id;
        document.getElementById('methodField').value = 'PATCH';
        document.getElementById('collectionId').value = data.id;

        document.getElementById('name').value = data.name;
        document.getElementById('slug').value = data.slug;
        document.getElementById('description').value = data.description || '';
        document.querySelector('input[name="sort_order"]').value = data.sortOrder;
        document.querySelector('input[name="is_active"]').checked = data.isActive;
        document.getElementById('showInHomepage').checked = data.showInHomepage || false;

        // Handle image preview
        if (data.image) {
            document.getElementById('imageUrl').value = data.image;
            document.getElementById('previewImg').src = data.image;
            document.getElementById('imagePreview').classList.remove('hidden');
            document.getElementById('uploadIcon').classList.add('hidden');
        }

        openModal();
    }

    function showConfirmDialog(title, message, onConfirm) {
        const overlay = document.createElement('div');
        overlay.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center animate-fadeIn';
        
        const dialog = document.createElement('div');
        dialog.className = 'bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-300 animate-slideUp';
        dialog.innerHTML = `
            <div class="p-6">
                <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 rounded-full bg-gradient-to-br from-red-100 to-pink-100">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 text-center mb-2">${title}</h3>
                <p class="text-gray-600 text-center mb-6">${message}</p>
                <div class="flex gap-3">
                    <button id="cancelBtn" class="flex-1 px-4 py-3 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition-all">
                        Cancel
                    </button>
                    <button id="confirmBtn" class="flex-1 px-4 py-3 bg-gradient-to-r from-red-500 to-pink-600 text-white font-semibold rounded-xl hover:from-red-600 hover:to-pink-700 transition-all shadow-lg hover:shadow-xl">
                        Delete
                    </button>
                </div>
            </div>
        `;
        
        overlay.appendChild(dialog);
        document.body.appendChild(overlay);
        
        const cancelBtn = dialog.querySelector('#cancelBtn');
        const confirmBtn = dialog.querySelector('#confirmBtn');
        
        const closeDialog = () => {
            overlay.style.opacity = '0';
            dialog.style.transform = 'scale(0.95)';
            setTimeout(() => overlay.remove(), 200);
        };
        
        cancelBtn.addEventListener('click', closeDialog);
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) closeDialog();
        });
        
        confirmBtn.addEventListener('click', () => {
            closeDialog();
            onConfirm();
        });
    }

    function showToastNotification(message, type = 'success') {
        let toastContainer = document.getElementById('toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.id = 'toast-container';
            toastContainer.className = 'fixed top-4 right-4 z-50 space-y-2';
            document.body.appendChild(toastContainer);
        }

        const toast = document.createElement('div');
        const bgColor = type === 'success' ? 'from-green-500 to-emerald-600' : 'from-red-500 to-pink-600';

        toast.className = `bg-gradient-to-r ${bgColor} text-white px-6 py-4 rounded-xl shadow-2xl flex items-center space-x-3 transform transition-all duration-300 translate-x-full`;
        toast.innerHTML = `
            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="font-semibold">${message}</span>
            <button onclick="this.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </button>
        `;

        toastContainer.appendChild(toast);
        setTimeout(() => toast.classList.remove('translate-x-full'), 100);
        setTimeout(() => {
            toast.classList.add('translate-x-full');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // Instant Image Upload Function via Backend
    async function uploadImageInstantly(file) {
        const formData = new FormData();
        formData.append('image', file);
        formData.append('folder', 'collections');

        try {
            const response = await fetch('/admin/collections/upload-image', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: formData
            });

            const result = await response.json();
            
            if (result.success && result.url) {
                return result;
            } else {
                throw new Error(result.message || 'Upload failed');
            }
        } catch (error) {
            throw error;
        }
    }

    // DOMContentLoaded Event
    document.addEventListener('DOMContentLoaded', function() {
        // --- Filter Logic Starts Here ---
        const globalSearch = document.getElementById('globalSearch');
        const statusFilter = document.getElementById('statusFilter');
        const homepageFilter = document.getElementById('homepageFilter');
        const itemsPerPage = document.getElementById('itemsPerPage');
        const resetFiltersBtn = document.getElementById('resetFilters');

        // Function to build URL and reload
        function applyFilters() {
            const params = new URLSearchParams(window.location.search);
            
            // Search
            const searchValue = globalSearch?.value.trim();
            if (searchValue) {
                params.set('search', searchValue);
            } else {
                params.delete('search');
            }

            // Status
            const statusValue = statusFilter?.value;
            if (statusValue !== '') {
                params.set('status', statusValue);
            } else {
                params.delete('status');
            }

            // Homepage
            const homepageValue = homepageFilter?.value;
            if (homepageValue !== '') {
                params.set('homepage', homepageValue);
            } else {
                params.delete('homepage');
            }

            // Per Page
            const perPageValue = itemsPerPage?.value;
            if (perPageValue) {
                params.set('per_page', perPageValue);
            }

            // Reset to page 1
            params.set('page', '1');

            window.location.href = `${window.location.pathname}?${params.toString()}`;
        }

        // Debounce for search
        let searchTimeout;
        globalSearch?.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(applyFilters, 500);
        });

        globalSearch?.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                clearTimeout(searchTimeout);
                applyFilters();
            }
        });

        // Event Listeners
        statusFilter?.addEventListener('change', applyFilters);
        homepageFilter?.addEventListener('change', applyFilters);
        itemsPerPage?.addEventListener('change', applyFilters);

        // Reset Logic
        resetFiltersBtn?.addEventListener('click', function() {
            if (globalSearch) globalSearch.value = '';
            if (statusFilter) statusFilter.value = '';
            if (homepageFilter) homepageFilter.value = '';
            if (itemsPerPage) itemsPerPage.value = '25';
            
            applyFilters();
        });

        // Initialize values from URL
        const urlParams = new URLSearchParams(window.location.search);
        if (globalSearch && urlParams.has('search')) globalSearch.value = urlParams.get('search');
        if (statusFilter && urlParams.has('status')) statusFilter.value = urlParams.get('status');
        if (homepageFilter && urlParams.has('homepage')) homepageFilter.value = urlParams.get('homepage');
        if (itemsPerPage && urlParams.has('per_page')) itemsPerPage.value = urlParams.get('per_page');
        
        // --- Filter Logic Ends Here ---
        // Modal Controls
        document.getElementById('closeModalBtn')?.addEventListener('click', closeModal);
        document.getElementById('cancelModalBtn')?.addEventListener('click', closeModal);
        document.getElementById('modalOverlay')?.addEventListener('click', closeModal);

        // Progress Bar Functions
        function updateUploadProgress(percent, status) {
            document.getElementById('uploadPercent').textContent = percent + '%';
            document.getElementById('uploadProgressBar').style.width = percent + '%';
            document.getElementById('uploadStatus').textContent = status;
        }

        function showUploadSuccess(data) {
            const successDiv = document.getElementById('uploadSuccess');
            successDiv.classList.remove('hidden');

            // Format file sizes
            const formatSize = (bytes) => {
                if (bytes < 1024) return bytes + ' B';
                if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(2) + ' KB';
                return (bytes / (1024 * 1024)).toFixed(2) + ' MB';
            };

            const originalSize = data.original_size || data.originalSize;
            const compressedSize = data.size;
            const saved = originalSize - compressedSize;
            const savedPercent = ((saved / originalSize) * 100).toFixed(1);

            document.getElementById('originalSize').textContent = formatSize(originalSize);
            document.getElementById('compressedSize').textContent = formatSize(compressedSize);
            document.getElementById('savedSize').textContent = formatSize(saved) + ' (' + savedPercent + '%)';
            document.getElementById('imageDimensions').textContent = data.width + 'x' + data.height + 'px';
            document.getElementById('uploadedPreview').src = data.thumbnail_url || data.url;
        }

        // Image Upload Handler - Instant Upload
        document.getElementById('imageUpload')?.addEventListener('change', async function(e) {
            const file = e.target.files[0];
            if (!file) return;

            // Validation
            const maxSize = 10 * 1024 * 1024; // 10MB
            if (file.size > maxSize) {
                showToastNotification('Image size must not exceed 10MB', 'error');
                this.value = '';
                return;
            }
            
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
            if (!validTypes.includes(file.type)) {
                showToastNotification('Only JPEG, PNG and WebP images are allowed', 'error');
                this.value = '';
                return;
            }

            // Hide old elements
            document.getElementById('imagePreview')?.classList.add('hidden');
            document.getElementById('uploadSuccess')?.classList.add('hidden');
            
            // Show progress
            const progressDiv = document.getElementById('uploadProgress');
            progressDiv.classList.remove('hidden');
            updateUploadProgress(0, 'Validating image...');

            // Show local preview immediately
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImg').src = e.target.result;
                document.getElementById('imagePreview').classList.remove('hidden');
                document.getElementById('uploadIcon').classList.add('hidden');
            };
            reader.readAsDataURL(file);

            // Upload to backend instantly
            try {
                updateUploadProgress(20, 'Compressing image...');
                
                const uploadResult = await uploadImageInstantly(file);
                
                updateUploadProgress(80, 'Uploading to ImageKit...');
                
                // Store the full upload result
                document.getElementById('imageUrl').value = uploadResult.url;
                document.getElementById('previewImg').src = uploadResult.thumbnail_url || uploadResult.url;
                
                updateUploadProgress(100, 'Upload complete!');
                
                // Hide progress, show success
                setTimeout(() => {
                    progressDiv.classList.add('hidden');
                    showUploadSuccess(uploadResult);
                }, 500);
                
                } catch (error) {
                progressDiv.classList.add('hidden');
                showToastNotification('Failed to upload image: ' + error.message, 'error');
                // Reset preview on error
                document.getElementById('imagePreview').classList.add('hidden');
                document.getElementById('uploadIcon').classList.remove('hidden');
                document.getElementById('imageUrl').value = '';
                this.value = '';
            }
        });

        // Auto-generate slug from name
        let isSlugManuallyEdited = false;
        
        document.getElementById('name')?.addEventListener('input', function() {
            const nameValue = this.value;
            const slugField = document.getElementById('slug');
            
            if (nameValue && slugField && !isSlugManuallyEdited) {
                // Generate slug: lowercase, replace spaces and special chars with hyphens
                const slug = nameValue
                    .toLowerCase()
                    .trim()
                    .replace(/[^a-z0-9\s-]/g, '') // Remove special characters
                    .replace(/\s+/g, '-') // Replace spaces with hyphens
                    .replace(/-+/g, '-') // Replace multiple hyphens with single
                    .replace(/^-|-$/g, ''); // Remove leading/trailing hyphens
                
                slugField.value = slug;
                
                // Add visual feedback
                slugField.style.backgroundColor = '#f0f9ff';
                slugField.style.borderColor = '#0ea5e9';
                setTimeout(() => {
                    slugField.style.backgroundColor = '';
                    slugField.style.borderColor = '';
                }, 300);
            }
        });
        
        // Track manual slug editing
        document.getElementById('slug')?.addEventListener('input', function() {
            isSlugManuallyEdited = this.value.length > 0;
        });
        
        // Reset slug tracking when form is reset
        function resetSlugTracking() {
            isSlugManuallyEdited = false;
        }

        // Fill Up button - Auto-fill with sample data
        document.getElementById('fillUpBtn')?.addEventListener('click', function() {
            const sampleCollections = [
                { name: 'Summer Collection 2024', description: 'Bright and breezy summer styles' },
                { name: 'Winter Essentials', description: 'Cozy and warm winter clothing' },
                { name: 'Urban Streetwear', description: 'Modern street fashion collection' },
                { name: 'Formal Business', description: 'Professional business attire' },
                { name: 'Casual Weekend', description: 'Comfortable weekend wear' }
            ];
            
            const randomCollection = sampleCollections[Math.floor(Math.random() * sampleCollections.length)];
            
            document.getElementById('name').value = randomCollection.name;
            document.getElementById('description').value = randomCollection.description;
            document.querySelector('input[name="sort_order"]').value = Math.floor(Math.random() * 100);
            document.querySelector('input[name="is_active"]').checked = true;
            
            showToastNotification('Form filled with sample data!', 'success');
        });

        // Edit buttons
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const data = {
                    id: this.dataset.id,
                    name: this.dataset.name,
                    slug: this.dataset.slug,
                    description: this.dataset.description,
                    image: this.dataset.image,
                    sortOrder: this.dataset.sortOrder,
                    isActive: this.dataset.isActive === 'true',
                    showInHomepage: this.dataset.showInHomepage === 'true'
                };
                openEditModal(data);
            });
        });

        // Delete buttons with AJAX
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const collectionId = this.dataset.id;
                const collectionName = this.dataset.name;
                const deleteBtn = this;

                showConfirmDialog(
                    'Delete Collection?',
                    `Are you sure you want to delete "${collectionName}"? This action cannot be undone.`,
                    () => {
                        deleteBtn.disabled = true;
                        deleteBtn.innerHTML = '<svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 714 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';

                        fetch('/admin/collections/' + collectionId, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                showToastNotification('Collection deleted successfully!', 'success');
                                const row = deleteBtn.closest('tr');
                                const collectionData = {
                                    is_active: row.dataset.status === '1',
                                    show_in_homepage: row.dataset.homepage === '1'
                                };

                                updateStatsOnDelete(collectionData);

                                row.style.transition = 'all 0.3s ease';
                                row.style.opacity = '0';
                                row.style.transform = 'translateX(-20px)';
                                setTimeout(() => row.remove(), 300);
                            } else {
                                showToastNotification(data.message || 'Failed to delete collection', 'error');
                                deleteBtn.disabled = false;
                                deleteBtn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>';
                            }
                        })
                        .catch(error => {
                            showToastNotification('Network error occurred', 'error');
                            deleteBtn.disabled = false;
                            deleteBtn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>';
                        });
                    }
                );
            });
        });

        // Toggle switches for status
        document.querySelectorAll('.status-toggle').forEach(toggle => {
            toggle.addEventListener('change', function() {
                const collectionId = this.dataset.id;
                const field = this.dataset.field;
                const value = this.checked ? 1 : 0;

                fetch('/admin/collections/' + collectionId + '/toggle', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ field, value })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showToastNotification(data.message, 'success');
                        updateStatsOnToggle(field, value);
                    } else {
                        this.checked = !this.checked;
                        showToastNotification(data.message, 'error');
                    }
                })
                .catch(() => {
                    this.checked = !this.checked;
                    showToastNotification('Network error occurred', 'error');
                });
            });
        });

        // Refresh button
        document.getElementById('refreshBtn')?.addEventListener('click', () => location.reload());

        // AJAX Form Submission
        document.getElementById('collectionForm')?.addEventListener('submit', function(e) {
            e.preventDefault();

            const submitBtn = document.getElementById('submitBtn');
            const spinner = document.getElementById('loadingSpinner');
            const formData = new FormData(this);
            const actionUrl = this.action;

            submitBtn.disabled = true;
            spinner?.classList.remove('hidden');

            fetch(actionUrl, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToastNotification(data.message, 'success');
                    closeModal();

                    const methodField = document.getElementById('methodField').value;
                    if (methodField === 'POST') {
                        updateStatsOnCreate(data.collection);
                        addCollectionRow(data.collection);
                    } else {
                        const oldRow = document.querySelector(`#collectionsTable tbody tr[data-id="${data.collection.id}"]`);
                        if (oldRow) {
                            const oldData = {
                                isActive: oldRow.dataset.status === '1',
                                showInHomepage: oldRow.dataset.homepage === '1'
                            };
                            updateStatsOnEdit(data.collection, oldData);
                            updateCollectionRow(data.collection);
                        }
                    }
                } else {
                    if (data.errors) {
                        let allErrors = [];
                        Object.keys(data.errors).forEach(key => {
                            data.errors[key].forEach(error => {
                                if (!allErrors.includes(error)) {
                                    allErrors.push(error);
                                }
                            });
                        });
                        showToastNotification(allErrors.join(', '), 'error');
                    } else {
                        showToastNotification(data.message || 'Failed to save collection', 'error');
                    }
                    submitBtn.disabled = false;
                    spinner?.classList.add('hidden');
                }
            })
            .catch(error => {
                showToastNotification('Network error occurred', 'error');
                submitBtn.disabled = false;
                spinner?.classList.add('hidden');
            });
        });

        // Export and Bulk Actions (simplified for space)
        document.getElementById('exportBtn')?.addEventListener('click', () => {
            window.open('/admin/collections/export', '_blank');
            showToastNotification('Export started', 'success');
        });

        // Select All and Bulk Actions
        document.getElementById('selectAll')?.addEventListener('change', function() {
            document.querySelectorAll('.row-select').forEach(cb => cb.checked = this.checked);
            updateBulkButton();
        });

        document.querySelectorAll('.row-select').forEach(cb => {
            cb.addEventListener('change', updateBulkButton);
        });

        function updateBulkButton() {
            const selected = document.querySelectorAll('.row-select:checked').length;
            const btn = document.getElementById('applyBulkAction');
            const count = document.getElementById('selectedCount');

            if (btn) {
                btn.disabled = selected === 0;
                btn.textContent = selected > 0 ? `Apply (${selected})` : 'Apply';
            }

            if (count) count.textContent = selected;
        }
    });

    // Helper Functions for Dynamic Stats Updates
    function updateStatsOnCreate(collection) {
        const totalEl = document.querySelector('.bg-gradient-to-br.from-indigo-500 .text-4xl');
        if (totalEl) {
            totalEl.textContent = parseInt(totalEl.textContent) + 1;
        }

        if (collection.is_active) {
            const activeEl = document.querySelector('.bg-gradient-to-br.from-green-500 .text-4xl');
            if (activeEl) {
                activeEl.textContent = parseInt(activeEl.textContent) + 1;
            }
        }
        
        if (collection.show_in_homepage) {
            const homepageEl = document.querySelector('.bg-gradient-to-br.from-indigo-500 .text-4xl');
            if (homepageEl) {
                homepageEl.textContent = parseInt(homepageEl.textContent) + 1;
            }
        }
    }

    function updateStatsOnEdit(newCollection, oldData) {
        if (newCollection.is_active !== oldData.isActive) {
            const activeEl = document.querySelector('.bg-gradient-to-br.from-green-500 .text-4xl');
            if (activeEl) {
                let count = parseInt(activeEl.textContent);
                activeEl.textContent = newCollection.is_active ? count + 1 : count - 1;
            }
        }
        
        if (newCollection.show_in_homepage !== oldData.showInHomepage) {
            const homepageEl = document.querySelector('.bg-gradient-to-br.from-indigo-500 .text-4xl');
            if (homepageEl) {
                let count = parseInt(homepageEl.textContent);
                homepageEl.textContent = newCollection.show_in_homepage ? count + 1 : count - 1;
            }
        }
    }

    function updateStatsOnToggle(field, value) {
        if (field === 'is_active') {
            const activeEl = document.querySelector('.bg-gradient-to-br.from-green-500 .text-4xl');
            if (activeEl) {
                let count = parseInt(activeEl.textContent);
                activeEl.textContent = value ? count + 1 : count - 1;
            }
        } else if (field === 'show_in_homepage') {
            const homepageEl = document.querySelector('.bg-gradient-to-br.from-indigo-500 .text-4xl');
            if (homepageEl) {
                let count = parseInt(homepageEl.textContent);
                homepageEl.textContent = value ? count + 1 : count - 1;
            }
        }
    }

    function updateStatsOnDelete(collection) {
        const totalEl = document.querySelector('.bg-gradient-to-br.from-indigo-500 .text-4xl');
        if (totalEl) {
            totalEl.textContent = Math.max(0, parseInt(totalEl.textContent) - 1);
        }

        if (collection.is_active) {
            const activeEl = document.querySelector('.bg-gradient-to-br.from-green-500 .text-4xl');
            if (activeEl) {
                activeEl.textContent = Math.max(0, parseInt(activeEl.textContent) - 1);
            }
        }
        
        if (collection.show_in_homepage) {
            const homepageEl = document.querySelector('.bg-gradient-to-br.from-indigo-500 .text-4xl');
            if (homepageEl) {
                homepageEl.textContent = Math.max(0, parseInt(homepageEl.textContent) - 1);
            }
        }
    }

    function addCollectionRow(collection) {
        const tbody = document.querySelector('#collectionsTable tbody');
        if (!tbody) return;

        const emptyRow = tbody.querySelector('#emptyState');
        if (emptyRow) emptyRow.remove();

        const row = createCollectionRow(collection);
        tbody.insertBefore(row, tbody.firstChild);

        row.style.opacity = '0';
        setTimeout(() => {
            row.style.transition = 'all 0.3s ease';
            row.style.opacity = '1';
        }, 10);
    }

    function updateCollectionRow(collection) {
        const row = document.querySelector(`#collectionsTable tbody tr[data-id="${collection.id}"]`);
        if (!row) return;

        const newRow = createCollectionRow(collection);
        row.replaceWith(newRow);

        newRow.style.backgroundColor = '#e0e7ff';
        setTimeout(() => {
            newRow.style.transition = 'background-color 1s ease';
            newRow.style.backgroundColor = '';
        }, 100);
    }

    function createCollectionRow(collection) {
        const row = document.createElement('tr');
        row.className = 'group hover:bg-gradient-to-r hover:from-indigo-50 hover:to-indigo-50 transition-all duration-200';
        row.dataset.id = collection.id;
        row.dataset.status = collection.is_active ? '1' : '0';
        row.dataset.homepage = collection.show_in_homepage ? '1' : '0';

        const escapeHtml = (text) => {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        };

        const imageHtml = collection.image 
            ? `<img src="${escapeHtml(collection.image)}" alt="${escapeHtml(collection.name)}" class="w-12 h-12 rounded-lg object-cover shadow-md border-2 border-gray-300 group-hover:border-indigo-400 transition-all">`
            : `<div class="w-12 h-12 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center border-2 border-gray-300 group-hover:border-indigo-400 transition-all">
                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
               </div>`;

        row.innerHTML = `
            <td class="px-4 py-4 text-center">
                <input type="checkbox" class="row-select w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-2 focus:ring-indigo-500" value="${collection.id}">
            </td>
            <td class="px-4 py-4 text-center">
                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 border border-gray-300">#${collection.id}</span>
            </td>
            <td class="px-4 py-4 text-center">
                <div class="flex justify-center">${imageHtml}</div>
            </td>
            <td class="px-4 py-4">
                <div>
                    <p class="text-sm font-bold text-gray-900 group-hover:text-indigo-700">${escapeHtml(collection.name)}</p>
                    <p class="text-xs text-gray-500 mt-1">${escapeHtml(collection.slug)}</p>
                </div>
            </td>
            <td class="px-4 py-4">
                <p class="text-sm text-gray-700 line-clamp-2">${escapeHtml(collection.description) || 'No description'}</p>
            </td>
            <td class="px-4 py-4 text-center">
                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-gradient-to-r from-indigo-100 to-indigo-100 text-indigo-700 border border-indigo-300">${collection.sort_order}</span>
            </td>
            <td class="px-4 py-4 text-center">
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" class="sr-only peer status-toggle" data-id="${collection.id}" data-field="is_active" ${collection.is_active ? 'checked' : ''}>
                    <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gradient-to-r peer-checked:from-green-400 peer-checked:to-emerald-500"></div>
                </label>
            </td>
            <td class="px-4 py-4 text-center">
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" class="sr-only peer status-toggle" data-id="${collection.id}" data-field="show_in_homepage" ${collection.show_in_homepage ? 'checked' : ''}>
                    <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gradient-to-r peer-checked:from-indigo-400 peer-checked:to-pink-500"></div>
                </label>
            </td>
            <td class="px-4 py-4">
                <div class="flex items-center justify-center space-x-2">
                    <button class="edit-btn inline-flex items-center justify-center w-9 h-9 text-indigo-600 bg-indigo-50 border-2 border-indigo-200 rounded-lg hover:bg-indigo-100 hover:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all"
                        title="Edit Collection" data-id="${collection.id}" data-name="${escapeHtml(collection.name)}" data-slug="${escapeHtml(collection.slug)}"
                        data-description="${escapeHtml(collection.description)}" data-image="${escapeHtml(collection.image)}"
                        data-sort-order="${collection.sort_order}" data-is-active="${collection.is_active ? 'true' : 'false'}" data-show-in-homepage="${collection.show_in_homepage ? 'true' : 'false'}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </button>
                    <button type="button" class="delete-btn inline-flex items-center justify-center w-9 h-9 text-red-600 bg-red-50 border-2 border-red-200 rounded-lg hover:bg-red-100 hover:border-red-400 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all" 
                        title="Delete Collection" data-id="${collection.id}" data-name="${escapeHtml(collection.name)}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            </td>
        `;

        attachRowEventListeners(row);
        return row;
    }

    function attachRowEventListeners(row) {
        const editBtn = row.querySelector('.edit-btn');
        const deleteBtn = row.querySelector('.delete-btn');
        const statusToggle = row.querySelector('.status-toggle');
        const rowSelect = row.querySelector('.row-select');

        if (editBtn) {
            editBtn.addEventListener('click', function() {
                const data = {
                    id: this.dataset.id, name: this.dataset.name, slug: this.dataset.slug,
                    description: this.dataset.description, image: this.dataset.image,
                    sortOrder: this.dataset.sortOrder, isActive: this.dataset.isActive === 'true',
                    showInHomepage: this.dataset.showInHomepage === 'true'
                };
                openEditModal(data);
            });
        }

        if (deleteBtn) {
            deleteBtn.addEventListener('click', function() {
                const collectionId = this.dataset.id;
                const collectionName = this.dataset.name;
                const deleteBtnElement = this;
                
                showConfirmDialog(
                    'Delete Collection?',
                    `Are you sure you want to delete "${collectionName}"? This action cannot be undone.`,
                    () => {
                        deleteBtnElement.disabled = true;
                        deleteBtnElement.innerHTML = '<svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 714 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
                        
                        fetch('/admin/collections/' + collectionId, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                showToastNotification('Collection deleted successfully!', 'success');
                                const row = deleteBtnElement.closest('tr');
                                const collectionData = {
                                    is_active: row.dataset.status === '1',
                                    show_in_homepage: row.dataset.homepage === '1'
                                };
                                
                                updateStatsOnDelete(collectionData);
                                
                                row.style.transition = 'all 0.3s ease';
                                row.style.opacity = '0';
                                row.style.transform = 'translateX(-20px)';
                                setTimeout(() => row.remove(), 300);
                            } else {
                                showToastNotification(data.message || 'Failed to delete collection', 'error');
                                deleteBtnElement.disabled = false;
                                deleteBtnElement.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>';
                            }
                        })
                        .catch(error => {
                            showToastNotification('Network error occurred', 'error');
                            deleteBtnElement.disabled = false;
                            deleteBtnElement.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>';
                        });
                    }
                );
            });
        }

        const statusToggles = row.querySelectorAll('.status-toggle');
        statusToggles.forEach(toggle => {
            toggle.addEventListener('change', function() {
                const collectionId = this.dataset.id;
                const field = this.dataset.field;
                const value = this.checked ? 1 : 0;
                
                fetch('/admin/collections/' + collectionId + '/toggle', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ field, value })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showToastNotification(data.message, 'success');
                        updateStatsOnToggle(field, value);
                        const row = this.closest('tr');
                        if (field === 'is_active') {
                            row.dataset.status = value ? '1' : '0';
                        } else if (field === 'show_in_homepage') {
                            row.dataset.homepage = value ? '1' : '0';
                        }
                    } else {
                        this.checked = !this.checked;
                        showToastNotification(data.message, 'error');
                    }
                })
                .catch(() => {
                    this.checked = !this.checked;
                    showToastNotification('Network error occurred', 'error');
                });
            });
        });

        if (rowSelect) {
            rowSelect.addEventListener('change', function() {
                const selected = document.querySelectorAll('.row-select:checked').length;
                document.getElementById('selectedCount').textContent = selected;
            });
        }
    }
</script><?php /**PATH C:\xampp\htdocs\ESS_Project1\resources\views/seller/collections/scripts.blade.php ENDPATH**/ ?>