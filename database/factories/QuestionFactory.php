<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionFactory extends Factory
{
    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'image' => $this->faker->imageUrl(),
            'user_id' => \App\Models\User::factory(),
            'status' => $this->faker->boolean,
        ];
    }
}
