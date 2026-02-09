@extends('seller.layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                            <i class="fas fa-cog text-indigo-600 mr-3"></i>
                            Settings
                        </h1>
                        <p class="mt-2 text-gray-600">Manage your account and business settings</p>
                    </div>
                    <div class="hidden md:flex items-center space-x-4">
                        <div class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                            <i class="fas fa-check-circle mr-1"></i>
                            Account Active
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <!-- Desktop Navigation -->
                <nav class="hidden md:flex space-x-0">
                    <button onclick="switchTab('profile', this)" class="tab-btn flex-1 border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50 py-4 px-6 border-b-2 font-medium text-sm transition-all duration-200 flex items-center justify-center">
                        <i class="fas fa-user mr-2"></i>Profile
                    </button>
                    <button onclick="switchTab('business', this)" class="tab-btn flex-1 border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50 py-4 px-6 border-b-2 font-medium text-sm transition-all duration-200 flex items-center justify-center">
                        <i class="fas fa-building mr-2"></i>Business
                    </button>
                    <button onclick="switchTab('bank', this)" class="tab-btn flex-1 border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50 py-4 px-6 border-b-2 font-medium text-sm transition-all duration-200 flex items-center justify-center">
                        <i class="fas fa-university mr-2"></i>Bank
                    </button>
                    <button onclick="switchTab('password', this)" class="tab-btn flex-1 border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50 py-4 px-6 border-b-2 font-medium text-sm transition-all duration-200 flex items-center justify-center">
                        <i class="fas fa-lock mr-2"></i>Security
                    </button>
                    <button onclick="switchTab('shiprocket', this)" class="tab-btn flex-1 border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50 py-4 px-6 border-b-2 font-medium text-sm transition-all duration-200 flex items-center justify-center">
                        <i class="fas fa-shipping-fast mr-2"></i>Shipping
                    </button>
                    <button onclick="switchTab('notifications', this)" class="tab-btn flex-1 border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50 py-4 px-6 border-b-2 font-medium text-sm transition-all duration-200 flex items-center justify-center">
                        <i class="fas fa-bell mr-2"></i>Alerts
                    </button>
                </nav>
                
                <!-- Mobile Navigation -->
                <div class="md:hidden">
                    <select id="mobile-tab-select" class="w-full p-4 border-0 bg-transparent text-gray-700 font-medium focus:outline-none focus:ring-0" onchange="switchTab(this.value, null)">
                        <option value="profile">👤 Profile</option>
                        <option value="business">🏢 Business</option>
                        <option value="bank">🏦 Bank</option>
                        <option value="password">🔒 Security</option>
                        <option value="shiprocket">🚚 Shipping</option>
                        <option value="notifications">🔔 Alerts</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Profile Tab -->
        <div id="profile-tab" class="tab-content">
            <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-user-circle mr-2"></i>
                        Profile Information
                    </h3>
                    <p class="mt-1 text-sm text-indigo-100">Update your personal information and contact details</p>
                </div>
                <div class="px-6 py-6">
                    <form id="profileForm" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">Name *</label>
                                <div class="relative">
                                    <input type="text" name="name" class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200" value="{{ $user->name }}" required>
                                    <i class="fas fa-user absolute left-3 top-3.5 text-gray-400"></i>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">Email *</label>
                                <div class="relative">
                                    <input type="email" name="email" class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200" value="{{ $user->email }}" required>
                                    <i class="fas fa-envelope absolute left-3 top-3.5 text-gray-400"></i>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">Mobile *</label>
                                <div class="relative">
                                    <input type="text" name="mobile" class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200" value="{{ $user->mobile }}" required maxlength="10">
                                    <i class="fas fa-phone absolute left-3 top-3.5 text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-end pt-4 border-t border-gray-200">
                            <button type="submit" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-8 py-3 rounded-lg hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all duration-200 font-medium">
                                <i class="fas fa-save mr-2"></i>Update Profile
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
            <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-red-500 to-pink-600 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-shield-alt mr-2"></i>
                        Security Settings
                    </h3>
                    <p class="mt-1 text-sm text-red-100">Manage your account security and authentication</p>
                </div>
                <div class="px-6 py-6 space-y-8">
                    <!-- Change Password Section -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Change Password</h4>
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
                                    <i class="fas fa-save mr-2"></i>Change Password
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Two-Factor Authentication Section -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900 flex items-center">
                                    <i class="fas fa-mobile-alt mr-2 text-blue-600"></i>
                                    Two-Factor Authentication
                                </h4>
                                <p class="text-sm text-gray-600 mt-1">Add an extra layer of security to your account</p>
                            </div>
                            <div class="flex items-center">
                                @if($seller->two_factor_enabled)
                                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                                        <i class="fas fa-check-circle mr-1"></i>Enabled
                                    </span>
                                @else
                                    <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-sm font-medium">
                                        <i class="fas fa-times-circle mr-1"></i>Disabled
                                    </span>
                                @endif
                            </div>
                        </div>

                        @if($seller->two_factor_enabled)
                            <!-- 2FA Enabled State -->
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                                <div class="flex items-center">
                                    <i class="fas fa-shield-check text-green-600 mr-3"></i>
                                    <div>
                                        <p class="text-sm font-medium text-green-800">Two-factor authentication is active</p>
                                        <p class="text-xs text-green-600">Your account is protected with 2FA</p>
                                    </div>
                                </div>
                            </div>
                            
                            <form id="disable2FAForm" class="space-y-4">
                                @csrf
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Enter your password to disable 2FA</label>
                                    <input type="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500" required>
                                </div>
                                <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                                    <i class="fas fa-times mr-2"></i>Disable 2FA
                                </button>
                            </form>
                        @else
                            <!-- 2FA Disabled State -->
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                                <div class="flex items-center">
                                    <i class="fas fa-exclamation-triangle text-yellow-600 mr-3"></i>
                                    <div>
                                        <p class="text-sm font-medium text-yellow-800">Two-factor authentication is not enabled</p>
                                        <p class="text-xs text-yellow-600">Enable 2FA to secure your account</p>
                                    </div>
                                </div>
                            </div>
                            
                            <button id="enable2FABtn" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <i class="fas fa-plus mr-2"></i>Enable 2FA
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Shiprocket Tab -->
        <div id="shiprocket-tab" class="tab-content hidden">
            <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-shipping-fast mr-2"></i>
                        Shiprocket Integration
                    </h3>
                    <p class="mt-1 text-sm text-blue-100">Configure your Shiprocket API credentials for shipping</p>
                </div>
                <div class="px-6 py-6">
                    <form id="shiprocketForm" class="space-y-6">
                        @csrf
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-blue-800">Simple Setup</h3>
                                    <div class="mt-2 text-sm text-blue-700">
                                        <p>Just enter your Shiprocket API credentials to enable shipping for your orders.</p>
                                        <p class="mt-1">Get your credentials from <a href="https://shiprocket.in" target="_blank" class="underline font-medium">Shiprocket Dashboard</a></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">API User (Email) *</label>
                                <div class="relative">
                                    <input type="email" name="shiprocket_email" class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200" value="{{ $settings['shiprocket_email'] ?? '' }}" placeholder="Enter your Shiprocket API email">
                                    <i class="fas fa-envelope absolute left-3 top-3.5 text-gray-400"></i>
                                </div>
                                <p class="text-xs text-gray-500">Your Shiprocket account email address</p>
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">API Password *</label>
                                <div class="relative">
                                    <input type="password" name="shiprocket_password" class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200" value="{{ $settings['shiprocket_password'] ?? '' }}" placeholder="Enter your Shiprocket API password">
                                    <i class="fas fa-lock absolute left-3 top-3.5 text-gray-400"></i>
                                </div>
                                <p class="text-xs text-gray-500">Your Shiprocket account password</p>
                            </div>

                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center">
                                    <input type="checkbox" name="shiprocket_enabled" id="shiprocket_enabled" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" {{ ($settings['shiprocket_enabled'] ?? false) ? 'checked' : '' }}>
                                    <label for="shiprocket_enabled" class="ml-3 text-sm font-medium text-gray-700">Enable Shiprocket Integration</label>
                                </div>
                                <p class="mt-2 text-xs text-gray-500">Check this to enable Shiprocket shipping for your orders</p>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                            <button type="button" onclick="testShiprocketConnection()" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200 font-medium">
                                <i class="fas fa-plug mr-2"></i>Test Connection
                            </button>
                            <button type="submit" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-8 py-3 rounded-lg hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all duration-200 font-medium">
                                <i class="fas fa-save mr-2"></i>Save Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Notifications Tab -->
        <div id="notifications-tab" class="tab-content hidden">
            <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-purple-500 to-pink-600 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-bell mr-2"></i>
                        Notification Preferences
                    </h3>
                    <p class="mt-1 text-sm text-purple-100">Manage how you want to receive notifications</p>
                </div>
                <div class="px-6 py-6">
                    <form id="notificationsForm" class="space-y-8">
                        @csrf
                        
                        <!-- Email Notifications Section -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                            <div class="flex items-center mb-4">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-envelope text-blue-600"></i>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-lg font-semibold text-gray-900">Email Notifications</h4>
                                    <p class="text-sm text-gray-600">Get notified via email for important events</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="flex items-center p-3 bg-white rounded-lg border border-gray-200 hover:border-blue-300 transition-colors">
                                    <input type="checkbox" name="email_new_order" id="email_new_order" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" {{ $settings['email_new_order'] ? 'checked' : '' }}>
                                    <div class="ml-3">
                                        <label for="email_new_order" class="text-sm font-medium text-gray-700">New Order</label>
                                        <p class="text-xs text-gray-500">When you receive a new order</p>
                                    </div>
                                </div>
                                <div class="flex items-center p-3 bg-white rounded-lg border border-gray-200 hover:border-blue-300 transition-colors">
                                    <input type="checkbox" name="email_product_approved" id="email_product_approved" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" {{ $settings['email_product_approved'] ? 'checked' : '' }}>
                                    <div class="ml-3">
                                        <label for="email_product_approved" class="text-sm font-medium text-gray-700">Product Approved</label>
                                        <p class="text-xs text-gray-500">When your product gets approved</p>
                                    </div>
                                </div>
                                <div class="flex items-center p-3 bg-white rounded-lg border border-gray-200 hover:border-blue-300 transition-colors">
                                    <input type="checkbox" name="email_product_rejected" id="email_product_rejected" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" {{ $settings['email_product_rejected'] ? 'checked' : '' }}>
                                    <div class="ml-3">
                                        <label for="email_product_rejected" class="text-sm font-medium text-gray-700">Product Rejected</label>
                                        <p class="text-xs text-gray-500">When your product gets rejected</p>
                                    </div>
                                </div>
                                <div class="flex items-center p-3 bg-white rounded-lg border border-gray-200 hover:border-blue-300 transition-colors">
                                    <input type="checkbox" name="email_payout_processed" id="email_payout_processed" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" {{ $settings['email_payout_processed'] ? 'checked' : '' }}>
                                    <div class="ml-3">
                                        <label for="email_payout_processed" class="text-sm font-medium text-gray-700">Payout Processed</label>
                                        <p class="text-xs text-gray-500">When your payout is processed</p>
                                    </div>
                                </div>
                                <div class="flex items-center p-3 bg-white rounded-lg border border-gray-200 hover:border-blue-300 transition-colors">
                                    <input type="checkbox" name="email_low_stock" id="email_low_stock" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" {{ $settings['email_low_stock'] ? 'checked' : '' }}>
                                    <div class="ml-3">
                                        <label for="email_low_stock" class="text-sm font-medium text-gray-700">Low Stock Alert</label>
                                        <p class="text-xs text-gray-500">When product stock is low</p>
                                    </div>
                                </div>
                                <div class="flex items-center p-3 bg-white rounded-lg border border-gray-200 hover:border-blue-300 transition-colors">
                                    <input type="checkbox" name="email_new_review" id="email_new_review" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" {{ $settings['email_new_review'] ? 'checked' : '' }}>
                                    <div class="ml-3">
                                        <label for="email_new_review" class="text-sm font-medium text-gray-700">New Review</label>
                                        <p class="text-xs text-gray-500">When you get a new product review</p>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <!-- Quick Actions -->
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h5 class="text-sm font-medium text-gray-900">Quick Actions</h5>
                                    <p class="text-xs text-gray-500">Manage all notifications at once</p>
                                </div>
                                <div class="flex space-x-2">
                                    <button type="button" onclick="toggleAllNotifications(true)" class="px-3 py-1 text-xs bg-indigo-100 text-indigo-700 rounded-md hover:bg-indigo-200 transition-colors">
                                        Enable All
                                    </button>
                                    <button type="button" onclick="toggleAllNotifications(false)" class="px-3 py-1 text-xs bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors">
                                        Disable All
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end pt-4 border-t border-gray-200">
                            <button type="submit" class="bg-gradient-to-r from-purple-600 to-pink-600 text-white px-8 py-3 rounded-lg hover:from-purple-700 hover:to-pink-700 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-all duration-200 font-medium">
                                <i class="fas fa-save mr-2"></i>Save Preferences
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
function switchTab(tabName, element) {
    // Hide all tab contents with fade effect
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.add('hidden');
        tab.style.opacity = '0';
    });
    
    // Remove active class from all tab buttons
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('border-indigo-500', 'text-indigo-600', 'bg-indigo-50');
        btn.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab content with fade effect
    const selectedTab = document.getElementById(tabName + '-tab');
    selectedTab.classList.remove('hidden');
    setTimeout(() => {
        selectedTab.style.opacity = '1';
    }, 50);
    
    // Add active class to selected tab button (desktop)
    if (element) {
        element.classList.remove('border-transparent', 'text-gray-500');
        element.classList.add('border-indigo-500', 'text-indigo-600', 'bg-indigo-50');
    }
    
    // Update mobile select
    const mobileSelect = document.getElementById('mobile-tab-select');
    if (mobileSelect) {
        mobileSelect.value = tabName;
    }
}

