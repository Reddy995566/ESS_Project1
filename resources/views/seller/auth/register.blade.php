@extends('website.layouts.master')

@section('title', 'Seller Registration')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#FAF5ED] to-[#E5E0D8] py-8 px-4">
    <!-- Main Container -->
    <div class="max-w-4xl mx-auto">
        <!-- Success/Error Messages (Top of page) -->
        <div id="messageContainer" class="hidden mb-6"></div>

        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-3">Become a Seller</h1>
            <p class="text-gray-600 text-lg">Join our marketplace and start selling your products today</p>
        </div>

        <!-- Registration Form -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <form id="sellerRegisterForm" class="space-y-6">
                @csrf

                <!-- Account Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Account Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @auth
                            <!-- Logged in user - show email as readonly -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                                <input type="email" id="email" name="email" value="{{ auth()->user()->email }}" readonly
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-900 bg-gray-100 cursor-not-allowed"
                                    placeholder="seller@example.com">
                                <p class="text-xs text-gray-500 mt-1">Using your current account email</p>
                            </div>
                            <!-- No password field for logged in users -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Account Status</label>
                                <div class="px-4 py-3 border border-green-300 rounded-lg bg-green-50">
                                    <p class="text-sm text-green-700">✓ Using existing account: {{ auth()->user()->name }}</p>
                                </div>
                            </div>
                        @else
                            <!-- Guest user - show email and password fields -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                    class="input-focus block w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                    placeholder="seller@example.com">
                                <span class="text-red-500 text-xs mt-1 hidden" id="email-error"></span>
                            </div>
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password *</label>
                                <div class="relative">
                                    <input type="password" id="password" name="password" required
                                        class="input-focus block w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                        placeholder="••••••••">
                                    <button 
                                        type="button" 
                                        onclick="togglePasswordVisibility('password', 'eyeIcon1')"
                                        class="absolute inset-y-0 right-0 pr-4 flex items-center"
                                    >
                                        <svg id="eyeIcon1" class="h-5 w-5 text-gray-400 hover:text-gray-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>
                                </div>
                        @endauth
                            <span class="text-red-500 text-xs mt-1 hidden" id="password-error"></span>
                        </div>
                        <div class="md:col-span-2">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password *</label>
                            <div class="relative">
                                <input type="password" id="password_confirmation" name="password_confirmation" required
                                    class="input-focus block w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                    placeholder="••••••••">
                                <button 
                                    type="button" 
                                    onclick="togglePasswordVisibility('password_confirmation', 'eyeIcon2')"
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center"
                                >
                                    <svg id="eyeIcon2" class="h-5 w-5 text-gray-400 hover:text-gray-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                            </div>
                            <span class="text-red-500 text-xs mt-1 hidden" id="password_confirmation-error"></span>
                        </div>
                    </div>
                </div>

                <!-- Business Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Business Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="business_name" class="block text-sm font-medium text-gray-700 mb-2">Business Name *</label>
                            <input type="text" id="business_name" name="business_name" value="{{ old('business_name') }}" required
                                class="input-focus block w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                placeholder="Your Business Name">
                            <span class="text-red-500 text-xs mt-1 hidden" id="business_name-error"></span>
                        </div>
                        <div>
                            <label for="business_type" class="block text-sm font-medium text-gray-700 mb-2">Business Type *</label>
                            <select id="business_type" name="business_type" required
                                class="input-focus block w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <option value="">Select Type</option>
                                <option value="individual" {{ old('business_type') == 'individual' ? 'selected' : '' }}>Individual</option>
                                <option value="proprietorship" {{ old('business_type') == 'proprietorship' ? 'selected' : '' }}>Proprietorship</option>
                                <option value="partnership" {{ old('business_type') == 'partnership' ? 'selected' : '' }}>Partnership</option>
                                <option value="company" {{ old('business_type') == 'company' ? 'selected' : '' }}>Company</option>
                            </select>
                            <span class="text-red-500 text-xs mt-1 hidden" id="business_type-error"></span>
                        </div>
                        <div>
                            <label for="gst_number" class="block text-sm font-medium text-gray-700 mb-2">GST Number (Optional)</label>
                            <input type="text" id="gst_number" name="gst_number" value="{{ old('gst_number') }}"
                                class="input-focus block w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                placeholder="22AAAAA0000A1Z5">
                            <span class="text-red-500 text-xs mt-1 hidden" id="gst_number-error"></span>
                        </div>
                        <div>
                            <label for="pan_number" class="block text-sm font-medium text-gray-700 mb-2">PAN Number *</label>
                            <input type="text" id="pan_number" name="pan_number" value="{{ old('pan_number') }}" required
                                class="input-focus block w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                placeholder="ABCDE1234F">
                            <span class="text-red-500 text-xs mt-1 hidden" id="pan_number-error"></span>
                        </div>
                    </div>
                    <div class="mt-4">
                        <label for="business_address" class="block text-sm font-medium text-gray-700 mb-2">Business Address *</label>
                        <textarea id="business_address" name="business_address" rows="3" required
                            class="input-focus block w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                            placeholder="Enter your complete business address">{{ old('business_address') }}</textarea>
                        <span class="text-red-500 text-xs mt-1 hidden" id="business_address-error"></span>
                    </div>
                </div>

                <!-- Contact Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Contact Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="contact_person" class="block text-sm font-medium text-gray-700 mb-2">Contact Person Name *</label>
                            <input type="text" id="contact_person" name="contact_person" value="{{ old('contact_person') }}" required
                                class="input-focus block w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                placeholder="John Doe">
                            <span class="text-red-500 text-xs mt-1 hidden" id="contact_person-error"></span>
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required
                                class="input-focus block w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                placeholder="9876543210">
                            <span class="text-red-500 text-xs mt-1 hidden" id="phone-error"></span>
                        </div>
                    </div>
                </div>

                <!-- Bank Details -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Bank Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="bank_name" class="block text-sm font-medium text-gray-700 mb-2">Bank Name *</label>
                            <input type="text" id="bank_name" name="bank_name" value="{{ old('bank_name') }}" required
                                class="input-focus block w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                placeholder="State Bank of India">
                            <span class="text-red-500 text-xs mt-1 hidden" id="bank_name-error"></span>
                        </div>
                        <div>
                            <label for="account_number" class="block text-sm font-medium text-gray-700 mb-2">Account Number *</label>
                            <input type="text" id="account_number" name="account_number" value="{{ old('account_number') }}" required
                                class="input-focus block w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                placeholder="1234567890">
                            <span class="text-red-500 text-xs mt-1 hidden" id="account_number-error"></span>
                        </div>
                        <div>
                            <label for="ifsc_code" class="block text-sm font-medium text-gray-700 mb-2">IFSC Code *</label>
                            <input type="text" id="ifsc_code" name="ifsc_code" value="{{ old('ifsc_code') }}" required
                                class="input-focus block w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                placeholder="SBIN0001234">
                            <span class="text-red-500 text-xs mt-1 hidden" id="ifsc_code-error"></span>
                        </div>
                        <div>
                            <label for="account_holder_name" class="block text-sm font-medium text-gray-700 mb-2">Account Holder Name *</label>
                            <input type="text" id="account_holder_name" name="account_holder_name" value="{{ old('account_holder_name') }}" required
                                class="input-focus block w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                placeholder="John Doe">
                            <span class="text-red-500 text-xs mt-1 hidden" id="account_holder_name-error"></span>
                        </div>
                    </div>
                </div>

                <!-- Terms & Conditions -->
                <div class="flex items-start">
                    <input type="checkbox" id="terms" name="terms" required class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded mt-1">
                    <label for="terms" class="ml-2 block text-sm text-gray-700">
                        I agree to the <a href="#" class="text-indigo-600 hover:text-indigo-500 font-medium">Terms and Conditions</a> and <a href="#" class="text-indigo-600 hover:text-indigo-500 font-medium">Seller Policy</a>
                    </label>
                    <span class="text-red-500 text-xs mt-1 hidden ml-6" id="terms-error"></span>
                </div>

                <!-- Admin Approval Notice (Hidden by default, shown after successful registration) -->
                <div id="approvalNotice" class="hidden bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-green-800">Please Wait - Admin Approval Required</h3>
                            <p class="mt-1 text-sm text-green-700">
                                Your seller application will be reviewed by our admin team. You will receive an email notification once your account is approved. This process typically takes 24-48 hours.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" id="registerBtn" class="w-full py-3.5 px-4 rounded-xl text-white font-semibold shadow-lg bg-gradient-to-r from-indigo-600 to-indigo-800 hover:from-indigo-700 hover:to-indigo-900 transition-all">
                    Register as Seller
                </button>

                <!-- Login Link -->
                <div class="text-center">
                    <p class="text-sm text-gray-600">
                        Already have an account? 
                        <a href="{{ route('seller.login') }}" class="font-medium text-indigo-600 hover:text-indigo-500">Sign in here</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function togglePasswordVisibility(inputId, iconId) {
    const passwordInput = document.getElementById(inputId);
    const eyeIcon = document.getElementById(iconId);
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
        `;
    } else {
        passwordInput.type = 'password';
        eyeIcon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
        `;
    }
}

