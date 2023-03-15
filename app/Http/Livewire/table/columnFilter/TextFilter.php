<?php

namespace App\Http\Livewire\table\columnFilter;

use App\Http\Livewire\table\Table;
use Illuminate\Database\Eloquent\Builder;

class TextFilter extends ColumnFilter
{
    public function render(Table $table)
    {
        return view(
            'livewire.table.columnFilter.text-filter', ['tableComponent' => $table, 'filter' => $this]);
    }

    public static function defaultCallback()
    {
        return function ($query, $key, $value){
            return $query->where($key,'like', '%'.$value.'%');
        };
    }
}
