<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BudgetCard extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'price',
        'link',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'integer',
        'sort_order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('price');
    }

    // Get the shop link with price filter
    public function getShopLinkAttribute()
    {
        if ($this->link) {
            return $this->link;
        }
        return route('shop') . '?max_price=' . $this->price;
    }

    // Format price for display
    public function getFormattedPriceAttribute()
    {
        return '₹ ' . number_format($this->price);
    }
}
