<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    // Banners data from server
    let bannersData = <?php echo json_encode($banners->keyBy('id'), 15, 512) ?>;

    // Initialize Sortable for drag-and-drop
    const el = document.getElementById('banners-list');
    if (el) {
        new Sortable(el, {
            animation: 150,
            handle: '.handle',
            onEnd: function() {
                const items = [];
                document.querySelectorAll('[data-banner-id]').forEach((el, index) => {
                    items.push({
                        id: el.dataset.bannerId,
                        order: index + 1
                    });
                });
                
                fetch('<?php echo e(route("admin.banners.updateOrder")); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                    },
                    body: JSON.stringify({ items })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast(data.message, 'success');
                    }
                });
            }
        });
    }

    // Open Add Modal
    window.openAddModal = function() {
        document.getElementById('modalTitle').textContent = 'Add Hero Banner';
        document.getElementById('bannerForm').reset();
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('bannerId').value = '';
        document.getElementById('desktopImage').required = true;
        document.getElementById('mobileImage').required = true;
        document.getElementById('desktopPreviewContainer').classList.add('hidden');
        document.getElementById('mobilePreviewContainer').classList.add('hidden');
        document.getElementById('bannerIsActive').checked = true;
        
        document.getElementById('bannerModal').classList.remove('hidden');
    };

    // Open Edit Modal
    window.openEditModal = function(id) {
        const banner = bannersData[id];
        if (!banner) return;

        document.getElementById('modalTitle').textContent = 'Edit Hero Banner';
        document.getElementById('formMethod').value = 'PUT';
        document.getElementById('bannerId').value = id;
        
        // Fill form
        document.getElementById('bannerLink').value = banner.link || '';
        document.getElementById('bannerIsActive').checked = banner.is_active;
        
        // Image previews - not required for edit
        document.getElementById('desktopImage').required = false;
        document.getElementById('mobileImage').required = false;
        
        if (banner.desktop_imagekit_url) {
            document.getElementById('desktopPreview').src = banner.desktop_imagekit_url;
            document.getElementById('desktopPreviewContainer').classList.remove('hidden');
        } else if (banner.image) {
            document.getElementById('desktopPreview').src = banner.image;
            document.getElementById('desktopPreviewContainer').classList.remove('hidden');
        } else {
            document.getElementById('desktopPreviewContainer').classList.add('hidden');
        }
        
        if (banner.mobile_imagekit_url) {
            document.getElementById('mobilePreview').src = banner.mobile_imagekit_url;
            document.getElementById('mobilePreviewContainer').classList.remove('hidden');
        } else {
            document.getElementById('mobilePreviewContainer').classList.add('hidden');
        }
        
        document.getElementById('bannerModal').classList.remove('hidden');
    };

    // Close Modal
    window.closeModal = function() {
        document.getElementById('bannerModal').classList.add('hidden');
    };

    // Delete Banner
    window.deleteBanner = function(id) {
        document.getElementById('deleteBannerId').value = id;
        document.getElementById('deleteModal').classList.remove('hidden');
    };

    // Close Delete Modal
    window.closeDeleteModal = function() {
        document.getElementById('deleteModal').classList.add('hidden');
    };

    // Confirm Delete via AJAX
    window.confirmDelete = function() {
        const id = document.getElementById('deleteBannerId').value;
        const deleteBtn = event.target;
        const originalText = deleteBtn.innerHTML;
        
        // Show spinner
        deleteBtn.innerHTML = '<svg class="animate-spin h-5 w-5 inline" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Deleting...';
        deleteBtn.disabled = true;
        
        fetch("<?php echo e(url('admin/banners')); ?>/" + id, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            deleteBtn.innerHTML = originalText;
            deleteBtn.disabled = false;
            
            if (data.success) {
                showToast(data.message, 'success');
                window.closeDeleteModal();
                // Remove row from table
                const row = document.querySelector('[data-banner-id="' + id + '"]');
                if (row) {
                    row.remove();
                }
                // Update stats
                updateStats();
                delete bannersData[id];
            } else {
                showToast(data.message || 'Failed to delete banner', 'error');
            }
        })
        .catch(error => {
            deleteBtn.innerHTML = originalText;
            deleteBtn.disabled = false;
            showToast('Error deleting banner', 'error');
        });
    };

    // Toggle Status
    function toggleStatus(id) {
        const checkbox = event.target;
        fetch("<?php echo e(url('admin/banners')); ?>/" + id + "/toggle", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            },
            body: JSON.stringify({ field: 'is_active', value: checkbox.checked })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                // Update local data
                if (bannersData[id]) {
                    bannersData[id].is_active = checkbox.checked;
                }
                updateStats();
            } else {
                // Revert checkbox
                checkbox.checked = !checkbox.checked;
                showToast(data.message || 'Failed to update status', 'error');
            }
        })
        .catch(error => {
            checkbox.checked = !checkbox.checked;
            showToast('Error updating status', 'error');
        });
    }

    // Desktop Image Preview
    function previewDesktopImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('desktopPreview').src = e.target.result;
                document.getElementById('desktopPreviewContainer').classList.remove('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Mobile Image Preview
    function previewMobileImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('mobilePreview').src = e.target.result;
                document.getElementById('mobilePreviewContainer').classList.remove('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Form Submit via AJAX
    document.getElementById('bannerForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const bannerId = document.getElementById('bannerId').value;
        const isEdit = bannerId && bannerId !== '';
        
        let url = "<?php echo e(route('admin.banners.store')); ?>";
        if (isEdit) {
            url = "<?php echo e(url('admin/banners')); ?>/" + bannerId;
            formData.append('_method', 'PUT');
        }
        
        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<svg class="animate-spin h-5 w-5 mr-2 inline" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Saving...';
        submitBtn.disabled = true;
        
        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            
            if (data.success) {
                showToast(data.message, 'success');
                window.closeModal();
                
                if (isEdit) {
                    // Update existing row
                    updateBannerRow(data.banner);
                    bannersData[data.banner.id] = data.banner;
                } else {
                    // Add new row
                    addBannerRow(data.banner);
                    bannersData[data.banner.id] = data.banner;
                }
                updateStats();
            } else {
                showToast(data.message || 'Failed to save banner', 'error');
            }
        })
        .catch(error => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            showToast('Error saving banner', 'error');
        });
    });

    // Add new banner row to table
    function addBannerRow(banner) {
        const tbody = document.getElementById('banners-list');
        const emptyRow = tbody.querySelector('td[colspan]');
        if (emptyRow) {
            emptyRow.closest('tr').remove();
        }
        
        const row = document.createElement('tr');
        row.setAttribute('data-banner-id', banner.id);
        row.className = 'hover:bg-gray-50 transition-colors';
        row.innerHTML = getBannerRowHTML(banner);
        tbody.appendChild(row);
    }

    // Update existing banner row
    function updateBannerRow(banner) {
        const row = document.querySelector('[data-banner-id="' + banner.id + '"]');
        if (row) {
            row.innerHTML = getBannerRowHTML(banner);
        }
    }

    // Get banner row HTML
    function getBannerRowHTML(banner) {
        const desktopImg = banner.desktop_imagekit_url || banner.image || '';
        const mobileImg = banner.mobile_imagekit_url || '';
        const linkDisplay = banner.link ? '<a href="' + banner.link + '" target="_blank" class="text-sm text-blue-600 hover:underline truncate block max-w-[200px]">' + (banner.link.length > 30 ? banner.link.substring(0, 30) + '...' : banner.link) + '</a>' : '<span class="text-gray-400 text-sm">No link</span>';
        
        return `
            <td class="px-6 py-4">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-400 cursor-grab handle" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
                    </svg>
                    <span class="text-sm font-medium text-gray-700">${banner.order || 1}</span>
                </div>
            </td>
            <td class="px-6 py-4">
                <div class="w-32 h-16 rounded-lg overflow-hidden bg-gray-100">
                    ${desktopImg ? '<img src="' + desktopImg + '" alt="Desktop" class="w-full h-full object-cover">' : '<div class="w-full h-full flex items-center justify-center text-gray-400 text-xs">No Image</div>'}
                </div>
            </td>
            <td class="px-6 py-4">
                <div class="w-16 h-20 rounded-lg overflow-hidden bg-gray-100">
                    ${mobileImg ? '<img src="' + mobileImg + '" alt="Mobile" class="w-full h-full object-cover">' : '<div class="w-full h-full flex items-center justify-center text-gray-400 text-xs">No Image</div>'}
                </div>
            </td>
            <td class="px-6 py-4">${linkDisplay}</td>
            <td class="px-6 py-4 text-center">
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" class="sr-only peer" ${banner.is_active ? 'checked' : ''} onchange="toggleStatus(${banner.id})">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                </label>
            </td>
            <td class="px-6 py-4 text-center">
                <div class="flex items-center justify-center space-x-2">
                    <button onclick="window.openEditModal(${banner.id})" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </button>
                    <button onclick="window.deleteBanner(${banner.id})" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            </td>
        `;
    }

    // Update stats
    function updateStats() {
        let total = Object.keys(bannersData).length;
        let active = Object.values(bannersData).filter(b => b.is_active).length;
        
        const totalEl = document.querySelector('.text-white.text-3xl.font-black');
        const activeEl = document.querySelectorAll('.text-white.text-3xl.font-black')[1];
        
        if (totalEl) totalEl.textContent = total;
        if (activeEl) activeEl.textContent = active;
    }

    // Toast notification
    function showToast(message, type = 'success') {
        const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-4 rounded-lg shadow-lg z-50 transform transition-all duration-300`;
        toast.textContent = message;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // Close modals on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            window.closeModal();
            window.closeDeleteModal();
        }
    });
</script>
<?php /**PATH C:\xampp\htdocs\ESS_Project1\resources\views/admin/banners/scripts.blade.php ENDPATH**/ ?>