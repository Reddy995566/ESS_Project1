<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SellerActivityLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'seller_id',
        'action',
        'description',
        'model_type',
        'model_id',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // Relationships
    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function model()
    {
        return $this->morphTo();
    }

    // Scopes
    public function scopeRecent($query, $limit = 50)
    {
        return $query->latest('created_at')->limit($limit);
    }

    public function scopeOfAction($query, $action)
    {
        return $query->where('action', $action);
    }

    public function scopeOfModel($query, $modelType, $modelId = null)
    {
        $query->where('model_type', $modelType);
        
        if ($modelId) {
            $query->where('model_id', $modelId);
        }
        
        return $query;
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year);
    }

    // Accessors
    public function getActionBadgeAttribute()
    {
        $badges = [
            'login' => '<span class="badge bg-success">Login</span>',
            'logout' => '<span class="badge bg-secondary">Logout</span>',
            'product_created' => '<span class="badge bg-success">Product Created</span>',
            'product_updated' => '<span class="badge bg-info">Product Updated</span>',
            'product_deleted' => '<span class="badge bg-danger">Product Deleted</span>',
            'order_updated' => '<span class="badge bg-info">Order Updated</span>',
            'settings_updated' => '<span class="badge bg-info">Settings Updated</span>',
            'profile_updated' => '<span class="badge bg-info">Profile Updated</span>',
        ];

        return $badges[$this->action] ?? '<span class="badge bg-secondary">' . ucfirst($this->action) . '</span>';
    }

    public function getBrowserAttribute()
    {
        $userAgent = $this->user_agent;
        
        if (str_contains($userAgent, 'Chrome')) return 'Chrome';
        if (str_contains($userAgent, 'Firefox')) return 'Firefox';
        if (str_contains($userAgent, 'Safari')) return 'Safari';
        if (str_contains($userAgent, 'Edge')) return 'Edge';
        if (str_contains($userAgent, 'Opera')) return 'Opera';
        
        return 'Unknown';
    }

    public function getDeviceAttribute()
    {
        $userAgent = $this->user_agent;
        
        if (str_contains($userAgent, 'Mobile')) return 'Mobile';
        if (str_contains($userAgent, 'Tablet')) return 'Tablet';
        
        return 'Desktop';
    }

    // Static methods for logging
    public static function log($sellerId, $action, $description, $modelType = null, $modelId = null)
    {
        return self::create([
            'seller_id' => $sellerId,
            'action' => $action,
            'description' => $description,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);
    }
}
