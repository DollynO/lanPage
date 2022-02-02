<?php

namespace App\Http\Livewire;
namespace App\Models;

use Livewire\Component;

class SearchableTable extends Component
{
    // Params
    public $class;

    public function render()
    {
        $data = $this->class::query()->all;

        return view('livewire.searchable-table',['data'=>$data]);
    }
}
