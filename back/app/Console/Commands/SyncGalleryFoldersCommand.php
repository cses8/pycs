<?php

namespace App\Console\Commands;

use App\Models\Gallery; // Make sure to import your Gallery model
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem; // Import the Filesystem
use Exception; // Import Exception for error handling

class SyncGalleryFoldersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:gallery-folders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create folders for each gallery record in storage/app/public/galleries based on ID, skipping existing ones.';

    /**
     * Filesystem instance.
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * Create a new command instance.
     *
     * @param \Illuminate\Filesystem\Filesystem $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting gallery folder synchronization...');

        // Define the base path where gallery folders should reside
        $basePath = storage_path('app/public/galleries');

        // Ensure the main 'galleries' base directory exists
        try {
            // ensureDirectoryExists is idempotent (safe to run multiple times)
            $this->files->ensureDirectoryExists($basePath, 0755); // Use desired permissions
        } catch (Exception $e) {
            $this->error("Could not create or access base directory: {$basePath}");
            $this->error("Error: " . $e->getMessage());
            $this->error("Please check filesystem permissions.");
            return Command::FAILURE;
        }

        $galleries = Gallery::select('id')->get();
        // Alternative for very large tables to save memory:
        // Gallery::select('id')->chunkById(100, function ($galleries) use ($basePath) {
        //    // Code inside foreach loop goes here
        // });

        if ($galleries->isEmpty()) {
            $this->warn('No galleries found in the database. Nothing to sync.');
            return Command::SUCCESS;
        }

        $createdCount = 0;
        $skippedCount = 0;
        $failedCount = 0;

        $this->info("Found {$galleries->count()} galleries. Processing...");

        // Create a progress bar (optional, but nice for many items)
        $progressBar = $this->output->createProgressBar($galleries->count());
        $progressBar->start();

        foreach ($galleries as $gallery) {
            $folderPath = $basePath . '/' . $gallery->id;

            if ($this->files->isDirectory($folderPath)) {
                // $this->line("Skipping: Folder already exists for Gallery ID {$gallery->id} at {$folderPath}");
                $skippedCount++;
            } else {
                try {
                    if ($this->files->makeDirectory($folderPath, 0755)) { // Use desired permissions
                        // $this->info("Created: Folder for Gallery ID {$gallery->id} at {$folderPath}");
                        $createdCount++;
                    } else {
                        $this->error(" FAILED to create folder for Gallery ID {$gallery->id} at {$folderPath}. Unknown error.");
                        $failedCount++;
                    }
                } catch (Exception $e) {
                    $this->error(" FAILED to create folder for Gallery ID {$gallery->id} at {$folderPath}. Error: " . $e->getMessage());
                    $failedCount++;
                }
            }
            $progressBar->advance(); // Advance progress bar
        }

        $progressBar->finish(); // Finish progress bar
        $this->line(''); // Add a newline after progress bar

        // --- Summary Report ---
        $this->info("\n--- Sync Finished ---");
        $this->line("Folders successfully created: " . $createdCount);
        $this->line("Folders already existed (skipped): " . $skippedCount);
        $this->line("Folders failed to create: " . $failedCount);
        $this->info("---------------------");


        if ($failedCount == 0) {
            $this->comment("\nReminder: If these folders need to be web-accessible, ensure you have run 'php artisan storage:link'.");
            return Command::SUCCESS;
        } else {
            $this->error("\nThere were errors creating some folders. Please check permissions and logs.");
            return Command::FAILURE;
        }
    }
}
