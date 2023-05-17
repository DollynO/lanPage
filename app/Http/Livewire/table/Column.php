<?php

namespace App\Http\Livewire\table;

use Closure;
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
     * A value indicating whether this column is the default sort column.
     * @var bool
     */
    public $isDefaultSortColumn = false;

    /**
     * A value indicating whether to sort default asc(true) or desc(false).
     * @var bool
     */
    public $defaultSortDirection = true;

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

    /**
     * Marks the column as livewire column.
     * @param array $params
     * @return $this
     */
    public function livewire(array $params = []) : Column
    {
        $this->isLivewire = true;
        $this->livewireParams = $params;

        return $this;
    }

    /**
     * If an alternative sort function is needed.
     * @param $callback
     * @return $this
     */
    public function sortable($callback = null) : Column
    {
        $this->sortCallback = $callback;
        return $this;
    }

    /**
     * The default sort callback for a string column.
     * @return Closure
     */
    public static function defaultSortCallback(): Closure
    {
        return function(Builder $query,string $key, bool $directionAsc){
            return $directionAsc ? $query->orderBy($key) : $query->orderByDesc($key);
        };
    }

    /**
     * Gets the sort callback. Either uses the custom callback or the default callback.
     * @return Closure
     */
    public function getSortCallback(): Closure
    {
        return $this->sortCallback ?? Column::defaultSortCallback();
    }

    /**
     * Marks the column as default sort column.
     * @return $this
     */
    public function defaultSortColumn(bool $asc = true) : Column
    {
        $this->isDefaultSortColumn = true;
        $this->defaultSortDirection = $asc;
        return $this;
    }
}

