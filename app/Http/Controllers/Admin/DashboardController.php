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
            'pending_applications' => Applicant::where('status', 'pending')->count(),
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

        // Get verification stats for uploads
        $verificationStats = [
            'pending' => Upload::where('verification_status', 'pending')->count(),
            'verified' => Upload::where('verification_status', 'verified')->count(),
            'rejected' => Upload::where('verification_status', 'rejected')->count(),
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
            'verificationStats',
            'recentApplications'
        ));
    }
}
