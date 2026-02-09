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

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function hasPermission($permission)
    {
        // Check both old array-based permissions and new relationship-based permissions
        if (in_array($permission, $this->permissions ?? [])) {
            return true;
        }
        
        return $this->permissions()->where('name', $permission)->exists();
    }

    public function givePermissionTo($permission)
    {
        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)->first();
        }
        
        if ($permission) {
            $this->permissions()->syncWithoutDetaching([$permission->id]);
        }
    }

    public function revokePermissionTo($permission)
    {
        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)->first();
        }
        
        if ($permission) {
            $this->permissions()->detach($permission->id);
        }
    }
}
