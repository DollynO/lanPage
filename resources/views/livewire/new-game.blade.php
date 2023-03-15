<form wire:submit.prevent="submit" method="POST">
    <div class="shadow overflow-hidden sm:rounded-md">
        <div class="px-4 py-5 bg-white sm:p-6">
            <div class="grid grid-cols-6 gap-6">
                <div class="col-span-12 sm:col-span-6">
                    <label for="name" class="block text-sm font-medium text-gray-700">Namen</label>
                    <input type="text" wire:model="name" id="name" autocomplete="given-name" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    @error('name')<span class="text-red-400">{{$message}}</span>@enderror
                </div>
                <div class="col-span-12 sm:col-span-6">
                    <label for="source" class="block text-sm font-medium text-gray-700">Source</label>
                    <input type="text" wire:model="source" id="source" autocomplete="given-name" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    @error('source')<span class="text-red-400">{{$message}}</span>@enderror
                </div>
                <div class="col-span-6 sm:col-span-3">
                    <label for="price" class="block text-sm font-medium text-gray-700">Preis</label>
                    <input type="text" wire:model="price" id="price" autocomplete="given-name" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    @error('price')<span class="text-red-400">{{$message}}</span>@enderror
                </div>
                <div class="col-span-6 sm:col-span-3">
                    <label for="player_count" class="block text-sm font-medium text-gray-700">Max. Player</label>
                    <input type="text" wire:model="player_count" id="player_count" autocomplete="given-name" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    @error('player_count')<span class="text-red-400">{{$message}}</span>@enderror
                </div>
                <div class="col-span-12 sm:col-span-6">
                    <label for="note" class="block text-sm font-medium text-gray-700">Notiz</label>
                    <input type="text" wire:model="note" id="note" autocomplete="given-name" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    @error('note')<span class="text-red-400">{{$message}}</span>@enderror
                </div>
                <div class="col-span-12 sm:col-span-6">
                    <label for="already_played" class="block text-sm font-medium text-gray-700">Schon gespielt</label>
                    <input type="checkbox" wire:model="already_played" id="already_played" autocomplete="given-name" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md">
                    @error('already_played')<span class="text-red-400">{{$message}}</span>@enderror
                </div>
            </div>
        </div>
        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Save</button>
        </div>
    </div>
</form>
