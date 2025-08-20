<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Applicant;
use App\Models\Upload;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    public function index()
    {
        // Get basic statistics
        $stats = [
            'total_applications' => Applicant::count(),
            // Treat in_verification as pending for dashboard KPIs
            'pending_applications' => Applicant::whereIn('status', ['pending', 'in_verification'])->count(),
            'verified_applications' => Applicant::where('status', 'verified')->count(),
            'rejected_applications' => Applicant::where('status', 'rejected')->count(),
            'total_uploads' => Upload::whereHas('applicant', function($q) {
                $q->whereNull('deleted_at');
            })->count(),
            'certificates_generated' => \App\Models\Certificate::count(),
        ];

        // Get monthly data for the last 12 months
        $monthlyData = collect();
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $count = Applicant::whereYear('created_at', $date->year)
                            ->whereMonth('created_at', $date->month)
                            ->count();
            
            $monthlyData->push([
                'label' => $date->format('M Y'),
                'count' => $count
            ]);
        }

        // Get application status stats for the donut chart
        $applicationStatusStats = [
            'pending' => $stats['pending_applications'],
            'verified' => $stats['verified_applications'],
            'rejected' => $stats['rejected_applications'],
        ];

        // Get recent applications
        $sortField = request('sort', 'submitted_at');
        $sortDirection = in_array(strtolower(request('direction', 'desc')), ['asc', 'desc']) 
            ? strtolower(request('direction', 'desc')) 
            : 'desc';

        $query = Applicant::whereHas('uploads', function($q) {
                $q->whereHas('applicant', function($q) {
                    $q->whereNull('deleted_at');
                });
            })
            ->with(['uploads' => function($query) {
                $query->orderBy('created_at', 'desc');
            }])
            ->withCount(['uploads' => function($q) {
                $q->whereHas('applicant', function($q) {
                    $q->whereNull('deleted_at');
                });
            }])
            ->orderBy($sortField, $sortDirection)
            ->take(10);

        $recentApplications = $query->get()
            ->filter(function($applicant) {
                // Ensure the applicant exists and has at least one upload
                return $applicant !== null && $applicant->uploads->isNotEmpty();
            });
        
        // Prepare sort data for view
        $sort = [
            'field' => $sortField,
            'direction' => $sortDirection,
            'nextDirection' => $sortDirection === 'asc' ? 'desc' : 'asc'
        ];

        return view('admin.dashboard', [
            'stats' => $stats,
            'monthlyData' => $monthlyData,
            'applicationStatusStats' => $applicationStatusStats,
            'recentApplications' => $recentApplications,
            'sort' => $sort,
            'queryParams' => request()->except(['sort', 'direction'])
        ]);
    }
}
