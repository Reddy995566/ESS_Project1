@extends('seller.products.edit._layout')

@section('step_title', 'Step 6: Final Settings')
@section('step_description', 'Complete product configuration and publish settings')

@section('step_content')
@php
    $currentStep = 6;
    $prevStepRoute = '/seller/products/' . $product->id . '/edit?step=5';
@endphp

<form id="stepForm" action="{{ route('seller.products.update', $product->id) }}" method="POST">
    @csrf
    @method('PUT')
    <input type="hidden" name="step" value="6">
    @csrf
    
    <div class="bg-white rounded-xl shadow-lg border border-gray-200">
        <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-purple-50">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg flex items-center justify-center shadow-lg">
                    <span class="text-white text-xl">⚙️</span>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Final Settings</h2>
                    <p class="text-gray-600 font-medium">Complete product configuration and publish settings</p>
                </div>
            </div>
        </div>
        
        <div class="p-8 space-y-8">
            <!-- Error Messages -->
            @if($errors->any())
                <div class="bg-red-50 border-2 border-red-500 rounded-xl p-6">
                    <h3 class="text-lg font-bold text-red-800 mb-2">❌ Error Updating Product</h3>
                    <ul class="list-disc list-inside text-red-700">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Product Status & Visibility -->
            <div class="space-y-6">
                <h3 class="text-lg font-bold text-gray-900 border-b border-gray-200 pb-2">📋 Publication Settings</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Product Status -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-800 mb-2">Product Status <span class="text-red-500">*</span></label>
                        <select name="status" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 hover:border-gray-400">
                            <option value="">Select status</option>
                            <option value="active" {{ old('status', $product->status ?? '') == 'active' ? 'selected' : '' }}>Active - Live on website</option>
                            <option value="draft" {{ old('status', $product->status ?? '') == 'draft' ? 'selected' : '' }}>Draft - Save for later</option>
                            <option value="inactive" {{ old('status', $product->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive - Hidden from website</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Visibility -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-800 mb-2">Visibility <span class="text-red-500">*</span></label>
                        <select name="visibility" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 hover:border-gray-400">
                            <option value="">Select visibility</option>
                            <option value="visible" {{ old('visibility', $product->visibility ?? '') == 'visible' ? 'selected' : '' }}>Visible - Everywhere</option>
                            <option value="catalog" {{ old('visibility', $product->visibility ?? '') == 'catalog' ? 'selected' : '' }}>Catalog Only</option>
                            <option value="search" {{ old('visibility', $product->visibility ?? '') == 'search' ? 'selected' : '' }}>Search Only</option>
                            <option value="hidden" {{ old('visibility', $product->visibility ?? '') == 'hidden' ? 'selected' : '' }}>Hidden</option>
                        </select>
                        @error('visibility')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Summary Section -->


            <!-- Final Action Buttons -->
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <h3 class="font-semibold text-green-800 mb-2">🎉 Ready to Update!</h3>
                <p class="text-sm text-green-700 mb-4">You've completed all the steps. Review the summary above and click "Update Product" to save your changes.</p>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
// Load summary from product data
document.addEventListener('DOMContentLoaded', function() {
    // Product data from model
    const productData = @json($product ?? []);
    

});
</script>
@endpush

@endsection