<?php

namespace App\Http\Livewire;

use App\Models\Party;
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
        $tournament->save();

        $this->tournaments = Tournament::all();
        $this->selectedTournament = $tournament;
        $this->name = null;
    }

    public function render()
    {
        return view('livewire.manage-tournaments');
    }
}
