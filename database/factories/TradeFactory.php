<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TradeFactory extends Factory
{
    public function definition()
    {
        return [
            'product_id' => \App\Models\Product::factory(),
            'user_id' => \App\Models\User::factory(),
            'status' => $this->faker->randomElement(['pending', 'accepted', 'denied']),
        ];
    }
}
