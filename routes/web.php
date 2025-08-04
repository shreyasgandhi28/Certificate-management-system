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
    
    // Placeholder routes (we'll implement these next)
    Route::get('/applicants', function() { return 'Applicants page coming soon'; })->name('applicants.index');
    Route::get('/uploads', function() { return 'Documents page coming soon'; })->name('uploads.index');
    Route::get('/certificates', function() { return 'Certificates page coming soon'; })->name('certificates.index');
    Route::get('/users', function() { return 'Users page coming soon'; })->name('users.index');
});

// Add missing admin.applicants.show route
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/applicants/{applicant}', function($id) { 
        $applicant = \App\Models\Applicant::findOrFail($id);
        return "Applicant details for: " . $applicant->name . " (Full page coming soon)"; 
    })->name('applicants.show');
});

// Complete Admin Routes Structure
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/applicant-stats', [App\Http\Controllers\Admin\DashboardController::class, 'applicantStats'])->name('applicant-stats');
    
    // Applicants Management
    Route::get('/applicants', function() { 
        $applicants = \App\Models\Applicant::with('uploads')->latest()->paginate(15);
        return view('admin.applicants.index', compact('applicants')); 
    })->name('applicants.index');
    
    Route::get('/applicants/{applicant}', function($id) { 
        $applicant = \App\Models\Applicant::with('uploads')->findOrFail($id);
        return view('admin.applicants.show', compact('applicant')); 
    })->name('applicants.show');
    
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

// Admin Dashboard Routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Placeholder routes for navigation links (you can implement these later)
    Route::get('/applicants', function() { return redirect()->route('admin.dashboard'); })->name('applicants.index');
    Route::get('/applicants/{id}', function($id) { return redirect()->route('admin.dashboard'); })->name('applicants.show');
    Route::get('/uploads', function() { return redirect()->route('admin.dashboard'); })->name('uploads.index');
    Route::get('/certificates', function() { return redirect()->route('admin.dashboard'); })->name('certificates.index');
    Route::get('/users', function() { return redirect()->route('admin.dashboard'); })->name('users.index');
});
