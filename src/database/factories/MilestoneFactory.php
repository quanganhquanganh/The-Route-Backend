<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MilestoneFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
            'roadmap_id' => $this->faker->numberBetween(1, 10),
            'user_id' => 1,
            'name' => $this->faker->sentence,
            'description' => $this->faker->sentence,
            'start_date' => $this->faker->dateTimeBetween('-2 year', '+2 year'),
            'end_date' => $this->faker->dateTimeBetween('-2 year', '+2 year'),
        ];
    }
}
