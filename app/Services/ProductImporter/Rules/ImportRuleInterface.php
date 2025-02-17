<?php

namespace App\Services\ProductImporter\Rules;

interface ImportRuleInterface
{
    public function passes(array $record): bool;
    public function getMessage(): string;
}
