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
    .animate-fadeIn { animation: fadeIn 0.2s ease-out forwards; }
    
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(100px) scale(0.95); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }
    .animate-slideUp { animation: slideUp 0.3s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }
    
    ::-webkit-scrollbar { width: 8px; height: 8px; }
    ::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 4px; }
    ::-webkit-scrollbar-thumb { background: linear-gradient(135deg, #9333ea 0%, #ec4899 100%); border-radius: 4px; }
    ::-webkit-scrollbar-thumb:hover { background: linear-gradient(135deg, #7e22ce 0%, #db2777 100%); }
</style>

<script>
    // Core Modal Functions
    function openModal() { document.getElementById('adminModal').classList.remove('hidden'); }
    function closeModal() { document.getElementById('adminModal').classList.add('hidden'); resetForm(); }
    function openViewModal() { document.getElementById('viewAdminModal').classList.remove('hidden'); }
    function closeViewModal() { document.getElementById('viewAdminModal').classList.add('hidden'); }
    
    function resetForm() {
        document.getElementById('modalTitle').textContent = 'Add New Admin';
        document.getElementById('submitBtnText').textContent = 'Create Admin';
        document.getElementById('methodField').value = 'POST';
        document.getElementById('adminId').value = '';
        document.getElementById('adminForm').reset();
        document.getElementById('avatarPreview').classList.add('hidden');
        document.getElementById('passwordRequired').classList.remove('hidden');
        document.getElementById('confirmRequired').classList.remove('hidden');
        document.getElementById('passwordHint').textContent = '';
        document.getElementById('password').required = true;
        clearErrors();
    }
    
    function clearErrors() {
        document.querySelectorAll('.text-red-500').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('input, select').forEach(el => el.classList.remove('border-red-500'));
    }
    
    function openAddModal() {
        resetForm();
        openModal();
    }
    
    // Event Listeners
    document.getElementById('closeModalBtn')?.addEventListener('click', closeModal);
    document.getElementById('cancelModalBtn')?.addEventListener('click', closeModal);
    document.getElementById('modalOverlay')?.addEventListener('click', closeModal);
    document.getElementById('closeViewModalBtn')?.addEventListener('click', closeViewModal);
    document.getElementById('cancelViewModalBtn')?.addEventListener('click', closeViewModal);
    document.getElementById('viewModalOverlay')?.addEventListener('click', closeViewModal);
    document.getElementById('refreshBtn')?.addEventListener('click', () => location.reload());
    
    // Status Toggle
    document.querySelectorAll('.status-toggle').forEach(toggle => {
        toggle.addEventListener('change', function() {
            toggleStatus(this.dataset.id);
        });
    });
    
    // View Admin
    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            viewAdmin(this.dataset.id);
        });
    });
    
    // Edit Admin
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            editAdmin(this.dataset.id);
        });
    });
    
    // Delete Admin
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            deleteAdmin(this.dataset.id, this.dataset.name);
        });
    });
    
    // Form Submit
    document.getElementById('adminForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const submitBtn = document.getElementById('submitBtn');
        const spinner = document.getElementById('loadingSpinner');
        const adminId = document.getElementById('adminId').value;
        const isEdit = adminId !== '';
        
        submitBtn.disabled = true;
        spinner.classList.remove('hidden');
        clearErrors();
        
        const formData = new FormData(this);
        const url = isEdit ? `/admin/admin-users/${adminId}` : '{{ route("admin-users.store") }}';
        
        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Success', data.message, 'success');
                closeModal();
                setTimeout(() => location.reload(), 1500);
            } else if (data.errors) {
                Object.keys(data.errors).forEach(key => {
                    const errorEl = document.getElementById(`${key}-error`);
                    const inputEl = document.getElementById(key);
                    if (errorEl && inputEl) {
                        errorEl.textContent = data.errors[key][0];
                        errorEl.classList.remove('hidden');
                        inputEl.classList.add('border-red-500');
                    }
                });
                showToast('Validation Error', 'Please check the form fields', 'error');
            }
        })
        .catch(error => {
            showToast('Error', 'Something went wrong', 'error');
        })
        .finally(() => {
            submitBtn.disabled = false;
            spinner.classList.add('hidden');
        });
    });
    
    // Functions
    function viewAdmin(id) {
        openViewModal();
        fetch(`/admin/admin-users/${id}`)
            .then(response => response.json())
            .then(data => {
                const admin = data.admin;
                document.getElementById('viewAdminContent').innerHTML = `
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center">
                            ${admin.avatar 
                                ? `<img src="/storage/${admin.avatar}" class="w-32 h-32 rounded-full mx-auto border-4 border-purple-200 shadow-lg">`
                                : `<div class="w-32 h-32 rounded-full mx-auto bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white text-4xl font-bold border-4 border-purple-200">${admin.name.charAt(0).toUpperCase()}</div>`
                            }
                            <h3 class="mt-4 text-xl font-bold text-gray-900">${admin.name}</h3>
                            <p class="text-sm text-gray-500">${admin.email}</p>
                        </div>
                        <div class="md:col-span-2 space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs font-bold text-gray-500 uppercase">Phone</label>
                                    <p class="text-gray-900 font-medium">${admin.phone || 'N/A'}</p>
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-gray-500 uppercase">Role</label>
                                    <p class="text-gray-900 font-medium">${admin.role.name}</p>
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-gray-500 uppercase">Status</label>
                                    <p><span class="inline-flex px-2 py-1 text-xs font-bold rounded-lg ${admin.status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700'}">${admin.status.toUpperCase()}</span></p>
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-gray-500 uppercase">Last Login</label>
                                    <p class="text-gray-900 font-medium">${admin.last_login || 'Never'}</p>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-gray-700 mb-2">Recent Activity</h4>
                                <div class="space-y-2 max-h-40 overflow-y-auto">
                                    ${admin.recent_activities && admin.recent_activities.length > 0 
                                        ? admin.recent_activities.map(activity => `
                                            <div class="flex items-start gap-2 p-2 bg-gray-50 rounded-lg">
                                                <span class="inline-flex px-2 py-0.5 text-xs font-bold rounded bg-purple-100 text-purple-700">${activity.action}</span>
                                                <div class="flex-1">
                                                    <p class="text-xs text-gray-700">${activity.description}</p>
                                                    <p class="text-xs text-gray-500">${activity.created_at}</p>
                                                </div>
                                            </div>
                                        `).join('')
                                        : '<p class="text-sm text-gray-500">No recent activity</p>'
                                    }
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            })
            .catch(error => {
                showToast('Error', 'Failed to load admin details', 'error');
                closeViewModal();
            });
    }
    
    function editAdmin(id) {
        fetch(`/admin/admin-users/${id}/edit`)
            .then(response => response.json())
            .then(data => {
                const admin = data.admin;
                document.getElementById('modalTitle').textContent = 'Edit Admin';
                document.getElementById('submitBtnText').textContent = 'Update Admin';
                document.getElementById('methodField').value = 'PUT';
                document.getElementById('adminId').value = admin.id;
                document.getElementById('name').value = admin.name;
                document.getElementById('email').value = admin.email;
                document.getElementById('phone').value = admin.phone || '';
                document.getElementById('role_id').value = admin.role_id;
                document.querySelector(`input[name="status"][value="${admin.status}"]`).checked = true;
                document.getElementById('password').required = false;
                document.getElementById('passwordRequired').classList.add('hidden');
                document.getElementById('confirmRequired').classList.add('hidden');
                document.getElementById('passwordHint').textContent = 'Leave blank to keep current password';
                if (admin.avatar) {
                    document.getElementById('avatarPreview').classList.remove('hidden');
                    document.getElementById('currentAvatar').src = '/storage/' + admin.avatar;
                }
                openModal();
            })
            .catch(error => {
                showToast('Error', 'Failed to load admin data', 'error');
            });
    }
    
    function toggleStatus(id) {
        fetch(`/admin/admin-users/${id}/toggle-status`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Success', data.message, 'success');
            } else {
                showToast('Error', data.message, 'error');
                location.reload();
            }
        })
        .catch(error => {
            showToast('Error', 'Failed to toggle status', 'error');
            location.reload();
        });
    }
    
    function deleteAdmin(id, name) {
        showConfirmDialog('Delete Admin', `Are you sure you want to delete ${name}? This action cannot be undone.`, () => {
            fetch(`/admin/admin-users/${id}`, {
                method: 'DELETE',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Success', data.message, 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showToast('Error', data.message, 'error');
                }
            })
            .catch(error => {
                showToast('Error', 'Failed to delete admin', 'error');
            });
        });
    }
    
    // Search and Filters
    document.getElementById('globalSearch')?.addEventListener('input', debounce(applyFilters, 500));
    document.getElementById('roleFilter')?.addEventListener('change', applyFilters);
    document.getElementById('statusFilter')?.addEventListener('change', applyFilters);
    document.getElementById('resetFilters')?.addEventListener('click', function() {
        document.getElementById('globalSearch').value = '';
        document.getElementById('roleFilter').value = '';
        document.getElementById('statusFilter').value = '';
        applyFilters();
    });
    
    function applyFilters() {
        const url = new URL(window.location.href);
        url.searchParams.set('search', document.getElementById('globalSearch').value);
        url.searchParams.set('role', document.getElementById('roleFilter').value);
        url.searchParams.set('status', document.getElementById('statusFilter').value);
        window.location.href = url.toString();
    }
    
    function debounce(func, wait) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func(...args), wait);
        };
    }
    
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
                    <button id="confirmBtn" class="flex-1 px-4 py-3 bg-gradient-to-r from-red-500 to-pink-600 text-white font-semibold rounded-xl hover:from-red-600 hover:to-pink-700 transition-all shadow-lg">Delete</button>
                </div>
            </div>
        `;
        overlay.appendChild(dialog);
        document.body.appendChild(overlay);
        const closeDialog = () => { overlay.style.opacity = '0'; dialog.style.transform = 'scale(0.95)'; setTimeout(() => overlay.remove(), 200); };
        dialog.querySelector('#cancelBtn').addEventListener('click', closeDialog);
        overlay.addEventListener('click', (e) => { if (e.target === overlay) closeDialog(); });
        dialog.querySelector('#confirmBtn').addEventListener('click', () => { closeDialog(); onConfirm(); });
    }
    
    function showToast(title, message, type = 'info') {
        const colors = { success: 'from-green-500 to-emerald-600', error: 'from-red-500 to-pink-600', info: 'from-purple-500 to-pink-600', warning: 'from-orange-500 to-amber-600' };
        const icons = {
            success: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
            error: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
            info: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
            warning: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>'
        };
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 z-50 max-w-sm w-full bg-gradient-to-r ${colors[type]} text-white rounded-xl shadow-2xl transform transition-all duration-300 animate-slideIn`;
        toast.innerHTML = `
            <div class="p-4 flex items-start">
                <div class="flex-shrink-0"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">${icons[type]}</svg></div>
                <div class="ml-3 flex-1"><p class="text-sm font-bold">${title}</p><p class="text-xs mt-1 opacity-90">${message}</p></div>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-4 flex-shrink-0 text-white hover:text-gray-200">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                </button>
            </div>
        `;
        document.body.appendChild(toast);
        setTimeout(() => { toast.style.opacity = '0'; toast.style.transform = 'translateX(100%)'; setTimeout(() => toast.remove(), 300); }, 5000);
    }
</script>
