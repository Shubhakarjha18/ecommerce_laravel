<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Map out permissions grouped by sidebar modules
        $modulesWithPermissions = [
            'dashboard' => ['view-dashboard'],
            'media-manager' => ['view-media', 'upload-media', 'delete-media'],
            'banner' => ['view-banner', 'create-banner', 'edit-banner', 'delete-banner'],
            'category' => ['view-category', 'create-category', 'edit-category', 'delete-category'],
            'product' => ['view-product', 'create-product', 'edit-product', 'delete-product'],
            'brand' => ['view-brand', 'create-brand', 'edit-brand', 'delete-brand'],
            'shipping' => ['view-shipping', 'create-shipping', 'edit-shipping', 'delete-shipping'],
            'order' => ['view-order', 'edit-order', 'delete-order'],
            'review' => ['view-review', 'edit-review', 'delete-review'],
            'post' => ['view-post', 'create-post', 'edit-post', 'delete-post'],
            'post-category' => ['view-post-category', 'create-post-category', 'edit-post-category', 'delete-post-category'],
            'post-tag' => ['view-post-tag', 'create-post-tag', 'edit-post-tag', 'delete-post-tag'],
            'comment' => ['view-comment', 'edit-comment', 'delete-comment'],
            'coupon' => ['view-coupon', 'create-coupon', 'edit-coupon', 'delete-coupon'],
            'user' => ['view-user', 'create-user', 'edit-user', 'delete-user'],
            'setting' => ['view-setting', 'edit-setting'],
        ];

        // 2. Create permissions in the database
        $allPermissions = [];
        foreach ($modulesWithPermissions as $module => $permissions) {
            foreach ($permissions as $permission) {
                Permission::firstOrCreate([
                    'name' => $permission,
                    'guard_name' => 'web',
                ]);
                $allPermissions[] = $permission;
            }
        }

        // 3. Ensure base roles exist
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $userRole  = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
        $superadminRole = Role::firstOrCreate(['name' => 'superadmin', 'guard_name' => 'web']);

        // 4. Assign permissions to roles

        // Superadmin gets all permissions
        $superadminRole->syncPermissions($allPermissions);
        // Admin gets all operational permissions
        $adminRole->syncPermissions($allPermissions);

        // Standard user gets basic customer permissions (e.g. view dashboard, orders, reviews, comments)
        $userRole->syncPermissions([
            'view-dashboard',
            'view-order',
            'view-review',
            'view-comment',
        ]);
    }
}
