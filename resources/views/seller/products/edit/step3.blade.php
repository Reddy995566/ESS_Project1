@extends('seller.products.edit._layout')

@section('step_title', 'Step 3: Categories & Organization')
@section('step_description', 'Organize your product with categories, brands, and tags')

@section('step_content')
    @php
        $currentStep = 3;
        $prevStepRoute = '/seller/products/' . $product->id . '/edit?step=2';
    @endphp

    <form id="stepForm" action="{{ route('seller.products.update', $product->id) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="step" value="3">

        <div class="bg-white rounded-xl shadow-lg border border-gray-200">
            <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-orange-50 to-yellow-50">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-gradient-to-r from-orange-600 to-yellow-600 rounded-lg flex items-center justify-center shadow-lg">
                        <span class="text-white text-xl">🏷️</span>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Categories & Organization</h2>
                        <p class="text-gray-600 font-medium">Organize your product for better discovery</p>
                    </div>
                </div>
            </div>

            <div class="p-8 space-y-6">
                <!-- Category -->
                <div>
                    <label class="block text-sm font-semibold text-gray-800 mb-2">Category <span class="text-red-500">*</span></label>
                    <select name="category_id" id="categorySelect" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                            @foreach($cat->children as $child)
                                <option value="{{ $child->id }}" {{ old('category_id', $product->category_id) == $child->id ? 'selected' : '' }}>
                                    &nbsp;&nbsp;&nbsp;→ {{ $child->name }}
                                </option>
                                @foreach($child->children as $grandchild)
                                    <option value="{{ $grandchild->id }}" {{ old('category_id', $product->category_id) == $grandchild->id ? 'selected' : '' }}>
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;→→ {{ $grandchild->name }}
                                    </option>
                                @endforeach
                            @endforeach
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Choose main category</p>
                    @error('category_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Fabric -->
                <div>
                    <label class="block text-sm font-semibold text-gray-800 mb-2">Fabric <span class="text-gray-500 text-xs">(Optional)</span></label>
                    <select name="fabric_id" id="fabricSelect" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Fabric</option>
                        @foreach($fabrics as $fabric)
                            <option value="{{ $fabric->id }}" {{ old('fabric_id', $product->fabric_id) == $fabric->id ? 'selected' : '' }}>
                                {{ $fabric->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Optional fabric type</p>
                    @error('fabric_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Brand -->
                <div>
                    <label class="block text-sm font-semibold text-gray-800 mb-2">Brand <span class="text-gray-500 text-xs">(Optional)</span></label>
                    <select name="brand_id" id="brandSelect" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Brand</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Optional brand</p>
                    @error('brand_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- SKU -->
                <div>
                    <label class="block text-sm font-semibold text-gray-800 mb-2">SKU <span class="text-blue-500 text-xs">(Auto)</span></label>
                    <input type="text" name="sku" id="productSku" readonly value="{{ old('sku', $product->sku ?? '') }}" class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed" placeholder="PRD-XXXXXXXX">
                    <p class="text-xs text-blue-500 mt-1">✨ Auto-generated code</p>
                    @error('sku')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Collections (Multiple Select) -->
                <div>
                    <label class="block text-sm font-semibold text-gray-800 mb-2">Collections <span class="text-gray-500 text-xs">(Multiple - Hold Ctrl/Cmd to select multiple)</span></label>
                    <select name="collections[]" id="collectionsSelect" multiple size="5" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @foreach($collections as $collection)
                            @php
                                $existingCollections = $product->collections ? $product->collections->pluck('id')->toArray() : [];
                                $selectedCollections = old('collections', $existingCollections);
                            @endphp
                            <option value="{{ $collection->id }}" 
                                {{ in_array($collection->id, $selectedCollections) ? 'selected' : '' }}>
                                {{ $collection->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Hold Ctrl (Windows) or Cmd (Mac) to select multiple collections</p>
                    <div id="selectedCollectionsDisplay" class="mt-2 flex flex-wrap gap-2"></div>
                </div>

                <!-- Tags (Multiple Select) -->
                <div>
                    <label class="block text-sm font-semibold text-gray-800 mb-2">Tags <span class="text-gray-500 text-xs">(Multiple - Hold Ctrl/Cmd to select multiple)</span></label>
                    <select name="tags[]" id="tagsSelect" multiple size="5" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @foreach($tags as $tag)
                            @php
                                $existingTags = $product->tags ? $product->tags->pluck('id')->toArray() : [];
                                $selectedTags = old('tags', $existingTags);
                            @endphp
                            <option value="{{ $tag->id }}" 
                                {{ in_array($tag->id, $selectedTags) ? 'selected' : '' }}>
                                {{ $tag->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Hold Ctrl (Windows) or Cmd (Mac) to select multiple tags</p>
                    <div id="selectedTagsDisplay" class="mt-2 flex flex-wrap gap-2"></div>
                </div>
            </div>
        </div>
    </form>

    @push('scripts')
        <script>
            // Pure Vanilla JavaScript - No Alpine.js
            document.addEventListener('DOMContentLoaded', function() {
                // Display selected collections
                const collectionsSelect = document.getElementById('collectionsSelect');
                const collectionsDisplay = document.getElementById('selectedCollectionsDisplay');
                
                function updateCollectionsDisplay() {
                    const selected = Array.from(collectionsSelect.selectedOptions);
                    collectionsDisplay.innerHTML = '';
                    
                    selected.forEach(option => {
                        const badge = document.createElement('span');
                        badge.className = 'px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-sm font-medium';
                        badge.textContent = option.text;
                        collectionsDisplay.appendChild(badge);
                    });
                    
                    console.log('Collections selected:', selected.map(o => o.value));
                }
                
                collectionsSelect.addEventListener('change', updateCollectionsDisplay);
                updateCollectionsDisplay(); // Initial display
                
                // Display selected tags
                const tagsSelect = document.getElementById('tagsSelect');
                const tagsDisplay = document.getElementById('selectedTagsDisplay');
                
                function updateTagsDisplay() {
                    const selected = Array.from(tagsSelect.selectedOptions);
                    tagsDisplay.innerHTML = '';
                    
                    selected.forEach(option => {
                        const badge = document.createElement('span');
                        badge.className = 'px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-medium';
                        badge.textContent = option.text;
                        tagsDisplay.appendChild(badge);
                    });
                    
                    console.log('Tags selected:', selected.map(o => o.value));
                }
                
                tagsSelect.addEventListener('change', updateTagsDisplay);
                updateTagsDisplay(); // Initial display
                
                // Before form submit - ensure all selected options are properly set
                window.addEventListener('beforeFormSubmit', function() {
                    console.log('Before form submit - ensuring selections are set');
                    
                    // Collections
                    const collectionsSelected = Array.from(collectionsSelect.selectedOptions);
                    console.log('Collections to submit:', collectionsSelected.map(o => o.value));
                    
                    // Tags
                    const tagsSelected = Array.from(tagsSelect.selectedOptions);
                    console.log('Tags to submit:', tagsSelected.map(o => o.value));
                });
            });
        </script>
    @endpush
@endsection
