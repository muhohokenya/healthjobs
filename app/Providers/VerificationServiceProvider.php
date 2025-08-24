<?php

namespace App\Providers;

use App\Services\PharmacyBoardVerificationService;
use Illuminate\Support\ServiceProvider;

class VerificationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(PharmacyBoardVerificationService::class, function ($app) {
            return new PharmacyBoardVerificationService();
        });

        // Optional: Create an alias for easier access
        $this->app->alias(PharmacyBoardVerificationService::class, 'pharmacy.verification');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
