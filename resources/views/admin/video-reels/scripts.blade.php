<script>
    // Searchable Product Dropdown Functions
    let productDropdownOpen = false;

    window.toggleProductDropdown = function() {
        const dropdown = document.getElementById('productDropdown');
        const arrow = document.getElementById('productDropdownArrow');
        productDropdownOpen = !productDropdownOpen;
        
        if (productDropdownOpen) {
            dropdown.classList.remove('hidden');
            arrow.style.transform = 'rotate(180deg)';
            document.getElementById('productSearchInput').focus();
        } else {
            dropdown.classList.add('hidden');
            arrow.style.transform = 'rotate(0deg)';
        }
    }

    window.selectProduct = function(id, name, price) {
        document.getElementById('product_id').value = id;
        document.getElementById('selectedProductText').textContent = name + ' - Rs. ' + price;
        document.getElementById('selectedProductText').classList.remove('text-gray-500');
        document.getElementById('selectedProductText').classList.add('text-gray-900');
        toggleProductDropdown();
        document.getElementById('productSearchInput').value = '';
        filterProducts('');
    }

    window.filterProducts = function(searchTerm) {
        const options = document.querySelectorAll('.product-option');
        const noResults = document.getElementById('noProductResults');
        let visibleCount = 0;
        
        searchTerm = searchTerm.toLowerCase().trim();
        
        options.forEach(option => {
            const name = option.dataset.name.toLowerCase();
            const price = option.dataset.price.toLowerCase();
            
            if (name.includes(searchTerm) || price.includes(searchTerm)) {
                option.classList.remove('hidden');
                visibleCount++;
            } else {
                option.classList.add('hidden');
            }
        });
        
        if (visibleCount === 0) {
            noResults.classList.remove('hidden');
        } else {
            noResults.classList.add('hidden');
        }
    }

    window.resetProductSelect = function() {
        document.getElementById('product_id').value = '';
        document.getElementById('selectedProductText').textContent = '-- Select Product --';
        document.getElementById('selectedProductText').classList.add('text-gray-500');
        document.getElementById('selectedProductText').classList.remove('text-gray-900');
        document.getElementById('productSearchInput').value = '';
        filterProducts('');
    }

    window.setProductSelect = function(id) {
        const option = document.querySelector(`.product-option[data-id="${id}"]`);
        if (option) {
            document.getElementById('product_id').value = id;
            document.getElementById('selectedProductText').textContent = option.dataset.name + ' - Rs. ' + option.dataset.price;
            document.getElementById('selectedProductText').classList.remove('text-gray-500');
            document.getElementById('selectedProductText').classList.add('text-gray-900');
        }
    }

    // Modal Functions - Global scope
    window.openAddModal = function() {
        document.getElementById('modalTitle').textContent = 'Add New Video Reel';
        document.getElementById('reelForm').action = '{{ route("admin.video-reels.store") }}';
        document.getElementById('methodField').value = 'POST';
        document.getElementById('reelId').value = '';
        resetProductSelect();
        document.getElementById('video_url').value = '';
        document.getElementById('video_file_id').value = '';
        document.getElementById('video_file').value = '';
        document.getElementById('title').value = '';
        document.getElementById('badge').value = '';
        document.getElementById('badge_color').value = '#3D5A3C';
        document.getElementById('views_count').value = '0';
        document.getElementById('sort_order').value = '0';
        document.getElementById('is_active').checked = true;
        document.getElementById('autoplay').checked = false;
        document.getElementById('videoUploadProgress').classList.add('hidden');
        document.getElementById('videoUploadSuccess').classList.add('hidden');
        document.getElementById('reelModal').classList.remove('hidden');
    }

    window.openEditModal = function(data) {
        document.getElementById('modalTitle').textContent = 'Edit Video Reel';
        document.getElementById('reelForm').action = `/admin/video-reels/${data.id}`;
        document.getElementById('methodField').value = 'PUT';
        document.getElementById('reelId').value = data.id;
        setProductSelect(data.productId);
        document.getElementById('video_url').value = data.videoUrl;
        document.getElementById('video_file_id').value = '';
        document.getElementById('video_file').value = '';
        document.getElementById('title').value = data.title || '';
        document.getElementById('badge').value = data.badge || '';
        document.getElementById('badge_color').value = data.badgeColor || '#3D5A3C';
        document.getElementById('views_count').value = data.viewsCount || '0';
        document.getElementById('sort_order').value = data.sortOrder || '0';
        document.getElementById('is_active').checked = data.isActive === 'true';
        document.getElementById('autoplay').checked = data.autoplay === 'true';
        document.getElementById('videoUploadProgress').classList.add('hidden');
        document.getElementById('videoUploadSuccess').classList.remove('hidden');
        document.getElementById('reelModal').classList.remove('hidden');
    }

    window.closeModal = function() {
        document.getElementById('reelModal').classList.add('hidden');
        // Close product dropdown if open
        if (productDropdownOpen) {
            toggleProductDropdown();
        }
    }

    function showToast(message, type = 'success') {
        let container = document.getElementById('toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toast-container';
            container.className = 'fixed top-4 right-4 z-50 space-y-2';
            document.body.appendChild(container);
        }
        const toast = document.createElement('div');
        const bg = type === 'success' ? 'from-green-500 to-emerald-600' : 'from-red-500 to-pink-600';
        toast.className = `bg-gradient-to-r ${bg} text-white px-6 py-4 rounded-xl shadow-2xl flex items-center space-x-3 transform transition-all duration-300 translate-x-full`;
        toast.innerHTML = `<span class="font-semibold">${message}</span>`;
        container.appendChild(toast);
        setTimeout(() => toast.classList.remove('translate-x-full'), 100);
        setTimeout(() => { toast.classList.add('translate-x-full'); setTimeout(() => toast.remove(), 300); }, 3000);
    }

    function showConfirmDialog(title, message, onConfirm) {
        const overlay = document.createElement('div');
        overlay.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center';
        overlay.innerHTML = `
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 p-6">
                <h3 class="text-xl font-bold text-gray-900 text-center mb-2">${title}</h3>
                <p class="text-gray-600 text-center mb-6">${message}</p>
                <div class="flex gap-3">
                    <button id="cancelDlg" class="flex-1 px-4 py-3 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200">Cancel</button>
                    <button id="confirmDlg" class="flex-1 px-4 py-3 bg-red-500 text-white font-semibold rounded-xl hover:bg-red-600">Delete</button>
                </div>
            </div>
        `;
        document.body.appendChild(overlay);
        overlay.querySelector('#cancelDlg').onclick = () => overlay.remove();
        overlay.querySelector('#confirmDlg').onclick = () => { overlay.remove(); onConfirm(); };
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Product Search Input Handler
        const productSearchInput = document.getElementById('productSearchInput');
        if (productSearchInput) {
            productSearchInput.addEventListener('input', function() {
                filterProducts(this.value);
            });
            productSearchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    toggleProductDropdown();
                }
            });
        }

        // Product Option Click Handlers
        document.querySelectorAll('.product-option').forEach(option => {
            option.addEventListener('click', function() {
                selectProduct(this.dataset.id, this.dataset.name, this.dataset.price);
            });
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            const wrapper = document.getElementById('productSelectWrapper');
            if (wrapper && !wrapper.contains(e.target) && productDropdownOpen) {
                toggleProductDropdown();
            }
        });

        // Video Upload Handler
        const videoFileInput = document.getElementById('video_file');
        if (videoFileInput) {
            videoFileInput.addEventListener('change', function() {
                const file = this.files[0];
                if (!file) return;

                if (file.size > 52428800) {
                    showToast('Video file must be less than 50MB', 'error');
                    this.value = '';
                    return;
                }

                document.getElementById('videoUploadProgress').classList.remove('hidden');
                document.getElementById('videoUploadSuccess').classList.add('hidden');

                const formData = new FormData();
                formData.append('video', file);
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

                const xhr = new XMLHttpRequest();
                xhr.open('POST', '{{ route("admin.video-reels.upload-video") }}', true);

                xhr.upload.onprogress = function(e) {
                    if (e.lengthComputable) {
                        const percent = Math.round((e.loaded / e.total) * 100);
                        document.getElementById('videoUploadPercent').textContent = percent + '%';
                        document.getElementById('videoProgressBar').style.width = percent + '%';
                    }
                };

                xhr.onload = function() {
                    if (xhr.status === 200) {
                        const response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            document.getElementById('video_url').value = response.url;
                            document.getElementById('video_file_id').value = response.file_id || '';
                            document.getElementById('videoUploadProgress').classList.add('hidden');
                            document.getElementById('videoUploadSuccess').classList.remove('hidden');
                            showToast('Video uploaded successfully!');
                        } else {
                            document.getElementById('videoUploadProgress').classList.add('hidden');
                            showToast(response.message || 'Upload failed', 'error');
                        }
                    } else {
                        document.getElementById('videoUploadProgress').classList.add('hidden');
                        showToast('Upload failed', 'error');
                    }
                };

                xhr.onerror = function() {
                    document.getElementById('videoUploadProgress').classList.add('hidden');
                    showToast('Network error', 'error');
                };

                xhr.send(formData);
            });
        }

        // Close modal
        document.getElementById('closeModalBtn')?.addEventListener('click', closeModal);
        document.getElementById('cancelBtn')?.addEventListener('click', closeModal);
        document.getElementById('modalOverlay')?.addEventListener('click', closeModal);

        // Filters
        const globalSearch = document.getElementById('globalSearch');
        const statusFilter = document.getElementById('statusFilter');

        function applyFilters() {
            const params = new URLSearchParams(window.location.search);
            if (globalSearch?.value.trim()) params.set('search', globalSearch.value.trim());
            else params.delete('search');
            if (statusFilter?.value !== '') params.set('status', statusFilter.value);
            else params.delete('status');
            params.set('page', '1');
            window.location.href = `${window.location.pathname}?${params.toString()}`;
        }

        let searchTimeout;
        globalSearch?.addEventListener('input', () => { clearTimeout(searchTimeout); searchTimeout = setTimeout(applyFilters, 500); });
        statusFilter?.addEventListener('change', applyFilters);
        document.getElementById('resetFilters')?.addEventListener('click', () => { window.location.href = window.location.pathname; });

        const urlParams = new URLSearchParams(window.location.search);
        if (globalSearch && urlParams.has('search')) globalSearch.value = urlParams.get('search');
        if (statusFilter && urlParams.has('status')) statusFilter.value = urlParams.get('status');

        // Edit buttons
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                openEditModal({
                    id: this.dataset.id,
                    productId: this.dataset.productId,
                    videoUrl: this.dataset.videoUrl,
                    title: this.dataset.title,
                    badge: this.dataset.badge,
                    badgeColor: this.dataset.badgeColor,
                    viewsCount: this.dataset.viewsCount,
                    sortOrder: this.dataset.sortOrder,
                    isActive: this.dataset.isActive,
                    autoplay: this.dataset.autoplay
                });
            });
        });

        // Delete buttons
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                const name = this.dataset.name;
                showConfirmDialog('Delete Video Reel?', `Are you sure you want to delete reel for "${name}"?`, () => {
                    fetch(`/admin/video-reels/${id}`, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) { showToast('Deleted successfully!'); setTimeout(() => location.reload(), 1000); }
                        else showToast(data.message, 'error');
                    });
                });
            });
        });

        // Toggle switches
        document.querySelectorAll('.feature-toggle').forEach(toggle => {
            toggle.addEventListener('change', function() {
                fetch(`/admin/video-reels/${this.dataset.id}/toggle`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify({ field: this.dataset.field, value: this.checked ? 1 : 0 })
                })
                .then(res => res.json())
                .then(data => { if (data.success) showToast(data.message); else { this.checked = !this.checked; showToast(data.message, 'error'); } });
            });
        });

        document.getElementById('refreshBtn')?.addEventListener('click', () => location.reload());

        document.getElementById('selectAll')?.addEventListener('change', function() {
            document.querySelectorAll('.row-select').forEach(cb => cb.checked = this.checked);
            updateBulkBtn();
        });
        document.querySelectorAll('.row-select').forEach(cb => cb.addEventListener('change', updateBulkBtn));

        document.getElementById('applyBulkAction')?.addEventListener('click', function() {
            const action = document.getElementById('bulkAction')?.value;
            const ids = Array.from(document.querySelectorAll('.row-select:checked')).map(cb => cb.value);
            if (!action || !ids.length) return showToast('Select action and items', 'error');
            if (action === 'delete') {
                showConfirmDialog('Delete Selected?', `Delete ${ids.length} reel(s)?`, () => doBulk(action, ids));
            } else doBulk(action, ids);
        });

        function doBulk(action, ids) {
            fetch('/admin/video-reels/bulk-action', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                body: JSON.stringify({ action, ids })
            })
            .then(res => res.json())
            .then(data => { if (data.success) { showToast(data.message); setTimeout(() => location.reload(), 1000); } else showToast(data.message, 'error'); });
        }

        function updateBulkBtn() {
            const count = document.querySelectorAll('.row-select:checked').length;
            const btn = document.getElementById('applyBulkAction');
            if (btn) { btn.disabled = count === 0; btn.textContent = count > 0 ? `Apply (${count})` : 'Apply'; }
        }
    });
</script>
