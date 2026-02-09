@extends('seller.products.create._layout')

@section('step_title', 'Step 6: Final Settings')
@section('step_description', 'Complete product configuration and publish settings')

@section('step_content')
@php
    $currentStep = 6;
    $prevStepRoute = route('seller.products.create.step5');
@endphp

<form id="stepForm" action="{{ route('seller.products.create.step6.process') }}" method="POST">
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
                    <h3 class="text-lg font-bold text-red-800 mb-2">❌ Error Creating Product</h3>
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
                            <option value="active" {{ old('status', $productData['status'] ?? '') == 'active' ? 'selected' : '' }}>Active - Live on website</option>
                            <option value="draft" {{ old('status', $productData['status'] ?? '') == 'draft' ? 'selected' : '' }}>Draft - Save for later</option>
                            <option value="inactive" {{ old('status', $productData['status'] ?? '') == 'inactive' ? 'selected' : '' }}>Inactive - Hidden from website</option>
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
                            <option value="visible" {{ old('visibility', $productData['visibility'] ?? '') == 'visible' ? 'selected' : '' }}>Visible - Everywhere</option>
                            <option value="catalog" {{ old('visibility', $productData['visibility'] ?? '') == 'catalog' ? 'selected' : '' }}>Catalog Only</option>
                            <option value="search" {{ old('visibility', $productData['visibility'] ?? '') == 'search' ? 'selected' : '' }}>Search Only</option>
                            <option value="hidden" {{ old('visibility', $productData['visibility'] ?? '') == 'hidden' ? 'selected' : '' }}>Hidden</option>
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
                <h3 class="font-semibold text-green-800 mb-2">🎉 Ready to Launch!</h3>
                <p class="text-sm text-green-700 mb-4">You've completed all the steps. Review the summary above and click "Create Product" to publish your product.</p>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const productData = @json($productData ?? []);
    const stepForm = document.getElementById('stepForm');
    const nextBtn = document.getElementById('nextBtn');
    const prevBtn = document.getElementById('prevBtn');
    
    // Handle next button (submit product)
    if (nextBtn) {
        nextBtn.addEventListener('click', async function(e) {
            e.preventDefault();
            
            if (!confirm('Are you sure you want to submit this product for approval?')) {
                return;
            }
            
            // Disable button
            nextBtn.disabled = true;
            nextBtn.innerHTML = '<svg class="animate-spin h-5 w-5 mr-2 inline" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Creating Product...';
            
            try {
                const formData = new FormData(stepForm);
                
                const response = await fetch(stepForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    // Show success message
                    alert('✅ Product created successfully and submitted for approval!');
                    
                    // Redirect to products list
                    window.location.href = result.redirect || '{{ route("seller.products.index") }}';
                } else {
                    alert('❌ Error: ' + (result.message || 'Failed to create product'));
                    nextBtn.disabled = false;
                    nextBtn.innerHTML = '✅ Create Product';
                }
            } catch (error) {
                console.error('Error:', error);
                alert('❌ An error occurred. Please try again.');
                nextBtn.disabled = false;
                nextBtn.innerHTML = '✅ Create Product';
            }
        });
    }
    
    // Handle previous button
    if (prevBtn) {
        prevBtn.addEventListener('click', function(e) {
            e.preventDefault();
            window.location.href = '{{ route("seller.products.create.step6") }}';
        });
    }
});
</script>
@endpush
@endsection