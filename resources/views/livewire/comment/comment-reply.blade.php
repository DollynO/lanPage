<div x-data="{ openReply: @entangle('openReply') }" xmlns:x-filament="http://www.w3.org/1999/html" class="pb-5">
    <div class="flex items-center mt-4 space-x-4">
        <x-filament::button
            color="gray"
            size="sm"
            icon="heroicon-o-chat-bubble-left-right"
            x-on:click="openReply = true"
        >
            Reply
        </x-filament::button>
    </div>

    <div x-show="openReply" x-cloak class="mt-4 space-y-3">
        <x-filament::input
            wire:model.defer="reply"
            placeholder="Write your reply..."
            class="w-full border border-gray-200 rounded-lg shadow-sm dark:border-gray-200 dark:bg-gray-800"
            rows="4"
        />

        <div class="flex justify-end gap-2">
            <x-filament::button
                color="gray"
                size="sm"
                wire:click="$set('openReply', false)"
            >
                {{ __('Cancel') }}
            </x-filament::button>

            <x-filament::button
                color="primary"
                size="sm"
                wire:click="addReply"
            >
                {{ __('Save') }}
            </x-filament::button>
        </div>
    </div>
</div>
