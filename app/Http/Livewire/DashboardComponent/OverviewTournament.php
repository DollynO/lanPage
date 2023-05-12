<?php

namespace App\Http\Livewire\DashboardComponent;

use Livewire\Component;

class OverviewTournament extends Component
{
    public $activeTab = 0;

    public function render()
    {
        return view('livewire.dashboard-component.overview-tournament');
    }
}
