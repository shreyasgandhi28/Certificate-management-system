<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ApplicantController;
use App\Http\Controllers\Admin\UploadController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'role:Super Admin|Certificate Issuer']], function () {
    // Dashboard
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    // Applicant Routes
    Route::prefix('admin/applicants')->group(function () {
        Route::get('/', [ApplicantController::class, 'index'])->name('admin.applicants.index');
        Route::get('/{applicant}', [ApplicantController::class, 'show'])->name('admin.applicants.show');
        Route::post('/{applicant}/start-verification', [ApplicantController::class, 'startVerification'])->name('admin.applicants.start-verification');
        Route::post('/{applicant}/complete-verification', [ApplicantController::class, 'completeVerification'])->name('admin.applicants.complete-verification');
        Route::post('/{applicant}/reject', [ApplicantController::class, 'reject'])->name('admin.applicants.reject');
        Route::post('/{applicant}/generate-certificate', [ApplicantController::class, 'generateCertificate'])->name('admin.applicants.generate-certificate');
        
        // New routes for email, WhatsApp, and reset
        Route::post('/{applicant}/send-email', [ApplicantController::class, 'sendEmail'])->name('admin.applicants.send-email');
        Route::post('/{applicant}/send-whatsapp', [ApplicantController::class, 'sendWhatsApp'])->name('admin.applicants.send-whatsapp');
        Route::post('/{applicant}/reset-verification', [ApplicantController::class, 'resetVerification'])->name('admin.applicants.reset-verification');
    });

    // Document Viewing
    Route::get('/admin/uploads/{upload}/view', [UploadController::class, 'view'])->name('admin.uploads.view');
});
