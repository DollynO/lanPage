<x-custom-card>
    <div class="flex flex-column gap-4">
        <div class="flex flex-row gap-4">
            <div class="w-1/2">
                <x-datetime-picker wire:model.defer="start_date" :without-time="true" placeholer="{{__('Start date')}}"
                                   label="{{__('Start date')}}" parse-format="YYYY-MM-DD"/>
            </div>
            <div class="w-1/2">
                <x-datetime-picker wire:model.defer="end_date" :without-time="true" placeholer="{{__('End date')}}"
                                   label="{{__('End date')}}" parse-format="YYYY-MM-DD"/>
            </div>
        </div>
        <x-input wire:model="location" placeholder="{{__('Location')}}" label="{{__('Location')}}"/>
    </div>
    <x-slot name="buttons">
        <x-button dark wire:click="save" label="{{__('Save')}}"/>
    </x-slot>
</x-custom-card>
