<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find the super-admin role
        $role = Role::where('slug', 'super-admin')->first();

        // Create Super Admin
        Admin::create([
            'name' => 'Super Admin',
            'email' => 'super@gmail.com',
            'password' => Hash::make('password'),
            'phone' => '1234567890',
            'role_id' => $role ? $role->id : null,
            'is_active' => true,
            'status' => 'active',
        ]);
    }
}
