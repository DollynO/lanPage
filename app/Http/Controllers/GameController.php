<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class GameController extends Controller
{
    //
    public function index(){
        $games = Game::all();
        return view('games', ['data'=>$games]);
    }

    /**
     * @param array $request
     * @return JsonResponse
     */
    public function create(Array $request): JsonResponse
    {
        $validator = Validator::make($request, [
            'player_count' => 'required|numeric',
            'price' => 'required|numeric',
            'name' => 'required|string',
            'note' => 'nullable|string',
            'source' => 'required|string',
            'already_played' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $game = new Game();
        $game->player_count = $request['player_count'];
        $game->price = $request['price'];
        $game->name = $request['name'];
        $game->note = $request['note'];
        $game->source = $request['source'];
        $game->already_played = $request['already_played'] ?? false;

        $game->save();

        return response()->json($game, 200);
    }

    /**
     * @param array $request
     * @return JsonResponse
     */
    public function update(Array $request): JsonResponse
    {
        $validator = Validator::make($request, [
            'id'=>'required|integer|exists:games,id',
            'player_count' => 'required|numeric',
            'price' => 'required|numeric',
            'name' => 'required|string',
            'note' => 'nullable|string',
            'source' => 'required|string',
            'already_played' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $game = Game::query()->whereKey($request['id'])->first();
        $game->fill($validator->validated());
        $game->save();

        return response()->json($game, 200);

    }
}
