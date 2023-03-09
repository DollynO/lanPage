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

    public function filter(Builder $query, $filterVal) : Builder
    {
        return $query->when(
            !empty($this->value),
            function ($q) use($filterVal){
                return $q->where($this->key,$filterVal);
            });
    }
}
