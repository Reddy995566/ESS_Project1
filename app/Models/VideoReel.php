<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoReel extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'video_url',
        'video_file_id',
        'product_id',
        'badge',
        'badge_color',
        'views_count',
        'sort_order',
        'is_active',
        'autoplay',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'autoplay' => 'boolean',
        'views_count' => 'integer',
        'sort_order' => 'integer',
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('created_at', 'desc');
    }

    // Format views count
    public function getFormattedViewsAttribute()
    {
        $views = $this->views_count;
        if ($views >= 1000000) {
            return number_format($views / 1000000, 1) . 'M';
        } elseif ($views >= 1000) {
            return number_format($views / 1000, 1) . 'K';
        }
        return $views;
    }
}
