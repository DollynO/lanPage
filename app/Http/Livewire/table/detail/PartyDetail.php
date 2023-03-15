<?php

namespace App\Http\Livewire\table\detail;

use App\Models\Party;

class PartyDetail extends Detail
{
    public $party;

    protected $rules =[
        'party.id' => 'sometimes|required|exists:parties,id',
        'party.is_active' => 'required|boolean',
        'party.location' => 'required|string',
        'party.start_date' => 'required|date',
        'party.end_date' => 'required|date|after:start_date'
    ];

    public function mount($object){
        $this->party = $object;
    }

    public function render()
    {
        return view('livewire.table.detail.party-detail');
    }

    public function delete()
    {
        $party = Party::query()->whereKey($this->party['id'])->first();
        $party->delete();
    }

    public function save()
    {
        $validatedData = $this->validate();

        $party = Party::query()->whereKey($this->party['id'])->first();
        $party->start_date = $validatedData['party']['start_date'];
        $party->end_date = $validatedData['party']['end_date'];
        $party->is_active = $validatedData['party']['is_active'];
        $party->location = $validatedData['party']['location'];
        $party->save();
        $this->party = $party->toArray();
        $this->inEditState = false;
    }
}
