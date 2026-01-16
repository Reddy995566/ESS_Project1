@extends('website.layouts.master')

@section('title', 'Register')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-[#FAF5ED] to-[#E5E0D8] py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-2xl shadow-2xl">
        <!-- Header -->
        <div class="text-center">
            <h2 class="font-serif-elegant text-4xl font-bold text-[#3D0C1F] tracking-wide">Create Account</h2>
            <p class="mt-2 text-sm text-gray-600">Join us today</p>
        </div>

        <!-- Register Form -->
        <form id="registerForm" class="mt-8 space-y-6">
            @csrf
            <div class="space-y-4">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                    <input 
                        id="name" 
                        name="name" 
                        type="text" 
                        required 
                        class="appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#3D0C1F] focus:border-transparent transition"
                        placeholder="Enter your full name"
                    >
                    <span class="text-red-500 text-xs mt-1 hidden" id="name-error"></span>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input 
                        id="email" 
                        name="email" 
                        type="email" 
                        required 
                        class="appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#3D0C1F] focus:border-transparent transition"
                        placeholder="Enter your email"
                    >
                    <span class="text-red-500 text-xs mt-1 hidden" id="email-error"></span>
                </div>

                <!-- Mobile Number -->
                <div>
                    <label for="mobile" class="block text-sm font-medium text-gray-700 mb-1">Mobile Number</label>
                    <input 
                        id="mobile" 
                        name="mobile" 
                        type="tel" 
                        required 
                        pattern="[0-9]{10}"
                        maxlength="10"
                        class="appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#3D0C1F] focus:border-transparent transition"
                        placeholder="Enter 10-digit mobile number"
                    >
                    <span class="text-red-500 text-xs mt-1 hidden" id="mobile-error"></span>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input 
                        id="password" 
                        name="password" 
                        type="password" 
                        required 
                        class="appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#3D0C1F] focus:border-transparent transition"
                        placeholder="Create a password (min 6 characters)"
                    >
                    <span class="text-red-500 text-xs mt-1 hidden" id="password-error"></span>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                    <input 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        type="password" 
                        required 
                        class="appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#3D0C1F] focus:border-transparent transition"
                        placeholder="Confirm your password"
                    >
                    <span class="text-red-500 text-xs mt-1 hidden" id="password_confirmation-error"></span>
                </div>
            </div>

            <!-- Submit Button -->
            <div>
                <button 
                    type="submit" 
                    id="registerBtn"
                    class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-[#3D0C1F] hover:bg-[#2a0815] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#3D0C1F] transition-all duration-200 shadow-lg hover:shadow-xl"
                >
                    Create Account
                </button>
            </div>

            <!-- Login Link -->
            <div class="text-center">
                <p class="text-sm text-gray-600">
                    Already have an account? 
                    <a href="{{ route('login') }}" class="font-medium text-[#3D0C1F] hover:text-[#2a0815]">Sign in</a>
                </p>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('registerForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const btn = document.getElementById('registerBtn');
    const originalText = btn.innerText;
    btn.innerText = 'Creating account...';
    btn.disabled = true;

    // Clear previous errors
    document.querySelectorAll('[id$="-error"]').forEach(el => {
        el.classList.add('hidden');
        el.innerText = '';
    });

    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());

    try {
        const response = await fetch('{{ route("register.post") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (result.success) {
            window.location.href = result.redirect || '{{ route("home") }}';
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
            } else {
                alert(result.message || 'Registration failed');
            }
            btn.innerText = originalText;
            btn.disabled = false;
        }
    } catch (error) {
        console.error('Registration error:', error);
        alert('Something went wrong. Please try again.');
        btn.innerText = originalText;
        btn.disabled = false;
    }
});
</script>
@endsection
