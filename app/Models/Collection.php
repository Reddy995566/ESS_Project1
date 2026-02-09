<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Collection extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'seller_id',
        'name',
        'slug',
        'description',
        'image',
        'imagekit_file_id',
        'imagekit_url',
        'imagekit_thumbnail_url',
        'imagekit_file_path',
        'image_size',
        'original_image_size',
        'image_width',
        'image_height',
        'sort_order',
        'is_active',
        'show_in_homepage',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'show_in_homepage' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($collection) {
            if (empty($collection->slug)) {
                $collection->slug = Str::slug($collection->name);
            }
        });

        static::updating(function ($collection) {
            if ($collection->isDirty('name') && empty($collection->slug)) {
                $collection->slug = Str::slug($collection->name);
            }
        });
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeShowInHomepage($query)
    {
        return $query->where('show_in_homepage', true);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_collection', 'collection_id', 'product_id')->withTimestamps();
    }

    public function getImageUrlAttribute()
    {
        // 1. Return ImageKit URL if available
        if (!empty($this->imagekit_url)) {
            return $this->imagekit_url;
        }

        // 2. Return local image if available
        if (!empty($this->image)) {
            if (str_starts_with($this->image, 'http')) {
                return $this->image;
            }
            return asset('storage/' . $this->image);
        }

        // 3. Return placeholder
        return asset('website/assets/images/placeholder.jpg');
    }
}