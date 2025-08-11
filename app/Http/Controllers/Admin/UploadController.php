<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    /**
     * Display a listing of the uploaded documents.
     */
    public function index(Request $request)
    {
        $query = Upload::with('applicant');

        // Keyword search across filename, type, applicant name/email
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

        // Specific applicant name/email filter
        if ($applicant = $request->input('applicant')) {
            $query->whereHas('applicant', function ($a) use ($applicant) {
                $a->where('name', 'like', "%{$applicant}%")
                  ->orWhere('email', 'like', "%{$applicant}%");
            });
        }

        // Filter by applicant id
        if ($applicantId = $request->input('applicant_id')) {
            $query->where('applicant_id', $applicantId);
        }

        // Filename filter
        if ($filename = $request->input('filename')) {
            $query->where('original_filename', 'like', "%{$filename}%");
        }

        // Type filter
        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }

        // Status filter
        if ($status = $request->input('status')) {
            $query->where('verification_status', $status);
        }

        // Uploaded date range filter
        if ($from = $request->input('from')) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to = $request->input('to')) {
            $query->whereDate('created_at', '<=', $to);
        }

        $uploads = $query->latest()->paginate(15)->appends($request->except('page'));

        return view('admin.uploads.index', compact('uploads'));
    }

    /**
     * View the uploaded document.
     */
    public function view(Upload $upload)
    {
        return Storage::disk('public')->response($upload->file_path);
    }

    /**
     * Export filtered uploads as CSV
     */
    public function exportCsv(Request $request)
    {
        $filename = 'uploads_' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($request) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Application ID', 'Applicant', 'Email', 'Type', 'Filename', 'Status', 'Uploaded At']);

            $query = Upload::with('applicant');
            if ($ids = $request->input('ids')) {
                $idArray = collect(explode(',', $ids))->filter()->values();
                if ($idArray->isNotEmpty()) {
                    $query->whereIn('id', $idArray);
                }
            }
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
            if ($applicant = $request->input('applicant')) {
                $query->whereHas('applicant', function ($a) use ($applicant) {
                    $a->where('name', 'like', "%{$applicant}%")
                      ->orWhere('email', 'like', "%{$applicant}%");
                });
            }
            if ($filename = $request->input('filename')) $query->where('original_filename', 'like', "%{$filename}%");
            if ($type = $request->input('type')) $query->where('type', $type);
            if ($status = $request->input('status')) $query->where('verification_status', $status);
            if ($from = $request->input('from')) $query->whereDate('created_at', '>=', $from);
            if ($to = $request->input('to')) $query->whereDate('created_at', '<=', $to);

            $query->orderByDesc('id')->chunk(500, function ($rows) use ($handle) {
                foreach ($rows as $u) {
                    fputcsv($handle, [
                        $u->id,
                        $u->applicant_id,
                        optional($u->applicant)->name,
                        optional($u->applicant)->email,
                        $u->type,
                        $u->original_filename,
                        $u->verification_status,
                        optional($u->created_at)?->toDateTimeString(),
                    ]);
                }
            });

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
