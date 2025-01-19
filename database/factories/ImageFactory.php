<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ImageFactory extends Factory
{
    public function definition()
    {
        return [
            'image' => $this->faker->imageUrl(),
            'product_id' => \App\Models\Product::factory(),
        ];
    }
}
