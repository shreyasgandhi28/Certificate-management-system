<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CleanupOrphanedUploads extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cleanup:orphaned-uploads {--force : Run the cleanup without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up uploads that belong to non-existent applicants';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Find all uploads that don't have a corresponding applicant
        $orphanedUploads = \App\Models\Upload::doesntHave('applicant')->get();
        
        if ($orphanedUploads->isEmpty()) {
            $this->info('No orphaned uploads found.');
            return 0;
        }
        
        $this->warn("Found {$orphanedUploads->count()} orphaned upload(s).");
        
        if ($this->option('force') || $this->confirm('Do you want to delete these orphaned uploads? This action cannot be undone.')) {
            $bar = $this->output->createProgressBar($orphanedUploads->count());
            $bar->start();
            
            $deletedCount = 0;
            
            foreach ($orphanedUploads as $upload) {
                try {
                    $upload->delete(); // This will trigger the file deletion in the model
                    $deletedCount++;
                } catch (\Exception $e) {
                    $this->error("Failed to delete upload ID {$upload->id}: " . $e->getMessage());
                }
                $bar->advance();
            }
            
            $bar->finish();
            $this->newLine(2);
            
            $this->info("Successfully deleted {$deletedCount} orphaned upload(s).");
            
            if ($deletedCount < $orphanedUploads->count()) {
                $this->warn('Some uploads could not be deleted. Check the error messages above.');
            }
        } else {
            $this->info('Cleanup cancelled.');
        }
    }
}
