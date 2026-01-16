<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogImage extends Model
{
    protected $fillable = [
        'blog_id',
        'image_url',
        'imagekit_file_id',
        'imagekit_url',
        'imagekit_thumbnail_url',
        'imagekit_file_path',
        'sort_order'
    ];

    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }

    public function getImageUrlAttribute($value)
    {
        return $this->imagekit_url ?: $value;
    }
}
