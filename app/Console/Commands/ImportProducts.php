<?php

namespace App\Console\Commands;

use App\Services\ProductImporter\ProductImportService;
use Illuminate\Console\Command;

class ImportProducts extends Command
{
    protected $signature = 'products:import {file} {--test : Run in test mode without database insertion}';
    protected $description = 'Import products from CSV file';

    private ProductImportService $importService;

    public function __construct(ProductImportService $importService)
    {
        parent::__construct();
        $this->importService = $importService;
    }

    public function handle()
    {
        $filePath = $this->argument('file');
        $testMode = $this->option('test');

        try {
            $result = $this->importService->import($filePath, $testMode);

            $this->info("Import Summary:");
            $this->info("Total processed: {$result->getTotalProcessed()}");
            $this->info("Successfully imported: {$result->getSuccessCount()}");
            $this->info("Skipped: {$result->getSkippedCount()}");

            if ($result->hasFailures()) {
                $this->error("\nFailed entries:");
                foreach ($result->getFailures() as $failure) {
                    $this->error("- Row {$failure->getRow()}: {$failure->getReason()}");
                }
            }
        } catch (\Exception $e) {
            $this->error("Import failed: " . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
