<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class GameVoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'is_accepted' => $this->faker->boolean(),
        ];
    }
}
