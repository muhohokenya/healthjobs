<?php

namespace App\Providers;

use App\Services\LicenseVerificationService;
use Illuminate\Support\ServiceProvider;

class VerificationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(LicenseVerificationService::class, function ($app) {
            return new LicenseVerificationService();
        });

        // Optional: Create an alias for easier access
        $this->app->alias(LicenseVerificationService::class, 'pharmacy.verification');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
