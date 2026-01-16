<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Fabric extends Model
{
    use SoftDeletes;

    protected $fillable = [
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
        'is_featured',
        'show_in_navbar',
        'show_in_homepage',
        'meta_title',
        'meta_description', 
        'meta_keywords'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'show_in_navbar' => 'boolean',
        'show_in_homepage' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($fabric) {
            if (empty($fabric->slug)) {
                $fabric->slug = Str::slug($fabric->name);
            }
        });

        static::updating(function ($fabric) {
            if ($fabric->isDirty('name') && empty($fabric->slug)) {
                $fabric->slug = Str::slug($fabric->name);
            }
        });
    }

    // Uncomment when products table has fabric_id column
    // public function products()
    // {
    //     return $this->hasMany(Product::class, 'fabric_id', 'id');
    // }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function getImageUrl()
    {
        return $this->imagekit_url;
    }

    public function getOptimizedImageUrl($width = 540, $height = 689, $quality = 80)
    {
        if ($this->imagekit_file_path) {
            $imageKitService = new \App\Services\ImageKitService();
            return $imageKitService->getOptimizedUrl($this->imagekit_file_path, $width, $height, $quality);
        }

        return $this->getImageUrl();
    }

    public function getThumbnailUrl()
    {
        if ($this->imagekit_thumbnail_url) {
            return $this->imagekit_thumbnail_url;
        }

        if ($this->imagekit_file_path) {
            $imageKitService = new \App\Services\ImageKitService();
            return $imageKitService->getThumbnailUrl($this->imagekit_file_path);
        }

        return null;
    }

    public function getListImageUrl()
    {
        if ($this->imagekit_file_path) {
            $imageKitService = new \App\Services\ImageKitService();
            return $imageKitService->getListUrl($this->imagekit_file_path);
        }

        return null;
    }

    public function hasImageKitImage()
    {
        return !empty($this->imagekit_file_id) && !empty($this->imagekit_url);
    }

    public function getFormattedImageSize()
    {
        if (!$this->image_size) {
            return 'N/A';
        }

        $size = $this->image_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        $unitIndex = 0;

        while ($size >= 1024 && $unitIndex < count($units) - 1) {
            $size /= 1024;
            $unitIndex++;
        }

        return round($size, 2) . ' ' . $units[$unitIndex];
    }
}
