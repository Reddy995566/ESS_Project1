<script>
document.addEventListener('DOMContentLoaded', function() {
    const table = document.getElementById('sellersTable');
    const tbody = table.querySelector('tbody');
    const globalSearch = document.getElementById('globalSearch');
    const statusFilter = document.getElementById('statusFilter');
    const itemsPerPage = document.getElementById('itemsPerPage');
    const resetFilters = document.getElementById('resetFilters');
    const refreshBtn = document.getElementById('refreshBtn');

    let allRows = Array.from(tbody.querySelectorAll('tr:not(#emptyState)'));

    // Search functionality
    function filterTable() {
        const searchTerm = globalSearch.value.toLowerCase();
        const statusValue = statusFilter.value.toLowerCase();

        allRows.forEach(row => {
            const businessName = row.querySelector('td:nth-child(2)')?.textContent.toLowerCase() || '';
            const owner = row.querySelector('td:nth-child(3)')?.textContent.toLowerCase() || '';
            const email = row.querySelector('td:nth-child(4)')?.textContent.toLowerCase() || '';
            const status = row.dataset.status?.toLowerCase() || '';

            const matchesSearch = businessName.includes(searchTerm) || 
                                owner.includes(searchTerm) || 
                                email.includes(searchTerm);
            const matchesStatus = !statusValue || status === statusValue;

            if (matchesSearch && matchesStatus) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });

        updateEmptyState();
    }

    // Update empty state
    function updateEmptyState() {
        const visibleRows = allRows.filter(row => row.style.display !== 'none');
        const emptyState = tbody.querySelector('#emptyState');
        
        if (visibleRows.length === 0 && !emptyState) {
            const emptyRow = document.createElement('tr');
            emptyRow.id = 'emptyState';
            emptyRow.innerHTML = `
                <td colspan="10" class="px-6 py-20">
                    <div class="text-center">
                        <div class="mx-auto flex items-center justify-center w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full mb-6">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">No Results Found</h3>
                        <p class="text-gray-500 mb-6 max-w-md mx-auto">No sellers match your current filters. Try adjusting your search criteria.</p>
                    </div>
                </td>
            `;
            tbody.appendChild(emptyRow);
        } else if (visibleRows.length > 0 && emptyState) {
            emptyState.remove();
        }
    }

    // Event listeners
    globalSearch.addEventListener('input', filterTable);
    statusFilter.addEventListener('change', filterTable);

    resetFilters.addEventListener('click', function() {
        globalSearch.value = '';
        statusFilter.value = '';
        filterTable();
    });

    refreshBtn.addEventListener('click', function() {
        location.reload();
    });

    // Approve seller
    document.querySelectorAll('.approve-seller').forEach(button => {
        button.addEventListener('click', function() {
            const sellerId = this.getAttribute('data-id');
            const sellerName = this.getAttribute('data-name');
            
            if(confirm(`Are you sure you want to approve "${sellerName}"?`)) {
                updateSellerStatus(sellerId, 'active', 'Seller approved successfully!');
            }
        });
    });

    // Reject seller
    document.querySelectorAll('.reject-seller').forEach(button => {
        button.addEventListener('click', function() {
            const sellerId = this.getAttribute('data-id');
            const sellerName = this.getAttribute('data-name');
            const reason = prompt(`Enter rejection reason for "${sellerName}":`);
            
            if(reason && reason.trim()) {
                updateSellerStatus(sellerId, 'rejected', 'Seller rejected!', reason);
            }
        });
    });

    // Suspend seller
    document.querySelectorAll('.suspend-seller').forEach(button => {
        button.addEventListener('click', function() {
            const sellerId = this.getAttribute('data-id');
            const sellerName = this.getAttribute('data-name');
            
            if(confirm(`Are you sure you want to suspend "${sellerName}"?`)) {
                updateSellerStatus(sellerId, 'suspended', 'Seller suspended!');
            }
        });
    });

    // Activate seller
    document.querySelectorAll('.activate-seller').forEach(button => {
        button.addEventListener('click', function() {
            const sellerId = this.getAttribute('data-id');
            const sellerName = this.getAttribute('data-name');
            
            if(confirm(`Are you sure you want to activate "${sellerName}"?`)) {
                updateSellerStatus(sellerId, 'active', 'Seller activated successfully!');
            }
        });
    });

    // Update seller status function
    function updateSellerStatus(sellerId, status, successMessage, rejectionReason = null) {
        const data = { status: status };
        if (rejectionReason) {
            data.rejection_reason = rejectionReason;
        }

        fetch(`/admin/sellers/${sellerId}/status`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(successMessage);
                location.reload();
            } else {
                alert('Error: ' + (data.message || 'Something went wrong'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error: ' + error.message);
        });
    }

    // Products Modal Functionality
    const sellerProductsModal = document.getElementById('sellerProductsModal');
    const modalSellerName = document.getElementById('modalSellerName');
    const closeModal = document.getElementById('closeModal');
    const productsLoading = document.getElementById('productsLoading');
    const productsContent = document.getElementById('productsContent');
    const productsTableBody = document.getElementById('productsTableBody');
    const emptyProductsState = document.getElementById('emptyProductsState');

    // View seller products
    document.addEventListener('click', function(e) {
        if (e.target.closest('.view-seller-products')) {
            const button = e.target.closest('.view-seller-products');
            const sellerId = button.dataset.sellerId;
            const sellerName = button.dataset.sellerName;
            
            openProductsModal(sellerId, sellerName);
        }
    });

    // Close modal
    closeModal.addEventListener('click', closeProductsModal);
    sellerProductsModal.addEventListener('click', function(e) {
        if (e.target === sellerProductsModal) {
            closeProductsModal();
        }
    });

    function openProductsModal(sellerId, sellerName) {
        modalSellerName.textContent = `${sellerName} - Products`;
        sellerProductsModal.classList.remove('hidden');
        productsLoading.classList.remove('hidden');
        productsContent.classList.add('hidden');
        
        // Fetch seller products
        fetchSellerProducts(sellerId);
    }

    function closeProductsModal() {
        sellerProductsModal.classList.add('hidden');
    }

    async function fetchSellerProducts(sellerId) {
        try {
            const response = await fetch(`/admin/sellers/${sellerId}/products`);
            const data = await response.json();
            
            if (data.success) {
                displayProducts(data.products, data.stats);
            } else {
                throw new Error(data.message || 'Failed to fetch products');
            }
        } catch (error) {
            console.error('Error fetching products:', error);
            productsLoading.innerHTML = `
                <div class="text-center py-12">
                    <div class="text-red-500 mb-4">
                        <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <p class="text-gray-600">Failed to load products. Please try again.</p>
                </div>
            `;
        }
    }

    function displayProducts(products, stats) {
        productsLoading.classList.add('hidden');
        productsContent.classList.remove('hidden');
        
        // Update stats
        document.getElementById('approvedCount').textContent = stats.approved || 0;
        document.getElementById('pendingCount').textContent = stats.pending || 0;
        document.getElementById('rejectedCount').textContent = stats.rejected || 0;
        document.getElementById('totalCount').textContent = stats.total || 0;
        
        // Clear table
        productsTableBody.innerHTML = '';
        
        if (products.length === 0) {
            emptyProductsState.classList.remove('hidden');
            return;
        }
        
        emptyProductsState.classList.add('hidden');
        
        // Populate table
        products.forEach(product => {
            const row = document.createElement('tr');
            row.className = 'hover:bg-gray-50';
            
            const statusBadge = getStatusBadge(product.status);
            const approvalBadge = getApprovalBadge(product.approval_status);
            
            row.innerHTML = `
                <td class="px-4 py-3">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                            <img class="h-10 w-10 rounded-lg object-cover" src="${product.image || '/placeholder.jpg'}" alt="${product.name}">
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">${product.name}</p>
                            <p class="text-sm text-gray-500">${product.category?.name || 'No Category'}</p>
                        </div>
                    </div>
                </td>
                <td class="px-4 py-3 text-sm text-gray-900">${product.sku || 'N/A'}</td>
                <td class="px-4 py-3 text-sm text-gray-900">₹${product.price}</td>
                <td class="px-4 py-3 text-sm text-gray-900">${product.stock}</td>
                <td class="px-4 py-3">${statusBadge}</td>
                <td class="px-4 py-3">${approvalBadge}</td>
                <td class="px-4 py-3">
                    <div class="flex items-center space-x-2">
                        <a href="/admin/products/${product.id}/edit" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Edit</a>
                        ${product.approval_status === 'pending' ? `
                            <button onclick="approveProduct(${product.id})" class="text-green-600 hover:text-green-900 text-sm font-medium">Approve</button>
                            <button onclick="rejectProduct(${product.id})" class="text-red-600 hover:text-red-900 text-sm font-medium">Reject</button>
                        ` : ''}
                    </div>
                </td>
            `;
            
            productsTableBody.appendChild(row);
        });
    }

    function getStatusBadge(status) {
        const badges = {
            'active': '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>',
            'inactive': '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Inactive</span>',
            'draft': '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Draft</span>'
        };
        return badges[status] || badges['inactive'];
    }

    function getApprovalBadge(approvalStatus) {
        const badges = {
            'approved': '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Approved</span>',
            'pending': '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">Pending</span>',
            'rejected': '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Rejected</span>'
        };
        return badges[approvalStatus] || badges['pending'];
    }

    // Global functions for product actions
    window.approveProduct = async function(productId) {
        if (!confirm('Are you sure you want to approve this product?')) return;
        
        try {
            const response = await fetch(`/admin/sellers/products/${productId}/approve`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Refresh the products in modal
                const sellerId = document.querySelector('.view-seller-products[data-seller-id]')?.dataset.sellerId;
                if (sellerId) {
                    fetchSellerProducts(sellerId);
                }
                showNotification('Product approved successfully!', 'success');
            } else {
                throw new Error(data.message);
            }
        } catch (error) {
            showNotification('Failed to approve product: ' + error.message, 'error');
        }
    };

    window.rejectProduct = async function(productId) {
        const reason = prompt('Please provide a reason for rejection:');
        if (!reason) return;
        
        try {
            const response = await fetch(`/admin/sellers/products/${productId}/reject`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ reason })
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Refresh the products in modal
                const sellerId = document.querySelector('.view-seller-products[data-seller-id]')?.dataset.sellerId;
                if (sellerId) {
                    fetchSellerProducts(sellerId);
                }
                showNotification('Product rejected successfully!', 'success');
            } else {
                throw new Error(data.message);
            }
        } catch (error) {
            showNotification('Failed to reject product: ' + error.message, 'error');
        }
    };

    function showNotification(message, type = 'info') {
        // Simple notification - you can enhance this
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg text-white ${type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500'}`;
        notification.textContent = message;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
});
</script>
