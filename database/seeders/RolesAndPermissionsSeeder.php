<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        /**
         * Worker permissions
         */
        $workerPermissions = [
            'create worker-profile',
            'edit worker-profile',
            'view worker-profile',
            'delete worker-profile',
        ];

        /**
         * Investor permissions
         */
        $investorPermissions = [
            'create investor-profile',
            'edit investor-profile',
            'view investor-profile',
            'delete investor-profile',
        ];

        /**
         * Admin permissions
         */
        $adminPermissions = [
            'manage users',
            'manage jobs',
        ];

        /**
         * All permissions
         */
        $permissions = array_merge($workerPermissions, $investorPermissions, $adminPermissions);

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        /**
         * Create roles
         */
        $admin   = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $worker  = Role::firstOrCreate(['name' => 'worker', 'guard_name' => 'web']);
        $investor = Role::firstOrCreate(['name' => 'investor', 'guard_name' => 'web']);

        /**
         * Assign permissions to roles
         */
        $admin->givePermissionTo(Permission::all());
        $worker->givePermissionTo($workerPermissions);
        $investor->givePermissionTo($investorPermissions);
    }
}
