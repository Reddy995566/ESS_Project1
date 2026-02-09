<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SellerNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'type',
        'title',
        'message',
        'data',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    // Relationships
    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function scopeRecent($query, $limit = 10)
    {
        return $query->latest()->limit($limit);
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Methods
    public function markAsRead()
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
    }

    public function markAsUnread()
    {
        $this->update([
            'is_read' => false,
            'read_at' => null,
        ]);
    }

    public function isRead()
    {
        return $this->is_read;
    }

    public function isUnread()
    {
        return !$this->is_read;
    }

    // Get icon based on notification type
    public function getIconAttribute()
    {
        $icons = [
            'order_placed' => '🛍️',
            'product_approved' => '✅',
            'product_rejected' => '❌',
            'payout_processed' => '💰',
            'low_stock' => '📦',
            'new_review' => '⭐',
            'order_status_changed' => '🔄',
            'admin_announcement' => '📢',
        ];

        return $icons[$this->type] ?? '🔔';
    }

    // Get color based on notification type
    public function getColorAttribute()
    {
        $colors = [
            'order_placed' => 'success',
            'product_approved' => 'success',
            'product_rejected' => 'danger',
            'payout_processed' => 'success',
            'low_stock' => 'warning',
            'new_review' => 'info',
            'order_status_changed' => 'info',
            'admin_announcement' => 'primary',
        ];

        return $colors[$this->type] ?? 'secondary';
    }
}
