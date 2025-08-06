<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Public Application Routes
Route::prefix('apply')->name('apply.')->group(function () {
    Route::get('/', [App\Http\Controllers\Public\FormController::class, 'create'])->name('create');
    Route::get('/{token}', [App\Http\Controllers\Public\FormController::class, 'show'])->name('show');
    Route::post('/', [App\Http\Controllers\Public\FormController::class, 'store'])->name('store');
    Route::get('/{token}/success', [App\Http\Controllers\Public\FormController::class, 'success'])->name('success');
});

require __DIR__.'/auth.php';

// Admin Routes (protected by auth middleware)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/applicant-stats', [App\Http\Controllers\Admin\DashboardController::class, 'applicantStats'])->name('applicant-stats');
    // Documents/Uploads Management
    Route::get('/uploads', function() { 
        $uploads = \App\Models\Upload::with('applicant')->latest()->paginate(15);
        return view('admin.uploads.index', compact('uploads')); 
    })->name('uploads.index');
    Route::get('/uploads/{upload}/view', function(\App\Models\Upload $upload) {
        \Log::info('View request for upload:', [
            'upload_id' => $upload->id,
            'file_path' => $upload->file_path,
            'stored_filename' => $upload->stored_filename
        ]);

        // Check all possible locations
        $locations = [
            storage_path('app/public/uploads/' . $upload->applicant_id . '/' . $upload->type . '/' . $upload->stored_filename),
            storage_path('app/' . $upload->file_path),
            storage_path('app/private/' . $upload->file_path),
            storage_path('app/private/uploads/' . $upload->applicant_id . '/' . $upload->type . '/' . $upload->stored_filename)
        ];

        \Log::info('Checking locations:', ['locations' => $locations]);

        foreach ($locations as $location) {
            \Log::info('Checking location: ' . $location);
            if (file_exists($location)) {
                \Log::info('File found at: ' . $location);
                return response()->file($location);
            }
        }

        // Get all files in storage for debugging
        $allFiles = str_replace(storage_path('app') . '/', '', 
            array_filter(
                glob(storage_path('app/**/*')), 
                'is_file'
            )
        );
        \Log::info('All files in storage:', ['files' => $allFiles]);

        abort(404, 'File not found in any storage location');
    })->name('uploads.view');
    // Certificates Management  
    Route::get('/certificates', function() { 
        return "Certificates management page coming soon in Phase 3"; 
    })->name('certificates.index');
    // Users Management
    Route::get('/users', function() { 
        return "Users management page coming soon in Phase 3"; 
    })->name('users.index');
});


// Applicants Management
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/applicants', [\App\Http\Controllers\Admin\ApplicantController::class, 'index'])->name('applicants.index');
    Route::get('/applicants/{applicant}', [\App\Http\Controllers\Admin\ApplicantController::class, 'show'])->name('applicants.show');
    Route::post('/applicants/{applicant}/start-verification', [\App\Http\Controllers\Admin\ApplicantController::class, 'startVerification'])->name('applicants.start-verification');
    Route::post('/applicants/{applicant}/complete-verification', [\App\Http\Controllers\Admin\ApplicantController::class, 'completeVerification'])->name('applicants.complete-verification');
    Route::post('/applicants/{applicant}/reject', [\App\Http\Controllers\Admin\ApplicantController::class, 'reject'])->name('applicants.reject');
    Route::post('/applicants/{applicant}/generate-certificate', [\App\Http\Controllers\Admin\ApplicantController::class, 'generateCertificate'])->name('applicants.generate-certificate');
});
