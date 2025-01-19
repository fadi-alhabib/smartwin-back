<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class StoreFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'type' => $this->faker->word,
            'country' => $this->faker->country,
            'address' => $this->faker->address,
            'phone' => $this->faker->phoneNumber,
            'points' => $this->faker->numberBetween(0, 1000),
            'is_active' => $this->faker->boolean,
            'user_id' => \App\Models\User::factory(),
        ];
    }
}
