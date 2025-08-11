<?php

namespace Database\Seeders;

use App\Models\CertificateTemplate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TemplateSeeder extends Seeder
{
    public function run(): void
    {
        CertificateTemplate::firstOrCreate(
            ['slug' => 'basic-certificate'],
            [
                'name' => 'Basic Certificate',
                'description' => 'Default certificate template',
                'blade_path' => 'admin.certificates.templates.basic',
                'variables' => [
                    'name', 'email', 'phone', 'serial_number', 'issued_at'
                ],
                'active' => true,
            ]
        );
    }
}


