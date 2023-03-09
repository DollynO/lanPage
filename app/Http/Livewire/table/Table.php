<?php

namespace App\Http\Livewire\table;

use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Illuminate\Support\Collection;

abstract class Table extends Component
{
    protected $listeners =[
        'updateFilterData',
    ];

    public $sortDirection = 'asc';
    public $sortBy = '';
    public $search = '';
    public $showDetail = false;
    public int $objectId;
    public $object;

    public abstract function query(): Builder;

    public abstract function columns() : array;
    public abstract function filters() : array;

    public abstract function searchField() : string;

    abstract public function getTableName() : string;

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
            })->when(
                count($this->filters()) > 0,
                function ($query)
                {
                    foreach ($this->filters() as $filter){
                        if (array_key_exists($filter->key, $this->{$this->getTableName()}['filters'])
                            && $this->{$this->getTableName()}['filters'][$filter->key])
                        {
                            $query = ($filter->filterCallback)(
                                $query,
                                $this->{$this->getTableName()}['filters'][$filter->key]
                            );
                        }
                    }
                    return $query;
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
            $this->sortDirection = $this->sortDirection === 'asc'
                ? 'desc'
                : 'asc';

            return;
        }

        $this->sortBy = $key;
        $this->sortDirection = 'asc';
    }

    public function render(){
        return view('livewire.table.table', ['data' => $this->data(), 'component' => $this]);
    }


    /**
     * Runs on every request, immediately after the component is instantiated, but before any other lifecycle methods are called
     */
    public function boot(): void
    {
        $this->{$this->getTableName()} = [
            'filters' => $this->{$this->getTableName()}['filters'] ?? [],
        ];

    }

    public function getFilters() : collection
    {
        return collection($this->filters());
    }
}
