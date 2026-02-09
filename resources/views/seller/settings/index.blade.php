@extends('seller.layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Settings</h1>
            <p class="mt-2 text-gray-600">Manage your account and business settings</p>
        </div>

        <!-- Tab Navigation -->
        <div class="mb-8">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8">
                    <button onclick="switchTab('profile')" class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                        <i class="fas fa-user mr-2"></i>Profile
                    </button>
                    <button onclick="switchTab('business')" class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                        <i class="fas fa-building mr-2"></i>Business Details
                    </button>
                    <button onclick="switchTab('bank')" class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                        <i class="fas fa-university mr-2"></i>Bank Details
                    </button>
                    <button onclick="switchTab('password')" class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                        <i class="fas fa-lock mr-2"></i>Change Password
                    </button>
                    <button onclick="switchTab('notifications')" class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                        <i class="fas fa-bell mr-2"></i>Notifications
                    </button>
                </nav>
            </div>
        </div>

        <!-- Profile Tab -->
        <div id="profile-tab" class="tab-content">
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Profile Information</h3>
                    <p class="mt-1 text-sm text-gray-500">Update your personal information</p>
                </div>
                <div class="px-6 py-6">
                    <form id="profileForm" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Name *</label>
                                <input type="text" name="name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" value="{{ $user->name }}" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                                <input type="email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" value="{{ $user->email }}" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Mobile *</label>
                                <input type="text" name="mobile" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" value="{{ $user->mobile }}" required maxlength="10">
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Business Tab -->
        <div id="business-tab" class="tab-content hidden">
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Business Information</h3>
                    <p class="mt-1 text-sm text-gray-500">Update your business details</p>
                </div>
                <div class="px-6 py-6">
                    <form id="businessForm" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Business Name *</label>
                                <input type="text" name="business_name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" value="{{ $seller->business_name }}" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Business Type *</label>
                                <select name="business_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                                    <option value="individual" {{ $seller->business_type == 'individual' ? 'selected' : '' }}>Individual</option>
                                    <option value="company" {{ $seller->business_type == 'company' ? 'selected' : '' }}>Company</option>
                                    <option value="partnership" {{ $seller->business_type == 'partnership' ? 'selected' : '' }}>Partnership</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Business Email *</label>
                                <input type="email" name="business_email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" value="{{ $seller->business_email }}" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Business Phone *</label>
                                <input type="text" name="business_phone" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" value="{{ $seller->business_phone }}" required maxlength="10">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">GST Number</label>
                                <input type="text" name="gst_number" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" value="{{ $seller->gst_number }}" maxlength="15">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">PAN Number *</label>
                                <input type="text" name="pan_number" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" value="{{ $seller->pan_number }}" required maxlength="10">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Business Address *</label>
                            <textarea name="business_address" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" rows="3" required>{{ $seller->business_address }}</textarea>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">City *</label>
                                <input type="text" name="business_city" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" value="{{ $seller->business_city }}" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">State *</label>
                                <input type="text" name="business_state" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" value="{{ $seller->business_state }}" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pincode *</label>
                                <input type="text" name="business_pincode" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" value="{{ $seller->business_pincode }}" required maxlength="6">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Business Description</label>
                            <textarea name="description" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" rows="3">{{ $seller->description }}</textarea>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                Update Business Details
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Bank Tab -->
        <div id="bank-tab" class="tab-content hidden">
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Bank Account Details</h3>
                    <p class="mt-1 text-sm text-gray-500">Update your bank account information</p>
                </div>
                <div class="px-6 py-6">
                    @if($bankDetails && $bankDetails->is_verified)
                    <div class="mb-6 bg-green-50 border border-green-200 rounded-md p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-green-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">Your bank details are verified</p>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="mb-6 bg-yellow-50 border border-yellow-200 rounded-md p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-yellow-800">Your bank details are pending verification</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <form id="bankForm" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Account Holder Name *</label>
                                <input type="text" name="account_holder_name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" value="{{ $bankDetails->account_holder_name ?? '' }}" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Account Number *</label>
                                <input type="text" name="account_number" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" value="{{ $bankDetails->account_number ?? '' }}" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">IFSC Code *</label>
                                <input type="text" name="ifsc_code" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" value="{{ $bankDetails->ifsc_code ?? '' }}" required maxlength="11">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Bank Name *</label>
                                <input type="text" name="bank_name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" value="{{ $bankDetails->bank_name ?? '' }}" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Branch Name *</label>
                                <input type="text" name="branch_name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" value="{{ $bankDetails->branch_name ?? '' }}" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Account Type *</label>
                                <select name="account_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                                    <option value="savings" {{ ($bankDetails->account_type ?? '') == 'savings' ? 'selected' : '' }}>Savings</option>
                                    <option value="current" {{ ($bankDetails->account_type ?? '') == 'current' ? 'selected' : '' }}>Current</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">UPI ID</label>
                                <input type="text" name="upi_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" value="{{ $bankDetails->upi_id ?? '' }}">
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                Update Bank Details
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Password Tab -->
        <div id="password-tab" class="tab-content hidden">
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Change Password</h3>
                    <p class="mt-1 text-sm text-gray-500">Update your account password</p>
                </div>
                <div class="px-6 py-6">
                    <form id="passwordForm" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Current Password *</label>
                                <input type="password" name="current_password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">New Password *</label>
                                <input type="password" name="new_password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required minlength="8">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password *</label>
                                <input type="password" name="new_password_confirmation" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required minlength="8">
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                Change Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Notifications Tab -->
        <div id="notifications-tab" class="tab-content hidden">
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Notification Preferences</h3>
                    <p class="mt-1 text-sm text-gray-500">Manage your notification settings</p>
                </div>
                <div class="px-6 py-6">
                    <form id="notificationsForm" class="space-y-8">
                        @csrf
                        <div>
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Email Notifications</h4>
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <input type="checkbox" name="email_new_order" id="email_new_order" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" checked>
                                    <label for="email_new_order" class="ml-3 text-sm font-medium text-gray-700">New Order</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" name="email_product_approved" id="email_product_approved" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" checked>
                                    <label for="email_product_approved" class="ml-3 text-sm font-medium text-gray-700">Product Approved</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" name="email_product_rejected" id="email_product_rejected" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" checked>
                                    <label for="email_product_rejected" class="ml-3 text-sm font-medium text-gray-700">Product Rejected</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" name="email_payout_processed" id="email_payout_processed" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" checked>
                                    <label for="email_payout_processed" class="ml-3 text-sm font-medium text-gray-700">Payout Processed</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" name="email_low_stock" id="email_low_stock" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" checked>
                                    <label for="email_low_stock" class="ml-3 text-sm font-medium text-gray-700">Low Stock Alert</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" name="email_new_review" id="email_new_review" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" checked>
                                    <label for="email_new_review" class="ml-3 text-sm font-medium text-gray-700">New Review</label>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4 class="text-lg font-medium text-gray-900 mb-4">SMS Notifications</h4>
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <input type="checkbox" name="sms_new_order" id="sms_new_order" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label for="sms_new_order" class="ml-3 text-sm font-medium text-gray-700">New Order</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" name="sms_payout_processed" id="sms_payout_processed" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label for="sms_payout_processed" class="ml-3 text-sm font-medium text-gray-700">Payout Processed</label>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                Save Preferences
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Tab switching functionality
function switchTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.add('hidden');
    });
    
    // Remove active class from all tab buttons
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('border-indigo-500', 'text-indigo-600');
        btn.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab content
    document.getElementById(tabName + '-tab').classList.remove('hidden');
    
    // Add active class to selected tab button
    event.target.classList.remove('border-transparent', 'text-gray-500');
    event.target.classList.add('border-indigo-500', 'text-indigo-600');
}

