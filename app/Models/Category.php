<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Category extends Model
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
        'parent_id',
        'sort_order',
        'is_active',
        'is_featured',
        'show_in_navbar',
        'show_in_homepage',
        'show_in_circle',
        'circle_type',
        'circle_text',
        'circle_bg_color',
        'circle_text_color',
        'circle_link',
        'circle_order',
        'meta_title',
        'meta_description', 
        'meta_keywords'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'show_in_navbar' => 'boolean',
        'show_in_homepage' => 'boolean',
        'show_in_circle' => 'boolean',
        'sort_order' => 'integer',
        'circle_order' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });

        static::updating(function ($category) {
            if ($category->isDirty('name') && empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('sort_order');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeShowInNavbar($query)
    {
        return $query->where('show_in_navbar', true);
    }

    public function scopeShowInHomepage($query)
    {
        return $query->where('show_in_homepage', true);
    }

    public function scopeShowInCircle($query)
    {
        return $query->where('show_in_circle', true);
    }

    public function scopeParents($query)
    {
        return $query->whereNull('parent_id');
    }

    public function getFullPathAttribute()
    {
        $path = [$this->name];
        $parent = $this->parent;

        while ($parent) {
            array_unshift($path, $parent->name);
            $parent = $parent->parent;
        }

        return implode(' > ', $path);
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

    public function hasChildren()
    {
        return $this->children()->count() > 0;
    }
}