// Set first tab as active on page load
document.addEventListener('DOMContentLoaded', function() {
    // Add CSS for smooth transitions
    const style = document.createElement('style');
    style.textContent = `
        .tab-content {
            transition: opacity 0.3s ease-in-out;
            opacity: 1;
        }
        .tab-btn {
            transition: all 0.2s ease-in-out;
        }
    `;
    document.head.appendChild(style);
    
    // Check if URL has hash for shiprocket
    if (window.location.hash === '#shiprocket') {
        switchTab('shiprocket');
        // Find and activate shiprocket tab button
        document.querySelectorAll('.tab-btn').forEach(btn => {
            if (btn.textContent.includes('Shipping')) {
                btn.classList.remove('border-transparent', 'text-gray-500');
                btn.classList.add('border-indigo-500', 'text-indigo-600', 'bg-indigo-50');
            }
        });
    } else {
        const firstTab = document.querySelector('.tab-btn');
        firstTab.classList.remove('border-transparent', 'text-gray-500');
        firstTab.classList.add('border-indigo-500', 'text-indigo-600', 'bg-indigo-50');
    }
});

// Notification function
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-xl shadow-2xl max-w-sm transition-all duration-500 transform translate-x-full border-l-4`;
    
    if (type === 'success') {
        notification.className += ' bg-white border-green-500 text-gray-800';
        notification.innerHTML = `
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-check text-green-600"></i>
                    </div>
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium text-gray-900">${message}</p>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-3 text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
    } else if (type === 'error') {
        notification.className += ' bg-white border-red-500 text-gray-800';
        notification.innerHTML = `
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                    </div>
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium text-gray-900">${message}</p>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-3 text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
    } else {
        notification.className += ' bg-white border-blue-500 text-gray-800';
        notification.innerHTML = `
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-info-circle text-blue-600"></i>
                    </div>
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium text-gray-900">${message}</p>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-3 text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
    }
    
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
        }, 500);
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
        
        fetch('{{ route("seller.settings.update-password") }}', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': formData.get('_token')
            },
            body: JSON.stringify({
                current_password: formData.get('current_password'),
                new_password: formData.get('new_password'),
                new_password_confirmation: formData.get('new_password_confirmation')
            })
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

    // Shiprocket form
    document.getElementById('shiprocketForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        fetch('{{ route("seller.settings.shiprocket") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            showNotification(data.message || 'Shiprocket settings saved', 'success');
        })
        .catch(error => {
            showNotification('Error: Something went wrong', 'error');
        });
    });

    // 2FA Enable Button
    document.getElementById('enable2FABtn')?.addEventListener('click', function() {
        fetch('{{ route("seller.settings.enable-2fa") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                show2FASetupModal(data.qr_code_url, data.secret);
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            showNotification('Error: Something went wrong', 'error');
        });
    });

    // Disable 2FA Form
    document.getElementById('disable2FAForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        fetch('{{ route("seller.settings.disable-2fa") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': formData.get('_token')
            },
            body: JSON.stringify({
                password: formData.get('password')
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            showNotification('Error: Something went wrong', 'error');
        });
    });
});

