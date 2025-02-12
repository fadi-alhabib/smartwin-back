<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PrivilegeFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->word,
        ];
    }
}
