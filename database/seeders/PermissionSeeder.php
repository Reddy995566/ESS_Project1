<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Dashboard
            ['name' => 'dashboard.view', 'display_name' => 'View Dashboard', 'module' => 'dashboard'],
            
            // Products
            ['name' => 'products.view', 'display_name' => 'View Products', 'module' => 'products'],
            ['name' => 'products.create', 'display_name' => 'Create Products', 'module' => 'products'],
            ['name' => 'products.edit', 'display_name' => 'Edit Products', 'module' => 'products'],
            ['name' => 'products.delete', 'display_name' => 'Delete Products', 'module' => 'products'],
            ['name' => 'products.approve', 'display_name' => 'Approve Products', 'module' => 'products'],
            
            // Orders
            ['name' => 'orders.view', 'display_name' => 'View Orders', 'module' => 'orders'],
            ['name' => 'orders.edit', 'display_name' => 'Edit Orders', 'module' => 'orders'],
            ['name' => 'orders.delete', 'display_name' => 'Delete Orders', 'module' => 'orders'],
            ['name' => 'orders.refund', 'display_name' => 'Process Refunds', 'module' => 'orders'],
            
            // Sellers
            ['name' => 'sellers.view', 'display_name' => 'View Sellers', 'module' => 'sellers'],
            ['name' => 'sellers.create', 'display_name' => 'Create Sellers', 'module' => 'sellers'],
            ['name' => 'sellers.edit', 'display_name' => 'Edit Sellers', 'module' => 'sellers'],
            ['name' => 'sellers.delete', 'display_name' => 'Delete Sellers', 'module' => 'sellers'],
            ['name' => 'sellers.approve', 'display_name' => 'Approve Sellers', 'module' => 'sellers'],
            ['name' => 'sellers.suspend', 'display_name' => 'Suspend Sellers', 'module' => 'sellers'],
            
            // Payouts
            ['name' => 'payouts.view', 'display_name' => 'View Payouts', 'module' => 'payouts'],
            ['name' => 'payouts.approve', 'display_name' => 'Approve Payouts', 'module' => 'payouts'],
            ['name' => 'payouts.reject', 'display_name' => 'Reject Payouts', 'module' => 'payouts'],
            ['name' => 'payouts.process', 'display_name' => 'Process Payouts', 'module' => 'payouts'],
            
            // Users
            ['name' => 'users.view', 'display_name' => 'View Users', 'module' => 'users'],
            ['name' => 'users.create', 'display_name' => 'Create Users', 'module' => 'users'],
            ['name' => 'users.edit', 'display_name' => 'Edit Users', 'module' => 'users'],
            ['name' => 'users.delete', 'display_name' => 'Delete Users', 'module' => 'users'],
            ['name' => 'users.ban', 'display_name' => 'Ban Users', 'module' => 'users'],
            
            // Categories & Catalog
            ['name' => 'catalog.view', 'display_name' => 'View Catalog', 'module' => 'catalog'],
            ['name' => 'catalog.manage', 'display_name' => 'Manage Catalog', 'module' => 'catalog'],
            
            // Reviews
            ['name' => 'reviews.view', 'display_name' => 'View Reviews', 'module' => 'reviews'],
            ['name' => 'reviews.moderate', 'display_name' => 'Moderate Reviews', 'module' => 'reviews'],
            ['name' => 'reviews.delete', 'display_name' => 'Delete Reviews', 'module' => 'reviews'],
            
            // Returns
            ['name' => 'returns.view', 'display_name' => 'View Returns', 'module' => 'returns'],
            ['name' => 'returns.process', 'display_name' => 'Process Returns', 'module' => 'returns'],
            
            // Content Management
            ['name' => 'content.view', 'display_name' => 'View Content', 'module' => 'content'],
            ['name' => 'content.manage', 'display_name' => 'Manage Content', 'module' => 'content'],
            
            // Settings
            ['name' => 'settings.view', 'display_name' => 'View Settings', 'module' => 'settings'],
            ['name' => 'settings.edit', 'display_name' => 'Edit Settings', 'module' => 'settings'],
            
            // Admin Management
            ['name' => 'admins.view', 'display_name' => 'View Admins', 'module' => 'admins'],
            ['name' => 'admins.create', 'display_name' => 'Create Admins', 'module' => 'admins'],
            ['name' => 'admins.edit', 'display_name' => 'Edit Admins', 'module' => 'admins'],
            ['name' => 'admins.delete', 'display_name' => 'Delete Admins', 'module' => 'admins'],
            
            // Roles & Permissions
            ['name' => 'roles.view', 'display_name' => 'View Roles', 'module' => 'roles'],
            ['name' => 'roles.manage', 'display_name' => 'Manage Roles', 'module' => 'roles'],
            
            // Analytics & Reports
            ['name' => 'analytics.view', 'display_name' => 'View Analytics', 'module' => 'analytics'],
            ['name' => 'reports.view', 'display_name' => 'View Reports', 'module' => 'reports'],
            ['name' => 'reports.export', 'display_name' => 'Export Reports', 'module' => 'reports'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }
    }
}