// Set first tab as active on page load
document.addEventListener('DOMContentLoaded', function() {
    document.querySelector('.tab-btn').classList.remove('border-transparent', 'text-gray-500');
    document.querySelector('.tab-btn').classList.add('border-indigo-500', 'text-indigo-600');
});

// Notification function
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm transition-all duration-300 transform translate-x-full`;
    
    if (type === 'success') {
        notification.className += ' bg-green-500 text-white';
    } else if (type === 'error') {
        notification.className += ' bg-red-500 text-white';
    } else {
        notification.className += ' bg-blue-500 text-white';
    }
    
    notification.innerHTML = `
        <div class="flex items-center justify-between">
            <span class="text-sm font-medium">${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-3 text-white hover:text-gray-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 300);
    }, 5000);
}

// Form handlers
document.addEventListener('DOMContentLoaded', function() {
    // Profile form
    document.getElementById('profileForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        fetch('{{ route("seller.settings.update-profile") }}', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': formData.get('_token')
            },
            body: JSON.stringify(Object.fromEntries(formData))
        })
        .then(response => response.json())
        .then(data => {
            showNotification(data.message || 'Profile updated successfully', 'success');
        })
        .catch(error => {
            showNotification('Error: Something went wrong', 'error');
        });
    });

    // Business form
    document.getElementById('businessForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        fetch('{{ route("seller.settings.update-business") }}', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': formData.get('_token')
            },
            body: JSON.stringify(Object.fromEntries(formData))
        })
        .then(response => response.json())
        .then(data => {
            showNotification(data.message || 'Business details updated successfully', 'success');
        })
        .catch(error => {
            showNotification('Error: Something went wrong', 'error');
        });
    });

    // Bank form
    document.getElementById('bankForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        fetch('{{ route("seller.settings.update-bank") }}', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': formData.get('_token')
            },
            body: JSON.stringify(Object.fromEntries(formData))
        })
        .then(response => response.json())
        .then(data => {
            showNotification(data.message || 'Bank details updated successfully', 'success');
            setTimeout(() => location.reload(), 1500);
        })
        .catch(error => {
            showNotification('Error: Something went wrong', 'error');
        });
    });

    // Password form
    document.getElementById('passwordForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const newPassword = formData.get('new_password');
        const confirmPassword = formData.get('new_password_confirmation');
        
        if(newPassword !== confirmPassword) {
            showNotification('Passwords do not match', 'error');
            return;
        }
        
        fetch('{{ route("seller.settings.update-profile") }}', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': formData.get('_token')
            },
            body: JSON.stringify(Object.fromEntries(formData))
        })
        .then(response => response.json())
        .then(data => {
            showNotification(data.message || 'Password changed successfully', 'success');
            document.getElementById('passwordForm').reset();
        })
        .catch(error => {
            showNotification('Error: Something went wrong', 'error');
        });
    });

    // Notifications form
    document.getElementById('notificationsForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        fetch('{{ route("seller.settings.update-notifications") }}', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': formData.get('_token')
            },
            body: JSON.stringify(Object.fromEntries(formData))
        })
        .then(response => response.json())
        .then(data => {
            showNotification(data.message || 'Notification preferences saved', 'success');
        })
        .catch(error => {
            showNotification('Error: Something went wrong', 'error');
        });
    });
});
</script>
@endpush
@endsection