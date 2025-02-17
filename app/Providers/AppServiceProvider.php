<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ProductImporter\ProductImportService;
use App\Services\ProductImporter\Validators\CsvValidator;
use App\Services\ProductImporter\Rules\MinimumStockAndPriceRule;
use App\Services\ProductImporter\Rules\MaximumPriceRule;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ProductImportService::class, function ($app) {
            return new ProductImportService(
                new CsvValidator(),
                [
                    new MinimumStockAndPriceRule(),
                    new MaximumPriceRule()
                ]
            );
        });
    }
}
