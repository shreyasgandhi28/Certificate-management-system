// Add to routes/web.php
use App\Http\Controllers\Admin\ApplicantController;

Route::get('/admin/applicants', [ApplicantController::class, 'index'])->name('admin.applicants.index');
Route::get('/admin/applicants/{applicant}', [ApplicantController::class, 'show'])->name('admin.applicants.show');
// Add more routes for verification actions as needed
