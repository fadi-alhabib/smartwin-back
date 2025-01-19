<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AnswerFactory extends Factory
{
    public function definition()
    {
        return [
            'question_id' => \App\Models\Question::factory(),
            'title' => $this->faker->sentence,
            'is_correct' => $this->faker->boolean,
        ];
    }
}
