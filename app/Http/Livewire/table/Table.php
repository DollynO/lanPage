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
    public $object;

    public $perPage = 10;
    public $currentPage = 1;
    public $pageCount = 1;
    public $totalRecords = 0;

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
        $data = $this
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
                                $filter->key,
                                $this->{$this->getTableName()}['filters'][$filter->key]
                            );
                        }
                    }
                    return $query;
                })
            ->when($this->sortBy !== '', function($query){
                ($this->search_array($this->columns(), 'key', $this->sortBy)
                        ->getSortCallback())($query, $this->sortBy, $this->sortDirection === 'asc');
            })
            ->paginate($this->perPage,['*'],'page',$this->currentPage );
        $this->pageCount = $data->lastPage();
        $this->totalRecords = $data->total();
        return $data;
    }

    public function sort($key)
    {
        $this->currentPage = 1;
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

    public function resetFilter()
    {
        $this->{$this->getTableName()}['filters'] = [];
        $this->search = '';
    }

    private function search_array($array, $key, $value){
        foreach ($array as $subarray){
            if($subarray->$key == $value)
                return $subarray;
        }
        return false;
    }
}
