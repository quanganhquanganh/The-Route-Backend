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
            'description' => $this->faker->paragraphs(3, true),
            'image' => 'default.png',
            'slug' => $this->faker->slug(2),
            'current' => 5
        ];
    }
}
