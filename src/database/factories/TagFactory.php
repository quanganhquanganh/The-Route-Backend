<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TagFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // $nameList = array();
        // $slugList = array();
        // for($i = 0; $i < 10; $i++) {
        //     $name = $this->faker->word;
        //     $slug = $this->faker->slug(2);
        //     while(in_array($name, $nameList)) {
        //         $name = $this->faker->word;
        //     }
        //     while(in_array($slug, $slugList)) {
        //         $slug = $this->faker->slug(2);
        //     }
        //     $nameList[] = $name;
        //     $slugList[] = $slug;
        // }
        
        return [
            'name' => $this->faker->sentence(3),
            'slug' => $this->faker->slug(2),
        ];
    }
}
