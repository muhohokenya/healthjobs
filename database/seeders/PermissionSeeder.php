<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user_id = 2;
        $user = User::query()->find($user_id);

//        $user->givePermissionTo('view-job-postings');
        $user->assignRole('super-admin');


    }
}
