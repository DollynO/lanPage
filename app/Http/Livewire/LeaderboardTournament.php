<?php

namespace App\Http\Livewire;

use App\Models\Tournament;
use Livewire\Component;

class LeaderboardTournament extends Component
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
        $contestants = [];
        $games = [];

        if (!$this->tournament) {
            return view('livewire.leaderboard-tournament', ['data' => $contestants]);
        }

        $tournament_rounds = $this->tournament->rounds()->get();
        if ($tournament_rounds->isEmpty()) {
            return view('livewire.leaderboard-tournament', ['data' => $contestants]);
        }

        foreach ($tournament_rounds[0]->results()->get() as $round_results) {
            $user = $round_results->user;
            $contestants[$user->id] = [
                'user' => $user,
                'total_points' => 0,
                'rounds' => [],
                'rank' => null,
            ];
        }

        foreach ($tournament_rounds as $tournament_round) {
            $result = $tournament_round->results()->get();



            foreach ($result as $contestant_result) {

                if (empty ($contestants[$contestant_result->user_id])) {
                    $contestants[$contestant_result->user_id] = [
                        'user' => $contestant_result->user,
                        'total_points' => 0,
                        'rounds' => [],
                        'rank' => null,
                    ];
                }

                $contestants[$contestant_result->user_id]['rounds'][] = $contestant_result;
                $contestants[$contestant_result->user_id]['total_points'] += $contestant_result->points;
            }
        }

        $points = array_column($contestants, 'total_points');
        array_multisort($points, SORT_DESC, $contestants);

        $i = 1;
        $previous_points = 999;
        $previous_rank = $i;
        foreach ($contestants as &$contestant) {
            $rank = $contestant['total_points'] == $previous_points ? $previous_rank : $i;
            $contestant['rank'] = $rank;
            $previous_points = $contestant['total_points'];
            $previous_rank = $rank;
            $i++;
        }

        $games = collect($games);
        array_unshift(
            $contestants,
            array_merge(
                ['Rank', 'Name', 'Points'],
                $games->pluck('name')->toArray(),
            )
        );


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

        //sort
        foreach ($roundResults as &$round) {
            // Sortiere nach points absteigend
            usort($round['result'], fn($a, $b) => $b['points'] <=> $a['points']);

            $rank = 1;
            $prevPoints = null;
            $skip = 0;

            foreach ($round['result'] as $i => &$player) {
                if ($prevPoints === $player['points']) {
                    $player['rank'] = $rank; // gleicher Rank wie vorher
                    $skip++;
                } else {
                    $rank += $skip;
                    $player['rank'] = $rank;
                    $skip = 1;
                    $prevPoints = $player['points'];
                }
            }
            unset($player);
        }
        unset($round);

        foreach ($roundResults as $key => $round) {
            usort($round['result'], fn($a, $b) => $b['points'] <=> $a['points']);

            // Prüfen, ob das erste Ergebnis 0 Punkte hat
            if (!empty($round['result']) && $round['result'][0]['points'] == 0) {
                unset($roundResults[$key]);
            }
        }

        $roundResults = array_values($roundResults);

        return view('livewire.leaderboard-tournament', [
            'playerResults' => $playerResults,
            'contestants' => $contestants,
            'rounds' => $tournament_rounds,
            'roundResults' => $roundResults,
        ]);
    }

    public function signalLeaveViewToParent()
    {
        $this->emitUp('leaveLeaderboardView');
    }
}
