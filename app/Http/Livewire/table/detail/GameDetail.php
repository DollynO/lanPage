<?php

namespace App\Http\Livewire\table\detail;

use App\Models\Game;

class GameDetail extends Detail
{
    public $game;

    protected $rules =[
        'game.id' => 'sometimes|required|exists:games,id',
        'game.name' => 'required|string|max:255',
        'game.genre' => 'required|string|max:200',
        'game.note' => 'nullable|string|max:255',
        'game.source' => 'required|string|max:255',
        'game.player_count' => 'required|integer|min:0',
        'game.price' => 'required|numeric|min:0',
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
        if ($game->gameSuggestions()->count()) {
            $this->notification()->error(
                'Can not delete!',
                'The game was suggested.'
            );
            return;
        }

        $game->delete();

        $this->notification()->success(
            'Game deleted.'
        );
    }

    public function save()
    {
        $this->game['price'] = empty($this->game['price']) ? null : $this->game['price'];
        $this->game['note'] = empty($this->game['note']) ? null : $this->game['note'];

        $validatedData = $this->validate();

        $game = Game::query()->whereKey($this->game['id'])->first();
        $game->name = $validatedData['game']['name'];
        $game->genre = $validatedData['game']['genre'];
        $game->note = $validatedData['game']['note'];
        $game->source = $validatedData['game']['source'];
        $game->player_count = $validatedData['game']['player_count'];
        $game->price = $validatedData['game']['price'];
        $game->already_played = $validatedData['game']['already_played'];
        $game->save();
        $this->game = $game->toArray();
        $this->inEditState = false;

        $this->notification()->success(
            'Game saved.',
        );
    }
}
