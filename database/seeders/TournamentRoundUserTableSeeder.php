<?php

namespace Database\Seeders;

use App\Models\TournamentRound;
use App\Models\TournamentRoundUser;
use App\Models\User;
use Illuminate\Database\Seeder;

class TournamentRoundUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        $tournamentRounds = TournamentRound::all();

        foreach ($tournamentRounds as $tournamentRound) {
            $placements = [];
            for ($i = 1; $i <= count($users); $i++) {
                array_push($placements, $i);
            }

            foreach ($users as $user) {
                if (count($placements) > 1) {
                    $placement_index = rand(0, count($placements) - 1);
                } else {
                    $placement_index = 0;
                }

                $placement = $placements[$placement_index];
                $points = count($users) - $placement + 1;
                array_splice($placements, $placement_index, 1);

                TournamentRoundUser::factory()->create([
                    'tournament_round_id' => $tournamentRound->id,
                    'user_id' => $user->id,
                    'points' => $points,
                    'has_won' => $placement == 1
                ]);
            }
        }
    }

}
