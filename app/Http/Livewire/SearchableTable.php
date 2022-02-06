<?php

namespace App\Http\Livewire;

use Livewire\Component;

class SearchableTable extends Component
{
    public $class;

    public function render()
    {
        $jsonString = file_get_contents(base_path('/resources/views/livewire/searchable-table.json'));
        $json = json_decode($jsonString, true);
        $config= $json[$this->class];

        $className =  '\App\Models\\'.$this->class;
        $class = new $className();
        $query = $class::query()->get()->all();

        $data = [];
        $data['config'] = $config;
        $data['query'] = $query;

        return view('livewire.searchable-table', ['data' => $data]);
    }
}
