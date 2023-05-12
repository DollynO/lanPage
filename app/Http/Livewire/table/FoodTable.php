<?php

namespace App\Http\Livewire\table;

use App\Models\Recipe;
use Illuminate\Database\Eloquent\Builder;

class FoodTable extends Table
{

    public $foodTable;

    /**
     * queries the table.
     */
    public function query(): Builder
    {
        return Recipe::query();
    }

    /**
     * creates the columns of the table.
     * @return array
     */
    public function columns(): array
    {
        return[
            Column::make('name','Name'),
            Column::make('description','Description'),
        ];
    }

    public function searchField(): string
    {
        return 'name';
    }

    public function getTableName(): string
    {
        return 'foodTable';
    }

    public function new(): mixed
    {
        if (Recipe::query()->where('name','New food')->count()){
            return null;
        }

        $food = new Recipe();
        $food->name = "New food";
        $food->description = '';
        $food->save();
        return null;
    }

    public function detailComponent(): ?string
    {
        return 'table.detail.food-detail';
    }
}
