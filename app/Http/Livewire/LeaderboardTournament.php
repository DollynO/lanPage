<?php

namespace App\Http\Livewire;

use App\Models\Party;
use App\Models\Tournament;
use Livewire\Component;

class LeaderboardTournament extends Component
{
    public function mount()
    {
    }

    public function render()
    {
        $tournaments = [];

        $partyId = Party::query()->where('is_active', true)->first()->id ?? null;
        $tournamentCollection = Tournament::query()
            ->where('party_id', $partyId)
            ->orderBy('created_at', 'desc')
            ->getModels();

        if (empty($tournamentCollection)) {
            return view('livewire.leaderboard-tournament', ['data' => []]);
        }

        foreach ($tournamentCollection as $tournament) {

            $contestants = [];
            $games = [];


            $tournament_rounds = $tournament->rounds()->get();
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

                    if (isset($contestants[$contestant_result->user_id])) {
                        $contestants[$contestant_result->user_id]['rounds'][] = $contestant_result;
                        $contestants[$contestant_result->user_id]['total_points'] += $contestant_result->points;
                    }

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

            $tournaments[] = [
                'name' => $tournament['name'],
                'contestants' => $contestants,
                'rounds' => $tournament_rounds,
            ];

        }

        $totalPointsPerUser = [];

        foreach ($tournaments as $tournament) {

            foreach ($tournament['contestants'] as $key => $tournamentContestant) {

                if ($key < 1) {
                    continue;
                }

                $userId = $tournamentContestant['user']->id;
                $userName = $tournamentContestant['user']->name;
                $points = $tournamentContestant['total_points'] ?? 0;

                if (!isset($totalPointsPerUser[$userId])) {
                    $totalPointsPerUser[$userId] = [
                        'user_id' => $userId,
                        'user_name' => $userName,
                        'total_points' => 0,
                    ];
                }

                $totalPointsPerUser[$userId]['total_points'] += $points;
            }
        }

        usort($totalPointsPerUser, function ($a, $b) {
            return $b['total_points'] <=> $a['total_points'];
        });

        return view('livewire.leaderboard-tournament',
            [
                'tournaments' => $tournaments,
                'totalPointsPerUser' => $totalPointsPerUser,
            ]
        );
    }

    public function signalLeaveViewToParent()
    {
        $this->emitUp('leaveLeaderboardView');
    }
}
