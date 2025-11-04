<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;

class ExportBladeToStatic extends Command
{
    protected $signature = 'blade:export {view} {output}';
    protected $description = 'Export a Blade view to static HTML';

    public function handle()
    {
        $viewName = $this->argument('view');
        $outputPath = $this->argument('output');

        try {
            // Default data for common Blade variables
            $defaultData = [
                'errors' => new \Illuminate\Support\MessageBag(),
                'message' => null,
                'status' => null,
            ];

            // Render the Blade view with default data
            $html = View::make($viewName, $defaultData)->render();

            // Ensure the directory exists
            $directory = dirname(public_path($outputPath));
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }

            // Write the HTML file
            File::put(public_path($outputPath), $html);

            $this->info("✅ Exported {$viewName} to public/{$outputPath}");
        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
            return 1;
        }

        return 0;
    }
}