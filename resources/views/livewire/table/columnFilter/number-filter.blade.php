<div>
    <div class="flex flex-row">
        <x-input
            wire:model="{{ $tableComponent->getTableName() }}.filters.{{ $filter->key }}.0"
            wire:key="{{ $tableComponent->getTableName() }}-filter-{{ $filter->key }}0"
            id="{{ $tableComponent->getTableName() }}-filter-{{ $filter->key }}0"
            label="{{__('From')}}" placeholder="{{$filter->getConfig('minValue')}}"/>
        <label class="mt-4 px-2">-</label>
        <x-input
            wire:model="{{ $tableComponent->getTableName() }}.filters.{{ $filter->key }}.1"
            wire:key="{{ $tableComponent->getTableName() }}-filter-{{ $filter->key }}1"
            id="{{ $tableComponent->getTableName() }}-filter-{{ $filter->key }}1"
            label="{{__('To')}}" placeholder="{{$filter->getConfig('maxValue')}}"/>
    </div>
</div>
