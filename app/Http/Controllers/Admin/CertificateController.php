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

        if ($q = $request->input('q')) {
            $query->where(function ($sub) use ($q) {
                $sub->where('serial_number', 'like', "%{$q}%")
                    ->orWhereHas('applicant', function ($a) use ($q) {
                        $a->where('name', 'like', "%{$q}%")
                          ->orWhere('email', 'like', "%{$q}%");
                    });
            });
        }

        $certificates = $query->latest()->paginate(15)->appends($request->except('page'));

        return view('admin.certificates.index', compact('certificates'));
    }

    public function download(Certificate $certificate)
    {
        return Storage::disk('public')->download($certificate->pdf_path);
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
}


