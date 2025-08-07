<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApplicationFormRequest;
use App\Models\Applicant;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ApplicantSubmittedNotification;
use App\Notifications\AdminNewSubmissionNotification;

class FormController extends Controller
{
    public function __construct(
        private FileUploadService $fileUploadService
    ) {}

    public function create(Request $request)
    {
        $token = $request->get('token', Str::random(64));
        $applicant = Applicant::where('token', $token)->first();
        
        return view('public.application-form', compact('applicant', 'token'));
    }

    public function show(string $token)
    {
        $applicant = Applicant::where('token', $token)->firstOrFail();
        $applicant->load('uploads');
        
        return view('public.application-form', compact('applicant', 'token'));
    }

    public function store(ApplicationFormRequest $request)
    {
        try {
            DB::beginTransaction();
            
            Log::info('Form submission received', $request->all());
            
            // Create or update applicant
            $applicant = Applicant::updateOrCreate(
                ['token' => $request->input('token', Str::random(64))],
                [
                    'name' => $request->input('name'),
                    'email' => $request->input('email'),
                    'phone' => $request->input('phone'),
                    'gender' => $request->input('gender'),
                    'date_of_birth' => $request->input('date_of_birth'),
                    'educational_details' => $request->input('educational_details'),
                    'status' => 'pending',
                    'submitted_at' => now(),
                ]
            );

            Log::info('Applicant created', ['id' => $applicant->id, 'name' => $applicant->name]);

            // Handle file uploads
            $uploadTypes = ['tenth_certificate', 'twelfth_certificate', 'graduation_certificate', 'masters_certificate'];
            $uploadCount = 0;

            foreach ($uploadTypes as $uploadType) {
                if ($request->hasFile($uploadType)) {
                    $files = $request->file($uploadType);
                    
                    foreach ($files as $file) {
                        Log::info("Processing file upload: $uploadType");
                        
                        // Validate file
                        $errors = $this->fileUploadService->validateFile($file);
                        if (!empty($errors)) {
                            throw new \Exception('File validation failed: ' . implode(', ', $errors));
                        }
                        
                        // Create new upload
                        $upload = $this->fileUploadService->handleUpload(
                            $file, 
                            $applicant, 
                            str_replace('_certificate', '', $uploadType)
                        );
                        
                        Log::info("File uploaded successfully", ['upload_id' => $upload->id]);
                        $uploadCount++;
                    }
                }
            }

            DB::commit();

            Log::info('Application submitted successfully', [
                'applicant_id' => $applicant->id,
                'uploads_count' => $uploadCount
            ]);

            // Notify applicant and admin
            Notification::route('mail', $applicant->email)
                ->notify(new ApplicantSubmittedNotification($applicant));

            // Send a basic admin notification to the first Super Admin
            $admin = \App\Models\User::role('Super Admin')->first();
            if ($admin) {
                $admin->notify(new AdminNewSubmissionNotification($applicant));
            }

            return redirect()
                ->route('apply.success', $applicant->token)
                ->with('success', 'Application submitted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Application submission failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to submit application: ' . $e->getMessage()]);
        }
    }

    public function success(string $token)
    {
        $applicant = Applicant::where('token', $token)->firstOrFail();
        
        return view('public.application-success', compact('applicant'));
    }
}
