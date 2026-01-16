@extends('website.layouts.master')

@section('title', 'Login')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-[#FAF5ED] to-[#E5E0D8] py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-2xl shadow-2xl">
        <!-- Header -->
        <div class="text-center">
            <h2 class="font-serif-elegant text-4xl font-bold text-[#3D0C1F] tracking-wide">Welcome Back</h2>
            <p class="mt-2 text-sm text-gray-600">Sign in to your account</p>
        </div>

        <!-- Login Form -->
        
        <!-- Session Messages -->
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                {{ session('error') }}
            </div>
        @endif
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        <form id="loginForm" class="mt-8 space-y-6">
            @csrf
            <div class="space-y-4">
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

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input 
                        id="password" 
                        name="password" 
                        type="password" 
                        required 
                        class="appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#3D0C1F] focus:border-transparent transition"
                        placeholder="Enter your password"
                    >
                    <span class="text-red-500 text-xs mt-1 hidden" id="password-error"></span>
                </div>
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input 
                        id="remember" 
                        name="remember" 
                        type="checkbox" 
                        class="h-4 w-4 text-[#3D0C1F] focus:ring-[#3D0C1F] border-gray-300 rounded"
                    >
                    <label for="remember" class="ml-2 block text-sm text-gray-900">Remember me</label>
                </div>
                <div class="text-sm">
                    <a href="#" class="font-medium text-[#3D0C1F] hover:text-[#2a0815]">Forgot password?</a>
                </div>
            </div>

            <!-- Submit Button -->
            <div>
                <button 
                    type="submit" 
                    id="loginBtn"
                    class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-[#3D0C1F] hover:bg-[#2a0815] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#3D0C1F] transition-all duration-200 shadow-lg hover:shadow-xl"
                >
                    Sign In
                </button>
            </div>

            <!-- Register Link -->
            <div class="text-center">
                <p class="text-sm text-gray-600">
                    Don't have an account? 
                    <a href="{{ route('register') }}" class="font-medium text-[#3D0C1F] hover:text-[#2a0815]">Sign up</a>
                </p>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('loginForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const btn = document.getElementById('loginBtn');
    const originalText = btn.innerText;
    btn.innerText = 'Signing in...';
    btn.disabled = true;

    // Clear previous errors
    document.querySelectorAll('[id$="-error"]').forEach(el => {
        el.classList.add('hidden');
        el.innerText = '';
    });

    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());

    try {
        const response = await fetch('{{ route("login.post") }}', {
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
            // Show errors inline
            if (result.errors) {
                Object.keys(result.errors).forEach(key => {
                    const errorEl = document.getElementById(key + '-error');
                    if (errorEl) {
                        errorEl.innerText = result.errors[key][0];
                        errorEl.classList.remove('hidden');
                    }
                });
            } else {
                // For general errors like "Invalid email or password", show it under password field if not specified
                const passwordError = document.getElementById('password-error');
                if (passwordError) {
                    passwordError.innerText = result.message || 'Login failed';
                    passwordError.classList.remove('hidden');
                } else {
                    alert(result.message || 'Login failed'); // Fallback
                }
            }
            btn.innerText = originalText;
            btn.disabled = false;
        }
    } catch (error) {
        console.error('Login error:', error);
        // Show generic error under password field
        const passwordError = document.getElementById('password-error');
        if (passwordError) {
            passwordError.innerText = 'Something went wrong. Please try again.';
            passwordError.classList.remove('hidden');
        } else {
            alert('Something went wrong. Please try again.');
        }
        btn.innerText = originalText;
        btn.disabled = false;
    }
});
</script>
@endsection
