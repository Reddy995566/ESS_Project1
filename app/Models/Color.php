<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Color extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'seller_id',
        'name',
        'hex_code',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForSeller($query, $sellerId)
    {
        return $query->where('seller_id', $sellerId);
    }

    public function seller()
    {
        return $this->belongsTo(\App\Models\Seller::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_color', 'color_id', 'product_id')->withTimestamps();
    }

    public function productVariants()
    {
        return $this->hasMany(ProductVariant::class, 'color_id');
    }
}