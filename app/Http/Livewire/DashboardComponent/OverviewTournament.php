<?php

namespace App\Http\Livewire\DashboardComponent;

use Livewire\Component;

class OverviewTournament extends Component
{
    public $activeTab = 0;

    protected $listeners = [
        'leaveSuggestionsView' => 'viewLeaderboard',
        'leaveLeaderboardView' => 'viewGameSuggestions',
    ];

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
