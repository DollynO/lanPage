<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tournament') }}
        </h2>
    </x-slot>

    <div class="container mx-auto">
        <div class="my-8">
            <livewire:game-suggestions-tournament key="{{now()}}" />
        </div>
    </div>

    <div class="flex flex-row">
        <div class="flex-1">
            <div class="container mx-auto">
                <div class="my-8">
                    @livewire('leaderboard-tournament')
                </div>
            </div>
        </div>
        <div class="flex-1 ml-4">

        </div>
    </div>

    <div class="container mx-auto">
        <div class="my-8">
            @livewire('manage-tournaments')
        </div>
    </div>

</x-app-layout>
