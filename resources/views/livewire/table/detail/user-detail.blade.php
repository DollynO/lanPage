<x-slide-in>
    <x-slot name="title">
        {{$user['name']}}
    </x-slot>

    <x-slot name="upperButton">
        <div>
        </div>
    </x-slot>
    <x-input x-bind:disabled="!inEditState" label="{{__('Name')}}" wire:model="user.name"/>
    <x-button negative label="{{__('Reset password')}}" x-on:confirm="{
    title: 'Sure reset password?',
    icon: 'warning',
    method: 'resetPassword'}"/>
</x-slide-in>
