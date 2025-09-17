<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Event>
 */
class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        $faker = $this->faker;

        // Choose a start date between 30 days ago and 60 days in the future
        $start = $faker->dateTimeBetween('-30 days', '+60 days');
        // End date is 1-8 hours after start
        $end = (clone $start)->modify('+'.$faker->numberBetween(1, 8).' hours');

        // Randomly set link and image_path as nullable sometimes
        $maybe = fn () => $faker->boolean(70); // 70% chance to include

        return [
            'start_date' => $start,
            'end_date' => $end,
            'title' => $faker->sentence(6),
            'description' => $faker->paragraphs($faker->numberBetween(1, 3), true),
            'link' => $maybe() ? $faker->url() : null,
            'image_path' => $maybe() ? 'images/events/'.Str::uuid().'.jpg' : null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
