<?php

namespace App\Http\Livewire\DashboardComponent;

use App\Models\Tournament;
use Livewire\Component;

class OverviewTournament extends Component
{
    public $activeTab;
    public $tournament;


    protected $listeners = [
        'leaveSuggestionsView' => 'viewLeaderboard',
        'leaveLeaderboardView' => 'viewGameSuggestions',
    ];

    public function mount()
    {
        $this->tournament = Tournament::latest('created_at')->first();
        if (isset($this->tournament))
        {
            $this->activeTab = $this->tournament->are_suggestions_closed ? 1 : 0;
        }
    }

    public function render()
    {
        return view('livewire.dashboard-component.overview-tournament');
    }

    public function viewLeaderboard(){
        $this->changeTab(1);
    }

    public function viewGameSuggestions()
    {
        $this->changeTab(0);
    }

    public function changeTab($tabIndex)
    {
        $this->activeTab = $tabIndex;
    }
}
