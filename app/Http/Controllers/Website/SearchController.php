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
            return response()->json([
                'suggestions' => [],
                'products' => [],
                'total_products' => 0
            ]);
        }

        // 1. Get Suggestions (Keywords)
        // We can search distinct product names that start with or contain the query
        // Or specific tags/categories if available. For now, let's use product names.
        $suggestions = Product::where('status', 'active')
            ->where('name', 'like', "%{$query}%")
            ->limit(5)
            ->pluck('name')
            ->map(function ($name) use ($query) {
                // Highlight the query part in the name for frontend logic if needed, 
                // but usually frontend handles highlighting. 
                // Let's just return the full name.
                // To make it look like "patola linen saree" we might want to verify if variations exist.
                return $name;
            });

        // 2. Get Products
        $products = Product::where('status', 'active')
            ->where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->with(['category']) // Eager load necessary relationships
            ->limit(5)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'price' => $product->sale_price > 0 ? $product->sale_price : $product->price,
                    'original_price' => $product->sale_price > 0 ? $product->price : null,
                    'image' => $product->image_url ?? asset('website/assets/images/placeholder.jpg'),
                    'reviews_count' => $product->reviews_count ?? 0, // Assuming relation exists or handle later
                    'rating' => 4.5, // Placeholder or actual rating
                ];
            });

        return response()->json([
            'suggestions' => $suggestions,
            'products' => $products,
            'total_products' => Product::where('status', 'active')->where('name', 'like', "%{$query}%")->count()
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
