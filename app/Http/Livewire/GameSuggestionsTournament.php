<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\Suggestion;
use App\Models\Game;
use WireUi\Traits\Actions;

class GameSuggestionsTournament extends Component
{
    use Actions;

    public $suggestions;
    public $suggestionsLeft;

    public $query;
    public $games;
    public $highlightIndex;

    protected $listeners = ['suggestionAdded' => 'updateSuggestions'];

    public function mount()
    {
        $this->resetSearchbar();
    }

    public function render()
    {
        $this->suggestions = $this->retrieveSuggestions();
        $this->updateAmountSuggestionsLeft();

        return view('livewire.game-suggestions-tournament');
    }

    public function retrieveSuggestions()
    {
        return Suggestion::with('game')->get()->unique('game_id');
    }

    public function updateAmountSuggestionsLeft()
    {
        $this->suggestionsLeft = 3 - Suggestion::where('user_id', Auth::id())->count();
    }

    public function updateSuggestions()
    {
        $this->suggestions = $this->retrieveSuggestions();
        $this->updateAmountSuggestionsLeft();
    }

    /*
     * UI-Functions the user can activly interact with.
     */

    public function increaseVotes($suggestionId)
    {
        if ($this->suggestionsLeft < 1){
            $this->notification()->error('No suggestions left.', 'You only have 3 votes for the tournament.');
            $this->resetSearchbar();
            return;
        }

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

    /*
     * Dropdown search functions.
     */

    public function resetSearchbar()
    {
        $this->query = '';
        $this->games = [];
        $this->highlightIndex = 0;
    }

    public function incrementHighlight()
    {
        if ($this->highlightIndex === count($this->games) - 1)
        {
            $this->highlightIndex = 0;
            return;
        }
        $this->highlightIndex++;
    }

    public function decrementHighlight()
    {
        if ($this->highlightIndex === 0) {
            $this->highlightIndex = count($this->games) - 1;
            return;
        }
        $this->highlightIndex--;
    }

    public function selectHighlightedGame()
    {
        $game = $this->games[$this->highlightIndex] ?? null;
        if ($game)
        {
            $this->selectGame($game->id);
        }
    }

    public function selectGame($gameId)
    {
        if ($this->suggestionsLeft < 1){
            $this->notification()->error('No suggestions left.', 'You only have 3 votes for the tournament.');
            $this->resetSearchbar();
            return;
        }

        $game = Game::find($gameId);
        if ($game) {
            $suggestion = Suggestion::query()->where('game_id', $game->id)->where('user_id', Auth::id())->first();
            if ($suggestion){
                $this->notification()->error('You already voted for this game.', 'You can only vote for a game once.');
            }else{
                Suggestion::create(['game_id' => $game->id, 'user_id' => Auth::id()]);
                $this->emit('suggestionAdded');
            }
        }

        $this->resetSearchbar();
    }

    public function updatedQuery()
    {
        $this->games = Game::query()->where('name', 'like', '%' . $this->query . '%')
            ->get()
            ->toArray();
    }
}