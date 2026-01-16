@extends('website.layouts.master')

@section('title', 'My Profile')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#FAF5ED] to-[#E5E0D8] py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            
            <!-- Sidebar -->
            @include('website.user.partials.sidebar')

            <!-- Main Content -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h2 class="font-serif-elegant text-3xl font-bold text-[#3D0C1F] mb-6">My Profile</h2>
                    
                    <!-- Success Message -->
                    <div id="successMessage" class="hidden mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                        Profile updated successfully!
                    </div>

                    <!-- Profile Form -->
                    <form id="profileForm" class="space-y-6">
                        @csrf
                        
                        <!-- Avatar Section -->
                        <div class="flex items-center gap-6 pb-6 border-b border-gray-200">
                            <div class="w-24 h-24 bg-gradient-to-br from-[#3D0C1F] to-[#4A0F23] rounded-full flex items-center justify-center text-white text-3xl font-bold">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Profile Picture</h3>
                                <p class="text-sm text-gray-500 mt-1">Avatar is generated from your name</p>
                            </div>
                        </div>

                        <!-- Personal Information -->
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-4">Personal Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Full Name -->
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                                    <input 
                                        type="text" 
                                        id="name" 
                                        name="name" 
                                        value="{{ Auth::user()->name }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3D0C1F] focus:border-transparent transition"
                                        required
                                    >
                                    <span class="text-red-500 text-xs mt-1 hidden" id="name-error"></span>
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                                    <input 
                                        type="email" 
                                        id="email" 
                                        name="email" 
                                        value="{{ Auth::user()->email }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3D0C1F] focus:border-transparent transition"
                                        required
                                    >
                                    <span class="text-red-500 text-xs mt-1 hidden" id="email-error"></span>
                                </div>

                                <!-- Mobile -->
                                <div>
                                    <label for="mobile" class="block text-sm font-medium text-gray-700 mb-2">Mobile Number</label>
                                    <input 
                                        type="tel" 
                                        id="mobile" 
                                        name="mobile" 
                                        value="{{ Auth::user()->mobile }}"
                                        pattern="[0-9]{10}"
                                        maxlength="10"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3D0C1F] focus:border-transparent transition"
                                        required
                                    >
                                    <span class="text-red-500 text-xs mt-1 hidden" id="mobile-error"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Change Password Section -->
                        <div class="pt-6 border-t border-gray-200">
                            <h3 class="text-xl font-semibold text-gray-900 mb-4">Change Password</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Current Password -->
                                <div>
                                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                                    <div class="relative">
                                        <input 
                                            type="password" 
                                            id="current_password" 
                                            name="current_password" 
                                            class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3D0C1F] focus:border-transparent transition"
                                            placeholder="Leave blank to keep current"
                                        >
                                        <button type="button" onclick="togglePassword('current_password')" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                            <svg id="current_password-eye" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <span class="text-red-500 text-xs mt-1 hidden" id="current_password-error"></span>
                                </div>

                                <div></div>

                                <!-- New Password -->
                                <div>
                                    <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                                    <div class="relative">
                                        <input 
                                            type="password" 
                                            id="new_password" 
                                            name="new_password" 
                                            class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3D0C1F] focus:border-transparent transition"
                                            placeholder="Minimum 6 characters"
                                        >
                                        <button type="button" onclick="togglePassword('new_password')" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                            <svg id="new_password-eye" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <span class="text-red-500 text-xs mt-1 hidden" id="new_password-error"></span>
                                </div>

                                <!-- Confirm Password -->
                                <div>
                                    <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                                    <div class="relative">
                                        <input 
                                            type="password" 
                                            id="new_password_confirmation" 
                                            name="new_password_confirmation" 
                                            class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3D0C1F] focus:border-transparent transition"
                                            placeholder="Re-enter new password"
                                        >
                                        <button type="button" onclick="togglePassword('new_password_confirmation')" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                            <svg id="new_password_confirmation-eye" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <p class="text-sm text-gray-500 mt-2">Leave password fields blank if you don't want to change it</p>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex gap-4 pt-6">
                            <button 
                                type="submit" 
                                id="saveBtn"
                                class="px-8 py-3 bg-[#3D0C1F] text-white font-medium rounded-lg hover:bg-[#2a0815] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#3D0C1F] transition-all shadow-lg"
                            >
                                Save Changes
                            </button>
                            <a 
                                href="{{ route('user.dashboard') }}" 
                                class="px-8 py-3 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition-all"
                            >
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notification Container -->
<div id="toastContainer" class="fixed top-4 right-4 z-50 space-y-2"></div>

<script>
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
    
    setTimeout(() => toast.classList.add('translate-x-0'), 10);
    setTimeout(() => {
        toast.classList.add('opacity-0', 'translate-x-full');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Toggle password visibility
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const eye = document.getElementById(fieldId + '-eye');
    
    if (field.type === 'password') {
        field.type = 'text';
        eye.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>';
    } else {
        field.type = 'password';
        eye.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
    }
}

document.getElementById('profileForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const btn = document.getElementById('saveBtn');
    const originalText = btn.innerText;
    btn.innerText = 'Saving...';
    btn.disabled = true;

    // Clear previous errors
    document.querySelectorAll('[id$="-error"]').forEach(el => {
        el.classList.add('hidden');
        el.innerText = '';
    });

    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());

    try {
        const response = await fetch('{{ route("user.profile.update") }}', {
            method: 'POST',
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
            
            if (response.status === 419) {
                showToast('Session expired. Please refresh the page.', 'error');
            } else if (response.status === 401 || text.includes('login')) {
                showToast('Please login first', 'error');
                setTimeout(() => window.location.href = '{{ route("login") }}', 1500);
            } else {
                showToast('Server error occurred. Check console for details.', 'error');
            }
            return;
        }

        const result = await response.json();

        if (result.success) {
            showToast('Profile updated successfully!', 'success');
            
            // Clear password fields
            document.getElementById('current_password').value = '';
            document.getElementById('new_password').value = '';
            document.getElementById('new_password_confirmation').value = '';
        } else {
            // Show errors
            if (result.errors) {
                Object.keys(result.errors).forEach(key => {
                    const errorEl = document.getElementById(key + '-error');
                    if (errorEl) {
                        errorEl.innerText = result.errors[key][0];
                        errorEl.classList.remove('hidden');
                    }
                });
                const firstError = Object.values(result.errors)[0][0];
                showToast(firstError, 'error');
            } else {
                showToast(result.message || 'Failed to update profile', 'error');
            }
        }
    } catch (error) {
        console.error('Profile update error:', error);
        showToast('Something went wrong. Please try again.', 'error');
    } finally {
        btn.innerText = originalText;
        btn.disabled = false;
    }
});
</script>
@endsection
