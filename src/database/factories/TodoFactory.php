<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TodoFactory extends Factory
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
            'content' => $this->faker->sentence,
            'start_date' => $this->faker->dateTimeBetween('-1 years', '+1 years'),
            'end_date' => $this->faker->dateTimeBetween('-1 years', '+1 years'),
            'task_id' => $this->faker->numberBetween(1, 10),
            'roadmap_id' => 1,
            'user_id' => 1,
            'completed' => $this->faker->boolean,
        ];
    }
}
