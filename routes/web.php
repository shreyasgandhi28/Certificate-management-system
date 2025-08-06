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
});
