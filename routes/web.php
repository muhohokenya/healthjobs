<?php

use App\Http\Controllers\HealthJobController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\DashboardController;
// Public routes
Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

// Authenticated routes
Route::middleware(['auth', 'permission:access-dashboard'])->group(function () {
    // Authenticated routes
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    });
});






// Protect routes with permissions
Route::middleware(['auth', 'permission:view-job-postings'])->group(function () {
    // Health Jobs routes (protected)
    Route::controller(HealthJobController::class)
        ->prefix('health-jobs')
        ->name('health-jobs.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('{healthJob}', 'show')->name('show');
        });
});






require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
