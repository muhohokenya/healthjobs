<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\HealthJobController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RolesAndPermissionsController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [\App\Http\Controllers\welcomeController::class, 'index'])->name('home');


// Authenticated routes
Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::controller(NotificationController::class)
        ->prefix('notifications')
        ->name('notifications.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::delete('/{uuid}', 'deleteNotifications')->name('deleteNotifications');
            Route::patch('{notification}/read', 'markAsRead')->name('read');
            Route::patch('mark-selected-read', 'markSelectedAsRead')->name('mark-selected-read');
            Route::patch('mark-all-read', 'markAllAsRead')->name('mark-all-read');
            Route::delete('delete-selected', 'deleteSelected')->name('delete-selected');
        });
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


Route::get('test', [HealthJobController::class, 'test'])->name('test');


Route::middleware(['auth'])->group(function () {
    Route::controller(HealthJobController::class)
        ->prefix('health-jobs')
        ->name('health-jobs.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('create', 'create')->name('create')->middleware('permission:create-job-postings');
            Route::post('upload', 'upload')->name('upload')->middleware('permission:create-job-postings');
            Route::post('jobs', 'store')->name('store');
            Route::get('{uuid}', 'show')->name('show');
            Route::post('interested', 'interested')->name('interested');
        });
});


Route::middleware(['auth'])->group(function () {
    Route::controller(\App\Http\Controllers\EventsController::class)
        ->prefix('events')
        ->name('events.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('events.create');
        });
});


Route::middleware(['auth'])->group(function () {
    Route::controller(\App\Http\Controllers\Settings\ProfileController::class)
        ->prefix('medics')
        ->name('medics.')
        ->group(function () {
            Route::get('/profiles', 'index')->name('medics.profiles');
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
