<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SellerWallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'balance',
        'pending_balance',
        'total_earned',
        'total_withdrawn',
        'is_active',
        'last_transaction_at',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'pending_balance' => 'decimal:2',
        'total_earned' => 'decimal:2',
        'total_withdrawn' => 'decimal:2',
        'is_active' => 'boolean',
        'last_transaction_at' => 'datetime',
    ];

    // Relationships
    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function transactions()
    {
        return $this->hasMany(SellerWalletTransaction::class, 'wallet_id');
    }

    // Methods
    public function addFunds($amount, $category, $description, $metadata = null, $orderId = null)
    {
        return $this->createTransaction('credit', $category, $amount, $description, $metadata, $orderId);
    }

    public function deductFunds($amount, $category, $description, $metadata = null, $orderId = null)
    {
        if ($this->balance < $amount) {
            throw new \Exception('Insufficient wallet balance');
        }
        
        return $this->createTransaction('debit', $category, $amount, $description, $metadata, $orderId);
    }

    private function createTransaction($type, $category, $amount, $description, $metadata = null, $orderId = null)
    {
        $balanceBefore = $this->balance;
        
        if ($type === 'credit') {
            $balanceAfter = $balanceBefore + $amount;
        } else {
            $balanceAfter = $balanceBefore - $amount;
        }

        // Create transaction
        $transaction = $this->transactions()->create([
            'seller_id' => $this->seller_id,
            'transaction_id' => 'TXN_' . strtoupper(uniqid()),
            'type' => $type,
            'category' => $category,
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
            'description' => $description,
            'metadata' => $metadata,
            'order_id' => $orderId,
            'status' => 'completed',
            'processed_at' => now(),
        ]);

        // Update wallet balance
        $this->update([
            'balance' => $balanceAfter,
            'last_transaction_at' => now(),
        ]);

        // Update totals
        if ($type === 'credit' && in_array($category, ['order_commission', 'bonus'])) {
            $this->increment('total_earned', $amount);
        } elseif ($type === 'debit' && $category === 'withdrawal') {
            $this->increment('total_withdrawn', $amount);
        }

        return $transaction;
    }

    public function getAvailableBalanceAttribute()
    {
        return $this->balance - $this->pending_balance;
    }

    public function canWithdraw($amount)
    {
        return $this->available_balance >= $amount;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}