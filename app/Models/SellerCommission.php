<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SellerCommission extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'order_id',
        'order_item_id',
        'product_id',
        'product_price',
        'quantity',
        'total_amount',
        'commission_rate',
        'commission_amount',
        'seller_amount',
        'payout_id',
        'status',
        'paid_at',
    ];

    protected $casts = [
        'product_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'seller_amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    // Relationships
    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function payout()
    {
        return $this->belongsTo(SellerPayout::class, 'payout_id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeRefunded($query)
    {
        return $query->where('status', 'refunded');
    }

    public function scopeUnpaid($query)
    {
        return $query->whereNull('payout_id')->where('status', 'pending');
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => '<span class="badge bg-warning">Pending</span>',
            'paid' => '<span class="badge bg-success">Paid</span>',
            'refunded' => '<span class="badge bg-danger">Refunded</span>',
        ];

        return $badges[$this->status] ?? '<span class="badge bg-secondary">Unknown</span>';
    }

    // Methods
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isPaid()
    {
        return $this->status === 'paid';
    }

    public function isRefunded()
    {
        return $this->status === 'refunded';
    }

    public function markAsPaid($payoutId = null)
    {
        $this->update([
            'status' => 'paid',
            'payout_id' => $payoutId,
            'paid_at' => now(),
        ]);
    }

    public function markAsRefunded()
    {
        $this->update([
            'status' => 'refunded',
            'payout_id' => null,
            'paid_at' => null,
        ]);
    }

    // Calculate commission
    public static function calculateCommission($amount, $commissionRate)
    {
        $commissionAmount = ($amount * $commissionRate) / 100;
        $sellerAmount = $amount - $commissionAmount;

        return [
            'commission_amount' => round($commissionAmount, 2),
            'seller_amount' => round($sellerAmount, 2),
        ];
    }
}
