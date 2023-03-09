<?php

namespace App\Http\Livewire\table\columnFilter;

use App\Http\Livewire\table\Table;

class NumberFilter extends ColumnFilter
{
    // A second value.
    public string $secondValue;

    public function render(Table $table)
    {
        return view('livewire.table.columnFilter.number-filter', ['tableComponent' => $table, 'filter' => $this]);
    }
}
