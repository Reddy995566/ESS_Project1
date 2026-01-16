<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'desktop_image',
        'desktop_imagekit_file_id',
        'desktop_imagekit_url',
        'mobile_image',
        'mobile_imagekit_file_id',
        'mobile_imagekit_url',
        'link',
        'order',
        'is_active',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function($q) {
                $q->whereNull('start_date')
                    ->orWhere('start_date', '<=', now());
            })
            ->where(function($q) {
                $q->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
            });
    }

    public function getDesktopImageUrl()
    {
        return $this->desktop_imagekit_url ?? $this->desktop_image;
    }

    public function getMobileImageUrl()
    {
        return $this->mobile_imagekit_url ?? $this->mobile_image ?? $this->getDesktopImageUrl();
    }
}
