<?php

namespace App\Http\Livewire\table;

use App\Http\Livewire\table\columnFilter\NumberFilter;
use App\Http\Livewire\table\columnFilter\TextFilter;
use App\Models\Game;
use App\Models\Rating;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class GameTable extends Table
{

    public $gameTable;

    /**
     * queries the table.
     */
    public function query(): Builder
    {
        return Game::with(['ratings', 'userRating'])
            ->withAvg('ratings', 'rating');
    }

    /**
     * creates the columns of the table.
     * @return array
     */
    public function columns(): array
    {
        return[
            Column::make('name','Name')
            ->defaultSortColumn(),
            Column::make('player_count', 'Player'),
            Column::make('price', 'Price')
            ->component('columns.numeric'),
            Column::make('source','Source'),
            Column::make('already_played','Already played')
            ->component('columns.checkbox'),
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

    /**
     * filters for the column.
     */
    public function filters(): array
    {
        return [
            TextFilter::make('name','Name')
            ->setConfig(['placeholder' => 'Name', 'label' => 'Name'])
            ->setFilterCallback(TextFilter::defaultCallback()),
            NumberFilter::make('player_count','Player')
            ->setConfig(['minValue' => 1, 'maxValue' => 99])
            ->setFilterCallback(NumberFilter::defaultCallback()),
            NumberFilter::make('rating', 'Rating')
            ->setConfig(['minValue' => 1, 'maxValue' => 5])
            ->setFilterCallback(function(Builder $query, $key, $value){
                return count($value) == 2 && !in_array("",$value)
                    ? $query->withAvg('ratings as avg_rating', 'rating')
                        ->havingBetween('avg_rating', $value)
                    : $query;
            }),
        ];
    }

    public function searchField(): string
    {
        return 'name';
    }

    public function getTableName(): string
    {
        return 'gameTable';
    }

    public function new(): mixed
    {
        return redirect()->route('new_game');
    }

    public function detailComponent(): ?string
    {
        return 'table.detail.game-detail';
    }
}
