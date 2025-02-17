<?php

namespace App\Services\ProductImporter;

class ImportFailure
{
    private int $row;
    private string $reason;

    public function __construct(int $row, string $reason)
    {
        $this->row = $row;
        $this->reason = $reason;
    }

    public function getRow(): int
    {
        return $this->row;
    }

    public function getReason(): string
    {
        return $this->reason;
    }
}
