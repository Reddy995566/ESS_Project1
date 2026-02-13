@extends('website.layouts.checkout')

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
        <div class="w-full lg:w-[58%] border-r border-[#e5e7eb] px-4 py-8 lg:px-12 lg:py-14" style="background-color: var(--color-bg-primary);">
            
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
                            <input type="text" name="first_name" placeholder="First name" value="{{ old('first_name', optional($lastOrder)->first_name ?? Auth::user()->name) }}" class="w-full h-12 px-3 border border-gray-300 rounded focus:ring-1 focus:ring-[#4b0f27] focus:border-[#4b0f27] outline-none placeholder-gray-500 text-sm bg-white shadow-sm transition-shadow text-gray-800">
                            <span class="error-message text-red-600 text-xs mt-1 hidden" data-field="first_name"></span>
                        </div>
                        <div class="w-1/2">
                            <input type="text" name="last_name" placeholder="Last name (optional)" value="{{ old('last_name', optional($lastOrder)->last_name ?? '') }}" class="w-full h-12 px-3 border border-gray-300 rounded focus:ring-1 focus:ring-[#4b0f27] focus:border-[#4b0f27] outline-none placeholder-gray-500 text-sm bg-white shadow-sm transition-shadow text-gray-800">
                            <span class="error-message text-red-600 text-xs mt-1 hidden" data-field="last_name"></span>
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="relative">
                        <input type="text" name="address" placeholder="Address" value="{{ old('address', optional($lastOrder)->address ?? '') }}" class="w-full h-12 px-3 border border-gray-300 rounded focus:ring-1 focus:ring-[#4b0f27] focus:border-[#4b0f27] outline-none placeholder-gray-500 text-sm bg-white shadow-sm transition-shadow text-gray-800">
                        <span class="error-message text-red-600 text-xs mt-1 hidden" data-field="address"></span>
                    </div>

                    <!-- Apartment -->
                    <input type="text" name="apartment" placeholder="Apartment, suite, etc. (optional)" value="{{ old('apartment', optional($lastOrder)->address_line_2 ?? '') }}" class="w-full h-12 px-3 border border-gray-300 rounded focus:ring-1 focus:ring-[#4b0f27] focus:border-[#4b0f27] outline-none placeholder-gray-500 text-sm bg-white shadow-sm transition-shadow">

                    <!-- Pincode, City, State -->
                    <div class="flex gap-3">
                        <div class="w-1/3">
                            <input type="text" name="pincode" placeholder="PIN Code" value="{{ old('pincode', optional($lastOrder)->zipcode ?? '') }}" class="w-full h-12 px-3 border border-gray-300 rounded focus:ring-1 focus:ring-[#4b0f27] focus:border-[#4b0f27] outline-none placeholder-gray-500 text-sm bg-white shadow-sm transition-shadow text-gray-800">
                            <span class="error-message text-red-600 text-xs mt-1 hidden" data-field="pincode"></span>
                        </div>
                        <div class="w-1/3">
                            <input type="text" name="city" placeholder="City" value="{{ old('city', optional($lastOrder)->city ?? '') }}" readonly class="w-full h-12 px-3 border border-gray-300 rounded focus:ring-1 focus:ring-[#4b0f27] focus:border-[#4b0f27] outline-none placeholder-gray-500 text-sm bg-gray-100 shadow-sm transition-shadow text-gray-600 cursor-not-allowed">
                            <span class="error-message text-red-600 text-xs mt-1 hidden" data-field="city"></span>
                        </div>
                        <div class="w-1/3">
                            <input type="text" name="state" placeholder="State" value="{{ old('state', optional($lastOrder)->state ?? '') }}" readonly class="w-full h-12 px-3 border border-gray-300 rounded focus:ring-1 focus:ring-[#4b0f27] focus:border-[#4b0f27] outline-none placeholder-gray-500 text-sm bg-gray-100 shadow-sm transition-shadow text-gray-600 cursor-not-allowed">
                            <span class="error-message text-red-600 text-xs mt-1 hidden" data-field="state"></span>
                        </div>
                    </div>

                    <!-- Phone -->
                    <div class="relative">
                        <input type="text" name="phone" placeholder="Phone" value="{{ old('phone', optional($lastOrder)->phone ?? Auth::user()->mobile) }}" class="w-full h-12 pl-3 pr-10 border border-gray-300 rounded focus:ring-1 focus:ring-[#4b0f27] focus:border-[#4b0f27] outline-none placeholder-gray-500 text-sm bg-white shadow-sm transition-shadow text-gray-800">
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
                            <input type="text" name="billing_first_name" placeholder="First name" value="{{ old('billing_first_name', optional($lastOrder)->billing_first_name ?? optional($lastOrder)->first_name ?? Auth::user()->name) }}" class="w-full h-12 px-3 border border-gray-300 rounded focus:ring-1 focus:ring-[#4b0f27] focus:border-[#4b0f27] outline-none placeholder-gray-500 text-sm bg-white shadow-sm transition-shadow text-gray-800">
                        </div>
                        <div class="w-1/2">
                            <input type="text" name="billing_last_name" placeholder="Last name" value="{{ old('billing_last_name', optional($lastOrder)->billing_last_name ?? optional($lastOrder)->last_name ?? '') }}" class="w-full h-12 px-3 border border-gray-300 rounded focus:ring-1 focus:ring-[#4b0f27] focus:border-[#4b0f27] outline-none placeholder-gray-500 text-sm bg-white shadow-sm transition-shadow text-gray-800">
                        </div>
                    </div>

                     <!-- Address -->
                     <div class="relative">
                        <input type="text" name="billing_address" placeholder="Address" value="{{ old('billing_address', optional($lastOrder)->billing_address ?? optional($lastOrder)->address ?? '') }}" class="w-full h-12 px-3 border border-gray-300 rounded focus:ring-1 focus:ring-[#4b0f27] focus:border-[#4b0f27] outline-none placeholder-gray-500 text-sm bg-white shadow-sm transition-shadow text-gray-800">
                    </div>

                    <!-- Apartment -->
                    <input type="text" name="billing_address_line_2" placeholder="Apartment, suite, etc. (optional)" value="{{ old('billing_address_line_2', optional($lastOrder)->billing_address_line_2 ?? optional($lastOrder)->address_line_2 ?? '') }}" class="w-full h-12 px-3 border border-gray-300 rounded focus:ring-1 focus:ring-[#4b0f27] focus:border-[#4b0f27] outline-none placeholder-gray-500 text-sm bg-white shadow-sm transition-shadow">

                    <!-- Pincode, City, State -->
                    <div class="flex gap-3">
                        <div class="w-1/3">
                            <input type="text" name="billing_pincode" placeholder="PIN Code" value="{{ old('billing_pincode', optional($lastOrder)->billing_zipcode ?? optional($lastOrder)->zipcode ?? '') }}" class="w-full h-12 px-3 border border-gray-300 rounded focus:ring-1 focus:ring-[#4b0f27] focus:border-[#4b0f27] outline-none placeholder-gray-500 text-sm bg-white shadow-sm transition-shadow text-gray-800">
                        </div>
                        <div class="w-1/3">
                            <input type="text" name="billing_city" placeholder="City" value="{{ old('billing_city', optional($lastOrder)->billing_city ?? optional($lastOrder)->city ?? '') }}" readonly class="w-full h-12 px-3 border border-gray-300 rounded focus:ring-1 focus:ring-[#4b0f27] focus:border-[#4b0f27] outline-none placeholder-gray-500 text-sm bg-gray-100 shadow-sm transition-shadow text-gray-600 cursor-not-allowed">
                        </div>
                        <div class="w-1/3">
                            <input type="text" name="billing_state" placeholder="State" value="{{ old('billing_state', optional($lastOrder)->billing_state ?? optional($lastOrder)->state ?? '') }}" readonly class="w-full h-12 px-3 border border-gray-300 rounded focus:ring-1 focus:ring-[#4b0f27] focus:border-[#4b0f27] outline-none placeholder-gray-500 text-sm bg-gray-100 shadow-sm transition-shadow text-gray-600 cursor-not-allowed">
                        </div>
                    </div>

                    <!-- Phone -->
                    <div class="relative">
                        <input type="text" name="billing_phone" placeholder="Phone" value="{{ old('billing_phone', optional($lastOrder)->billing_phone ?? optional($lastOrder)->phone ?? Auth::user()->mobile) }}" class="w-full h-12 pl-3 pr-10 border border-gray-300 rounded focus:ring-1 focus:ring-[#4b0f27] focus:border-[#4b0f27] outline-none placeholder-gray-500 text-sm bg-white shadow-sm transition-shadow text-gray-800">
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
                                 <div class="flex items-center space-x-1 text-xs text-gray-500">
                                     <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                         <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                     </svg>
                                     <span class="font-medium">Secure</span>
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
        <div class="hidden lg:block w-[42%] border-l border-[#e5e7eb] px-8 py-14" style="background-color: var(--color-bg-secondary);">
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
                        @if(isset($item['color_name']) || isset($item['size_abbr']) || isset($item['size_name']))
                        <p class="text-[10px] text-gray-500 mt-1 flex items-center gap-1">
                             @if(isset($item['color_name']))
                                Color: {{ $item['color_name'] }}
                             @endif
                             @if(isset($item['color_name']) && (isset($item['size_abbr']) || isset($item['size_name'])))
                                |
                             @endif
                             @if(isset($item['size_abbr']))
                                Size: {{ $item['size_abbr'] }}
                             @elseif(isset($item['size_name']))
                                Size: {{ $item['size_name'] }}
                             @endif
                        </p>
                        @endif
                    </div>
                    <div class="text-right">
                         <p class="text-sm font-medium text-gray-800">Rs. {{ number_format($item['price'] * $item['quantity']) }}</p>
                    </div>
                </div>
                @endforeach

                <!-- Discount Code Section -->
                <div class="mb-6 border-t border-gray-200 pt-6">
                    <div class="flex gap-2">
                        <input type="text" id="couponCode" placeholder="Discount code" class="flex-1 h-12 px-3 border border-gray-300 rounded focus:ring-1 focus:ring-[#4b0f27] focus:border-[#4b0f27] outline-none placeholder-gray-500 text-sm bg-white shadow-sm transition-shadow text-gray-800">
                        <button type="button" id="applyCouponBtn" class="px-6 h-12 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded font-medium text-sm transition-colors border border-gray-300">
                            Apply
                        </button>
                    </div>
                    <div id="couponMessage" class="mt-2 text-sm hidden"></div>
                </div>

                <!-- Price Breakdown -->
                <div class="space-y-3 mb-6 border-t border-gray-200 pt-6">
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Subtotal</span>
                        <span class="font-medium text-gray-800">Rs. {{ number_format($subtotal) }}</span>
                    </div>
                    @if(isset($discount) && $discount > 0)
                    <div id="discountRow" class="flex justify-between text-sm text-gray-600">
                        <span id="discountLabel">Discount @if(isset($appliedCoupon))({{ $appliedCoupon['code'] }})@endif</span>
                        <span id="discountAmount" class="font-medium text-green-600">-Rs. {{ number_format($discount) }}</span>
                    </div>
                    @else
                    <div id="discountRow" class="flex justify-between text-sm text-gray-600 hidden">
                        <span id="discountLabel">Discount</span>
                        <span id="discountAmount" class="font-medium text-green-600">-Rs. 0</span>
                    </div>
                    @endif
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
                        <span id="totalAmount" class="text-2xl font-medium text-[#333]">Rs. {{ number_format($total) }}</span>
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

        // Pincode API Auto-fill functionality
        let pincodeTimeout = null;
        
        async function fetchPincodeDetails(pincode, cityField, stateField) {
            // Clear previous timeout
            if (pincodeTimeout) {
                clearTimeout(pincodeTimeout);
            }
            
            // Only proceed if pincode is 6 digits
            if (pincode.length !== 6 || !/^\d{6}$/.test(pincode)) {
                return;
            }
            
            // Add loading state
            cityField.value = 'Loading...';
            stateField.value = 'Loading...';
            
            try {
                const response = await fetch(`https://api.postalpincode.in/pincode/${pincode}`);
                const data = await response.json();
                
                if (data && data[0] && data[0].Status === 'Success' && data[0].PostOffice && data[0].PostOffice.length > 0) {
                    const postOffice = data[0].PostOffice[0];
                    cityField.value = postOffice.District || '';
                    stateField.value = postOffice.State || '';
                } else {
                    // Invalid pincode
                    cityField.value = '';
                    stateField.value = '';
                    alert('Invalid PIN code. Please enter a valid 6-digit PIN code.');
                }
            } catch (error) {
                console.error('Pincode API error:', error);
                cityField.value = '';
                stateField.value = '';
                alert('Unable to fetch location details. Please enter manually.');
            }
        }
        
        // Shipping Address Pincode Handler
        const shippingPincode = document.querySelector('input[name="pincode"]');
        const shippingCity = document.querySelector('input[name="city"]');
        const shippingState = document.querySelector('input[name="state"]');
        
        if (shippingPincode && shippingCity && shippingState) {
            shippingPincode.addEventListener('input', function() {
                const pincode = this.value.trim();
                
                // Clear timeout and set new one for debouncing
                if (pincodeTimeout) {
                    clearTimeout(pincodeTimeout);
                }
                
                pincodeTimeout = setTimeout(() => {
                    fetchPincodeDetails(pincode, shippingCity, shippingState);
                }, 500); // Wait 500ms after user stops typing
            });
        }
        
        // Billing Address Pincode Handler
        const billingPincode = document.querySelector('input[name="billing_pincode"]');
        const billingCity = document.querySelector('input[name="billing_city"]');
        const billingState = document.querySelector('input[name="billing_state"]');
        
        if (billingPincode && billingCity && billingState) {
            billingPincode.addEventListener('input', function() {
                const pincode = this.value.trim();
                
                // Clear timeout and set new one for debouncing
                if (pincodeTimeout) {
                    clearTimeout(pincodeTimeout);
                }
                
                pincodeTimeout = setTimeout(() => {
                    fetchPincodeDetails(pincode, billingCity, billingState);
                }, 500); // Wait 500ms after user stops typing
            });
        }

        // Razorpay Integration
        document.getElementById('checkoutForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            clearErrors();
            
            const formData = new FormData(this);
            const paymentMethod = formData.get('payment_method');
            
            // Add applied coupon data if exists
            if (appliedCoupon) {
                formData.append('applied_coupon_id', appliedCoupon.id);
                formData.append('applied_coupon_code', appliedCoupon.code);
                formData.append('discount_amount', currentDiscount);
            }
            
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
                
                // Check if response is JSON
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    // Log the actual HTML response for debugging
                    const htmlText = await response.text();
                    console.error('Server returned HTML instead of JSON:');
                    console.error(htmlText.substring(0, 500)); // First 500 chars
                    throw new Error('Server returned non-JSON response. Please check server logs.');
                }
                
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
                        name: '{{ $siteSettings["site_name"] ?? config("app.name") }}',
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
                console.error('Error details:', error.message);
                const errorDiv = document.createElement('div');
                errorDiv.className = 'bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm mb-4';
                errorDiv.textContent = 'Something went wrong. Please try again. Check console for details.';
                document.getElementById('checkoutForm').insertBefore(errorDiv, document.getElementById('checkoutForm').firstChild);
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            }
        });

        // Coupon/Discount Functionality
        let appliedCoupon = @json($appliedCoupon ?? null);
        let originalSubtotal = {{ $subtotal }};
        let currentDiscount = {{ $discount ?? 0 }};

        // Initialize UI if coupon is already applied
        if (appliedCoupon && currentDiscount > 0) {
            document.getElementById('couponCode').value = appliedCoupon.code;
            document.getElementById('couponCode').disabled = true;
            updatePriceDisplay();
            showCouponMessage(`Coupon "${appliedCoupon.code}" applied! You saved Rs. ${currentDiscount.toFixed(2)}`, 'success');
            
            const applyBtn = document.getElementById('applyCouponBtn');
            applyBtn.textContent = 'Remove';
            applyBtn.classList.remove('bg-gray-100', 'hover:bg-gray-200', 'text-gray-700');
            applyBtn.classList.add('bg-red-100', 'hover:bg-red-200', 'text-red-700');
        }

        document.getElementById('applyCouponBtn').addEventListener('click', async function() {
            const applyBtn = this;
            
            // Check if we're removing the coupon
            if (applyBtn.textContent === 'Remove') {
                removeCoupon();
                return;
            }
            
            // Otherwise, apply the coupon
            const couponCode = document.getElementById('couponCode').value.trim();
            const messageDiv = document.getElementById('couponMessage');
            
            if (!couponCode) {
                showCouponMessage('Please enter a coupon code', 'error');
                return;
            }

            // Show loading state
            applyBtn.disabled = true;
            applyBtn.textContent = 'Applying...';
            messageDiv.classList.add('hidden');

            try {
                const response = await fetch('{{ route("checkout.apply-coupon") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        coupon_code: couponCode,
                        subtotal: originalSubtotal
                    })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    // Apply discount
                    appliedCoupon = data.coupon;
                    currentDiscount = parseFloat(data.discount_amount);
                    
                    // Update UI
                    updatePriceDisplay();
                    showCouponMessage(`Coupon "${couponCode}" applied! You saved Rs. ${parseFloat(data.discount_amount).toFixed(2)}`, 'success');
                    
                    // Disable input and change button to "Remove"
                    document.getElementById('couponCode').disabled = true;
                    applyBtn.textContent = 'Remove';
                    applyBtn.classList.remove('bg-gray-100', 'hover:bg-gray-200', 'text-gray-700');
                    applyBtn.classList.add('bg-red-100', 'hover:bg-red-200', 'text-red-700');
                    
                } else {
                    showCouponMessage(data.message || 'Invalid coupon code', 'error');
                    applyBtn.textContent = 'Apply';
                }
            } catch (error) {
                console.error('Coupon error:', error);
                showCouponMessage('Something went wrong. Please try again.', 'error');
                applyBtn.textContent = 'Apply';
            } finally {
                applyBtn.disabled = false;
            }
        });

        function removeCoupon() {
            appliedCoupon = null;
            currentDiscount = 0;
            
            // Reset UI
            updatePriceDisplay();
            document.getElementById('couponCode').value = '';
            document.getElementById('couponCode').disabled = false;
            document.getElementById('couponMessage').classList.add('hidden');
            
            const applyBtn = document.getElementById('applyCouponBtn');
            applyBtn.textContent = 'Apply';
            applyBtn.classList.remove('bg-red-100', 'hover:bg-red-200', 'text-red-700');
            applyBtn.classList.add('bg-gray-100', 'hover:bg-gray-200', 'text-gray-700');
        }

        function updatePriceDisplay() {
            const discountRow = document.getElementById('discountRow');
            const discountLabel = document.getElementById('discountLabel');
            const discountAmount = document.getElementById('discountAmount');
            const totalAmount = document.getElementById('totalAmount');
            
            if (currentDiscount > 0) {
                // Show discount row
                discountRow.classList.remove('hidden');
                discountLabel.textContent = appliedCoupon ? `Discount (${appliedCoupon.code})` : 'Discount';
                discountAmount.textContent = `-Rs. ${Number(currentDiscount).toLocaleString('en-IN')}`;
                
                // Update total (ensure proper calculation)
                const newTotal = Number(originalSubtotal) - Number(currentDiscount);
                totalAmount.textContent = `Rs. ${Number(newTotal).toLocaleString('en-IN')}`;
            } else {
                // Hide discount row
                discountRow.classList.add('hidden');
                
                // Reset total
                totalAmount.textContent = `Rs. ${Number(originalSubtotal).toLocaleString('en-IN')}`;
            }
        }

        function showCouponMessage(message, type) {
            const messageDiv = document.getElementById('couponMessage');
            messageDiv.textContent = message;
            messageDiv.classList.remove('hidden', 'text-red-600', 'text-green-600');
            
            if (type === 'error') {
                messageDiv.classList.add('text-red-600');
            } else if (type === 'success') {
                messageDiv.classList.add('text-green-600');
            }
        }
    </script>
@endsection
