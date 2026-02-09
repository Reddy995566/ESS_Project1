<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SellerBankDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'account_holder_name',
        'account_number',
        'ifsc_code',
        'bank_name',
        'branch_name',
        'account_type',
        'upi_id',
        'is_verified',
        'verified_at',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
    ];

    protected $hidden = [
        'account_number', // Hide full account number
    ];

    // Relationships
    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    // Accessors
    public function getMaskedAccountNumberAttribute()
    {
        if (!$this->account_number) {
            return null;
        }
        
        $length = strlen($this->account_number);
        if ($length <= 4) {
            return str_repeat('*', $length);
        }
        
        return str_repeat('*', $length - 4) . substr($this->account_number, -4);
    }

    public function getVerificationStatusBadgeAttribute()
    {
        return $this->is_verified 
            ? '<span class="badge bg-success">Verified</span>' 
            : '<span class="badge bg-warning">Pending Verification</span>';
    }

    // Methods
    public function isVerified()
    {
        return $this->is_verified;
    }

    public function markAsVerified()
    {
        $this->update([
            'is_verified' => true,
            'verified_at' => now(),
        ]);
    }
}
