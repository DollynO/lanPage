<?php

namespace App\Http\Livewire;

use App\Http\Controllers\GameController;
use App\Models\Game;
use Livewire\Component;

class EditGame extends Component
{
    public $gameId;
    public $name;
    public $source;
    public $price;
    public $already_played;
    public $note;
    public $player_count;

    protected $rules= [
        'name'=>'required|string',
        'source' => 'required|string',
        'price' => 'numeric|nullable',
        'player_count' => 'required|numeric',
        'note' => 'string|nullable',
        'already_played' => 'boolean|nullable'
    ];

    public function updated($propertyName){
        $this->validateOnly($propertyName);
    }

    public function submit(){
        $validatedData = $this->validate();

        $gameController = new GameController();

        if( !empty( $validatedData->gameId)){
            $response = $gameController->update($validatedData);
        }else{
            $response = $gameController->create($validatedData);
        }
        redirect()->to('/games');
    }

    public function mount($id){
        $game = Game::find($id);
        $this->gameId = $game->id ?? '';
        $this->name = $game->name ?? 'new game';
        $this->note = $game->note ?? '';
        $this->source = $game->source ?? '';
        $this->player_count = $game->player_count ?? 0;
        $this->price = $game->price ?? 0.00;
        $this->already_played = $game->already_played ?? false;
    }

    public function render()
    {

        return view('livewire.edit-game');
    }
}
