<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AdminFactory extends Factory
{
    public function definition()
    {

        return [
            'full_name' => "fadi",
            'username' => "fadi",
            'password' => bcrypt('fadiadminpassword.password'), // password
        ];
    }
}
