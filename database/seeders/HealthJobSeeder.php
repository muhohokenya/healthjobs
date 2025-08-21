<?php

namespace Database\Seeders;

use App\Models\HealthJob;
use Illuminate\Database\Seeder;

class HealthJobSeeder extends Seeder
{
    public function run()
    {
        HealthJob::factory(50)->create();
    }
}
