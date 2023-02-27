<?php

namespace App\Http\Livewire\table\detail;

use App\Models\Party;

class PartyDetail extends Detail
{
    public $party;

    protected $rules =[
        'party.id' => 'sometimes|required|exists:parties,id',
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
        $party->start_date = $validatedData['party']['name'];
        $party->end_date = $validatedData['party']['note'];
        $party->location = $validatedData['party']['source'];
        $party->save();
        $this->party = $party->toArray();
        $this->inEditState = false;
    }
}
