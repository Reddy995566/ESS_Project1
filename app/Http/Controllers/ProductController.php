<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\RecentlyViewed;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->activeAndApproved()
            ->with(['colors', 'sizes']) // Load colors and sizes relationships
            ->withCount('approvedReviews')
            ->firstOrFail();

        // Track view for logged-in users
        if (auth()->check()) {
            RecentlyViewed::trackView(auth()->id(), $product->id);
        }

        // Get selected color and size from query params
        $selectedColorId = request()->query('color');
        $selectedSizeId = request()->query('size');

        // Get similar products from the same category
        $similarProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->activeAndApproved()
            ->limit(4)
            ->get();

        // Get recently viewed products
        $recentlyViewed = collect();

        if (auth()->check()) {
            // For logged-in users, get from database
            $recentlyViewed = RecentlyViewed::getRecentlyViewed(
                auth()->id(),
                10,
                $product->id
            );
        }

        // Calculate recent purchases (last 24 hours)
        // This would require an orders table - for now using a placeholder
        // You can implement this based on your orders/sales tracking
        $recentPurchases = 0; // TODO: Implement based on your orders table
        
        return view('website.product-details', compact('product', 'similarProducts', 'recentlyViewed', 'recentPurchases', 'selectedColorId', 'selectedSizeId'));

    }

    /**
     * Track product view (AJAX endpoint)
     */
    public function trackView(Request $request)
    {
        $productId = $request->input('product_id');

        if (!$productId) {
            return response()->json(['success' => false, 'message' => 'Product ID required'], 400);
        }

        // Check if product exists
        $product = Product::find($productId);
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found'], 404);
        }

        // Track for logged-in users
        if (auth()->check()) {
            RecentlyViewed::trackView(auth()->id(), $productId);
            return response()->json(['success' => true, 'storage' => 'database']);
        }

        // For guests, return success (handled by localStorage on frontend)
        return response()->json(['success' => true, 'storage' => 'localStorage']);
    }

    /**
     * Get recently viewed products (AJAX endpoint for guests)
     */
    public function getRecentlyViewed(Request $request)
    {
        $productIds = $request->input('product_ids', []);
        $excludeId = $request->input('exclude_id');

        if (empty($productIds)) {
            return response()->json(['products' => []]);
        }

        // Get products in the order they were viewed
        $products = Product::whereIn('id', $productIds)
            ->activeAndApproved()
            ->when($excludeId, function ($query) use ($excludeId) {
                return $query->where('id', '!=', $excludeId);
            })
            ->get()
            ->sortBy(function ($product) use ($productIds) {
                return array_search($product->id, $productIds);
            })
            ->values();

        return response()->json([
            'products' => $products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'price' => $product->price,
                    'compare_price' => $product->compare_price,
                    'image_url' => $product->image_url,
                ];
            })
        ]);
    }

    /**
     * Legacy method - kept for backward compatibility
     */
    public function trackRecentlyViewed($productId)
    {
        $recentlyViewed = session('recently_viewed', []);

        // Remove if already exists to avoid duplicates
        $recentlyViewed = array_diff($recentlyViewed, [$productId]);

        // Add to beginning of array
        array_unshift($recentlyViewed, $productId);

        // Keep only last 10
        $recentlyViewed = array_slice($recentlyViewed, 0, 10);

        session(['recently_viewed' => $recentlyViewed]);

        return response()->json(['success' => true]);
    }
}
