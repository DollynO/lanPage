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
    <div class="flex items-center gap-2">
        <input id="can_upload" type="checkbox"
               wire:model.defer="user.can_upload"
               class="h-4 w-4">
        <label for="can_upload" class="text-sm">Upload photo permissions</label>
    </div>
</x-slide-in>
