<script>
    // Banners data from server
    const bannersData = @json($banners->keyBy('id'));

    // Open Add Modal
    window.openAddModal = function() {
        document.getElementById('modalTitle').textContent = 'Add Promotional Banner';
        document.getElementById('bannerForm').action = "{{ route('admin.promotional-banners.store') }}";
        document.getElementById('formMethod').value = 'POST';
        
        // Reset form
        document.getElementById('bannerForm').reset();
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

        document.getElementById('modalTitle').textContent = 'Edit Promotional Banner';
        document.getElementById('bannerForm').action = "{{ url('admin/promotional-banners') }}/" + id;
        document.getElementById('formMethod').value = 'PUT';
        
        // Fill form
        document.getElementById('bannerLink').value = banner.link || '';
        document.getElementById('bannerPosition').value = banner.position || 'after_hot_collection';
        document.getElementById('bannerSortOrder').value = banner.sort_order || 0;
        document.getElementById('bannerIsActive').checked = banner.is_active;
        
        // Format dates for datetime-local input
        if (banner.start_date) {
            const startDate = new Date(banner.start_date);
            document.getElementById('bannerStartDate').value = startDate.toISOString().slice(0, 16);
        } else {
            document.getElementById('bannerStartDate').value = '';
        }
        
        if (banner.end_date) {
            const endDate = new Date(banner.end_date);
            document.getElementById('bannerEndDate').value = endDate.toISOString().slice(0, 16);
        } else {
            document.getElementById('bannerEndDate').value = '';
        }
        
        // Image previews - not required for edit
        document.getElementById('desktopImage').required = false;
        document.getElementById('mobileImage').required = false;
        
        if (banner.desktop_imagekit_url) {
            document.getElementById('desktopPreview').src = banner.desktop_imagekit_url;
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
        document.getElementById('deleteForm').action = "{{ url('admin/promotional-banners') }}/" + id;
        document.getElementById('deleteModal').classList.remove('hidden');
    };

    // Close Delete Modal
    window.closeDeleteModal = function() {
        document.getElementById('deleteModal').classList.add('hidden');
    };

    // Toggle Status
    function toggleStatus(id) {
        fetch("{{ url('admin/promotional-banners') }}/" + id + "/toggle-status", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
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

    // Close modals on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            window.closeModal();
            window.closeDeleteModal();
        }
    });
</script>
