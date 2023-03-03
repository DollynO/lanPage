<?php

namespace App\Http\Livewire\table;

use App\Http\Livewire\Column;
use App\Models\Game;
use App\Models\Party;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;

class PartyTable extends Table
{
    public function query(): Builder
    {
        return Party::query();
    }

    public function columns(): array
    {
        return[
            Column::make('start_date','Start date'),
            Column::make('end_date', 'End date'),
            Column::make('location', 'Location'),
            Column::make('rating','Rating')
                ->component('components.star-rating')
                ->livewire(),
            Column::make('participants', 'Participants'),
        ];
    }

    public function searchField(): string
    {
        return '';
    }

    public function new()
    {
        return redirect()->route('new_party');
    }

    public function detailComponent()
    {
        return 'table.detail.game-detail';
    }
}
