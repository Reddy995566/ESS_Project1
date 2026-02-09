@extends('admin.products.create._layout')

@section('step_title', 'Step 6: Final Settings')
@section('step_description', 'Complete product configuration and publish settings')

@section('step_content')
@php
    $currentStep = 6;
    $prevStepRoute = route('admin.products.create.step5');
@endphp

<form id="stepForm" action="{{ route('admin.products.create.step6.process') }}" method="POST">
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
// Load summary from session data
document.addEventListener('DOMContentLoaded', function() {
    // This would be populated from session data or API call
    const productData = @json($productData ?? []);
    

});
</script>
@endpush
@endsection