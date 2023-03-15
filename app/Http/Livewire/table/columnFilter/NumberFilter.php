<?php

namespace App\Http\Livewire\table\columnFilter;

use App\Http\Livewire\table\Table;

class NumberFilter extends ColumnFilter
{
    public function render(Table $table)
    {
        return view('livewire.table.columnFilter.number-filter', ['tableComponent' => $table, 'filter' => $this]);
    }

    public static function defaultCallback()
    {
        return function($query, $key, $value){
            return count($value) == 2 && !in_array("",$value) ? $query->whereBetween($key,$value) : $query;
        };
    }
}
