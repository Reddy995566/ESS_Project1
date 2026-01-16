@extends('website.layouts.master')

@section('title', 'My Addresses')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#FAF5ED] to-[#E5E0D8] py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            
            <!-- Sidebar -->
            @include('website.user.partials.sidebar')

            <!-- Main Content -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="font-serif-elegant text-3xl font-bold text-[#3D0C1F]">My Addresses</h2>
                        <button onclick="openAddressModal()" class="px-6 py-2 bg-[#3D0C1F] text-white font-medium rounded-lg hover:bg-[#2a0815] transition-all shadow-lg">
                            + Add New Address
                        </button>
                    </div>
                    
                    <!-- Empty State -->
                    <div class="text-center py-16" id="emptyState">
                        <svg class="w-24 h-24 mx-auto text-gray-300 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <h3 class="text-2xl font-semibold text-gray-700 mb-2">No Addresses Saved</h3>
                        <p class="text-gray-500 mb-6">Add your delivery addresses for faster checkout</p>
                    </div>

                    <!-- Addresses Grid (When addresses exist) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 hidden" id="addressesGrid">
                        <!-- Address cards will be populated here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Address Modal -->
<div id="addressModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200 flex justify-between items-center sticky top-0 bg-white">
            <h3 class="text-2xl font-serif-elegant font-bold text-[#3D0C1F]">Add New Address</h3>
            <button onclick="closeAddressModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form id="addressForm" class="p-6">
            @csrf
            <div class="space-y-4">
                <!-- Full Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                    <input 
                        type="text" 
                        name="full_name" 
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3D0C1F] focus:border-transparent"
                        placeholder="Full Name"
                    >
                </div>

                <!-- Address -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                    <div class="relative">
                        <input 
                            type="text" 
                            name="address" 
                            required
                            class="w-full px-4 py-3 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3D0C1F] focus:border-transparent"
                            placeholder="Address"
                        >
                        <svg class="w-5 h-5 text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Apartment -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Apartment, suite, etc. (optional)</label>
                    <input 
                        type="text" 
                        name="apartment" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3D0C1F] focus:border-transparent"
                        placeholder="Apartment, suite, etc. (optional)"
                    >
                </div>

                <!-- City, State, PIN -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">City</label>
                        <input 
                            type="text" 
                            name="city" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3D0C1F] focus:border-transparent"
                            placeholder="City"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">State</label>
                        <select 
                            name="state" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3D0C1F] focus:border-transparent"
                        >
                            <option value="">Select State</option>
                            <option value="Andhra Pradesh">Andhra Pradesh</option>
                            <option value="Arunachal Pradesh">Arunachal Pradesh</option>
                            <option value="Assam">Assam</option>
                            <option value="Bihar">Bihar</option>
                            <option value="Chhattisgarh">Chhattisgarh</option>
                            <option value="Goa">Goa</option>
                            <option value="Gujarat">Gujarat</option>
                            <option value="Haryana">Haryana</option>
                            <option value="Himachal Pradesh">Himachal Pradesh</option>
                            <option value="Jharkhand">Jharkhand</option>
                            <option value="Karnataka">Karnataka</option>
                            <option value="Kerala">Kerala</option>
                            <option value="Madhya Pradesh">Madhya Pradesh</option>
                            <option value="Maharashtra">Maharashtra</option>
                            <option value="Manipur">Manipur</option>
                            <option value="Meghalaya">Meghalaya</option>
                            <option value="Mizoram">Mizoram</option>
                            <option value="Nagaland">Nagaland</option>
                            <option value="Odisha">Odisha</option>
                            <option value="Punjab">Punjab</option>
                            <option value="Rajasthan">Rajasthan</option>
                            <option value="Sikkim">Sikkim</option>
                            <option value="Tamil Nadu">Tamil Nadu</option>
                            <option value="Telangana">Telangana</option>
                            <option value="Tripura">Tripura</option>
                            <option value="Uttar Pradesh">Uttar Pradesh</option>
                            <option value="Uttarakhand">Uttarakhand</option>
                            <option value="West Bengal">West Bengal</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">PIN code</label>
                        <input 
                            type="text" 
                            name="pincode" 
                            required
                            pattern="[0-9]{6}"
                            maxlength="6"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3D0C1F] focus:border-transparent"
                            placeholder="PIN code"
                        >
                    </div>
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                    <div class="relative">
                        <input 
                            type="tel" 
                            name="phone" 
                            required
                            pattern="[0-9]{10}"
                            maxlength="10"
                            class="w-full px-4 py-3 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3D0C1F] focus:border-transparent"
                            placeholder="Phone number"
                        >
                        <button type="button" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Default Address Checkbox -->
                <div class="flex items-center">
                    <input 
                        type="checkbox" 
                        name="is_default" 
                        id="is_default"
                        class="w-4 h-4 text-[#3D0C1F] border-gray-300 rounded focus:ring-[#3D0C1F]"
                    >
                    <label for="is_default" class="ml-2 text-sm text-gray-700">Set as default address</label>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex gap-4 mt-6 pt-6 border-t border-gray-200">
                <button 
                    type="submit" 
                    id="saveAddressBtn"
                    class="flex-1 px-6 py-3 bg-[#3D0C1F] text-white font-medium rounded-lg hover:bg-[#2a0815] transition-all shadow-lg"
                >
                    Save Address
                </button>
                <button 
                    type="button" 
                    onclick="closeAddressModal()"
                    class="px-6 py-3 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition-all"
                >
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Toast Notification Container -->
<div id="toastContainer" class="fixed top-4 right-4 z-50 space-y-2"></div>

