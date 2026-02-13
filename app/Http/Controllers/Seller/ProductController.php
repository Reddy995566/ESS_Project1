<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Color;
use App\Models\Size;
use App\Models\Fabric;
use App\Models\Tag;
use App\Models\Brand;
use App\Services\ImageKitService;

class ProductController extends Controller
{
    protected $imageKitService;

    public function __construct(ImageKitService $imageKitService)
    {
        $this->imageKitService = $imageKitService;
    }

    public function index(Request $request)
    {
        $seller = Auth::guard('seller')->user();

        // Get base query for statistics
        $baseQuery = $seller->products();

        // Calculate statistics
        $totalProducts = $baseQuery->count();
        $activeProducts = (clone $baseQuery)->where('status', 'active')->count();
        $featuredProducts = (clone $baseQuery)->where('is_featured', true)->count();
        $outOfStockProducts = (clone $baseQuery)->where('stock_status', 'out_of_stock')->count();

        // Main query for products table
        $query = $seller->products()->with(['category', 'colors', 'sizes', 'variants']);

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter by approval status
        if ($request->has('approval_status') && $request->approval_status != '') {
            $query->where('approval_status', $request->approval_status);
        }

        // Filter by category
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('sku', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Get per page
        $perPage = $request->get('per_page', 10);
        $products = $query->latest()->paginate($perPage);

        // Get categories for filter dropdown
        $categories = Category::where('seller_id', $seller->id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('seller.products.index', compact(
            'products',
            'totalProducts',
            'activeProducts',
            'featuredProducts',
            'outOfStockProducts',
            'categories'
        ));
    }

    public function pending(Request $request)
    {
        $seller = Auth::guard('seller')->user();

        // Get base query for statistics
        $baseQuery = $seller->products()->where('approval_status', 'pending');

        // Calculate statistics
        $totalPending = $baseQuery->count();

        // Main query for products table
        $query = $seller->products()
            ->where('approval_status', 'pending')
            ->with(['category', 'colors', 'sizes', 'variants']);

        // Filter by category
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('sku', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Get per page
        $perPage = $request->get('per_page', 10);
        $products = $query->latest()->paginate($perPage);

        // Get categories for filter dropdown
        $categories = Category::where('seller_id', $seller->id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('seller.products.pending', compact(
            'products',
            'totalPending',
            'categories'
        ));
    }

    public function rejected()
    {
        $seller = Auth::guard('seller')->user();
        $products = $seller->products()
            ->where('approval_status', 'rejected')
            ->with(['category', 'colors', 'sizes', 'variants'])
            ->latest()
            ->paginate(20);

        return view('seller.products.rejected', compact('products'));
    }

    public function create()
    {
        // Redirect to step 1
        return redirect()->route('seller.products.create.step1');
    }

    // Step 1: Basic Information
    public function createStep1()
    {
        $productData = session('seller_product_data', []);
        return view('seller.products.create.step1', compact('productData'));
    }

    public function processStep1(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'slug' => 'nullable|string|max:255',
                'sku' => 'nullable|string|max:100',
                'description' => 'required|string',
                'washing_instructions' => 'nullable|string',
                'shipping_information' => 'nullable|string',
                'returns_refunds' => 'nullable|string',
            ]);

            // Generate slug if empty
            if (empty($validated['slug'])) {
                $baseSlug = Str::slug($validated['name']);
                $slug = $baseSlug;
                $counter = 1;
                while (Product::where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $counter;
                    $counter++;
                }
                $validated['slug'] = $slug;
            }

            // Clean empty HTML content
            if (isset($validated['washing_instructions'])) {
                $cleanContent = trim(strip_tags($validated['washing_instructions']));
                if (empty($cleanContent)) {
                    $validated['washing_instructions'] = null;
                }
            }

            if (isset($validated['shipping_information'])) {
                $cleanContent = trim(strip_tags($validated['shipping_information']));
                if (empty($cleanContent)) {
                    $validated['shipping_information'] = null;
                }
            }

            if (isset($validated['returns_refunds'])) {
                $cleanContent = trim(strip_tags($validated['returns_refunds']));
                if (empty($cleanContent)) {
                    $validated['returns_refunds'] = null;
                }
            }

            $productData = array_merge(session('seller_product_data', []), $validated);
            session(['seller_product_data' => $productData]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Step 1 completed!',
                    'next_step_url' => route('seller.products.create.step2'),
                ]);
            }

            return redirect()->route('seller.products.create.step2');
        } catch (\Exception $e) {
            \Log::error('Seller Step 1 failed', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    // Step 2: Images
    // Step 3: Categories & Organization
    public function createStep3()
    {
        $seller = Auth::guard('seller')->user();
        $productData = session('seller_product_data', []);

        // Get all categories with hierarchy for this seller
        $categories = Category::where('seller_id', $seller->id)
            ->whereNull('parent_id')
            ->with(['children.children'])
            ->orderBy('name')
            ->get();

        // Flatten categories for easier access
        $allCategories = Category::where('seller_id', $seller->id)->orderBy('name')->get();

        $collections = Collection::where('seller_id', $seller->id)->orderBy('name')->get();
        $tags = Tag::where('seller_id', $seller->id)->orderBy('name')->get();
        $brands = Brand::where('seller_id', $seller->id)->orderBy('name')->get();
        $fabrics = Fabric::where('seller_id', $seller->id)->orderBy('name')->get();

        return view('seller.products.create.step3', compact('productData', 'categories', 'allCategories', 'collections', 'tags', 'brands', 'fabrics'));
    }

    public function processStep3(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'fabric_id' => 'nullable|exists:fabrics,id',
            'sku' => 'nullable|string|max:100',
            'collections' => 'nullable|array',
            'collections.*' => 'exists:collections,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        $productData = array_merge(session('seller_product_data', []), $validated);
        session(['seller_product_data' => $productData]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Step 3 completed!',
                'next_step_url' => route('seller.products.create.step4'),
            ]);
        }

        return redirect()->route('seller.products.create.step4');
    }

