<?php

namespace App\Http\Livewire;

use App\Models\Party;
use Livewire\Component;

class Dashboard extends Component
{
    protected $listeners = [
        'refreshComponent',
    ];

    public array $selectedParty;
    public array $availableParty;

    public function mount(){
        $this->selectedParty = Party::query()->with(['participants'])->where('is_active', true)->first()->toArray();
        $this->availableParty = Party::all()->toArray();
    }

    public function render()
    {
        return view('livewire.dashboard');
    }

    public function refreshComponent()
    {
        $this->selectedParty = Party::query()->with(['participants'])->where('id', $this->selectedParty['id'])->first()->toArray();
    }
}
