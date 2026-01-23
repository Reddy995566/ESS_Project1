@extends('admin.layouts.app')

@section('title', 'Theme Settings')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-gray-50 to-zinc-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-black text-gray-900 tracking-tight">Theme Settings</h1>
                    <p class="mt-1 text-sm text-gray-600">Customize your website colors and appearance</p>
                </div>
                <div class="flex gap-3">
                    <button onclick="resetToDefault()" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition font-medium">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Reset to Default
                    </button>
                    <button onclick="saveSettings()" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Save Changes
                    </button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Color Settings -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Primary Colors -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <span class="w-3 h-3 rounded-full bg-gradient-to-r from-purple-500 to-pink-500 mr-2"></span>
                        Primary Colors
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach($colorSettings->where('sort_order', '<', 10) as $setting)
                        <div class="color-input-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ $setting->label }}</label>
                            <div class="flex items-center gap-2">
                                <input type="color" name="colors[{{ $setting->key }}]" value="{{ $setting->value }}" 
                                    class="w-12 h-12 rounded-lg border-2 border-gray-200 cursor-pointer" 
                                    onchange="updateColorPreview(this)">
                                <input type="text" value="{{ $setting->value }}" 
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm font-mono"
                                    onchange="syncColorInput(this)" data-color-key="{{ $setting->key }}">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">{{ $setting->description }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Background Colors -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <span class="w-3 h-3 rounded-full bg-gradient-to-r from-amber-200 to-yellow-100 mr-2"></span>
                        Background Colors
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach($colorSettings->whereBetween('sort_order', [10, 19]) as $setting)
                        <div class="color-input-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ $setting->label }}</label>
                            <div class="flex items-center gap-2">
                                <input type="color" name="colors[{{ $setting->key }}]" value="{{ $setting->value }}" 
                                    class="w-12 h-12 rounded-lg border-2 border-gray-200 cursor-pointer"
                                    onchange="updateColorPreview(this)">
                                <input type="text" value="{{ $setting->value }}" 
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm font-mono"
                                    onchange="syncColorInput(this)" data-color-key="{{ $setting->key }}">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">{{ $setting->description }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Text Colors -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <span class="w-3 h-3 rounded-full bg-gradient-to-r from-gray-700 to-gray-400 mr-2"></span>
                        Text Colors
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach($colorSettings->whereBetween('sort_order', [20, 29]) as $setting)
                        <div class="color-input-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ $setting->label }}</label>
                            <div class="flex items-center gap-2">
                                <input type="color" name="colors[{{ $setting->key }}]" value="{{ $setting->value }}" 
                                    class="w-12 h-12 rounded-lg border-2 border-gray-200 cursor-pointer"
                                    onchange="updateColorPreview(this)">
                                <input type="text" value="{{ $setting->value }}" 
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm font-mono"
                                    onchange="syncColorInput(this)" data-color-key="{{ $setting->key }}">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">{{ $setting->description }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Accent Colors -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <span class="w-3 h-3 rounded-full bg-gradient-to-r from-yellow-400 to-orange-400 mr-2"></span>
                        Accent Colors
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($colorSettings->whereBetween('sort_order', [30, 39]) as $setting)
                        <div class="color-input-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ $setting->label }}</label>
                            <div class="flex items-center gap-2">
                                <input type="color" name="colors[{{ $setting->key }}]" value="{{ $setting->value }}" 
                                    class="w-12 h-12 rounded-lg border-2 border-gray-200 cursor-pointer"
                                    onchange="updateColorPreview(this)">
                                <input type="text" value="{{ $setting->value }}" 
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm font-mono"
                                    onchange="syncColorInput(this)" data-color-key="{{ $setting->key }}">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">{{ $setting->description }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Button Colors -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <span class="w-3 h-3 rounded-full bg-gradient-to-r from-blue-500 to-indigo-500 mr-2"></span>
                        Button Colors
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($colorSettings->whereBetween('sort_order', [40, 49]) as $setting)
                        <div class="color-input-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ $setting->label }}</label>
                            <div class="flex items-center gap-2">
                                <input type="color" name="colors[{{ $setting->key }}]" value="{{ $setting->value }}" 
                                    class="w-12 h-12 rounded-lg border-2 border-gray-200 cursor-pointer"
                                    onchange="updateColorPreview(this)">
                                <input type="text" value="{{ $setting->value }}" 
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm font-mono"
                                    onchange="syncColorInput(this)" data-color-key="{{ $setting->key }}">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">{{ $setting->description }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Header/Footer Colors -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <span class="w-3 h-3 rounded-full bg-gradient-to-r from-teal-500 to-cyan-500 mr-2"></span>
                        Header & Footer Colors
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($colorSettings->whereBetween('sort_order', [50, 59]) as $setting)
                        <div class="color-input-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ $setting->label }}</label>
                            <div class="flex items-center gap-2">
                                <input type="color" name="colors[{{ $setting->key }}]" value="{{ $setting->value }}" 
                                    class="w-12 h-12 rounded-lg border-2 border-gray-200 cursor-pointer"
                                    onchange="updateColorPreview(this)">
                                <input type="text" value="{{ $setting->value }}" 
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm font-mono"
                                    onchange="syncColorInput(this)" data-color-key="{{ $setting->key }}">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">{{ $setting->description }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Border Colors -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <span class="w-3 h-3 rounded-full border-2 border-gray-400 mr-2"></span>
                        Border Colors
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($colorSettings->where('sort_order', '>=', 60) as $setting)
                        <div class="color-input-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ $setting->label }}</label>
                            <div class="flex items-center gap-2">
                                <input type="color" name="colors[{{ $setting->key }}]" value="{{ $setting->value }}" 
                                    class="w-12 h-12 rounded-lg border-2 border-gray-200 cursor-pointer"
                                    onchange="updateColorPreview(this)">
                                <input type="text" value="{{ $setting->value }}" 
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm font-mono"
                                    onchange="syncColorInput(this)" data-color-key="{{ $setting->key }}">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">{{ $setting->description }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Font Settings -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16"/>
                        </svg>
                        Typography / Font Settings
                    </h2>
                    <p class="text-sm text-gray-500 mb-6">Choose fonts for your website. All fonts are loaded from Google Fonts.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Heading Font -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Heading Font</label>
                            <select name="fonts[font_heading]" id="font_heading" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                                onchange="updateFontPreview()">
                                <optgroup label="Serif Fonts (Elegant)">
                                    @foreach(['Playfair Display', 'Cormorant Garamond', 'Libre Baskerville', 'Lora', 'Merriweather', 'Crimson Text', 'EB Garamond', 'Spectral', 'Source Serif Pro', 'Noto Serif', 'PT Serif', 'Bitter', 'Vollkorn', 'Cardo', 'Old Standard TT'] as $font)
                                    <option value="{{ $font }}" {{ ($fontSettings->where('key', 'font_heading')->first()->value ?? 'Playfair Display') == $font ? 'selected' : '' }}>{{ $font }}</option>
                                    @endforeach
                                </optgroup>
                                <optgroup label="Sans-Serif Fonts (Modern)">
                                    @foreach(['Inter', 'Poppins', 'Montserrat', 'Open Sans', 'Roboto', 'Lato', 'Nunito', 'Nunito Sans', 'Raleway', 'Work Sans', 'DM Sans', 'Outfit', 'Plus Jakarta Sans', 'Manrope', 'Figtree', 'Sora', 'Urbanist', 'Jost', 'Quicksand', 'Mulish', 'Rubik', 'Karla', 'Cabin', 'Josefin Sans', 'Assistant', 'Barlow', 'Archivo', 'Source Sans Pro', 'PT Sans', 'Fira Sans', 'Noto Sans', 'Oxygen', 'Exo 2', 'Titillium Web', 'Overpass', 'Maven Pro', 'Questrial', 'Hind'] as $font)
                                    <option value="{{ $font }}" {{ ($fontSettings->where('key', 'font_heading')->first()->value ?? 'Playfair Display') == $font ? 'selected' : '' }}>{{ $font }}</option>
                                    @endforeach
                                </optgroup>
                                <optgroup label="Display Fonts (Decorative)">
                                    @foreach(['Abril Fatface', 'Bebas Neue', 'Oswald', 'Anton', 'Righteous', 'Alfa Slab One', 'Lobster', 'Pacifico', 'Dancing Script', 'Great Vibes', 'Sacramento', 'Satisfy', 'Tangerine', 'Allura', 'Alex Brush'] as $font)
                                    <option value="{{ $font }}" {{ ($fontSettings->where('key', 'font_heading')->first()->value ?? 'Playfair Display') == $font ? 'selected' : '' }}>{{ $font }}</option>
                                    @endforeach
                                </optgroup>
                            </select>
                            <p class="text-xs text-gray-500 mt-2">Used for titles, headings, and brand name</p>
                            <div class="mt-3 p-3 bg-gray-50 rounded-lg">
                                <p id="heading-preview" class="text-2xl" style="font-family: '{{ $fontSettings->where('key', 'font_heading')->first()->value ?? 'Playfair Display' }}', serif;">
                                    The Trusted Store
                                </p>
                            </div>
                        </div>
                        
                        <!-- Body Font -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Body Font</label>
                            <select name="fonts[font_body]" id="font_body" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                                onchange="updateFontPreview()">
                                <optgroup label="Sans-Serif Fonts (Modern)">
                                    @foreach(['Inter', 'Poppins', 'Montserrat', 'Open Sans', 'Roboto', 'Lato', 'Nunito', 'Nunito Sans', 'Raleway', 'Work Sans', 'DM Sans', 'Outfit', 'Plus Jakarta Sans', 'Manrope', 'Figtree', 'Sora', 'Urbanist', 'Jost', 'Quicksand', 'Mulish', 'Rubik', 'Karla', 'Cabin', 'Josefin Sans', 'Assistant', 'Barlow', 'Archivo', 'Source Sans Pro', 'PT Sans', 'Fira Sans', 'Noto Sans', 'Oxygen', 'Exo 2', 'Titillium Web', 'Overpass', 'Maven Pro', 'Questrial', 'Hind'] as $font)
                                    <option value="{{ $font }}" {{ ($fontSettings->where('key', 'font_body')->first()->value ?? 'Inter') == $font ? 'selected' : '' }}>{{ $font }}</option>
                                    @endforeach
                                </optgroup>
                                <optgroup label="Serif Fonts (Elegant)">
                                    @foreach(['Playfair Display', 'Cormorant Garamond', 'Libre Baskerville', 'Lora', 'Merriweather', 'Crimson Text', 'EB Garamond', 'Spectral', 'Source Serif Pro', 'Noto Serif', 'PT Serif', 'Bitter', 'Vollkorn', 'Cardo', 'Old Standard TT'] as $font)
                                    <option value="{{ $font }}" {{ ($fontSettings->where('key', 'font_body')->first()->value ?? 'Inter') == $font ? 'selected' : '' }}>{{ $font }}</option>
                                    @endforeach
                                </optgroup>
                            </select>
                            <p class="text-xs text-gray-500 mt-2">Used for paragraphs, descriptions, and general text</p>
                            <div class="mt-3 p-3 bg-gray-50 rounded-lg">
                                <p id="body-preview" class="text-sm" style="font-family: '{{ $fontSettings->where('key', 'font_body')->first()->value ?? 'Inter' }}', sans-serif;">
                                    The quick brown fox jumps over the lazy dog. Premium quality fashion for everyone.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Font Pairing Suggestions -->
                    <div class="mt-6 p-4 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl">
                        <h3 class="text-sm font-semibold text-gray-800 mb-3">💡 Popular Font Pairings</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <button type="button" onclick="applyFontPairing('Playfair Display', 'Inter')" 
                                class="text-left p-3 bg-white rounded-lg hover:shadow-md transition text-xs border border-gray-200">
                                <span class="font-semibold block" style="font-family: 'Playfair Display', serif;">Playfair Display</span>
                                <span class="text-gray-500" style="font-family: 'Inter', sans-serif;">+ Inter</span>
                            </button>
                            <button type="button" onclick="applyFontPairing('Cormorant Garamond', 'Poppins')" 
                                class="text-left p-3 bg-white rounded-lg hover:shadow-md transition text-xs border border-gray-200">
                                <span class="font-semibold block" style="font-family: 'Cormorant Garamond', serif;">Cormorant Garamond</span>
                                <span class="text-gray-500" style="font-family: 'Poppins', sans-serif;">+ Poppins</span>
                            </button>
                            <button type="button" onclick="applyFontPairing('Montserrat', 'Open Sans')" 
                                class="text-left p-3 bg-white rounded-lg hover:shadow-md transition text-xs border border-gray-200">
                                <span class="font-semibold block" style="font-family: 'Montserrat', sans-serif;">Montserrat</span>
                                <span class="text-gray-500" style="font-family: 'Open Sans', sans-serif;">+ Open Sans</span>
                            </button>
                            <button type="button" onclick="applyFontPairing('Lora', 'Nunito')" 
                                class="text-left p-3 bg-white rounded-lg hover:shadow-md transition text-xs border border-gray-200">
                                <span class="font-semibold block" style="font-family: 'Lora', serif;">Lora</span>
                                <span class="text-gray-500" style="font-family: 'Nunito', sans-serif;">+ Nunito</span>
                            </button>
                            <button type="button" onclick="applyFontPairing('Bebas Neue', 'Roboto')" 
                                class="text-left p-3 bg-white rounded-lg hover:shadow-md transition text-xs border border-gray-200">
                                <span class="font-semibold block" style="font-family: 'Bebas Neue', sans-serif;">Bebas Neue</span>
                                <span class="text-gray-500" style="font-family: 'Roboto', sans-serif;">+ Roboto</span>
                            </button>
                            <button type="button" onclick="applyFontPairing('DM Sans', 'DM Sans')" 
                                class="text-left p-3 bg-white rounded-lg hover:shadow-md transition text-xs border border-gray-200">
                                <span class="font-semibold block" style="font-family: 'DM Sans', sans-serif;">DM Sans</span>
                                <span class="text-gray-500" style="font-family: 'DM Sans', sans-serif;">+ DM Sans</span>
                            </button>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Live Preview -->
            <div class="lg:col-span-1">
                <div class="sticky top-8">
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h2 class="text-lg font-bold text-gray-900 mb-4">Live Preview</h2>
                        
                        <!-- Preview Card -->
                        <div id="preview-container" class="rounded-xl overflow-hidden border border-gray-200">
                            <!-- Header Preview -->
                            <div class="preview-header p-4 flex items-center justify-between">
                                <span class="preview-header-text font-bold text-lg">The Trusted Store</span>
                                <div class="flex gap-2">
                                    <span class="preview-header-text">🔍</span>
                                    <span class="preview-header-text">❤️</span>
                                    <span class="preview-header-text">🛒</span>
                                </div>
                            </div>
                            
                            <!-- Content Preview -->
                            <div class="preview-bg-primary p-4">
                                <h3 class="preview-text-primary font-semibold mb-2">All-Time Favorites</h3>
                                <div class="grid grid-cols-2 gap-2 mb-4">
                                    <div class="preview-bg-secondary rounded-lg p-2">
                                        <div class="bg-gray-300 h-20 rounded mb-2"></div>
                                        <p class="preview-text-primary text-xs">Product Name</p>
                                        <p class="preview-text-secondary text-xs">Rs. 1,299</p>
                                    </div>
                                    <div class="preview-bg-secondary rounded-lg p-2">
                                        <div class="bg-gray-300 h-20 rounded mb-2"></div>
                                        <p class="preview-text-primary text-xs">Product Name</p>
                                        <p class="preview-text-secondary text-xs">Rs. 1,499</p>
                                    </div>
                                </div>
                                
                                <!-- Buttons Preview -->
                                <div class="space-y-2">
                                    <button class="preview-btn-primary w-full py-2 rounded text-white text-sm font-medium">
                                        Add to Cart
                                    </button>
                                    <button class="preview-btn-secondary w-full py-2 rounded text-white text-sm font-medium">
                                        Buy Now
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Footer Preview -->
                            <div class="preview-footer p-4">
                                <p class="preview-footer-text text-xs text-center">© 2024 The Trusted Store</p>
                            </div>
                        </div>
                        
                        <p class="text-xs text-gray-500 mt-4 text-center">Preview updates as you change colors</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- Load Google Fonts for Preview -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@400;500;600;700&family=Cormorant+Garamond:wght@400;500;600;700&family=Poppins:wght@400;500;600;700&family=Montserrat:wght@400;500;600;700&family=Open+Sans:wght@400;500;600;700&family=Roboto:wght@400;500;700&family=Lato:wght@400;700&family=Nunito:wght@400;600;700&family=Raleway:wght@400;500;600;700&family=Work+Sans:wght@400;500;600;700&family=DM+Sans:wght@400;500;700&family=Lora:wght@400;500;600;700&family=Merriweather:wght@400;700&family=Bebas+Neue&family=Oswald:wght@400;500;600;700&family=Quicksand:wght@400;500;600;700&family=Josefin+Sans:wght@400;500;600;700&family=Libre+Baskerville:wght@400;700&family=Crimson+Text:wght@400;600;700&family=Source+Serif+Pro:wght@400;600;700&family=PT+Serif:wght@400;700&family=Abril+Fatface&family=Dancing+Script:wght@400;500;600;700&family=Great+Vibes&family=Pacifico&display=swap" rel="stylesheet">

