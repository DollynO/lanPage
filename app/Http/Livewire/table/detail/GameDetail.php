<?php

namespace App\Http\Livewire\table\detail;

use App\Models\Game;
use Livewire\Component;

class GameDetail extends Component
{
    public $game;
    public $inEditState;
    public $confirmDelete = false;

    protected $rules =[
        'game.id' => 'sometimes|required|exists:games,id',
        'game.name' => 'required|string',
        'game.note' => 'nullable|string',
        'game.source' => 'required|string',
        'game.player_count' => 'required|integer|min:0',
        'game.price' => 'nullable|numeric',
        'game.already_played' => 'nullable|boolean',
    ];

    public function mount($object){
        $this->game = $object;
    }

    public function render()
    {
        return view('livewire.table.detail.game-detail');
    }

    public function delete()
    {
        $game = Game::query()->whereKey($this->game['id'])->first();
        $game->delete();
    }

    public function saveGame()
    {
        $validatedData = $this->validate();

        $game = Game::query()->whereKey($this->game['id'])->first();
        $game->name = $validatedData['game']['name'];
        $game->note = $validatedData['game']['note'];
        $game->source = $validatedData['game']['source'];
        $game->player_count = $validatedData['game']['player_count'];
        $game->price = $validatedData['game']['price'];
        $game->already_played = $validatedData['game']['already_played'];
        $game->save();
        $this->game = $game->toArray();
        $this->inEditState = false;
    }
}
