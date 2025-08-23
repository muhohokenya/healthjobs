<?php

use App\Http\Controllers\FacilityController;
use App\Http\Controllers\HealthJobController;
use App\Http\Controllers\RolesAndPermissionsController;
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

Route::middleware(['auth', 'permission:view-facilities'])->group(function () {
    Route::controller(FacilityController::class)
        ->prefix('facilities')
        ->name('facilities.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('create', 'create')->name('create')->middleware('permission:create-facility');
            Route::post('store', 'store')->name('store');
        });
});

Route::middleware(['auth', 'permission:view-job-postings'])->group(function () {
    // Health Jobs routes (protected)
    Route::controller(HealthJobController::class)
        ->prefix('health-jobs')
        ->name('health-jobs.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('create', 'create')->name('create');
            Route::post('jobs', 'store')->name('store');
            Route::get('{healthJob}', 'show')->name('show');
        });
});

Route::middleware(['auth', 'roles:super-admin'])->group(function () {
    Route::controller(RolesAndPermissionsController::class)
        ->prefix('iam')
        ->name('iam.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('create', 'create')->name('create');
            Route::post('store', 'store')->name('store');
            Route::get('roles', 'roles')->name('roles');
            Route::get('roles/create', 'createRole')->name('roles.create');
        });
});





require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
