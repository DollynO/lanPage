<div x-data="{openReply : @entangle('openReply')}">
    <div class="flex items-center mt-4 space-x-4">
        <button type="button"
                class="flex items-center text-sm text-gray-500 hover:underline dark:text-gray-400 font-medium"
                x-on:click="openReply = true">
            <svg class="mr-1.5 w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 18">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5h5M5 8h2m6-3h2m-5 3h6m2-7H2a1 1 0 0 0-1 1v9a1 1 0 0 0 1 1h3v5l5-5h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1Z"/>
            </svg>
            <div class="ml-1">
                Reply
            </div>
        </button>
    </div>
    <div x-show="openReply">
        <div class="flex flex-col">
            <x-input class="mb-2" wire:model="reply"/>
            <div class="flex flex-row justify-end gap-2">
                <x-button
                    red
                    label="{{__('Cancel')}}"
                    x-on:click="openReply = false"/>
                <x-button
                    dark
                    label="{{__('Save')}}"
                    x-on:click="$wire.addReply()"/>
            </div>
        </div>
    </div>
</div>
