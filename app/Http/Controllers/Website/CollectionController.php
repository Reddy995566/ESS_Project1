<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class CollectionController extends Controller
{
    /**
     * Get all descendant category IDs recursively
     */
    private function getAllChildCategoryIds($parentId)
    {
        $ids = [];
        $children = Category::where('parent_id', $parentId)->pluck('id')->toArray();
        
        foreach ($children as $childId) {
            $ids[] = $childId;
            // Recursively get children of this child
            $ids = array_merge($ids, $this->getAllChildCategoryIds($childId));
        }
        
        return $ids;
    }

    public function index(Request $request)
    {
        // Start with active products
        $query = Product::where('status', 'active')
            ->with(['category', 'reviews']);

        // 1. Filter by Category
        if ($request->has('category')) {
            $slug = $request->input('category');
            $category = Category::where('slug', $slug)->first();
            if ($category) {
                // Get all category IDs (parent + all descendants)
                $categoryIds = [$category->id];
                $childIds = $this->getAllChildCategoryIds($category->id);
                $categoryIds = array_merge($categoryIds, $childIds);
                
                $query->whereIn('category_id', $categoryIds);
            }
        }

        // 2. Filter by Collection (from URL param if any, e.g. ?collection=slug)
        if ($request->has('collection')) {
            $collectionSlug = $request->input('collection');
            $collection = \App\Models\Collection::where('slug', $collectionSlug)->first();
            if ($collection) {
                // Assuming many-to-many relationship products() exists on Collection model
                // However, since we started with Product::query(), we need to filter whereHas
                $query->whereHas('collections', function ($q) use ($collection) {
                    $q->where('collections.id', $collection->id);
                });
            }
        }

        // 3. Filter by Availability
        if ($request->has('availability')) {
            $availability = $request->input('availability');
            if ($availability === 'in_stock') {
                $query->where('stock', '>', 0);
            } elseif ($availability === 'out_of_stock') {
                $query->where('stock', '<=', 0);
            }
        }

        // 4. Filter by Price
        if ($request->has('min_price') && $request->input('min_price') !== null) {
            $query->where('price', '>=', $request->input('min_price'));
        }
        if ($request->has('max_price') && $request->input('max_price') !== null) {
            $query->where('price', '<=', $request->input('max_price'));
        }

        // 5. Sorting
        $sort = $request->input('sort', 'featured'); // Default to featured
        switch ($sort) {
            case 'best-selling':
                // Assuming 'sold_count' or similar exists. If not, fallback to newest.
                // For now, let's assume we don't have a reliable sold count column yet or use `id` as proxy?
                // Or maybe order by `sales_count` if available. Checking Product model might be needed.
                // Falling back to created_at for now until confirmed.
                $query->orderBy('created_at', 'desc');
                break;
            case 'title-ascending':
                $query->orderBy('name', 'asc');
                break;
            case 'title-descending':
                $query->orderBy('name', 'desc');
                break;
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'featured':
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        // Get Min and Max price for the slider (based on current filtered result or global? Usually global for the category)
        // To be performant, let's get global range for active products
        $priceRange = Product::where('status', 'active')
            ->selectRaw('MIN(price) as min, MAX(price) as max')
            ->first();

        $minPrice = floor($priceRange->min ?? 0);
        $maxPrice = ceil($priceRange->max ?? 10000);

        // Pagination
        $products = $query->paginate(12);

        // Fetch Categories for Filter Sidebar
        $categories = Category::where('is_active', true)->withCount('products')->get();

        // Count for Availability Filter (Optional, but good for UI "In stock (18)")
        $inStockCount = Product::where('status', 'active')->where('stock', '>', 0)->count();
        $outStockCount = Product::where('status', 'active')->where('stock', '<=', 0)->count();

        return view('website.collection', compact(
            'products',
            'categories',
            'sort',
            'minPrice',
            'maxPrice',
            'inStockCount',
            'outStockCount'
        ));
    }

    public function filterProducts(Request $request)
    {
        // Start with active products
        $query = Product::where('status', 'active')
            ->with(['category', 'reviews']);

        // 1. Filter by Category
        if ($request->has('category')) {
            $slug = $request->input('category');
            $category = Category::where('slug', $slug)->first();
            if ($category) {
                // Get all category IDs (parent + all descendants)
                $categoryIds = [$category->id];
                $childIds = $this->getAllChildCategoryIds($category->id);
                $categoryIds = array_merge($categoryIds, $childIds);
                
                $query->whereIn('category_id', $categoryIds);
            }
        }

        // 2. Filter by Collection
        if ($request->has('collection')) {
            $collectionSlug = $request->input('collection');
            $collection = \App\Models\Collection::where('slug', $collectionSlug)->first();
            if ($collection) {
                $query->whereHas('collections', function ($q) use ($collection) {
                    $q->where('collections.id', $collection->id);
                });
            }
        }

        // 3. Filter by Availability
        if ($request->has('availability')) {
            $availability = $request->input('availability');
            if ($availability === 'in_stock') {
                $query->where('stock', '>', 0);
            } elseif ($availability === 'out_of_stock') {
                $query->where('stock', '<=', 0);
            }
        }

        // 4. Filter by Price
        if ($request->has('min_price') && $request->input('min_price') !== null) {
            $query->where('price', '>=', $request->input('min_price'));
        }
        if ($request->has('max_price') && $request->input('max_price') !== null) {
            $query->where('price', '<=', $request->input('max_price'));
        }

        // 5. Sorting
        $sort = $request->input('sort', 'newest');
        switch ($sort) {
            case 'title-ascending':
                $query->orderBy('name', 'asc');
                break;
            case 'title-descending':
                $query->orderBy('name', 'desc');
                break;
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        // Pagination
        $products = $query->paginate(12);

        // Count for Availability Filter
        $inStockCount = Product::where('status', 'active')->where('stock', '>', 0)->count();
        $outStockCount = Product::where('status', 'active')->where('stock', '<=', 0)->count();

        // Return JSON response
        return response()->json([
            'products' => $products->items(),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'total' => $products->total(),
                'per_page' => $products->perPage(),
                'from' => $products->firstItem(),
                'to' => $products->lastItem(),
            ],
            'filters' => [
                'inStockCount' => $inStockCount,
                'outStockCount' => $outStockCount,
            ]
        ]);
    }
}
