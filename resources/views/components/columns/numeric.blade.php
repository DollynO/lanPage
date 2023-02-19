@props([
    'value',
])

<div>
    @if(!empty($value))
        {{$value}}
    @else
        0.00
    @endif
</div>
