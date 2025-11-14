<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class GameFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'genre' => $this->faker->randomElement(['FPS','RPG','RTS']),
            'name'=> $this->faker->name(),
            'note'=> $this->faker->text(),
            'source' => $this->faker->url(),
            'player_count' => $this->faker->numberBetween(1,10),
            'price' => $this->faker->numberBetween(1,10000)/100,
            'already_played' => $this->faker->boolean(),
        ];
    }
}
