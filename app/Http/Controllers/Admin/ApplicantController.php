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
}

