@extends('website.layouts.master')

@section('title', 'Checkout - ' . ($siteSettings['site_name'] ?? 'Fashion Store'))

@section('content')
<style>
    .form-select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 0.5rem center;
        background-repeat: no-repeat;
        background-size: 1.5em 1.5em;
        padding-right: 2.5rem;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
</style>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

    <!-- Flash Messages -->
    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 mt-6">
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm relative" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        </div>
    @endif
    @if ($errors->any())
    <div class="max-w-7xl mx-auto px-4 mt-6">
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm relative">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <div class="flex flex-col lg:flex-row min-h-screen">
        
        <!-- LEFT COLUMN - FORMS (58%) -->
        <div class="w-full lg:w-[58%] border-r border-[#e5e7eb] px-4 py-8 lg:px-12 lg:py-14 bg-[#fff7ec]">
            
            <form action="{{ route('checkout.store') }}" method="POST" id="checkoutForm">
            @csrf

            <!-- Breadcrumbs -->
            <div class="flex items-center text-xs text-[#4b0f27] space-x-2 mb-8">
                <span>Cart</span>
                <span class="text-gray-400">&gt;</span>
                <span class="font-medium">Information</span>
                <span class="text-gray-400">&gt;</span>
                <span class="text-gray-500">Shipping</span>
                <span class="text-gray-400">&gt;</span>
                <span class="text-gray-500">Payment</span>
            </div>

            <!-- Contact Section -->
            <div class="mb-8">
                <div class="flex justify-between items-center mb-3">
                    <h2 class="text-lg font-medium text-[#333]">Contact</h2>
                    @auth
                        <span class="text-sm text-gray-600">Logged in as {{ Auth::user()->email }}</span>
                    @else
                         <a href="{{ route('login') }}" class="text-sm text-[#4b0f27] underline decoration-solid underline-offset-2">Sign in</a>
                    @endauth
                </div>
                
                <input type="email" name="email" value="{{ Auth::user()->email ?? old('email') }}" readonly class="w-full h-12 px-3 border border-gray-300 rounded bg-gray-100 text-gray-500 text-sm mb-3 shadow-sm cursor-not-allowed">
            </div>

            <!-- Addresses Section -->
            <div class="mb-10">
                <h2 class="text-lg font-medium text-[#333] mb-4">Delivery Address</h2>
                
                @if($addresses->count() > 0)
                    <div class="space-y-4 mb-6">
                        @foreach($addresses as $addr)
                        <label class="flex items-start p-4 border border-gray-200 rounded cursor-pointer hover:border-[#4b0f27] transition-colors relative bg-white">
                            <input type="radio" name="shipping_address_id" value="{{ $addr->id }}" class="w-4 h-4 mt-1 text-[#4b0f27] border-gray-300 focus:ring-[#4b0f27] accent-[#4b0f27]" onchange="toggleNewAddress(false)" {{ (old('shipping_address_id') == $addr->id || ($loop->first && !old('shipping_address_id'))) ? 'checked' : '' }}>
                            <div class="ml-3">
                                <span class="block text-sm font-medium text-gray-900">{{ $addr->full_name }}</span>
                                <span class="block text-sm text-gray-500">{{ $addr->address }}, {{ $addr->apartment ? $addr->apartment . ', ' : '' }} {{ $addr->city }}</span>
                                <span class="block text-sm text-gray-500">{{ $addr->state }} - {{ $addr->pincode }}</span>
                                <span class="block text-sm text-gray-500">Phone: {{ $addr->phone }}</span>
                            </div>
                        </label>
                        @endforeach
                        
                        <label class="flex items-center p-4 border border-gray-200 rounded cursor-pointer hover:border-[#4b0f27] transition-colors bg-white">
                            <input type="radio" name="shipping_address_id" value="" class="w-4 h-4 text-[#4b0f27] border-gray-300 focus:ring-[#4b0f27] accent-[#4b0f27]" onchange="toggleNewAddress(true)" id="newAddressRadio" {{ old('shipping_address_id') === null && session()->has('errors') ? 'checked' : '' }}>
                            <span class="ml-3 text-sm font-medium text-gray-900">Use a new address</span>
                        </label>
                    </div>
                @endif

                <div id="newAddressForm" class="{{ ($addresses->count() > 0 && !(old('shipping_address_id') === null && session()->has('errors'))) ? 'hidden' : '' }} space-y-3">
                    <!-- Country -->
                    <div class="relative">
                        <select name="country" class="w-full h-12 px-3 border border-gray-300 rounded focus:ring-1 focus:ring-[#4b0f27] focus:border-[#4b0f27] outline-none text-gray-700 text-sm bg-white shadow-sm appearance-none form-select">
                            <option value="India">India</option>
                        </select>
                        <label class="absolute text-[10px] text-gray-500 top-2 left-3 opacity-0">Country/Region</label>
                    </div>

                    <!-- Name Row -->
                    <div class="flex gap-3">
                        <div class="w-1/2">
                            <input type="text" name="first_name" placeholder="First name" value="{{ old('first_name', Auth::user()->name) }}" class="w-full h-12 px-3 border border-gray-300 rounded focus:ring-1 focus:ring-[#4b0f27] focus:border-[#4b0f27] outline-none placeholder-gray-500 text-sm bg-white shadow-sm transition-shadow text-gray-800">
                            <span class="error-message text-red-600 text-xs mt-1 hidden" data-field="first_name"></span>
                        </div>
                        <div class="w-1/2">
                            <input type="text" name="last_name" placeholder="Last name (optional)" value="{{ old('last_name') }}" class="w-full h-12 px-3 border border-gray-300 rounded focus:ring-1 focus:ring-[#4b0f27] focus:border-[#4b0f27] outline-none placeholder-gray-500 text-sm bg-white shadow-sm transition-shadow text-gray-800">
                            <span class="error-message text-red-600 text-xs mt-1 hidden" data-field="last_name"></span>
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="relative">
                        <input type="text" name="address" placeholder="Address" value="{{ old('address') }}" class="w-full h-12 px-3 border border-gray-300 rounded focus:ring-1 focus:ring-[#4b0f27] focus:border-[#4b0f27] outline-none placeholder-gray-500 text-sm bg-white shadow-sm transition-shadow text-gray-800">
                        <span class="error-message text-red-600 text-xs mt-1 hidden" data-field="address"></span>
                    </div>

                    <!-- Apartment -->
                    <input type="text" name="apartment" placeholder="Apartment, suite, etc. (optional)" value="{{ old('apartment') }}" class="w-full h-12 px-3 border border-gray-300 rounded focus:ring-1 focus:ring-[#4b0f27] focus:border-[#4b0f27] outline-none placeholder-gray-500 text-sm bg-white shadow-sm transition-shadow">

                    <!-- City State Zip -->
                    <div class="flex gap-3">
                        <div class="w-1/3">
                            <input type="text" name="city" placeholder="City" value="{{ old('city') }}" class="w-full h-12 px-3 border border-gray-300 rounded focus:ring-1 focus:ring-[#4b0f27] focus:border-[#4b0f27] outline-none placeholder-gray-500 text-sm bg-white shadow-sm transition-shadow text-gray-800">
                            <span class="error-message text-red-600 text-xs mt-1 hidden" data-field="city"></span>
                        </div>
                        <div class="w-1/3 relative">
                            <select name="state" class="w-full h-12 px-3 border border-gray-300 rounded focus:ring-1 focus:ring-[#4b0f27] focus:border-[#4b0f27] outline-none text-gray-800 text-sm bg-white shadow-sm appearance-none form-select">
                                <option value="">Select State</option>
                                <option value="West Bengal" {{ old('state') == 'West Bengal' ? 'selected' : '' }}>West Bengal</option>
                                <option value="Maharashtra" {{ old('state') == 'Maharashtra' ? 'selected' : '' }}>Maharashtra</option>
                                <option value="Delhi" {{ old('state') == 'Delhi' ? 'selected' : '' }}>Delhi</option>
                                <option value="Karnataka" {{ old('state') == 'Karnataka' ? 'selected' : '' }}>Karnataka</option>
                                <!-- Add more states as needed -->
                            </select>
                            <span class="error-message text-red-600 text-xs mt-1 hidden" data-field="state"></span>
                        </div>
                        <div class="w-1/3">
                            <input type="text" name="zipcode" placeholder="PIN code" value="{{ old('zipcode') }}" class="w-full h-12 px-3 border border-gray-300 rounded focus:ring-1 focus:ring-[#4b0f27] focus:border-[#4b0f27] outline-none placeholder-gray-500 text-sm bg-white shadow-sm transition-shadow text-gray-800">
                            <span class="error-message text-red-600 text-xs mt-1 hidden" data-field="zipcode"></span>
                        </div>
                    </div>

                    <!-- Phone -->
                    <div class="relative">
                        <input type="text" name="phone" placeholder="Phone" value="{{ old('phone', Auth::user()->mobile) }}" class="w-full h-12 pl-3 pr-10 border border-gray-300 rounded focus:ring-1 focus:ring-[#4b0f27] focus:border-[#4b0f27] outline-none placeholder-gray-500 text-sm bg-white shadow-sm transition-shadow text-gray-800">
                        <span class="error-message text-red-600 text-xs mt-1 hidden" data-field="phone"></span>
                    </div>
                </div>
            </div>

            <!-- Shipping Method -->
            <div class="mb-10">
                <h2 class="text-lg font-medium text-[#333] mb-4">Shipping method</h2>
                <div class="flex justify-between items-center p-4 border border-[#4b0f27] bg-[#fdf2f2] rounded bg-opacity-30">
                     <span class="text-sm text-gray-800">Standard Shipping</span>
                     <span class="text-sm font-bold text-[#333]">FREE</span>
                </div>
            </div>

            <!-- Billing Address Section -->
            <div class="mb-10">
                <h2 class="text-lg font-medium text-[#333] mb-4">Billing address</h2>
                
                <div class="border border-gray-200 rounded overflow-hidden mb-4">
                    <label class="flex items-center p-4 border-b border-gray-200 cursor-pointer hover:bg-gray-50 bg-white">
                        <input type="radio" name="billing_selection" value="same" class="w-4 h-4 text-[#4b0f27] border-gray-300 focus:ring-[#4b0f27] accent-[#4b0f27]" checked onchange="toggleBillingAddress(false)">
                        <span class="ml-3 text-sm font-medium text-gray-900">Same as shipping address</span>
                    </label>
                    <label class="flex items-center p-4 cursor-pointer hover:bg-gray-50 bg-white">
                        <input type="radio" name="billing_selection" value="different" class="w-4 h-4 text-[#4b0f27] border-gray-300 focus:ring-[#4b0f27] accent-[#4b0f27]" onchange="toggleBillingAddress(true)">
                        <span class="ml-3 text-sm font-medium text-gray-900">Use a different billing address</span>
                    </label>
                </div>

                <div id="billingAddressForm" class="hidden bg-gray-50 p-4 rounded border border-gray-200 space-y-3">
                     <!-- Country -->
                     <div class="relative">
                        <input type="text" name="billing_country" value="India" readonly class="w-full h-12 px-3 border border-gray-300 rounded bg-gray-100 text-gray-600 outline-none text-sm shadow-sm cursor-not-allowed">
                        <label class="absolute text-[10px] text-gray-500 top-2 left-3 opacity-0">Country/Region</label>
                    </div>

                    <!-- Name Row -->
                    <div class="flex gap-3">
                        <div class="w-1/2">
                            <input type="text" name="billing_first_name" placeholder="First name" class="w-full h-12 px-3 border border-gray-300 rounded focus:ring-1 focus:ring-[#4b0f27] focus:border-[#4b0f27] outline-none placeholder-gray-500 text-sm bg-white shadow-sm transition-shadow text-gray-800">
                        </div>
                        <div class="w-1/2">
                            <input type="text" name="billing_last_name" placeholder="Last name" class="w-full h-12 px-3 border border-gray-300 rounded focus:ring-1 focus:ring-[#4b0f27] focus:border-[#4b0f27] outline-none placeholder-gray-500 text-sm bg-white shadow-sm transition-shadow text-gray-800">
                        </div>
                    </div>

                     <!-- Address -->
                     <div class="relative">
                        <input type="text" name="billing_address" placeholder="Address" class="w-full h-12 px-3 border border-gray-300 rounded focus:ring-1 focus:ring-[#4b0f27] focus:border-[#4b0f27] outline-none placeholder-gray-500 text-sm bg-white shadow-sm transition-shadow text-gray-800">
                    </div>

                    <!-- Apartment -->
                    <input type="text" name="billing_address_line_2" placeholder="Apartment, suite, etc. (optional)" class="w-full h-12 px-3 border border-gray-300 rounded focus:ring-1 focus:ring-[#4b0f27] focus:border-[#4b0f27] outline-none placeholder-gray-500 text-sm bg-white shadow-sm transition-shadow">

                    <!-- City State Zip -->
                    <div class="flex gap-3">
                        <div class="w-1/3">
                            <input type="text" name="billing_city" placeholder="City" class="w-full h-12 px-3 border border-gray-300 rounded focus:ring-1 focus:ring-[#4b0f27] focus:border-[#4b0f27] outline-none placeholder-gray-500 text-sm bg-white shadow-sm transition-shadow text-gray-800">
                        </div>
                        <div class="w-1/3 relative">
                            <select name="billing_state" class="w-full h-12 px-3 border border-gray-300 rounded focus:ring-1 focus:ring-[#4b0f27] focus:border-[#4b0f27] outline-none text-gray-800 text-sm bg-white shadow-sm appearance-none form-select">
                                <option value="">Select State</option>
                                <option value="West Bengal">West Bengal</option>
                                <option value="Maharashtra">Maharashtra</option>
                                <option value="Delhi">Delhi</option>
                                <option value="Karnataka">Karnataka</option>
                                <!-- Add more states as needed -->
                            </select>
                        </div>
                        <div class="w-1/3">
                            <input type="text" name="billing_zipcode" placeholder="PIN code" class="w-full h-12 px-3 border border-gray-300 rounded focus:ring-1 focus:ring-[#4b0f27] focus:border-[#4b0f27] outline-none placeholder-gray-500 text-sm bg-white shadow-sm transition-shadow text-gray-800">
                        </div>
                    </div>

                    <!-- Phone -->
                    <div class="relative">
                        <input type="text" name="billing_phone" placeholder="Phone" class="w-full h-12 pl-3 pr-10 border border-gray-300 rounded focus:ring-1 focus:ring-[#4b0f27] focus:border-[#4b0f27] outline-none placeholder-gray-500 text-sm bg-white shadow-sm transition-shadow text-gray-800">
                    </div>
                </div>
            </div>

            <!-- Payment -->
            <div class="mb-10">
                <h2 class="text-lg font-medium text-[#333] mb-1">Payment</h2>
                <p class="text-sm text-gray-500 mb-4">All transactions are secure and encrypted.</p>

                <div class="border border-gray-200 rounded overflow-hidden">
                    <!-- Razorpay Section -->
                    <label class="bg-[#fdf2f2] bg-opacity-30 p-4 border-b border-[#4b0f27] border-opacity-100 flex items-start cursor-pointer">
                         <div class="flex items-center h-5">
                             <input type="radio" name="payment_method" value="online" checked class="w-4 h-4 border-gray-300 text-[#4b0f27] focus:ring-[#4b0f27] accent-[#4b0f27]">
                         </div>
                         <div class="ml-3 w-full">
                             <div class="flex justify-between items-center w-full">
                                 <span class="text-sm font-medium text-[#333]">Razorpay Secure (UPI, Cards, Int'l Cards, Wallets)</span>
                                 <div class="flex space-x-1">
                                     <img src="https://cdn.shopify.com/shopifycloud/checkout_web/assets/016969563dbad11263d76e8be94dc745.svg" class="h-5">
                                 </div>
                             </div>
                         </div>
                    </label>

                    <!-- COD Section -->
                    <label class="p-4 flex items-center cursor-pointer hover:bg-gray-50 transition">
                         <input type="radio" name="payment_method" value="cod" class="w-4 h-4 border-gray-300 text-[#4b0f27] focus:ring-[#4b0f27] accent-[#4b0f27]">
                         <span class="ml-3 text-sm font-medium text-gray-800">Cash on Delivery (COD)</span>
                    </label>
                </div>
            </div>

            <!-- Pay Button -->
            <button type="submit" class="w-full bg-[#4b0f27] hover:bg-[#3a0a1e] text-white h-12 rounded font-medium text-lg transition-colors shadow-sm mb-12 flex items-center justify-center">
                <span>Complete Order</span>
            </button>
            
            </form>

            <!-- Footer Links -->
            <div class="pt-4 flex flex-wrap gap-4 text-xs text-[#4b0f27]">
                <a href="{{ route('refund-policy') }}" class="hover:opacity-70 transition-opacity">Refund policy</a>
                <a href="{{ route('shipping-policy') }}" class="hover:opacity-70 transition-opacity">Shipping</a>
                <a href="{{ route('privacy-policy') }}" class="hover:opacity-70 transition-opacity">Privacy policy</a>
                <a href="{{ route('terms-of-service') }}" class="hover:opacity-70 transition-opacity">Terms of service</a>
                <a href="{{ route('contact') }}" class="hover:opacity-70 transition-opacity">Contact</a>
            </div>

        </div>


        <!-- RIGHT COLUMN - SUMMARY (42%) -->
        <div class="hidden lg:block w-[42%] bg-[#fff1e4] border-l border-[#e5e7eb] px-8 py-14">
            <div class="sticky top-14 max-w-[400px]">
                
                @foreach($cart as $item)
                <!-- Product Row -->
                <div class="flex gap-4 mb-6">
                    <div class="relative w-16 h-16 border border-gray-200 rounded bg-white flex-shrink-0">
                         <img src="{{ $item['image'] ?? 'https://via.placeholder.com/150' }}" class="w-full h-full object-cover rounded" loading="lazy">
                         <div class="absolute -top-2 -right-2 w-5 h-5 bg-gray-500 text-white rounded-full flex items-center justify-center text-xs font-medium bg-opacity-90">{{ $item['quantity'] }}</div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-sm font-medium text-gray-800">{{ $item['name'] }}</h3>
                        @if(isset($item['color_name']))
                        <p class="text-[10px] text-gray-500 mt-1 flex items-center gap-1">
                             Color: {{ $item['color_name'] }}
                        </p>
                        @endif
                    </div>
                    <div class="text-right">
                         <p class="text-sm font-medium text-gray-800">Rs. {{ number_format($item['price'] * $item['quantity']) }}</p>
                    </div>
                </div>
                @endforeach

                <!-- Price Breakdown -->
                <div class="space-y-3 mb-6 border-t border-gray-200 pt-6">
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Subtotal</span>
                        <span class="font-medium text-gray-800">Rs. {{ number_format($subtotal) }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-600">
                        <div class="flex items-center gap-1">
                            <span>Shipping</span>
                        </div>
                        <span class="text-xs font-medium tracking-wide">FREE</span>
                    </div>
                </div>

                <!-- Total -->
                <div class="border-t border-gray-200 pt-6 flex justify-between items-baseline mb-1">
                    <span class="text-lg font-medium text-[#333]">Total</span>
                    <div class="text-right">
                        <span class="text-xs text-gray-500 mr-2">INR</span>
                        <span class="text-2xl font-medium text-[#333]">Rs. {{ number_format($total) }}</span>
                    </div>
                </div>
                
                <div class="flex justify-end items-center text-sm font-medium text-[#333] gap-1 mt-4">
                     <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                     Secure Checkout
                </div>

            </div>
        </div>
    </div>

    <script>
        function toggleNewAddress(show) {
            const form = document.getElementById('newAddressForm');
            if (show) {
                form.classList.remove('hidden');
            } else {
                form.classList.add('hidden');
            }
        }

        function toggleBillingAddress(show) {
            const form = document.getElementById('billingAddressForm');
            if (show) {
                form.classList.remove('hidden');
            } else {
                form.classList.add('hidden');
            }
        }

        // Clear all validation errors
        function clearErrors() {
            document.querySelectorAll('.error-message').forEach(el => {
                el.classList.add('hidden');
                el.textContent = '';
            });
            document.querySelectorAll('input, select').forEach(el => {
                el.classList.remove('border-red-500');
            });
        }

        // Display validation errors
        function showErrors(errors) {
            clearErrors();
            
            for (const [field, messages] of Object.entries(errors)) {
                const errorSpan = document.querySelector(`.error-message[data-field="${field}"]`);
                const inputField = document.querySelector(`[name="${field}"]`);
                
                if (errorSpan && inputField) {
                    errorSpan.textContent = messages[0];
                    errorSpan.classList.remove('hidden');
                    inputField.classList.add('border-red-500');
                    
                    // Scroll to first error
                    if (Object.keys(errors)[0] === field) {
                        inputField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
            }
        }

        // Razorpay Integration
        document.getElementById('checkoutForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            clearErrors();
            
            const formData = new FormData(this);
            const paymentMethod = formData.get('payment_method');
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span>Processing...</span>';
            
            try {
                const response = await fetch('{{ route("checkout.store") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                if(!response.ok) {
                    // Handle validation errors
                    if (data.errors) {
                        showErrors(data.errors);
                    } else if (data.message) {
                        // Show general error at top
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm mb-4';
                        errorDiv.textContent = data.message;
                        document.getElementById('checkoutForm').insertBefore(errorDiv, document.getElementById('checkoutForm').firstChild);
                        setTimeout(() => errorDiv.remove(), 5000);
                    }
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                    return;
                }
                
                if(data.success && paymentMethod === 'online') {
                    // Open Razorpay modal
                    const options = {
                        key: '{{ $razorpayKey }}',
                        amount: data.amount * 100,
                        currency: 'INR',
                        name: 'Fashion Store',
                        description: 'Order Payment',
                        order_id: data.razorpay_order_id,
                        prefill: {
                            name: data.name,
                            email: data.email,
                            contact: data.contact
                        },
                        theme: {
                            color: '#4b0f27'
                        },
                        handler: async function(response) {
                            // Payment successful, verify on backend
                            try {
                                const verifyResponse = await fetch('{{ route("checkout.verify") }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({
                                        razorpay_order_id: response.razorpay_order_id,
                                        razorpay_payment_id: response.razorpay_payment_id,
                                        razorpay_signature: response.razorpay_signature
                                    })
                                });
                                
                                const verifyData = await verifyResponse.json();
                                
                                if(verifyData.success) {
                                    window.location.href = '{{ url("/order/success") }}/' + verifyData.order_id;
                                } else {
                                    const errorDiv = document.createElement('div');
                                    errorDiv.className = 'bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm mb-4';
                                    errorDiv.textContent = 'Payment verification failed. Please contact support.';
                                    document.getElementById('checkoutForm').insertBefore(errorDiv, document.getElementById('checkoutForm').firstChild);
                                    submitBtn.disabled = false;
                                    submitBtn.innerHTML = originalBtnText;
                                }
                            } catch(error) {
                                console.error('Verification error:', error);
                                const errorDiv = document.createElement('div');
                                errorDiv.className = 'bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm mb-4';
                                errorDiv.textContent = 'Payment verification failed. Please contact support.';
                                document.getElementById('checkoutForm').insertBefore(errorDiv, document.getElementById('checkoutForm').firstChild);
                                submitBtn.disabled = false;
                                submitBtn.innerHTML = originalBtnText;
                            }
                        },
                        modal: {
                            ondismiss: function() {
                                submitBtn.disabled = false;
                                submitBtn.innerHTML = originalBtnText;
                            }
                        }
                    };
                    
                    const rzp = new Razorpay(options);
                    rzp.open();
                    
                } else if(response.ok && paymentMethod === 'cod') {
                    // For COD, redirect directly
                    window.location.href = data.redirect || '{{ route("order.success", ":id") }}'.replace(':id', data.order_id);
                } else {
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm mb-4';
                    errorDiv.textContent = data.message || 'Order placement failed';
                    document.getElementById('checkoutForm').insertBefore(errorDiv, document.getElementById('checkoutForm').firstChild);
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                }
                
            } catch(error) {
                console.error('Checkout error:', error);
                const errorDiv = document.createElement('div');
                errorDiv.className = 'bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm mb-4';
                errorDiv.textContent = 'Something went wrong. Please try again.';
                document.getElementById('checkoutForm').insertBefore(errorDiv, document.getElementById('checkoutForm').firstChild);
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            }
        });
    </script>
@endsection
