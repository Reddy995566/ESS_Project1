<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Color;
use App\Models\Size;
use App\Models\Tag;
use App\Models\Brand;
use App\Models\Fabric;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Services\ImageKitService;

class ProductsController extends Controller
{
    use LogsActivity;
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $filterStatus = $request->get('filter_status', '');
        $filterCategory = $request->get('filter_category', '');
        $sortField = $request->get('sort_field', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        $perPage = $request->get('per_page', 10);

        $query = Product::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('sku', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhere('washing_instructions', 'like', '%' . $search . '%')
                    ->orWhere('shipping_information', 'like', '%' . $search . '%')
                    ->orWhere('returns_refunds', 'like', '%' . $search . '%');
            });
        }

        if ($filterStatus) {
            $query->where('status', $filterStatus);
        }

        if ($filterCategory) {
            $query->where('category_id', $filterCategory);
        }

        $query->orderBy($sortField, $sortDirection);
        $products = $query->with('category')->paginate($perPage);

        $categories = Category::where('is_active', true)->orderBy('name')->get();

        $totalProducts = Product::count();
        $activeProducts = Product::where('status', 'active')->count();
        $featuredProducts = Product::where('is_featured', true)->count();
        $outOfStockProducts = Product::where('stock_status', 'out_of_stock')->count();

        return view('admin.products.index', [
            'products' => $products,
            'categories' => $categories,
            'totalProducts' => $totalProducts,
            'activeProducts' => $activeProducts,
            'featuredProducts' => $featuredProducts,
            'outOfStockProducts' => $outOfStockProducts,
            'search' => $search,
            'filterStatus' => $filterStatus,
            'filterCategory' => $filterCategory,
            'sortField' => $sortField,
            'sortDirection' => $sortDirection,
            'perPage' => $perPage,
        ]);
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $collections = Collection::where('is_active', true)->orderBy('name')->get();
        $colors = Color::where('is_active', true)->orderBy('name')->get();
        $sizes = Size::where('is_active', true)->orderBy('name')->get();
        $brands = Brand::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();
        $tags = Tag::where('is_active', true)
            ->orderBy('usage_count', 'desc')
            ->orderBy('name')
            ->get();

        return view('admin.products.create', compact('categories', 'collections', 'colors', 'sizes', 'brands', 'tags'));
    }

    public function store(Request $request, ImageKitService $imageKit)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug',
            'sku' => 'nullable|string|max:100|unique:products,sku',
            'description' => 'required|string',
            'washing_instructions' => 'nullable|string',
            'shipping_information' => 'nullable|string',
            'returns_refunds' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'stock_status' => 'required|in:in_stock,out_of_stock',
            'brand_id' => 'nullable|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'collections' => 'nullable|array',
            'collections.*' => 'exists:collections,id',
            'status' => 'required|in:active,inactive,draft',
            'visibility' => 'required|in:visible,hidden,catalog,search',
            'main_image' => 'nullable|string',
            'additional_images' => 'nullable|string',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'focus_keywords' => 'nullable|string',
            'canonical_url' => 'nullable|url',
            'og_title' => 'nullable|string|max:60',
            'og_description' => 'nullable|string|max:160',
            'is_featured' => 'nullable|boolean',
            'is_new' => 'nullable|boolean',
            'is_trending' => 'nullable|boolean',
            'is_bestseller' => 'nullable|boolean',
            'is_topsale' => 'nullable|boolean',
            'is_sale' => 'nullable|boolean',
            'show_in_homepage' => 'nullable|boolean',
            'is_exclusive' => 'nullable|boolean',
            'is_limited_edition' => 'nullable|boolean',

            'has_variants' => 'nullable|boolean',
            'variant_colors' => 'nullable|string',
            'variant_sizes' => 'nullable|string',
            'variant_images' => 'nullable|string',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        if (empty($validated['sku'])) {
            $validated['sku'] = 'PRD-' . strtoupper(Str::random(8));
        }

        $validated['image'] = $request->input('main_image');

        $additionalImages = $request->input('additional_images', []);
        if (is_string($additionalImages)) {
            $additionalImages = json_decode($additionalImages, true) ?? [];
        }
        $validated['images'] = $additionalImages;

        $validated['tags'] = $request->input('tags', []);

        $validated['collections'] = $request->input('collections', []);

        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_new'] = $request->has('is_new');
        $validated['is_trending'] = $request->has('is_trending');
        $validated['is_bestseller'] = $request->has('is_bestseller');
        $validated['is_topsale'] = $request->has('is_topsale');
        $validated['is_sale'] = $request->has('is_sale');
        $validated['show_in_homepage'] = $request->has('show_in_homepage');
        $validated['is_exclusive'] = $request->has('is_exclusive');
        $validated['is_limited_edition'] = $request->has('is_limited_edition');

        try {
            $product = Product::create($validated);

            self::logActivity('created', "Created new product: {$product->name}", $product);

            if ($request->input('has_variants') === 'true') {
                $this->createProductVariants($product, $request);
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product created successfully!',
                    'product' => $product
                ]);
            }

            return redirect()->route('admin.products')->with('success', 'Product created successfully!');
        } catch (\Exception $e) {
            \Log::error('Product creation failed', ['error' => $e->getMessage()]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create product: ' . $e->getMessage()
                ], 500);
            }

            return back()->withInput()->withErrors(['error' => 'Failed to create product: ' . $e->getMessage()]);
        }
    }

    public function show(Product $product)
    {
        if (request()->expectsJson()) {
            try {

                try {
                    $product->load(['category', 'brand', 'tags', 'collections', 'colors', 'sizes', 'variants.color', 'variants.size']);

                    \Log::info('Product show debug', [
                        'product_id' => $product->id,
                        'category_id' => $product->category_id,
                        'category_loaded' => $product->relationLoaded('category'),
                        'category_exists' => $product->category ? 'yes' : 'no',
                        'category_is_object' => is_object($product->category) ? 'yes' : 'no'
                    ]);
                } catch (\Exception $e) {
                    \Log::warning('Failed to load product relationships: ' . $e->getMessage());
                }

                return response()->json([
                    'success' => true,
                    'product' => [
                        'id' => $product->id,
                        'name' => $product->name,
                        'slug' => $product->slug,
                        'sku' => $product->sku,
                        'description' => $product->description,
                        'washing_instructions' => $product->washing_instructions,
                        'shipping_information' => $product->shipping_information,
                        'returns_refunds' => $product->returns_refunds,
                        'additional_information' => null, // Deprecated
                        'price' => $product->price,
                        'sale_price' => $product->sale_price,
                        'cost_price' => $product->cost_price ?? null,
                        'stock' => $product->stock,
                        'stock_status' => $product->stock_status,
                        'status' => $product->status,
                        'visibility' => $product->visibility ?? 'visible',
                        'is_featured' => (bool) $product->is_featured,
                        'is_new' => (bool) $product->is_new,
                        'is_sale' => (bool) $product->is_sale,
                        'is_trending' => (bool) $product->is_trending,
                        'is_bestseller' => (bool) $product->is_bestseller,
                        'is_topsale' => (bool) $product->is_topsale,
                        'is_exclusive' => (bool) $product->is_exclusive,
                        'is_limited_edition' => (bool) $product->is_limited_edition,
                        'show_in_homepage' => (bool) $product->show_in_homepage,

                        'category' => $product->category ? [
                            'id' => $product->category->id,
                            'name' => $product->category->name,
                            'slug' => $product->category->slug ?? null
                        ] : null,

                        'brand' => $product->brand ? [
                            'id' => $product->brand->id,
                            'name' => $product->brand->name,
                            'slug' => $product->brand->slug ?? null
                        ] : null,

                        'image' => $product->image_url,
                        'images' => $this->getProductImages($product),

                        'tags' => $this->processProductField($product, 'tags'),

                        'collections' => $this->processProductField($product, 'collections'),

                        'colors' => $this->processProductField($product, 'colors'),

                        'sizes' => $this->processProductField($product, 'sizes'),

                        'variants' => $product->variants->map(function ($variant) {
                            return [
                                'id' => $variant->id,
                                'sku' => $variant->sku,
                                'color' => $variant->color ? [
                                    'id' => $variant->color->id,
                                    'name' => $variant->color->name,
                                    'hex_code' => $variant->color->hex_code ?? $variant->color->color_code ?? '#ccc'
                                ] : null,
                                'size' => $variant->size ? [
                                    'id' => $variant->size->id,
                                    'name' => $variant->size->name
                                ] : null,
                                'price' => $variant->price,
                                'stock' => $variant->stock,
                                'images' => $variant->images ?? [],
                                'is_default' => $variant->is_default,
                                'is_active' => $variant->is_active
                            ];
                        }),

                        'meta_title' => $product->meta_title,
                        'meta_description' => $product->meta_description,
                        'focus_keywords' => $product->focus_keywords,
                        'canonical_url' => $product->canonical_url,
                        'og_title' => $product->og_title,
                        'og_description' => $product->og_description,

                        'created_at' => $product->created_at ? $product->created_at->format('M d, Y H:i A') : null,
                        'updated_at' => $product->updated_at ? $product->updated_at->format('M d, Y H:i A') : null,
                    ]
                ]);
            } catch (\Exception $e) {
                \Log::error('Error in product show method', [
                    'product_id' => $product->id,
                    'error' => $e->getMessage(),
                    'line' => $e->getLine(),
                    'file' => $e->getFile()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Error loading product: ' . $e->getMessage()
                ], 500);
            }
        }

        return view('admin.products.show', compact('product'));
    }

    public function toggle(Request $request, Product $product)
    {
        try {
            $field = $request->input('field');
            $value = $request->input('value');

            if (!in_array($field, ['status', 'is_featured', 'stock_status'])) {
                return response()->json(['success' => false, 'message' => 'Invalid field: ' . $field], 400);
            }

            if ($field === 'status') {
                if (!in_array($value, ['active', 'inactive', 'draft'])) {
                    return response()->json(['success' => false, 'message' => 'Invalid status value'], 400);
                }
                $product->update([$field => $value]);
            } elseif ($field === 'is_featured') {
                $boolValue = filter_var($value, FILTER_VALIDATE_BOOLEAN);
                $product->update([$field => $boolValue]);
            } elseif ($field === 'stock_status') {
                if (!in_array($value, ['in_stock', 'out_of_stock'])) {
                    return response()->json(['success' => false, 'message' => 'Invalid stock status value'], 400);
                }
                $product->update([$field => $value]);
            }

            return response()->json(['success' => true, 'message' => 'Product updated successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update product'], 500);
        }
    }

    public function bulkAction(Request $request)
    {
        try {
            $action = $request->input('action');
            $ids = $request->input('ids', []);

            if (empty($ids)) {
                return response()->json(['success' => false, 'message' => 'No products selected'], 400);
            }

            $count = 0;
            switch ($action) {
                case 'activate':
                    $count = Product::whereIn('id', $ids)->update(['status' => 'active']);
                    $message = "$count product(s) activated!";
                    break;
                case 'deactivate':
                    $count = Product::whereIn('id', $ids)->update(['status' => 'inactive']);
                    $message = "$count product(s) deactivated!";
                    break;
                case 'feature':
                    $count = Product::whereIn('id', $ids)->update(['is_featured' => true]);
                    $message = "$count product(s) marked as featured!";
                    break;
                case 'unfeature':
                    $count = Product::whereIn('id', $ids)->update(['is_featured' => false]);
                    $message = "$count product(s) removed from featured!";
                    break;
                case 'in_stock':
                    $count = Product::whereIn('id', $ids)->update(['stock_status' => 'in_stock']);
                    $message = "$count product(s) marked as in stock!";
                    break;
                case 'out_of_stock':
                    $count = Product::whereIn('id', $ids)->update(['stock_status' => 'out_of_stock']);
                    $message = "$count product(s) marked as out of stock!";
                    break;
                case 'delete':
                    $count = Product::whereIn('id', $ids)->delete();
                    $message = "$count product(s) deleted!";
                    break;
                default:
                    return response()->json(['success' => false, 'message' => 'Invalid action'], 400);
            }

            return response()->json(['success' => true, 'message' => $message]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to perform bulk action'], 500);
        }
    }

    public function export(Request $request)
    {
        $query = Product::query();

        $search = $request->get('search', '');
        $filterStatus = $request->get('filter_status', '');
        $filterCategory = $request->get('filter_category', '');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('sku', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        if ($filterStatus) {
            $query->where('status', $filterStatus);
        }

        if ($filterCategory) {
            $query->where('category_id', $filterCategory);
        }

        $products = $query->with('category')->orderBy('created_at', 'desc')->get();
        $filename = 'products_' . date('Y-m-d_His') . '.csv';
        $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => "attachment; filename=\"$filename\""];

        $callback = function () use ($products) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Name', 'SKU', 'Category', 'Price', 'Sale Price', 'Stock', 'Stock Status', 'Status', 'Featured', 'Created At']);
            foreach ($products as $product) {
                fputcsv($file, [
                    $product->id,
                    $product->name,
                    $product->sku ?? 'N/A',
                    $product->category ? $product->category->name : 'Uncategorized',
                    $product->price ?? 0,
                    $product->sale_price ?? 0,
                    $product->stock ?? 0,
                    $product->stock_status ?? 'in_stock',
                    $product->status,
                    $product->is_featured ? 'Yes' : 'No',
                    $product->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function update(Request $request, Product $product)
    {
        try {
            $validated = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'slug' => 'sometimes|required|string|max:255|unique:products,slug,' . $product->id,
                'sku' => 'sometimes|nullable|string|max:100',
                'description' => 'sometimes|required|string',
                'washing_instructions' => 'sometimes|nullable|string',
                'shipping_information' => 'sometimes|nullable|string',
                'returns_refunds' => 'sometimes|nullable|string',
                'price' => 'sometimes|required|numeric|min:0',
                'sale_price' => 'sometimes|nullable|numeric|min:0',
                'stock' => 'sometimes|required|integer|min:0',
                'stock_status' => 'sometimes|required|in:in_stock,out_of_stock',
                'brand_id' => 'sometimes|nullable|exists:brands,id',
                'category_id' => 'sometimes|required|exists:categories,id',
                'image' => 'sometimes|nullable|string',
                'images' => 'sometimes|nullable',
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
                'tags' => 'sometimes|nullable|array',
                'tags.*' => 'exists:tags,id',
                'collections' => 'sometimes|nullable|array',
                'collections.*' => 'exists:collections,id',
            ]);

            $tags = $validated['tags'] ?? null;
            $collections = $validated['collections'] ?? null;
            unset($validated['tags'], $validated['collections']);

            $oldData = $product->only(['name', 'price', 'status', 'stock']);
            $product->update($validated);

            self::logActivity('updated', "Updated product: {$product->name}", $product, $oldData, $validated);

            if ($tags !== null) {
                $product->tags()->sync($tags);
            }
            if ($collections !== null) {
                $product->collections()->sync($collections);
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product updated successfully!',
                    'product' => $product
                ]);
            }

            return redirect()->route('admin.products.edit', $product->id)->with('success', 'Product updated successfully!');
        } catch (\Exception $e) {
            \Log::error('Product update failed', ['error' => $e->getMessage(), 'product_id' => $product->id]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update product: ' . $e->getMessage()
                ], 500);
            }

            return back()->withInput()->withErrors(['error' => 'Failed to update product: ' . $e->getMessage()]);
        }
    }

    public function destroy(Product $product)
    {
        try {
            $product->delete();
            if (request()->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Product deleted successfully!']);
            }
            return redirect()->route('admin.products')->with('success', 'Product deleted successfully!');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Failed to delete product.'], 500);
            }
            return redirect()->route('admin.products')->with('error', 'Failed to delete product.');
        }
    }

    public function updateStatus(Request $request, Product $product)
    {
        $request->validate([
            'status' => 'required|string|in:active,inactive,draft'
        ]);

        $product->update(['status' => $request->status]);
        return back()->with('success', 'Product status updated!');
    }

    public function toggleFeatured(Product $product)
    {
        $product->update(['featured' => !$product->featured]);
        return back()->with('success', 'Featured status updated!');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'products' => 'required|array',
            'products.*' => 'exists:products,id'
        ]);

        Product::whereIn('id', $request->products)->delete();
        return back()->with('success', 'Selected products deleted!');
    }

    public function clearSession()
    {
        session()->forget('product_data');
        \Log::info('Product creation session cleared by user');

        return response()->json([
            'success' => true,
            'message' => 'Session cleared successfully'
        ]);
    }

    public function deleteVariantColor(Product $product, Request $request)
    {
        try {
            $request->validate([
                'color_id' => 'required|exists:colors,id'
            ]);

            $colorId = $request->color_id;

            $count = $product->variants()->where('color_id', $colorId)->delete();

            // Also detach the color from the product_color pivot table
            $product->colors()->detach($colorId);

            self::logActivity('deleted', "Deleted color variant (ID: $colorId) from product: {$product->name}", $product);

            return response()->json([
                'success' => true,
                'message' => 'Color variants deleted successfully from database!',
                'deleted_count' => $count
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete variant: ' . $e->getMessage()
            ], 500);
        }
    }

    public function createStep1()
    {
        $productData = session('product_data', []);
        return view('admin.products.create.step1', compact('productData'));
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

            \Log::info('Step 1 - washing_instructions received', [
                'has_field' => isset($validated['washing_instructions']),
                'value' => $validated['washing_instructions'] ?? 'NOT SET',
                'length' => isset($validated['washing_instructions']) ? strlen($validated['washing_instructions']) : 0
            ]);

            $productData = array_merge(session('product_data', []), $validated);
            session(['product_data' => $productData]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Step 1 completed successfully!',
                    'next_step_url' => route('admin.products.create.step2'),
                    'data' => $validated
                ]);
            }

            return redirect()->route('admin.products.create.step2');

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

    public function createStep2()
    {
        $productData = session('product_data', []);
        if (empty($productData)) {
            return redirect()->route('admin.products.create.step1')->with('error', 'Please complete Step 1 first.');
        }
        return view('admin.products.create.step2', compact('productData'));
    }

    public function processStep2(Request $request)
    {
        try {
            $validated = $request->validate([
                'main_image' => 'nullable|string',
                'additional_images' => 'nullable|string',
            ]);

            $productData = array_merge(session('product_data', []), $validated);
            session(['product_data' => $productData]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Step 2 completed successfully!',
                    'next_step_url' => route('admin.products.create.step3'),
                    'data' => $validated
                ]);
            }

            return redirect()->route('admin.products.create.step3');

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

    public function createStep3()
    {
        $productData = session('product_data', []);
        if (empty($productData)) {
            return redirect()->route('admin.products.create.step1')->with('error', 'Please complete previous steps first.');
        }

        // Fetch categories with hierarchy for the dropdown
        $categories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->with([
                'children' => function ($query) {
                    $query->where('is_active', true)->with('children')->orderBy('name');
                }
            ])
            ->orderBy('name')
            ->get();

        $allCategories = Category::where('is_active', true)->orderBy('name')->get();

        $collections = Collection::where('is_active', true)->orderBy('name')->get();
        $brands = Brand::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();
        $tags = Tag::where('is_active', true)->orderBy('usage_count', 'desc')->orderBy('name')->get();
        $fabrics = Fabric::where('is_active', true)->orderBy('name')->get();

        return view('admin.products.create.step3', compact('productData', 'categories', 'allCategories', 'collections', 'brands', 'tags', 'fabrics'));
    }

    public function processStep3(Request $request)
    {
        try {
            $validated = $request->validate([
                'brand_id' => 'nullable|exists:brands,id',
                'category_id' => 'required|exists:categories,id',
                'fabric_id' => 'nullable|exists:fabrics,id',
                'sku' => 'nullable|string|max:100',
                'tags' => 'nullable|array',
                'tags.*' => 'exists:tags,id',
                'collections' => 'nullable|array',
                'collections.*' => 'exists:collections,id',
            ]);

            if (!array_key_exists('tags', $validated)) {
                $validated['tags'] = [];
            }
            if (!array_key_exists('collections', $validated)) {
                $validated['collections'] = [];
            }

            if (empty($validated['sku'])) {
                $validated['sku'] = 'PRD-' . strtoupper(Str::random(8));
            }

            $baseSku = $validated['sku'];
            $sku = $baseSku;
            $counter = 1;

            while (Product::where('sku', $sku)->exists()) {
                $sku = $baseSku . '-' . $counter;
                $counter++;
            }

            $validated['sku'] = $sku;

            $productData = array_merge(session('product_data', []), $validated);
            session(['product_data' => $productData]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Step 3 completed successfully!',
                    'next_step_url' => route('admin.products.create.step4'),
                    'data' => $validated
                ]);
            }

            return redirect()->route('admin.products.create.step4');

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

    public function createStep4()
    {
        $productData = session('product_data', []);
        if (empty($productData)) {
            return redirect()->route('admin.products.create.step1')->with('error', 'Please complete previous steps first.');
        }
        return view('admin.products.create.step4', compact('productData'));
    }

    public function processStep4(Request $request)
    {
        try {
            $validated = $request->validate([
                'price' => 'required|numeric|min:0',
                'sale_price' => 'nullable|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'stock_status' => 'required|in:in_stock,out_of_stock',
            ]);

            $productData = array_merge(session('product_data', []), $validated);
            session(['product_data' => $productData]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Step 4 completed successfully!',
                    'next_step_url' => route('admin.products.create.step5'),
                    'data' => $validated
                ]);
            }

            return redirect()->route('admin.products.create.step5');

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

    public function createStep5()
    {
        $productData = session('product_data', []);
        if (empty($productData)) {
            return redirect()->route('admin.products.create.step1')->with('error', 'Please complete previous steps first.');
        }

        $colors = Color::where('is_active', true)->orderBy('name')->get();
        $sizes = Size::where('is_active', true)->orderBy('name')->get();

        return view('admin.products.create.step5', compact('productData', 'colors', 'sizes'));
    }

    public function processStep5(Request $request)
    {
        try {
            $validated = $request->validate([
                'has_variants' => 'nullable|boolean',
                'variant_colors' => 'nullable|string',
                'variant_sizes' => 'nullable|string',
                'variant_images' => 'nullable|string',
            ]);

            $productData = array_merge(session('product_data', []), $validated);
            session(['product_data' => $productData]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Step 5 completed successfully!',
                    'next_step_url' => route('admin.products.create.step6'),
                    'data' => $validated
                ]);
            }

            return redirect()->route('admin.products.create.step6');

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

    public function createStep6()
    {
        $productData = session('product_data', []);
        if (empty($productData)) {
            return redirect()->route('admin.products.create.step1')->with('error', 'Please complete previous steps first.');
        }
        return view('admin.products.create.step6', compact('productData'));
    }

    public function processStep6(Request $request)
    {
        try {
            $validated = $request->validate([
                'meta_title' => 'nullable|string|max:60',
                'meta_description' => 'nullable|string|max:160',
                'focus_keywords' => 'nullable|string',
                'canonical_url' => 'nullable|url',
                'og_title' => 'nullable|string|max:60',
                'og_description' => 'nullable|string|max:160',
            ]);

            $productData = array_merge(session('product_data', []), $validated);
            session(['product_data' => $productData]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Step 6 completed successfully!',
                    'next_step_url' => route('admin.products.create.step7'),
                    'data' => $validated
                ]);
            }

            return redirect()->route('admin.products.create.step7');

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

    public function createStep7()
    {
        $productData = session('product_data', []);
        if (empty($productData)) {
            return redirect()->route('admin.products.create.step1')->with('error', 'Please complete previous steps first.');
        }
        return view('admin.products.create.step7', compact('productData'));
    }

    public function processStep7(Request $request)
    {
        try {
            $validated = $request->validate([
                'status' => 'required|in:active,inactive,draft',
                'visibility' => 'required|in:visible,hidden,catalog,search',
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

            $productData = array_merge(session('product_data', []), $validated);

            DB::beginTransaction();

            try {
                $productData['is_featured'] = $request->has('is_featured');
                $productData['is_new'] = $request->has('is_new');
                $productData['is_trending'] = $request->has('is_trending');
                $productData['is_bestseller'] = $request->has('is_bestseller');
                $productData['is_topsale'] = $request->has('is_topsale');
                $productData['is_sale'] = $request->has('is_sale');
                $productData['show_in_homepage'] = $request->has('show_in_homepage');
                $productData['is_exclusive'] = $request->has('is_exclusive');
                $productData['is_limited_edition'] = $request->has('is_limited_edition');

                $productData['image'] = $productData['main_image'] ?? null;

                $additionalImages = $productData['additional_images'] ?? '[]';
                if (is_string($additionalImages)) {
                    $additionalImages = json_decode($additionalImages, true) ?? [];
                }
                $productData['images'] = $additionalImages;

                if (!isset($productData['category_id'])) {
                    $productData['category_id'] = null;
                }

                $productData['stock_status'] = $productData['stock_status'] ?? 'in_stock';
                $productData['stock'] = $productData['stock'] ?? 0;
                $productData['price'] = $productData['price'] ?? 0;

                foreach (['additional_information', 'short_description'] as $field) {
                    if (isset($productData[$field])) {
                        $cleanContent = trim(strip_tags($productData[$field]));
                        if (empty($cleanContent)) {
                            $productData[$field] = null;
                        }
                    }
                }

                $hasVariants = $productData['has_variants'] ?? false;
                $variantColors = $productData['variant_colors'] ?? '[]';
                $variantSizes = $productData['variant_sizes'] ?? '[]';
                $variantImages = $productData['variant_images'] ?? '{}';

                $tags = $productData['tags'] ?? [];
                $collections = $productData['collections'] ?? [];

                // Ensure we handle empty arrays if the keys exist but are explicitly null or empty, 
                // though previously we defaulted them. Key issue is ensuring we respect the empty state.

                unset($productData['main_image']);
                unset($productData['additional_images']);
                unset($productData['has_variants']);
                unset($productData['variant_colors']);
                unset($productData['variant_sizes']);
                unset($productData['variant_images']);
                unset($productData['tags']);
                unset($productData['collections']);

                // Ensure SKU Uniqueness
                $baseSku = $productData['sku'];
                $sku = $baseSku;
                $counter = 1;
                while (Product::where('sku', $sku)->exists()) {
                    $sku = $baseSku . '-' . $counter;
                    $counter++;
                }
                $productData['sku'] = $sku;

                // Ensure Slug Uniqueness
                $baseSlug = $productData['slug'] ?? Str::slug($productData['name']);
                $slug = $baseSlug;
                $counter = 1;
                while (Product::where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $counter;
                    $counter++;
                }
                $productData['slug'] = $slug;

                $product = Product::create($productData);

                if (!empty($tags)) {
                    $product->tags()->sync($tags);
                }

                if (!empty($collections)) {
                    $product->collections()->sync($collections);
                }

                if ($hasVariants) {
                    $variantData = [
                        'variant_colors' => $variantColors,
                        'variant_sizes' => $variantSizes,
                        'variant_images' => $variantImages
                    ];
                    $this->createProductVariants($product, $variantData);

                    $colorIds = json_decode($variantColors, true) ?? [];
                    $sizeIds = json_decode($variantSizes, true) ?? [];

                    if (!empty($colorIds)) {
                        $product->colors()->sync($colorIds);
                    }

                    if (!empty($sizeIds)) {
                        $product->sizes()->sync($sizeIds);
                    }
                }

                DB::commit();

                session()->forget('product_data');

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Product created successfully!',
                        'redirect_url' => route('admin.products'),
                        'product_id' => $product->id
                    ]);
                }

                return redirect()->route('admin.products')->with('success', 'Product created successfully!');

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            $errorMessage = 'Failed to create product: ' . $e->getMessage();
            if (config('app.debug')) {
                $errorMessage .= ' (Line: ' . $e->getLine() . ')';
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 500);
            }

            return back()->withInput()->withErrors(['error' => $errorMessage]);
        }


    }

    private function createProductVariants(Product $product, $productData)
    {
        $colors = json_decode($productData['variant_colors'] ?? '[]', true) ?? [];
        $sizes = json_decode($productData['variant_sizes'] ?? '[]', true) ?? [];
        $colorImages = json_decode($productData['variant_images'] ?? '{}', true) ?? [];

        $hasColors = !empty($colors);
        $hasSizes = !empty($sizes);
        $isFirstVariant = true;

        if ($hasColors && $hasSizes) {

            foreach ($colors as $colorId) {
                foreach ($sizes as $sizeId) {
                    $this->createVariant($product, $colorId, $sizeId, $colorImages, $isFirstVariant);
                    $isFirstVariant = false;
                }
            }
        } elseif ($hasColors) {

            foreach ($colors as $colorId) {
                $this->createVariant($product, $colorId, null, $colorImages, $isFirstVariant);
                $isFirstVariant = false;
            }
        } elseif ($hasSizes) {

            foreach ($sizes as $sizeId) {
                $this->createVariant($product, null, $sizeId, $colorImages, $isFirstVariant);
                $isFirstVariant = false;
            }
        }
    }

    private function createVariant(Product $product, $colorId, $sizeId, $colorImages, $isDefault)
    {

        $sku = $product->sku;
        if ($colorId) {
            $color = Color::find($colorId);
            $sku .= '-' . strtoupper(substr($color->name ?? 'COL', 0, 2));
        }
        if ($sizeId) {
            $size = Size::find($sizeId);
            $sku .= '-' . ($size->abbreviation ?? strtoupper(substr($size->name ?? 'SIZ', 0, 2)));
        }
        $sku .= '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);

        $variantImages = [];
        if ($colorId && isset($colorImages[$colorId])) {
            $variantImages = array_column($colorImages[$colorId], 'url');
        }

        ProductVariant::create([
            'product_id' => $product->id,
            'sku' => $sku,
            'color_id' => $colorId,
            'size_id' => $sizeId,
            'price' => $product->price, // Default to product price
            'stock' => $product->stock, // Default to product stock
            'images' => $variantImages,
            'is_default' => $isDefault,
            'is_active' => true,
        ]);
    }

    private function getProductImages($product)
    {
        // First check if product has images from step 2
        if (!empty($product->images_array) && is_array($product->images_array)) {
            return $product->images_array;
        }

        // Fallback: Get images from product variants
        $variantImages = [];
        try {
            $variants = $product->variants()->get();

            foreach ($variants as $variant) {
                if (!empty($variant->images) && is_array($variant->images)) {
                    foreach ($variant->images as $image) {
                        if (is_string($image) && !empty($image)) {
                            $variantImages[] = $image;
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::warning('Failed to fetch variant images: ' . $e->getMessage());
        }

        return array_values(array_unique($variantImages));
    }

    private function processProductField($product, $fieldName)
    {
        try {

            if ($product->relationLoaded($fieldName)) {
                $relationData = $product->$fieldName;

                if (is_object($relationData) && method_exists($relationData, 'map')) {
                    return $relationData->map(function ($item) use ($fieldName) {
                        return $this->mapFieldItem($item, $fieldName);
                    })->toArray();
                }
            }

            $fieldValue = $product->$fieldName;

            if (is_array($fieldValue)) {

                return collect($fieldValue)->map(function ($item) use ($fieldName) {
                    return $this->mapFieldItem($item, $fieldName);
                })->toArray();
            }

            if (is_string($fieldValue)) {
                $decoded = json_decode($fieldValue, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    return collect($decoded)->map(function ($item) use ($fieldName) {
                        return $this->mapFieldItem($item, $fieldName);
                    })->toArray();
                }
            }

            return [];
        } catch (\Exception $e) {
            \Log::warning("Failed to process product field {$fieldName}: " . $e->getMessage());
            return [];
        }
    }

    private function mapFieldItem($item, $fieldName)
    {
        if (is_object($item)) {

            $mapped = [
                'id' => $item->id ?? null,
                'name' => $item->name ?? null,
                'slug' => $item->slug ?? null
            ];

            if ($fieldName === 'colors') {
                $mapped['hex_code'] = $item->hex_code ?? $item->color_code ?? $item->color ?? null;
                $mapped['description'] = $item->description ?? null;
            } elseif ($fieldName === 'sizes') {
                $mapped['size'] = $item->size ?? $item->name ?? null;
            }

            return $mapped;
        } elseif (is_array($item)) {

            $mapped = [
                'id' => $item['id'] ?? null,
                'name' => $item['name'] ?? $item,
                'slug' => $item['slug'] ?? null
            ];

            if ($fieldName === 'colors') {
                $mapped['hex_code'] = $item['hex_code'] ?? $item['color_code'] ?? $item['color'] ?? null;
                $mapped['description'] = $item['description'] ?? null;
            } elseif ($fieldName === 'sizes') {
                $mapped['size'] = $item['size'] ?? $item['name'] ?? $item;
            }

            return $mapped;
        } else {

            $mapped = [
                'id' => null,
                'name' => $item,
                'slug' => null
            ];

            if ($fieldName === 'sizes') {
                $mapped['size'] = $item;
            }

            return $mapped;
        }
    }

    public function editStep1(Product $product)
    {

        session()->forget('edit_product_data');

        session([
            'edit_product_data' => [
                'product_id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'short_description' => $product->short_description,
                'description' => $product->description,
                'additional_information' => $product->additional_information,
            ]
        ]);

        $productData = session('edit_product_data', []);

        return view('admin.products.edit.step1', compact('product', 'productData'));
    }

    public function processEditStep1(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug,' . $product->id,
            'short_description' => 'nullable|string',
            'description' => 'required|string',
            'additional_information' => 'nullable|string',
        ]);

        if (isset($validated['additional_information'])) {
            $cleanContent = trim(strip_tags($validated['additional_information'], '<p><br>'));
            if ($cleanContent === '' || $cleanContent === '<p><br></p>' || $cleanContent === '<p></p>') {
                $validated['additional_information'] = null;
            }
        }

        $product->update($validated);

        $editData = session('edit_product_data', []);
        $editData = array_merge($editData, $validated);
        session(['edit_product_data' => $editData]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully!',
                'redirect_url' => route('admin.products.edit.step1', $product->id)
            ]);
        }

        return redirect()->route('admin.products.edit.step1', $product->id)
            ->with('success', 'Product updated successfully!');
    }

    public function editStep2(Product $product)
    {
        $editData = session('edit_product_data', []);

        $editData['image'] = $product->image;
        $editData['images'] = $product->images;
        session(['edit_product_data' => $editData]);

        $productData = $editData;

        return view('admin.products.edit.step2', compact('product', 'productData'));
    }

    public function processEditStep2(Request $request, Product $product)
    {
        $validated = $request->validate([
            'main_image' => 'nullable|string',
            'additional_images' => 'nullable|string',
        ]);

        $additionalImages = $request->input('additional_images', '[]');
        if (is_string($additionalImages)) {
            $additionalImages = json_decode($additionalImages, true) ?? [];
        }
        $validated['images'] = $additionalImages;
        $validated['image'] = $request->input('main_image');

        $product->update($validated);

        $editData = session('edit_product_data', []);
        $editData = array_merge($editData, $validated);
        session(['edit_product_data' => $editData]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Product images updated successfully!',
                'redirect_url' => route('admin.products.edit.step2', $product->id)
            ]);
        }

        return redirect()->route('admin.products.edit.step2', $product->id)
            ->with('success', 'Product images updated successfully!');
    }

    public function editStep3(Product $product)
    {
        $editData = session('edit_product_data', []);

        $editData = array_merge($editData, [
            'price' => $product->price,
            'sale_price' => $product->sale_price,
            'cost_price' => $product->cost_price,
            'stock' => $product->stock,
            'stock_status' => $product->stock_status,
            'sku' => $product->sku,
            'track_inventory' => $product->track_inventory ?? 1,
        ]);
        session(['edit_product_data' => $editData]);

        $productData = $editData;

        return view('admin.products.edit.step3', compact('product', 'productData'));
    }

    public function processEditStep3(Request $request, Product $product)
    {
        $validated = $request->validate([
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'stock_status' => 'required|in:in_stock,out_of_stock',
            'track_inventory' => 'nullable|boolean',
        ]);

        $product->update($validated);

        $editData = session('edit_product_data', []);
        $editData = array_merge($editData, $validated);
        session(['edit_product_data' => $editData]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pricing & Inventory updated successfully!',
                'redirect_url' => route('admin.products.edit.step3', $product->id)
            ]);
        }

        return redirect()->route('admin.products.edit.step3', $product->id)
            ->with('success', 'Pricing & Inventory updated successfully!');
    }

    public function editStep4(Product $product)
    {
        $editData = session('edit_product_data', []);

        $categories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->with([
                'children' => function ($query) {
                    $query->where('is_active', true)->with('children')->orderBy('name');
                }
            ])
            ->orderBy('name')
            ->get();

        $allCategories = Category::where('is_active', true)->orderBy('name')->get();

        $brands = Brand::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();
        $collections = Collection::where('is_active', true)->orderBy('name')->get();
        $tags = Tag::where('is_active', true)->orderBy('usage_count', 'desc')->orderBy('name')->get();
        $fabrics = Fabric::where('is_active', true)->orderBy('name')->get();

        $product->load(['category', 'brand', 'collections', 'tags', 'fabric']);

        $collectionsData = [];
        $tagsData = [];

        // Extract collection IDs - use getRelation to bypass column collision
        if ($product->relationLoaded('collections')) {
            $loadedCollections = $product->getRelation('collections');
            if ($loadedCollections && $loadedCollections->isNotEmpty()) {
                $collectionsData = $loadedCollections->pluck('id')->toArray();
                \Log::info('Collections loaded via relation for product ' . $product->id, ['collections' => $collectionsData]);
            } else {
                \Log::info('No collections found via relation for product ' . $product->id);
            }
        }

        // Fallback to legacy column data checks (using getAttribute to be safe)
        if (empty($collectionsData)) {
            $rawCollections = $product->getAttribute('collections');
            if ($rawCollections && is_array($rawCollections)) {
                $collectionsData = $rawCollections;
            }
        }

        // Extract tag IDs - use getRelation here too
        if ($product->relationLoaded('tags')) {
            $loadedTags = $product->getRelation('tags');
            if ($loadedTags && $loadedTags->isNotEmpty()) {
                $tagsData = $loadedTags->pluck('id')->toArray();
            }
        }

        if (empty($tagsData)) {
            $rawTags = $product->getAttribute('tags');
            if ($rawTags && is_array($rawTags)) {
                $tagsData = $rawTags;
            }
        }

        // Merge session data but ensure database data takes precedence for collections and tags
        $productData = array_merge($editData, [
            'category_id' => $product->category_id,
            'brand_id' => $product->brand_id,
            'fabric_id' => $product->fabric_id,
            'sku' => $product->sku,
            'collections' => $collectionsData,
            'tags' => $tagsData,
        ]);

        // Update session with the correct data
        session(['edit_product_data' => $productData]);

        return view('admin.products.edit.step4', compact('product', 'productData', 'categories', 'allCategories', 'brands', 'collections', 'tags', 'fabrics'));
    }


    public function processEditStep4(Request $request, Product $product)
    {
        \Log::info('Processing Edit Step 4 for product ' . $product->id, [
            'request_collections' => $request->input('collections'),
            'request_all' => $request->all()
        ]);

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

        if (!array_key_exists('collections', $validated)) {
            $validated['collections'] = [];
        }
        if (!array_key_exists('tags', $validated)) {
            $validated['tags'] = [];
        }

        $collections = $validated['collections'] ?? [];
        $tags = $validated['tags'] ?? [];

        \Log::info('Collections to sync:', ['collections' => $collections]);

        unset($validated['collections'], $validated['tags']);

        $product->update($validated);

        $product->collections()->sync($collections);
        $product->tags()->sync($tags);

        // Verify the sync worked
        $product->load('collections');
        // Use getRelation to avoid collision with 'collections' column
        $syncedCollections = $product->getRelation('collections');

        \Log::info('Collections after sync:', [
            'count' => $syncedCollections ? $syncedCollections->count() : 0,
            'ids' => $syncedCollections ? $syncedCollections->pluck('id')->toArray() : []
        ]);

        $editData = session('edit_product_data', []);
        $editData = array_merge($editData, $validated);
        $editData['collections'] = $collections;
        $editData['tags'] = $tags;
        session(['edit_product_data' => $editData]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Category & Brand updated successfully!',
                'redirect_url' => route('admin.products.edit.step4', $product->id)
            ]);
        }

        return redirect()->route('admin.products.edit.step4', $product->id)
            ->with('success', 'Category & Brand updated successfully!');
    }

    public function editStep5(Product $product)
    {
        $editData = session('edit_product_data', []);

        $colors = Color::where('is_active', true)->orderBy('name')->get();
        $sizes = Size::where('is_active', true)->orderBy('name')->get();

        $product->load(['variants']);

        $variantColorIds = $product->variants->pluck('color_id')->filter()->unique()->values()->toArray();
        $variantSizeIds = $product->variants->pluck('size_id')->filter()->unique()->values()->toArray();

        $variantImages = [];
        foreach ($product->variants as $variant) {
            if ($variant->color_id && $variant->images) {
                $images = is_string($variant->images) ? json_decode($variant->images, true) : $variant->images;
                if (is_array($images) && !empty($images)) {
                    if (!isset($variantImages[$variant->color_id])) {
                        $variantImages[$variant->color_id] = [];
                    }
                    foreach ($images as $imageUrl) {
                        $variantImages[$variant->color_id][] = [
                            'url' => $imageUrl,
                            'fileId' => null,
                            'size' => 0,
                            'width' => 360,
                            'height' => 459
                        ];
                    }
                }
            }
        }

        $editData = array_merge($editData, [
            'has_variants' => $product->variants()->exists(),
            'variant_colors' => json_encode($variantColorIds),
            'variant_sizes' => json_encode($variantSizeIds),
            'variant_images' => json_encode($variantImages),
        ]);
        session(['edit_product_data' => $editData]);

        $productData = $editData;

        return view('admin.products.edit.step5', compact('product', 'productData', 'colors', 'sizes'));
    }

    public function processEditStep5(Request $request, Product $product)
    {
        $validated = $request->validate([
            'has_variants' => 'nullable|boolean',
            'variant_colors' => 'nullable|string',
            'variant_sizes' => 'nullable|string',
            'variant_images' => 'nullable|string',
        ]);

        $hasVariants = $validated['has_variants'] ?? false;
        $variantColors = $validated['variant_colors'] ?? '[]';
        $variantSizes = $validated['variant_sizes'] ?? '[]';
        $variantImages = $validated['variant_images'] ?? '{}';

        if ($hasVariants) {
            $product->variants()->delete();

            $variantData = [
                'variant_colors' => $variantColors,
                'variant_sizes' => $variantSizes,
                'variant_images' => $variantImages
            ];
            $this->createProductVariants($product, $variantData);

            $colorIds = json_decode($variantColors, true) ?? [];
            $sizeIds = json_decode($variantSizes, true) ?? [];

            if (!empty($colorIds)) {
                $product->colors()->sync($colorIds);
            }

            if (!empty($sizeIds)) {
                $product->sizes()->sync($sizeIds);
            }
        }

        $editData = session('edit_product_data', []);
        $editData = array_merge($editData, $validated);
        session(['edit_product_data' => $editData]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Product Variants updated successfully!',
                'redirect_url' => route('admin.products.edit.step5', $product->id)
            ]);
        }

        return redirect()->route('admin.products.edit.step5', $product->id)
            ->with('success', 'Product Variants updated successfully!');
    }

    public function editStep6(Product $product)
    {
        $editData = session('edit_product_data', []);

        $editData = array_merge($editData, [
            'meta_title' => $product->meta_title,
            'meta_description' => $product->meta_description,
            'focus_keywords' => $product->focus_keywords,
            'canonical_url' => $product->canonical_url,
            'og_title' => $product->og_title,
            'og_description' => $product->og_description,
        ]);
        session(['edit_product_data' => $editData]);

        $productData = $editData;

        return view('admin.products.edit.step6', compact('product', 'productData'));
    }

    public function processEditStep6(Request $request, Product $product)
    {
        $validated = $request->validate([
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'focus_keywords' => 'nullable|string',
            'canonical_url' => 'nullable|url',
            'og_title' => 'nullable|string|max:60',
            'og_description' => 'nullable|string|max:160',
        ]);

        $product->update($validated);

        $editData = session('edit_product_data', []);
        $editData = array_merge($editData, $validated);
        session(['edit_product_data' => $editData]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'SEO settings updated successfully!',
                'redirect_url' => route('admin.products.edit.step6', $product->id)
            ]);
        }

        return redirect()->route('admin.products.edit.step6', $product->id)
            ->with('success', 'SEO settings updated successfully!');
    }

    public function editStep7(Product $product)
    {
        $editData = session('edit_product_data', []);

        $editData = array_merge($editData, [
            'status' => $product->status,
            'visibility' => $product->visibility,
            'is_featured' => $product->is_featured,
            'is_new' => $product->is_new,
            'is_trending' => $product->is_trending,
            'is_bestseller' => $product->is_bestseller,
            'is_topsale' => $product->is_topsale,
            'is_sale' => $product->is_sale,
            'show_in_homepage' => $product->show_in_homepage,
            'is_exclusive' => $product->is_exclusive,
            'is_limited_edition' => $product->is_limited_edition,
        ]);
        session(['edit_product_data' => $editData]);

        $productData = $editData;

        return view('admin.products.edit.step7', compact('product', 'productData'));
    }

    public function processEditStep7(Request $request, Product $product)
    {
        try {
            $validated = $request->validate([
                'status' => 'required|in:active,inactive,draft',
                'visibility' => 'required|in:visible,hidden,catalog,search',
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

            $editData = session('edit_product_data', []);
            $allData = array_merge($editData, $validated);

            $allData['is_featured'] = $request->has('is_featured');
            $allData['is_new'] = $request->has('is_new');
            $allData['is_trending'] = $request->has('is_trending');
            $allData['is_bestseller'] = $request->has('is_bestseller');
            $allData['is_topsale'] = $request->has('is_topsale');
            $allData['is_sale'] = $request->has('is_sale');
            $allData['show_in_homepage'] = $request->has('show_in_homepage');
            $allData['is_exclusive'] = $request->has('is_exclusive');
            $allData['is_limited_edition'] = $request->has('is_limited_edition');

            $product->update($allData);

            if (isset($allData['tags'])) {
                $product->tags()->sync($allData['tags'] ?? []);
            }
            if (isset($allData['collections'])) {
                $product->collections()->sync($allData['collections'] ?? []);
            }

            session()->forget('edit_product_data');

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product updated successfully!',
                    'redirect_url' => route('admin.products.edit.step7', $product->id)
                ]);
            }

            return redirect()->route('admin.products.edit.step7', $product->id)
                ->with('success', 'Product updated successfully!');

        } catch (\Exception $e) {
            \Log::error('Product update failed', ['error' => $e->getMessage(), 'product_id' => $product->id]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update product: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Failed to update product: ' . $e->getMessage());
        }
    }

    /**
     * Copy/Duplicate a product with all its related data
     */
    public function copyProduct(Product $product)
    {
        try {
            DB::beginTransaction();

            // Load all relationships
            $product->load(['variants', 'collections', 'tags', 'colors', 'sizes', 'category', 'brand']);

            // Prepare product data for duplication
            $productData = $product->toArray();

            // Remove unique identifiers
            unset($productData['id'], $productData['created_at'], $productData['updated_at']);

            // Modify name to indicate it's a copy
            $productData['name'] = $product->name . ' (Copy)';

            // Generate unique SKU
            $baseSku = $product->sku . '-Copy';
            $sku = $baseSku;
            $counter = 1;
            while (Product::where('sku', $sku)->exists()) {
                $sku = $baseSku . '-' . $counter;
                $counter++;
            }
            $productData['sku'] = $sku;

            // Generate unique slug
            $baseSlug = $product->slug . '-copy';
            $slug = $baseSlug;
            $counter = 1;
            while (Product::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }
            $productData['slug'] = $slug;

            // Set status to draft for review
            $productData['status'] = 'draft';

            // Create new product
            $newProduct = Product::create($productData);

            // Copy many-to-many relationships
            if ($product->collections && $product->collections->isNotEmpty()) {
                $newProduct->collections()->sync($product->collections->pluck('id'));
            }

            if ($product->tags && $product->tags->isNotEmpty()) {
                $newProduct->tags()->sync($product->tags->pluck('id'));
            }

            if ($product->colors && $product->colors->isNotEmpty()) {
                $newProduct->colors()->sync($product->colors->pluck('id'));
            }

            if ($product->sizes && $product->sizes->isNotEmpty()) {
                $newProduct->sizes()->sync($product->sizes->pluck('id'));
            }

            // Copy variants
            if ($product->variants && $product->variants->isNotEmpty()) {
                foreach ($product->variants as $variant) {
                    $variantData = $variant->toArray();
                    unset($variantData['id'], $variantData['product_id'], $variantData['created_at'], $variantData['updated_at']);

                    // Generate unique variant SKU
                    if (!empty($variantData['sku'])) {
                        $baseVariantSku = $variantData['sku'] . '-Copy';
                        $variantSku = $baseVariantSku;
                        $variantCounter = 1;
                        while (ProductVariant::where('sku', $variantSku)->exists()) {
                            $variantSku = $baseVariantSku . '-' . $variantCounter;
                            $variantCounter++;
                        }
                        $variantData['sku'] = $variantSku;
                    }

                    $variantData['product_id'] = $newProduct->id;
                    ProductVariant::create($variantData);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Product copied successfully!',
                'product_id' => $newProduct->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Product copy failed', ['error' => $e->getMessage(), 'product_id' => $product->id]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to copy product: ' . $e->getMessage()
            ], 500);
        }
    }
}