<?php

namespace App\Http\Livewire;

use App\Models\Game;
use App\Models\Party;
use App\Models\Suggestion;
use App\Models\TournamentRound;
use App\Models\TournamentRoundUser;
use Livewire\Component;
use App\Models\Tournament;
use WireUi\Traits\Actions;

class ManageTournaments extends Component
{
    use Actions;

    public $tournaments;
    public $selectedTournament;
    public $totalSuggestions;
    public $name;
    public $tournamentRounds;
    public $selectedTournamentRound;
    public $tournamentRoundUsers;

    protected $rules = [
        'tournamentRoundUsers.*.points' => 'required|numeric',
    ];

    public function mount()
    {
        $this->tournaments = Tournament::all();
        $this->selectTournament($this->tournaments->last()->id);
    }

    public function selectTournament($tournamentId)
    {
        $this->selectedTournament = Tournament::find($tournamentId);
        if ($this->selectedTournament){
            $this->name = $this->selectedTournament->name;
            $this->date = $this->selectedTournament->date;
            $this->location = $this->selectedTournament->location;

            $this->tournamentRounds = $this->selectedTournament->rounds;
            $this->totalSuggestions = Suggestion::all()->count();
        }
    }

    public function deleteTournament($tournamentId)
    {
        Tournament::destroy($tournamentId);
        $this->tournaments = Tournament::all();
        $this->selectedTournament = null;
        $this->name = null;
    }

    public function createTournament()
    {
        $tournament = new Tournament;
        $tournament->name = "Tournament " . today()->format('m.Y');
        $tournament->party_id = Party::all()->last()->id;
        $tournament->are_suggestions_closed = false;
        $tournament->is_completed = false;
        // The following two should be set by the default, but arent, so for now i hardcode them here.
        $tournament->amount_rounds = 4;
        $tournament->amount_game_votes = 3;
        $tournament->save();

        // Decoy rounds
        for ($i = 0; $i < $tournament->amount_rounds; $i++){
            $tournamentRound = new TournamentRound;
            $tournamentRound->tournament_id = $tournament->id;
            $tournamentRound->game_id = Game::all()->first()->id;
            $tournamentRound->round_number = $i;
            $tournamentRound->rules = "There is only one rule: There are no rules.";
            $tournamentRound->is_decoy = true;
            $tournamentRound->save();
        }

        $this->tournaments = Tournament::all();
        $this->selectedTournament = $tournament;
        $this->name = null;
    }

    public function toggleSuggestionsClosed()
    {
        $this->selectedTournament->are_suggestions_closed = !$this->selectedTournament->are_suggestions_closed;
        $this->selectedTournament->save();
        $this->tournaments = Tournament::all();
    }

    public function toggleCompleted()
    {
        $this->selectedTournament->is_completed = !$this->selectedTournament->is_completed;
        $this->selectedTournament->save();
        $this->tournaments = Tournament::all();
    }

    public function selectTournamentRound($tournamentRoundId)
    {
        $this->selectedTournamentRound = TournamentRound::find($tournamentRoundId);
        $this->tournamentRoundUsers = TournamentRoundUser::query()
            ->where('tournament_round_id', $tournamentRoundId)->get();

    }

    public function rollGameForRound($tournamentRoundId)
    {
        if ($this->totalSuggestions < $this->selectedTournament->amount_rounds){
            $this->notification()->error('Not enough suggestions!',
                'There have to be at least ' . $this->selectedTournament->amount_rounds . ' unique suggestions to roll for a game.');
            return;
        }

        $rolledGameIds = TournamentRound::query()->where('is_decoy', false)->get()?->pluck('game_id');
        $suggestions = Suggestion::query()->whereNotIn('game_id', $rolledGameIds)->get();

        if ($suggestions->count() == 0){
            $this->notification()->error('Not enough suggestions!',
                'There have to be at least ' . $this->selectedTournament->amount_rounds . ' unique suggestions to roll for a game.');
            return;
        }

        $rolledSuggestion = $suggestions[array_rand($suggestions->toArray())];
        if ($rolledSuggestion) {
            $tournamentRound = TournamentRound::find($tournamentRoundId);

            $tournamentRound->game_id = $rolledSuggestion->game_id;
//            $tournamentRound->rules = $rolledSuggestion->rules;
            $tournamentRound->is_decoy = false;
            $tournamentRound->save();
        }

        $this->selectTournament($this->selectedTournament->id);
//        $this->tournamentRounds = $this->selectedTournament->rounds;
    }

    public function createUserResults()
    {
        foreach (Party::query()->where('is_active', true)->first()?->participants as $user) {
            $userResult = new TournamentRoundUser;
            $userResult->tournament_round_id = $this->selectedTournamentRound->id;
            $userResult->user_id = $user->id;
            $userResult->points = 0;
            $userResult->has_won = false;

            $userResult->save();
        }

        $this->tournamentRoundUsers = TournamentRoundUser::query()->where('tournament_round_id', $this->selectedTournamentRound->id)->get();
    }

    public function saveUserResults()
    {
        foreach ($this->tournamentRoundUsers as $userResult) {
            $userResult->save();
        }

        $this->notification()->info('Results saved');
    }

    public function render()
    {
        return view('livewire.manage-tournaments');
    }
}
