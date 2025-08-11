<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Applicant;
use App\Models\Upload;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
            'total_uploads' => Upload::count(),
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
        $recentApplications = Applicant::with('uploads')
                                     ->latest('created_at')
                                     ->take(10)
                                     ->get()
                                     ->map(function ($applicant) {
                                         $applicant->submitted_at = $applicant->created_at;
                                         return $applicant;
                                     });

        return view('admin.dashboard', compact(
            'stats',
            'monthlyData', 
            'applicationStatusStats',
            'recentApplications'
        ));
    }
}
