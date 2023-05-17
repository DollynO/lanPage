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
    public $genre;

    protected $rules= [
        'name'=>'required|string|max:255',
        'source' => 'required|string|max:255',
        'price' => 'required|numeric',
        'player_count' => 'required|numeric',
        'note' => 'string|nullable|max:255',
        'already_played' => 'boolean|nullable',
        'genre' => 'required|string|max:200',
    ];

    public function updated($propertyName){
        $this->validateOnly($propertyName);
    }

    public function save(){
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
