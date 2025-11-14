<x-layouts.app :title="__('Foods')">

    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">

        <div class="z-0">
            <div class="max-w-[120rem] mx-auto">

                <div class="relative mb-6 w-full">
                    <flux:heading size="xl" level="4">{{ __('Foods') }}</flux:heading>
                    <flux:subheading size="lg" class="mb-6">{{ __('Manage Foods') }}</flux:subheading>
                    <flux:separator variant="subtle" />
                </div>
                    <livewire:table.foods/>
            </div>
        </div>
    </div>
</x-layouts.app>
