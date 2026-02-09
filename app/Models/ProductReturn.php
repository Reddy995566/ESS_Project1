<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductReturn extends Model
{
    protected $table = 'returns';
    
    protected $fillable = [
        'return_number',
        'order_id',
        'order_item_id',
        'user_id',
        'seller_id',
        'product_id',
        'quantity',
        'amount',
        'reason',
        'reason_details',
        'images',
        'status',
        'refund_method',
        'refund_amount',
        'requested_at',
        'approved_at',
        'rejected_at',
        'picked_up_at',
        'refunded_at',
        'admin_notes',
        'seller_notes',
        'rejection_reason',
        'pickup_address',
        'tracking_number',
        'processed_by_admin',
        'processed_by_seller',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'refund_amount' => 'decimal:2',
        'images' => 'array',
        'requested_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'picked_up_at' => 'datetime',
        'refunded_at' => 'datetime',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function processedByAdmin()
    {
        return $this->belongsTo(Admin::class, 'processed_by_admin');
    }

    public function processedBySeller()
    {
        return $this->belongsTo(Seller::class, 'processed_by_seller');
    }

    // Helper methods
    public static function generateReturnNumber()
    {
        do {
            $number = 'RET-' . strtoupper(uniqid());
        } while (self::where('return_number', $number)->exists());

        return $number;
    }

    public function canBeRequested()
    {
        // Check if order is delivered and within 3 days
        if ($this->order->status !== 'delivered') {
            return false;
        }

        $deliveredAt = $this->order->delivered_at;
        if (!$deliveredAt) {
            return false;
        }

        $threeDaysAgo = now()->subDays(3);
        return $deliveredAt->gte($threeDaysAgo);
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'approved' => 'bg-blue-100 text-blue-800',
            'rejected' => 'bg-red-100 text-red-800',
            'picked_up' => 'bg-indigo-100 text-indigo-800',
            'refunded' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-gray-100 text-gray-800',
        ];

        return $badges[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    public function getReasonLabelAttribute()
    {
        $reasons = [
            'defective' => 'Defective Product',
            'wrong_item' => 'Wrong Item Received',
            'size_issue' => 'Size Issue',
            'quality_issue' => 'Quality Issue',
            'not_as_described' => 'Not as Described',
            'damaged_shipping' => 'Damaged in Shipping',
            'changed_mind' => 'Changed Mind',
            'other' => 'Other',
        ];

        return $reasons[$this->reason] ?? 'Unknown';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($return) {
            if (!$return->return_number) {
                $return->return_number = self::generateReturnNumber();
            }
            if (!$return->requested_at) {
                $return->requested_at = now();
            }
        });
    }
}