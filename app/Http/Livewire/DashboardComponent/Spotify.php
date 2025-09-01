<?php

namespace App\Http\Livewire\DashboardComponent;

use App\Models\Tournament;
use Livewire\Component;

class Spotify extends Component
{
    public $tournament;
    public $tournaments;

    public function mount()
    {
        $this->tournament = Tournament::latest('created_at')->first();
        $this->tournaments = Tournament::get();
    }

    public function render()
    {
        if (!$this->tournament) {
            return view('livewire.dashboard-component.spotify', [
                'playerResults' => []
            ]);
        }

        $tournament_rounds = $this->tournament->rounds()->get();
        if ($tournament_rounds->isEmpty()) {
            return view('livewire.dashboard-component.spotify', [
                'playerResults' => []
            ]);
        }


        $roundResults = [];
        $playerResults = [];

        $rounds = $this->tournament->rounds()->get();
        $users = $rounds->flatMap->results->map->user->unique('id');
        foreach ($rounds as $key => $round) {
            $userResult = [];
            foreach ($users as $user) {
                $result = $round->results()->where('user_id', $user->id)->first();
                if ($result) {

                    $userResult[] = [
                        'name' => $user->name,
                        'points' => $result->points,
                        'rank' => 0,
                    ];

                    $roundResults[$round->id] = [
                        'roundId' => $round->id,
                        'gameName' => $round->is_decoy ? 'Round' . $key +1 : $round->game()->first()->name,
                        'result' => $userResult
                    ];
                }

                $scores = $playerResults[$user->id]['scores']??[];
                $scores[] = $result->points??0;
                $playerResult = [
                    'name' => $user->name,
                    'scores' => $scores
                ];
                $playerResults[$user->id] = $playerResult;

            }

        }
        $playerResults = array_values($playerResults);


        //sort
        $playerResults = collect($playerResults)
            ->map(function ($player) {
                $player['total_points'] = array_sum($player['scores']);
                return $player;
            })
            ->sortByDesc('total_points')
            ->values() // numerische Indizes
            ->toArray();

        // Tiebreaker-Rank vergeben
        $rankedResults = [];
        $rank = 1;
        $prevPoints = null;
        $skip = 0;

        foreach ($playerResults as $index => $player) {
            if ($prevPoints === $player['total_points']) {
                $player['rank'] = $rank;
                $skip++;
            } else {
                $rank += $skip;
                $player['rank'] = $rank;
                $skip = 1;
                $rankedResults[] = $player;
                $prevPoints = $player['total_points'];
                continue;
            }
            $rankedResults[] = $player;
        }

        $playerResults = $rankedResults;

        return view('livewire.dashboard-component.spotify', [
            'playerResults' => $playerResults
        ]);
    }
}
