<?php

namespace App\Http\Livewire\table;

use App\Models\GameSuggestion;
use Illuminate\Database\Eloquent\Builder;

class GameSuggestionDashboardTable extends Table
{

    public $gameSuggestionDashboardTable;
    public $party;

    /**
     * queries the table.
     */
    public function query(): Builder
    {
        return GameSuggestion::query()->where('party_id', $this->party->id)
            ->with(['game', 'party', 'user']);
    }

    /**
     * creates the columns of the table.
     * @return array
     */
    public function columns(): array
    {
        return [
            Column::make('game.name', 'Name'),
            Column::make('game.genre', 'Genre'),
            Column::make('game.player_count', 'Player'),
            Column::make('game.price', 'Price')
                ->component('columns.numeric'),
            Column::make('game.source', 'Source'),
            Column::make('user.name', 'Suggester'),
        ];
    }

    /**
     * Creates custom buttons.
     * @return array
     */
    public function customButtons() : array
    {
        return [
            CustomButton::make('Suggest', 'suggestGame'),
            CustomButton::make('Remove suggestion', 'removeSuggestion')
                ->enableCondition('!(selectedRowId > 0)')
        ];
    }

    /**
     * filters for the column.
     */
    public function filters(): array
    {
        return [];
    }

    public function searchField(): string
    {
        return '';
    }

    public function getTableName(): string
    {
        return 'gameSuggestionDashboardTable';
    }

    public function tableTitle(): string
    {
        return 'Game suggestions';
    }

    public function disablePagination(): bool
    {
        return true;
    }

    public function disableHeaderBar(): bool
    {
        return true;
    }

    public function disableFooterBar(): bool
    {
        return true;
    }

    public function new(): mixed
    {
        return null;
    }

    public function detailComponent(): ?string
    {
        return null;
    }

    public function suggestGame()
    {
        $this->emit('openGameSuggestion');
    }

    public function removeSuggestion()
    {

        $this->emit('removeGameSuggestion', ['id'=>$this->object['id']]);
    }
}
