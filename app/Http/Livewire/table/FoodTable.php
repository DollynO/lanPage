<?php

namespace App\Http\Livewire\table;

use App\Models\Recipe;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class FoodTable extends Table
{

    public $foodTable;

    /**
     * queries the table.
     */
    public function query(): Builder
    {
        return Recipe::with(['ratings', 'userRating'])
            ->withAvg('ratings', 'rating');
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
            Column::make('rating', 'Rating')
                ->component('components.star-rating')
                ->livewire()
                ->sortable(function(Builder $query, string $key, bool $directionAsc)
                {
                    // order by the avg field we create in the query withAVG()
                    return $query->orderByRaw(DB::raw('ratings_avg_rating '. ($directionAsc ? 'ASC' : 'DESC')));
                }),
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
