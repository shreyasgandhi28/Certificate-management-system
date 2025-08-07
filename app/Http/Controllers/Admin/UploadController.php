<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Upload;
use App\Models\Applicant;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function index(Request $request)
    {
        $query = Upload::with('applicant');

        if ($q = $request->input('q')) {
            $query->where(function ($sub) use ($q) {
                $sub->where('original_filename', 'like', "%{$q}%")
                    ->orWhere('type', 'like', "%{$q}%")
                    ->orWhereHas('applicant', function ($a) use ($q) {
                        $a->where('name', 'like', "%{$q}%")
                          ->orWhere('email', 'like', "%{$q}%");
                    });
            });
        }

        if ($status = $request->input('status')) {
            $query->where('verification_status', $status);
        }

        $uploads = $query->latest()->paginate(15)->appends($request->except('page'));

        return view('admin.uploads.index', compact('uploads'));
    }

    public function view(Upload $upload)
    {
        return Storage::disk('public')->response($upload->file_path);
    }

    public function verify(Request $request, Upload $upload)
    {
        $request->validate([
            'verification_comments' => ['nullable', 'string', 'max:1000'],
        ]);

        $upload->forceFill([
            'verification_status' => 'verified',
            'verifier_id' => auth()->id(),
            'verification_comments' => $request->input('verification_comments'),
            'verified_at' => now(),
        ])->save();

        // If all uploads verified, move applicant to in_verification or keep current; when user completes, they can finalize
        $applicant = $upload->applicant;
        if ($applicant->uploads()->where('verification_status', '!=', 'verified')->count() === 0) {
            if ($applicant->status === 'in_verification') {
                // leave as in_verification until Complete Verification pressed
            }
        }

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'upload_verified',
            'target_type' => Upload::class,
            'target_id' => $upload->id,
            'metadata' => ['comments' => $request->input('verification_comments')],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', 'Document marked as verified.');
    }

    public function reject(Request $request, Upload $upload)
    {
        $request->validate([
            'verification_comments' => ['required', 'string', 'max:1000'],
        ]);

        $upload->forceFill([
            'verification_status' => 'rejected',
            'verifier_id' => auth()->id(),
            'verification_comments' => $request->input('verification_comments'),
            'verified_at' => now(),
        ])->save();

        $applicant = $upload->applicant;
        $applicant->update(['status' => 'rejected', 'rejection_reason' => 'One or more documents rejected.']);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'upload_rejected',
            'target_type' => Upload::class,
            'target_id' => $upload->id,
            'metadata' => ['comments' => $request->input('verification_comments')],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', 'Document rejected and application marked as rejected.');
    }
}
