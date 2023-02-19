@props([
    'value',
])

<div>
    <input type="checkbox" disabled="disabled"
           @if($value) checked @endif>
</div>
