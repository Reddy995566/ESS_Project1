<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PromotionalBanner extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'link',
        'desktop_image',
        'desktop_imagekit_file_id',
        'desktop_imagekit_url',
        'desktop_imagekit_file_path',
        'mobile_image',
        'mobile_imagekit_file_id',
        'mobile_imagekit_url',
        'mobile_imagekit_file_path',
        'position',
        'sort_order',
        'is_active',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    // Position options
    public const POSITIONS = [
        'after_hero_banner' => 'After Hero Banner',
        'after_all_time_favorites' => 'After All-Time Favorites',
        'after_hot_collection' => 'After Hot Collection',
        'after_shop_by_budget' => 'After Shop By Budget',
        'after_video_reels' => 'After Video Reels',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePosition($query, $position)
    {
        return $query->where('position', $position);
    }

    public function scopeCurrentlyActive($query)
    {
        $now = now();
        return $query->where('is_active', true)
            ->where(function ($q) use ($now) {
                $q->whereNull('start_date')
                    ->orWhere('start_date', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('end_date')
                    ->orWhere('end_date', '>=', $now);
            });
    }

    public function getDesktopImageUrl()
    {
        return $this->desktop_imagekit_url;
    }

    public function getMobileImageUrl()
    {
        return $this->mobile_imagekit_url ?? $this->desktop_imagekit_url;
    }

    public function hasDesktopImage()
    {
        return !empty($this->desktop_imagekit_file_id) && !empty($this->desktop_imagekit_url);
    }

    public function hasMobileImage()
    {
        return !empty($this->mobile_imagekit_file_id) && !empty($this->mobile_imagekit_url);
    }
}
