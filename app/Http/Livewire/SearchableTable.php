<?php

namespace App\Http\Livewire;

use Livewire\Component;

class SearchableTable extends Component
{
    public $class;
    public $search;
    public $sortField = 'player_count';
    public $sortDirection = 'asc';
    public $showSearch = false;

    public function render()
    {
        $jsonString = file_get_contents(base_path('/resources/views/livewire/searchable-table.json'));
        $json = json_decode($jsonString, true);
        $config = $json[$this->class];

        $className = '\App\Models\\' . $this->class;
        $class = new $className();
        $query = $class::query();

        if (!empty($this->search)) {
            $query->where('name', 'LIKE', "%{$this->search}%");
        }

        $query = $query->with('gameRatings')->orderBy($this->sortField, $this->sortDirection)->get()->all();
        $data = [];
        $data['config'] = $config;
        $data['query'] = $query;

        return view('livewire.searchable-table', ['data' => $data]);
    }

    public function search($name)
    {
        $this->search = $name;
    }

    public function sort($field){
        if($this->sortField === $field){
            $this->sortDirection =  $this->sortDirection === 'asc' ? 'desc' : 'asc';
        }else{
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    public function openSearch(){
        $this->
        showSearch = !$this->showSearch;
    }
}
