<?php

namespace App\Http\Livewire\table;

use Illuminate\Database\Eloquent\Builder;

class Column
{
    public string $component = 'columns.column';
    public array $livewireParams;


    /**
     * The key|column name of the column.
     * @string
     */
    public string $key;

    /**
     * The header label.
     * @var string
     */
    public string $label;

    /**
     * A value indicating whether the column has a livewire component or not.
     * @var bool
     */
    public bool $isLivewire = false;

    /**
     * An alternative callback to sort after this column.
     * @var null
     */
    public $sortCallback = null;

    /**
     * Constructor to create a new column.
     * @param $key
     * @param $label
     */
    public function __construct($key, $label)
    {
        $this->key = $key;
        $this->label = $label;
    }

    /**
     * Static function to create a new instance.
     * @param $key
     * @param $label
     * @return static
     */
    public static function make($key, $label): static
    {
        return new static($key, $label);
    }

    /**
     * The component used to render the content of the column.
     * @param $component
     * @return $this
     */
    public function component($component): Column
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

