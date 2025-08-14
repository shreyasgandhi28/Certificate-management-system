<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateSuperAdmin extends Command
{
    protected $signature = 'app:create-super-admin';
    protected $description = 'Create a super admin user';

    public function handle()
    {
        // Create or get Super Admin role with all permissions
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin']);
        
        // Create admin user
        $user = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('admin123'),
                'email_verified_at' => now(),
            ]
        );

        // Assign Super Admin role
        $user->assignRole('Super Admin');

        $this->info('Super Admin user created successfully!');
        $this->info('Email: admin@example.com');
        $this->info('Password: admin123');
        $this->info('Role: Super Admin');
    }
}
