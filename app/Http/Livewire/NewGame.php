<?php

namespace App\Http\Livewire;

use App\Http\Controllers\GameController;
use App\Models\Game;
use Livewire\Component;

class NewGame extends Component
{
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
        $gameController->create($validatedData);

        redirect()->to('/games');
    }

    public function render()
    {
        return view('livewire.new-game');
    }
}
