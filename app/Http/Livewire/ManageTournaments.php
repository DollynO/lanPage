<?php

namespace App\Http\Livewire;

use App\Models\Game;
use App\Models\Party;
use App\Models\TournamentRound;
use Livewire\Component;
use App\Models\Tournament;

class ManageTournaments extends Component
{
    public $tournaments;
    public $selectedTournament;
    public $name;

    public function mount()
    {
        $this->tournaments = Tournament::all();
        $this->selectedTournament = $this->tournaments->last();
    }

    public function selectTournament($tournamentId)
    {
        $this->selectedTournament = Tournament::find($tournamentId);
        if ($this->selectedTournament){
            $this->name = $this->selectedTournament->name;
            $this->date = $this->selectedTournament->date;
            $this->location = $this->selectedTournament->location;
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

    public function render()
    {
        return view('livewire.manage-tournaments');
    }
}
