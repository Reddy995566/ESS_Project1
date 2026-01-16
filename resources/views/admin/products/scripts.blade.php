<script>
/**
 * Products Management Script
 * Enterprise-level JavaScript functionality for the products management page
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize components
    initializeSelectionControls();
    initializeBulkActions();
    initializeToggleButtons();
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
                showToast('Please select an action and at least one product.', 'error');
                return;
            }

            // Confirm destructive actions
            if (action === 'delete') {
                const confirmMessage = `Are you sure you want to delete ${selectedIds.length} product(s)? This action cannot be undone.`;
                if (!confirm(confirmMessage)) {
                    return;
                }
            }

            performBulkAction(action, selectedIds);
        });
    }
}

async function performBulkAction(action, productIds) {
    showLoading('Processing bulk action...');
    
    try {
        const response = await fetch(`/admin/products/bulk-action`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                action: action,
                ids: productIds
            })
        });

        const data = await response.json();

        if (data.success) {
            showToast(data.message, 'success');
            
            // Update UI based on action
            updateRowsAfterBulkAction(action, productIds);
            
            // Reset selections
            resetSelections();
        } else {
            showToast(data.message || 'Bulk action failed', 'error');
        }
    } catch (error) {
        showToast('An error occurred while performing bulk action.', 'error');
    } finally {
        hideLoading();
    }
}

function updateRowsAfterBulkAction(action, productIds) {
    productIds.forEach(id => {
        const row = document.querySelector(`tr[data-id="${id}"]`);
        if (!row) return;

        switch (action) {
            case 'delete':
                row.remove();
                break;
            case 'activate':
                updateRowStatus(row, 'active');
                break;
            case 'deactivate':
                updateRowStatus(row, 'inactive');
                break;
            case 'feature':
                updateRowFeatureStatus(row, true);
                break;
            case 'unfeature':
                updateRowFeatureStatus(row, false);
                break;
            case 'in_stock':
                updateRowStockStatus(row, 'in_stock');
                break;
            case 'out_of_stock':
                updateRowStockStatus(row, 'out_of_stock');
                break;
        }
    });
    
    // Update statistics if needed
    setTimeout(refreshPage, 1500);
}

// Toggle Buttons (Status and Featured)
function initializeToggleButtons() {
    // Status toggles
    document.querySelectorAll('.status-toggle').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const productId = this.dataset.id;
            const isActive = this.checked;
            updateProductField(productId, 'status', isActive ? 'active' : 'inactive', this);
        });
    });

    // Featured toggles
    document.querySelectorAll('.featured-toggle').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const productId = this.dataset.id;
            const isFeatured = this.checked;
            updateProductField(productId, 'is_featured', isFeatured, this);
        });
    });
}

async function updateProductField(productId, field, value, toggleElement) {
    const originalState = toggleElement.checked;
    
    try {
        const response = await fetch(`/admin/products/${productId}/toggle`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                field: field,
                value: value
            })
        });

        const data = await response.json();

        if (data.success) {
            showToast(data.message, 'success');
            
            // Update row data attributes
            const row = document.querySelector(`tr[data-id="${productId}"]`);
            if (row && field === 'status') {
                row.dataset.status = value;
            } else if (row && field === 'is_featured') {
                row.dataset.featured = value ? '1' : '0';
            }
            
            // Update stats counters
            updateStatsCounters();
        } else {
            // Revert toggle state on error
            toggleElement.checked = !originalState;
            showToast(data.message || 'Update failed', 'error');
        }
    } catch (error) {
        // Revert toggle state
        showToast('An error occurred while updating the product.', 'error');
    }
}

// Action Buttons (View, Edit, Delete)
function initializeActionButtons() {
    // View buttons - Open modal instead of new tab
    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.dataset.id;
            openProductModal(productId);
        });
    });

    // Edit buttons
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.dataset.id;
            window.location.href = `/admin/products/${productId}/edit/step1`;
        });
    });

    // Delete buttons
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.dataset.id;
            const productName = this.dataset.name;
            
            if (confirm(`Are you sure you want to delete "${productName}"? This action cannot be undone.`)) {
                deleteProduct(productId);
            }
        });
    });

    // Copy buttons
    document.querySelectorAll('.copy-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.dataset.id;
            const productName = this.dataset.name;
            
            if (confirm(`Are you sure you want to duplicate "${productName}"?\n\nA copy will be created with "(Copy)" appended to the name and set to Draft status.`)) {
                copyProduct(productId);
            }
        });
    });
}

async function copyProduct(productId) {
    showLoading('Duplicating product...');
    
    try {
        const response = await fetch(`/admin/products/${productId}/copy`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        const data = await response.json();

        if (data.success) {
            showToast(data.message, 'success');
            
            // Refresh page after delay to show the new product
            setTimeout(refreshPage, 1500);
        } else {
            showToast(data.message || 'Copy failed', 'error');
        }
    } catch (error) {
        showToast('An error occurred while copying the product.', 'error');
    } finally {
        hideLoading();
    }
}

async function deleteProduct(productId) {
    showLoading('Deleting product...');
    
    try {
        const response = await fetch(`/admin/products/${productId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        const data = await response.json();

        if (data.success) {
            showToast(data.message, 'success');
            
            // Remove row from table
            const row = document.querySelector(`tr[data-id="${productId}"]`);
            if (row) {
                row.remove();
            }
            
            // Refresh page after delay to update counts
            setTimeout(refreshPage, 1500);
        } else {
            showToast(data.message || 'Delete failed', 'error');
        }
    } catch (error) {
        showToast('An error occurred while deleting the product.', 'error');
    } finally {
        hideLoading();
    }
}

// Filters
function initializeFilters() {
    const globalSearch = document.getElementById('globalSearch');
    const statusFilter = document.getElementById('statusFilter');
    const categoryFilter = document.getElementById('categoryFilter');
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
            params.set('filter_status', statusValue);
        } else {
            params.delete('filter_status');
        }

        // Category
        const categoryValue = categoryFilter?.value;
        if (categoryValue) {
            params.set('filter_category', categoryValue);
        } else {
            params.delete('filter_category');
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
            e.preventDefault(); // Prevent form submit if in form
            clearTimeout(searchTimeout);
            applyFilters();
        }
    });

    // Event Listeners for Dropdowns
    statusFilter?.addEventListener('change', applyFilters);
    categoryFilter?.addEventListener('change', applyFilters);
    itemsPerPage?.addEventListener('change', applyFilters);

    // Reset Logic
    resetFiltersBtn?.addEventListener('click', function() {
        if (globalSearch) globalSearch.value = '';
        if (statusFilter) statusFilter.value = '';
        if (categoryFilter) categoryFilter.value = '';
        if (itemsPerPage) itemsPerPage.value = '10'; // Default to 10
        
        applyFilters();
    });

    // Refresh Button
    if (refreshBtn) {
        refreshBtn.addEventListener('click', function() {
            window.location.reload();
        });
    }

    // Initialize values from URL (optional as Blade does it, but good for client-side sync if needed)
    const urlParams = new URLSearchParams(window.location.search);
    if (globalSearch && urlParams.has('search')) globalSearch.value = urlParams.get('search');
    if (statusFilter && urlParams.has('filter_status')) statusFilter.value = urlParams.get('filter_status');
    if (categoryFilter && urlParams.has('filter_category')) categoryFilter.value = urlParams.get('filter_category');
    if (itemsPerPage && urlParams.has('per_page')) itemsPerPage.value = urlParams.get('per_page');
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
                window.location.href = `/admin/products/export?${params}`;
                
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
    const toggle = row.querySelector('.status-toggle');
    if (toggle) {
        toggle.checked = status === 'active';
    }
}

function updateRowFeatureStatus(row, featured) {
    row.dataset.featured = featured ? '1' : '0';
    const toggle = row.querySelector('.featured-toggle');
    if (toggle) {
        toggle.checked = featured;
    }
}

function updateRowStockStatus(row, stockStatus) {
    row.dataset.stock = stockStatus;
    const stockBadge = row.querySelector('td:nth-child(7) span:last-child');
    if (stockBadge) {
        if (stockStatus === 'in_stock') {
            stockBadge.textContent = '📦 In Stock';
            stockBadge.className = 'inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium mt-1 bg-green-100 text-green-800 border border-green-300';
        } else {
            stockBadge.textContent = '❌ Out of Stock';
            stockBadge.className = 'inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium mt-1 bg-red-100 text-red-800 border border-red-300';
        }
    }
}

function updateStatsCounters() {
    // Count products by status from visible rows
    const allRows = document.querySelectorAll('tr[data-id]');
    
    let activeCount = 0;
    let featuredCount = 0;
    
    allRows.forEach(row => {
        if (row.dataset.status === 'active') {
            activeCount++;
        }
        if (row.dataset.featured === '1') {
            featuredCount++;
        }
    });
    
    // Update stat boxes if they exist
    const activeProductsEl = document.querySelector('.from-emerald-500.to-green-600 p.text-4xl');
    const featuredProductsEl = document.querySelector('.from-amber-500.to-orange-600 p.text-4xl');
    
    if (activeProductsEl) {
        activeProductsEl.textContent = activeCount;
    }
    
    if (featuredProductsEl) {
        featuredProductsEl.textContent = featuredCount;
    }
}

function resetSelections() {
    document.getElementById('selectAll').checked = false;
    document.querySelectorAll('.row-select').forEach(cb => cb.checked = false);
    document.getElementById('selectedCount').textContent = '0';
    document.getElementById('bulkAction').value = '';
    document.getElementById('applyBulkAction').disabled = true;
}

function refreshPage() {
    window.location.reload();
}

function showLoading(message = 'Loading...') {
    // Create loading overlay if it doesn't exist
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
    // Create toast container if it doesn't exist
    let container = document.getElementById('toastContainer');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toastContainer';
        container.className = 'fixed top-4 right-4 z-50 space-y-2';
        document.body.appendChild(container);
    }

    // Create toast
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

// Product Modal Functions
function initializeModal() {
    const modal = document.getElementById('productModal');
    const closeButtons = [document.getElementById('closeModalBtn'), document.getElementById('closeModal')];
    const modalOverlay = document.getElementById('modalOverlay');
    
    // Close modal handlers
    closeButtons.forEach(btn => {
        if (btn) {
            btn.addEventListener('click', closeProductModal);
        }
    });
    
    // Close on overlay click
    if (modalOverlay) {
        modalOverlay.addEventListener('click', closeProductModal);
    }
    
    // ESC key handler
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeProductModal();
        }
    });
}

async function openProductModal(productId) {
    const modal = document.getElementById('productModal');
    const modalLoading = document.getElementById('modalLoading');
    const productContent = document.getElementById('productContent');
    
    // Show modal with loading state
    modal.classList.remove('hidden');
    modalLoading.classList.remove('hidden');
    productContent.classList.add('hidden');
    
    // Set current timestamp
    const timestampEl = document.getElementById('viewTimestamp');
    if (timestampEl) timestampEl.textContent = new Date().toLocaleString();
    
    try {
        const response = await fetch(`/admin/products/${productId}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            populateModal(data.product);
            modalLoading.classList.add('hidden');
            productContent.classList.remove('hidden');
        } else {
            throw new Error(data.message || 'Failed to load product');
        }
    } catch (error) {
        modalLoading.innerHTML = `
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">Error Loading Product</h3>
                <p class="mt-2 text-sm text-gray-500">${error.message}</p>
                <button onclick="closeProductModal()" class="mt-4 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    Close
                </button>
            </div>
        `;
    }
}

let currentImageIndex = 0;
let productImages = [];

function populateModal(product) {
    // Product basic info
    const nameElement = document.getElementById('productName');
    const skuElement = document.getElementById('productSku');
    const idElement = document.getElementById('productId');
    
    if (nameElement) nameElement.textContent = product.name;
    if (skuElement) skuElement.textContent = product.sku ? `SKU: ${product.sku}` : 'No SKU';
    if (idElement) idElement.textContent = `#${product.id}`;
    
    // Brand info - handle both object and string formats
    const brandDiv = document.getElementById('productBrand');
    if (brandDiv) {
        if (product.brand) {
            brandDiv.classList.remove('hidden');
            let brandName = 'Unknown Brand';
            if (typeof product.brand === 'string') {
                brandName = product.brand;
            } else if (typeof product.brand === 'object' && product.brand !== null) {
                brandName = product.brand.name || product.brand.label || 'Unknown Brand';
            }
            brandDiv.textContent = brandName;
        } else {
            brandDiv.classList.add('hidden');
        }
    }
    
    // Setup image gallery
    setupImageGallery(product);
    
    // Pricing with discount calculation
    const regularPrice = parseFloat(product.price || 0);
    const priceEl = document.getElementById('productPrice');
    if (priceEl) priceEl.textContent = `₹${regularPrice.toLocaleString('en-IN', {minimumFractionDigits: 2})}`;
    
    if (product.sale_price && parseFloat(product.sale_price) < regularPrice) {
        const salePrice = parseFloat(product.sale_price);
        const discount = ((regularPrice - salePrice) / regularPrice * 100).toFixed(1);
        
        const salePriceEl = document.getElementById('productSalePrice');
        const discountEl = document.getElementById('discountPercentage');
        
        if (salePriceEl) {
            salePriceEl.classList.remove('hidden');
            salePriceEl.textContent = `₹${salePrice.toLocaleString('en-IN', {minimumFractionDigits: 2})}`;
        }
        
        if (discountEl) {
            discountEl.classList.remove('hidden');
            discountEl.textContent = `${discount}% OFF`;
        }
    } else {
        const salePriceEl = document.getElementById('productSalePrice');
        if (salePriceEl) salePriceEl.classList.add('hidden');
    }
    
    // Stock information with low stock warning
    const stock = parseInt(product.stock || 0);
    const stockEl = document.getElementById('productStock');
    if (stockEl) stockEl.textContent = stock.toLocaleString();
    
    const stockStatus = document.getElementById('productStockStatus');
    const lowStockWarning = document.getElementById('lowStockWarning');
    
    if (stockStatus) {
        if (product.stock_status === 'in_stock') {
            stockStatus.textContent = '📦 In Stock';
            stockStatus.className = 'text-xs text-green-600 font-medium';
            
            // Show low stock warning if stock < 10
            if (lowStockWarning) {
                if (stock < 10 && stock > 0) {
                    lowStockWarning.classList.remove('hidden');
                } else {
                    lowStockWarning.classList.add('hidden');
                }
            }
        } else {
            stockStatus.textContent = '❌ Out of Stock';
            stockStatus.className = 'text-xs text-red-600 font-medium';
            if (lowStockWarning) lowStockWarning.classList.add('hidden');
        }
    }
    
    // Status
    const statusElement = document.getElementById('productStatus');
    if (statusElement) {
        const status = product.status;
        if (status === 'active') {
            statusElement.textContent = '✅ Active';
            statusElement.className = 'text-xl font-bold mt-1 text-green-600';
        } else if (status === 'inactive') {
            statusElement.textContent = '❌ Inactive';
            statusElement.className = 'text-xl font-bold mt-1 text-red-600';
        } else {
            statusElement.textContent = '📝 Draft';
            statusElement.className = 'text-xl font-bold mt-1 text-yellow-600';
        }
    }
    
    // Category
    const categoryElement = document.getElementById('productCategory');
    if (categoryElement) {
        let categoryName = 'Uncategorized';
        if (product.category && product.category !== null) {
            if (typeof product.category === 'string') {
                categoryName = product.category;
            } else if (typeof product.category === 'object') {
                categoryName = product.category.name || 'Uncategorized';
            }
        }
        categoryElement.textContent = categoryName;
    }
    
    // Product badges
    const badgesContainer = document.getElementById('productBadges');
    if (badgesContainer) {
        badgesContainer.innerHTML = '';
        
        if (product.is_featured) {
            badgesContainer.innerHTML += '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-300">⭐ Featured</span>';
        }
        
        if (product.is_new) {
            badgesContainer.innerHTML += '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-300">🆕 New</span>';
        }
        
        if (product.is_sale) {
            badgesContainer.innerHTML += '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-300">🔥 Sale</span>';
        }
    }
    
    // Complete the product population with all details
    completeProductPopulation(product);
    
    // Setup image navigation event listeners
    const prevImageBtn = document.getElementById('prevImage');
    const nextImageBtn = document.getElementById('nextImage');
    
    if (prevImageBtn) {
        prevImageBtn.onclick = () => {
            const prevIndex = currentImageIndex > 0 ? currentImageIndex - 1 : productImages.length - 1;
            showImage(prevIndex);
        };
    }
    
    if (nextImageBtn) {
        nextImageBtn.onclick = () => {
            const nextIndex = currentImageIndex < productImages.length - 1 ? currentImageIndex + 1 : 0;
            showImage(nextIndex);
        };
    }
}

// Image Gallery Functions
function setupImageGallery(product) {
    productImages = [];
    currentImageIndex = 0;
    
    // Add main image (backend guarantees this is a string URL via image_url accessor)
    if (product.image && typeof product.image === 'string' && product.image.trim() !== '') {
        productImages.push(product.image);
    }
    
    // Add additional images (backend guarantees this is an array of string URLs via images_array accessor)
    if (product.images && Array.isArray(product.images)) {
        product.images.forEach(img => {
            if (typeof img === 'string' && img.trim() !== '') {
                productImages.push(img);
            }
        });
    }
    
    // Remove duplicates and filter out any non-string values
    productImages = [...new Set(productImages)].filter(img => typeof img === 'string' && img.length > 0);
    
    // Setup main image
    const mainImage = document.getElementById('productMainImage');
    if (mainImage) {
        if (productImages.length > 0 && productImages[0]) {
            mainImage.src = productImages[0];
            mainImage.alt = product.name;
        } else {
            mainImage.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzIwIiBoZWlnaHQ9IjMwMCIgdmlld0JveD0iMCAwIDMyMCAzMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIzMjAiIGhlaWdodD0iMzAwIiBmaWxsPSIjRjNGNEY2Ii8+CjxwYXRoIGQ9Ik0xNjAgMTIwQzE3MS4wNDYgMTIwIDE4MCAzMTEuMDQ2IDE4MCAzMjJDMTgwIDEzMi45NTQgMTcxLjA0NiAxNDQgMTYwIDE0NEMxNDguOTU0IDE0NCAxNDAgMTMyLjk1NCAxNDAgMTIyQzE0MCAxMTEuMDQ2IDE0OC45NTQgMTAwIDE2MCAxMDBaIiBmaWxsPSIjOUNBM0FGII8+CjxwYXRoIGQ9Ik0xMDAgMTgwQzEwNSAxODAgMTA5IDIwNS4yMyAxMDkgMjE2QzEwOSAyMjYuNzcgMTA1IDIzMiAxMDAgMjMySDIyMEMyMTUgMjMyIDIxMSAyMjYuNzcgMjExIDIxNkMyMTEgMjA1LjIzIDIxNSAxODAgMjIwIDE4MEgxMDBaIiBmaWxsPSIjOUNBM0FGII8+Cjwvc3ZnPgo=';
            mainImage.alt = 'No image available';
        }
    }
    
    // Setup thumbnails
    const thumbnailsContainer = document.getElementById('imageThumbnails');
    if (!thumbnailsContainer) return;
    
    thumbnailsContainer.innerHTML = '';
    
    if (productImages.length > 1) {
        productImages.forEach((image, index) => {
            // Skip if image is not a valid string URL
            if (!image || typeof image !== 'string' || image.trim() === '') {
                return;
            }
            
            const thumbnail = document.createElement('img');
            thumbnail.src = image;
            thumbnail.alt = `${product.name} - Image ${index + 1}`;
            thumbnail.className = `w-16 h-16 object-cover rounded-lg cursor-pointer border-2 transition-all duration-200 ${
                index === 0 ? 'border-purple-500' : 'border-gray-200 hover:border-purple-300'
            }`;
            thumbnail.addEventListener('click', () => showImage(index));
            thumbnailsContainer.appendChild(thumbnail);
        });
        
        // Show navigation arrows and counter
        const prevBtn = document.getElementById('prevImage');
        const nextBtn = document.getElementById('nextImage');
        const counter = document.getElementById('imageCounter');
        
        if (prevBtn) prevBtn.classList.remove('hidden');
        if (nextBtn) nextBtn.classList.remove('hidden');
        if (counter) counter.classList.remove('hidden');
        updateImageCounter();
    } else {
        const prevBtn = document.getElementById('prevImage');
        const nextBtn = document.getElementById('nextImage');
        const counter = document.getElementById('imageCounter');
        
        if (prevBtn) prevBtn.classList.add('hidden');
        if (nextBtn) nextBtn.classList.add('hidden');
        if (counter) counter.classList.add('hidden');
    }
}

function showImage(index) {
    if (index >= 0 && index < productImages.length) {
        const imageUrl = productImages[index];
        // Validate image URL is a string
        if (!imageUrl || typeof imageUrl !== 'string') {
            return;
        }
        
        currentImageIndex = index;
        const mainImg = document.getElementById('productMainImage');
        if (mainImg) mainImg.src = imageUrl;
        
        // Update thumbnail borders
        document.querySelectorAll('#imageThumbnails img').forEach((thumb, i) => {
            thumb.className = `w-16 h-16 object-cover rounded-lg cursor-pointer border-2 transition-all duration-200 ${
                i === index ? 'border-purple-500' : 'border-gray-200 hover:border-purple-300'
            }`;
        });
        
        updateImageCounter();
    }
}

function updateImageCounter() {
    const currentEl = document.getElementById('currentImageIndex');
    const totalEl = document.getElementById('totalImages');
    
    if (currentEl) currentEl.textContent = currentImageIndex + 1;
    if (totalEl) totalEl.textContent = productImages.length;
}

function completeProductPopulation(product) {
    // Status and visibility
    const statusElement = document.getElementById('productStatus');
    const visibilityDiv = document.getElementById('visibility');
    const visibilityElement = visibilityDiv ? visibilityDiv.querySelector('span') : null;
    
    if (statusElement) {
        switch (product.status) {
            case 'active':
                statusElement.textContent = '✅ Active';
                statusElement.className = 'font-bold text-green-600';
                break;
            case 'inactive':
                statusElement.textContent = '❌ Inactive';
                statusElement.className = 'font-bold text-red-600';
                break;
            default:
                statusElement.textContent = '📝 Draft';
                statusElement.className = 'font-bold text-yellow-600';
        }
    }
    
    if (visibilityElement) {
        visibilityElement.textContent = `${(product.visibility || 'visible').charAt(0).toUpperCase() + (product.visibility || 'visible').slice(1)}`;
    }
    
    // Category
    let categoryName = 'Uncategorized';
    if (product.category && product.category !== null) {
        if (typeof product.category === 'object') {
            categoryName = product.category.name || 'Uncategorized';
        } else if (typeof product.category === 'string') {
            categoryName = product.category;
        }
    }
    const categoryEl = document.getElementById('productCategory');
    if (categoryEl) categoryEl.textContent = categoryName;
    
    // Tags
    const productTagsEl = document.getElementById('productTags');
    if (productTagsEl && product.tags && Array.isArray(product.tags) && product.tags.length > 0) {
        productTagsEl.classList.remove('hidden');
        const tagsContainer = document.getElementById('tagsContainer');
        if (tagsContainer) {
            tagsContainer.innerHTML = '';
            product.tags.forEach(tag => {
                const tagElement = document.createElement('span');
                tagElement.className = 'inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-300';
                const tagName = typeof tag === 'object' ? (tag.name || tag) : tag;
                tagElement.innerHTML = `<svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>${tagName}`;
                tagsContainer.appendChild(tagElement);
            });
        }
    } else if (productTagsEl) {
        productTagsEl.classList.add('hidden');
    }
    
    // Collections
    const productCollectionsEl = document.getElementById('productCollections');
    if (productCollectionsEl && product.collections && Array.isArray(product.collections) && product.collections.length > 0) {
        productCollectionsEl.classList.remove('hidden');
        const collectionsContainer = document.getElementById('collectionsContainer');
        if (collectionsContainer) {
            collectionsContainer.innerHTML = '';
            product.collections.forEach(collection => {
                const collectionElement = document.createElement('span');
                collectionElement.className = 'inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 border border-purple-300';
                const collectionName = typeof collection === 'object' ? (collection.name || collection) : collection;
                collectionElement.innerHTML = `<svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>${collectionName}`;
                collectionsContainer.appendChild(collectionElement);
            });
        }
    } else if (productCollectionsEl) {
        productCollectionsEl.classList.add('hidden');
    }
    
    // Product Variants with Color Images - Frontend Style
    const hasVariants = product.variants && Array.isArray(product.variants) && product.variants.length > 0;
    const hasColors = product.colors && Array.isArray(product.colors) && product.colors.length > 0;
    const hasSizes = product.sizes && Array.isArray(product.sizes) && product.sizes.length > 0;
    
    if (hasVariants) {
        // Show variants section with color-based images
        displayProductVariants(product.variants);
    } else if (hasColors || hasSizes) {
        // Show simple attributes if no variants
        const simpleAttributesEl = document.getElementById('simpleAttributes');
        if (simpleAttributesEl) simpleAttributesEl.classList.remove('hidden');
        
        if (hasColors) {
            const productColorsEl = document.getElementById('productColors');
            if (productColorsEl) productColorsEl.classList.remove('hidden');
            const colorsContainer = document.getElementById('colorsContainer');
            if (colorsContainer) {
                colorsContainer.innerHTML = '';
                product.colors.forEach(color => {
                    const colorElement = document.createElement('div');
                    colorElement.className = 'flex items-center space-x-2 bg-white px-3 py-2 rounded-lg border border-gray-200';
                    
                    // Get color name safely
                    let colorName = 'Unknown';
                    if (typeof color === 'string') {
                        colorName = color;
                    } else if (color && typeof color === 'object') {
                        colorName = color.name || color.label || 'Unknown';
                    }
                    
                    // Get color hex code safely
                    let colorHex = '#ccc';
                    if (typeof color === 'object' && color) {
                        colorHex = color.hex_code || color.color_code || color.color || '#ccc';
                    }
                    // Ensure hex code is a string
                    if (typeof colorHex !== 'string' || !colorHex.match(/^#[0-9A-Fa-f]{3,6}$/)) {
                        colorHex = '#ccc';
                    }
                    
                    colorElement.innerHTML = `
                        <div class="w-6 h-6 rounded-full border-2 border-gray-300" style="background-color: ${colorHex}"></div>
                        <span class="text-sm font-medium text-gray-700">${colorName}</span>
                    `;
                    colorsContainer.appendChild(colorElement);
                });
            }
        } else {
            const productColorsEl = document.getElementById('productColors');
            if (productColorsEl) productColorsEl.classList.add('hidden');
        }
        
        if (hasSizes) {
            const productSizesEl = document.getElementById('productSizes');
            if (productSizesEl) productSizesEl.classList.remove('hidden');
            const sizesContainer = document.getElementById('sizesContainer');
            if (sizesContainer) {
                sizesContainer.innerHTML = '';
                product.sizes.forEach(size => {
                    const sizeElement = document.createElement('span');
                    sizeElement.className = 'inline-flex items-center justify-center w-12 h-12 bg-white text-gray-700 font-bold border-2 border-gray-300 rounded-lg hover:border-blue-400 transition-colors';
                    const sizeName = typeof size === 'object' ? (size.name || size.size || size) : size;
                    sizeElement.textContent = sizeName;
                    sizesContainer.appendChild(sizeElement);
                });
            }
        } else {
            const sizesEl = document.getElementById('productSizes');
            if (sizesEl) sizesEl.classList.add('hidden');
        }
    } else {
        const simpleAttributesEl = document.getElementById('simpleAttributes');
        if (simpleAttributesEl) simpleAttributesEl.classList.add('hidden');
    }
    
    // Descriptions
    const descEl = document.getElementById('productDescription');
    if (descEl) descEl.innerHTML = product.description || '<em class="text-gray-500">No description available</em>';
    
    const shortDescSection = document.getElementById('shortDescription');
    const shortDescEl = document.getElementById('productShortDescription');
    if (shortDescSection && shortDescEl) {
        if (product.short_description) {
            shortDescSection.classList.remove('hidden');
            shortDescEl.innerHTML = product.short_description;
        } else {
            shortDescSection.classList.add('hidden');
        }
    }
    
    // Additional information
    const additionalInfoSection = document.getElementById('additionalInfo');
    const additionalInfoEl = document.getElementById('productAdditionalInfo');
    if (additionalInfoSection && additionalInfoEl) {
        if (product.additional_information && product.additional_information.trim() !== '') {
            additionalInfoSection.classList.remove('hidden');
            additionalInfoEl.innerHTML = product.additional_information;
        } else {
            additionalInfoSection.classList.add('hidden');
        }
    }
    
    // Product Attributes Section - NEW
    populateProductAttributes(product);
    
    // SEO Information
    const metaTitleEl = document.getElementById('productMetaTitle');
    const metaDescEl = document.getElementById('productMetaDescription');
    const slugEl = document.getElementById('productSlug');
    
    if (metaTitleEl) metaTitleEl.textContent = product.meta_title || '-';
    if (metaDescEl) metaDescEl.textContent = product.meta_description || '-';
    if (slugEl) slugEl.textContent = product.slug || '-';
    
    // System information
    const createdAt = product.created_at ? new Date(product.created_at).toLocaleString() : '-';
    const updatedAt = product.updated_at ? new Date(product.updated_at).toLocaleString() : createdAt;
    const createdEl = document.getElementById('productCreated');
    const updatedEl = document.getElementById('productUpdated');
    
    if (createdEl) createdEl.textContent = createdAt;
    if (updatedEl) updatedEl.textContent = updatedAt;
    
    // Product badges
    const badgesContainer = document.getElementById('productBadges');
    if (badgesContainer) {
        badgesContainer.innerHTML = '';
        
        if (product.is_featured) {
            badgesContainer.innerHTML += '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-300 mb-2">⭐ Featured</span>';
        }
        
        if (product.is_new) {
            badgesContainer.innerHTML += '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-300 mb-2">🆕 New</span>';
        }
        
        if (product.is_sale) {
            badgesContainer.innerHTML += '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-300 mb-2">🔥 Sale</span>';
        }
        
        if (product.is_trending) {
            badgesContainer.innerHTML += '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-pink-100 text-pink-800 border border-pink-300 mb-2">📈 Trending</span>';
        }
        
        if (product.is_bestseller) {
            badgesContainer.innerHTML += '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-300 mb-2">👑 Bestseller</span>';
        }
    }
}

// Display Product Variants with Color-Based Images - Frontend Style
let selectedVariantIndex = 0;
let productVariantsData = [];

function displayProductVariants(variants) {
    if (!variants || variants.length === 0) return;
    
    productVariantsData = variants;
    const variantsSection = document.getElementById('productVariantsSection');
    variantsSection.classList.remove('hidden');
    
    // Group variants by color
    const variantsByColor = {};
    variants.forEach((variant, index) => {
        if (variant.color) {
            const colorId = variant.color.id;
            if (!variantsByColor[colorId]) {
                variantsByColor[colorId] = {
                    color: variant.color,
                    variants: [],
                    firstIndex: index
                };
            }
            variantsByColor[colorId].variants.push({...variant, originalIndex: index});
        }
    });
    
    // Create color selector buttons
    const colorButtonsContainer = document.getElementById('colorVariantButtons');
    colorButtonsContainer.innerHTML = '';
    
    Object.values(variantsByColor).forEach((colorGroup, idx) => {
        const button = document.createElement('button');
        button.type = 'button';
        button.className = `group relative flex items-center space-x-3 px-5 py-3 rounded-xl border-2 transition-all duration-200 hover:scale-105 ${
            idx === 0 ? 'border-indigo-500 bg-indigo-50 shadow-lg' : 'border-gray-300 bg-white hover:border-indigo-300'
        }`;
        button.onclick = () => showVariantByColor(colorGroup.firstIndex);
        
        const colorCircle = document.createElement('div');
        colorCircle.className = 'w-8 h-8 rounded-full border-2 border-white shadow-md';
        colorCircle.style.backgroundColor = colorGroup.color.hex_code || '#ccc';
        
        const colorName = document.createElement('span');
        colorName.className = 'font-semibold text-gray-700 group-hover:text-indigo-600';
        colorName.textContent = colorGroup.color?.name || 'Unknown Color';
        
        const variantCount = document.createElement('span');
        variantCount.className = 'text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full';
        variantCount.textContent = `${colorGroup.variants.length} variant${colorGroup.variants.length > 1 ? 's' : ''}`;
        
        button.appendChild(colorCircle);
        button.appendChild(colorName);
        button.appendChild(variantCount);
        colorButtonsContainer.appendChild(button);
    });
    
    // Show first variant by default
    showVariantByColor(0);
}

function showVariantByColor(variantIndex) {
    if (!productVariantsData[variantIndex]) return;
    
    selectedVariantIndex = variantIndex;
    const variant = productVariantsData[variantIndex];
    
    // Update color buttons active state
    const buttons = document.querySelectorAll('#colorVariantButtons button');
    buttons.forEach((btn, idx) => {
        if (productVariantsData[idx] && productVariantsData[idx].color && variant.color && 
            productVariantsData[idx].color.id === variant.color.id) {
            btn.className = 'group relative flex items-center space-x-3 px-5 py-3 rounded-xl border-2 border-indigo-500 bg-indigo-50 shadow-lg transition-all duration-200';
        } else {
            btn.className = 'group relative flex items-center space-x-3 px-5 py-3 rounded-xl border-2 border-gray-300 bg-white hover:border-indigo-300 transition-all duration-200 hover:scale-105';
        }
    });
    
    // Display variant images
    const imagesGallery = document.getElementById('variantImagesGallery');
    imagesGallery.innerHTML = '';
    
    if (variant.images && variant.images.length > 0) {
        variant.images.forEach((imageUrl, idx) => {
            const imgContainer = document.createElement('div');
            imgContainer.className = 'relative group cursor-pointer overflow-hidden rounded-lg border-2 border-gray-200 hover:border-indigo-400 transition-all duration-200';
            
            const img = document.createElement('img');
            img.src = imageUrl;
            img.alt = `${variant.color?.name || 'Variant'} - Image ${idx + 1}`;
            img.className = 'w-full h-48 object-cover group-hover:scale-110 transition-transform duration-300';
            img.onclick = () => {
                // Open image in lightbox or full view
                showImageLightbox(imageUrl);
            };
            
            imgContainer.appendChild(img);
            imagesGallery.appendChild(imgContainer);
        });
    } else {
        imagesGallery.innerHTML = '<p class="text-gray-500 italic col-span-2">No images available for this variant</p>';
    }
    
    // Display variant info
    const variantInfo = document.getElementById('variantInfo');
    variantInfo.innerHTML = '';
    
    const addInfoRow = (icon, label, value, highlighted = false) => {
        const row = document.createElement('div');
        row.className = `flex items-center justify-between p-3 rounded-lg ${
            highlighted ? 'bg-indigo-50 border border-indigo-200' : 'bg-gray-50'
        }`;
        row.innerHTML = `
            <div class="flex items-center space-x-2">
                <span class="text-lg">${icon}</span>
                <span class="text-sm font-bold text-gray-600">${label}</span>
            </div>
            <span class="text-sm font-semibold text-gray-900">${value}</span>
        `;
        variantInfo.appendChild(row);
    };
    
    addInfoRow('🆔', 'Variant SKU', variant.sku || 'N/A', true);
    
    if (variant.color) {
        addInfoRow('🎨', 'Color', `
            <span class="inline-flex items-center space-x-2">
                <span class="w-4 h-4 rounded-full border border-gray-300" style="background-color: ${variant.color.hex_code || '#ccc'}"></span>
                <span>${variant.color.name || 'Unknown'}</span>
            </span>
        `);
    }
    
    if (variant.size) {
        addInfoRow('📏', 'Size', variant.size?.name || variant.size || 'N/A');
    }
    
    addInfoRow('💰', 'Price', `₹${parseFloat(variant.price || 0).toLocaleString('en-IN', {minimumFractionDigits: 2})}`, true);
    addInfoRow('📦', 'Stock', `${variant.stock || 0} units`);
    addInfoRow('✅', 'Status', variant.is_active ? '<span class="text-green-600 font-bold">Active</span>' : '<span class="text-red-600 font-bold">Inactive</span>');
    
    if (variant.is_default) {
        addInfoRow('⭐', 'Default Variant', '<span class="text-yellow-600 font-bold">Yes</span>', true);
    }
}

function showImageLightbox(imageUrl) {
    // Simple lightbox implementation
    const lightbox = document.createElement('div');
    lightbox.className = 'fixed inset-0 z-[100] bg-black bg-opacity-90 flex items-center justify-center p-4';
    lightbox.onclick = () => lightbox.remove();
    
    const img = document.createElement('img');
    img.src = imageUrl;
    img.className = 'max-w-full max-h-full rounded-lg shadow-2xl';
    img.onclick = (e) => e.stopPropagation();
    
    const closeBtn = document.createElement('button');
    closeBtn.className = 'absolute top-4 right-4 text-white bg-black bg-opacity-50 hover:bg-opacity-75 rounded-full p-3 transition-all';
    closeBtn.innerHTML = `
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    `;
    closeBtn.onclick = () => lightbox.remove();
    
    lightbox.appendChild(img);
    lightbox.appendChild(closeBtn);
    document.body.appendChild(lightbox);
}

function populateProductAttributes(product) {
    const attributesSection = document.getElementById('productAttributes');
    if (!attributesSection) return;
    
    // Check if product has any special attributes
    const hasAttributes = product.is_trending || product.is_bestseller || product.is_topsale || 
                         product.is_exclusive || product.is_limited_edition || product.show_in_homepage;
    
    if (hasAttributes) {
        attributesSection.classList.remove('hidden');
        const attributesGrid = document.getElementById('attributesGrid');
        attributesGrid.innerHTML = '';
        
        // Helper function to add attribute card
        const addAttributeCard = (icon, label, value, colorClass) => {
            const card = document.createElement('div');
            card.className = `flex items-center space-x-3 bg-white rounded-xl p-4 border-2 ${colorClass}`;
            card.innerHTML = `
                <div class="text-2xl">${icon}</div>
                <div class="flex-1">
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">${label}</p>
                    <p class="text-lg font-bold ${value ? 'text-green-600' : 'text-gray-400'}">${value ? 'Yes' : 'No'}</p>
                </div>
            `;
            attributesGrid.appendChild(card);
        };
        
        // Add all attribute cards
        addAttributeCard('📈', 'Trending', product.is_trending, product.is_trending ? 'border-pink-300' : 'border-gray-200');
        addAttributeCard('👑', 'Bestseller', product.is_bestseller, product.is_bestseller ? 'border-green-300' : 'border-gray-200');
        addAttributeCard('🔝', 'Top Sale', product.is_topsale, product.is_topsale ? 'border-purple-300' : 'border-gray-200');
        addAttributeCard('💎', 'Exclusive', product.is_exclusive, product.is_exclusive ? 'border-indigo-300' : 'border-gray-200');
        addAttributeCard('⚡', 'Limited Edition', product.is_limited_edition, product.is_limited_edition ? 'border-orange-300' : 'border-gray-200');
        addAttributeCard('🏠', 'Show on Homepage', product.show_in_homepage, product.show_in_homepage ? 'border-blue-300' : 'border-gray-200');
    } else {
        attributesSection.classList.add('hidden');
    }
    
    // Additional Product Details Table
    populateProductDetailsTable(product);
}

function populateProductDetailsTable(product) {
    const detailsTable = document.getElementById('productDetailsTable');
    if (!detailsTable) return;
    
    detailsTable.innerHTML = '';
    
    const addDetailRow = (label, value, highlight = false) => {
        const row = document.createElement('tr');
        row.className = highlight ? 'bg-purple-50' : 'hover:bg-gray-50';
        row.innerHTML = `
            <td class="px-4 py-3 text-sm font-bold text-gray-600 border-b border-gray-200">${label}</td>
            <td class="px-4 py-3 text-sm text-gray-900 border-b border-gray-200 font-medium">${value || '-'}</td>
        `;
        detailsTable.appendChild(row);
    };
    
    // Basic Information
    addDetailRow('Product ID', `#${product.id}`, true);
    addDetailRow('Product Name', product.name);
    addDetailRow('SKU', product.sku || 'N/A');
    addDetailRow('URL Slug', product.slug || '-');
    
    // Pricing Details
    addDetailRow('Regular Price', `₹${parseFloat(product.price || 0).toLocaleString('en-IN', {minimumFractionDigits: 2})}`, true);
    if (product.sale_price) {
        addDetailRow('Sale Price', `₹${parseFloat(product.sale_price).toLocaleString('en-IN', {minimumFractionDigits: 2})}`);
        const discount = ((parseFloat(product.price) - parseFloat(product.sale_price)) / parseFloat(product.price) * 100).toFixed(1);
        addDetailRow('Discount', `${discount}% OFF`);
    }
    if (product.cost_price) {
        addDetailRow('Cost Price', `₹${parseFloat(product.cost_price).toLocaleString('en-IN', {minimumFractionDigits: 2})}`);
    }
    
    // Inventory
    addDetailRow('Stock Quantity', product.stock || '0', true);
    addDetailRow('Stock Status', product.stock_status === 'in_stock' ? '✅ In Stock' : '❌ Out of Stock');
    
    // Category & Organization
    let categoryName = 'Uncategorized';
    if (product.category && product.category !== null) {
        if (typeof product.category === 'object') {
            categoryName = product.category.name || 'Uncategorized';
        } else if (typeof product.category === 'string') {
            categoryName = product.category;
        }
    }
    addDetailRow('Category', categoryName, true);
    
    const brandName = typeof product.brand === 'object' ? product.brand.name : product.brand;
    if (brandName) {
        addDetailRow('Brand', brandName);
    }
    
    // Status & Visibility
    addDetailRow('Status', product.status ? product.status.charAt(0).toUpperCase() + product.status.slice(1) : 'Draft', true);
    addDetailRow('Visibility', product.visibility ? product.visibility.charAt(0).toUpperCase() + product.visibility.slice(1) : 'Visible');
    
    // SEO
    if (product.meta_title) {
        addDetailRow('Meta Title', product.meta_title, true);
    }
    if (product.meta_description) {
        addDetailRow('Meta Description', product.meta_description);
    }
    if (product.focus_keywords) {
        addDetailRow('Focus Keywords', product.focus_keywords);
    }
    if (product.canonical_url) {
        addDetailRow('Canonical URL', product.canonical_url);
    }
    
    // Timestamps
    addDetailRow('Created At', product.created_at || '-', true);
    addDetailRow('Updated At', product.updated_at || '-');
}

function closeProductModal() {
    const modal = document.getElementById('productModal');
    modal.classList.add('hidden');
}

// CSS Animations
const style = document.createElement('style');
style.textContent = `
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

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(50px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
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

    .hidden {
        display: none !important;
    }
`;
document.head.appendChild(style);
</script>