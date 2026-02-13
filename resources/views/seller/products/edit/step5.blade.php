@extends('seller.products.edit._layout')

@section('step_title', 'Step 5: SEO Optimization')
@section('step_description', 'Optimize for search engines and social media')

@section('step_content')
@php
    $currentStep = 5;
    $prevStepRoute = '/seller/products/' . $product->id . '/edit?step=4';
@endphp

<form id="stepForm" action="{{ route('seller.products.update', $product->id) }}" method="POST">
    @csrf
    @method('PUT')
    <input type="hidden" name="step" value="5">
    @csrf
    
    <div class="bg-white rounded-xl shadow-lg border border-gray-200">
        <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-green-50 to-teal-50">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-gradient-to-r from-green-600 to-teal-600 rounded-lg flex items-center justify-center shadow-lg">
                    <span class="text-white text-xl">🔍</span>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">SEO & Marketing</h2>
                    <p class="text-gray-600 font-medium">Optimize for search engines & social media</p>
                </div>
            </div>
        </div>
        
        <div class="p-8 space-y-6">
            <!-- Search Engine Optimization -->
            <div class="space-y-4">
                <div class="flex justify-between items-center border-b border-gray-200 pb-2">
                    <h3 class="text-lg font-bold text-gray-900">🔍 Search Engine Optimization</h3>
                    <button type="button" onclick="autoGenerateSEO()" class="px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white text-sm font-semibold rounded-lg shadow-md hover:from-blue-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 flex items-center space-x-2">
                        <span>✨</span>
                        <span>Auto Generate SEO</span>
                    </button>
                </div>
                
                <!-- Meta Title -->
                <div>
                    <label class="block text-sm font-semibold text-gray-800 mb-2">Meta Title <span class="text-gray-500 text-xs">(50-60 characters)</span></label>
                    <input type="text" name="meta_title" maxlength="60" 
                        value="{{ old('meta_title', $product->meta_title ?? '') }}"
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 hover:border-gray-400"
                        placeholder="Enter SEO title for search engines">
                    <div class="flex justify-between text-xs text-gray-500 mt-1">
                        <span>Appears in search results and browser tabs</span>
                        <span id="metaTitleCount">0/60</span>
                    </div>
                    @error('meta_title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Meta Description -->
                <div>
                    <label class="block text-sm font-semibold text-gray-800 mb-2">Meta Description <span class="text-gray-500 text-xs">(150-160 characters)</span></label>
                    <textarea name="meta_description" maxlength="160" rows="3" 
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 hover:border-gray-400"
                        placeholder="Brief description that appears in search results">{{ old('meta_description', $product->meta_description ?? '') }}</textarea>
                    <div class="flex justify-between text-xs text-gray-500 mt-1">
                        <span>Shown in search results below the title</span>
                        <span id="metaDescCount">0/160</span>
                    </div>
                    @error('meta_description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Focus Keywords -->
                <div>
                    <label class="block text-sm font-semibold text-gray-800 mb-2">Focus Keywords</label>
                    <input type="text" name="focus_keywords" 
                        value="{{ old('focus_keywords', $product->focus_keywords ?? '') }}"
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 hover:border-gray-400"
                        placeholder="keyword1, keyword2, keyword3">
                    <p class="text-xs text-gray-500 mt-1">Comma-separated keywords you want to rank for</p>
                    @error('focus_keywords')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Canonical URL -->
                <div>
                    <label class="block text-sm font-semibold text-gray-800 mb-2">Canonical URL</label>
                    <input type="url" name="canonical_url" 
                        value="{{ old('canonical_url', $product->canonical_url ?? '') }}"
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 hover:border-gray-400"
                        placeholder="https://example.com/product-url">
                    <p class="text-xs text-gray-500 mt-1">Preferred URL for this product (prevents duplicate content)</p>
                    @error('canonical_url')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Google Search Preview -->
                <div class="bg-gray-50 border-2 border-gray-200 rounded-lg p-6">
                    <h4 class="text-md font-bold text-gray-700 mb-4 flex items-center">
                        <span class="text-lg mr-2">👁️</span>
                        Google Search Preview
                    </h4>
                    <div class="bg-white p-4 rounded-lg border shadow-sm">
                        <div class="space-y-1">
                            <div id="previewTitle" class="text-blue-600 text-lg font-medium hover:underline cursor-pointer" style="line-height: 1.3;">
                                Enter a meta title to see preview...
                            </div>
                            <div id="previewUrl" class="text-green-700 text-sm" style="line-height: 1.4;">
                                https://yourstore.com/products/product-name
                            </div>
                            <div id="previewDescription" class="text-gray-600 text-sm" style="line-height: 1.4; max-width: 600px;">
                                Enter a meta description to see how it appears in Google search results...
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 text-xs text-gray-500 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        This is how your product will appear in Google search results
                    </div>
                </div>
            </div>

            <!-- Social Media Optimization -->
            <div class="space-y-4">
                <h3 class="text-lg font-bold text-gray-900 border-b border-gray-200 pb-2">📱 Social Media Optimization</h3>
                
                <!-- Open Graph Title -->
                <div>
                    <label class="block text-sm font-semibold text-gray-800 mb-2">Open Graph Title <span class="text-gray-500 text-xs">(Facebook, LinkedIn)</span></label>
                    <input type="text" name="og_title" maxlength="60" 
                        value="{{ old('og_title', $product->og_title ?? '') }}"
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 hover:border-gray-400"
                        placeholder="Title for social media shares">
                    <p class="text-xs text-gray-500 mt-1">Title shown when shared on Facebook, LinkedIn</p>
                    @error('og_title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Open Graph Description -->
                <div>
                    <label class="block text-sm font-semibold text-gray-800 mb-2">Open Graph Description</label>
                    <textarea name="og_description" maxlength="160" rows="3" 
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 hover:border-gray-400"
                        placeholder="Description for social media shares">{{ old('og_description', $product->og_description ?? '') }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Description shown when shared on social media</p>
                    @error('og_description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- SEO Tips -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <h3 class="font-semibold text-yellow-800 mb-2">💡 SEO Best Practices</h3>
                <ul class="text-sm text-yellow-700 space-y-1">
                    <li>• Use your main keyword in the meta title</li>
                    <li>• Write compelling meta descriptions that encourage clicks</li>
                    <li>• Include relevant keywords naturally in descriptions</li>
                    <li>• Make social media titles engaging and share-worthy</li>
                    <li>• Keep URLs short, descriptive, and SEO-friendly</li>
                </ul>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
// Auto Generate SEO Function - Enhanced
function autoGenerateSEO() {
    // Data from server (using product relationships)
    const productName = @json($product->name ?? '');
    const productDesc = @json($product->description ?? '');
    const brandName = @json($product->brand->name ?? '');
    const categoryName = @json($product->category->name ?? '');
    const price = @json($product->price ?? '');
    const productSlug = @json($product->slug ?? 'product');
    
    // Strip HTML and clean description
    const tmpDiv = document.createElement("DIV");
    tmpDiv.innerHTML = productDesc;
    let cleanDesc = tmpDiv.textContent || tmpDiv.innerText || "";
    cleanDesc = cleanDesc.replace(/\s+/g, ' ').trim();
    
    // Extract key features/benefits from description (first 2-3 sentences)
    const sentences = cleanDesc.split(/[.!?]+/).filter(s => s.trim().length > 0);
    const keyFeatures = sentences.slice(0, 2).join('. ').trim();
    
    // 1. Generate COMPELLING Meta Title (aim for 55-60 chars for optimal display)
    let metaTitle = '';
    
    if (brandName && categoryName) {
        // Format: "Product Name - Brand | Category Collection"
        metaTitle = `${productName} - ${brandName} | ${categoryName}`;
    } else if (brandName) {
        // Format: "Product Name by Brand | Premium/Best Quality"
        metaTitle = `${productName} by ${brandName} | Premium Quality`;
    } else if (categoryName) {
        // Format: "Product Name | Category - Shop Now"
        metaTitle = `${productName} | ${categoryName} - Shop Now`;
    } else {
        // Format: "Product Name | Buy Online - Best Price"
        metaTitle = `${productName} | Buy Online - Best Price`;
    }
    
    // Ensure optimal length (50-60 characters)
    if (metaTitle.length > 60) {
        metaTitle = metaTitle.substring(0, 57) + '...';
    } else if (metaTitle.length < 50 && price) {
        // Add price hint if title is too short
        const priceHint = ` - ₹${price}`;
        if (metaTitle.length + priceHint.length <= 60) {
            metaTitle += priceHint;
        }
    }
    
    // 2. Generate COMPELLING Meta Description (aim for 155-160 chars)
    let metaDescription = '';
    
    if (keyFeatures && keyFeatures.length > 20) {
        // Use actual features from description
        metaDescription = keyFeatures;
    } else if (cleanDesc.length > 30) {
        // Use beginning of description
        metaDescription = cleanDesc.substring(0, 120);
    } else {
        // Generate generic but appealing description
        metaDescription = `Shop ${productName}`;
        if (brandName) metaDescription += ` by ${brandName}`;
        if (categoryName) metaDescription += ` in ${categoryName} category`;
        metaDescription += `. Premium quality products with fast shipping and best prices`;
    }
    
    // Add call-to-action if space permits
    if (metaDescription.length < 140) {
        const cta = " Order now for best deals!";
        if (metaDescription.length + cta.length <= 160) {
            metaDescription += cta;
        }
    }
    
    // Ensure optimal length (150-160 characters)
    if (metaDescription.length > 160) {
        metaDescription = metaDescription.substring(0, 157) + '...';
    } else if (metaDescription.length < 150) {
        // Pad with benefit statement
        const padding = " Fast delivery. Easy returns.";
        if (metaDescription.length + padding.length <= 160) {
            metaDescription += padding;
        }
    }
    
    // 3. Generate RICH Keywords (varied and specific)
    let keywords = [];
    
    // Add primary terms
    keywords.push(productName);
    if (brandName) {
        keywords.push(brandName);
        keywords.push(`${brandName} ${categoryName || 'products'}`);
    }
    if (categoryName) {
        keywords.push(categoryName);
        keywords.push(`best ${categoryName.toLowerCase()}`);
    }
    
    // Extract meaningful words from product name (length > 3)
    const nameWords = productName.split(/[\s,\/\-]+/).filter(word => 
        word.length > 3 && !['with', 'from', 'for'].includes(word.toLowerCase())
    );
    nameWords.forEach(word => {
        keywords.push(word);
        if (categoryName) keywords.push(`${word} ${categoryName.toLowerCase()}`);
    });
    
    // Add commercial intent keywords
    if (brandName) keywords.push(`buy ${brandName.toLowerCase()} online`);
    keywords.push(`${productName.toLowerCase()} online`);
    keywords.push(`${productName.toLowerCase()} price`);
    if (categoryName) keywords.push(`${categoryName.toLowerCase()} online shopping`);
    
    // Unique and comma separated (limit to top 15-20)
    const uniqueKeywords = [...new Set(keywords)].slice(0, 18).join(', ');
    
    // 4. Generate Canonical URL (use product slug)
    const canonicalUrl = `${window.location.origin}/products/${productSlug}`;
    
    // Populate ALL Fields
    document.querySelector('input[name="meta_title"]').value = metaTitle;
    document.querySelector('textarea[name="meta_description"]').value = metaDescription;
    document.querySelector('input[name="focus_keywords"]').value = uniqueKeywords;
    document.querySelector('input[name="canonical_url"]').value = canonicalUrl;
    
    // Social Media - Make it more engaging
    let ogTitle = metaTitle;
    let ogDescription = metaDescription;
    
    // Make OG title more social-friendly (add emoji or emphasis)
    if (ogTitle.length < 55) {
        ogTitle = `✨ ${ogTitle}`;
    }
    
    document.querySelector('input[name="og_title"]').value = ogTitle;
    document.querySelector('textarea[name="og_description"]').value = ogDescription;
    
    // Trigger input events to update counters and preview
    document.querySelector('input[name="meta_title"]').dispatchEvent(new Event('input'));
    document.querySelector('textarea[name="meta_description"]').dispatchEvent(new Event('input'));
    document.querySelector('input[name="canonical_url"]').dispatchEvent(new Event('input'));
    document.querySelector('input[name="og_title"]').dispatchEvent(new Event('input'));
    
    // Show success feedback
    const button = document.querySelector('button[onclick="autoGenerateSEO()"]');
    const originalText = button.innerHTML;
    button.innerHTML = '<span>✅</span><span>Generated!</span>';
    button.classList.add('animate-pulse');
    setTimeout(() => {
        button.innerHTML = originalText;
        button.classList.remove('animate-pulse');
    }, 2000);
}

// Character count for meta fields & Real-time Google Preview
document.addEventListener('DOMContentLoaded', function() {
    const metaTitle = document.querySelector('input[name="meta_title"]');
    const metaDescription = document.querySelector('textarea[name="meta_description"]');
    const canonicalUrl = document.querySelector('input[name="canonical_url"]');
    const metaTitleCount = document.getElementById('metaTitleCount');
    const metaDescCount = document.getElementById('metaDescCount');
    
    // Preview elements
    const previewTitle = document.getElementById('previewTitle');
    const previewUrl = document.getElementById('previewUrl');
    const previewDescription = document.getElementById('previewDescription');
    
    // Get product name and slug from product model
    const productName = '{{ $product->name ?? "Product Name" }}';
    const productSlug = '{{ $product->slug ?? "product-name" }}';

    function updateCount(input, counter) {
        const current = input.value.length;
        const max = input.getAttribute('maxlength');
        counter.textContent = `${current}/${max}`;
        
        // Color coding for optimal lengths
        if (input.name === 'meta_title') {
            if (current >= 50 && current <= 60) {
                counter.className = 'text-green-600 font-semibold';
            } else if (current > 60) {
                counter.className = 'text-red-600 font-semibold';
            } else {
                counter.className = 'text-gray-500';
            }
        } else if (input.name === 'meta_description') {
            if (current >= 150 && current <= 160) {
                counter.className = 'text-green-600 font-semibold';
            } else if (current > 160) {
                counter.className = 'text-red-600 font-semibold';
            } else {
                counter.className = 'text-gray-500';
            }
        }
    }
    
    function updateGooglePreview() {
        // Update title
        const title = metaTitle.value.trim() || productName + ' | Your Store Name';
        previewTitle.textContent = title.length > 60 ? title.substring(0, 57) + '...' : title;
        
        // Update URL
        const baseUrl = canonicalUrl.value.trim() || `https://yourstore.com/products/${productSlug}`;
        previewUrl.textContent = baseUrl;
        
        // Update description
        const description = metaDescription.value.trim() || 'Enter a meta description to see how it appears in Google search results. This text helps users understand what your product is about.';
        previewDescription.textContent = description.length > 160 ? description.substring(0, 157) + '...' : description;
        
        // Update character count colors based on preview
        if (title.length > 60) {
            previewTitle.className = 'text-blue-600 text-lg font-medium hover:underline cursor-pointer truncate';
        } else {
            previewTitle.className = 'text-blue-600 text-lg font-medium hover:underline cursor-pointer';
        }
    }
    
    // Initialize counts and preview
    if (metaTitle && metaTitleCount) {
        updateCount(metaTitle, metaTitleCount);
        metaTitle.addEventListener('input', () => {
            updateCount(metaTitle, metaTitleCount);
            updateGooglePreview();
        });
    }

    if (metaDescription && metaDescCount) {
        updateCount(metaDescription, metaDescCount);
        metaDescription.addEventListener('input', () => {
            updateCount(metaDescription, metaDescCount);
            updateGooglePreview();
        });
    }
    
    if (canonicalUrl) {
        canonicalUrl.addEventListener('input', updateGooglePreview);
    }
    
    // Initial preview update
    updateGooglePreview();
});
</script>
@endpush

@endsection
