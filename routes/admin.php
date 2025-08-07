<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ApplicantController;
use App\Http\Controllers\Admin\UploadController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'role:Super Admin|Verifier|Certificate Issuer']], function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/applicants', [ApplicantController::class, 'index'])->name('admin.applicants.index');
    Route::get('/admin/applicants/{applicant}', [ApplicantController::class, 'show'])->name('admin.applicants.show');
    Route::post('/admin/applicants/{applicant}/start-verification', [ApplicantController::class, 'startVerification'])->name('admin.applicants.start-verification');
    Route::post('/admin/applicants/{applicant}/complete-verification', [ApplicantController::class, 'completeVerification'])->name('admin.applicants.complete-verification');
    Route::post('/admin/applicants/{applicant}/reject', [ApplicantController::class, 'reject'])->name('admin.applicants.reject');
    Route::post('/admin/applicants/{applicant}/generate-certificate', [ApplicantController::class, 'generateCertificate'])->name('admin.applicants.generate-certificate');

    Route::get('/admin/uploads/{upload}/view', [UploadController::class, 'view'])->name('admin.uploads.view');
});

