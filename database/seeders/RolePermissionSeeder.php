<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Create permissions (use firstOrCreate to avoid duplicates)
        $permissions = [
            'view-applicants',
            'verify-documents', 
            'generate-certificates',
            'send-certificates',
            'manage-templates',
            'view-audit-logs',
            'manage-users',
            'manage-roles'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles (use firstOrCreate to avoid duplicates)
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
        $verifier = Role::firstOrCreate(['name' => 'Verifier']);
        $certificateIssuer = Role::firstOrCreate(['name' => 'Certificate Issuer']);

        // Assign permissions to roles (sync will replace existing permissions)
        $superAdmin->syncPermissions(Permission::all());
        
        $verifier->syncPermissions([
            'view-applicants',
            'verify-documents',
            'view-audit-logs'
        ]);
        
        $certificateIssuer->syncPermissions([
            'view-applicants',
            'generate-certificates', 
            'send-certificates',
            'view-audit-logs'
        ]);

        // Create default admin user (use firstOrCreate to avoid duplicates)
        $admin = User::firstOrCreate(
            ['email' => 'admin@certificate-system.com'],
            [
                'name' => 'System Administrator',
                'password' => bcrypt('admin123'),
                'email_verified_at' => now()
            ]
        );

        $admin->assignRole('Super Admin');
    }
}
