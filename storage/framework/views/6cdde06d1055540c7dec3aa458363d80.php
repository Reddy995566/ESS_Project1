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
</style>

<script>
    // Helper Functions
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
        const icon = type === 'success' ? 
            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>' :
            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>';

        toast.className = `bg-gradient-to-r ${bgColor} text-white px-6 py-4 rounded-xl shadow-2xl flex items-start space-x-3 transform transition-all duration-300 translate-x-full max-w-md`;
        toast.innerHTML = `
            <svg class="w-6 h-6 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                ${icon}
            </svg>
            <div class="flex-1 text-sm font-semibold">${message}</div>
            <button onclick="this.parentElement.remove()" class="ml-2 text-white hover:text-gray-200 flex-shrink-0">
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
        }, 5000);
    }

    // DOMContentLoaded Event
    document.addEventListener('DOMContentLoaded', function() {
        // --- Filter Logic Starts Here ---
        const globalSearch = document.getElementById('globalSearch');
        const statusFilter = document.getElementById('statusFilter');
        const featuredFilter = document.getElementById('featuredFilter');
        const parentFilter = document.getElementById('parentFilter');
        const navbarFilter = document.getElementById('navbarFilter');
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

            // Featured
            const featuredValue = featuredFilter?.value;
            if (featuredValue !== '') {
                params.set('featured', featuredValue);
            } else {
                params.delete('featured');
            }

            // Parent
            const parentValue = parentFilter?.value;
            if (parentValue !== '') {
                params.set('parent', parentValue);
            } else {
                params.delete('parent');
            }

            // Navbar
            const navbarValue = navbarFilter?.value;
            if (navbarValue !== '') {
                params.set('navbar', navbarValue);
            } else {
                params.delete('navbar');
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
        featuredFilter?.addEventListener('change', applyFilters);
        parentFilter?.addEventListener('change', applyFilters);
        navbarFilter?.addEventListener('change', applyFilters);
        homepageFilter?.addEventListener('change', applyFilters);
        itemsPerPage?.addEventListener('change', applyFilters);

        // Reset Logic
        resetFiltersBtn?.addEventListener('click', function() {
            if (globalSearch) globalSearch.value = '';
            if (statusFilter) statusFilter.value = '';
            if (featuredFilter) featuredFilter.value = '';
            if (parentFilter) parentFilter.value = '';
            if (navbarFilter) navbarFilter.value = '';
            if (homepageFilter) homepageFilter.value = '';
            if (itemsPerPage) itemsPerPage.value = '25';
            
            applyFilters();
        });
        
        // Initialize values from URL
        const urlParams = new URLSearchParams(window.location.search);
        if (globalSearch && urlParams.has('search')) globalSearch.value = urlParams.get('search');
        if (statusFilter && urlParams.has('status')) statusFilter.value = urlParams.get('status');
        if (featuredFilter && urlParams.has('featured')) featuredFilter.value = urlParams.get('featured');
        if (parentFilter && urlParams.has('parent')) parentFilter.value = urlParams.get('parent');
        if (navbarFilter && urlParams.has('navbar')) navbarFilter.value = urlParams.get('navbar');
        if (homepageFilter && urlParams.has('homepage')) homepageFilter.value = urlParams.get('homepage');
        if (itemsPerPage && urlParams.has('per_page')) itemsPerPage.value = urlParams.get('per_page');
        // Edit buttons - Open modal with data
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                openEditModal({
                    id: this.dataset.id,
                    name: this.dataset.name,
                    slug: this.dataset.slug,
                    description: this.dataset.description,
                    parentId: this.dataset.parentId,
                    sortOrder: this.dataset.sortOrder,
                    isActive: this.dataset.isActive === 'true',
                    isFeatured: this.dataset.isFeatured === 'true',
                    showInNavbar: this.dataset.showInNavbar === 'true',
                    showInHomepage: this.dataset.showInHomepage === 'true',
                    showInCircle: this.dataset.showInCircle === 'true',
                    circleType: this.dataset.circleType,
                    circleText: this.dataset.circleText,
                    circleLink: this.dataset.circleLink,
                    circleBgColor: this.dataset.circleBgColor,
                    circleTextColor: this.dataset.circleTextColor,
                    circleOrder: this.dataset.circleOrder,
                    metaTitle: this.dataset.metaTitle,
                    metaDescription: this.dataset.metaDescription,
                    metaKeywords: this.dataset.metaKeywords,
                    imageUrl: this.dataset.imageUrl
                });
            });
        });

        // Delete buttons with confirmation dialog
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const categoryId = this.dataset.id;
                const categoryName = this.dataset.name;
                const deleteBtn = this;

                showConfirmDialog(
                    'Delete Category?',
                    `Are you sure you want to delete "${categoryName}"? This action cannot be undone.`,
                    () => {
                        deleteBtn.disabled = true;
                        deleteBtn.innerHTML = '<svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';

                        fetch('/seller/categories/' + categoryId, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                showToastNotification('Category deleted successfully!', 'success');
                                const row = deleteBtn.closest('tr');
                                row.style.transition = 'all 0.3s ease';
                                row.style.opacity = '0';
                                row.style.transform = 'translateX(-20px)';
                                setTimeout(() => row.remove(), 300);
                            } else {
                                showToastNotification(data.message || 'Failed to delete category', 'error');
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

        // Toggle switches for all fields
        document.querySelectorAll('.feature-toggle').forEach(toggle => {
            toggle.addEventListener('change', function() {
                const categoryId = this.dataset.id;
                const field = this.dataset.field;
                const value = this.checked ? 1 : 0;

                fetch('/seller/categories/' + categoryId + '/toggle', {
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
        const refreshBtn = document.getElementById('refreshBtn');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', () => location.reload());
        }

        // Export button
        const exportBtn = document.getElementById('exportBtn');
        if (exportBtn) {
            exportBtn.addEventListener('click', function() {
                window.open('/seller/categories/export', '_blank');
                showToastNotification('Export started', 'success');
            });
        }

        // Select All checkbox
        const selectAllCheckbox = document.getElementById('selectAll');
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                document.querySelectorAll('.row-select').forEach(cb => cb.checked = this.checked);
                updateBulkButton();
            });
        }

        // Individual row checkboxes
        document.querySelectorAll('.row-select').forEach(cb => {
            cb.addEventListener('change', updateBulkButton);
        });

        // Bulk action button
        const applyBulkActionBtn = document.getElementById('applyBulkAction');
        if (applyBulkActionBtn) {
            applyBulkActionBtn.addEventListener('click', function() {
                const action = document.getElementById('bulkAction')?.value;
                const selected = Array.from(document.querySelectorAll('.row-select:checked')).map(cb => cb.value);

                if (!action || selected.length === 0) {
                    showToastNotification('Select action and items', 'error');
                    return;
                }

                if (action === 'delete') {
                    showConfirmDialog(
                        'Delete Multiple Categories?',
                        `Are you sure you want to delete ${selected.length} selected categor${selected.length > 1 ? 'ies' : 'y'}? This action cannot be undone.`,
                        () => {
                            performBulkAction(action, selected);
                        }
                    );
                    return;
                }

                performBulkAction(action, selected);
            });
        }

        function performBulkAction(action, selected) {
            fetch('/seller/categories/bulk-action', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ action, ids: selected })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showToastNotification(data.message, 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showToastNotification(data.message, 'error');
                }
            })
            .catch(() => showToastNotification('Error occurred', 'error'));
        }

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

        // AJAX Form Submission for Create/Update Category
        const categoryForm = document.getElementById('categoryForm');
        if (categoryForm) {
            categoryForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const submitBtn = document.getElementById('submitBtn');
                const submitBtnText = document.getElementById('submitBtnText');
                const loadingSpinner = document.getElementById('loadingSpinner');
                const formData = new FormData(this);
                const method = document.getElementById('methodField').value;
                const categoryId = document.getElementById('categoryId').value;
                
                // Disable submit button
                submitBtn.disabled = true;
                loadingSpinner.classList.remove('hidden');
                submitBtnText.textContent = categoryId ? 'Updating...' : 'Creating...';
                
                // Determine URL based on method
                let url = categoryId ? 
                    '<?php echo e(route("seller.categories.index")); ?>/' + categoryId : 
                    '<?php echo e(route("seller.categories.store")); ?>';
                
                // For PUT, we need to add _method field
                if (method === 'PUT') {
                    formData.append('_method', 'PUT');
                }
                
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToastNotification(data.message, 'success');
                        closeModal();
                        
                        // Reload page after 1 second to show updated data
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        // Handle validation errors
                        if (data.errors) {
                            let errorMessages = '';
                            Object.values(data.errors).forEach(errors => {
                                errors.forEach(error => {
                                    errorMessages += error + '<br>';
                                });
                            });
                            showToastNotification(errorMessages, 'error');
                        } else {
                            showToastNotification(data.message || 'An error occurred', 'error');
                        }
                        
                        // Re-enable submit button
                        submitBtn.disabled = false;
                        loadingSpinner.classList.add('hidden');
                        submitBtnText.textContent = categoryId ? 'Update Category' : 'Create Category';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToastNotification('Network error occurred. Please try again.', 'error');
                    
                    // Re-enable submit button
                    submitBtn.disabled = false;
                    loadingSpinner.classList.add('hidden');
                    submitBtnText.textContent = categoryId ? 'Update Category' : 'Create Category';
                });
            });
        }
    });
</script>
<?php /**PATH C:\xampp\htdocs\ESS_Project1\resources\views/seller/categories/scripts.blade.php ENDPATH**/ ?>