<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Wishlist;
use App\Models\Product;

class WishlistController extends Controller
{
    public function getWishlist()
    {
        $userId = Auth::id();
        $sessionId = session()->getId();
        
        $wishlistItems = Wishlist::with(['product', 'color'])
            ->when($userId, fn($q) => $q->where('user_id', $userId))
            ->when(!$userId, fn($q) => $q->where('session_id', $sessionId))
            ->get();
        
        $items = [];
        foreach($wishlistItems as $item) {
            if($item->product) {
                $items[] = [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'name' => $item->product->name,
                    'price' => $item->product->sale_price > 0 ? $item->product->sale_price : $item->product->price,
                    'original_price' => $item->product->sale_price > 0 ? $item->product->price : null,
                    'image' => $item->product->image_url,
                    'slug' => $item->product->slug,
                    'color_id' => $item->color_id,
                    'color_name' => $item->color ? $item->color->name : null,
                    'color_hex' => $item->color ? $item->color->hex_code : null,
                ];
            }
        }
        
        // Get 5 random products for "Explore Our Best Sellers"
        $randomProducts = Product::activeAndApproved()
            ->inRandomOrder()
            ->limit(5)
            ->get()
            ->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->sale_price > 0 ? $product->sale_price : $product->price,
                    'original_price' => $product->sale_price > 0 ? $product->price : null,
                    'image' => $product->image_url,
                    'slug' => $product->slug,
                ];
            });
        
        return response()->json([
            'success' => true,
            'items' => $items,
            'count' => count($items),
            'recommended' => $randomProducts,
        ]);
    }

    public function addToWishlist(Request $request)
    {
        try {
            $productId = $request->input('product_id');
            $colorId = $request->input('color_id');
            $product = Product::find($productId);
            
            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found'
                ], 404);
            }

            $userId = Auth::id();
            $sessionId = session()->getId();
            
            // Check if already in wishlist (same product and color)
            $exists = Wishlist::where('product_id', $productId)
                ->when($colorId, fn($q) => $q->where('color_id', $colorId))
                ->when($userId, fn($q) => $q->where('user_id', $userId))
                ->when(!$userId, fn($q) => $q->where('session_id', $sessionId))
                ->exists();
            
            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Already in wishlist'
                ]);
            }
            
            // Add to wishlist
            Wishlist::create([
                'user_id' => $userId,
                'session_id' => $sessionId,
                'product_id' => $productId,
                'color_id' => $colorId,
            ]);
            
            // Get updated wishlist
            $count = Wishlist::when($userId, fn($q) => $q->where('user_id', $userId))
                ->when(!$userId, fn($q) => $q->where('session_id', $sessionId))
                ->count();
            
            return response()->json([
                'success' => true,
                'message' => 'Added to wishlist',
                'count' => $count,
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function removeFromWishlist(Request $request)
    {
        try {
            $wishlistId = $request->input('wishlist_id');
            $userId = Auth::id();
            $sessionId = session()->getId();
            
            $wishlist = Wishlist::where('id', $wishlistId)
                ->when($userId, fn($q) => $q->where('user_id', $userId))
                ->when(!$userId, fn($q) => $q->where('session_id', $sessionId))
                ->first();
            
            if (!$wishlist) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item not found'
                ], 404);
            }
            
            $wishlist->delete();
            
            // Get updated count
            $count = Wishlist::when($userId, fn($q) => $q->where('user_id', $userId))
                ->when(!$userId, fn($q) => $q->where('session_id', $sessionId))
                ->count();
            
            return response()->json([
                'success' => true,
                'message' => 'Removed from wishlist',
                'count' => $count,
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function checkWishlist(Request $request)
    {
        $productId = $request->input('product_id');
        $userId = Auth::id();
        $sessionId = session()->getId();
        
        $inWishlist = Wishlist::where('product_id', $productId)
            ->when($userId, fn($q) => $q->where('user_id', $userId))
            ->when(!$userId, fn($q) => $q->where('session_id', $sessionId))
            ->exists();
        
        return response()->json([
            'success' => true,
            'in_wishlist' => $inWishlist,
        ]);
    }
    
    /**
     * Get wishlist item count
     */
    public function getCount()
    {
        $userId = Auth::id();
        $sessionId = session()->getId();
        
        $count = Wishlist::when($userId, fn($q) => $q->where('user_id', $userId))
            ->when(!$userId, fn($q) => $q->where('session_id', $sessionId))
            ->count();
        
        return response()->json(['count' => $count]);
    }
}
