<?php

namespace App\Http\Livewire\table;

use Illuminate\Database\Eloquent\Builder;

class Column
{
    public string $component = 'columns.column';
    public array $livewireParams;

    public string $key;

    public string $label;
    public bool $isLivewire = false;
    public $sortCallback = null;

    public function __construct($key, $label)
    {
        $this->key = $key;
        $this->label = $label;
    }


    public static function make($key, $label)
    {
        return new static($key, $label);
    }

    public function component($component)
    {
        $this->component = $component;

        return $this;
    }

    public function livewire($params = [])
    {
        $this->isLivewire = true;
        $this->livewireParams = $params;

        return $this;
    }

    public function sortable($callback = null) {
        $this->sortCallback = $callback;
        return $this;
    }

    public static function defaultSortCallback()
    {
        return function(Builder $query,string $key, bool $directionAsc){
            return $directionAsc ? $query->orderBy($key) : $query->orderByDesc($key);
        };
    }

    public function getSortcallback(){
        return $this->sortCallback ?? Column::defaultSortCallback();
    }
}