// Test Shiprocket connection
function testShiprocketConnection() {
    const email = document.querySelector('input[name="shiprocket_email"]').value;
    const password = document.querySelector('input[name="shiprocket_password"]').value;
    
    if (!email || !password) {
        showNotification('Please enter email and password first', 'error');
        return;
    }
    
    const formData = new FormData();
    formData.append('shiprocket_email', email);
    formData.append('shiprocket_password', password);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    
    fetch('{{ route("seller.settings.shiprocket.test") }}', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Shiprocket connection successful!', 'success');
        } else {
            showNotification('Connection failed: ' + data.message, 'error');
        }
    })
    .catch(error => {
        showNotification('Error: Something went wrong', 'error');
    });
}

// Toggle all notifications
function toggleAllNotifications(enable) {
    const checkboxes = document.querySelectorAll('#notificationsForm input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
        checkbox.checked = enable;
    });
    
    const action = enable ? 'enabled' : 'disabled';
    showNotification(`All notifications ${action}`, 'info');
}

// 2FA Setup Modal
function show2FASetupModal(qrCodeUrl, secret) {
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
    modal.innerHTML = `
        <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4">
            <div class="text-center mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Setup Two-Factor Authentication</h3>
                <p class="text-sm text-gray-600">Scan the QR code with your authenticator app</p>
            </div>
            
            <div class="text-center mb-6">
                <img src="${qrCodeUrl}" alt="QR Code" class="mx-auto mb-4 border rounded-lg">
                <div class="bg-gray-100 p-3 rounded-lg">
                    <p class="text-xs text-gray-600 mb-1">Manual entry key:</p>
                    <code class="text-sm font-mono break-all">${secret}</code>
                </div>
            </div>
            
            <form id="confirm2FAForm">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Enter 6-digit code from your app</label>
                    <input type="text" name="code" maxlength="6" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 text-center text-lg font-mono" placeholder="000000" required>
                </div>
                
                <div class="flex space-x-3">
                    <button type="button" onclick="this.closest('.fixed').remove()" class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                        Verify & Enable
                    </button>
                </div>
            </form>
        </div>
    `;
    
    document.body.appendChild(modal);
    
    // Handle form submission
    modal.querySelector('#confirm2FAForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const code = this.querySelector('input[name="code"]').value;
        
        fetch('{{ route("seller.settings.confirm-2fa") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ code: code })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                modal.remove();
                showRecoveryCodesModal(data.recovery_codes);
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            showNotification('Error: Something went wrong', 'error');
        });
    });
}

// Recovery Codes Modal
function showRecoveryCodesModal(recoveryCodes) {
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
    modal.innerHTML = `
        <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4">
            <div class="text-center mb-6">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-check text-green-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">2FA Enabled Successfully!</h3>
                <p class="text-sm text-gray-600">Save these recovery codes in a safe place</p>
            </div>
            
            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                <div class="grid grid-cols-2 gap-2 text-sm font-mono">
                    ${recoveryCodes.map(code => `<div class="bg-white p-2 rounded text-center">${code}</div>`).join('')}
                </div>
            </div>
            
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-4">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-triangle text-yellow-600 mt-0.5 mr-2"></i>
                    <div class="text-xs text-yellow-800">
                        <p class="font-medium">Important:</p>
                        <p>Store these codes safely. You can use them to access your account if you lose your authenticator device.</p>
                    </div>
                </div>
            </div>
            
            <button onclick="this.closest('.fixed').remove(); location.reload();" class="w-full bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                I've Saved My Recovery Codes
            </button>
        </div>
    `;
    
    document.body.appendChild(modal);
}
</script>
@endpush
@endsection