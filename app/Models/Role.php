<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'permissions',
        'description',
    ];

    protected $casts = [
        'permissions' => 'array',
    ];

    public function admins()
    {
        return $this->hasMany(Admin::class);
    }

    public function hasPermission($permission)
    {
        return in_array($permission, $this->permissions ?? []);
    }
}
