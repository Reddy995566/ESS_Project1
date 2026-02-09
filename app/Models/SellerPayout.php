<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SellerPayout extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'payout_number',
        'amount',
        'commission_amount',
        'net_amount',
        'period_start',
        'period_end',
        'status',
        'payment_method',
        'transaction_id',
        'transaction_date',
        'request_date',
        'notes',
        'processed_by',
        'processed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'period_start' => 'date',
        'period_end' => 'date',
        'transaction_date' => 'datetime',
        'request_date' => 'datetime',
        'processed_at' => 'datetime',
    ];

    // Relationships
    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function processedBy()
    {
        return $this->belongsTo(Admin::class, 'processed_by');
    }

    public function commissions()
    {
        return $this->hasMany(SellerCommission::class, 'payout_id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => '<span class="badge bg-warning">Pending</span>',
            'processing' => '<span class="badge bg-info">Processing</span>',
            'completed' => '<span class="badge bg-success">Completed</span>',
            'failed' => '<span class="badge bg-danger">Failed</span>',
            'cancelled' => '<span class="badge bg-secondary">Cancelled</span>',
        ];

        return $badges[$this->status] ?? '<span class="badge bg-secondary">Unknown</span>';
    }

    public function getPeriodAttribute()
    {
        return $this->period_start->format('M d, Y') . ' - ' . $this->period_end->format('M d, Y');
    }

    // Methods
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isProcessing()
    {
        return $this->status === 'processing';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isFailed()
    {
        return $this->status === 'failed';
    }

    public function markAsProcessing()
    {
        $this->update(['status' => 'processing']);
    }

    public function markAsCompleted($transactionId = null, $transactionDate = null)
    {
        $this->update([
            'status' => 'completed',
            'transaction_id' => $transactionId,
            'transaction_date' => $transactionDate ?? now(),
            'processed_at' => now(),
        ]);

        // Mark all commissions as paid
        $this->commissions()->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);
    }

    public function markAsFailed($notes = null)
    {
        $this->update([
            'status' => 'failed',
            'notes' => $notes,
        ]);
    }

    public function cancel($notes = null)
    {
        $this->update([
            'status' => 'cancelled',
            'notes' => $notes,
        ]);
    }

    // Generate unique payout number
    public static function generatePayoutNumber()
    {
        $prefix = 'PO';
        $date = now()->format('Ymd');
        $lastPayout = self::whereDate('created_at', today())->latest()->first();
        $sequence = $lastPayout ? (int) substr($lastPayout->payout_number, -4) + 1 : 1;
        
        return $prefix . $date . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
}
