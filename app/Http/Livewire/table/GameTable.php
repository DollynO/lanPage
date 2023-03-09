<?php

namespace App\Http\Livewire\table;

use App\Http\Livewire\table\columnFilter\NumberFilter;
use App\Http\Livewire\table\columnFilter\TextFilter;
use App\Models\Game;
use Illuminate\Database\Eloquent\Builder;

class GameTable extends Table
{

    public $gameTable;

    /**
     * queries the table.
     * @return Builder
     */
    public function query(): Builder
    {
        return Game::query()->with(['ratings', 'userRating']);
    }

    /**
     * creates the columns of the table.
     * @return array
     */
    public function columns(): array
    {
        return[
            Column::make('name','Name'),
            Column::make('player_count', 'Player'),
            Column::make('price', 'Price')
            ->component('columns.numeric'),
            Column::make('source','Source'),
            Column::make('already_played','Already played')
            ->component('columns.checkbox'),
            Column::make('rating', 'Rating')
            ->component('components.star-rating')
            ->livewire(),
        ];
    }

    /**
     * filters for the column.
     * @return array
     */
    public function filters(): array
    {
        return [
            TextFilter::make('name','Name')
            ->setConfig(['placeholder' => 'Name', 'label' => 'Name'])
            ->setFilterCallback(function ($query, $value){
                return $query->where('name','like', '%'.$value.'%');
            }),
            NumberFilter::make('player_count','Player')
            ->setConfig(['minValue' => 1, 'maxValue' => 99])
            ->setFilterCallback(function (Builder $query, $value){
                return count($value) == 2 && !in_array("",$value) ? $query->whereBetween('player_count',$value) : $query;
            })
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

    public function new()
    {
        return redirect()->route('new_game');
    }

    public function detailComponent()
    {
        return 'table.detail.game-detail';
    }
}
