<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\HealthJobController;
use App\Http\Controllers\RolesAndPermissionsController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Public routes
Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware(['auth', 'roles:super-admin'])->group(function () {
    Route::controller(FacilityController::class)
        ->prefix('facilities')
        ->name('facilities.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('create', 'create')->name('create')->middleware('permission:create-facility');
            Route::post('store', 'store')->name('store')->middleware('permission:create-facility');
        });
});

Route::post('check-licence', [HealthJobController::class, 'checkLicence'])->name('check-licence');

Route::middleware(['auth'])->group(function () {
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
            Route::post('update', 'update')->name('roles.update');
            Route::get('roles', 'roles')->name('roles');
            Route::get('roles/map', 'map')->name('roles.map');
            Route::get('roles/create', 'createRole')->name('roles.create');
        });
});

Route::middleware(['auth', 'permission:has-complete-profile'])->group(function () {});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
