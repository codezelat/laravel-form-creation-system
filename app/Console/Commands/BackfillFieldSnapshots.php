<?php

namespace App\Console\Commands;

use App\Models\Form;
use App\Models\FormSubmission;
use Illuminate\Console\Command;

class BackfillFieldSnapshots extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'submissions:backfill-snapshots {--dry-run : Preview changes without making them}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backfill field snapshots for existing form submissions to enable better backwards compatibility';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        
        $this->info('Starting field snapshot backfill process...');
        
        if ($dryRun) {
            $this->warn('DRY RUN MODE - No changes will be made');
        }
        
        // Get all submissions without field snapshots
        $submissions = FormSubmission::whereNull('field_snapshot')
            ->with('form.fields')
            ->get();
            
        if ($submissions->isEmpty()) {
            $this->info('No submissions found that need field snapshots.');
            return 0;
        }
        
        $this->info("Found {$submissions->count()} submissions to process.");
        
        $processedCount = 0;
        $errorCount = 0;
        
        foreach ($submissions as $submission) {
            try {
                if (!$submission->form) {
                    $this->warn("Submission {$submission->id}: Form not found, skipping");
                    $errorCount++;
                    continue;
                }
                
                // Create snapshot from current form fields
                // Note: This is a best-effort approach - the current fields might be different
                // from when the submission was made, but it's better than nothing
                $snapshot = FormSubmission::createFieldSnapshot($submission->form);
                
                if (!$dryRun) {
                    $submission->update(['field_snapshot' => $snapshot]);
                }
                
                $this->line("✓ Processed submission {$submission->id} for form '{$submission->form->title}'");
                $processedCount++;
                
            } catch (\Exception $e) {
                $this->error("✗ Error processing submission {$submission->id}: " . $e->getMessage());
                $errorCount++;
            }
        }
        
        $this->newLine();
        $this->info("Backfill completed!");
        $this->info("✓ Processed: {$processedCount}");
        
        if ($errorCount > 0) {
            $this->warn("✗ Errors: {$errorCount}");
        }
        
        if ($dryRun) {
            $this->warn('This was a dry run - no changes were made. Remove --dry-run to apply changes.');
        } else {
            $this->info('Field snapshots have been created for existing submissions.');
            $this->warn('Note: These snapshots use current form fields, which may differ from the original form structure when submissions were made.');
        }
        
        return 0;
    }
}
