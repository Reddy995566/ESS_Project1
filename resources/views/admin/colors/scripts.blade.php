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
        background: linear-gradient(135deg, #a855f7 0%, #ec4899 100%);
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, #9333ea 0%, #db2777 100%);
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
        background: linear-gradient(135deg, #ede9fe 0%, #e0e7ff 100%);
        border-color: #8b5cf6;
        color: #6d28d9;
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(139, 92, 246, 0.3);
    }

    .paginate_button.current {
        background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%);
        color: white;
        border-color: #7c3aed;
        box-shadow: 0 4px 6px -1px rgba(139, 92, 246, 0.5);
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
        document.getElementById('colorModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('colorModal').classList.add('hidden');
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
        document.getElementById('modalTitle').textContent = 'Add New Color';
        document.getElementById('submitBtnText').textContent = 'Create Color';
        document.getElementById('colorForm').action = '{{ route('admin.colors.store') }}';
        document.getElementById('methodField').value = 'POST';
        document.getElementById('colorId').value = '';
        document.getElementById('colorForm').reset();
    }

    function openAddModal() {
        resetForm();
        openModal();
    }

    function openEditModal(data) {
        document.getElementById('modalTitle').textContent = 'Edit Color';
        document.getElementById('submitBtnText').textContent = 'Update Color';
        document.getElementById('colorForm').action = '/admin/colors/' + data.id;
        document.getElementById('methodField').value = 'PATCH';
        document.getElementById('colorId').value = data.id;

        document.getElementById('name').value = data.name;
        
        // Only set if fields exist (not commented out)
        const hexCodeEl = document.getElementById('hexCode');
        const hexCodePickerEl = document.getElementById('hexCodePicker');
        const sortOrderEl = document.querySelector('input[name="sort_order"]');
        
        if (hexCodeEl) hexCodeEl.value = data.hexCode;
        if (hexCodePickerEl) hexCodePickerEl.value = data.hexCode;
        if (sortOrderEl) sortOrderEl.value = data.sortOrder;
        
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
        // --- Filter Logic Starts Here ---

        const globalSearch = document.getElementById('globalSearch');
        const statusFilter = document.getElementById('statusFilter');
        const itemsPerPage = document.getElementById('itemsPerPage');
        const resetFiltersBtn = document.getElementById('resetFilters');

        // Function to build URL and reload
        function applyFilters() {
            const params = new URLSearchParams(window.location.search);
            
            // Search
            const searchValue = globalSearch.value.trim();
            if (searchValue) {
                params.set('search', searchValue);
            } else {
                params.delete('search');
            }

            // Status
            const statusValue = statusFilter.value;
            if (statusValue === '1') {
                params.set('status_filter', 'active');
            } else if (statusValue === '0') {
                params.set('status_filter', 'inactive');
            } else {
                params.delete('status_filter');
            }

            // Per Page
            const perPageValue = itemsPerPage.value;
            if (perPageValue) {
                params.set('per_page', perPageValue);
            }

            // Reset to page 1 when filtering
            params.set('page', '1');

            window.location.href = `${window.location.pathname}?${params.toString()}`;
        }

        // Debounce function for search
        let searchTimeout;
        globalSearch?.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(applyFilters, 500);
        });

        // Search on Enter key
        globalSearch?.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                clearTimeout(searchTimeout);
                applyFilters();
            }
        });

        // Status Filter Change
        statusFilter?.addEventListener('change', applyFilters);

        // Items Per Page Change
        itemsPerPage?.addEventListener('change', applyFilters);

        // Reset Filters
        resetFiltersBtn?.addEventListener('click', function() {
            if (globalSearch) globalSearch.value = '';
            if (statusFilter) statusFilter.value = '';
            if (itemsPerPage) itemsPerPage.value = '25'; // Default to 25
            
            applyFilters();
        });

        // Initialize values from URL (to keep UI in sync)
        const urlParams = new URLSearchParams(window.location.search);
        
        if (globalSearch && urlParams.has('search')) {
            globalSearch.value = urlParams.get('search');
        }

        if (statusFilter && urlParams.has('status_filter')) {
            const status = urlParams.get('status_filter');
            if (status === 'active') statusFilter.value = '1';
            else if (status === 'inactive') statusFilter.value = '0';
        }

        if (itemsPerPage && urlParams.has('per_page')) {
            itemsPerPage.value = urlParams.get('per_page');
        }

        // --- Filter Logic Ends Here ---

        // Modal Controls
        document.getElementById('closeModalBtn')?.addEventListener('click', closeModal);
        document.getElementById('cancelModalBtn')?.addEventListener('click', closeModal);
        document.getElementById('modalOverlay')?.addEventListener('click', closeModal);

        // Fill Up button - Auto-fill with sample data
        document.getElementById('fillUpBtn')?.addEventListener('click', function() {
            const sampleColors = [
                { name: 'Crimson Red', hex: '#DC143C' },
                { name: 'Ocean Blue', hex: '#0077BE' },
                { name: 'Forest Green', hex: '#228B22' },
                { name: 'Sunset Orange', hex: '#FF6347' },
                { name: 'Royal Purple', hex: '#7851A9' }
            ];
            
            const randomColor = sampleColors[Math.floor(Math.random() * sampleColors.length)];
            
            document.getElementById('name').value = randomColor.name;
            
            // Only set if fields exist
            const hexCodeEl = document.getElementById('hexCode');
            const hexCodePickerEl = document.getElementById('hexCodePicker');
            const sortOrderEl = document.querySelector('input[name="sort_order"]');
            
            if (hexCodeEl) hexCodeEl.value = randomColor.hex;
            if (hexCodePickerEl) hexCodePickerEl.value = randomColor.hex;
            if (sortOrderEl) sortOrderEl.value = Math.floor(Math.random() * 100);
            
            document.querySelector('input[name="is_active"]').checked = true;
            
            showToastNotification('Form filled with sample data!', 'success');
        });

        // Sync color picker with hex code input
        document.getElementById('hexCodePicker')?.addEventListener('input', function() {
            document.getElementById('hexCode').value = this.value.toUpperCase();
        });

        document.getElementById('hexCode')?.addEventListener('input', function() {
            const hexValue = this.value.trim();
            if (/^#[0-9A-F]{6}$/i.test(hexValue)) {
                document.getElementById('hexCodePicker').value = hexValue;
            }
        });

        // Edit buttons
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const data = {
                    id: this.dataset.id,
                    name: this.dataset.name,
                    hexCode: this.dataset.hexCode,
                    sortOrder: this.dataset.sortOrder,
                    isActive: this.dataset.isActive === 'true'
                };
                openEditModal(data);
            });
        });

        // Delete buttons with AJAX
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const colorId = this.dataset.id;
                const colorName = this.dataset.name;
                const deleteBtn = this;

                showConfirmDialog(
                    'Delete Color?',
                    `Are you sure you want to delete "${colorName}"? This action cannot be undone.`,
                    () => {
                        deleteBtn.disabled = true;
                        deleteBtn.innerHTML = '<svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';

                        fetch('/admin/colors/' + colorId, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showToastNotification('Color deleted successfully!', 'success');
                        const row = this.closest('tr');
                        const colorData = {
                            is_active: row.dataset.status === '1'
                        };

                        updateStatsOnDelete(colorData);

                        row.style.transition = 'all 0.3s ease';
                        row.style.opacity = '0';
                        row.style.transform = 'translateX(-20px)';
                        setTimeout(() => row.remove(), 300);
                    } else {
                        showToastNotification(data.message || 'Failed to delete color', 'error');
                        this.disabled = false;
                        this.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>';
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
                const colorId = this.dataset.id;
                const field = this.dataset.field;
                const value = this.checked ? 1 : 0;

                fetch('/admin/colors/' + colorId + '/toggle', {
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
        document.getElementById('colorForm')?.addEventListener('submit', function(e) {
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
                        updateStatsOnCreate(data.color);
                        addColorRow(data.color);
                    } else {
                        const oldRow = document.querySelector(`#colorsTable tbody tr[data-id="${data.color.id}"]`);
                        if (oldRow) {
                            const oldData = {
                                isActive: oldRow.dataset.status === '1'
                            };
                            updateStatsOnEdit(data.color, oldData);
                            updateColorRow(data.color);
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
                        showToastNotification(data.message || 'Failed to save color', 'error');
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
            window.open('/admin/colors/export', '_blank');
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
                    'Delete Multiple Colors?',
                    `Are you sure you want to delete ${selected.length} selected color${selected.length > 1 ? 's' : ''}? This action cannot be undone.`,
                    () => {
                        performBulkAction(action, selected);
                    }
                );
                return;
            }

            performBulkAction(action, selected);
        });

        function performBulkAction(action, selected) {

                fetch('/admin/colors/bulk-action', {
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
    function updateStatsOnCreate(color) {
        // Total colors
        const totalEl = document.querySelector('.bg-gradient-to-br.from-purple-500 .text-4xl');
        if (totalEl) {
            totalEl.textContent = parseInt(totalEl.textContent) + 1;
        }

        // Active colors
        if (color.is_active) {
            const activeEl = document.querySelector('.bg-gradient-to-br.from-green-500 .text-4xl');
            if (activeEl) {
                activeEl.textContent = parseInt(activeEl.textContent) + 1;
            }
        }
    }

    function updateStatsOnEdit(newColor, oldData) {
        // Active status changed?
        if (newColor.is_active !== oldData.isActive) {
            const activeEl = document.querySelector('.bg-gradient-to-br.from-green-500 .text-4xl');
            if (activeEl) {
                let count = parseInt(activeEl.textContent);
                activeEl.textContent = newColor.is_active ? count + 1 : count - 1;
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

    function updateStatsOnDelete(color) {
        // Total colors
        const totalEl = document.querySelector('.bg-gradient-to-br.from-purple-500 .text-4xl');
        if (totalEl) {
            totalEl.textContent = Math.max(0, parseInt(totalEl.textContent) - 1);
        }

        // Active colors
        if (color.is_active) {
            const activeEl = document.querySelector('.bg-gradient-to-br.from-green-500 .text-4xl');
            if (activeEl) {
                activeEl.textContent = Math.max(0, parseInt(activeEl.textContent) - 1);
            }
        }
    }

    function addColorRow(color) {
        const tbody = document.querySelector('#colorsTable tbody');
        if (!tbody) return;

        // Remove empty state if exists
        const emptyRow = tbody.querySelector('#emptyState');
        if (emptyRow) emptyRow.remove();

        const row = createColorRow(color);
        tbody.insertBefore(row, tbody.firstChild);

        // Add fade-in animation
        row.style.opacity = '0';
        setTimeout(() => {
            row.style.transition = 'all 0.3s ease';
            row.style.opacity = '1';
        }, 10);
    }

    function updateColorRow(color) {
        const row = document.querySelector(`#colorsTable tbody tr[data-id="${color.id}"]`);
        if (!row) return;

        const newRow = createColorRow(color);
        row.replaceWith(newRow);

        // Add highlight animation
        newRow.style.backgroundColor = '#fae8ff';
        setTimeout(() => {
            newRow.style.transition = 'background-color 1s ease';
            newRow.style.backgroundColor = '';
        }, 100);
    }

    function createColorRow(color) {
        const row = document.createElement('tr');
        row.className = 'group hover:bg-gradient-to-r hover:from-purple-50 hover:to-pink-50 transition-all duration-200';
        row.dataset.id = color.id;
        row.dataset.status = color.is_active ? '1' : '0';

        const escapeHtml = (text) => {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        };

        row.innerHTML = `
            <td class="px-4 py-4 text-center">
                <input type="checkbox" class="row-select w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-2 focus:ring-purple-500" value="${color.id}">
            </td>
            <td class="px-4 py-4 text-center">
                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 border border-gray-300">#${color.id}</span>
            </td>
            
            <td class="px-4 py-4">
                <p class="text-sm font-bold text-gray-900 group-hover:text-purple-700">${escapeHtml(color.name)}</p>
            </td>
            
            <td class="px-4 py-4 text-center">
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" class="sr-only peer status-toggle" data-id="${color.id}" data-field="is_active" ${color.is_active ? 'checked' : ''}>
                    <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gradient-to-r peer-checked:from-green-400 peer-checked:to-emerald-500"></div>
                </label>
            </td>
            <td class="px-4 py-4">
                <div class="flex items-center justify-center space-x-2">
                    <button class="edit-btn inline-flex items-center justify-center w-9 h-9 text-purple-600 bg-purple-50 border-2 border-purple-200 rounded-lg hover:bg-purple-100 hover:border-purple-400 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-all"
                        title="Edit Color"
                        data-id="${color.id}"
                        data-name="${escapeHtml(color.name)}"
                        data-hex-code="${escapeHtml(color.hex_code)}"
                        data-sort-order="${color.sort_order}"
                        data-is-active="${color.is_active ? 'true' : 'false'}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </button>
                    <button type="button" class="delete-btn inline-flex items-center justify-center w-9 h-9 text-red-600 bg-red-50 border-2 border-red-200 rounded-lg hover:bg-red-100 hover:border-red-400 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all" 
                        title="Delete Color"
                        data-id="${color.id}"
                        data-name="${escapeHtml(color.name)}">
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
                    hexCode: this.dataset.hexCode,
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
                const colorId = this.dataset.id;
                const colorName = this.dataset.name;
                const btn = this;

                showConfirmDialog(
                    'Delete Color?',
                    `Are you sure you want to delete "${colorName}"? This action cannot be undone.`,
                    () => {
                        btn.disabled = true;
                        btn.innerHTML = '<svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';

                        fetch('/admin/colors/' + colorId, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showToastNotification('Color deleted successfully!', 'success');
                        const rowToDelete = this.closest('tr');
                        const colorData = {
                            is_active: rowToDelete.dataset.status === '1'
                        };

                        updateStatsOnDelete(colorData);

                        rowToDelete.style.transition = 'all 0.3s ease';
                        rowToDelete.style.opacity = '0';
                        rowToDelete.style.transform = 'translateX(-20px)';
                        setTimeout(() => rowToDelete.remove(), 300);
                    } else {
                        showToastNotification(data.message || 'Failed to delete color', 'error');
                        this.disabled = false;
                        this.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>';
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
                const colorId = this.dataset.id;
                const field = this.dataset.field;
                const value = this.checked ? 1 : 0;

                fetch('/admin/colors/' + colorId + '/toggle', {
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