    // Step 4: Inventory & Pricing
    public function createStep4()
    {
        $productData = session('seller_product_data', []);
        return view('seller.products.create.step4', compact('productData'));
    }

    public function processStep4(Request $request)
    {
        $validated = $request->validate([
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'stock_status' => 'nullable|in:in_stock,out_of_stock',
        ]);

        // Set default stock to 0 if not provided (will be managed at variant level)
        if (!isset($validated['stock'])) {
            $validated['stock'] = 0;
        }
        if (!isset($validated['stock_status'])) {
            $validated['stock_status'] = 'in_stock';
        }

        $productData = array_merge(session('seller_product_data', []), $validated);
        session(['seller_product_data' => $productData]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Step 4 completed!',
                'next_step_url' => route('seller.products.create.step5'),
            ]);
        }

        return redirect()->route('seller.products.create.step5');
    }

    // Step 2: Variants (Colors & Sizes)
    public function createStep2()
    {
        $seller = Auth::guard('seller')->user();
        $productData = session('seller_product_data', []);
        $colors = Color::where('seller_id', $seller->id)->orderBy('name')->get();
        $sizes = Size::where('seller_id', $seller->id)->orderBy('name')->get();

        return view('seller.products.create.step2', compact('productData', 'colors', 'sizes'));
    }

    public function processStep2(Request $request)
    {
        try {
            $validated = $request->validate([
                'variants_data' => 'required|json',
            ]);

            $variantsData = json_decode($validated['variants_data'], true);

            if (empty($variantsData)) {
                return back()->withErrors(['error' => 'Please add at least one variant']);
            }

            // Validate each variant
            foreach ($variantsData as $variant) {
                if (empty($variant['color_id'])) {
                    return back()->withErrors(['error' => 'All variants must have a color selected']);
                }
                if (empty($variant['size_ids']) || !is_array($variant['size_ids']) || count($variant['size_ids']) === 0) {
                    return back()->withErrors(['error' => 'All variants must have at least one size selected']);
                }
                // Validate size_stocks
                if (empty($variant['size_stocks']) || !is_array($variant['size_stocks'])) {
                    return back()->withErrors(['error' => 'All variants must have stock quantities for each size']);
                }
                foreach ($variant['size_ids'] as $sizeId) {
                    if (!isset($variant['size_stocks'][$sizeId]) || $variant['size_stocks'][$sizeId] < 0) {
                        return back()->withErrors(['error' => 'All sizes must have valid stock quantities (0 or more)']);
                    }
                }
            }

            $productData = array_merge(session('seller_product_data', []), $validated);
            session(['seller_product_data' => $productData]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Variants saved successfully!',
                    'next_step_url' => route('seller.products.create.step3'),
                    'data' => $validated
                ]);
            }

            return redirect()->route('seller.products.create.step3');

        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->validator->errors()
                ], 422);
            }

            throw $e;
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred: ' . $e->getMessage()
                ], 500);
            }

            return back()->withInput()->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    // Step 5: SEO & Meta
    public function createStep5()
    {
        $productData = session('seller_product_data', []);
        return view('seller.products.create.step5', compact('productData'));
    }

    public function processStep5(Request $request)
    {
        $validated = $request->validate([
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'focus_keywords' => 'nullable|string',
            'canonical_url' => 'nullable|url',
            'og_title' => 'nullable|string|max:60',
            'og_description' => 'nullable|string|max:160',
        ]);

        $productData = array_merge(session('seller_product_data', []), $validated);
        session(['seller_product_data' => $productData]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Step 5 (SEO) completed!',
                'next_step_url' => route('seller.products.create.step6'),
            ]);
        }

        return redirect()->route('seller.products.create.step6');
    }

    // Step 6: Settings & Review
    public function createStep6()
    {
        $productData = session('seller_product_data', []);
        return view('seller.products.create.step6', compact('productData'));
    }

    public function processStep6(Request $request)
    {
        $seller = Auth::guard('seller')->user();
        $productData = session('seller_product_data', []);

        \Log::info('Seller processStep7 called', [
            'seller_id' => $seller ? $seller->id : 'NULL',
            'has_product_data' => !empty($productData),
            'product_data_keys' => array_keys($productData),
        ]);

        if (empty($productData)) {
            \Log::error('No product data in session');
            return response()->json(['success' => false, 'message' => 'No product data found'], 422);
        }

        try {
            DB::beginTransaction();

            // Generate SKU if not provided
            if (empty($productData['sku'])) {
                $productData['sku'] = 'SELLER-' . strtoupper(Str::random(8));
            }

            // Handle images - Use variant images if available
            $mainImage = null;
            $additionalImages = [];

            // Check for variant images first
            if (!empty($productData['variant_images'])) {
                $variantImages = is_string($productData['variant_images'])
                    ? json_decode($productData['variant_images'], true)
                    : $productData['variant_images'];

                if (is_array($variantImages) && !empty($variantImages)) {
                    // Get all images from all color variants
                    foreach ($variantImages as $colorId => $images) {
                        if (is_array($images)) {
                            foreach ($images as $img) {
                                if (isset($img['url'])) {
                                    $additionalImages[] = $img;
                                }
                            }
                        }
                    }

                    // First image becomes main image
                    if (!empty($additionalImages)) {
                        $mainImage = $additionalImages[0]['url'] ?? null;
                    }
                }
            }

            // Fallback to additional_images if no variant images
            if (empty($mainImage) && !empty($productData['additional_images'])) {
                if (is_string($productData['additional_images'])) {
                    $additionalImages = json_decode($productData['additional_images'], true) ?? [];
                } else {
                    $additionalImages = $productData['additional_images'];
                }

                // First image becomes main image
                if (!empty($additionalImages) && is_array($additionalImages)) {
                    $mainImage = $additionalImages[0]['url'] ?? null;
                }
            }

            // Get settings from step 7
            $validated = $request->validate([
                'status' => 'nullable|in:active,inactive,draft',
                'visibility' => 'nullable|in:visible,hidden,catalog,search',
                'is_featured' => 'nullable|boolean',
                'is_new' => 'nullable|boolean',
                'is_trending' => 'nullable|boolean',
                'is_bestseller' => 'nullable|boolean',
                'is_topsale' => 'nullable|boolean',
                'is_sale' => 'nullable|boolean',
                'show_in_homepage' => 'nullable|boolean',
                'is_exclusive' => 'nullable|boolean',
                'is_limited_edition' => 'nullable|boolean',
            ]);

            // Merge step 7 data
            $productData = array_merge($productData, $validated);

            // Create product
            $product = Product::create([
                'seller_id' => $seller->id,
                'approval_status' => 'pending',
                'name' => $productData['name'] ?? '',
                'slug' => $productData['slug'] ?? '',
                'sku' => $productData['sku'],
                'description' => $productData['description'] ?? null,
                'washing_instructions' => $productData['washing_instructions'] ?? null,
                'shipping_information' => $productData['shipping_information'] ?? null,
                'returns_refunds' => $productData['returns_refunds'] ?? null,
                'price' => $productData['price'] ?? 0,
                'sale_price' => $productData['sale_price'] ?? null,
                'stock' => $productData['stock'] ?? 0,
                'stock_status' => $productData['stock_status'] ?? 'in_stock',
                'category_id' => $productData['category_id'] ?? null,
                'brand_id' => $productData['brand_id'] ?? null,
                'fabric_id' => $productData['fabric_id'] ?? null,
                'status' => $productData['status'] ?? 'draft',
                'visibility' => $productData['visibility'] ?? 'visible',
                'image' => $mainImage,
                'images' => $additionalImages,
                'meta_title' => $productData['meta_title'] ?? null,
                'meta_description' => $productData['meta_description'] ?? null,
                'focus_keywords' => $productData['focus_keywords'] ?? null,
                'canonical_url' => $productData['canonical_url'] ?? null,
                'og_title' => $productData['og_title'] ?? null,
                'og_description' => $productData['og_description'] ?? null,
                'is_featured' => $productData['is_featured'] ?? false,
                'is_new' => $productData['is_new'] ?? false,
                'is_trending' => $productData['is_trending'] ?? false,
                'is_bestseller' => $productData['is_bestseller'] ?? false,
                'is_topsale' => $productData['is_topsale'] ?? false,
                'is_sale' => $productData['is_sale'] ?? false,
                'show_in_homepage' => $productData['show_in_homepage'] ?? false,
                'is_exclusive' => $productData['is_exclusive'] ?? false,
                'is_limited_edition' => $productData['is_limited_edition'] ?? false,
            ]);

            \Log::info('Product created successfully', [
                'product_id' => $product->id,
                'seller_id' => $product->seller_id,
                'approval_status' => $product->approval_status,
                'name' => $product->name,
            ]);

            // Sync relationships
            if (!empty($productData['collections'])) {
                $product->collections()->sync($productData['collections']);
            }
            if (!empty($productData['tags'])) {
                $product->tags()->sync($productData['tags']);
            }

            // Sync colors - check multiple possible keys
            $colorsToSync = [];
            if (!empty($productData['variant_colors'])) {
                $colorsToSync = $productData['variant_colors'];
            } elseif (!empty($productData['selected_colors'])) {
                $colorsToSync = $productData['selected_colors'];
            } elseif (!empty($productData['colors'])) {
                $colorsToSync = $productData['colors'];
            }

            if (!empty($colorsToSync)) {
                \Log::info('Syncing colors', ['colors' => $colorsToSync]);
                $product->colors()->sync($colorsToSync);
            }

            // Sync sizes - check multiple possible keys
            $sizesToSync = [];
            if (!empty($productData['variant_sizes'])) {
                $sizesToSync = $productData['variant_sizes'];
            } elseif (!empty($productData['selected_sizes'])) {
                $sizesToSync = $productData['selected_sizes'];
            } elseif (!empty($productData['sizes'])) {
                $sizesToSync = $productData['sizes'];
            }

            if (!empty($sizesToSync)) {
                \Log::info('Syncing sizes', ['sizes' => $sizesToSync]);
                $product->sizes()->sync($sizesToSync);
            }

            // Handle new variants_data format (from step 2)
            if (isset($productData['variants_data']) && !empty($productData['variants_data'])) {
                $variantsData = is_string($productData['variants_data'])
                    ? json_decode($productData['variants_data'], true)
                    : $productData['variants_data'];

                if (is_array($variantsData) && count($variantsData) > 0) {
                    $allColorIds = [];
                    $allSizeIds = [];
                    $defaultVariantSet = false;

                    foreach ($variantsData as $variantData) {
                        $colorId = $variantData['color_id'];
                        $sizeIds = $variantData['size_ids'] ?? [];
                        $sizeStocks = $variantData['size_stocks'] ?? []; // Get size-specific stocks
                        $images = json_encode($variantData['images'] ?? []);
                        $isDefault = $variantData['is_default'] ?? false;

                        $allColorIds[] = $colorId;

                        // Create one variant for each size with its specific stock
                        foreach ($sizeIds as $sizeId) {
                            $allSizeIds[] = $sizeId;
                            $stock = $sizeStocks[$sizeId] ?? 0; // Get stock for this specific size

                            // Generate unique SKU for variant
                            $variantSku = $product->sku;
                            $color = \App\Models\Color::find($colorId);
                            if ($color) {
                                $variantSku .= '-' . strtoupper(substr($color->name, 0, 2));
                            }
                            $size = \App\Models\Size::find($sizeId);
                            if ($size) {
                                $variantSku .= '-' . ($size->abbreviation ?? strtoupper(substr($size->name, 0, 2)));
                            }

                            // Only set as default if this variant is marked as default and no default has been set yet
                            $shouldBeDefault = $isDefault && !$defaultVariantSet;
                            if ($shouldBeDefault) {
                                $defaultVariantSet = true;
                            }

                            $product->variants()->create([
                                'sku' => $variantSku,
                                'color_id' => $colorId,
                                'size_id' => $sizeId,
                                'stock' => $stock,
                                'images' => $images,
                                'is_default' => $shouldBeDefault
                            ]);
                        }
                    }

                    // If no default was set, set first variant as default
                    if (!$defaultVariantSet) {
                        $firstVariant = $product->variants()->first();
                        if ($firstVariant) {
                            $firstVariant->update(['is_default' => true]);
                        }
                    }

                    // Update synced colors and sizes with unique values
                    $uniqueColorIds = array_unique($allColorIds);
                    $uniqueSizeIds = array_unique($allSizeIds);

                    if (!empty($uniqueColorIds)) {
                        $product->colors()->sync($uniqueColorIds);
                    }
                    if (!empty($uniqueSizeIds)) {
                        $product->sizes()->sync($uniqueSizeIds);
                    }
                }
            }

            // Log activity
            $seller->logActivity('product_created', 'Created product: ' . $product->name);

            // Notification
            $seller->notifications()->create([
                'type' => 'product_pending',
                'title' => 'Product Submitted',
                'message' => 'Product "' . $product->name . '" submitted for approval.',
                'data' => json_encode(['product_id' => $product->id]),
                'is_read' => false,
            ]);

            DB::commit();

            // Clear session
            session()->forget('seller_product_data');

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product created successfully!',
                    'redirect' => route('seller.products.index'),
                ]);
            }

            return redirect()->route('seller.products.index')->with('success', 'Product created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Seller product creation failed', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function clearSession()
    {
        session()->forget('seller_product_data');
        return response()->json(['success' => true, 'message' => 'Session cleared']);
    }

    public function uploadImage(Request $request)
    {
        try {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240', // 10MB max
            ]);

            $imageKitService = app(\App\Services\ImageKitService::class);
            $folder = $request->input('folder', 'products');

            // Use uploadProductImage method
            $result = $imageKitService->uploadProductImage($request->file('image'), $folder);

            if (!$result || !$result['success']) {
                throw new \Exception('Image upload failed');
            }

            return response()->json([
                'success' => true,
                'url' => $result['url'],
                'fileId' => $result['file_id'] ?? null,
                'file_id' => $result['file_id'] ?? null,
                'name' => $result['name'] ?? $request->file('image')->getClientOriginalName(),
                'size' => $result['size'] ?? $request->file('image')->getSize(),
                'width' => $result['width'] ?? null,
                'height' => $result['height'] ?? null,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . implode(', ', $e->errors()['image'] ?? ['Invalid image'])
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Image upload failed: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $seller = Auth::guard('seller')->user();

        try {
            DB::beginTransaction();

            // Minimal validation - only product_name is required since products go through admin approval
            $validated = $request->validate([
                'product_name' => 'required|string|max:255',
                'name' => 'nullable|string|max:255',
                'slug' => 'nullable|string|max:255|unique:products,slug',
                'sku' => 'nullable|string|max:100|unique:products,sku',
                'description' => 'nullable|string',
                'washing_instructions' => 'nullable|string',
                'shipping_information' => 'nullable|string',
                'returns_refunds' => 'nullable|string',
                'price' => 'nullable|numeric|min:0',
                'sale_price' => 'nullable|numeric|min:0',
                'stock' => 'nullable|integer|min:0',
                'stock_status' => 'nullable|in:in_stock,out_of_stock',
                'brand_id' => 'nullable|exists:brands,id',
                'category_id' => 'nullable|exists:categories,id',
                'fabric_id' => 'nullable|exists:fabrics,id',
                'tags' => 'nullable|array',
                'tags.*' => 'exists:tags,id',
                'collections' => 'nullable|array',
                'collections.*' => 'exists:collections,id',
                'colors' => 'nullable|array',
                'colors.*' => 'exists:colors,id',
                'sizes' => 'nullable|array',
                'sizes.*' => 'exists:sizes,id',
                'status' => 'nullable|in:active,inactive',
                'visibility' => 'nullable|in:visible,hidden,catalog,search',
                'main_image' => 'nullable|string',
                'additional_images' => 'nullable|string',
                'meta_title' => 'nullable|string|max:60',
                'meta_description' => 'nullable|string|max:160',
                'focus_keywords' => 'nullable|string',
                'is_featured' => 'nullable|boolean',
                'is_new' => 'nullable|boolean',
                'show_in_homepage' => 'nullable|boolean',
            ]);

            // Generate slug if not provided
            $productName = $validated['product_name'] ?? $validated['name'] ?? 'Product';
            if (empty($validated['slug'])) {
                $validated['slug'] = Str::slug($productName) . '-' . time();
            }

            // Generate SKU if not provided
            if (empty($validated['sku'])) {
                $validated['sku'] = 'SELLER-' . strtoupper(Str::random(8));
            }

            // Handle images
            $validated['image'] = $request->input('main_image');
            $additionalImages = $request->input('additional_images', []);
            if (is_string($additionalImages)) {
                $additionalImages = json_decode($additionalImages, true) ?? [];
            }
            $validated['images'] = $additionalImages;

            // Handle boolean fields
            $validated['is_featured'] = $request->has('is_featured');
            $validated['is_new'] = $request->has('is_new');
            $validated['show_in_homepage'] = $request->has('show_in_homepage');

            // Create product with seller_id and pending approval
            $product = Product::create([
                'seller_id' => $seller->id,
                'approval_status' => 'pending',
                'name' => $productName,
                'slug' => $validated['slug'],
                'sku' => $validated['sku'],
                'description' => $validated['description'] ?? null,
                'washing_instructions' => $validated['washing_instructions'] ?? null,
                'shipping_information' => $validated['shipping_information'] ?? null,
                'returns_refunds' => $validated['returns_refunds'] ?? null,
                'price' => $validated['price'] ?? 0,
                'sale_price' => $validated['sale_price'] ?? null,
                'stock' => $validated['stock'] ?? 0,
                'stock_status' => $validated['stock_status'] ?? 'in_stock',
                'brand_id' => $validated['brand_id'] ?? null,
                'category_id' => $validated['category_id'] ?? null,
                'fabric_id' => $validated['fabric_id'] ?? null,
                'status' => $validated['status'] ?? 'inactive',
                'visibility' => $validated['visibility'] ?? 'visible',
                'image' => $validated['image'] ?? null,
                'images' => $validated['images'] ?? [],
                'meta_title' => $validated['meta_title'] ?? null,
                'meta_description' => $validated['meta_description'] ?? null,
                'focus_keywords' => $validated['focus_keywords'] ?? null,
                'is_featured' => $validated['is_featured'],
                'is_new' => $validated['is_new'],
                'show_in_homepage' => $validated['show_in_homepage'],
            ]);

            // Sync relationships
            if (!empty($validated['collections'])) {
                $product->collections()->sync($validated['collections']);
            }

            if (!empty($validated['colors'])) {
                $product->colors()->sync($validated['colors']);
            }

            if (!empty($validated['sizes'])) {
                $product->sizes()->sync($validated['sizes']);
            }

            if (!empty($validated['tags'])) {
                $product->tags()->sync($validated['tags']);
            }

            // Log activity
            $seller->logActivity('product_created', 'Created product: ' . $product->name);

            // Create notification for seller
            $seller->notifications()->create([
                'type' => 'product_pending',
                'title' => 'Product Submitted for Approval',
                'message' => 'Your product "' . $product->name . '" has been submitted for admin approval.',
                'data' => json_encode(['product_id' => $product->id]),
                'is_read' => false,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Product created successfully and submitted for approval!',
                'product_id' => $product->id,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Seller product creation failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error creating product: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function edit($id)
    {
        $seller = Auth::guard('seller')->user();
        $product = $seller->products()
            ->with(['colors', 'sizes', 'tags', 'collections', 'category', 'brand', 'fabric', 'variants'])
            ->findOrFail($id);

        // Note: Allow editing approved products but they will need re-approval
        // No redirect needed - just proceed to edit form

        $step = request()->get('step', 1);

        // Map step numbers to correct views
        $stepViewMap = [
            1 => 'step1', // Basic Info
            2 => 'step2', // Variants
            3 => 'step3', // Categories & Organization
            4 => 'step4', // Pricing & Inventory
            5 => 'step5', // SEO
            6 => 'step6', // Settings
        ];

        $viewFile = $stepViewMap[$step] ?? 'step1';

        // Load seller-specific data
        $categories = Category::where('seller_id', $seller->id)->where('is_active', true)->orderBy('name')->get();
        $allCategories = Category::where('seller_id', $seller->id)->where('is_active', true)->orderBy('name')->get();
        $collections = Collection::where('seller_id', $seller->id)->where('is_active', true)->orderBy('name')->get();
        $colors = Color::where('seller_id', $seller->id)->where('is_active', true)->orderBy('name')->get();
        $sizes = Size::where('seller_id', $seller->id)->where('is_active', true)->orderBy('sort_order')->get();
        $fabrics = Fabric::where('seller_id', $seller->id)->where('is_active', true)->orderBy('name')->get();
        $tags = Tag::where('seller_id', $seller->id)->where('is_active', true)->orderBy('name')->get();
        $brands = Brand::where('seller_id', $seller->id)->where('is_active', true)->orderBy('name')->get();

        return view('seller.products.edit.' . $viewFile, compact(
            'step',
            'product',
            'categories',
            'allCategories',
            'collections',
            'colors',
            'sizes',
            'fabrics',
            'tags',
            'brands'
        ));
    }

    public function update(Request $request, $id)
    {
        $seller = Auth::guard('seller')->user();
        $product = $seller->products()->findOrFail($id);

        \Log::info('Seller product update called', [
            'product_id' => $id,
            'seller_id' => $seller->id,
            'request_data' => $request->all(),
            'request_method' => $request->method(),
            'content_type' => $request->header('Content-Type'),
        ]);

        // If editing an approved product, reset approval status to pending
        $wasApproved = $product->approval_status === 'approved';

        try {
            DB::beginTransaction();

            // Use 'sometimes|required' for partial updates like admin panel
            $validated = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'slug' => 'sometimes|required|string|max:255|unique:products,slug,' . $id,
                'description' => 'sometimes|nullable|string',
                'washing_instructions' => 'sometimes|nullable|string',
                'shipping_information' => 'sometimes|nullable|string',
                'returns_refunds' => 'sometimes|nullable|string',
                'price' => 'sometimes|required|numeric|min:0',
                'sale_price' => 'sometimes|nullable|numeric|min:0',
                'sku' => 'sometimes|required|string|max:100|unique:products,sku,' . $id,
                'stock' => 'sometimes|required|integer|min:0',
                'stock_status' => 'sometimes|required|in:in_stock,out_of_stock',
                'category_id' => 'sometimes|required|exists:categories,id',
                'fabric_id' => 'sometimes|nullable|exists:fabrics,id',
                'brand_id' => 'sometimes|nullable|exists:brands,id',
                'status' => 'sometimes|required|in:active,inactive,draft',
                'visibility' => 'sometimes|required|in:visible,hidden,catalog,search',
                'meta_title' => 'sometimes|nullable|string|max:60',
                'meta_description' => 'sometimes|nullable|string|max:160',
                'focus_keywords' => 'sometimes|nullable|string',
                'canonical_url' => 'sometimes|nullable|url',
                'og_title' => 'sometimes|nullable|string|max:60',
                'og_description' => 'sometimes|nullable|string|max:160',
                'is_featured' => 'sometimes|nullable|boolean',
                'is_new' => 'sometimes|nullable|boolean',
                'is_trending' => 'sometimes|nullable|boolean',
                'is_bestseller' => 'sometimes|nullable|boolean',
                'is_topsale' => 'sometimes|nullable|boolean',
                'is_sale' => 'sometimes|nullable|boolean',
                'show_in_homepage' => 'sometimes|nullable|boolean',
                'is_exclusive' => 'sometimes|nullable|boolean',
                'is_limited_edition' => 'sometimes|nullable|boolean',
                'collections' => 'sometimes|nullable|array',
                'collections.*' => 'exists:collections,id',
                'tags' => 'sometimes|nullable|array',
                'tags.*' => 'exists:tags,id',
                // Step-based validation
                'step' => 'sometimes|nullable|string',
                'has_variants' => 'sometimes|nullable|boolean',
                'variant_colors' => 'sometimes|nullable|string',
                'variant_sizes' => 'sometimes|nullable|string',
                'variant_images' => 'sometimes|nullable|string',
            ]);

            \Log::info('Validation passed', ['validated_data' => $validated]);

            // IMPORTANT: If product is already approved, keep it approved regardless of changes
            // Seller can make any updates without requiring re-approval
            if ($wasApproved) {
                $validated['approval_status'] = 'approved';
                \Log::info('Product was approved, keeping approval status as approved');
            } elseif (!$wasApproved) {
                // If it wasn't approved before, keep it pending
                $validated['approval_status'] = 'pending';
                \Log::info('Product was not approved, keeping as pending');
            }

            // Update only provided fields
            $product->update($validated);

            \Log::info('Product updated', ['product_id' => $product->id, 'approval_status' => $product->approval_status]);

            // Handle step-based updates
            if ($request->has('step') && $request->step == '2') {
                \Log::info('Processing step 2 (variants) update', [
                    'step' => $request->step,
                    'variants_data' => $request->variants_data,
                ]);

                // Step 2: Variants - handle with new variants_data format
                if ($request->has('variants_data')) {
                    $variantsData = json_decode($request->input('variants_data'), true);

                    if (!empty($variantsData)) {
                        // Delete existing variants
                        $deletedVariants = $product->variants()->delete();
                        \Log::info('Deleted existing variants', ['count' => $deletedVariants]);

                        // Create new variants - one for each size with its own stock
                        $allColorIds = [];
                        $allSizeIds = [];
                        $defaultVariantSet = false;

                        foreach ($variantsData as $variantData) {
                            $colorId = $variantData['color_id'];
                            $sizeIds = $variantData['size_ids'];
                            $sizeStocks = $variantData['size_stocks']; // Object with sizeId => stock
                            $images = json_encode($variantData['images'] ?? []);
                            $isDefault = $variantData['is_default'] ?? false;

                            $allColorIds[] = $colorId;

                            // Create one variant for each size with its specific stock
                            foreach ($sizeIds as $sizeId) {
                                $allSizeIds[] = $sizeId;
                                $stock = $sizeStocks[$sizeId] ?? 0; // Get stock for this specific size

                                // Generate unique SKU for variant
                                $variantSku = $product->sku;
                                $color = \App\Models\Color::find($colorId);
                                if ($color) {
                                    $variantSku .= '-' . strtoupper(substr($color->name, 0, 2));
                                }
                                $size = \App\Models\Size::find($sizeId);
                                if ($size) {
                                    $variantSku .= '-' . ($size->abbreviation ?? strtoupper(substr($size->name, 0, 2)));
                                }

                                // Only set as default if this variant is marked as default and no default has been set yet
                                $shouldBeDefault = $isDefault && !$defaultVariantSet;
                                if ($shouldBeDefault) {
                                    $defaultVariantSet = true;
                                }

                                $product->variants()->create([
                                    'sku' => $variantSku,
                                    'color_id' => $colorId,
                                    'size_id' => $sizeId,
                                    'stock' => $stock,
                                    'images' => $images,
                                    'is_default' => $shouldBeDefault
                                ]);
                            }
                        }

                        // If no default was set, set first variant as default
                        if (!$defaultVariantSet) {
                            $firstVariant = $product->variants()->first();
                            if ($firstVariant) {
                                $firstVariant->update(['is_default' => true]);
                            }
                        }

                        // Sync colors and sizes relationships
                        $uniqueColorIds = array_unique($allColorIds);
                        $uniqueSizeIds = array_unique($allSizeIds);

                        if (!empty($uniqueColorIds)) {
                            $product->colors()->sync($uniqueColorIds);
                            \Log::info('Colors synced', ['count' => count($uniqueColorIds)]);
                        }

                        if (!empty($uniqueSizeIds)) {
                            $product->sizes()->sync($uniqueSizeIds);
                            \Log::info('Sizes synced', ['count' => count($uniqueSizeIds)]);
                        }

                        \Log::info('Step 2 processing completed');
                    } else {
                        \Log::info('No variants data, clearing all variants');
                        // Clear variants if empty
                        $product->variants()->delete();
                        $product->colors()->sync([]);
                        $product->sizes()->sync([]);
                    }
                }
            }

            // Handle step 3 (categories & organization) - Always sync collections and tags
            if ($request->has('step') && $request->step == '3') {
                \Log::info('Processing step 3 - syncing collections and tags');

                // Sync collections - if not provided or empty, clear them
                $collections = $request->input('collections', []);
                if (!is_array($collections)) {
                    $collections = [];
                }
                \Log::info('Syncing collections (step 3)', ['collections' => $collections]);
                $product->collections()->sync($collections);

                // Sync tags - if not provided or empty, clear them
                $tags = $request->input('tags', []);
                if (!is_array($tags)) {
                    $tags = [];
                }
                \Log::info('Syncing tags (step 3)', ['tags' => $tags]);
                $product->tags()->sync($tags);
            } else {
                // Regular sync for non-step updates
                if ($request->has('collections')) {
                    \Log::info('Syncing collections', ['collections' => $request->collections]);
                    $product->collections()->sync($request->collections ?? []);
                }

                if ($request->has('colors')) {
                    \Log::info('Syncing colors (regular)', ['colors' => $request->colors]);
                    $product->colors()->sync($request->colors);
                }

                if ($request->has('sizes')) {
                    \Log::info('Syncing sizes (regular)', ['sizes' => $request->sizes]);
                    $product->sizes()->sync($request->sizes);
                }

                if ($request->has('tags')) {
                    \Log::info('Syncing tags', ['tags' => $request->tags]);
                    $product->tags()->sync($request->tags ?? []);
                }
            }

            // Log activity
            $seller->logActivity('product_updated', 'Updated product: ' . $product->name);

            // No notification needed - product stays approved

            DB::commit();

            \Log::info('Product update completed successfully', ['product_id' => $product->id]);

            $message = 'Product updated successfully!';

            return response()->json([
                'success' => true,
                'message' => $message,
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            \Log::error('Validation failed', ['errors' => $e->validator->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->validator->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Product update failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Error updating product: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        $seller = Auth::guard('seller')->user();
        $product = $seller->products()->findOrFail($id);

        // Check if product can be deleted
        if ($product->approval_status === 'approved') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete approved products. Please contact admin.',
            ], 403);
        }

        try {
            // Log activity
            $seller->logActivity('product_deleted', 'Deleted product: ' . $product->name);

            $product->delete();

            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully!',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting product: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function deleteImage(Request $request)
    {
        $request->validate([
            'file_id' => 'required|string',
        ]);

        try {
            $this->imageKitService->delete($request->file_id);

            return response()->json([
                'success' => true,
                'message' => 'Image deleted successfully!',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting image: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function generateSlug(Request $request)
    {
        $name = $request->input('name');
        $slug = Str::slug($name);

        // Check if slug exists
        $count = 1;
        $originalSlug = $slug;
        while (Product::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return response()->json(['slug' => $slug]);
    }

    /**
     * Display the specified product for AJAX modal
     */
    public function show($id)
    {
        $seller = Auth::guard('seller')->user();
        $product = $seller->products()
            ->with(['category', 'brand', 'colors', 'sizes', 'tags', 'collections', 'variants'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'product' => $product,
        ]);
    }

    /**
     * Toggle product field (status, is_featured)
     */
    public function toggle(Request $request, $id)
    {
        $seller = Auth::guard('seller')->user();
        $product = $seller->products()->findOrFail($id);

        $field = $request->input('field');
        $value = $request->input('value');

        // Validate allowed fields
        $allowedFields = ['status', 'is_featured', 'is_new', 'is_sale'];
        if (!in_array($field, $allowedFields)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid field',
            ], 400);
        }

        try {
            $product->update([$field => $value]);

            $fieldNames = [
                'status' => 'Status',
                'is_featured' => 'Featured',
                'is_new' => 'New',
                'is_sale' => 'Sale',
            ];

            $statusText = is_bool($value) ? ($value ? 'enabled' : 'disabled') : "changed to {$value}";

            return response()->json([
                'success' => true,
                'message' => "{$fieldNames[$field]} {$statusText} successfully!",
                'product' => $product->fresh(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating product: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Copy/Duplicate a product
     */
    public function copy($id)
    {
        $seller = Auth::guard('seller')->user();
        $product = $seller->products()->with(['colors', 'sizes', 'tags', 'collections'])->findOrFail($id);

        try {
            DB::beginTransaction();

            // Create copy
            $newProduct = $product->replicate();
            $newProduct->name = $product->name . ' (Copy)';
            $newProduct->slug = Str::slug($newProduct->name) . '-' . time();
            $newProduct->sku = 'SELLER-' . strtoupper(Str::random(8));
            $newProduct->status = 'draft';
            $newProduct->approval_status = 'pending';
            $newProduct->created_at = now();
            $newProduct->updated_at = now();
            $newProduct->save();

            // Copy relationships with null checks
            if ($product->colors && $product->colors->count() > 0) {
                $newProduct->colors()->sync($product->colors->pluck('id'));
            }
            if ($product->sizes && $product->sizes->count() > 0) {
                $newProduct->sizes()->sync($product->sizes->pluck('id'));
            }
            if ($product->tags && $product->tags->count() > 0) {
                $newProduct->tags()->sync($product->tags->pluck('id'));
            }
            if ($product->collections && $product->collections->count() > 0) {
                $newProduct->collections()->sync($product->collections->pluck('id'));
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Product duplicated successfully!',
                'product_id' => $newProduct->id,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error duplicating product: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Perform bulk actions on products
     */
    public function bulkAction(Request $request)
    {
        $seller = Auth::guard('seller')->user();
        $action = $request->input('action');
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'No products selected',
            ], 400);
        }

        $products = $seller->products()->whereIn('id', $ids);

        try {
            switch ($action) {
                case 'activate':
                    $products->update(['status' => 'active']);
                    $message = 'Products activated successfully!';
                    break;

                case 'deactivate':
                    $products->update(['status' => 'inactive']);
                    $message = 'Products deactivated successfully!';
                    break;

                case 'feature':
                    $products->update(['is_featured' => true]);
                    $message = 'Products marked as featured!';
                    break;

                case 'unfeature':
                    $products->update(['is_featured' => false]);
                    $message = 'Featured status removed from products!';
                    break;

                case 'in_stock':
                    $products->update(['stock_status' => 'in_stock']);
                    $message = 'Products marked as in stock!';
                    break;

                case 'out_of_stock':
                    $products->update(['stock_status' => 'out_of_stock']);
                    $message = 'Products marked as out of stock!';
                    break;

                case 'delete':
                    // Only delete non-approved products
                    $products->where('approval_status', '!=', 'approved')->delete();
                    $message = 'Selected products deleted!';
                    break;

                default:
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid action',
                    ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error performing bulk action: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Export products to CSV
     */
    public function export(Request $request)
    {
        $seller = Auth::guard('seller')->user();

        $query = $seller->products()->with(['category']);

        // Apply filters
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        $products = $query->get();

        // Generate CSV
        $filename = 'products_export_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($products) {
            $file = fopen('php://output', 'w');

            // CSV Headers
            fputcsv($file, [
                'ID',
                'Name',
                'SKU',
                'Category',
                'Price',
                'Sale Price',
                'Stock',
                'Stock Status',
                'Status',
                'Featured',
                'Created At'
            ]);

            // CSV Data
            foreach ($products as $product) {
                fputcsv($file, [
                    $product->id,
                    $product->name,
                    $product->sku,
                    $product->category?->name ?? 'N/A',
                    $product->price,
                    $product->sale_price ?? 'N/A',
                    $product->stock,
                    $product->stock_status,
                    $product->status,
                    $product->is_featured ? 'Yes' : 'No',
                    $product->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Create product variants (copied from admin controller)
     */
    private function createProductVariants(Product $product, $productData)
    {
        \Log::info('createProductVariants called', [
            'product_id' => $product->id,
            'productData' => $productData
        ]);

        $colors = json_decode($productData['variant_colors'] ?? '[]', true) ?? [];
        $sizes = json_decode($productData['variant_sizes'] ?? '[]', true) ?? [];
        $colorImages = json_decode($productData['variant_images'] ?? '{}', true) ?? [];
        $colorStock = $productData['color_stock'] ?? [];

        \Log::info('Parsed variant data', [
            'colors' => $colors,
            'sizes' => $sizes,
            'colorImages' => $colorImages,
            'colorStock' => $colorStock
        ]);

        $hasColors = !empty($colors);
        $hasSizes = !empty($sizes);
        $isFirstVariant = true;

        if ($hasColors && $hasSizes) {
            \Log::info('Creating variants with both colors and sizes');
            // Both colors and sizes
            foreach ($colors as $colorId) {
                foreach ($sizes as $sizeId) {
                    $stock = $colorStock[$colorId] ?? 0;
                    $this->createVariant($product, $colorId, $sizeId, $colorImages, $stock, $isFirstVariant);
                    $isFirstVariant = false;
                }
            }
        } elseif ($hasColors) {
            \Log::info('Creating variants with colors only');
            // Only colors
            foreach ($colors as $colorId) {
                $stock = $colorStock[$colorId] ?? 0;
                $this->createVariant($product, $colorId, null, $colorImages, $stock, $isFirstVariant);
                $isFirstVariant = false;
            }
        } elseif ($hasSizes) {
            \Log::info('Creating variants with sizes only');
            // Only sizes
            foreach ($sizes as $sizeId) {
                $stock = $product->stock ?? 0;
                $this->createVariant($product, null, $sizeId, $colorImages, $stock, $isFirstVariant);
                $isFirstVariant = false;
            }
        } else {
            \Log::info('No colors or sizes provided, no variants created');
        }

        \Log::info('createProductVariants completed');
    }

    /**
     * Create individual variant (copied from admin controller)
     */
    private function createVariant(Product $product, $colorId, $sizeId, $colorImages, $stock, $isDefault)
    {
        \Log::info('createVariant called', [
            'product_id' => $product->id,
            'colorId' => $colorId,
            'sizeId' => $sizeId,
            'stock' => $stock,
            'isDefault' => $isDefault
        ]);

        // Generate SKU
        $sku = $product->sku;
        if ($colorId) {
            $color = \App\Models\Color::find($colorId);
            $sku .= '-' . strtoupper(substr($color->name ?? 'COL', 0, 2));
        }
        if ($sizeId) {
            $size = \App\Models\Size::find($sizeId);
            $sku .= '-' . ($size->abbreviation ?? strtoupper(substr($size->name ?? 'SIZ', 0, 2)));
        }
        $sku .= '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);

        // Get variant images
        $variantImages = [];
        if ($colorId && isset($colorImages[$colorId])) {
            $variantImages = array_column($colorImages[$colorId], 'url');
        }

        $variantData = [
            'product_id' => $product->id,
            'sku' => $sku,
            'color_id' => $colorId,
            'size_id' => $sizeId,
            'price' => $product->price, // Default to product price
            'stock' => $stock, // Use the stock passed from color_stock array
            'images' => $variantImages,
            'is_default' => $isDefault,
            'is_active' => true,
        ];

        \Log::info('Creating variant with data', $variantData);

        $variant = \App\Models\ProductVariant::create($variantData);

        \Log::info('Variant created successfully', ['variant_id' => $variant->id]);

        return $variant;
    }
}
