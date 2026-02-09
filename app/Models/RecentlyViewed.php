<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecentlyViewed extends Model
{
    use HasFactory;

    protected $table = 'recently_viewed';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'product_id',
        'viewed_at',
    ];

    protected $casts = [
        'viewed_at' => 'datetime',
    ];

    /**
     * Get the user that viewed the product
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the product that was viewed (only approved products)
     */
    public function product()
    {
        return $this->belongsTo(Product::class)->where('status', 'active')->where('approval_status', 'approved');
    }

    /**
     * Scope to get recent views for a user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to get most recent views
     */
    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderBy('viewed_at', 'desc')->limit($limit);
    }

    /**
     * Track a product view for a user
     */
    public static function trackView($userId, $productId)
    {
        return static::updateOrCreate(
            [
                'user_id' => $userId,
                'product_id' => $productId,
            ],
            [
                'viewed_at' => now(),
            ]
        );
    }

    /**
     * Get recently viewed products for a user (only approved products)
     */
    public static function getRecentlyViewed($userId, $limit = 10, $excludeProductId = null)
    {
        $query = static::where('user_id', $userId)
            ->with(['product' => function($q) {
                $q->where('status', 'active')->where('approval_status', 'approved');
            }])
            ->orderBy('viewed_at', 'desc')
            ->limit($limit);

        if ($excludeProductId) {
            $query->where('product_id', '!=', $excludeProductId);
        }

        return $query->get()->pluck('product')->filter();
    }
}
