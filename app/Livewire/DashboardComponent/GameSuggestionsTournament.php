<?php

namespace App\Livewire\DashboardComponent;

use App\Livewire\On;
use App\Models\Game;
use App\Models\Suggestion;
use App\Models\Tournament;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;


class GameSuggestionsTournament extends Component
{
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
        $this->suggestions = $this->retrieveSuggestions($this->tournament?->id);
        $this->updateAmountSuggestionsLeft();

        return view('livewire.dashboard-component.game-suggestions-tournament');
    }

    public function signalLeaveViewToParent()
    {
        $this->dispatch('leaveSuggestionsView');
    }

    public function retrieveSuggestions($tournament_id)
    {
        $gameIdCounts = Suggestion::query()
            ->where('tournament_id', $tournament_id)
            ->select('game_id', DB::raw('count(*) as count'))
            ->groupBy('game_id')
            ->pluck('count', 'game_id');

        $suggestions = Suggestion::with('game')
            ->where('tournament_id', $tournament_id)
            ->get()
            ->unique('game_id');

        return $suggestions->sortByDesc(function ($suggestion) use ($gameIdCounts) {
            return $gameIdCounts[$suggestion->game_id] ?? 0;
        });
    }

    public function updateAmountSuggestionsLeft()
    {
        $this->suggestionsLeft = 3 - Suggestion::where('user_id', Auth::id())
                ->where('tournament_id', $this->tournament->id)
                ->count();
    }

    #[On('suggestionAdded')]
    public function updateSuggestions()
    {
        $this->suggestions = $this->retrieveSuggestions($this->tournament?->id);
        $this->updateAmountSuggestionsLeft();
    }

    /*
     * UI-Functions the user can activly interact with.
     */
    public function increaseVotes($suggestionId)
    {
        if ($this->tournament->are_suggestions_closed)
        {
            Notification::make()
                ->title(    'Voting is closed.')
                ->danger()
                ->send();
            return;
        }

        if ($this->suggestionsLeft < 1){
            Notification::make()
                ->title('No suggestions left.')
                ->body('You only have 3 votes for the tournament.')
                ->danger()
                ->send();
            $this->resetSearchbar();
            return;
        }

        $suggestion = Suggestion::find($suggestionId);
        if ($suggestion) {
            Suggestion::create(['game_id' => $suggestion->game_id, 'user_id' => Auth::id(), 'tournament_id' => $this->tournament?->id]);
            $this->dispatch('suggestionAdded');
        }
    }

    public function decreaseVotes($suggestion)
    {
        if ($this->tournament->are_suggestions_closed)
        {
            Notification::make()
                ->title('Voting is closed.')
                ->danger()
                ->send();
            return;
        }

        $userSuggestion = Suggestion::where('game_id', $suggestion['game_id'])
            ->where('user_id', Auth::id())
            ->where('tournament_id', $this->tournament?->id)
            ->first();

        if ($userSuggestion) {
            $userSuggestion->delete();
            $this->dispatch('suggestionAdded');
        }
    }

    public function removeSuggestion($suggestionId)
    {
        if ($this->tournament->are_suggestions_closed)
        {
            Notification::make()
                ->title('Voting is closed.')
                ->danger()
                ->send();
            return;
        }

        $suggestion = Suggestion::find($suggestionId);
        if ($suggestion) {
            $suggestion->delete();
            $this->dispatch('suggestionAdded');
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
            Notification::make()
                ->title('Voting is closed.')
                ->danger()
                ->send();
            return;
        }

        $game = $this->games[$this->highlightIndex] ?? null;
        if ($game)
        {
            $this->selectGame($game['id']);
        }
    }

    public function selectGame($gameId)
    {
        if ($this->tournament->are_suggestions_closed)
        {
            Notification::make()
                ->title('Voting is closed.')
                ->danger()
                ->send();
            return;
        }

        if ($this->suggestionsLeft < 1){
            Notification::make()
                ->title('No suggestions left.')
                ->body('You only have 3 votes for the tournament.')
                ->danger()
                ->send();
            $this->resetSearchbar();
            return;
        }

        $game = Game::find($gameId);
        if ($game) {
            $suggestion = Suggestion::query()
                ->where('tournament_id', $this->tournament?->id)
                ->where('game_id', $game->id)
                ->where('user_id', Auth::id())->first();
            if ($suggestion){
                Notification::make()
                    ->title('You already voted for this game.')
                    ->body('You can only vote for a game once.')
                    ->danger()
                    ->send();
            }else{
                Suggestion::create(['game_id' => $game->id, 'user_id' => Auth::id(), 'tournament_id' => $this->tournament?->id]);
                $this->dispatch('suggestionAdded');
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
