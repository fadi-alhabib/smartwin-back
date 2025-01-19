<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TransferRequestFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'points' => $this->faker->numberBetween(100, 1000),
        ];
    }
}
