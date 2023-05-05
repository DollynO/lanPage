<?php

namespace App\Http\Livewire\table;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

abstract class Table extends Component
{
    protected $listeners =[
        'updateFilterData',
    ];

    /**
     * The id of the selected row.
     * @var int
     */
    public int $selectedRowId = -1;

    /**
     * The sort direction.
     * Either asc, desc.
     * @var string
     */
    public string $sortDirection = 'asc';

    /**
     * The field to sort.
     * @var string
     */
    public string $sortBy = '';


    /**
     * The search parameter.
     * @var string
     */
    public string $search = '';

    /**
     * A value indicating whether to show or hide the detail tab.
     * Is entangled in the view.
     * @var bool
     */
    public ?bool $showDetail = false;

    /**
     * The selected row as array.
     * Gets passed to the detail component.
     * @var
     */
    public $object;

    /**
     * Used for pagination.
     * The amount of entries that are displayed per page.
     * @var int
     */
    public int $perPage = 10;

    /**
     * Used for pagination.
     * The current active page.
     * @var int
     */
    public int $currentPage = 1;

    /**
     * Used fpr pagination.
     * The total page count.
     * @var int
     */
    public int $pageCount = 1;

    /**
     * Number of total records in this table.
     * @var int
     */
    public int $totalRecords = 0;

//Mandatory function. Need to be created for every instance.
    /**
     * Function to get the base query for the table.
     * @return Builder
     */
    public abstract function query(): Builder;

    /**
     * Returns an array with the columns used in the table.
     * @return array
     */
    public abstract function columns() : array;

    /**
     * The field the default search bar searches in.
     * @return string
     */
    public abstract function searchField() : string;

    /**
     * Returns the table name.
     * Used to map the properties in the view.
     * Should be unique.
     * @return string
     */
    public abstract function getTableName() : string;

    /**
     * Method to create a new entry for the tables class.
     * @return mixed
     */
    public abstract function new(): mixed;

// Optional function
    /**
     * Returns an array with the filters for the table.
     * The filters are displayed above the table.
     * @return array
     */
    public function filters() : array
    {
        return [];
    }

    /**
     * The detail component.
     * Gets displayed if the user clicks on a row.
     * @return string|null
     */
    public function detailComponent() : string|null
    {
        return null;
    }

    /**
     * Returns an array of custom buttons.
     * @return array
     */
    public function customButtons() : array
    {
        return [];
    }

    /**
     * Disables the pagination of the table.
     * Disables: ui.per page, ui.pagination button in footer, pagination in the data query.
     *
     * @return bool
     */
    public function disablePagination(): bool
    {
        return false;
    }

    /**
     * Disables the header bar of the component.
     * Disables: default search bar, filter, new button
     * @return bool
     */
    public function disableHeaderBar(): bool
    {
        return false;
    }

    /**
     * Disables the footer of the table.
     * Disables: Total record count
     * @return bool
     */
    public function disableFooterBar(): bool
    {
        return false;
    }

// Base function. Should not be changed.
    /**
     * Used to set the selected row.
     * @param $object
     * @return void
     */
    public function detail($object): void
    {
        $this->showDetail = $this->detailComponent();
        $this->object = $object;
        $this->selectedRowId = $object['id'];
    }

    /**
     * Queries the data for the table. Includes search, filters, pagination
     * @return array|Builder|Builder[]|Collection|mixed
     */
    public function data(): mixed
    {
        $data = $this
            ->query()
            ->when($this->search !== '' && $this->searchField() !== '', function ($query){
                $query->where($this->searchField(), 'LIKE', "%$this->search%");
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
            });

        if (!$this->disablePagination()){
            $data = $data->paginate($this->perPage,['*'],'page',$this->currentPage );
            $this->pageCount = $data->lastPage();
            $this->totalRecords = $data->total();
        }
        else{
            $data = $data->get();
        }

        return $data;
    }

    /**
     * Changes the sort parameter.
     * @param $key
     * @return void
     */
    public function sort($key): void
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

    /**
     * Renders the view.
     * All tables are rendered with the same view.
     * @return Application|Factory|View
     */
    public function render(): View|Factory|Application
    {
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

    /**
     * Resets filter values.
     * @return void
     */
    public function resetFilter(): void
    {
        $this->{$this->getTableName()}['filters'] = [];
        $this->search = '';
    }

    /**
     * Helper to search in an array.
     * @param $array
     * @param $key
     * @param $value
     * @return false
     */
    private function search_array($array, $key, $value)
    {
        foreach ($array as $subarray){
            if($subarray->$key == $value)
                return $subarray;
        }
        return false;
    }
}
