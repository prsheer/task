<?php

namespace App\Services\ProductImporter\Validators;

use League\Csv\Reader;

class CsvValidator
{
    private const REQUIRED_HEADERS = [
        'name',
        'price',
        'stock',
        'discontinued'
    ];

    public function validate(Reader $csv): void
    {
        $headers = $csv->getHeader();

        $missingHeaders = array_diff(self::REQUIRED_HEADERS, $headers);

        if (!empty($missingHeaders)) {
            throw new \RuntimeException(
                'Missing required headers: ' . implode(', ', $missingHeaders)
            );
        }
    }
}
