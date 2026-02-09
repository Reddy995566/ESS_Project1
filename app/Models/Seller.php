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
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'commission_rate' => 'decimal:2',
        'verification_documents' => 'array',
        'approved_at' => 'datetime',
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
        return $this->notifications()->create([
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
        ]);
    }
}
