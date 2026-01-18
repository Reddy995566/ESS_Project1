@extends('website.layouts.master')

@section('title', 'Contact Us - ' . ($siteSettings['site_name'] ?? 'Fashion Store'))

@section('content')

    <!-- CONTACT FORM SECTION -->
    <section class="w-full py-12 md:py-16">
        <div class="max-w-[800px] mx-auto px-4">

            <div class="text-center mb-10">
                <h1 class="text-3xl md:text-3xl font-normal text-[#2A1810] mb-2 font-handwritten"
                    style="font-family: 'Playfair Display', serif;">
                    Contact Form
                </h1>
                <p class="text-sm text-[#5C3A2E] font-medium">
                    Please complete the following form to send us an email
                </p>
            </div>

            <!-- Dynamic Success Message Container -->
            <div id="successMessage"
                class="hidden mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-md text-sm text-center">
            </div>

            <form id="contactForm" class="space-y-4" novalidate>
                @csrf

                <!-- Your Name -->
                <div>
                    <input type="text" name="name" id="name" placeholder="Your Name"
                        class="w-full px-4 py-3 bg-[#FAF5ED] border border-[#D6B27A]/50 rounded-sm focus:outline-none focus:border-[#4A0F23] placeholder-[#5C3A2E]/60 text-[#2A1810]"
                        required>
                    <span class="text-xs text-red-500 hidden mt-1 error-message" id="error-name"></span>
                </div>

                <!-- Your Email -->
                <div>
                    <input type="email" name="email" id="email" placeholder="Your Email"
                        class="w-full px-4 py-3 bg-[#FAF5ED] border border-[#D6B27A]/50 rounded-sm focus:outline-none focus:border-[#4A0F23] placeholder-[#5C3A2E]/60 text-[#2A1810]"
                        required>
                    <span class="text-xs text-red-500 hidden mt-1 error-message" id="error-email"></span>
                </div>

                <!-- Your Phone -->
                <div>
                    <input type="tel" name="phone" id="phone" placeholder="Your Phone (optional)"
                        class="w-full px-4 py-3 bg-[#FAF5ED] border border-[#D6B27A]/50 rounded-sm focus:outline-none focus:border-[#4A0F23] placeholder-[#5C3A2E]/60 text-[#2A1810]">
                    <span class="text-xs text-red-500 hidden mt-1 error-message" id="error-phone"></span>
                </div>

                <!-- Your Message -->
                <div>
                    <textarea name="message" id="message" rows="6" placeholder="Your Message"
                        class="w-full px-4 py-3 bg-[#FAF5ED] border border-[#D6B27A]/50 rounded-sm focus:outline-none focus:border-[#4A0F23] placeholder-[#5C3A2E]/60 text-[#2A1810] resize-none"
                        required></textarea>
                    <span class="text-xs text-red-500 hidden mt-1 error-message" id="error-message"></span>
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit" id="submitBtn"
                        class="w-full bg-[#441227] hover:bg-[#380b1d] text-white font-medium text-base py-3 rounded-sm transition-colors duration-200 flex items-center justify-center">
                        <span id="btnText">Send</span>
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

            </form>

        </div>
    </section>

    <!-- Vanilla JS AJAX Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('contactForm');
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
                fetch("{{ route('contact.store') }}", options)
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
                        successMessage.textContent = data.message || 'Thank you for contacting us! We will get back to you soon.';
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
                    btnText.textContent = 'Send';
                    btnLoader.classList.add('hidden');
                }
            }

            function hideErrors() {
                const errorElements = document.querySelectorAll('.error-message');
                errorElements.forEach(el => {
                    el.classList.add('hidden');
                    el.textContent = '';
                });

                const inputs = form.querySelectorAll('input, textarea');
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

    <!-- FAQ SECTION -->
    <section class="w-full pb-20 pt-4">
        <div class="text-center">
            <h2 class="text-2xl md:text-3xl font-handwritten font-normal text-[#2A1810] mb-2"
                style="font-family: 'Playfair Display', serif;">
                Looking For FAQs?
            </h2>
            <p class="text-xs md:text-sm text-[#5C3A2E]/80 mb-6">
                View answers to some of the most common questions.
            </p>

            <a href="{{ route('faqs') }}"
                class="inline-block px-10 py-2 border border-[#441227] text-[#441227] text-sm font-medium hover:bg-[#441227] hover:text-white transition-all duration-300 rounded-sm">
                Read FAQs
            </a>
        </div>
    </section>

@endsection