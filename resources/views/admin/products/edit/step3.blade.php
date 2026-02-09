@extends('admin.products.edit._layout')

@section('step_title', 'Step 3: Pricing & Inventory')
@section('step_description', 'Set pricing and manage stock levels')

@section('step_content')
@php
    $currentStep = 3;
    $prevStepRoute = route('admin.products.edit.step5', $product->id);
@endphp

<form id="stepForm" action="{{ route('admin.products.edit.step3.process', $product->id) }}" method="POST">
    @csrf
    
    <div class="bg-white rounded-xl shadow-lg border border-gray-200">
        <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-cyan-50 to-blue-50">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-gradient-to-r from-cyan-600 to-blue-600 rounded-lg flex items-center justify-center shadow-lg">
                    <span class="text-white text-xl">💰</span>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Pricing & Inventory</h2>
                    <p class="text-gray-600 font-medium">Set prices and manage stock levels</p>
                </div>
            </div>
        </div>
        
        <div class="p-8">
            <div class="space-y-8">
                <!-- Pricing Section -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <span class="w-2 h-2 bg-blue-500 rounded-full mr-3"></span>
                        Product Pricing
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Regular Price *
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-3 text-gray-500">₹</span>
                                <input type="number" 
                                       step="0.01" 
                                       name="price" 
                                       value="{{ old('price', $product->price) }}"
                                       class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 font-medium text-gray-900"
                                       placeholder="0.00"
                                       required>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Sale Price
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-3 text-gray-500">₹</span>
                                <input type="number" 
                                       step="0.01" 
                                       name="sale_price" 
                                       value="{{ old('sale_price', $product->sale_price) }}"
                                       class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 font-medium text-gray-900"
                                       placeholder="0.00">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Leave empty if no discount</p>
                        </div>
                    </div>
                </div>
                
                <!-- Inventory Section -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <span class="w-2 h-2 bg-blue-500 rounded-full mr-3"></span>
                        Stock Management
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Stock Quantity *
                            </label>
                            <input type="number" 
                                   name="stock" 
                                   value="{{ old('stock', $product->stock) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 font-medium text-gray-900"
                                   placeholder="0"
                                   min="0"
                                   required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Stock Status
                            </label>
                            <select name="stock_status" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 font-medium text-gray-900 bg-white">
                                <option value="in_stock" {{ old('stock_status', $product->stock_status) == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                                <option value="out_of_stock" {{ old('stock_status', $product->stock_status) == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="track_inventory" 
                                   value="1"
                                   {{ old('track_inventory', $product->track_inventory ?? 1) ? 'checked' : '' }}
                                   class="rounded text-blue-600 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <span class="ml-3 text-sm font-medium text-gray-700">Track inventory for this product</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>


@endsection