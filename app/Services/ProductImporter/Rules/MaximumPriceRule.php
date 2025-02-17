<?php

namespace App\Services\ProductImporter\Rules;

class MaximumPriceRule implements ImportRuleInterface
{
    public function passes(array $record): bool
    {
        return (float) ($record['price'] ?? 0) <= 1000;
    }

    public function getMessage(): string
    {
        return 'Product price exceeds $1000';
    }
}
