<!-- SEO Settings Tab -->
<div id="seo-tab" class="{{ $activeTab === 'seo' ? '' : 'hidden' }}">
    <div class="bg-white shadow-xl rounded-2xl border border-gray-200">
        <div class="px-8 py-6 border-b border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-xl font-bold text-gray-900">SEO Settings</h3>
                    <p class="text-sm text-gray-600">Optimize your website for search engines</p>
                </div>
            </div>
        </div>
        <div class="px-8 py-6">
            <form id="seo-form" method="POST" action="{{ route('admin.settings.seo') }}">
                @csrf
                <div class="space-y-6">
                    
                    <!-- Meta Title -->
                    <div class="group">
                        <label for="seo_meta_title" class="block text-sm font-semibold text-gray-700 mb-2">
                            Meta Title
                            <span class="text-gray-400 font-normal">(Recommended: 50-60 characters)</span>
                        </label>
                        <input type="text" name="seo_meta_title" id="seo_meta_title" 
                               value="{{ old('seo_meta_title', $settings['seo_meta_title']) }}"
                               maxlength="60"
                               class="block w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 text-sm"
                               placeholder="Your E-commerce Store | Best Products Online">
                        <p class="mt-1 text-xs text-gray-500">
                            <span id="meta-title-count">{{ strlen($settings['seo_meta_title']) }}</span>/60 characters
                        </p>
                    </div>

                    <!-- Meta Description -->
                    <div class="group">
                        <label for="seo_meta_description" class="block text-sm font-semibold text-gray-700 mb-2">
                            Meta Description
                            <span class="text-gray-400 font-normal">(Recommended: 150-160 characters)</span>
                        </label>
                        <textarea name="seo_meta_description" id="seo_meta_description" 
                                  rows="3"
                                  maxlength="160"
                                  class="block w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 text-sm"
                                  placeholder="Shop the best products online with fast shipping and great prices. Find everything you need in one place.">{{ old('seo_meta_description', $settings['seo_meta_description']) }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">
                            <span id="meta-desc-count">{{ strlen($settings['seo_meta_description']) }}</span>/160 characters
                        </p>
                    </div>

                    <!-- Meta Keywords -->
                    <div class="group">
                        <label for="seo_meta_keywords" class="block text-sm font-semibold text-gray-700 mb-2">
                            Meta Keywords
                            <span class="text-gray-400 font-normal">(Comma-separated)</span>
                        </label>
                        <input type="text" name="seo_meta_keywords" id="seo_meta_keywords" 
                               value="{{ old('seo_meta_keywords', $settings['seo_meta_keywords']) }}"
                               class="block w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 text-sm"
                               placeholder="ecommerce, online shopping, clothing, fashion">
                        <p class="mt-1 text-xs text-gray-500">Separate keywords with commas</p>
                    </div>

                    <!-- Open Graph Image -->
                    <div class="group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Open Graph Image
                            <span class="text-gray-400 font-normal">(For social media sharing - 1200x630px recommended)</span>
                        </label>
                        <div class="flex items-center space-x-4">
                            @if($settings['seo_og_image'])
                                <img id="og-image-preview" src="{{ $settings['seo_og_image'] }}" alt="OG Image" class="w-48 h-24 object-cover border-2 border-gray-200 rounded-lg">
                            @else
                                <div id="og-image-preview" class="w-48 h-24 bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                            <div>
                                <input type="file" id="og-image-upload" accept="image/*" class="hidden">
                                <button type="button" onclick="document.getElementById('og-image-upload').click()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm font-medium">
                                    Upload OG Image
                                </button>
                                <p class="text-xs text-gray-500 mt-2">PNG, JPG up to 2MB</p>
                            </div>
                        </div>
                    </div>

                    <!-- Google Analytics -->
                    <div class="group">
                        <label for="seo_google_analytics" class="block text-sm font-semibold text-gray-700 mb-2">
                            Google Analytics ID
                            <span class="text-gray-400 font-normal">(G-XXXXXXXXXX or UA-XXXXXXXXX-X)</span>
                        </label>
                        <input type="text" name="seo_google_analytics" id="seo_google_analytics" 
                               value="{{ old('seo_google_analytics', $settings['seo_google_analytics']) }}"
                               class="block w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 text-sm font-mono"
                               placeholder="G-XXXXXXXXXX">
                    </div>

                    <!-- Google Site Verification -->
                    <div class="group">
                        <label for="seo_google_site_verification" class="block text-sm font-semibold text-gray-700 mb-2">
                            Google Site Verification Code
                        </label>
                        <input type="text" name="seo_google_site_verification" id="seo_google_site_verification" 
                               value="{{ old('seo_google_site_verification', $settings['seo_google_site_verification']) }}"
                               class="block w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 text-sm font-mono"
                               placeholder="xxxxxxxxxxxxxxxxxxxxxxxx">
                    </div>

                    <!-- Sitemap Settings -->
                    <div class="border-2 border-gray-200 rounded-xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h4 class="text-lg font-bold text-gray-900">XML Sitemap</h4>
                                <p class="text-sm text-gray-600">Generate sitemap for search engines</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="seo_sitemap_enabled" value="1" {{ $settings['seo_sitemap_enabled'] ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                            </label>
                        </div>
                        <button type="button" onclick="generateSitemap()" class="w-full px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-900 rounded-lg font-medium text-sm transition-colors">
                            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Generate Sitemap Now
                        </button>
                        <p class="mt-2 text-xs text-gray-500">Current sitemap: <a href="{{ url('/sitemap.xml') }}" target="_blank" class="text-green-600 hover:underline">{{ url('/sitemap.xml') }}</a></p>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center text-sm text-gray-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            SEO settings improve search visibility
                        </div>
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-semibold rounded-xl text-white bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 shadow-lg transform transition-all duration-200 hover:scale-105">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Save SEO Settings
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Character counter for meta title
document.getElementById('seo_meta_title')?.addEventListener('input', function() {
    document.getElementById('meta-title-count').textContent = this.value.length;
});

// Character counter for meta description
document.getElementById('seo_meta_description')?.addEventListener('input', function() {
    document.getElementById('meta-desc-count').textContent = this.value.length;
});

// OG Image upload
document.getElementById('og-image-upload')?.addEventListener('change', function(e) {
    if (e.target.files.length > 0) {
        const formData = new FormData();
        formData.append('og_image', e.target.files[0]);
        
        fetch('{{ route("admin.settings.uploadOGImage") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const preview = document.getElementById('og-image-preview');
                preview.innerHTML = `<img src="${data.url}" alt="OG Image" class="w-full h-full object-cover">`;
                preview.classList.remove('bg-gray-100', 'border-dashed');
                preview.classList.add('border-gray-200');
                showToast(data.message, 'success');
            } else {
                showToast(data.message, 'error');
            }
        })
        .catch(error => {
            showToast('Failed to upload image', 'error');
        });
    }
});

// Generate Sitemap
function generateSitemap() {
    fetch('{{ route("admin.settings.generateSitemap") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message + ' - ' + data.url, 'success');
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(error => {
        showToast('Failed to generate sitemap', 'error');
    });
}
</script>
