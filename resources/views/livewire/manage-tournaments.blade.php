<div class="flex flex-row justify-between">
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <div class="relative overflow-x-auto shadow-md rounded-lg">
            <x-table class="mr-4">
                <x-thead>
                    <tr>
                        <x-th>Name</x-th>
                        <x-th>Date</x-th>
                        <x-th>Voting Closed</x-th>
                        <x-th>Finished</x-th>
                        <x-th>Actions</x-th>
                    </tr>
                </x-thead>
                <tbody>
                    @foreach ($tournaments as $tournament)
                        <tr
                            wire:click="selectTournament({{ $tournament->id }})"
                            class="bg-white border-b hover:bg-gray-50"
                            @class(['bg-gray-200' => $selectedTournament === $tournament->id])
                        >
                            <x-td>{{ $tournament->name }}</x-td>
                            <x-td>{{ $tournament->created_at->format('d.m.Y') }}</x-td>
                            <x-td>
                                <div class="relative inline-block w-6 h-6">
                                    <div class="absolute w-6 h-6 bg-white border border-gray-300 rounded pointer-events-none"></div>
                                    @if ($tournament->are_suggestions_closed)
                                        <svg class="absolute w-6 h-6 text-indigo-600 pointer-events-none" viewBox="0 0 24 24">
                                            <path fill="currentColor" d="M9 16.17l-4.59-4.59L3 13l6 6L21 7l-1.41-1.41L9 16.17z" />
                                        </svg>
                                    @endif
                                </div>
                            </x-td>
                            <x-td>
                                <div class="absolute w-6 h-6 bg-white border border-gray-300 rounded pointer-events-none"></div>
                                @if ($tournament->is_completed)
                                    <svg class="absolute w-6 h-6 text-indigo-600 pointer-events-none" viewBox="0 0 24 24">
                                        <path fill="currentColor" d="M9 16.17l-4.59-4.59L3 13l6 6L21 7l-1.41-1.41L9 16.17z" />
                                    </svg>
                                @endif
                            </x-td>
                            <x-td>
                                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                                        wire:click="deleteTournament({{ $tournament->id }})">Delete</button>
                            </x-td>
                        </tr>
                    @endforeach
                </tbody>
            </x-table>
            <form class="ml-4 my-4 w-2/3" wire:submit.prevent="createTournament">
                <div class="flex items-center mb-4">
                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit">Create new Tournament</button>
                </div>
            </form>
        </div>
    </div>
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <div class="relative overflow-x-auto shadow-md rounded-lg">
            @if ($selectedTournament)
                <div class="w-full justify-center py-3 px-6 flex items-center bg-gray-700 text-gray-50 uppercase mb-3">
                    <h2 class="text-sx font-bold">{{ $selectedTournament->name }}</h2>
                </div>
                <div class="px-4">
                    <div class="flex mb-4">
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2"
                                wire:click="toggleSuggestionsClosed">
                            {{ $selectedTournament->are_suggestions_closed ? 'Open Suggestions' : 'Close Suggestions' }}
                        </button>
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-2"
                                wire:click="toggleCompleted">
                            {{ $selectedTournament->is_completed ? 'Mark as Open' : 'Mark as Complete' }}
                        </button>
                    </div>
                    <form wire:submit.prevent="updateTournament">
                        <div class="mb-4">
                            <label class="mr-4" for="name">Name:</label>
                            <input class="border rounded w-full py-2 px-3" type="text" wire:model="selectedTournament.name">
                        </div>
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit">Save</button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
