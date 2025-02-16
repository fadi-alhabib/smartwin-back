<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        \App\Models\User::factory(10)->create();
        \App\Models\Admin::factory(5)->create();
        \App\Models\Privilege::factory(5)->create();
        \App\Models\Transfer::factory(20)->create();
        \App\Models\Store::factory(10)->create();
        \App\Models\Product::factory(50)->create();
        \App\Models\ProductRating::factory(100)->create();
        \App\Models\Image::factory(100)->create();
        \App\Models\Advertisement::factory(10)->create();
        \App\Models\Room::factory(10)->create();
        \App\Models\Question::factory(20)->create();
        \App\Models\Answer::factory(50)->create();
        \App\Models\Trade::factory(30)->create();
    }
}
