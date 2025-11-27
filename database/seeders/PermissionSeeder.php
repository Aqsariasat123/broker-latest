<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Policies
            ['name' => 'View Policies', 'slug' => 'policies.view', 'module' => 'policies'],
            ['name' => 'Create Policies', 'slug' => 'policies.create', 'module' => 'policies'],
            ['name' => 'Edit Policies', 'slug' => 'policies.edit', 'module' => 'policies'],
            ['name' => 'Delete Policies', 'slug' => 'policies.delete', 'module' => 'policies'],
            
            // Clients
            ['name' => 'View Clients', 'slug' => 'clients.view', 'module' => 'clients'],
            ['name' => 'Create Clients', 'slug' => 'clients.create', 'module' => 'clients'],
            ['name' => 'Edit Clients', 'slug' => 'clients.edit', 'module' => 'clients'],
            ['name' => 'Delete Clients', 'slug' => 'clients.delete', 'module' => 'clients'],
            
            // Users
            ['name' => 'View Users', 'slug' => 'users.view', 'module' => 'users'],
            ['name' => 'Create Users', 'slug' => 'users.create', 'module' => 'users'],
            ['name' => 'Edit Users', 'slug' => 'users.edit', 'module' => 'users'],
            ['name' => 'Delete Users', 'slug' => 'users.delete', 'module' => 'users'],
            
            // Reports
            ['name' => 'View Reports', 'slug' => 'reports.view', 'module' => 'reports'],
            ['name' => 'Export Reports', 'slug' => 'reports.export', 'module' => 'reports'],
            
            // Settings
            ['name' => 'Manage Settings', 'slug' => 'settings.manage', 'module' => 'settings'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['slug' => $permission['slug']],
                $permission
            );
        }

        // Assign permissions to roles
        // Admin gets all permissions
        $adminPermissions = Permission::all();
        foreach ($adminPermissions as $permission) {
            DB::table('role_permissions')->updateOrInsert(
                [
                    'role' => 'admin',
                    'permission_id' => $permission->id,
                ],
                [
                    'role' => 'admin',
                    'permission_id' => $permission->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // Support gets view permissions only
        $supportPermissions = Permission::where('slug', 'like', '%.view')->get();
        foreach ($supportPermissions as $permission) {
            DB::table('role_permissions')->updateOrInsert(
                [
                    'role' => 'support',
                    'permission_id' => $permission->id,
                ],
                [
                    'role' => 'support',
                    'permission_id' => $permission->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}

