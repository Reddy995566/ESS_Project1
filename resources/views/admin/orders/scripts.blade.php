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
</style>

<script>
    // Toast Notification
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

    // Confirm Dialog
    function showConfirmDialog(title, message, onConfirm) {
        const overlay = document.createElement('div');
        overlay.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center animate-fadeIn';
        
        const dialog = document.createElement('div');
        dialog.className = 'bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-300 animate-slideUp';
        dialog.innerHTML = `
            <div class="p-6">
                <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 rounded-full bg-gradient-to-br from-blue-100 to-indigo-100">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 text-center mb-2">${title}</h3>
                <p class="text-gray-600 text-center mb-6">${message}</p>
                <div class="flex gap-3">
                    <button id="cancelBtn" class="flex-1 px-4 py-3 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition-all">
                        Cancel
                    </button>
                    <button id="confirmBtn" class="flex-1 px-4 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-semibold rounded-xl hover:from-blue-600 hover:to-indigo-700 transition-all shadow-lg hover:shadow-xl">
                        Confirm
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

    // DOMContentLoaded Event
    document.addEventListener('DOMContentLoaded', function() {
        // Refresh button
        document.getElementById('refreshBtn')?.addEventListener('click', () => location.reload());

        // Export button
        document.getElementById('exportBtn')?.addEventListener('click', function() {
            window.open('/admin/orders/export', '_blank');
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

            const actionLabels = {
                'processing': 'mark as Processing',
                'shipped': 'mark as Shipped',
                'delivered': 'mark as Delivered',
                'cancelled': 'cancel'
            };

            showConfirmDialog(
                'Bulk Action Confirmation',
                `Are you sure you want to ${actionLabels[action] || action} ${selected.length} selected order${selected.length > 1 ? 's' : ''}?`,
                () => {
                    performBulkAction(action, selected);
                }
            );
        });

        function performBulkAction(action, selected) {
            fetch('/admin/orders/bulk-action', {
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

        // Filters
        const statusFilter = document.getElementById('statusFilter');
        const paymentFilter = document.getElementById('paymentFilter');
        const searchInput = document.getElementById('globalSearch');

        function applyFilters() {
            const statusValue = statusFilter?.value.toLowerCase();
            const paymentValue = paymentFilter?.value.toLowerCase();
            const searchValue = searchInput?.value.toLowerCase();

            let visibleCount = 0;

            document.querySelectorAll('#ordersTable tbody tr:not(#emptyState)').forEach(row => {
                const status = row.dataset.status?.toLowerCase();
                const payment = row.dataset.payment?.toLowerCase();
                const text = row.textContent.toLowerCase();

                const statusMatch = !statusValue || status === statusValue;
                const paymentMatch = !paymentValue || payment === paymentValue;
                const searchMatch = !searchValue || text.includes(searchValue);

                const shouldShow = statusMatch && paymentMatch && searchMatch;
                row.style.display = shouldShow ? '' : 'none';
                
                if (shouldShow) visibleCount++;
            });

            // Show/hide empty state
            const emptyState = document.getElementById('emptyState');
            const allRows = document.querySelectorAll('#ordersTable tbody tr:not(#emptyState)');
            
            if (emptyState && allRows.length > 0) {
                emptyState.style.display = visibleCount === 0 ? '' : 'none';
            }

            }

        if (statusFilter) {
            statusFilter.addEventListener('change', function() {
                applyFilters();
            });
        }
        
        if (paymentFilter) {
            paymentFilter.addEventListener('change', function() {
                applyFilters();
            });
        }
        
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                applyFilters();
            });
        }

        document.getElementById('resetFilters')?.addEventListener('click', function() {
            if (statusFilter) statusFilter.value = '';
            if (paymentFilter) paymentFilter.value = '';
            if (searchInput) searchInput.value = '';
            applyFilters();
            showToastNotification('Filters reset', 'success');
        });
    });
</script>
