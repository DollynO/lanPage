<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $id == null ? __('New Game') : __('Edit Game') }}
        </h2>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mt-10 sm:mt-0">
                <div class="md:gap-6">
                    <div class="mt-5 md:mt-0">
                        @livewire('edit-game',['id'=> $id])
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
