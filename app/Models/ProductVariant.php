<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id', 'sku', 'color_id', 'size_id', 'price', 'stock', 'images', 'is_default', 'is_active'
    ];

    protected $casts = [
        'images' => 'array',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    /**
     * Get the first variant image URL
     */
    public function getFirstImageUrl()
    {
        $images = $this->images;
        
        // If images is a string (shouldn't be, but handle it)
        if (is_string($images) && !empty(trim($images))) {
            $decoded = json_decode($images, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $images = $decoded;
            } else {
                return trim($images);
            }
        }
        
        // If images is an array
        if (is_array($images) && !empty($images)) {
            $firstImage = $images[0] ?? null;
            
            if (is_string($firstImage)) {
                return $firstImage;
            } elseif (is_array($firstImage)) {
                return $firstImage['url'] ?? $firstImage['path'] ?? null;
            }
        }
        
        return null;
    }

    /**
     * Get image URL accessor
     */
    public function getImageUrlAttribute()
    {
        return $this->getFirstImageUrl();
    }
}