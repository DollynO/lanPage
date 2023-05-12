<x-slide-in>
    <x-slot name="title">
        {{$recipe['name']}}
    </x-slot>

    <x-slot name="upperButton">
        <div>
        </div>
    </x-slot>
    <x-input x-bind:disabled="!inEditState" label="{{__('Name')}}" wire:model.defer="recipe.name"/>
    <x-textarea x-bind:disabled="!inEditState" label="{{__('Description')}}" wire:model.defer="recipe.description"/>
</x-slide-in>
