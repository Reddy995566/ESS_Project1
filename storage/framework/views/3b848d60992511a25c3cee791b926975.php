<script>
// Global variables
let currentPayoutId = null;

// Modal functions - attach to window for global access
window.openModal = function(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
    document.getElementById(modalId).classList.add('flex');
}

window.closeModal = function(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    document.getElementById(modalId).classList.remove('flex');
}

// Payout action functions - attach to window for inline onclick
window.approvePayout = function(payoutId) {
    currentPayoutId = payoutId;
    openModal('approveModal');
}

window.rejectPayout = function(payoutId) {
    currentPayoutId = payoutId;
    openModal('rejectModal');
}

window.completePayout = function(payoutId) {
    currentPayoutId = payoutId;
    openModal('completeModal');
}

window.confirmApprove = function() {
    if (!currentPayoutId) {
        alert('No payout selected');
        return;
    }
    
    // Show loading state
    const approveBtn = document.getElementById('confirmApproveBtn');
    const originalText = approveBtn.textContent;
    approveBtn.textContent = 'Processing...';
    approveBtn.disabled = true;
    
    console.log('Approving payout ID:', currentPayoutId);
    
    fetch(`/admin/seller-payouts/${currentPayoutId}/approve`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            alert('Payout approved successfully!');
            closeModal('approveModal');
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to approve payout'));
            approveBtn.textContent = originalText;
            approveBtn.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while approving the payout: ' + error.message);
        approveBtn.textContent = originalText;
        approveBtn.disabled = false;
    });
}

// Wait for DOM to be ready
document.addEventListener('DOMContentLoaded', function() {
    // Form submissions
    const rejectForm = document.getElementById('rejectForm');
    if (rejectForm) {
        rejectForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!currentPayoutId) return;
            
            const formData = new FormData(this);
            
            fetch(`/admin/seller-payouts/${currentPayoutId}/reject`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Payout rejected successfully!');
                    closeModal('rejectModal');
                    location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Failed to reject payout'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while rejecting the payout');
            });
        });
    }

    const completeForm = document.getElementById('completeForm');
    if (completeForm) {
        completeForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!currentPayoutId) return;
            
            const formData = new FormData(this);
            
            fetch(`/admin/seller-payouts/${currentPayoutId}/complete`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Payout completed successfully!');
                    closeModal('completeModal');
                    location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Failed to complete payout'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while completing the payout');
            });
        });
    }

    // Bulk actions
    const bulkActionBtn = document.getElementById('bulkActionBtn');
    if (bulkActionBtn) {
        bulkActionBtn.addEventListener('click', function() {
            const selectedCheckboxes = document.querySelectorAll('.payout-checkbox:checked');
            if (selectedCheckboxes.length === 0) {
                alert('Please select at least one payout to perform bulk actions.');
                return;
            }
            openModal('bulkActionModal');
        });
    }

    const bulkActionForm = document.getElementById('bulkActionForm');
    if (bulkActionForm) {
        bulkActionForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const selectedCheckboxes = document.querySelectorAll('.payout-checkbox:checked');
            const payoutIds = Array.from(selectedCheckboxes).map(cb => cb.value);
            const action = this.querySelector('[name="action"]').value;
            
            if (payoutIds.length === 0) {
                alert('No payouts selected');
                return;
            }
            
            const formData = new FormData();
            formData.append('action', action);
            payoutIds.forEach(id => formData.append('payout_ids[]', id));
            
            fetch('/admin/seller-payouts/bulk-action', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Failed to perform bulk action'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while performing bulk action');
            });
            
            closeModal('bulkActionModal');
        });
    }

    // Select all functionality
    const selectAll = document.getElementById('selectAll');
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.payout-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }

    // Refresh button
    const refreshBtn = document.getElementById('refreshBtn');
    if (refreshBtn) {
        refreshBtn.addEventListener('click', function() {
            location.reload();
        });
    }

    // Close modals when clicking outside
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('fixed') && e.target.classList.contains('inset-0')) {
            const modals = ['approveModal', 'rejectModal', 'completeModal', 'bulkActionModal'];
            modals.forEach(modalId => {
                if (e.target.id === modalId) {
                    closeModal(modalId);
                }
            });
        }
    });
});
</script><?php /**PATH C:\xampp\htdocs\ESS_Project1\resources\views/admin/seller-payouts/scripts.blade.php ENDPATH**/ ?>