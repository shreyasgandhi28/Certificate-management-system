<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Jobs\SendCertificateEmail;
use App\Jobs\SendCertificateWhatsApp;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function index(Request $request)
    {
        $query = Certificate::with(['applicant', 'template']);

        // Keyword search across serial and applicant
        if ($q = $request->input('q')) {
            $query->where(function ($sub) use ($q) {
                $sub->where('serial_number', 'like', "%{$q}%")
                    ->orWhereHas('applicant', function ($a) use ($q) {
                        $a->where('name', 'like', "%{$q}%")
                          ->orWhere('email', 'like', "%{$q}%");
                    });
            });
        }

        // Applicant filter
        if ($applicant = $request->input('applicant')) {
            $query->whereHas('applicant', function ($a) use ($applicant) {
                $a->where('name', 'like', "%{$applicant}%")
                  ->orWhere('email', 'like', "%{$applicant}%");
            });
        }

        // Applicant ID filter
        if ($applicantId = $request->input('applicant_id')) {
            $query->where('applicant_id', $applicantId);
        }

        // Status filter
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // Template filter
        if ($templateId = $request->input('template_id')) {
            $query->where('template_id', $templateId);
        }

        // Generated date range
        if ($from = $request->input('from')) {
            $query->whereDate('generated_at', '>=', $from);
        }
        if ($to = $request->input('to')) {
            $query->whereDate('generated_at', '<=', $to);
        }

        $certificates = $query->latest()->paginate(15)->appends($request->except('page'));

        return view('admin.certificates.index', compact('certificates'));
    }

    public function download(Certificate $certificate)
    {
        return Storage::disk('public')->download($certificate->pdf_path);
    }

    public function view(Certificate $certificate)
    {
        // Stream the PDF inline in the browser
        return Storage::disk('public')->response($certificate->pdf_path);
    }

    public function sendEmail(Certificate $certificate): RedirectResponse
    {
        SendCertificateEmail::dispatch($certificate)->onQueue('mail');
        return back()->with('success', 'Email dispatch queued.');
    }

    public function sendWhatsApp(Certificate $certificate): RedirectResponse
    {
        SendCertificateWhatsApp::dispatch($certificate)->onQueue('default');
        return back()->with('success', 'WhatsApp dispatch queued.');
    }

    public function resetStatus(Certificate $certificate): RedirectResponse
    {
        $certificate->update([
            'status' => 'generated',
            'email_sent_at' => null,
            'whatsapp_sent_at' => null,
            'send_attempts' => 0,
            'last_error' => null,
            'last_attempt_at' => null,
        ]);
        return back()->with('success', 'Delivery status reset.');
    }

    /**
     * Export filtered certificates as CSV
     */
    public function exportCsv(Request $request)
    {
        $filename = 'certificates_' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($request) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Application ID', 'Serial', 'Applicant', 'Email', 'Template', 'Status', 'Generated At', 'Email Sent', 'WhatsApp Sent', 'Attempts']);

            $query = Certificate::with(['applicant', 'template']);
            if ($ids = $request->input('ids')) {
                $idArray = collect(explode(',', $ids))->filter()->values();
                if ($idArray->isNotEmpty()) {
                    $query->whereIn('id', $idArray);
                }
            }
            if ($q = $request->input('q')) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('serial_number', 'like', "%{$q}%")
                        ->orWhereHas('applicant', function ($a) use ($q) {
                            $a->where('name', 'like', "%{$q}%")
                              ->orWhere('email', 'like', "%{$q}%");
                        });
                });
            }
            if ($applicant = $request->input('applicant')) {
                $query->whereHas('applicant', function ($a) use ($applicant) {
                    $a->where('name', 'like', "%{$applicant}%")
                      ->orWhere('email', 'like', "%{$applicant}%");
                });
            }
            if ($status = $request->input('status')) $query->where('status', $status);
            if ($templateId = $request->input('template_id')) $query->where('template_id', $templateId);
            if ($from = $request->input('from')) $query->whereDate('generated_at', '>=', $from);
            if ($to = $request->input('to')) $query->whereDate('generated_at', '<=', $to);

            $query->orderByDesc('id')->chunk(500, function ($rows) use ($handle) {
                foreach ($rows as $c) {
                    fputcsv($handle, [
                        $c->id,
                        $c->applicant_id,
                        $c->serial_number,
                        optional($c->applicant)->name,
                        optional($c->applicant)->email,
                        optional($c->template)->name,
                        $c->status,
                        optional($c->generated_at)?->toDateTimeString(),
                        optional($c->email_sent_at)?->toDateTimeString(),
                        optional($c->whatsapp_sent_at)?->toDateTimeString(),
                        $c->send_attempts,
                    ]);
                }
            });

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}


