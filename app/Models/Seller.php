<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Seller extends Authenticatable
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'business_name',
        'business_type',
        'business_registration_number',
        'gst_number',
        'pan_number',
        'business_email',
        'business_phone',
        'business_address',
        'business_city',
        'business_state',
        'business_pincode',
        'logo',
        'banner',
        'description',
        'commission_rate',
        'status',
        'is_verified',
        'verification_documents',
        'approved_by',
        'approved_at',
        'two_factor_enabled',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'commission_rate' => 'decimal:2',
        'verification_documents' => 'array',
        'approved_at' => 'datetime',
        'two_factor_enabled' => 'boolean',
        'two_factor_recovery_codes' => 'array',
        'two_factor_confirmed_at' => 'datetime',
    ];

    protected $hidden = [
        'remember_token',
    ];

    // Override getAuthPassword to use user's password
    public function getAuthPassword()
    {
        return $this->user->password;
    }

    // Override getAuthIdentifierName
    public function getAuthIdentifierName()
    {
        return 'id';
    }

    // Override getAuthIdentifier
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(Admin::class, 'approved_by');
    }

    public function bankDetails()
    {
        return $this->hasOne(SellerBankDetail::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function orders()
    {
        return $this->hasManyThrough(Order::class, OrderItem::class, 'seller_id', 'id', 'id', 'order_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payouts()
    {
        return $this->hasMany(SellerPayout::class);
    }

    public function commissions()
    {
        return $this->hasMany(SellerCommission::class);
    }

    public function notifications()
    {
        return $this->hasMany(SellerNotification::class);
    }

    public function settings()
    {
        return $this->hasMany(SellerSetting::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(SellerActivityLog::class);
    }

    public function wallet()
    {
        return $this->hasOne(SellerWallet::class);
    }

    public function walletTransactions()
    {
        return $this->hasMany(SellerWalletTransaction::class);
    }

    public function documents()
    {
        return $this->hasMany(SellerDocument::class);
    }

    public function verifications()
    {
        return $this->hasMany(SellerVerification::class);
    }

    // Verification methods
    public function getVerificationProgress()
    {
        $requiredVerifications = [
            'identity' => 'Identity Verification',
            'business' => 'Business Verification', 
            'bank_account' => 'Bank Account Verification',
            'address' => 'Address Verification',
        ];

        $progress = [];
        $totalRequired = count($requiredVerifications);
        $completed = 0;

        foreach ($requiredVerifications as $type => $name) {
            $verification = $this->verifications()->where('verification_type', $type)->first();
            $status = $verification ? $verification->status : 'not_started';
            
            if ($status === 'approved') {
                $completed++;
            }
            
            $progress[$type] = [
                'name' => $name,
                'status' => $status,
                'verification' => $verification,
            ];
        }

        return [
            'progress' => $progress,
            'completion_percentage' => round(($completed / $totalRequired) * 100),
            'completed' => $completed,
            'total' => $totalRequired,
            'is_complete' => $completed === $totalRequired,
        ];
    }

    public function checkVerificationStatus()
    {
        $progress = $this->getVerificationProgress();
        
        // Update seller verification status based on progress
        $newStatus = $this->status;
        
        if ($progress['is_complete']) {
            $newStatus = 'active';
        } elseif ($progress['completed'] > 0) {
            $newStatus = 'pending'; // Partial verification
        }
        
        if ($newStatus !== $this->status) {
            $this->update(['status' => $newStatus]);
            
            // Send notification
            $this->sendNotification(
                'verification_updated',
                'Verification Status Updated',
                "Your verification status has been updated. Progress: {$progress['completed']}/{$progress['total']} completed.",
                json_encode($progress)
            );
        }
        
        return $progress;
    }

    public function getRequiredDocuments()
    {
        return [
            'pan_card' => [
                'name' => 'PAN Card',
                'required' => true,
                'description' => 'Clear photo of your PAN card',
            ],
            'gst_certificate' => [
                'name' => 'GST Certificate',
                'required' => !empty($this->gst_number),
                'description' => 'GST registration certificate (if applicable)',
            ],
            'business_registration' => [
                'name' => 'Business Registration',
                'required' => $this->business_type !== 'individual',
                'description' => 'Business registration certificate or partnership deed',
            ],
            'bank_statement' => [
                'name' => 'Bank Statement',
                'required' => true,
                'description' => 'Recent bank statement (last 3 months)',
            ],
            'cancelled_cheque' => [
                'name' => 'Cancelled Cheque',
                'required' => true,
                'description' => 'Cancelled cheque or bank account proof',
            ],
            'address_proof' => [
                'name' => 'Address Proof',
                'required' => true,
                'description' => 'Utility bill, rent agreement, or address proof',
            ],
        ];
    }

    public function getDocumentStatus($documentType)
    {
        $document = $this->documents()->where('document_type', $documentType)->latest()->first();
        
        if (!$document) {
            return 'not_uploaded';
        }
        
        return $document->verification_status;
    }

    public function isDocumentRequired($documentType)
    {
        $requiredDocs = $this->getRequiredDocuments();
        return isset($requiredDocs[$documentType]) && $requiredDocs[$documentType]['required'];
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeApproved($query)
    {
        return $query->whereIn('status', ['approved', 'active']);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    // Accessors
    public function getFullAddressAttribute()
    {
        return "{$this->business_address}, {$this->business_city}, {$this->business_state} - {$this->business_pincode}";
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => '<span class="badge bg-warning">Pending</span>',
            'approved' => '<span class="badge bg-success">Approved</span>',
            'active' => '<span class="badge bg-success">Active</span>',
            'suspended' => '<span class="badge bg-danger">Suspended</span>',
            'rejected' => '<span class="badge bg-danger">Rejected</span>',
        ];

        return $badges[$this->status] ?? '<span class="badge bg-secondary">Unknown</span>';
    }

    // Methods
    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isApproved()
    {
        return in_array($this->status, ['approved', 'active']);
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isSuspended()
    {
        return $this->status === 'suspended';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    public function getTotalSales()
    {
        return $this->orderItems()->sum('seller_amount');
    }

    public function getTotalOrders()
    {
        return $this->orderItems()->distinct('order_id')->count('order_id');
    }

    public function getTotalProducts()
    {
        return $this->products()->count();
    }

    public function getActiveProducts()
    {
        return $this->products()->where('status', 'active')->count();
    }

    public function getPendingPayouts()
    {
        return $this->payouts()->where('status', 'pending')->sum('net_amount');
    }

    public function getPendingPayout()
    {
        // Calculate total earned commissions
        $totalEarned = $this->commissions()->sum('seller_amount');
        
        // Calculate total already paid out
        $totalPaidOut = $this->payouts()->whereIn('status', ['completed', 'processing'])->sum('net_amount');
        
        // Calculate pending payout requests
        $pendingRequests = $this->payouts()->where('status', 'pending')->sum('net_amount');
        
        // Available balance = Total earned - Total paid out - Pending requests
        return max(0, $totalEarned - $totalPaidOut - $pendingRequests);
    }

    public function getSetting($key, $default = null)
    {
        $setting = $this->settings()->where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public function setSetting($key, $value)
    {
        return $this->settings()->updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    public function logActivity($action, $description, $modelType = null, $modelId = null)
    {
        return $this->activityLogs()->create([
            'action' => $action,
            'description' => $description,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public function sendNotification($type, $title, $message, $data = null)
    {
        // Create in-app notification
        $notification = $this->notifications()->create([
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
        ]);

        // Check notification preferences and send email/SMS if enabled
        $this->sendNotificationBasedOnPreferences($type, $title, $message, $data);

        return $notification;
    }

    public function sendNotificationBasedOnPreferences($type, $title, $message, $data = null)
    {
        $settings = $this->settings()->pluck('value', 'key')->toArray();
        
        // Map notification types to email preference keys only
        $emailPreferenceMap = [
            'order_placed' => 'email_new_order',
            'product_approved' => 'email_product_approved',
            'product_rejected' => 'email_product_rejected',
            'payout_processed' => 'email_payout_processed',
            'low_stock' => 'email_low_stock',
            'new_review' => 'email_new_review',
        ];

        // Send email notification if enabled
        if (isset($emailPreferenceMap[$type])) {
            $emailEnabled = ($settings[$emailPreferenceMap[$type]] ?? '1') === '1';
            if ($emailEnabled) {
                $this->sendEmailNotification($type, $title, $message, $data);
            }
        }
    }

    private function sendEmailNotification($type, $title, $message, $data = null)
    {
        try {
            // You can implement actual email sending here
            // For now, just log it
            \Log::info("Email notification sent to seller {$this->id}: {$title}");
            
            // Example: Mail::to($this->user->email)->send(new SellerNotificationMail($type, $title, $message, $data));
        } catch (\Exception $e) {
            \Log::error("Failed to send email notification to seller {$this->id}: " . $e->getMessage());
        }
    }
}
