<?php

use App\Models\HealthJob;
use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

// Guests should be redirected (routes are protected)

test('guests are redirected from health jobs index', function () {
    $response = $this->get('/health-jobs');
    $response->assertRedirect('/login');
});

test('guests are redirected from health jobs show', function () {
    $job = HealthJob::factory()->create();

    $response = $this->get('/health-jobs/'.$job->getKey());
    $response->assertRedirect('/login');
});

// Authenticated users can access

test('authenticated users can view health jobs index', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get('/health-jobs');
    $response->assertOk();
});

test('authenticated users can view a health job', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $job = HealthJob::factory()->create();

    $response = $this->get('/health-jobs/'.$job->getKey());
    $response->assertOk();
});