// Form submission with AJAX
document.getElementById('sellerRegisterForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const btn = document.getElementById('registerBtn');
    const originalText = btn.innerText;
    btn.innerText = 'Registering...';
    btn.disabled = true;

    // Clear previous errors
    clearErrors();

    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());
    
    // Get CSRF token from meta tag or form
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || 
                      document.querySelector('input[name="_token"]')?.value ||
                      '{{ csrf_token() }}';

    try {
        const response = await fetch('{{ route("seller.register.post") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (result.success) {
            // Show approval notice
            document.getElementById('approvalNotice').classList.remove('hidden');
            
            // Scroll to approval notice
            document.getElementById('approvalNotice').scrollIntoView({ behavior: 'smooth', block: 'center' });
            
            // Reset form
            document.getElementById('sellerRegisterForm').reset();
            
            btn.innerText = originalText;
            btn.disabled = false;
        } else {
            // Show errors
            if (result.errors) {
                Object.keys(result.errors).forEach(key => {
                    showFieldError(key, result.errors[key][0]);
                });
                // Scroll to first error
                const firstError = document.querySelector('.border-red-500');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            } else {
                showMessage(result.message || 'Registration failed. Please check your information.', 'error');
            }
            btn.innerText = originalText;
            btn.disabled = false;
        }
    } catch (error) {
        console.error('Registration error:', error);
        showMessage('Something went wrong. Please try again.', 'error');
        btn.innerText = originalText;
        btn.disabled = false;
    }
});

