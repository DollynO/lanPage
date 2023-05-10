<?php

namespace App\Http\Livewire\table\detail;

use Livewire\Component;
use WireUi\Traits\Actions;

abstract class Detail extends Component
{
    use Actions;

    public $inEditState;
    public $confirmDelete = false;

    public abstract function mount($object);

    public abstract function delete();

    public abstract function save();
}
