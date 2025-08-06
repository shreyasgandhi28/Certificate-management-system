<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Applicant;

class ApplicantController extends Controller
{
    public function index(Request $request)
    {
        $query = Applicant::query();

        // Filter by name
        if ($name = $request->input('name')) {
            $query->where('name', 'like', "%{$name}%");
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

    public function startVerification(Request $request, Applicant $applicant)
    {
        // Check if application is in pending state
        if ($applicant->status !== 'pending') {
            return back()->with('error', 'Verification can only be started for pending applications.');
        }

        // Start verification process
        $applicant->status = 'pending_verification'; // Using 'pending_verification' instead of 'in_verification'
        $applicant->verification_started_at = now();
        $applicant->verification_started_by = auth()->id();
        $applicant->save();

        // Create audit log
        \App\Models\AuditLog::create([
            'user_id' => auth()->id(),
            'applicant_id' => $applicant->id,
            'action' => 'verification_started',
            'details' => 'Started verification process'
        ]);

        return back()->with('success', 'Verification process started.');
    }

    public function completeVerification(Request $request, Applicant $applicant)
    {
        // Validate request
        $request->validate([
            'verification_notes' => 'nullable|string|max:1000'
        ]);

        // Update applicant status
        $applicant->update([
            'status' => 'verified',
            'verification_completed_at' => now(),
            'verification_completed_by' => auth()->id(),
            'verification_notes' => $request->verification_notes
        ]);

        // Create audit log
        \App\Models\AuditLog::create([
            'user_id' => auth()->id(),
            'applicant_id' => $applicant->id,
            'action' => 'verification_completed',
            'details' => 'Completed verification process'
        ]);

        return back()->with('success', 'Application has been verified successfully.');
    }

    public function reject(Request $request, Applicant $applicant)
    {
        // Validate request
        $request->validate([
            'rejection_reason' => 'required|string|max:1000'
        ]);

        // Update applicant status
        $applicant->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'rejected_by' => auth()->id(),
            'rejection_reason' => $request->rejection_reason
        ]);

        // Create audit log
        \App\Models\AuditLog::create([
            'user_id' => auth()->id(),
            'applicant_id' => $applicant->id,
            'action' => 'application_rejected',
            'details' => 'Application rejected: ' . $request->rejection_reason
        ]);

        return back()->with('success', 'Application has been rejected.');
    }

    public function generateCertificate(Request $request, Applicant $applicant)
    {
        // Check if application is verified
        if ($applicant->status !== 'verified') {
            return back()->with('error', 'Certificates can only be generated for verified applications.');
        }

        // Generate certificate
        $certificate = \App\Models\Certificate::create([
            'applicant_id' => $applicant->id,
            'template_id' => $request->template_id,
            'certificate_number' => 'CERT-' . str_pad($applicant->id, 6, '0', STR_PAD_LEFT),
            'issued_at' => now(),
            'issued_by' => auth()->id(),
            'valid_until' => now()->addYears(5) // Example: 5-year validity
        ]);

        // Create audit log
        \App\Models\AuditLog::create([
            'user_id' => auth()->id(),
            'applicant_id' => $applicant->id,
            'action' => 'certificate_generated',
            'details' => 'Generated certificate: ' . $certificate->certificate_number
        ]);

        return back()->with('success', 'Certificate has been generated successfully.');
    }
}

