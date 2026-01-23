@extends('website.layouts.master')

@section('title', 'Bulk Order Enquiry - ' . ($siteSettings['site_name'] ?? 'The Trusted Store'))

@section('content')

    <!-- BULK ORDER FORM SECTION -->
    <section class="w-full py-12 md:py-20 min-h-screen flex items-center justify-center">
        <div class="w-full max-w-[600px] mx-auto px-4">

            <!-- Form Header -->
            <div class="text-center mb-8">
                <h1 class="text-2xl md:text-3xl font-bold text-[#2B2B2B] mb-2">
                    Bulk Order Enquiry
                </h1>
                <p class="text-sm text-[#6B6B6B]">
                    Fill out this form and we will reach out to you as soon as possible.
                </p>
            </div>

            <!-- Dynamic Success Message Container -->
            <div id="successMessage"
                class="hidden mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-md text-sm text-center">
            </div>

            <!-- Form -->
            <form id="bulkOrderForm" class="space-y-4" novalidate>
                @csrf

                <!-- Full Name -->
                <div>
                    <input type="text" name="name" id="name" placeholder="Full Name"
                        class="w-full px-4 py-3 text-sm bg-white border border-gray-200 rounded md:rounded-md focus:outline-none focus:border-[#441227] placeholder-gray-400 transition-colors"
                        required>
                    <span class="text-xs text-red-500 hidden mt-1 error-message" id="error-name"></span>
                </div>

                <!-- Business Name -->
                <div>
                    <input type="text" name="business_name" id="business_name" placeholder="Business Name"
                        class="w-full px-4 py-3 text-sm bg-white border border-gray-200 rounded md:rounded-md focus:outline-none focus:border-[#441227] placeholder-gray-400 transition-colors"
                        required>
                    <span class="text-xs text-red-500 hidden mt-1 error-message" id="error-business_name"></span>
                </div>

                <!-- Email -->
                <div>
                    <input type="email" name="email" id="email" placeholder="Email"
                        class="w-full px-4 py-3 text-sm bg-white border border-gray-200 rounded md:rounded-md focus:outline-none focus:border-[#441227] placeholder-gray-400 transition-colors"
                        required>
                    <span class="text-xs text-red-500 hidden mt-1 error-message" id="error-email"></span>
                </div>

                <!-- Phone Number with Country Code -->
                <div class="flex gap-2">
                    <div class="w-24">
                        <div
                            class="w-full h-full bg-white border border-gray-200 rounded md:rounded-md flex items-center px-2">
                            <img src="https://flagcdn.com/w20/in.png" class="w-5 h-auto mr-2" alt="IN">
                            <select class="w-full bg-transparent text-sm focus:outline-none appearance-none cursor-pointer">
                                <option value="+91">▼</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex-1 relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-gray-500">+91</span>
                        <input type="tel" name="phone" id="phone" placeholder="Phone / WhatsApp Number"
                            class="w-full pl-12 pr-4 py-3 text-sm bg-white border border-gray-200 rounded md:rounded-md focus:outline-none focus:border-[#441227] placeholder-gray-400 transition-colors"
                            required>
                    </div>
                </div>
                <!-- Phone error needs to be outside the flex container to display correctly below it -->
                <span class="text-xs text-red-500 hidden mt-1 error-message block" id="error-phone"></span>

                <!-- City & State -->
                <div>
                    <input type="text" name="city_state" id="city_state" placeholder="City & State"
                        class="w-full px-4 py-3 text-sm bg-white border border-gray-200 rounded md:rounded-md focus:outline-none focus:border-[#441227] placeholder-gray-400 transition-colors"
                        required>
                    <span class="text-xs text-red-500 hidden mt-1 error-message" id="error-city_state"></span>
                </div>

                <!-- Products Required -->
                <div>
                    <input type="text" name="products_required" id="products_required" placeholder="Products Required"
                        class="w-full px-4 py-3 text-sm bg-white border border-gray-200 rounded md:rounded-md focus:outline-none focus:border-[#441227] placeholder-gray-400 transition-colors"
                        required>
                    <span class="text-xs text-red-500 hidden mt-1 error-message" id="error-products_required"></span>
                </div>

                <!-- Quantity Required -->
                <div>
                    <input type="text" name="quantity_required" id="quantity_required" placeholder="Quantity Required"
                        class="w-full px-4 py-3 text-sm bg-white border border-gray-200 rounded md:rounded-md focus:outline-none focus:border-[#441227] placeholder-gray-400 transition-colors"
                        required>
                    <span class="text-xs text-red-500 hidden mt-1 error-message" id="error-quantity_required"></span>
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit" id="submitBtn"
                        class="w-full bg-[#4A5D3F] hover:bg-[#3A4D2F] text-white font-medium text-sm py-3 rounded md:rounded-md transition-colors duration-200 flex items-center justify-center">
                        <span id="btnText">Send Request</span>
                        <svg id="btnLoader" class="animate-spin ml-2 h-4 w-4 text-white hidden"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </button>
                </div>

                <!-- Privacy Notice -->
                <div class="text-center pt-2">
                    <p class="text-[10px] md:text-xs text-[#6B6B6B] leading-tight max-w-lg mx-auto">
                        By signing up, you agree to receive marketing emails and text messages. View our
                        <a href="#" class="underline hover:text-[#4A0F23]">privacy policy</a> and
                        <a href="#" class="underline hover:text-[#4A0F23]">terms of service</a> for more info.
                    </p>
                </div>

            </form>

        </div>
    </section>

    <!-- Vanilla JS AJAX Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('bulkOrderForm');
            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const btnLoader = document.getElementById('btnLoader');
            const successMessage = document.getElementById('successMessage');

            form.addEventListener('submit', function (e) {
                e.preventDefault();

                // Reset errors and messages
                hideErrors();
                successMessage.classList.add('hidden');
                successMessage.textContent = '';

                // Loading state
                setLoading(true);

                // Prepare data
                const formData = new FormData(form);
                const data = Object.fromEntries(formData.entries());

                // Fetch options
                const options = {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                };

                // Send request
                fetch("{{ route('bulk-orders.store') }}", options)
                    .then(response => {
                        return response.json().then(data => {
                            if (!response.ok) {
                                throw { status: response.status, data: data };
                            }
                            return data;
                        });
                    })
                    .then(data => {
                        // Success
                        form.reset();
                        successMessage.textContent = data.message || 'Request submitted successfully!';
                        successMessage.classList.remove('hidden');

                        // Scroll to success message
                        successMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    })
                    .catch(error => {
                        console.error('Error:', error);

                        if (error.status === 422 && error.data.errors) {
                            // Validation errors
                            showErrors(error.data.errors);
                        } else {
                            // General error
                            alert('Something went wrong. Please try again later.');
                        }
                    })
                    .finally(() => {
                        setLoading(false);
                    });
            });

            function setLoading(isLoading) {
                if (isLoading) {
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
                    btnText.textContent = 'Sending...';
                    btnLoader.classList.remove('hidden');
                } else {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-75', 'cursor-not-allowed');
                    btnText.textContent = 'Send Request';
                    btnLoader.classList.add('hidden');
                }
            }

            function hideErrors() {
                const errorElements = document.querySelectorAll('.error-message');
                errorElements.forEach(el => {
                    el.classList.add('hidden');
                    el.textContent = '';
                });

                const inputs = form.querySelectorAll('input');
                inputs.forEach(input => {
                    input.classList.remove('border-red-500');
                });
            }

            function showErrors(errors) {
                for (const [field, messages] of Object.entries(errors)) {
                    const errorElement = document.getElementById(`error-${field}`);
                    const inputElement = document.getElementById(field);

                    if (errorElement) {
                        errorElement.textContent = messages[0];
                        errorElement.classList.remove('hidden');
                    }

                    if (inputElement) {
                        inputElement.classList.add('border-red-500');

                        // Add shake animation effect
                        inputElement.classList.add('animate-pulse');
                        setTimeout(() => inputElement.classList.remove('animate-pulse'), 500);
                    }
                }
            }
        });
    </script>

@endsection