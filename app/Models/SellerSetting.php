<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SellerSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'key',
        'value',
    ];

    // Relationships
    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    // Methods
    public static function get($sellerId, $key, $default = null)
    {
        $setting = self::where('seller_id', $sellerId)
            ->where('key', $key)
            ->first();

        return $setting ? $setting->value : $default;
    }

    public static function set($sellerId, $key, $value)
    {
        return self::updateOrCreate(
            [
                'seller_id' => $sellerId,
                'key' => $key,
            ],
            [
                'value' => $value,
            ]
        );
    }

    public static function has($sellerId, $key)
    {
        return self::where('seller_id', $sellerId)
            ->where('key', $key)
            ->exists();
    }

    public static function forget($sellerId, $key)
    {
        return self::where('seller_id', $sellerId)
            ->where('key', $key)
            ->delete();
    }

    public static function all($sellerId)
    {
        return self::where('seller_id', $sellerId)
            ->pluck('value', 'key')
            ->toArray();
    }
}
