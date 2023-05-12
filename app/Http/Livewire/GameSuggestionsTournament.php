<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\Suggestion;
use App\Models\Game;

class GameSuggestionsTournament extends Component
{
    public $searchTerm;
    public $suggestions;
    public $suggestionsLeft;

    protected $listeners = ['suggestionAdded' => 'updateSuggestions'];

    public function render()
    {
        $this->suggestions = $this->retrieveSuggestions();
        $this->updateAmountSuggestionsLeft();

        $games = Game::where('name', 'like', '%' . $this->searchTerm . '%')->get();

        return view('livewire.game-suggestions-tournament', [
            'games' => $games,
        ]);
    }

    public function selectGame($gameId)
    {
        $game = Game::find($gameId);
        if ($game) {
            Suggestion::create(['game_id' => $game->id, 'user_id' => Auth::id()]);
            $this->emit('suggestionAdded');
        }

        $this->searchTerm = '';
    }

    public function updateSuggestions()
    {
        $this->suggestions = $this->retrieveSuggestions();
        $this->updateAmountSuggestionsLeft();
    }

    public function updateAmountSuggestionsLeft()
    {
        $this->suggestionsLeft = 3 - Suggestion::where('user_id', Auth::id())->count();
    }

    public function retrieveSuggestions()
    {
        return Suggestion::with('game')->get()->unique('game_id');
    }

    public function increaseVotes($suggestionId)
    {
        $suggestion = Suggestion::find($suggestionId);
        if ($suggestion) {
            Suggestion::create(['game_id' => $suggestion->game_id, 'user_id' => Auth::id()]);
            $this->emit('suggestionAdded');
        }
    }

    public function decreaseVotes($suggestion)
    {
        $userSuggestion = Suggestion::where('game_id', $suggestion['game_id'])
            ->where('user_id', Auth::id())
            ->first();

        if ($userSuggestion) {
            $userSuggestion->delete();
            $this->emit('suggestionAdded');
        }
    }

    public function removeSuggestion($suggestionId)
    {
        $suggestion = Suggestion::find($suggestionId);
        if ($suggestion) {
            $suggestion->delete();
            $this->emit('suggestionAdded');
        }
    }
}
