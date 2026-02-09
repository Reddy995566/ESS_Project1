<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'variant_id',
        'seller_id',
        'product_name',
        'variant_name',
        'price',
        'quantity',
        'total',
        'commission_rate',
        'commission_amount',
        'seller_amount',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'total' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'seller_amount' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function returns()
    {
        return $this->hasMany(\App\Models\ProductReturn::class);
    }

    /**
     * Get the image URL for this order item
     * Priority: Variant image > Product image
     */
    public function getImageUrl()
    {
        // Try variant image first if variant exists
        if ($this->variant) {
            $variantImage = $this->variant->getFirstImageUrl();
            if ($variantImage) {
                return $variantImage;
            }
        }
        
        // Fallback to product image
        if ($this->product) {
            return $this->product->getFirstImageUrl();
        }
        
        return null;
    }
}
