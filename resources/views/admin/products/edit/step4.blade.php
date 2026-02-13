@extends('admin.products.edit._layout')

@section('step_title', 'Step 4: Inventory & Pricing')
@section('step_description', 'Set pricing and manage stock levels')

@section('step_content')
@php
    $currentStep = 4;
    $prevStepRoute = route('admin.products.edit.step3', $product->id);
@endphp

<form id="stepForm" action="{{ route('admin.products.edit.step4.process', $product->id) }}" method="POST">
    @csrf
    
    <div class="bg-white rounded-xl shadow-lg border border-gray-200">
        <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-cyan-50 to-blue-50">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-gradient-to-r from-cyan-600 to-blue-600 rounded-lg flex items-center justify-center shadow-lg">
                    <span class="text-white text-xl">💰</span>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Inventory & Pricing</h2>
                    <p class="text-gray-600 font-medium">Set prices and manage stock levels</p>
                </div>
            </div>
        </div>
        
        <div class="p-8 space-y-6">
            <!-- Pricing Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-800 mb-2">Regular Price <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">₹</span>
                        <input type="number" name="price" step="0.01" min="0" required 
                            value="{{ old('price', $product->price ?? '') }}"
                            class="w-full pl-8 pr-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 hover:border-gray-400"
                            placeholder="0.00">
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Base selling price</p>
                    @error('price')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-800 mb-2">Sale Price</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">₹</span>
                        <input type="number" name="sale_price" step="0.01" min="0" 
                            value="{{ old('sale_price', $product->sale_price ?? '') }}"
                            class="w-full pl-8 pr-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 hover:border-gray-400"
                            placeholder="0.00">
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Discounted price (optional)</p>
                    @error('sale_price')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Info Note -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h3 class="font-semibold text-blue-800 mb-2 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Stock Management
                </h3>
                <p class="text-sm text-blue-700">
                    Stock quantities are managed at the variant level (Step 2: Product Variants). Each color-size combination can have its own stock quantity.
                </p>
            </div>

            <!-- Pricing Tips -->
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <h3 class="font-semibold text-green-800 mb-2">💡 Pricing Tips</h3>
                <ul class="text-sm text-green-700 space-y-1">
                    <li>• Set competitive prices by researching similar products</li>
                    <li>• Use sale price for temporary discounts and promotions</li>
                    <li>• Ensure sale price is lower than regular price for valid discounts</li>
                </ul>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
// Basic price validation
document.addEventListener('DOMContentLoaded', function() {
    const priceInput = document.querySelector('input[name="price"]');
    const salePriceInput = document.querySelector('input[name="sale_price"]');
    
    function validatePricing() {
        const price = parseFloat(priceInput.value) || 0;
        const salePrice = parseFloat(salePriceInput.value) || 0;
        
        // Show warning if sale price is higher than regular price
        if (salePrice > 0 && salePrice >= price && price > 0) {
            salePriceInput.style.borderColor = '#ef4444';
        } else {
            salePriceInput.style.borderColor = '';
        }
    }
    
    salePriceInput?.addEventListener('blur', validatePricing);
    priceInput?.addEventListener('blur', validatePricing);
});
</script>
@endpush
@endsection
