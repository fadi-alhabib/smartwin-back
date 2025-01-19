<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RoomFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'image' => $this->faker->imageUrl(),
            'online' => $this->faker->boolean,
            'host_id' => \App\Models\User::factory(),
        ];
    }
}
