<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use App\Models\Party;
use App\Models\TournamentRound;
use App\Models\TournamentRoundUser;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class TournamentController extends Controller
{
    public function index()
    {
        $this->getCurrentLeaderboard();

        $tournament = Tournament::all()->last();
        $participants = Party::all()->last();

        return $this->getCurrentLeaderboard();
    }

    public function store()
    {
        $tournament = new Tournament();
        $tournament->save();

        return redirect()->route('tournaments.index');
    }

    public function getCurrentLeaderboard()
    {
        // $tournament = Tournament::latest('created_at')->first();
        return view('tournament');

        $tournament = Tournament::query()->latest('created_at')->first();
        $tournament_rounds = $tournament ? $tournament->rounds()->get() : collect();

        if (!$tournament) {
            return view('tournament', ['data' => [], 'tournaments' => Tournament::get()]);
        }
        $tournament_rounds = $tournament->rounds()->get();
        if ($tournament_rounds->isEmpty()) {
            return view('tournament', ['data' => [], 'tournaments' => Tournament::get()]);
        }

        $games = [];
        $contestants = [];
        foreach ($tournament_rounds[0]->results()->get() as $round_results) {
            $user = $round_results->user;
            $contestants[$user->id] = $user;
            $contestants[$user->id]->total_points = 0;
            $contestants[$user->id]->rounds = [];
        }

        $results = [];
        foreach ($tournament_rounds as $tournament_round) {
            $result = $tournament_round->results()->get();
            array_push($results, $result);
            foreach ($result as $contestant_result) {
                $contestants[$contestant_result->user_id]->rounds[] = $contestant_result;
                $contestants[$contestant_result->user_id]->total_points += $contestant_result->points;
            }
            $games[] = $tournament_round->game;
        }

        $points = array_column($contestants, 'total_points');
        array_multisort($points, SORT_DESC, $contestants);

        $i = 1;
        $previous_points = 999;
        $previous_rank = $i;
        foreach ($contestants as $contestant) {
            $rank = $contestant->total_points == $previous_points ? $previous_rank : $i;
            $contestant->rank = $rank;

            $previous_points = $contestant->total_points;
            $previous_rank = $rank;
            $i++;
        }

        array_unshift(
            $contestants,
            array_merge(
                ['Rank', 'Name', 'Points'],
                $games->pluck('name')->toArray()
            )
        );

    }

    public function show(string $id): JsonResponse
    {
        $tournament = Tournament::query()->whereKey($id)->first();
        if (!$tournament) {
            return response()->json(['error' => 'Tournament not found'], 404);
        }
        return response()->json($tournament, 200);
    }

    public function delete(string $id): JsonResponse
    {
        $tournament = Tournament::find($id);

        if (!$tournament) {
            return response()->json(['error' => 'Tournament not found'], 404);
        }

        $tournament->delete();

        return response()->json(['success' => 'Tournament deleted'], 200);
    }
}
