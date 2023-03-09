<div>
    <x-input
        wire:model="{{ $tableComponent->getTableName() }}.filters.{{ $filter->key }}"
        wire:key="{{ $tableComponent->getTableName() }}-filter-{{ $filter->key }}"
        id="{{ $tableComponent->getTableName() }}-filter-{{ $filter->key }}"
        label="{{$filter->getConfig('label')}}" placeholder="{{$filter->getConfig('placeholder')}}"/>
</div>
