<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Clear cached roles/permissions (important for Spatie)
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Roles only (no permissions for now)
        Role::firstOrCreate([
            'name' => 'superadmin',
            'guard_name' => 'web',
        ]);

        Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        Role::firstOrCreate([
            'name' => 'user',
            'guard_name' => 'web',
        ]);
    }
}
