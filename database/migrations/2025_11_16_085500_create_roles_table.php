<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->json('permissions')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Insert default roles
        DB::table('roles')->insert([
            [
                'name' => 'Super Admin',
                'slug' => 'super-admin',
                'permissions' => json_encode([
                    'dashboard.view',
                    'products.view', 'products.create', 'products.edit', 'products.delete',
                    'categories.view', 'categories.create', 'categories.edit', 'categories.delete',
                    'colors.view', 'colors.create', 'colors.edit', 'colors.delete',
                    'sizes.view', 'sizes.create', 'sizes.edit', 'sizes.delete',
                    'brands.view', 'brands.create', 'brands.edit', 'brands.delete',
                    'collections.view', 'collections.create', 'collections.edit', 'collections.delete',
                    'tags.view', 'tags.create', 'tags.edit', 'tags.delete',
                    'coupons.view', 'coupons.create', 'coupons.edit', 'coupons.delete',
                    'orders.view', 'orders.edit', 'orders.delete',
                    'customers.view', 'customers.edit', 'customers.delete',
                    'admins.view', 'admins.create', 'admins.edit', 'admins.delete',
                    'roles.view', 'roles.create', 'roles.edit', 'roles.delete',
                    'settings.view', 'settings.edit',
                    'logs.view',
                ]),
                'description' => 'Full access to all features',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Manager',
                'slug' => 'manager',
                'permissions' => json_encode([
                    'dashboard.view',
                    'products.view', 'products.create', 'products.edit',
                    'categories.view', 'categories.create', 'categories.edit',
                    'colors.view', 'colors.create', 'colors.edit',
                    'sizes.view', 'sizes.create', 'sizes.edit',
                    'brands.view', 'brands.create', 'brands.edit',
                    'collections.view', 'collections.create', 'collections.edit',
                    'tags.view', 'tags.create', 'tags.edit',
                    'coupons.view', 'coupons.create', 'coupons.edit',
                    'orders.view', 'orders.edit',
                    'customers.view',
                ]),
                'description' => 'Manage products, orders and customers',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Editor',
                'slug' => 'editor',
                'permissions' => json_encode([
                    'dashboard.view',
                    'products.view', 'products.edit',
                    'categories.view',
                    'colors.view',
                    'sizes.view',
                    'brands.view',
                    'collections.view',
                    'tags.view',
                    'coupons.view',
                    'orders.view',
                    'customers.view',
                ]),
                'description' => 'View and edit products only',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
