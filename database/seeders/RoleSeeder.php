<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Create roles
        $adminRole = Role::updateOrCreate(['name' => 'admin']);
        $ocaRole = Role::updateOrCreate(['name' => 'oca']);
        $clubRole = Role::updateOrCreate(['name' => 'club']);

        // Create specific club roles
        $buccRole = Role::updateOrCreate(['name' => 'bucc']);
        $robuRole = Role::updateOrCreate(['name' => 'robu']);
        $buacRole = Role::updateOrCreate(['name' => 'buac']);
        $bizbeeRole = Role::updateOrCreate(['name' => 'bizbee']);

        // Create permissions
        $permissions = [
            'manage_users',
            'manage_roles',
            'manage_clubs',
            'manage_venues',
            'manage_events',
            'approve_events',
            'create_events',
            'view_events',
            'chat'
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(['name' => $permission]);
        }

        // Assign permissions to roles
        $adminRole->syncPermissions([
            'manage_users',
            'manage_roles',
            'manage_clubs',
            'manage_venues',
            'manage_events',
            'approve_events',
            'view_events'
        ]);

        $ocaRole->syncPermissions([
            'manage_clubs',
            'manage_venues',
            'manage_events',
            'approve_events',
            'view_events'
        ]);

        // Assign permissions to club roles
        $clubPermissions = [
            'create_events',
            'view_events',
            'chat'
        ];

        $clubRole->syncPermissions($clubPermissions);
        $buccRole->syncPermissions($clubPermissions);
        $robuRole->syncPermissions($clubPermissions);
        $buacRole->syncPermissions($clubPermissions);
        $bizbeeRole->syncPermissions($clubPermissions);
    }
}
