<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Applicant;
use App\Models\AuditLog;
use App\Models\Certificate;
use App\Models\CertificateTemplate;
use App\Services\CertificateService;
use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Notifications\CertificateGeneratedNotification;

class ApplicantController extends Controller
{
    public function index(Request $request)
    {
        $query = Applicant::query();

        // Filter by name
        if ($name = $request->input('name')) {
            $query->where('name', 'like', "%{$name}%");
        }
        // Filter by ID
        if ($id = $request->input('id')) {
            $query->where('id', $id);
        }
        // Filter by email
        if ($email = $request->input('email')) {
            $query->where('email', 'like', "%{$email}%");
        }
        // Filter by phone
        if ($phone = $request->input('phone')) {
            $query->where('phone', 'like', "%{$phone}%");
        }
        // Filter by status
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }
        // Filter by submitted_at (date)
        if ($date = $request->input('submitted_at')) {
            $query->whereDate('submitted_at', $date);
        }

        $applicants = $query->with('uploads')->latest()->paginate(10)->appends($request->except('page'));

        return view('admin.applicants.index', compact('applicants'));
    }

    public function show(Applicant $applicant)
    {
        $applicant->load(['uploads']);
        return view('admin.applicants.show', compact('applicant'));
    }

    public function edit(Applicant $applicant)
    {
        return view('admin.applicants.edit', compact('applicant'));
    }

    public function update(Request $request, Applicant $applicant)
    {
        $validated = $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255'],
            'phone' => ['nullable','string','max:30'],
            'status' => ['required','in:pending,in_verification,verified,rejected,certificate_generated'],
            'uploads' => ['array'],
            'uploads.*.verification_status' => ['in:pending,verified,rejected']
        ]);
        $applicant->update(collect($validated)->only(['name','email','phone','status'])->toArray());

        // Update upload verification statuses if provided
        foreach ($request->input('uploads', []) as $uploadId => $data) {
            \App\Models\Upload::where('id', $uploadId)
                ->where('applicant_id', $applicant->id)
                ->update(['verification_status' => $data['verification_status'] ?? 'pending']);
        }
        return redirect()->route('admin.applicants.show', $applicant)->with('success','Application updated.');
    }

    public function destroy(Applicant $applicant)
    {
        $applicant->delete();
        return redirect()->route('admin.applicants.index')->with('success','Application moved to trash.');
    }

    public function restore($id)
    {
        $applicant = Applicant::withTrashed()->findOrFail($id);
        $applicant->restore();
        return redirect()->route('admin.applicants.show', $applicant)->with('success','Application restored.');
    }

    public function startVerification(Request $request, Applicant $applicant)
    {
        // Check if application is in pending state
        if ($applicant->status !== 'pending') {
            return back()->with('error', 'Verification can only be started for pending applications.');
        }

        // Start verification process
        $applicant->status = 'in_verification';
        $applicant->verification_started_at = now();
        $applicant->verification_started_by = auth()->id();
        $applicant->save();

        // Create audit log
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'verification_started',
            'target_type' => Applicant::class,
            'target_id' => $applicant->id,
            'metadata' => ['details' => 'Started verification process'],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', 'Verification process started.');
    }

    public function completeVerification(Request $request, Applicant $applicant)
    {
        // Validate request
        $request->validate([
            'verification_notes' => 'nullable|string|max:1000'
        ]);

        Log::info('Starting verification for applicant: ' . $applicant->id);

        // Update applicant status
        $applicant->update([
            'status' => 'verified',
            'verification_completed_at' => now(),
            'verification_completed_by' => auth()->id(),
            'verification_notes' => $request->verification_notes
        ]);

        // Update status of all uploads to verified
        Upload::where('applicant_id', $applicant->id)->update(['verification_status' => 'verified']);
        Log::info('Updated all uploads to verified for applicant ' . $applicant->id);

        // Create audit log
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'verification_completed',
            'target_type' => Applicant::class,
            'target_id' => $applicant->id,
            'metadata' => ['notes' => $request->verification_notes],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        Log::info('Verification complete for applicant: ' . $applicant->id);

        return back()->with('success', 'Application has been verified successfully.');
    }

    public function reject(Request $request, Applicant $applicant)
    {
        // Validate request
        $request->validate([
            'rejection_reason' => 'required|string|max:1000'
        ]);

        Log::info('Starting rejection for applicant: ' . $applicant->id);

        // Update applicant status
        $applicant->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'rejected_by' => auth()->id(),
            'rejection_reason' => $request->rejection_reason
        ]);

        // Update status of all uploads to rejected
        Upload::where('applicant_id', $applicant->id)->update(['verification_status' => 'rejected']);
        Log::info('Updated all uploads to rejected for applicant ' . $applicant->id);

        // Create audit log
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'application_rejected',
            'target_type' => Applicant::class,
            'target_id' => $applicant->id,
            'metadata' => ['reason' => $request->rejection_reason],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        Log::info('Rejection complete for applicant: ' . $applicant->id);

        return back()->with('success', 'Application has been rejected.');
    }

    public function exportCsv(Request $request)
    {
        $filename = 'applicants_' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($request) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Name', 'Email', 'Phone', 'Status', 'Submitted At', 'Verified At']);

            $query = Applicant::query();
            if ($ids = $request->input('ids')) {
                $idArray = collect(explode(',', $ids))->filter()->values();
                if ($idArray->isNotEmpty()) {
                    $query->whereIn('id', $idArray);
                }
            }
            if ($name = $request->input('name')) $query->where('name', 'like', "%{$name}%");
            if ($email = $request->input('email')) $query->where('email', 'like', "%{$email}%");
            if ($status = $request->input('status')) $query->where('status', $status);
            if ($date = $request->input('submitted_at')) $query->whereDate('submitted_at', $date);

            $query->orderByDesc('id')->chunk(500, function ($rows) use ($handle) {
                foreach ($rows as $a) {
                    fputcsv($handle, [
                        $a->id,
                        $a->name,
                        $a->email,
                        $a->phone,
                        $a->status,
                        optional($a->submitted_at)?->toDateTimeString(),
                        optional($a->verification_completed_at)?->toDateTimeString(),
                    ]);
                }
            });

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
    public function generateCertificate(Request $request, Applicant $applicant, CertificateService $certificateService)
    {
        // Check if application is verified
        if ($applicant->status !== 'verified') {
            return back()->with('error', 'Certificates can only be generated for verified applications.');
        }

        $request->validate([
            'template_id' => 'required|exists:certificate_templates,id'
        ]);

        $template = CertificateTemplate::findOrFail($request->input('template_id'));

        $certificate = $certificateService->generateCertificate($applicant, $template, auth()->id());

        // Create audit log
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'certificate_generated',
            'target_type' => Applicant::class,
            'target_id' => $applicant->id,
            'metadata' => [
                'certificate_id' => $certificate->id,
                'serial_number' => $certificate->serial_number,
            ],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Notify applicant via email (queued)
        Notification::route('mail', $applicant->email)
            ->notify(new CertificateGeneratedNotification($certificate));

        return redirect()->route('admin.applicants.index')->with('success', 'Certificate generated successfully.');
    }
}

