<?php

namespace App\Http\Livewire\DashboardComponent;

use App\Models\Game;
use App\Models\GameSuggestion;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class GameSuggestions extends Component
{
    protected $listeners = [
        'openGameSuggestion',
        'removeGameSuggestion'
    ];
    public $party;
    public bool $suggestionOverlay = false;
    public $edit = [];
    public $editGame;


    /**
     * Deletes the selected game suggestion.
     * @param $id
     * @return void
     */
    public function removeGameSuggestion($id): void
    {
        GameSuggestion::find($id)?->first()?->delete();
    }

    /**
     * Opens the suggestion overlay.
     * @return void
     */
    public function openGameSuggestion(): void
    {
        $this->resetEditFields();
        $this->suggestionOverlay = true;
    }

    /**
     * Add a new suggestion.
     * Creates a new game or edit the selected game with the data.
     * @return void
     */
    public function addGameSuggestion(): void
    {
        $this->validate([
            'edit.name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Game::class, 'name')->ignore($this->editGame, 'id'),
            ],
            'edit.player_count' => 'required|integer|gt:0',
            'edit.price' => 'sometimes|numeric|gte:0',
            'edit.genre' => 'required|string|max:200',
            'edit.source' => 'required|string|max:255',
            'editGame' => [
                'nullable',
                'integer',
                Rule::exists(Game::class, 'id'),
                Rule::unique(GameSuggestion::class, 'game_id')
                    ->where('party_id', $this->party['id']),
            ],
        ],
        [
            'edit.name' => 'The name is required.',
            'edit.player_count' => 'The player count must be greater then 0.',
            'edit.price' => 'The price must be greater then 0',
            'edit.genre' => 'The genre is required.',
            'editGame' => 'This game was already suggested.',
        ]);

        $game = Game::query()->whereKey($this->editGame)->firstOrNew();
        if (!$this->editGame){
            $game->name = $this->edit['name'];
        }
        $game->genre = $this->edit['genre'];
        $game->player_count = $this->edit['player_count'];
        $game->price = $this->edit['price'] ?? 0;
        $game->source = $this->edit['source'];
        $game->save();

        $gameSuggestion = new GameSuggestion();
        $gameSuggestion->party_id = $this->party['id'];
        $gameSuggestion->user_id = Auth::id();
        $gameSuggestion->game_id = $game->id;
        $gameSuggestion->save();
        $this->suggestionOverlay = false;
    }


    /**
     * Fills the edit array with information of the selected game.
     * @return void
     */
    public function fillFromSelection(): void
    {
        if ($this->editGame) {
            $selectedGame = Game::query()->whereKey($this->editGame)->first();
            $this->edit = [
                'name' => $selectedGame->name ?? '',
                'genre' => $selectedGame->genre ?? '',
                'player_count' => $selectedGame->player_count ?? '',
                'price' => $selectedGame->price ?? '',
                'source' => $selectedGame->source ?? '',
            ];
        }
    }

    /**
     * Resets the edit array.
     * @return void
     */
    public function resetEditFields(): void
    {
        $this->edit = [];
        $this->editGame = null;
        $this->resetValidation();
    }

    /**
     * Renders the view.
     * @return Factory|View|Application
     */
    public function render(): Factory|View|Application
    {
        return view('livewire.dashboard-component.game-suggestions');
    }
}
