<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\GameVote;
use App\Models\User;
use Illuminate\Database\Seeder;

class GameVoteTableSeeder extends Seeder
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
                GameVote::factory()->create([
                    'game_id'=> $game->id,
                    'user_id' => $user->id,
                ]);
            }
        }
    }
}