<script>
// Store addresses loaded from database
let addresses = [];
let editingAddressId = null;

// Toast notification function
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `transform transition-all duration-300 ease-in-out px-6 py-4 rounded-lg shadow-lg flex items-center gap-3 ${
        type === 'success' ? 'bg-[#3D0C1F]' : 'bg-[#8B2635]'
    } text-white`;
    
    toast.innerHTML = `
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            ${type === 'success' 
                ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>'
                : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>'
            }
        </svg>
        <span>${message}</span>
    `;
    
    const container = document.getElementById('toastContainer');
    container.appendChild(toast);
    
    // Animate in
    setTimeout(() => toast.classList.add('translate-x-0'), 10);
    
    // Remove after 3 seconds
    setTimeout(() => {
        toast.classList.add('opacity-0', 'translate-x-full');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Load addresses from database on page load
document.addEventListener('DOMContentLoaded', async function() {
    await loadAddresses();
});

// Load addresses from server
async function loadAddresses() {
    try {
        const response = await fetch('{{ route("api.addresses.index") }}');
        const result = await response.json();
        
        if (result.success) {
            addresses = result.addresses;
            updateAddressesDisplay();
            console.log('✅ Loaded', addresses.length, 'addresses from database');
        }
    } catch (error) {
        console.error('❌ Error loading addresses:', error);
    }
}

function openAddressModal(addressId = null) {
    editingAddressId = addressId;
    const modal = document.getElementById('addressModal');
    const form = document.getElementById('addressForm');
    const title = modal.querySelector('h3');
    
    if (addressId) {
        // Edit mode
        title.textContent = 'Edit Address';
        const address = addresses.find(a => a.id === addressId);
        if (address) {
            form.full_name.value = address.full_name;
            form.address.value = address.address;
            form.apartment.value = address.apartment || '';
            form.city.value = address.city;
            form.state.value = address.state;
            form.pincode.value = address.pincode;
            form.phone.value = address.phone;
            form.is_default.checked = address.is_default;
        }
    } else {
        // Add mode
        title.textContent = 'Add New Address';
        form.reset();
    }
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeAddressModal() {
    document.getElementById('addressModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    document.getElementById('addressForm').reset();
    editingAddressId = null;
}

// Close modal on outside click
document.getElementById('addressModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeAddressModal();
    }
});

// Render address card
function renderAddressCard(address, index) {
    const fullAddress = address.apartment 
        ? `${address.address}, ${address.apartment}` 
        : address.address;
    
    return `
        <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow relative">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="font-semibold text-lg text-gray-900">Address ${index + 1}</h3>
                    ${address.is_default ? '<span class="inline-block px-3 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full mt-1">Default</span>' : ''}
                </div>
                <button onclick="deleteAddress(${address.id})" class="text-gray-400 hover:text-red-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </div>
            
            <div class="text-gray-600 space-y-1 mb-4">
                <p class="font-medium text-gray-900">${address.full_name}</p>
                <p>${fullAddress}</p>
                <p>${address.city}, ${address.state} - ${address.pincode}</p>
                <p>Phone: ${address.phone}</p>
            </div>
            
            <div class="flex gap-3 pt-4 border-t border-gray-200">
                <button onclick="openAddressModal(${address.id})" class="flex-1 px-4 py-2 border border-[#3D0C1F] text-[#3D0C1F] rounded-lg hover:bg-[#3D0C1F] hover:text-white transition-all">
                    Edit
                </button>
                ${!address.is_default ? `<button onclick="setDefaultAddress(${address.id})" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all">Set as Default</button>` : ''}
            </div>
        </div>
    `;
}

// Update addresses display
function updateAddressesDisplay() {
    const grid = document.getElementById('addressesGrid');
    const emptyState = document.getElementById('emptyState');
    
    console.log('🔄 Updating display. Addresses count:', addresses.length);
    
    if (addresses.length === 0) {
        grid.classList.add('hidden');
        emptyState.classList.remove('hidden');
    } else {
        emptyState.classList.add('hidden');
        grid.classList.remove('hidden');
        grid.innerHTML = addresses.map((addr, idx) => renderAddressCard(addr, idx)).join('');
    }
}

// Delete address
async function deleteAddress(id) {
    if (!confirm('Are you sure you want to delete this address?')) return;
    
    try {
        const response = await fetch(`/api/addresses/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            await loadAddresses();
            showToast('Address deleted successfully!', 'success');
        } else {
            showToast('Failed to delete address', 'error');
        }
    } catch (error) {
        console.error('❌ Error deleting address:', error);
        showToast('Failed to delete address', 'error');
    }
}

