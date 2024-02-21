<div>
    @if(isset($tournament))
    <!-- A searchbar that lets the user select games to add to suggestions in a dropdown. -->
        <div class="px-2 my-2">
            <div class="flex items-center justify-between space-x-8 mb-4">
                <span class="whitespace-nowrap">Vote for a game ({{$suggestionsLeft}}/3 left):</span>
                <div class="relative flex-1">
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
                        <div class="absolute z-10 my-2 left-0 right-0">
                            <div class="bg-white border max-h-64 sm:max-h-60 overflow-y-auto overscroll-contain soft-scrollbar select-none">
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
                                        <div class="list-item block py-2 px-4 text-red-500 hover:bg-gray-100">Game not found</div>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    @endif
                </div>
                <x-button dark
                          wire:click="signalLeaveViewToParent"
                          class="whitespace-nowrap">
                    View Leaderboard
                </x-button>
            </div>
        </div>

        <!-- The table with all suggestions for the current tournament. -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="relative overflow-x-auto shadow-md rounded-lg">
                <x-table>
                    <x-thead>
                        <tr>
                            <x-th>{{__('Game')}}</x-th>
                            <x-th>{{__('Votes')}}</x-th>
                            <x-th>{{__('Vote')}}</x-th>
                        </tr>
                    </x-thead>

                    <style>
                        .data-initials {
                            background: #099bdd;
                            color: white;
                            opacity: 1;
                            content: attr(data-initials);
                            display: inline-block;
                            font-weight: bold;
                            border-radius: 50%;
                            vertical-align: middle;
                            margin-left: 0.5em;
                            width: 35px;
                            height: 35px;
                            line-height: 35px;
                            text-align: center;
                        }
                    </style>
                    <script>
                        function showUserInfo(element) {
                            element.querySelector('.user-info').style.display = 'block';
                        }

                        function hideUserInfo(element) {
                            element.querySelector('.user-info').style.display = 'none';
                        }
                    </script>
                    <tbody>
                        @forelse ($suggestions as $index => $suggestion)
                            <tr class="bg-white border-b hover:bg-gray-50">
                                <x-td>{{ $suggestion['game']->name }}</x-td>
                                <x-td>{{ $suggestion->votes()->count() }}
                                    @foreach ($suggestion->votes() as $vote)
                                        <div style="position: relative; display: inline-block; cursor: pointer;" onmouseenter="showUserInfo(this)" onmouseleave="hideUserInfo(this)">
                                            <div class="data-initials" data-user-id="{{ $vote->user_id }}">
                                                {{ strtoupper(substr($vote->user->name, 0, 1)) }}{{ substr($vote->user->name, strpos($vote->user->name, " ") + 1, 1) }}
                                            </div>
                                            <div class="user-info" data-user-id="{{ $vote->user_id }}" style="position: absolute; z-index: 1; bottom: 100%; left: 50%; transform: translateX(-50%); width: 200px; background-color: #f9f9f9; box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2); padding: 12px; border-radius: 4px; display: none; margin-bottom: 10px;">
                                                <!-- Pop-up content, hidden by default -->
                                                {{ $vote->user->name }}
                                            </div>
                                        </div>
                                    @endforeach

                                </x-td>
                                <x-td>
                                    <div class="button-container" style="margin: -8px;">
                                    <button wire:click="increaseVotes({{ $suggestion->id }})"
                                            class="outline-none inline-flex justify-center items-center group transition-all ease-in duration-150 focus:ring-2 focus:ring-offset-2 hover:shadow-sm disabled:opacity-80 disabled:cursor-not-allowed rounded gap-x-2 text-sm w-10 py-2 ring-blue-500 text-white bg-blue-500 hover:bg-blue-600 hover:ring-blue-600"
                                            @if ($suggestion->userVote(Auth::id()) || $suggestionsLeft == 0) style="display: none" @endif>
                                        +1
                                    </button>
                                    <button wire:click="decreaseVotes({{ $suggestion }})"
                                            class="outline-none inline-flex justify-center items-center group transition-all ease-in duration-150 focus:ring-2 focus:ring-offset-2 hover:shadow-sm disabled:opacity-80 disabled:cursor-not-allowed rounded gap-x-2 text-sm w-10 py-2 ring-red-500 text-white bg-red-500 hover:bg-red-600 hover:ring-red-600"
                                            @if (!$suggestion->userVote(Auth::id()) || $suggestion->votes()->count() === 1) style="display: none" @endif>
                                        -1
                                    </button>
                                    <button wire:click="removeSuggestion({{ $suggestion->id }})"
                                            class="outline-none inline-flex justify-center items-center group transition-all ease-in duration-150 focus:ring-2 focus:ring-offset-2 hover:shadow-sm disabled:opacity-80 disabled:cursor-not-allowed rounded gap-x-2 text-sm w-10 py-2 ring-red-500 text-white bg-red-500 hover:bg-red-600 hover:ring-red-600"
                                            @if (!$suggestion->userVote(Auth::id()) || $suggestion->votes()->count() > 1) style="display: none" @endif>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16"> <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/> <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/> </svg>
                                        </button>
                                    </div>
                                </x-td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-3 py-2 text-center">No game votes available</td>
                            </tr>
                        @endforelse
                    </tbody>
                </x-table>
            </div>
        </div>
    @else
        <h2>No Tournament in Progress.</h2>
    @endif
</div>
