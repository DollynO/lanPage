<?php

namespace App\Http\Livewire;

use App\Models\Party;
use Livewire\Component;

class Dashboard extends Component
{
    protected $listeners = [
        'refresh' => '$refresh',
    ];

    public $selectedPartyId;
    public $availableParty;


    public function mount()
    {
        $this->selectedPartyId = Party::query()->where('is_active', true)->first()->id ?? null;
        $this->availableParty = Party::all();
    }

    public function render()
    {
        return view('livewire.dashboard');
    }

    public function selectedParty()
    {
        return Party::find($this->selectedPartyId)->with(['participants', 'meals'])->first();
    }
}
