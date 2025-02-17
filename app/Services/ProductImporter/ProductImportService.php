<?php

namespace App\Services\ProductImporter;

use App\Services\ProductImporter\Validators\CsvValidator;
use League\Csv\Exception;
use League\Csv\InvalidArgument;
use League\Csv\Reader;
use League\Csv\UnavailableFeature;
use League\Csv\UnavailableStream;

class ProductImportService
{
    private array $rules;
    private CsvValidator $validator;

    public function __construct(CsvValidator $validator, array $rules)
    {
        $this->validator = $validator;
        $this->rules = $rules;
    }

    /**
     * @throws InvalidArgument
     * @throws UnavailableStream
     * @throws UnavailableFeature
     * @throws Exception
     */
    public function import(string $filePath, bool $testMode = false): ImportResult
    {
        if (!file_exists($filePath)) {
            throw new \RuntimeException("File not found: $filePath");
        }

        $csv = Reader::createFromPath($filePath, 'r');
        $csv->setHeaderOffset(0);
        $csv->addStreamFilter('convert.iconv.ISO-8859-1/UTF-8');

        $this->validator->validate($csv);

        $result = new ImportResult();

        foreach ($csv->getRecords() as $offset => $record) {
            try {
                $record = $this->normalizeData($record);

                $skipRecord = false;
                foreach ($this->rules as $rule) {
                    if (!$rule->passes($record)) {
                        $result->addSkipped($offset + 1, $rule->getMessage());
                        $skipRecord = true;
                        break;
                    }
                }

                if ($skipRecord) {
                    continue;
                }

                if (!$testMode) {
                    $this->processRecord($record);
                }

                $result->addSuccess();
            } catch (\Exception $e) {
                $result->addFailure($offset + 1, $e->getMessage());
            }
        }

        return $result;
    }

    private function normalizeData(array $record): array
    {
        return array_map(function ($value) {
            $value = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $value);
            return trim($value);
        }, $record);
    }

    private function processRecord(array $record): void
    {
        $product = new Product();
        $product->fill($record);

        if ($record['discontinued'] ?? false) {
            $product->discontinued_at = now();
        }

        $product->save();
    }
}
