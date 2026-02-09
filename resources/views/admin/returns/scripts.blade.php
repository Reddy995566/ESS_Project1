<script>
/**
 * Returns Management Script
 * Enterprise-level JavaScript functionality for the returns management page
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize components
    initializeSelectionControls();
    initializeBulkActions();
    initializeActionButtons();
    initializeFilters();
    initializeExport();
    initializeModal();
});

// Selection Controls
function initializeSelectionControls() {
    const selectAll = document.getElementById('selectAll');
    const rowSelects = document.querySelectorAll('.row-select');
    const selectedCount = document.getElementById('selectedCount');
    const bulkActionSelect = document.getElementById('bulkAction');
    const applyBulkActionBtn = document.getElementById('applyBulkAction');

    // Select all functionality
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            const isChecked = this.checked;
            rowSelects.forEach(checkbox => {
                checkbox.checked = isChecked;
            });
            updateSelectionCount();
        });
    }

    // Individual row selection
    rowSelects.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectionCount();
            
            // Update select all state
            const checkedBoxes = document.querySelectorAll('.row-select:checked').length;
            selectAll.checked = checkedBoxes === rowSelects.length && checkedBoxes > 0;
            selectAll.indeterminate = checkedBoxes > 0 && checkedBoxes < rowSelects.length;
        });
    });

    function updateSelectionCount() {
        const checkedBoxes = document.querySelectorAll('.row-select:checked');
        selectedCount.textContent = checkedBoxes.length;
        
        // Enable/disable bulk actions
        const hasSelection = checkedBoxes.length > 0;
        applyBulkActionBtn.disabled = !hasSelection || !bulkActionSelect.value;
        
        // Reset bulk action select if no items selected
        if (!hasSelection) {
            bulkActionSelect.value = '';
        }
    }

    // Enable/disable apply button based on action selection
    if (bulkActionSelect) {
        bulkActionSelect.addEventListener('change', function() {
            const checkedBoxes = document.querySelectorAll('.row-select:checked');
            applyBulkActionBtn.disabled = !this.value || checkedBoxes.length === 0;
        });
    }
}

// Bulk Actions
function initializeBulkActions() {
    const applyBulkActionBtn = document.getElementById('applyBulkAction');
    
    if (applyBulkActionBtn) {
        applyBulkActionBtn.addEventListener('click', function() {
            const action = document.getElementById('bulkAction').value;
            const selectedIds = Array.from(document.querySelectorAll('.row-select:checked'))
                .map(cb => cb.value);
            
            if (!action || selectedIds.length === 0) {
                showToast('Please select an action and at least one return.', 'error');
                return;
            }

            // Confirm destructive actions
            if (action === 'delete') {
                const confirmMessage = `Are you sure you want to delete ${selectedIds.length} return(s)? This action cannot be undone.`;
                if (!confirm(confirmMessage)) {
                    return;
                }
            } else if (action === 'reject') {
                const reason = prompt('Please enter rejection reason for all selected returns:');
                if (!reason || !reason.trim()) {
                    return;
                }
                performBulkAction(action, selectedIds, { rejection_reason: reason.trim() });
                return;
            }

            performBulkAction(action, selectedIds);
        });
    }
}

async function performBulkAction(action, returnIds, extraData = {}) {
    showLoading('Processing bulk action...');
    
    try {
        const response = await fetch(`/admin/returns/bulk-action`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                action: action,
                return_ids: returnIds,
                ...extraData
            })
        });

        const data = await response.json();

        if (data.success) {
            showToast(data.message, 'success');
            
            // Update UI based on action
            updateRowsAfterBulkAction(action, returnIds);
            
            // Reset selections
            resetSelections();
            
            // Update statistics
            updateStatistics();
        } else {
            showToast(data.message || 'Bulk action failed', 'error');
        }
    } catch (error) {
        showToast('An error occurred while performing bulk action.', 'error');
    } finally {
        hideLoading();
    }
}

function updateRowsAfterBulkAction(action, returnIds) {
    returnIds.forEach(id => {
        const row = document.querySelector(`tr[data-id="${id}"]`);
        if (!row) return;

        switch (action) {
            case 'delete':
                row.remove();
                break;
            case 'approve':
                updateRowStatus(row, 'approved');
                break;
            case 'reject':
                updateRowStatus(row, 'rejected');
                break;
            case 'pickup':
                updateRowStatus(row, 'picked_up');
                break;
            case 'refund':
                updateRowStatus(row, 'refunded');
                break;
        }
    });
    
    // Check if table is empty
    const remainingRows = document.querySelectorAll('tr[data-id]');
    if (remainingRows.length === 0) {
        showEmptyState();
    }
}

// Action Buttons
function initializeActionButtons() {
    // View buttons
    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const returnId = this.dataset.id;
            window.location.href = `/admin/returns/${returnId}`;
        });
    });

    // Approve buttons
    document.querySelectorAll('.approve-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const returnId = this.dataset.id;
            approveReturn(returnId);
        });
    });

    // Reject buttons
    document.querySelectorAll('.reject-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const returnId = this.dataset.id;
            rejectReturn(returnId);
        });
    });

    // Pickup buttons
    document.querySelectorAll('.pickup-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const returnId = this.dataset.id;
            markPickedUp(returnId);
        });
    });

    // Refund buttons
    document.querySelectorAll('.refund-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const returnId = this.dataset.id;
            const amount = this.dataset.amount;
            processRefund(returnId, amount);
        });
    });
}

async function approveReturn(returnId) {
    if (!confirm('Are you sure you want to approve this return?')) {
        return;
    }

    showLoading('Approving return...');
    
    try {
        const response = await fetch(`/admin/returns/${returnId}/approve`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        const data = await response.json();

        if (data.success) {
            showToast(data.message, 'success');
            
            // Update row status
            const row = document.querySelector(`tr[data-id="${returnId}"]`);
            if (row) {
                updateRowStatus(row, 'approved');
            }
            
            updateStatistics();
        } else {
            showToast(data.message || 'Failed to approve return', 'error');
        }
    } catch (error) {
        showToast('An error occurred while approving the return.', 'error');
    } finally {
        hideLoading();
    }
}

async function rejectReturn(returnId) {
    const reason = prompt('Please enter rejection reason:');
    if (!reason || !reason.trim()) {
        return;
    }

    showLoading('Rejecting return...');
    
    try {
        const response = await fetch(`/admin/returns/${returnId}/reject`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                rejection_reason: reason.trim()
            })
        });

        const data = await response.json();

        if (data.success) {
            showToast(data.message, 'success');
            
            // Update row status
            const row = document.querySelector(`tr[data-id="${returnId}"]`);
            if (row) {
                updateRowStatus(row, 'rejected');
            }
            
            updateStatistics();
        } else {
            showToast(data.message || 'Failed to reject return', 'error');
        }
    } catch (error) {
        showToast('An error occurred while rejecting the return.', 'error');
    } finally {
        hideLoading();
    }
}

async function markPickedUp(returnId) {
    const trackingNumber = prompt('Enter tracking number (optional):');
    
    showLoading('Marking as picked up...');
    
    try {
        const response = await fetch(`/admin/returns/${returnId}/pickup`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                tracking_number: trackingNumber || ''
            })
        });

        const data = await response.json();

        if (data.success) {
            showToast(data.message, 'success');
            
            // Update row status
            const row = document.querySelector(`tr[data-id="${returnId}"]`);
            if (row) {
                updateRowStatus(row, 'picked_up');
            }
            
            updateStatistics();
        } else {
            showToast(data.message || 'Failed to mark as picked up', 'error');
        }
    } catch (error) {
        showToast('An error occurred while marking as picked up.', 'error');
    } finally {
        hideLoading();
    }
}

async function processRefund(returnId, originalAmount) {
    const refundAmount = prompt(`Enter refund amount (Original: ₹${originalAmount}):`, originalAmount);
    if (!refundAmount || isNaN(refundAmount) || parseFloat(refundAmount) <= 0) {
        return;
    }

    showLoading('Processing refund...');
    
    try {
        const response = await fetch(`/admin/returns/${returnId}/refund`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                refund_amount: parseFloat(refundAmount)
            })
        });

        const data = await response.json();

        if (data.success) {
            showToast(data.message, 'success');
            
            // Update row status
            const row = document.querySelector(`tr[data-id="${returnId}"]`);
            if (row) {
                updateRowStatus(row, 'refunded');
                // Update amount display
                const amountCell = row.querySelector('td:nth-child(6)');
                if (amountCell) {
                    amountCell.innerHTML = `
                        <div class="text-sm font-bold text-gray-900">₹${parseFloat(originalAmount).toFixed(2)}</div>
                        <div class="text-xs text-green-600 font-medium">Refunded: ₹${parseFloat(refundAmount).toFixed(2)}</div>
                    `;
                }
            }
            
            updateStatistics();
        } else {
            showToast(data.message || 'Failed to process refund', 'error');
        }
    } catch (error) {
        showToast('An error occurred while processing refund.', 'error');
    } finally {
        hideLoading();
    }
}

// Filters
function initializeFilters() {
    const globalSearch = document.getElementById('globalSearch');
    const statusFilter = document.getElementById('statusFilter');
    const dateRangeFilter = document.getElementById('dateRangeFilter');
    const itemsPerPage = document.getElementById('itemsPerPage');
    const resetFiltersBtn = document.getElementById('resetFilters');
    const refreshBtn = document.getElementById('refreshBtn');

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
        if (statusValue) {
            params.set('status', statusValue);
        } else {
            params.delete('status');
        }

        // Date Range
        const dateRangeValue = dateRangeFilter?.value;
        if (dateRangeValue) {
            params.set('date_range', dateRangeValue);
        } else {
            params.delete('date_range');
        }

        // Per Page
        const perPageValue = itemsPerPage?.value;
        if (perPageValue) {
            params.set('per_page', perPageValue);
        }

        // Reset to page 1 for new filters
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
            e.preventDefault();
            clearTimeout(searchTimeout);
            applyFilters();
        }
    });

    // Event Listeners for Dropdowns
    statusFilter?.addEventListener('change', applyFilters);
    dateRangeFilter?.addEventListener('change', applyFilters);
    itemsPerPage?.addEventListener('change', applyFilters);

    // Reset Logic
    resetFiltersBtn?.addEventListener('click', function() {
        if (globalSearch) globalSearch.value = '';
        if (statusFilter) statusFilter.value = '';
        if (dateRangeFilter) dateRangeFilter.value = '';
        if (itemsPerPage) itemsPerPage.value = '20';
        
        applyFilters();
    });

    // Refresh Button
    if (refreshBtn) {
        refreshBtn.addEventListener('click', function() {
            window.location.reload();
        });
    }
}

// Export functionality
function initializeExport() {
    const exportBtn = document.getElementById('exportBtn');
    
    if (exportBtn) {
        exportBtn.addEventListener('click', async function() {
            showLoading('Preparing export...');
            
            try {
                // Get current filters
                const formData = new FormData(document.getElementById('filtersForm'));
                const params = new URLSearchParams(formData).toString();
                
                // Trigger download
                window.location.href = `/admin/returns/export?${params}`;
                
                showToast('Export started! Download will begin shortly.', 'success');
            } catch (error) {
                showToast('Export failed. Please try again.', 'error');
            } finally {
                hideLoading();
            }
        });
    }
}

// Utility Functions
function updateRowStatus(row, status) {
    row.dataset.status = status;
    
    // Update status badge
    const statusCell = row.querySelector('td:nth-child(7) span');
    if (statusCell) {
        let statusText, statusClass;
        
        switch (status) {
            case 'pending':
                statusText = '🕐 Pending';
                statusClass = 'bg-yellow-100 text-yellow-800';
                break;
            case 'approved':
                statusText = '✅ Approved';
                statusClass = 'bg-blue-100 text-blue-800';
                break;
            case 'rejected':
                statusText = '❌ Rejected';
                statusClass = 'bg-red-100 text-red-800';
                break;
            case 'picked_up':
                statusText = '📦 Picked Up';
                statusClass = 'bg-indigo-100 text-indigo-800';
                break;
            case 'refunded':
                statusText = '💰 Refunded';
                statusClass = 'bg-green-100 text-green-800';
                break;
        }
        
        statusCell.textContent = statusText;
        statusCell.className = `inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold ${statusClass}`;
    }
    
    // Update action buttons
    const actionsCell = row.querySelector('td:last-child > div');
    if (actionsCell) {
        updateActionButtons(actionsCell, status, row.dataset.id, row.dataset.amount);
    }
}

function updateActionButtons(container, status, returnId, amount) {
    // Keep view button, update others based on status
    const viewBtn = container.querySelector('.view-btn');
    container.innerHTML = '';
    
    if (viewBtn) {
        container.appendChild(viewBtn);
    }
    
    if (status === 'pending') {
        container.innerHTML += `
            <button class="approve-btn inline-flex items-center justify-center w-9 h-9 text-green-600 bg-green-50 border-2 border-green-200 rounded-lg hover:bg-green-100 hover:border-green-400 focus:outline-none focus:ring-2 focus:ring-green-500 transition-all"
                    title="Approve Return" data-id="${returnId}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </button>
            <button class="reject-btn inline-flex items-center justify-center w-9 h-9 text-red-600 bg-red-50 border-2 border-red-200 rounded-lg hover:bg-red-100 hover:border-red-400 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all"
                    title="Reject Return" data-id="${returnId}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        `;
    } else if (status === 'approved') {
        container.innerHTML += `
            <button class="pickup-btn inline-flex items-center justify-center w-9 h-9 text-purple-600 bg-purple-50 border-2 border-purple-200 rounded-lg hover:bg-purple-100 hover:border-purple-400 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-all"
                    title="Mark Picked Up" data-id="${returnId}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </button>
        `;
    } else if (status === 'picked_up') {
        container.innerHTML += `
            <button class="refund-btn inline-flex items-center justify-center w-9 h-9 text-cyan-600 bg-cyan-50 border-2 border-cyan-200 rounded-lg hover:bg-cyan-100 hover:border-cyan-400 focus:outline-none focus:ring-2 focus:ring-cyan-500 transition-all"
                    title="Process Refund" data-id="${returnId}" data-amount="${amount}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                </svg>
            </button>
        `;
    }
    
    // Re-initialize action buttons for new elements
    initializeActionButtons();
}

function updateStatistics() {
    // Count returns by status from visible rows
    const allRows = document.querySelectorAll('tr[data-id]');
    
    let totalCount = allRows.length;
    let pendingCount = 0;
    let approvedCount = 0;
    let refundedCount = 0;
    
    allRows.forEach(row => {
        const status = row.dataset.status;
        if (status === 'pending') pendingCount++;
        else if (status === 'approved') approvedCount++;
        else if (status === 'refunded') refundedCount++;
    });
    
    // Update stat boxes
    const totalEl = document.getElementById('totalReturns');
    const pendingEl = document.getElementById('pendingReturns');
    const approvedEl = document.getElementById('approvedReturns');
    const refundedEl = document.getElementById('refundedReturns');
    
    if (totalEl) totalEl.textContent = totalCount.toLocaleString();
    if (pendingEl) pendingEl.textContent = pendingCount.toLocaleString();
    if (approvedEl) approvedEl.textContent = approvedCount.toLocaleString();
    if (refundedEl) refundedEl.textContent = refundedCount.toLocaleString();
}

function resetSelections() {
    document.getElementById('selectAll').checked = false;
    document.querySelectorAll('.row-select').forEach(cb => cb.checked = false);
    document.getElementById('selectedCount').textContent = '0';
    document.getElementById('bulkAction').value = '';
    document.getElementById('applyBulkAction').disabled = true;
}

function showEmptyState() {
    const tbody = document.querySelector('#returnsTable tbody');
    if (tbody) {
        tbody.innerHTML = `
            <tr id="emptyState">
                <td colspan="9" class="px-6 py-20">
                    <div class="text-center">
                        <div class="mx-auto flex items-center justify-center w-24 h-24 bg-gradient-to-br from-purple-100 to-indigo-200 rounded-full mb-6">
                            <svg class="w-12 h-12 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">No Returns Found</h3>
                        <p class="text-gray-500 mb-6 max-w-md mx-auto">No return requests match your current filters.</p>
                    </div>
                </td>
            </tr>
        `;
    }
}

function showLoading(message = 'Loading...') {
    let overlay = document.getElementById('loadingOverlay');
    if (!overlay) {
        overlay = document.createElement('div');
        overlay.id = 'loadingOverlay';
        overlay.className = 'fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50';
        overlay.innerHTML = `
            <div class="bg-white rounded-lg p-6 flex items-center space-x-4 shadow-xl">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-purple-600"></div>
                <span class="text-gray-700 font-medium" id="loadingMessage">${message}</span>
            </div>
        `;
        document.body.appendChild(overlay);
    } else {
        document.getElementById('loadingMessage').textContent = message;
        overlay.classList.remove('hidden');
    }
}

function hideLoading() {
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) {
        overlay.classList.add('hidden');
    }
}

function showToast(message, type = 'info') {
    let container = document.getElementById('toastContainer');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toastContainer';
        container.className = 'fixed top-4 right-4 z-50 space-y-2';
        document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
    const icon = type === 'success' ? '✓' : type === 'error' ? '✕' : 'ℹ';

    toast.className = `${bgColor} text-white px-6 py-4 rounded-lg shadow-lg flex items-center space-x-3 transform translate-x-full transition-transform duration-300 ease-in-out`;
    toast.innerHTML = `
        <span class="text-lg font-bold">${icon}</span>
        <span class="font-medium">${message}</span>
        <button onclick="this.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
        </button>
    `;

    container.appendChild(toast);

    // Animate in
    setTimeout(() => {
        toast.classList.remove('translate-x-full');
    }, 100);

    // Auto remove after 5 seconds
    setTimeout(() => {
        toast.classList.add('translate-x-full');
        setTimeout(() => {
            if (toast.parentElement) {
                toast.remove();
            }
        }, 300);
    }, 5000);
}

// Modal Functions (placeholder for future implementation)
function initializeModal() {
    // Modal functionality can be added here if needed
}
</script>