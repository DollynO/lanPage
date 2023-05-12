<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <h2 class="text-xl font-bold mb-4">Game Suggestions for Active Tournament</h2>

            <div class="flex items-center">
                <input type="text" placeholder="Enter a game name" wire:model="searchTerm" x-bind:disable="$wire.suggestionsLeft == 0"
                       class="border border-gray-300 rounded px-4 py-2 mr-2" {{ $suggestionsLeft === 0 ? 'disabled' : '' }}>
                <span class="text-gray-500">Suggestions left: {{ $suggestionsLeft }}</span>
            </div>
                @if (!empty($searchTerm) && count($games) > 0)
                    <div class="absolute z-10 bg-white border border-gray-300 rounded max-w-full max-h-40 overflow-y-auto shadow">
                        <ul class="py-2">
                            @foreach ($games as $game)
                                <li wire:click="selectGame('{{ $game->id }}')"
                                    class="cursor-pointer hover:bg-gray-100 px-4 py-2 w-full inline-block">
                                    {{ $game->name }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @elseif (!empty($searchTerm))
                    <div class="absolute bg-white border border-gray-300 rounded min-w-full max-w-full max-h-40 overflow-y-auto shadow">
                        <ul class="py-2">
                            <li class="cursor-pointer hover:bg-gray-100 px-4 py-2">
                                Create new game
                            </li>
                        </ul>
                    </div>
                @endif
            </div>

            <div class="overflow-y-auto" style="min-height: 500px; max-height: 500px;">
                <table class="w-full table-auto border-collapse">
                    <thead>
                        <tr class="text-left">
                            <th class="px-3 py-2 border-b-2 border-gray-300">Game</th>
                            <th class="px-3 py-2 border-b-2 border-gray-300">Votes</th>
                            <th class="px-3 py-2 border-b-2 border-gray-300">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm font-normal">
                        @forelse ($suggestions as $index => $suggestion)
                            <tr class="border-b border-gray-200 hover:bg-gray-100">
                                <td class="px-3 py-2">{{ $suggestion['game']->name }}</td>
                                <td class="px-3 py-2">{{ $suggestion->votes() }}</td>
                                <td class="px-3 py-2">
                                    <button wire:click="increaseVotes( {{ $suggestion->id }} )"
                                            class="bg-blue-500 text-white font-bold py-1 px-3 rounded"
                                            @if ($suggestion->userVote(Auth::id()) || $suggestionsLeft == 0) style="display: none" @endif>
                                        +1
                                    </button>
                                    <button wire:click="decreaseVotes({{ $suggestion }})"
                                            class="bg-red-500 text-white font-bold py-1 px-3 rounded"
                                            @if (!$suggestion->userVote(Auth::id()) || $suggestion->votes() === 1) style="display: none" @endif>
                                        -1
                                    </button>
                                    <button wire:click="removeSuggestion({{ $suggestion->id }})"
                                            class="bg-red-500 text-white font-bold py-1 px-3 rounded"
                                            @if (!$suggestion->userVote(Auth::id()) || $suggestion->votes() > 1) style="display: none" @endif>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16"> <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/> <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/> </svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-3 py-2 text-center">No game suggestions available</td>
                            </tr>
                        @endforelse
                    </tbody>
            </table>
        </div>
    </div>
</div>
