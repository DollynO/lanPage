<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\Tournament;
use App\Models\TournamentRound;
use Illuminate\Database\Seeder;

class TournamentRoundTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 5; $i++) {
            TournamentRound::factory()->create([
                'tournament_id' => Tournament::query()->latest('created_at')->first()->id,
                'game_id' => Game::all()->random()->id,
                'round_number' => $i,
            ]);
        }
    }
}
