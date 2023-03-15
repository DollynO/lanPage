<?php

namespace App\Http\Livewire\table\detail;

use Livewire\Component;

abstract class Detail extends Component
{
    public $inEditState;
    public $confirmDelete = false;

    public abstract function mount($object);

    public abstract function delete();

    public abstract function save();
}
