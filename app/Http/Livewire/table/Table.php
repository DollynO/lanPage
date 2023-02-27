<?php

namespace App\Http\Livewire\table;

use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

abstract class Table extends Component
{
    public $sortDirection = 'asc';
    public $sortBy = '';
    public $search = '';
    public $showDetail = false;
    public int $objectId;
    public $object;

    public abstract function query(): Builder;

    public abstract function columns() : array;

    public abstract function searchField() : string;


    public abstract function new();
    public abstract function detailComponent();
    public function detail($object){
        $this->showDetail = true;
        $this->object = $object;
    }

    public function data()
    {
        return $this
            ->query()
            ->when($this->search !== '', function ($query){
                $query->where($this->searchField(), 'LIKE', "%{$this->search}%");
            })
            ->get()
            ->when($this->sortBy !== '', function ($query){
                if ($this->sortDirection === 'asc'){

                    return $query->sortBy($this->sortBy);
                }
                else
                {
                    return $query->sortByDesc($this->sortBy);
                }
            });
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
