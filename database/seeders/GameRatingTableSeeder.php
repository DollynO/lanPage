<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\GameRating;
use App\Models\User;
use Illuminate\Database\Seeder;

class GameRatingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $games = Game::all();
        $users = User::all();
        foreach ($games as $game) {
            foreach ($users as $user){
                GameRating::factory()->create([
                    'game_id'=> $game->id,
                    'user_id' => $user->id,
                ]);
            }
        }
    }
}
