<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Upload;
use Illuminate\Support\Facades\Storage;

class FixUploadPaths extends Command
{
    protected $signature = 'uploads:fix-paths';
    protected $description = 'Fix upload paths by moving files to the correct public location';

    public function handle()
    {
        $this->info('Starting to fix upload paths...');
        
        $uploads = Upload::all();
        
        foreach ($uploads as $upload) {
            $this->info("Processing upload ID: {$upload->id}");
            
            // Build the correct public path
            $baseDir = 'uploads/' . $upload->applicant_id . '/' . $upload->type;
            $fileName = $upload->stored_filename;
            $publicPath = 'public/' . $baseDir . '/' . $fileName;
            
            // Check possible old locations
            $possiblePaths = [
                'private/public/' . $baseDir . '/' . $fileName,
                'private/' . $baseDir . '/' . $fileName,
                $upload->file_path,
                'private/' . $upload->file_path
            ];
            
            $fileFound = false;
            foreach ($possiblePaths as $oldPath) {
                if (Storage::exists($oldPath)) {
                    $this->info("Found file at: {$oldPath}");
                    
                    // Create directory if it doesn't exist
                    $publicDir = storage_path('app/public/' . $baseDir);
                    if (!file_exists($publicDir)) {
                        mkdir($publicDir, 0755, true);
                    }
                    
                    // Copy to public location
                    if (!Storage::exists($publicPath)) {
                        Storage::copy($oldPath, $publicPath);
                        $this->info("Copied to: {$publicPath}");
                    }
                    
                    // Update the file path in database
                    $upload->update([
                        'file_path' => $baseDir . '/' . $fileName
                    ]);
                    
                    $fileFound = true;
                    break;
                }
            }
            
            if (!$fileFound) {
                $this->error("Could not find file for upload ID: {$upload->id}");
            }
        }
        
        $this->info('Finished fixing upload paths.');
    }
}
