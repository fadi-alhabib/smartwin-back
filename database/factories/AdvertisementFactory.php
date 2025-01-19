<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AdvertisementFactory extends Factory
{
    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'path' => $this->faker->url,
            'home_ad' => $this->faker->boolean,
            'priority' => $this->faker->numberBetween(1, 10),
            'is_img' => $this->faker->boolean,
            'from_date' => $this->faker->date,
            'to_date' => $this->faker->date,
            'is_active' => $this->faker->boolean,
        ];
    }
}
