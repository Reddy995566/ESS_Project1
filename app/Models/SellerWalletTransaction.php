<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SellerWalletTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'wallet_id',
        'transaction_id',
        'type',
        'category',
        'amount',
        'balance_before',
        'balance_after',
        'description',
        'metadata',
        'status',
        'order_id',
        'payout_id',
        'reference_type',
        'reference_id',
        'processed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'metadata' => 'array',
        'processed_at' => 'datetime',
    ];

    // Relationships
    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function wallet()
    {
        return $this->belongsTo(SellerWallet::class, 'wallet_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function payout()
    {
        return $this->belongsTo(SellerPayout::class);
    }

    public function reference()
    {
        return $this->morphTo();
    }

    // Accessors
    public function getFormattedAmountAttribute()
    {
        return '₹' . number_format($this->amount, 2);
    }

    public function getTypeIconAttribute()
    {
        return $this->type === 'credit' ? '💰' : '💸';
    }

    public function getCategoryIconAttribute()
    {
        $icons = [
            'order_commission' => '🛒',
            'withdrawal' => '🏦',
            'refund' => '↩️',
            'bonus' => '🎁',
            'penalty' => '⚠️',
            'adjustment' => '⚖️',
        ];

        return $icons[$this->category] ?? '💳';
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'pending' => 'yellow',
            'completed' => 'green',
            'failed' => 'red',
            'cancelled' => 'gray',
        ];

        return $colors[$this->status] ?? 'gray';
    }

    // Scopes
    public function scopeCredit($query)
    {
        return $query->where('type', 'credit');
    }

    public function scopeDebit($query)
    {
        return $query->where('type', 'debit');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeOfCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeRecent($query, $limit = 10)
    {
        return $query->latest()->limit($limit);
    }
}