@extends('website.layouts.master')

@section('title', 'Forgot Password')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8" style="background: linear-gradient(135deg, var(--color-bg-primary) 0%, var(--color-bg-secondary) 100%);">
    <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-2xl shadow-2xl">
        <!-- Header -->
        <div class="text-center">
            <div class="mx-auto h-16 w-16 bg-[#3D0C1F] rounded-full flex items-center justify-center mb-4">
                <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                </svg>
            </div>
            <h2 class="font-serif-elegant text-3xl font-bold text-[#3D0C1F] tracking-wide">Forgot Password?</h2>
            <p class="mt-2 text-sm text-gray-600">No worries, we'll send you reset instructions</p>
        </div>

        <!-- Success Message -->
        <div id="successMessage" class="hidden bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
            <div class="flex items-center">
                <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span id="successText"></span>
            </div>
        </div>

        <!-- Forgot Password Form -->
        <form id="forgotPasswordForm" class="mt-8 space-y-6">
            @csrf
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

            <!-- Submit Button -->
            <div>
                <button 
                    type="submit" 
                    id="submitBtn"
                    class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-[#3D0C1F] hover:bg-[#2a0815] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#3D0C1F] transition-all duration-200 shadow-lg hover:shadow-xl"
                >
                    <svg id="spinner" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span id="btnText">Send Reset Link</span>
                </button>
            </div>

            <!-- Back to Login -->
            <div class="text-center">
                <a href="{{ route('login') }}" class="flex items-center justify-center text-sm font-medium text-[#3D0C1F] hover:text-[#2a0815]">
                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Login
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('forgotPasswordForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const btn = document.getElementById('submitBtn');
    const btnText = document.getElementById('btnText');
    const spinner = document.getElementById('spinner');
    const successMessage = document.getElementById('successMessage');
    
    btn.disabled = true;
    spinner.classList.remove('hidden');
    btnText.innerText = 'Sending...';
    successMessage.classList.add('hidden');

    // Clear previous errors
    document.querySelectorAll('[id$="-error"]').forEach(el => {
        el.classList.add('hidden');
        el.innerText = '';
    });

    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());

    try {
        const response = await fetch('{{ route("password.email") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (result.success) {
            successMessage.classList.remove('hidden');
            document.getElementById('successText').innerText = result.message;
            document.getElementById('email').value = '';
        } else {
            if (result.errors) {
                Object.keys(result.errors).forEach(key => {
                    const errorEl = document.getElementById(key + '-error');
                    if (errorEl) {
                        errorEl.innerText = result.errors[key][0];
                        errorEl.classList.remove('hidden');
                    }
                });
            } else {
                const emailError = document.getElementById('email-error');
                emailError.innerText = result.message || 'Failed to send reset link';
                emailError.classList.remove('hidden');
            }
        }
    } catch (error) {
        console.error('Error:', error);
        const emailError = document.getElementById('email-error');
        emailError.innerText = 'Something went wrong. Please try again.';
        emailError.classList.remove('hidden');
    } finally {
        btn.disabled = false;
        spinner.classList.add('hidden');
        btnText.innerText = 'Send Reset Link';
    }
});
</script>
@endsection
