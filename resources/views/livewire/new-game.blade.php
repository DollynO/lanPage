<x-custom-card>
    <div class="flex flex-column gap-4">
        <x-input label="{{__('Name')}}" wire:model.defer="name"/>
        <x-input label="{{__('Genre')}}" wire:model.defer="genre"/>
        <x-input label="{{__('Source')}}" wire:model.defer="source"/>
        <div class="grid grid-cols-2 gap-4">
            <x-input label="{{__('Price')}}" wire:model.defer="price"/>
            <x-input label="{{__('Player count')}}" wire:model.defer="player_count"/>
        </div>
        <x-textarea label="{{__('Note')}}" wire:model.defer="note"/>
        <x-checkbox label="{{__('Already played')}}" wire:model.defer="already_played"/>
    </div>
    <x-slot name="buttons">
        <x-button dark label="{{__('Save')}}" wire:click="save"/>
    </x-slot>
</x-custom-card>
