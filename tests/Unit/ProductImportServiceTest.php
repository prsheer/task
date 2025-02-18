<?php

namespace Tests\Unit;

use App\Services\ProductImporter\ProductImportService;
use App\Services\ProductImporter\Rules\MaximumPriceRule;
use App\Services\ProductImporter\Rules\MinimumStockAndPriceRule;
use App\Services\ProductImporter\Validators\CsvValidator;
use Tests\TestCase;

class ProductImportServiceTest extends TestCase
{
    private ProductImportService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new ProductImportService(
            new CsvValidator(),
            [
                new MinimumStockAndPriceRule(),
                new MaximumPriceRule(),
            ]
        );
    }

    public function testImportValidProducts(): void
    {
        $csvContent = "name,price,stock,discontinued\n";
        $csvContent .= "Product 1,10.00,20,0\n";
        $csvContent .= "Product 2,15.00,30,1\n";

        $tempFile = tempnam(sys_get_temp_dir(), 'test_import');
        file_put_contents($tempFile, $csvContent);

        $result = $this->service->import($tempFile, true);

        $this->assertEquals(2, $result->getTotalProcessed());
        $this->assertEquals(2, $result->getSuccessCount());
        $this->assertEquals(0, $result->getSkippedCount());

        unlink($tempFile);
    }

    public function testSkipProductsWithLowStockAndPrice(): void
    {
        $csvContent = "name,price,stock,discontinued\n";
        $csvContent .= "Product 1,4.00,5,0\n";

        $tempFile = tempnam(sys_get_temp_dir(), 'test_import');
        file_put_contents($tempFile, $csvContent);

        $result = $this->service->import($tempFile, true);

        $this->assertEquals(1, $result->getTotalProcessed());
        $this->assertEquals(0, $result->getSuccessCount());
        $this->assertEquals(1, $result->getSkippedCount());

        unlink($tempFile);
    }
}
