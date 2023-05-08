@component('components.SlideIn')
    @slot('title')
        {{$user['name']}}
    @endslot

    @slot('upperButton')
        <div>

        </div>
    @endslot
    <x-input x-bind:disabled="!inEditState" label="{{__('Name')}}" wire:model="user.name"/>
    <x-button red label="{{__('Change password')}}"
              x-on:click="changePassword = true"/>
@endcomponent
