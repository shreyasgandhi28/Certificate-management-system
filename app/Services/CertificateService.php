<?php

namespace App\Services;

use App\Models\Applicant;
use App\Models\Certificate;
use App\Models\CertificateTemplate;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CertificateService
{
    public function generateCertificate(Applicant $applicant, CertificateTemplate $template, int $generatedByUserId): Certificate
    {
        $serialNumber = $this->generateSerialNumber($applicant->id);

        $data = $this->buildCertificateData($applicant, $serialNumber);

        $pdfPath = $this->renderAndStorePdf($template, $data, $applicant->id, $serialNumber);

        return Certificate::create([
            'applicant_id' => $applicant->id,
            'template_id' => $template->id,
            'serial_number' => $serialNumber,
            'pdf_path' => $pdfPath,
            'data' => $data,
            'status' => 'generated',
            'generated_by' => $generatedByUserId,
            'generated_at' => now(),
        ]);
    }

    private function generateSerialNumber(int $applicantId): string
    {
        return 'CERT-' . date('Y') . '-' . str_pad((string) $applicantId, 6, '0', STR_PAD_LEFT) . '-' . strtoupper(Str::random(4));
    }

    private function buildCertificateData(Applicant $applicant, string $serialNumber): array
    {
        return [
            'name' => $applicant->name,
            'email' => $applicant->email,
            'phone' => $applicant->phone,
            'gender' => $applicant->gender,
            'date_of_birth' => optional($applicant->date_of_birth)->toDateString(),
            'submitted_at' => optional($applicant->submitted_at)->toDateTimeString(),
            'serial_number' => $serialNumber,
            'issued_at' => now()->toDateString(),
        ];
    }

    private function renderAndStorePdf(CertificateTemplate $template, array $data, int $applicantId, string $serialNumber): string
    {
        $html = view($template->blade_path, $data)->render();

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultPaperSize', 'A4');
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $directory = 'certificates/' . $applicantId;
        $filename = $serialNumber . '.pdf';
        $fullPath = $directory . '/' . $filename;

        Storage::disk('public')->put($fullPath, $dompdf->output());

        return $fullPath;
    }
}


