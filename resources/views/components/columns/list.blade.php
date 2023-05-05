@props([
    'value',
    'display_field',
])

<div>
    {{implode(', ', $value->pluck($display_field ?? 'name')->toArray())}}
</div>
