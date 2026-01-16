<style>
    @keyframes slideIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-slideIn { animation: slideIn 0.3s ease-out forwards; }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(100px) scale(0.95); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    .animate-fadeIn { animation: fadeIn 0.2s ease-out forwards; }
    .animate-slideUp { animation: slideUp 0.3s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }
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
                    <button id="cancelBtn" class="flex-1 px-4 py-3 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition-all">Cancel</button>
                    <button id="confirmBtn" class="flex-1 px-4 py-3 bg-gradient-to-r from-red-500 to-pink-600 text-white font-semibold rounded-xl hover:from-red-600 hover:to-pink-700 transition-all shadow-lg hover:shadow-xl">Delete</button>
                </div>
            </div>
        `;
        
        overlay.appendChild(dialog);
        document.body.appendChild(overlay);
        
        const closeDialog = () => {
            overlay.style.opacity = '0';
            dialog.style.transform = 'scale(0.95)';
            setTimeout(() => overlay.remove(), 200);
        };
        
        dialog.querySelector('#cancelBtn').addEventListener('click', closeDialog);
        overlay.addEventListener('click', (e) => { if (e.target === overlay) closeDialog(); });
        dialog.querySelector('#confirmBtn').addEventListener('click', () => { closeDialog(); onConfirm(); });
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
        `;

        toastContainer.appendChild(toast);
        setTimeout(() => toast.classList.remove('translate-x-full'), 100);
        setTimeout(() => {
            toast.classList.add('translate-x-full');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // Modal Functions - Global scope
    window.openAddModal = function() {
        document.getElementById('modalTitle').textContent = 'Add New Testimonial';
        document.getElementById('testimonialForm').action = '{{ route("admin.testimonials.store") }}';
        document.getElementById('methodField').value = 'POST';
        document.getElementById('testimonialId').value = '';
        document.getElementById('name').value = '';
        document.getElementById('review').value = '';
        document.getElementById('rating').value = '5';
        document.getElementById('sort_order').value = '0';
        document.getElementById('is_active').checked = true;
        setRatingStars(5);
        document.getElementById('testimonialModal').classList.remove('hidden');
    }

    window.openEditModal = function(data) {
        document.getElementById('modalTitle').textContent = 'Edit Testimonial';
        document.getElementById('testimonialForm').action = `/admin/testimonials/${data.id}`;
        document.getElementById('methodField').value = 'PUT';
        document.getElementById('testimonialId').value = data.id;
        document.getElementById('name').value = data.name;
        document.getElementById('review').value = data.review;
        document.getElementById('rating').value = data.rating;
        document.getElementById('sort_order').value = data.sortOrder;
        document.getElementById('is_active').checked = data.isActive === 'true';
        setRatingStars(parseInt(data.rating));
        document.getElementById('testimonialModal').classList.remove('hidden');
    }

    window.closeModal = function() {
        document.getElementById('testimonialModal').classList.add('hidden');
    }

    function setRatingStars(rating) {
        document.querySelectorAll('.rating-star').forEach((star, index) => {
            if (index < rating) {
                star.classList.remove('text-gray-300');
                star.classList.add('text-yellow-400');
            } else {
                star.classList.remove('text-yellow-400');
                star.classList.add('text-gray-300');
            }
        });
    }

    // DOMContentLoaded Event
    document.addEventListener('DOMContentLoaded', function() {
        // Rating stars click
        document.querySelectorAll('.rating-star').forEach(star => {
            star.addEventListener('click', function() {
                const rating = parseInt(this.dataset.rating);
                document.getElementById('rating').value = rating;
                setRatingStars(rating);
            });
        });

        // Initialize rating stars
        setRatingStars(5);

        // Close modal buttons
        document.getElementById('closeModalBtn')?.addEventListener('click', closeModal);
        document.getElementById('cancelBtn')?.addEventListener('click', closeModal);
        document.getElementById('modalOverlay')?.addEventListener('click', closeModal);

        // Filter Logic
        const globalSearch = document.getElementById('globalSearch');
        const statusFilter = document.getElementById('statusFilter');
        const ratingFilter = document.getElementById('ratingFilter');
        const resetFiltersBtn = document.getElementById('resetFilters');

        function applyFilters() {
            const params = new URLSearchParams(window.location.search);
            
            const searchValue = globalSearch?.value.trim();
            if (searchValue) params.set('search', searchValue);
            else params.delete('search');

            const statusValue = statusFilter?.value;
            if (statusValue !== '') params.set('status', statusValue);
            else params.delete('status');

            const ratingValue = ratingFilter?.value;
            if (ratingValue !== '') params.set('rating', ratingValue);
            else params.delete('rating');

            params.set('page', '1');
            window.location.href = `${window.location.pathname}?${params.toString()}`;
        }

        let searchTimeout;
        globalSearch?.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(applyFilters, 500);
        });

        statusFilter?.addEventListener('change', applyFilters);
        ratingFilter?.addEventListener('change', applyFilters);

        resetFiltersBtn?.addEventListener('click', function() {
            if (globalSearch) globalSearch.value = '';
            if (statusFilter) statusFilter.value = '';
            if (ratingFilter) ratingFilter.value = '';
            window.location.href = window.location.pathname;
        });

        // Initialize values from URL
        const urlParams = new URLSearchParams(window.location.search);
        if (globalSearch && urlParams.has('search')) globalSearch.value = urlParams.get('search');
        if (statusFilter && urlParams.has('status')) statusFilter.value = urlParams.get('status');
        if (ratingFilter && urlParams.has('rating')) ratingFilter.value = urlParams.get('rating');

        // Edit buttons
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                openEditModal({
                    id: this.dataset.id,
                    name: this.dataset.name,
                    review: this.dataset.review,
                    rating: this.dataset.rating,
                    sortOrder: this.dataset.sortOrder,
                    isActive: this.dataset.isActive
                });
            });
        });

        // Delete buttons
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const testimonialId = this.dataset.id;
                const testimonialName = this.dataset.name;
                const deleteBtn = this;

                showConfirmDialog(
                    'Delete Testimonial?',
                    `Are you sure you want to delete testimonial from "${testimonialName}"?`,
                    () => {
                        deleteBtn.disabled = true;
                        fetch('/admin/testimonials/' + testimonialId, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                showToastNotification('Testimonial deleted successfully!', 'success');
                                const row = deleteBtn.closest('tr');
                                row.style.transition = 'all 0.3s ease';
                                row.style.opacity = '0';
                                setTimeout(() => row.remove(), 300);
                            } else {
                                showToastNotification(data.message || 'Failed to delete', 'error');
                                deleteBtn.disabled = false;
                            }
                        })
                        .catch(() => {
                            showToastNotification('Network error occurred', 'error');
                            deleteBtn.disabled = false;
                        });
                    }
                );
            });
        });

        // Toggle switches
        document.querySelectorAll('.feature-toggle').forEach(toggle => {
            toggle.addEventListener('change', function() {
                const testimonialId = this.dataset.id;
                const field = this.dataset.field;
                const value = this.checked ? 1 : 0;

                fetch('/admin/testimonials/' + testimonialId + '/toggle', {
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
        document.getElementById('refreshBtn')?.addEventListener('click', () => location.reload());

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
        document.getElementById('applyBulkAction')?.addEventListener('click', function() {
            const action = document.getElementById('bulkAction')?.value;
            const selected = Array.from(document.querySelectorAll('.row-select:checked')).map(cb => cb.value);

            if (!action || selected.length === 0) {
                showToastNotification('Select action and items', 'error');
                return;
            }

            if (action === 'delete') {
                showConfirmDialog(
                    'Delete Multiple Testimonials?',
                    `Are you sure you want to delete ${selected.length} selected testimonial(s)?`,
                    () => performBulkAction(action, selected)
                );
                return;
            }

            performBulkAction(action, selected);
        });

        function performBulkAction(action, selected) {
            fetch('/admin/testimonials/bulk-action', {
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
            if (btn) {
                btn.disabled = selected === 0;
                btn.textContent = selected > 0 ? `Apply (${selected})` : 'Apply';
            }
        }
    });
</script>
