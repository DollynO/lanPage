<?php

namespace App\Http\Livewire\table;

use App\Http\Livewire\Column;
use App\Models\Game;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;

class GameTable extends Table
{
    public function query(): Builder
    {
        return Game::query()->with(['ratings', 'userRating']);
    }

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

    public function searchField(): string
    {
        return 'name';
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
