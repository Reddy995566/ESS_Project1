<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SellerDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'document_type',
        'document_name',
        'file_path',
        'file_url',
        'file_id',
        'file_size',
        'mime_type',
        'verification_status',
        'rejection_reason',
        'verified_by',
        'verified_at',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'verified_at' => 'datetime',
    ];

    // Document types
    const DOCUMENT_TYPES = [
        'pan_card' => 'PAN Card',
        'gst_certificate' => 'GST Certificate',
        'business_registration' => 'Business Registration',
        'bank_statement' => 'Bank Statement',
        'address_proof' => 'Address Proof',
        'identity_proof' => 'Identity Proof',
        'cancelled_cheque' => 'Cancelled Cheque',
        'other' => 'Other Document',
    ];

    // Verification statuses
    const STATUS_PENDING = 'pending';
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
        return $query->where('verification_status', self::STATUS_PENDING);
    }

    public function scopeApproved($query)
    {
        return $query->where('verification_status', self::STATUS_APPROVED);
    }

    public function scopeRejected($query)
    {
        return $query->where('verification_status', self::STATUS_REJECTED);
    }

    // Accessors
    public function getDocumentTypeNameAttribute()
    {
        return self::DOCUMENT_TYPES[$this->document_type] ?? $this->document_type;
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            self::STATUS_PENDING => '<span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">Pending</span>',
            self::STATUS_APPROVED => '<span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Approved</span>',
            self::STATUS_REJECTED => '<span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">Rejected</span>',
        ];

        return $badges[$this->verification_status] ?? '';
    }

    public function getFileSizeFormattedAttribute()
    {
        if (!$this->file_size) return 'Unknown';
        
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    // Methods
    public function isPending()
    {
        return $this->verification_status === self::STATUS_PENDING;
    }

    public function isApproved()
    {
        return $this->verification_status === self::STATUS_APPROVED;
    }

    public function isRejected()
    {
        return $this->verification_status === self::STATUS_REJECTED;
    }

    public function approve($adminId = null, $notes = null)
    {
        $this->update([
            'verification_status' => self::STATUS_APPROVED,
            'verified_by' => $adminId,
            'verified_at' => now(),
            'rejection_reason' => null,
        ]);

        // Log activity
        $this->seller->logActivity('document_approved', "Document {$this->document_type_name} approved", 'SellerDocument', $this->id);
    }

    public function reject($reason, $adminId = null)
    {
        $this->update([
            'verification_status' => self::STATUS_REJECTED,
            'verified_by' => $adminId,
            'verified_at' => now(),
            'rejection_reason' => $reason,
        ]);

        // Log activity
        $this->seller->logActivity('document_rejected', "Document {$this->document_type_name} rejected: {$reason}", 'SellerDocument', $this->id);
    }
}