// Inline validation
document.getElementById('email').addEventListener('blur', function() {
    const email = this.value.trim();
    if (!email) {
        showFieldError('email', 'Email is required');
    } else if (!isValidEmail(email)) {
        showFieldError('email', 'Please enter a valid email address');
    } else {
        clearFieldError('email');
    }
});

document.getElementById('password').addEventListener('blur', function() {
    const password = this.value;
    if (!password) {
        showFieldError('password', 'Password is required');
    } else if (password.length < 6) {
        showFieldError('password', 'Password must be at least 6 characters');
    } else {
        clearFieldError('password');
    }
});

document.getElementById('password_confirmation').addEventListener('blur', function() {
    const password = document.getElementById('password').value;
    const confirmation = this.value;
    if (!confirmation) {
        showFieldError('password_confirmation', 'Please confirm your password');
    } else if (password !== confirmation) {
        showFieldError('password_confirmation', 'Passwords do not match');
    } else {
        clearFieldError('password_confirmation');
    }
});

// Also validate confirmation when password changes
document.getElementById('password').addEventListener('input', function() {
    const confirmation = document.getElementById('password_confirmation').value;
    if (confirmation) {
        if (this.value !== confirmation) {
            showFieldError('password_confirmation', 'Passwords do not match');
        } else {
            clearFieldError('password_confirmation');
        }
    }
});

document.getElementById('phone').addEventListener('blur', function() {
    const phone = this.value.trim();
    if (!phone) {
        showFieldError('phone', 'Phone number is required');
    } else if (!/^\d{10}$/.test(phone)) {
        showFieldError('phone', 'Please enter a valid 10-digit phone number');
    } else {
        clearFieldError('phone');
    }
});

document.getElementById('pan_number').addEventListener('blur', function() {
    const pan = this.value.trim().toUpperCase();
    this.value = pan;
    if (!pan) {
        showFieldError('pan_number', 'PAN number is required');
    } else if (!/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/.test(pan)) {
        showFieldError('pan_number', 'Please enter a valid PAN number (e.g., ABCDE1234F)');
    } else {
        clearFieldError('pan_number');
    }
});

document.getElementById('ifsc_code').addEventListener('blur', function() {
    const ifsc = this.value.trim().toUpperCase();
    this.value = ifsc;
    if (!ifsc) {
        showFieldError('ifsc_code', 'IFSC code is required');
    } else if (!/^[A-Z]{4}0[A-Z0-9]{6}$/.test(ifsc)) {
        showFieldError('ifsc_code', 'Please enter a valid IFSC code (e.g., SBIN0001234)');
    } else {
        clearFieldError('ifsc_code');
    }
});

// Helper functions
function isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

function showFieldError(field, message) {
    const errorEl = document.getElementById(field + '-error');
    if (errorEl) {
        errorEl.textContent = message;
        errorEl.classList.remove('hidden');
    }
    const inputEl = document.getElementById(field);
    if (inputEl) {
        inputEl.classList.add('border-red-500');
    }
}

function clearFieldError(field) {
    const errorEl = document.getElementById(field + '-error');
    if (errorEl) {
        errorEl.textContent = '';
        errorEl.classList.add('hidden');
    }
    const inputEl = document.getElementById(field);
    if (inputEl) {
        inputEl.classList.remove('border-red-500');
    }
}

function clearErrors() {
    document.querySelectorAll('[id$="-error"]').forEach(el => {
        el.textContent = '';
        el.classList.add('hidden');
    });
    document.querySelectorAll('input, select, textarea').forEach(input => {
        input.classList.remove('border-red-500');
    });
    const messageContainer = document.getElementById('messageContainer');
    messageContainer.classList.add('hidden');
    messageContainer.innerHTML = '';
}

function showMessage(message, type) {
    const messageContainer = document.getElementById('messageContainer');
    const bgColor = type === 'success' ? 'bg-green-50 border-green-500 text-green-700' : 'bg-red-50 border-red-500 text-red-700';
    const icon = type === 'success' 
        ? '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>'
        : '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>';
    
    messageContainer.innerHTML = `
        <div class="${bgColor} border-l-4 p-4 rounded-lg shadow-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">${icon}</svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium">${message}</p>
                </div>
            </div>
        </div>
    `;
    messageContainer.classList.remove('hidden');
}
</script>
@endsection
