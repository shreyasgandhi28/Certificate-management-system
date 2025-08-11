<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or get Super Admin role with all permissions
        $superAdminRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Super Admin']);
        
        // Get all permissions and sync them with Super Admin role
        $permissions = \Spatie\Permission\Models\Permission::all();
        $superAdminRole->syncPermissions($permissions);

        // Create admin user
        $user = \App\Models\User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test Admin',
                'password' => bcrypt('test'),
                'email_verified_at' => now(),
            ]
        );

        // Remove any existing roles and assign Super Admin role
        $user->syncRoles(['Super Admin']);

        // Ensure user has all permissions directly as well
        $user->syncPermissions($permissions);

        $this->command->info('Super Admin user created with all permissions!');
        $this->command->info('Email: test@example.com');
        $this->command->info('Password: test');
        $this->command->info('Role: Super Admin');
    }
}
