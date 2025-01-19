<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductRatingFactory extends Factory
{
    public function definition()
    {
        return [
            'product_id' => \App\Models\Product::factory(),
            'user_id' => \App\Models\User::factory(),
            'rating' => $this->faker->numberBetween(1, 5),
            'review' => $this->faker->paragraph,
        ];
    }
}
