<?php

use App\Models\Event;
use Database\Seeders\EventsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can create an event via factory', function () {
    $event = Event::factory()->create();

    expect($event->id)->not->toBeNull();
    expect($event->title)->not->toBeEmpty();
    expect($event->start_date)->not->toBeNull();
    expect($event->end_date)->not->toBeNull();
    expect($event->end_date->greaterThan($event->start_date))->toBeTrue();
});

it('seeds 100 events using EventsSeeder', function () {
    $this->seed(EventsSeeder::class);

    expect(Event::query()->count())->toBe(100);
});
