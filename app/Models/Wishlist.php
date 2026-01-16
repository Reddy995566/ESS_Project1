<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
        'product_id',
        'variant_id',
        'color_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    // Get wishlist items for user or session
    public static function getWishlistItems($userId = null, $sessionId = null)
    {
        $query = self::with(['product.category', 'product.variants.color', 'product.variants.size', 'variant.color', 'variant.size']);
        
        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }
        
        return $query->get();
    }

    // Check if product is in wishlist (optionally with variant)
    public static function isInWishlist($productId, $userId = null, $sessionId = null, $variantId = null)
    {
        $query = self::where('product_id', $productId);
        
        if ($variantId) {
            $query->where('variant_id', $variantId);
        }
        
        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }
        
        return $query->exists();
    }

    // Get wishlist count
    public static function getWishlistCount($userId = null, $sessionId = null)
    {
        $query = self::query();
        
        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }
        
        return $query->count();
    }
}
