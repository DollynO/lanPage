<?php

namespace App\Http\Livewire;

use App\Models\Tournament;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Models\Suggestion;
use App\Models\Game;
use WireUi\Traits\Actions;


class GameSuggestionsTournament extends Component
{
    use Actions;

    public $tournament;
    public $suggestions;
    public $suggestionsLeft;

    public $query;
    public $games;
    public $highlightIndex;

    protected $listeners = ['suggestionAdded' => 'updateSuggestions'];

    public function mount()
    {
        $this->tournament = Tournament::latest('created_at')->first();
        $this->resetSearchbar();
    }

    public function render()
    {
        $this->suggestions = $this->retrieveSuggestions();
        $this->updateAmountSuggestionsLeft();

        return view('livewire.game-suggestions-tournament');
    }

    public function signalLeaveViewToParent()
    {
        $this->emitUp('leaveSuggestionsView');
    }

    public function retrieveSuggestions()
    {
        $gameIdCounts = Suggestion::query()
            ->select('game_id', DB::raw('count(*) as count'))
            ->groupBy('game_id')
            ->pluck('count', 'game_id');

        $suggestions = Suggestion::with('game')->get()->unique('game_id');

        return $suggestions->sortByDesc(function ($suggestion) use ($gameIdCounts) {
            return $gameIdCounts[$suggestion->game_id] ?? 0;
        });
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
        if ($this->tournament->are_suggestions_closed)
        {
            $this->notification()->error('Voting is closed.');
            return;
        }

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
        if ($this->tournament->are_suggestions_closed)
        {
            $this->notification()->error('Voting is closed.');
            return;
        }

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
        if ($this->tournament->are_suggestions_closed)
        {
            $this->notification()->error('Voting is closed.');
            return;
        }

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
        if ($this->tournament->are_suggestions_closed)
        {
            $this->notification()->error('Voting is closed.');
            return;
        }

        $game = $this->games[$this->highlightIndex] ?? null;
        if ($game)
        {
            $this->selectGame($game->id);
        }
    }

    public function selectGame($gameId)
    {
        if ($this->tournament->are_suggestions_closed)
        {
            $this->notification()->error('Voting is closed.');
            return;
        }

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
