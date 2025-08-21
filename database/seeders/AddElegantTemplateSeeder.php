<?php

namespace Database\Seeders;

use App\Models\CertificateTemplate;
use Illuminate\Database\Seeder;

class AddElegantTemplateSeeder extends Seeder
{
    public function run(): void
    {
        CertificateTemplate::updateOrCreate(
            ['slug' => 'elegant-certificate'],
            [
                'name' => 'Elegant Certificate',
                'description' => 'An elegant and professional certificate template.',
                'blade_path' => 'certificates.templates.elegant',
                'variables' => [
                    'name', 'email', 'phone', 'serial_number', 'issued_at', 'course_name', 'completion_date'
                ],
                'active' => true,
                'is_default' => true,
            ]
        );
    }
}
