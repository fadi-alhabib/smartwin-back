<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TransferFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'country' => $this->faker->country,
            'phone' => $this->faker->phoneNumber,
            'points' => $this->faker->numberBetween(100, 1000),
        ];
    }
}
