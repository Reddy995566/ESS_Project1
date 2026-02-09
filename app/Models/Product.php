<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'seller_id',
        'name',
        'slug',
        'sku',
        'description',
        'washing_instructions',
        'shipping_information',
        'returns_refunds',
        'price',
        'sale_price',
        'stock',
        'stock_status',
        'brand_id',
        'category_id',
        'fabric_id',
        'image',
        'images',
        'status',
        'visibility',
        'approval_status',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'featured',
        'is_featured',
        'is_new',
        'is_trending',
        'is_bestseller',
        'is_topsale',
        'is_sale',
        'is_discounted',
        'show_in_homepage',
        'is_exclusive',
        'is_limited_edition',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'focus_keywords',
        'canonical_url',
        'og_title',
        'og_description'
    ];

    protected $hidden = [];

    protected $appends = [
        'image_url',
        'images_array'
    ];

    protected $casts = [
        'images' => 'array',
        'featured' => 'boolean',
        'is_featured' => 'boolean',
        'is_new' => 'boolean',
        'is_trending' => 'boolean',
        'is_bestseller' => 'boolean',
        'is_topsale' => 'boolean',
        'is_sale' => 'boolean',
        'is_discounted' => 'boolean',
        'show_in_homepage' => 'boolean',
        'is_exclusive' => 'boolean',
        'is_limited_edition' => 'boolean',
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'compare_price' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
            if (empty($product->sku)) {
                $product->sku = 'PRD-' . strtoupper(Str::random(8));
            }
        });
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }

    public function fabric()
    {
        return $this->belongsTo(Fabric::class);
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(Admin::class, 'approved_by');
    }

    public function colors()
    {
        return $this->belongsToMany(Color::class, 'product_color', 'product_id', 'color_id')->withTimestamps();
    }

    public function sizes()
    {
        return $this->belongsToMany(Size::class, 'product_size', 'product_id', 'size_id')->withTimestamps();
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'product_tag', 'product_id', 'tag_id')->withTimestamps();
    }

    public function collections()
    {
        return $this->belongsToMany(Collection::class, 'product_collection', 'product_id', 'collection_id')->withTimestamps();
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function approvedReviews()
    {
        return $this->hasMany(Review::class)->where('is_approved', true);
    }

    public function defaultVariant()
    {
        return $this->hasOne(ProductVariant::class)->where('is_default', true);
    }

    public function getImageUrlAttribute()
    {
        // Use the helper method for consistency
        return $this->getFirstImageUrl();
    }

    public function getImagesArrayAttribute()
    {
        $images = $this->images;
        $urls = [];

        if (is_string($images)) {
            $decoded = json_decode($images, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $images = $decoded;
            }
        }

        if (is_array($images)) {
            foreach ($images as $img) {
                if (is_string($img) && trim($img) !== '') {
                    $urls[] = trim($img);
                } elseif (is_array($img)) {
                    $u = $img['url'] ?? $img['path'] ?? null;
                    if (is_string($u) && trim($u) !== '') {
                        $urls[] = trim($u);
                    }
                }
            }
        }

        return array_values(array_unique($urls));
    }

    /**
     * Get the first product image URL
     * Priority: image column -> images column (first image)
     */
    public function getFirstImageUrl()
    {
        // First try the 'image' column
        $image = $this->attributes['image'] ?? null;

        if (is_string($image) && !empty(trim($image))) {
            $trimmed = trim($image);

            // Try to decode if it's JSON
            $decoded = json_decode($trimmed, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $firstImage = $decoded[0] ?? $decoded['url'] ?? $decoded['path'] ?? null;
                if ($firstImage)
                    return $firstImage;
            } else {
                // Return as is if not JSON
                return $trimmed;
            }
        }

        if (is_array($image) && !empty($image)) {
            $firstImage = $image[0] ?? $image['url'] ?? $image['path'] ?? null;
            if ($firstImage)
                return $firstImage;
        }

        // Fallback to 'images' column if 'image' is empty
        $images = $this->attributes['images'] ?? null;

        if (is_string($images) && !empty(trim($images))) {
            $decoded = json_decode($images, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded) && !empty($decoded)) {
                $firstImage = $decoded[0];

                // Handle if first element is string
                if (is_string($firstImage)) {
                    return $firstImage;
                }

                // Handle if first element is array with url/path
                if (is_array($firstImage)) {
                    return $firstImage['url'] ?? $firstImage['path'] ?? null;
                }
            }
        }

        if (is_array($images) && !empty($images)) {
            $firstImage = $images[0];

            if (is_string($firstImage)) {
                return $firstImage;
            }

            if (is_array($firstImage)) {
                return $firstImage['url'] ?? $firstImage['path'] ?? null;
            }
        }

        // Fallback to variants if no direct images found
        // Use eager loaded relation if possible, or lazy load
        if ($this->variants->isNotEmpty()) {
            // Try to find default variant first
            $defaultVariant = $this->variants->where('is_default', true)->first();
            if ($defaultVariant && $defaultVariant->image_url) {
                return $defaultVariant->image_url;
            }

            // Otherwise take the first variant available
            $firstVariant = $this->variants->first();
            if ($firstVariant && $firstVariant->image_url) {
                return $firstVariant->image_url;
            }
        }

        return null;
    }

    public function getDisplayImagesAttribute()
    {
        // 1. Get Gallery Images
        $allImages = $this->images_array ?? [];

        // 2. Get Main Image (Raw check to avoid recursion or fallback logic of accessors)
        $mainImageRaw = $this->attributes['image'] ?? null;
        $mainImageUrl = null;

        if (!empty($mainImageRaw)) {
            if (is_string($mainImageRaw)) {
                $decoded = json_decode($mainImageRaw, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $mainImageRaw = $decoded;
                }
            }

            if (is_array($mainImageRaw)) {
                $mainImageUrl = $mainImageRaw['url'] ?? $mainImageRaw['path'] ?? ($mainImageRaw[0] ?? null);
                // Handle nested array case if any
                if (is_array($mainImageUrl)) {
                    $mainImageUrl = $mainImageUrl['url'] ?? $mainImageUrl['path'] ?? null;
                }
            } elseif (is_string($mainImageRaw)) {
                $mainImageUrl = $mainImageRaw;
            }
        }

        // 3. Merge Main Image if valid and unique
        if ($mainImageUrl && is_string($mainImageUrl) && !in_array($mainImageUrl, $allImages)) {
            array_unshift($allImages, $mainImageUrl);
        }

        // 4. Return if we have images
        if (!empty($allImages)) {
            return $allImages;
        }

        // 5. Fallback to Variants (if no product images at all)
        if ($this->variants->isNotEmpty()) {
            $urls = [];
            foreach ($this->variants as $variant) {
                $vImages = $variant->images;

                if (is_string($vImages)) {
                    $decoded = json_decode($vImages, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $vImages = $decoded;
                    }
                }

                if (is_array($vImages)) {
                    foreach ($vImages as $img) {
                        $u = is_array($img) ? ($img['url'] ?? $img['path'] ?? null) : $img;
                        if ($u)
                            $urls[] = $u;
                    }
                }
            }
            if (!empty($urls)) {
                return array_values(array_unique($urls));
            }
        }

        return [];
    }

    public function getGroupedVariantImagesAttribute()
    {
        $grouped = [];
        if ($this->variants->isNotEmpty()) {
            foreach ($this->variants as $variant) {
                if ($variant->color_id) {
                    $images = $variant->images;

                    if (is_string($images)) {
                        $images = json_decode($images, true);
                    }

                    if (is_array($images)) {
                        $urls = [];
                        foreach ($images as $img) {
                            $u = is_array($img) ? ($img['url'] ?? $img['path'] ?? null) : $img;
                            if ($u && is_string($u))
                                $urls[] = trim($u);
                        }
                        if (!empty($urls)) {
                            $grouped[$variant->color_id] = $urls;
                        }
                    }
                }
            }
        }
        return $grouped;
    }
}