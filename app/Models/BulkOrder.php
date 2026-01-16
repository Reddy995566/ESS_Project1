<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BulkOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'business_name',
        'email',
        'phone',
        'city_state',
        'products_required',
        'quantity_required',
        'status',
    ];
}
