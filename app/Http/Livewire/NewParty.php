<?php

namespace App\Http\Livewire;

use App\Models\Party;
use Livewire\Component;

class NewParty extends Component
{
    public $start_date;
    public $end_date;
    public string $location;

    protected $rules = [
        'start_date' => 'required|date',
        'end_date' => 'required|date|after:startDate',
        'location' => 'required|string'
    ];

    public function render()
    {
        return view('livewire.new-party');
    }

    public function save()
    {
        $validatedData = $this->validate();
        $party = new Party();
        $party->fill($validatedData);
        $party->save();
        redirect()->to('/parties');
    }
}
