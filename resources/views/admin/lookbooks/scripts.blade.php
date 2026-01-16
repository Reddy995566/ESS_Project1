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
        background: linear-gradient(135deg, #3b82f6 0%, #6366f1 100%);
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, #2563eb 0%, #4f46e5 100%);
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
        font-Lookbook: 0.875rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .paginate_button:hover:not(.disabled) {
        background: linear-gradient(135deg, #dbeafe 0%, #e0e7ff 100%);
        border-color: #3b82f6;
        color: #1e40af;
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.3);
    }

    .paginate_button.current {
        background: linear-gradient(135deg, #3b82f6 0%, #6366f1 100%);
        color: white;
        border-color: #2563eb;
        box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.5);
        font-weight: 700;
    }

    .paginate_button.disabled {
        opacity: 0.4;
        cursor: not-allowed;
        background: #f9fafb;
    }
</style>

<script>
    // Core Functions
    function openModal() {
        document.getElementById('lookbookModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('lookbookModal').classList.add('hidden');
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
        document.getElementById('modalTitle').textContent = 'Add New Lookbook';
        document.getElementById('submitBtnText').textContent = 'Create Lookbook';
        document.getElementById('lookbookForm').action = '{{ route('admin.lookbooks.store') }}';
        document.getElementById('methodField').value = 'POST';
        document.getElementById('lookbookId').value = '';
        document.getElementById('lookbookForm').reset();
    }

    function openAddModal() {
        resetForm();
        openModal();
    }

    function openEditModal(data) {
        document.getElementById('modalTitle').textContent = 'Edit Lookbook';
        document.getElementById('submitBtnText').textContent = 'Update Lookbook';
        document.getElementById('lookbookForm').action = '/admin/lookbooks/' + data.id;
        document.getElementById('methodField').value = 'PATCH';
        document.getElementById('lookbookId').value = data.id;

        document.getElementById('title').value = data.title;
        document.getElementById('subtitle').value = data.subtitle || '';
        document.getElementById('link').value = data.link || '';
        document.getElementById('position').value = data.position || 'left';
        document.querySelector('input[name="sort_order"]').value = data.sortOrder;
        document.querySelector('input[name="is_active"]').checked = data.isActive;

        openModal();
    }

    function showConfirmDialog(title, message, onConfirm) {
        // Create overlay
        const overlay = document.createElement('div');
        overlay.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center animate-fadeIn';
        
        // Create dialog
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
        
        // Handle buttons
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

    // DOMContentLoaded Event
    document.addEventListener('DOMContentLoaded', function() {
        // Modal Controls
        document.getElementById('closeModalBtn')?.addEventListener('click', closeModal);
        document.getElementById('cancelModalBtn')?.addEventListener('click', closeModal);
        document.getElementById('modalOverlay')?.addEventListener('click', closeModal);

        // Fill Up button - Auto-fill with sample data
        document.getElementById('fillUpBtn')?.addEventListener('click', function() {
            const sampleLookbooks = [
                { title: 'LOOKBOOK 2021', subtitle: 'MAKE LOVE THIS LOOK', link: 'https://example.com/lookbook-2021' },
                { title: 'Summer Collection', subtitle: 'Hot Trends This Season', link: 'https://example.com/summer' },
                { title: 'Winter Fashion', subtitle: 'Stay Warm & Stylish', link: 'https://example.com/winter' },
                { title: 'Spring Vibes', subtitle: 'Fresh New Looks', link: 'https://example.com/spring' },
                { title: 'Autumn Style', subtitle: 'Cozy & Chic', link: 'https://example.com/autumn' },
                { title: 'Holiday Special', subtitle: 'Festive Fashion', link: 'https://example.com/holiday' },
                { title: 'Street Style', subtitle: 'Urban Fashion Trends', link: 'https://example.com/street' },
                { title: 'Luxury Collection', subtitle: 'Premium Designer Wear', link: 'https://example.com/luxury' }
            ];
            
            const randomLookbook = sampleLookbooks[Math.floor(Math.random() * sampleLookbooks.length)];
            
            document.getElementById('title').value = randomlookbook.title;
            document.getElementById('subtitle').value = randomlookbook.subtitle;
            document.getElementById('link').value = randomlookbook.link;
            document.getElementById('position').value = Math.random() > 0.5 ? 'left' : 'right';
            document.querySelector('input[name="sort_order"]').value = Math.floor(Math.random() * 100);
            document.querySelector('input[name="is_active"]').checked = true;
            
            showToastNotification('Form filled with sample data!', 'success');
        });

        // Image Upload Handler
        document.getElementById('imageFile')?.addEventListener('change', async function(e) {
            const file = e.target.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('image', file);

            const progressBar = document.getElementById('progressBar');
            const uploadProgress = document.getElementById('uploadProgress');
            const imagePreview = document.getElementById('imagePreview');
            const previewImg = document.getElementById('previewImg');

            uploadProgress.classList.remove('hidden');
            progressBar.style.width = '30%';

            try {
                const response = await fetch('{{ route("admin.lookbooks.uploadImage") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                });

                progressBar.style.width = '70%';
                const data = await response.json();

                if (data.success) {
                    document.getElementById('imageUrl').value = data.url;
                    previewImg.src = data.url;
                    imagePreview.classList.remove('hidden');
                    progressBar.style.width = '100%';
                    
                    setTimeout(() => {
                        uploadProgress.classList.add('hidden');
                        progressBar.style.width = '0%';
                    }, 1000);

                    showToastNotification('Image uploaded successfully!', 'success');
                } else {
                    throw new Error(data.message || 'Upload failed');
                }
            } catch (error) {
                uploadProgress.classList.add('hidden');
                progressBar.style.width = '0%';
                showToastNotification(error.message || 'Failed to upload image', 'error');
                e.target.value = '';
            }
        });

        // Edit buttons
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const data = {
                    id: this.dataset.id,
                    title: this.dataset.title,
                    subtitle: this.dataset.subtitle,
                    link: this.dataset.link,
                    position: this.dataset.position,
                    sortOrder: this.dataset.sortOrder,
                    isActive: this.dataset.isActive === 'true'
                };
                openEditModal(data);
            });
        });

        // Delete buttons with AJAX
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const lookbookId = this.dataset.id;
                const LookbookName = this.dataset.name;
                const deleteBtn = this;

                showConfirmDialog(
                    'Delete Lookbook?',
                    `Are you sure you want to delete "${LookbookName}"? This action cannot be undone.`,
                    () => {
                        deleteBtn.disabled = true;
                        deleteBtn.innerHTML = '<svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';

                        fetch('/admin/lookbooks/' + lookbookId, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showToastNotification('Lookbook deleted successfully!', 'success');
                        const row = deleteBtn.closest('tr');
                        const LookbookData = {
                            is_active: row.dataset.status === '1'
                        };

                        updateStatsOnDelete(LookbookData);

                        row.style.transition = 'all 0.3s ease';
                        row.style.opacity = '0';
                        row.style.transform = 'translateX(-20px)';
                        setTimeout(() => row.remove(), 300);
                    } else {
                        showToastNotification(data.message || 'Failed to delete Lookbook', 'error');
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
                const lookbookId = this.dataset.id;
                const field = this.dataset.field;
                const value = this.checked ? 1 : 0;

                fetch('/admin/lookbooks/' + lookbookId + '/toggle', {
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
        document.getElementById('lookbookForm')?.addEventListener('submit', function(e) {
            e.preventDefault();

            // Check if image is uploaded (for create mode)
            const methodField = document.getElementById('methodField').value;
            const imageUrl = document.getElementById('imageUrl').value;
            
            if (methodField === 'POST' && !imageUrl) {
                showToastNotification('Please upload an image first!', 'error');
                return;
            }

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
                        updateStatsOnCreate(data.lookbook);
                        addLookbookRow(data.lookbook);
                    } else {
                        const oldRow = document.querySelector(`#lookbooksTable tbody tr[data-id="${data.lookbook.id}"]`);
                        if (oldRow) {
                            const oldData = {
                                isActive: oldRow.dataset.status === '1'
                            };
                            updateStatsOnEdit(data.lookbook, oldData);
                            updateLookbookRow(data.lookbook);
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
                        showToastNotification(data.message || 'Failed to save Lookbook', 'error');
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

        // Export button
        document.getElementById('exportBtn')?.addEventListener('click', function() {
            window.open('/admin/lookbooks/export', '_blank');
            showToastNotification('Export started', 'success');
        });

        // Bulk actions
        document.getElementById('selectAll')?.addEventListener('change', function() {
            document.querySelectorAll('.row-select').forEach(cb => cb.checked = this.checked);
            updateBulkButton();
        });

        document.querySelectorAll('.row-select').forEach(cb => {
            cb.addEventListener('change', updateBulkButton);
        });

        document.getElementById('applyBulkAction')?.addEventListener('click', function() {
            const action = document.getElementById('bulkAction')?.value;
            const selected = Array.from(document.querySelectorAll('.row-select:checked')).map(cb => cb.value);

            if (!action || selected.length === 0) {
                showToastNotification('Select action and items', 'error');
                return;
            }

            if (action === 'delete') {
                showConfirmDialog(
                    'Delete Multiple Lookbooks?',
                    `Are you sure you want to delete ${selected.length} selected Lookbook${selected.length > 1 ? 's' : ''}? This action cannot be undone.`,
                    () => {
                        performBulkAction(action, selected);
                    }
                );
                return;
            }

            performBulkAction(action, selected);
        });

        function performBulkAction(action, selected) {

                fetch('/admin/lookbooks/bulk-action', {
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
    });

    // Helper Functions for Dynamic Stats Updates
    function updateStatsOnCreate(Lookbook) {
        // Total Lookbooks
        const totalEl = document.querySelector('.bg-gradient-to-br.from-blue-500 .text-4xl');
        if (totalEl) {
            totalEl.textContent = parseInt(totalEl.textContent) + 1;
        }

        // Active Lookbooks
        if (lookbook.is_active) {
            const activeEl = document.querySelector('.bg-gradient-to-br.from-green-500 .text-4xl');
            if (activeEl) {
                activeEl.textContent = parseInt(activeEl.textContent) + 1;
            }
        }
    }

    function updateStatsOnEdit(newLookbook, oldData) {
        // Active status changed?
        if (newlookbook.is_active !== oldData.isActive) {
            const activeEl = document.querySelector('.bg-gradient-to-br.from-green-500 .text-4xl');
            if (activeEl) {
                let count = parseInt(activeEl.textContent);
                activeEl.textContent = newlookbook.is_active ? count + 1 : count - 1;
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
        }
    }

    function updateStatsOnDelete(Lookbook) {
        // Total Lookbooks
        const totalEl = document.querySelector('.bg-gradient-to-br.from-blue-500 .text-4xl');
        if (totalEl) {
            totalEl.textContent = Math.max(0, parseInt(totalEl.textContent) - 1);
        }

        // Active Lookbooks
        if (lookbook.is_active) {
            const activeEl = document.querySelector('.bg-gradient-to-br.from-green-500 .text-4xl');
            if (activeEl) {
                activeEl.textContent = Math.max(0, parseInt(activeEl.textContent) - 1);
            }
        }
    }

    function addLookbookRow(Lookbook) {
        const tbody = document.querySelector('#lookbooksTable tbody');
        if (!tbody) return;

        // Remove empty state if exists
        const emptyRow = tbody.querySelector('#emptyState');
        if (emptyRow) emptyRow.remove();

        const row = createLookbookRow(Lookbook);
        tbody.insertBefore(row, tbody.firstChild);

        // Add fade-in animation
        row.style.opacity = '0';
        setTimeout(() => {
            row.style.transition = 'all 0.3s ease';
            row.style.opacity = '1';
        }, 10);
    }

    function updateLookbookRow(Lookbook) {
        const row = document.querySelector(`#lookbooksTable tbody tr[data-id="${lookbook.id}"]`);
        if (!row) return;

        const newRow = createLookbookRow(Lookbook);
        row.replaceWith(newRow);

        // Add highlight animation
        newRow.style.backgroundColor = '#dbeafe';
        setTimeout(() => {
            newRow.style.transition = 'background-color 1s ease';
            newRow.style.backgroundColor = '';
        }, 100);
    }

    function createLookbookRow(lookbook) {
        const row = document.createElement('tr');
        row.className = 'group hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all duration-200';
        row.dataset.id = lookbook.id;
        row.dataset.status = lookbook.is_active ? '1' : '0';

        const escapeHtml = (text) => {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        };

        row.innerHTML = `
            <td class="px-4 py-4 text-center">
                <input type="checkbox" class="row-select w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500" value="${lookbook.id}">
            </td>
            <td class="px-4 py-4 text-center">
                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 border border-gray-300">#${lookbook.id}</span>
            </td>
            <td class="px-4 py-4">
                <p class="text-sm font-bold text-gray-900 group-hover:text-blue-700">${escapeHtml(lookbook.title)}</p>
            </td>
            <td class="px-4 py-4 text-center">
                <img src="${lookbook.image_url || '/assets/images/home-01/bn-05.jpg'}" alt="${escapeHtml(lookbook.title)}" class="w-16 h-16 object-cover rounded-lg mx-auto">
            </td>
            <td class="px-4 py-4">
                <p class="text-sm text-gray-600">${escapeHtml(lookbook.subtitle) || 'N/A'}</p>
            </td>
            <td class="px-4 py-4 text-center">
                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-700 border border-blue-300">${lookbook.sort_order}</span>
            </td>
            <td class="px-4 py-4 text-center">
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" class="sr-only peer status-toggle" data-id="${lookbook.id}" data-field="is_active" ${lookbook.is_active ? 'checked' : ''}>
                    <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gradient-to-r peer-checked:from-green-400 peer-checked:to-emerald-500"></div>
                </label>
            </td>
            <td class="px-4 py-4">
                <div class="flex items-center justify-center space-x-2">
                    <button class="edit-btn inline-flex items-center justify-center w-9 h-9 text-blue-600 bg-blue-50 border-2 border-blue-200 rounded-lg hover:bg-blue-100 hover:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all"
                        title="Edit Lookbook"
                        data-id="${lookbook.id}"
                        data-title="${escapeHtml(lookbook.title)}"
                        data-subtitle="${escapeHtml(lookbook.subtitle)}"
                        data-link="${escapeHtml(lookbook.link)}"
                        data-position="${lookbook.position}"
                        data-sort-order="${lookbook.sort_order}"
                        data-is-active="${lookbook.is_active ? 'true' : 'false'}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </button>
                    <button type="button" class="delete-btn inline-flex items-center justify-center w-9 h-9 text-red-600 bg-red-50 border-2 border-red-200 rounded-lg hover:bg-red-100 hover:border-red-400 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all" 
                        title="Delete Lookbook"
                        data-id="${lookbook.id}"
                        data-title="${escapeHtml(lookbook.title)}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            </td>
        `;

        // Attach event listeners to new row
        attachRowEventListeners(row);
        return row;
    }

    function attachRowEventListeners(row) {
        // Edit button
        const editBtn = row.querySelector('.edit-btn');
        if (editBtn) {
            editBtn.addEventListener('click', function() {
                const data = {
                    id: this.dataset.id,
                    name: this.dataset.name,
                    subtitle: this.dataset.subtitle,
                    sortOrder: this.dataset.sortOrder,
                    isActive: this.dataset.isActive === 'true'
                };
                openEditModal(data);
            });
        }

        // Delete button
        const deleteBtn = row.querySelector('.delete-btn');
        if (deleteBtn) {
            deleteBtn.addEventListener('click', function() {
                const lookbookId = this.dataset.id;
                const LookbookName = this.dataset.name;
                const btn = this;

                showConfirmDialog(
                    'Delete Lookbook?',
                    `Are you sure you want to delete "${LookbookName}"? This action cannot be undone.`,
                    () => {
                        btn.disabled = true;
                        btn.innerHTML = '<svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';

                        fetch('/admin/lookbooks/' + lookbookId, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showToastNotification('Lookbook deleted successfully!', 'success');
                        const rowToDelete = btn.closest('tr');
                        const LookbookData = {
                            is_active: rowToDelete.dataset.status === '1'
                        };

                        updateStatsOnDelete(LookbookData);

                        rowToDelete.style.transition = 'all 0.3s ease';
                        rowToDelete.style.opacity = '0';
                        rowToDelete.style.transform = 'translateX(-20px)';
                        setTimeout(() => rowToDelete.remove(), 300);
                    } else {
                        showToastNotification(data.message || 'Failed to delete Lookbook', 'error');
                        btn.disabled = false;
                        btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>';
                    }
                })
                        .catch(error => {
                            showToastNotification('Network error occurred', 'error');
                            btn.disabled = false;
                            btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>';
                        });
                    }
                );
            });
        }

        // Status toggle
        const statusToggle = row.querySelector('.status-toggle');
        if (statusToggle) {
            statusToggle.addEventListener('change', function() {
                const lookbookId = this.dataset.id;
                const field = this.dataset.field;
                const value = this.checked ? 1 : 0;

                fetch('/admin/lookbooks/' + lookbookId + '/toggle', {
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
                        // Update row dataset
                        this.closest('tr').dataset.status = value ? '1' : '0';
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
        }

        // Row select checkbox
        const rowSelect = row.querySelector('.row-select');
        if (rowSelect) {
            rowSelect.addEventListener('change', function() {
                const selected = document.querySelectorAll('.row-select:checked').length;
                const btn = document.getElementById('applyBulkAction');
                const count = document.getElementById('selectedCount');

                if (btn) {
                    btn.disabled = selected === 0;
                    btn.textContent = selected > 0 ? `Apply (${selected})` : 'Apply';
                }

                if (count) count.textContent = selected;
            });
        }
    }
</script>
