<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AdminFactory extends Factory
{
    public function definition()
    {
        return [
            'full_name' => $this->faker->name,
            'username' => $this->faker->unique()->userName,
            'password' => bcrypt('password'), // password
        ];
    }
}
