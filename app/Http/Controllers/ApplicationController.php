<?php

namespace App\Http\Controllers;

use App\Models\Applicant;           // you already have this model
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    /**
     * Display a paginated, filterable list of applications.
     */
    public function index(Request $request)
    {
        $query = Applicant::query();

        // search
        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name',  'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // status filter
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // date range filter
        if ($from = $request->input('from')) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to = $request->input('to')) {
            $query->whereDate('created_at', '<=', $to);
        }

        // sort (default newest first)
        $query->orderBy(
            $request->input('sort', 'created_at'),
            $request->input('dir', 'desc')
        );

        $applicants = $query->paginate(15)->withQueryString();

        return view('applications.index', compact('applicants'));
    }
}
