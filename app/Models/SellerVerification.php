<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SellerVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'verification_type',
        'status',
        'verification_data',
        'notes',
        'rejection_reason',
        'verified_by',
        'verified_at',
        'expires_at',
    ];

    protected $casts = [
        'verification_data' => 'array',
        'verified_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    // Verification types
    const TYPE_IDENTITY = 'identity';
    const TYPE_BUSINESS = 'business';
    const TYPE_BANK_ACCOUNT = 'bank_account';
    const TYPE_ADDRESS = 'address';
    const TYPE_PHONE = 'phone';
    const TYPE_EMAIL = 'email';

    // Statuses
    const STATUS_PENDING = 'pending';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    // Relationships
    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function verifiedBy()
    {
        return $this->belongsTo(Admin::class, 'verified_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    // Methods
    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isApproved()
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isRejected()
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function approve($adminId = null, $notes = null)
    {
        $this->update([
            'status' => self::STATUS_APPROVED,
            'verified_by' => $adminId,
            'verified_at' => now(),
            'notes' => $notes,
            'rejection_reason' => null,
        ]);

        // Check if all verifications are complete
        $this->seller->checkVerificationStatus();
    }

    public function reject($reason, $adminId = null)
    {
        $this->update([
            'status' => self::STATUS_REJECTED,
            'verified_by' => $adminId,
            'verified_at' => now(),
            'rejection_reason' => $reason,
        ]);

        // Update seller verification status
        $this->seller->checkVerificationStatus();
    }
}
