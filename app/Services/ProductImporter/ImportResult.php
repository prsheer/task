<?php

namespace App\Services\ProductImporter;

class ImportResult
{
    private int $successCount = 0;
    private array $failures = [];
    private array $skipped = [];

    public function addSuccess(): void
    {
        $this->successCount++;
    }

    public function addFailure(int $row, string $reason): void
    {
        $this->failures[] = new ImportFailure($row, $reason);
    }

    public function addSkipped(int $row, string $reason): void
    {
        $this->skipped[] = new ImportFailure($row, $reason);
    }

    public function getTotalProcessed(): int
    {
        return $this->successCount + count($this->failures) + count($this->skipped);
    }

    public function getSuccessCount(): int
    {
        return $this->successCount;
    }

    public function getSkippedCount(): int
    {
        return count($this->skipped);
    }

    public function getFailures(): array
    {
        return $this->failures;
    }

    public function hasFailures(): bool
    {
        return !empty($this->failures);
    }
}
