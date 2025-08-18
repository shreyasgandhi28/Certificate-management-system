<?php

namespace Database\Seeders;

use App\Models\CertificateTemplate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TemplateSeeder extends Seeder
{
    public function run(): void
    {
        CertificateTemplate::updateOrCreate(
            ['slug' => 'modern-certificate-updated'],
            [
                'name' => 'Modern Certificate Updated',
                'description' => 'A modern and visually appealing certificate template.',
                'blade_path' => 'certificates.templates.modern_updated',
                'variables' => [
                    'name', 'email', 'phone', 'serial_number', 'issued_at'
                ],
                'active' => true,
            ]
        );

        // Deactivate the old template if it exists
        $oldTemplate = CertificateTemplate::where('slug', 'basic-certificate')->first();
        if ($oldTemplate) {
            $oldTemplate->active = false;
            $oldTemplate->save();
        }
    }
}