<script>
    // Sync color picker with text input
    function updateColorPreview(colorInput) {
        const textInput = colorInput.nextElementSibling;
        textInput.value = colorInput.value;
        updatePreview();
    }

    function syncColorInput(textInput) {
        const colorKey = textInput.dataset.colorKey;
        const colorInput = document.querySelector(`input[name="colors[${colorKey}]"]`);
        if (colorInput && /^#[0-9A-Fa-f]{6}$/.test(textInput.value)) {
            colorInput.value = textInput.value;
            updatePreview();
        }
    }

    // Update live preview
    function updatePreview() {
        const colors = {};
        document.querySelectorAll('input[type="color"]').forEach(input => {
            const key = input.name.replace('colors[', '').replace(']', '');
            colors[key] = input.value;
        });

        // Apply to preview
        const preview = document.getElementById('preview-container');
        
        // Header
        preview.querySelector('.preview-header').style.backgroundColor = colors.color_header_bg || '#FAF5ED';
        preview.querySelectorAll('.preview-header-text').forEach(el => el.style.color = colors.color_header_text || '#441227');
        
        // Background
        preview.querySelectorAll('.preview-bg-primary').forEach(el => el.style.backgroundColor = colors.color_bg_primary || '#FAF5ED');
        preview.querySelectorAll('.preview-bg-secondary').forEach(el => el.style.backgroundColor = colors.color_bg_secondary || '#EDE5DA');
        
        // Text
        preview.querySelectorAll('.preview-text-primary').forEach(el => el.style.color = colors.color_text_primary || '#2B2B2B');
        preview.querySelectorAll('.preview-text-secondary').forEach(el => el.style.color = colors.color_text_secondary || '#4B4B4B');
        
        // Buttons
        preview.querySelector('.preview-btn-primary').style.backgroundColor = colors.color_btn_primary || '#3D0C1F';
        preview.querySelector('.preview-btn-secondary').style.backgroundColor = colors.color_btn_secondary || '#495530';
        
        // Footer
        preview.querySelector('.preview-footer').style.backgroundColor = colors.color_footer_bg || '#FAF5ED';
        preview.querySelectorAll('.preview-footer-text').forEach(el => el.style.color = colors.color_footer_text || '#441227');
        
        // Apply fonts to preview
        updateFontPreview();
    }
    
    // Update font preview
    function updateFontPreview() {
        const headingFont = document.getElementById('font_heading').value;
        const bodyFont = document.getElementById('font_body').value;
        
        // Update heading preview
        const headingPreview = document.getElementById('heading-preview');
        if (headingPreview) {
            headingPreview.style.fontFamily = `'${headingFont}', serif`;
        }
        
        // Update body preview
        const bodyPreview = document.getElementById('body-preview');
        if (bodyPreview) {
            bodyPreview.style.fontFamily = `'${bodyFont}', sans-serif`;
        }
        
        // Update live preview card
        const preview = document.getElementById('preview-container');
        if (preview) {
            preview.querySelectorAll('.preview-header-text').forEach(el => {
                el.style.fontFamily = `'${headingFont}', serif`;
            });
            preview.querySelectorAll('h3').forEach(el => {
                el.style.fontFamily = `'${headingFont}', serif`;
            });
            preview.querySelectorAll('p').forEach(el => {
                el.style.fontFamily = `'${bodyFont}', sans-serif`;
            });
        }
        
        // Load font dynamically if not already loaded
        loadGoogleFont(headingFont);
        loadGoogleFont(bodyFont);
    }
    
    // Load Google Font dynamically
    function loadGoogleFont(fontName) {
        const fontId = 'font-' + fontName.replace(/\s+/g, '-').toLowerCase();
        if (!document.getElementById(fontId)) {
            const link = document.createElement('link');
            link.id = fontId;
            link.rel = 'stylesheet';
            link.href = `https://fonts.googleapis.com/css2?family=${fontName.replace(/\s+/g, '+')}:wght@400;500;600;700&display=swap`;
            document.head.appendChild(link);
        }
    }
    
    // Apply font pairing
    function applyFontPairing(headingFont, bodyFont) {
        document.getElementById('font_heading').value = headingFont;
        document.getElementById('font_body').value = bodyFont;
        updateFontPreview();
    }

    // Save settings
    function saveSettings() {
        const formData = new FormData();
        
        // Collect color settings
        document.querySelectorAll('input[type="color"]').forEach(input => {
            const key = input.name.replace('colors[', '').replace(']', '');
            formData.append(`colors[${key}]`, input.value);
        });
        
        // Collect font settings
        const headingFont = document.getElementById('font_heading');
        const bodyFont = document.getElementById('font_body');
        if (headingFont) formData.append('fonts[font_heading]', headingFont.value);
        if (bodyFont) formData.append('fonts[font_body]', bodyFont.value);

        fetch('{{ route("admin.theme-settings.update") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
            } else {
                alert('Error saving settings');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error saving settings');
        });
    }

    // Reset to default
    function resetToDefault() {
        if (!confirm('Are you sure you want to reset all settings to default?')) return;

        fetch('{{ route("admin.theme-settings.reset") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            }
        });
    }

    // Initialize preview on load
    document.addEventListener('DOMContentLoaded', function() {
        updatePreview();
        updateFontPreview();
    });
</script>
@endpush
@endsection
