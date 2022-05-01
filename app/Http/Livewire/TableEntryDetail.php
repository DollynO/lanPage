<?php

namespace App\Http\Livewire;

use Livewire\Component;

class TableEntryDetail extends Component
{
    public $entry;

    public function render()
    {
        return view('livewire.table-entry-detail');
    }
}
