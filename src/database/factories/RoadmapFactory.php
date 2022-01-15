<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoadmapFactory extends Factory
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
            'user_id' => User::factory(),
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->sentence(10),
            'image' => 'default.png',
            'slug' => $this->faker->slug(2),
        ];
    }
}
