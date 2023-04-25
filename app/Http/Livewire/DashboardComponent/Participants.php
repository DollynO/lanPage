<?php

namespace App\Http\Livewire\DashboardComponent;

use App\Models\Party;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Participants extends Component
{
    public $party;
    public $takesPart;

    public function render()
    {;
        if ($this->party->participants) {
            foreach ($this->party->participants as $val) {
                if ($val->id === Auth::id()) {
                    $this->takesPart = true;
                }
            }
        }

        return view('livewire.dashboard-component.participants');
    }

    public function takePart($assign)
    {
        $party = $this->party;
        $assign
            ? $party->participants()->attach(Auth::id(), ['start_day' => $party->start_date, 'end_day' => $party->end_date])
            : $party->participants()->detach(Auth::id());
        $party->save();
        $this->emit('refresh');
    }
}
