<?php

namespace App\Http\Livewire\table;

use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

abstract class Table extends Component
{
    public $sortBy = '';
    public $sortDirection = 'asc';
    public $search = '';

    public abstract function query(): Builder;

    public abstract function columns() : array;

    public abstract function searchField() : string;

    public abstract function new();

    public function data()
    {
        return $this
            ->query()
            ->when($this->search !== '', function ($query){
                $query->where($this->searchField(), 'LIKE', "%{$this->search}%");
            })
            ->when($this->sortBy !== '', function ($query){
                $query->orderBy($this->sortBy,
                $this->sortDirection);
            })
            ->get();
    }

    public function sort($key)
    {
        if ($this->sortBy === $key)
        {
            $directoin = $this->sortDirection === 'asc'
                ? 'desc'
                : 'asc';
            $this->sortDirection = $directoin;

            return;
        }

        $this->sortBy = $key;
        $this->sortDirection = 'asc';
    }

    public function render(){
        return view('livewire.table.table', ['data' => $this->data()]);
    }
}