// Set default address
async function setDefaultAddress(id) {
    try {
        const response = await fetch(`/api/addresses/${id}/set-default`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            await loadAddresses();
            showToast('Default address updated!', 'success');
        } else {
            showToast('Failed to update default address', 'error');
        }
    } catch (error) {
        console.error('❌ Error updating default:', error);
        showToast('Failed to update default address', 'error');
    }
}

// Form submission
document.getElementById('addressForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const btn = document.getElementById('saveAddressBtn');
    const originalText = btn.innerText;
    btn.innerText = 'Saving...';
    btn.disabled = true;

    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());
    data.is_default = formData.get('is_default') ? true : false;

    try {
        const url = editingAddressId 
            ? `/api/addresses/${editingAddressId}`
            : '{{ route("api.addresses.store") }}';
        
        const method = editingAddressId ? 'PUT' : 'POST';
        
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
        });

        // Check if response is JSON
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            console.error('❌ Server returned non-JSON response');
            const text = await response.text();
            console.error('Response:', text.substring(0, 200));
            
            if (response.status === 401 || text.includes('login')) {
                showToast('Please login first', 'error');
                setTimeout(() => window.location.href = '{{ route("login") }}', 1500);
            } else {
                showToast('Server error occurred', 'error');
            }
            return;
        }

        const result = await response.json();

        if (result.success) {
            showToast(editingAddressId ? 'Address updated successfully!' : 'Address saved successfully!', 'success');
            await loadAddresses();
            closeAddressModal();
        } else {
            if (result.errors) {
                const firstError = Object.values(result.errors)[0][0];
                showToast(firstError, 'error');
            } else {
                showToast(result.message || 'Failed to save address', 'error');
            }
        }
        
    } catch (error) {
        console.error('❌ Error saving address:', error);
        showToast('Failed to save address', 'error');
    } finally {
        btn.innerText = originalText;
        btn.disabled = false;
    }
});
</script>
@endsection
