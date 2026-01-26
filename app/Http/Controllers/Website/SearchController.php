<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('q');

        if (!$query) {
            // If AJAX request, return JSON
            if ($request->ajax() || $request->input('ajax')) {
                return response()->json([
                    'products' => [],
                    'total' => 0,
                    'html' => view('website.partials.no-products')->render()
                ]);
            }
            // Otherwise redirect to shop
            return redirect()->route('shop');
        }

        // Start query
        $productsQuery = Product::where('status', 'active')
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhere('sku', 'like', "%{$query}%");
            });

        // Debug: Log the base query count
        $baseCount = $productsQuery->count();
        \Log::info('Search base count for "' . $query . '": ' . $baseCount);

        // Apply category filter
        if ($request->has('categories') && $request->categories) {
            $categories = explode(',', $request->categories);
            $productsQuery->whereIn('category_id', $categories);
            \Log::info('After category filter: ' . $productsQuery->count() . ' (categories: ' . implode(',', $categories) . ')');
        }

        // Apply price range filter
        if ($request->has('min_price') && $request->min_price) {
            $productsQuery->whereRaw('COALESCE(sale_price, price) >= ?', [$request->min_price]);
            \Log::info('After min_price filter: ' . $productsQuery->count() . ' (min: ' . $request->min_price . ')');
        }

        if ($request->has('max_price') && $request->max_price) {
            $productsQuery->whereRaw('COALESCE(sale_price, price) <= ?', [$request->max_price]);
            \Log::info('After max_price filter: ' . $productsQuery->count() . ' (max: ' . $request->max_price . ')');
        }

        // Apply availability filter
        if ($request->has('availability') && $request->availability !== 'all') {
            $productsQuery->where('stock_status', $request->availability);
        }

        // Apply sorting
        if ($request->has('sort') && $request->sort) {
            switch ($request->sort) {
                case 'price_low':
                    $productsQuery->orderByRaw('COALESCE(sale_price, price) ASC');
                    break;
                case 'price_high':
                    $productsQuery->orderByRaw('COALESCE(sale_price, price) DESC');
                    break;
                case 'title-ascending':
                    $productsQuery->orderBy('name', 'ASC');
                    break;
                case 'title-descending':
                    $productsQuery->orderBy('name', 'DESC');
                    break;
                case 'created-descending':
                    $productsQuery->orderBy('created_at', 'DESC');
                    break;
            }
        }

        $products = $productsQuery->with(['category', 'brand'])->get();
        $total = $products->count();

        // If AJAX request (for filters)
        if ($request->ajax() || $request->input('ajax')) {
            // For live search suggestions (no filters) - only when it's truly autocomplete
            $hasFilters = $request->has('categories') || 
                         $request->has('min_price') || 
                         $request->has('max_price') || 
                         $request->has('availability') || 
                         $request->has('sort');
            
            if (!$hasFilters) {
                // Autocomplete suggestions
                $productsData = $products->take(50)->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'slug' => $product->slug,
                        'url' => route('product.show', $product->slug),
                        'price' => number_format($product->sale_price > 0 ? $product->sale_price : $product->price, 0),
                        'original_price' => $product->sale_price > 0 ? number_format($product->price, 0) : null,
                        'image' => $product->image_url ?? null,
                    ];
                });

                return response()->json([
                    'products' => $productsData,
                    'total' => $total
                ]);
            }

            // For filtered results, return HTML
            if ($products->count() > 0) {
                $html = '<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6">';
                foreach ($products as $product) {
                    $html .= view('website.partials.product-card', ['product' => $product])->render();
                }
                $html .= '</div>';
            } else {
                $html = view('website.partials.no-products')->render();
            }

            return response()->json([
                'html' => $html,
                'total' => $total
            ]);
        }

        // Get all categories for filter
        $categories = \App\Models\Category::where('is_active', true)
            ->whereNull('parent_id')
            ->orderBy('name', 'ASC')
            ->get();

        // Calculate max price for filter
        $maxPrice = Product::where('status', 'active')->max('price') ?? 10000;

        // Regular search page view
        return view('website.search', [
            'products' => $products,
            'query' => $query,
            'total' => $total,
            'categories' => $categories,
            'maxPrice' => $maxPrice
        ]);
    }

    public function quickView($id)
    {
        try {
            $product = Product::where('status', 'active')->find($id);

            if (!$product) {
                return response()->json(['error' => 'Product not found'], 404);
            }

            // 1. Images: Logic matching product-details.blade.php
            $groupedVariantImages = $product->grouped_variant_images;
            $initialImages = $product->images_array;

            // Fallback to first variant if no main images but variants exist
            if (empty($initialImages) && !empty($groupedVariantImages)) {
                $initialImages = reset($groupedVariantImages);
            }

            // Final fallback
            if (empty($initialImages)) {
                $initialImages = [$product->image_url ?? asset('website/assets/images/placeholder.jpg')];
            }

            // 2. Colors: Use the 'colors' relationship explicitly to avoid shadowing
            $colorsCollection = $product->colors()->get();
            $groupedImages = $product->grouped_variant_images;

            $colors = $colorsCollection->map(function ($color) use ($groupedImages) {
                // Find image for this color from grouped variants
                $image = null;
                if (isset($groupedImages[$color->id]) && !empty($groupedImages[$color->id])) {
                    $image = $groupedImages[$color->id][0];
                }

                return [
                    'id' => $color->id,
                    'name' => $color->name,
                    'image' => $image,
                    'hex_code' => $color->hex_code
                ];
            });

            // 3. More Colors (Related Products)
            $moreColors = Product::where('category_id', $product->category_id)
                ->where('id', '!=', $product->id)
                ->where('status', 'active')
                ->limit(4)
                ->get()
                ->map(function ($p) {
                    return [
                        'id' => $p->id,
                        'name' => $p->name,
                        'slug' => $p->slug,
                        'image' => $p->image_url ?? asset('website/assets/images/placeholder.jpg')
                    ];
                });

            // 4. Reviews count
            $reviewsCount = $product->reviews()->count(); // Safer than accessing reviews relationship directly if large

            // 5. Recent purchases (last 24 hours)
            // TODO: Implement based on your orders table
            $recentPurchases = 0; // Placeholder

            $data = [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'price' => $product->price,
                'sale_price' => $product->sale_price,
                'original_price' => $product->sale_price > 0 ? $product->price : null,
                'description' => $product->description,
                'rating' => 4.5, // Placeholder or calculate: $product->reviews()->avg('rating')
                'reviews_count' => $reviewsCount,
                'recent_purchases' => $recentPurchases,
                'images' => $initialImages,
                'grouped_images' => $groupedVariantImages,
                'colors' => $colors,
                'more_colors' => $moreColors,
                // Add any other specific fields needed for the modal
                'sku' => $product->sku,
                'stock_status' => $product->stock_status,
            ];

            return response()->json($data);

        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Server Error: ' . $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 200);
        }
    }
}
