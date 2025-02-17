<?php

namespace App\Services\ProductImporter\Rules;

class MinimumStockAndPriceRule implements ImportRuleInterface
{
    public function passes(array $record): bool
    {
        $price = (float) ($record['price'] ?? 0);
        $stock = (int) ($record['stock'] ?? 0);

        return !($price < 5 && $stock < 10);
    }

    public function getMessage(): string
    {
        return 'Product price is less than $5 and stock is less than 10';
    }
}
