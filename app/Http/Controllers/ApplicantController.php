<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use Illuminate\Http\Request;

class ApplicantController extends Controller
{
    public function index(Request $request)
    {
        $query = Applicant::with(['uploads', 'certificates']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Sort
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $applicants = $query->paginate(15)->withQueryString();

        return view('admin.applicants.index', compact('applicants'));
    }

    public function show(Applicant $applicant)
    {
        $applicant->load(['uploads', 'certificates']);
        return view('admin.applicants.show', compact('applicant'));
    }

    public function updateStatus(Request $request, Applicant $applicant)
    {
        $request->validate([
            'status' => 'required|in:pending,verified,rejected,certificate_generated'
        ]);

        $applicant->update(['status' => $request->status]);

        return back()->with('success', 'Applicant status updated successfully.');
    }
}
