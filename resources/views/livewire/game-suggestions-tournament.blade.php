<div>
    <!-- A searchbar that lets the user select games to add to suggestions in a dropdown. -->
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <div class="relative rounded-md">
            <div class="px-2 my-2">
            <input type="text"
                   class="placeholder-secondary-400 border border-secondary-300 focus:ring-primary-500 focus:border-primary-500 form-input block w-full sm:text-sm rounded-md transition ease-in-out duration-100 pr-8 bg-slate-100"
                   placeholder="Search Games..."
                   wire:model="query"
                   wire:keydown.escape="resetSearchbar"
                   wire:keydown.tab="resetSearchbar"
                   wire:keydown.arrow-up="decrementHighlight"
                   wire:keydown.arrow-down="incrementHighlight"
                   wire:keydown.enter="selectHighlightedGame"
                   x-bind:disable="$wire.suggestionsLeft == 0"
            />
            @if(!empty($query))
                <div class="absolute z-10 my-2"
                    style="width: calc(100% - 16px);">
                    <div class=" bg-white border  max-h-64 sm:max-h-60 overflow-y-auto overscroll-contain soft-scrollbar select-none" >
                        <ul>
                            @if(!empty($games))
                                @foreach($games as $i => $game)
                                    <li>
                                        <div wire:click="selectGame({{$game['id']}})"
                                            class="py-2 px-3 focus:outline-none all-colors ease-in-out duration-150 relative group text-secondary-600 dark:text-secondary-400 flex items-center justify-between cursor-pointer focus:bg-primary-100 focus:text-primary-800 hover:text-white dark:focus:bg-secondary-700 hover:bg-primary-500 dark:hover:bg-secondary-700">
                                            <div>
                                                {{ $game['name'] }}
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            @else
                                <div class="list-item block py-2 px-4 hover:bg-gray-100">Game not found!</div>
                            @endif
                        </ul>
                    </div>
                </div>
            @endif
        </div>
        </div>


        <span class="text-gray-500">Suggestions left: {{ $suggestionsLeft }}</span>

        <!-- The table with all suggestions for the current tournament. -->
        <div class="overflow-y-auto" style="min-height: 500px; max-height: 500px;">
            <x-table>
                <x-thead>
                    <tr>
                        <x-th>Game</x-th>
                        <x-th>Votes</x-th>
                        <x-th>Actions</x-th>
                    </tr>
                </x-thead>
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
            </x-table>
        </div>
    </div>
</div>
