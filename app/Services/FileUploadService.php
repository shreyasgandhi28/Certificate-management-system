<?php

namespace App\Services;

use App\Models\Applicant;
use App\Models\Upload;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadService
{
    public function handleUpload(UploadedFile $file, Applicant $applicant, string $type): Upload
    {
        // Generate unique filename
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $storedName = Str::uuid() . '.' . $extension;
        
        // Define upload path
        $uploadPath = 'uploads/' . $applicant->id . '/' . $type;
        
        // Store file
        $filePath = $file->storeAs($uploadPath, $storedName, 'public');
        
        // Create upload record
        return Upload::create([
            'applicant_id' => $applicant->id,
            'type' => $type,
            'original_filename' => $originalName,
            'stored_filename' => $storedName,
            'file_path' => $filePath,
            'file_hash' => hash_file('sha256', Storage::disk('public')->path($filePath)),
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'verification_status' => 'pending',
            'uploader_ip' => request()->ip(),
        ]);
    }

    public function validateFile(UploadedFile $file): array
    {
        $errors = [];
        
        if ($file->getSize() > 5242880) {
            $errors[] = 'File size cannot exceed 5MB.';
        }
        
        $allowedMimes = ['application/pdf', 'image/jpeg', 'image/png', 'image/jpg'];
        if (!in_array($file->getMimeType(), $allowedMimes)) {
            $errors[] = 'File must be a PDF, JPG, JPEG, or PNG.';
        }
        
        return $errors;
    }

    public function deleteUpload(Upload $upload): bool
    {
        if (Storage::exists($upload->file_path)) {
            Storage::delete($upload->file_path);
        }
        
        return $upload->delete();
    }
}
