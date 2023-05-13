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
                $contestants[$contestant_result->user_id]['rounds'][] = $contestant_result;
                $contestants[$contestant_result->user_id]['total_points'] += $contestant_result->points;
            }
            $games[] = $tournament_round->game;
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

        return view('livewire.leaderboard-tournament', [
            'contestants' => $contestants,
            'games' => $games
        ]);
    }

    public function signalLeaveViewToParent()
    {
        $this->emitUp('leaveLeaderboardView');
    }
}
