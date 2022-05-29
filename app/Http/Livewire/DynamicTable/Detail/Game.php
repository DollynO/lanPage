<?php

namespace App\Http\Livewire\DynamicTable\Detail;

use Livewire\Component;
use function view;

class Game extends Component
{
    public $entry;

    public function render()
    {;
        return view('livewire.dynamic-table.detail.game');
    }
}
