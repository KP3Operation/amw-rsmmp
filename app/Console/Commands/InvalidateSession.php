<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InvalidateSession extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'session:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all sessions data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // TODO: need to determine which driver is used and delete it based on the driver

        if (config('app.debug')) {
            $sessionFiles = File::allFiles(storage_path('framework'.DIRECTORY_SEPARATOR.'sessions'.DIRECTORY_SEPARATOR));

            foreach ($sessionFiles as $sessionFile) {
                if ($sessionFile != '.gitignore') {
                    File::delete(
                        storage_path(
                            'framework'.
                            DIRECTORY_SEPARATOR.
                            'sessions'.
                            DIRECTORY_SEPARATOR.
                            $sessionFile->getFilename()
                        )
                    );
                }
            }
        }

        $this->components->info('Application session cleared successfully.');
    }
}
