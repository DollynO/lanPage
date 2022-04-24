<?php

namespace App\Http\Livewire;

use App\Http\Controllers\GameController;
use Illuminate\Routing\Route;
use Livewire\Component;

class EditGame extends Component
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
        $response = $gameController->create($validatedData);

        dd($response);
    }

    public function render()
    {
        return view('livewire.edit-game');
    }
}
