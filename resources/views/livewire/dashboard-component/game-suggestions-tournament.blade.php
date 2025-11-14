<div>
    @if(isset($tournament))
        <!-- A searchbar that lets the user select games to add to suggestions in a dropdown. -->
        <div class="px-2 my-2">
            <div class="flex items-center justify-between space-x-8 mb-4">
                <span class="whitespace-nowrap">Vote for a game ({{$suggestionsLeft}}/3 left):</span>
                <div class="relative flex-1">
                    <x-input type="text"
                             placeholder="Search Games..."
                             wire:model.live="query"
                             wire:keydown.escape="resetSearchbar"
                             wire:keydown.tab="resetSearchbar"
                             wire:keydown.arrow-up="decrementHighlight"
                             wire:keydown.arrow-down="incrementHighlight"
                             wire:keydown.enter="selectHighlightedGame"
                             x-bind:disabled="$wire.suggestionsLeft == 0"
                    />
                    @if(!empty($query))
                        <div class="absolute z-10 my-2 left-0 right-0">
                            <div
                                class="bg-white border max-h-64 sm:max-h-60 overflow-y-auto overscroll-contain soft-scrollbar select-none dark:bg-gray-600 dark:text-white">
                                <ul>
                                    @if(!empty($games))
                                        @foreach($games as $i => $game)
                                            <li>
                                                <div wire:click="selectGame({{$game['id']}})"
                                                     class="py-2 px-3 focus:outline-none all-colors ease-in-out duration-150 relative group text-secondary-600 dark:text-secondary-400 flex items-center justify-between cursor-pointer focus:bg-primary-100 focus:text-primary-800 hover:text-white dark:focus:bg-secondary-700 hover:bg-primary-500 dark:hover:bg-secondary-700">
                                                    <div class="dark:text-white">
                                                        {{ $game['name'] }}
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    @else
                                        <div class="list-item block py-2 px-4 text-red-500 hover:bg-gray-100">Game not
                                            found
                                        </div>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    @endif
                </div>
                <flux:button  wire:click="signalLeaveViewToParent"
                              variant="fi-btn"
                              size="sm"
                >
                    {{__(' View Leaderboard')}}
                </flux:button>

            </div>
        </div>

        <!-- The table with all suggestions for the current tournament. -->
        <div class="overflow-hidden sm:rounded-lg">
            <div class="relative overflow-x-auto rounded-lg">
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
                    <tbody>
                    @forelse ($suggestions as $index => $suggestion)
                        <tr class="bg-white border-b hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:hover:bg-[#374151]  fi-ta-row">
                            <x-td>{{ $suggestion['game']->name }}</x-td>
                            <x-td>
                                @foreach ($suggestion->votes() as $vote)
                                    <div style="position: relative; display: inline-block; cursor: pointer;"
                                         onmouseenter="showUserInfo(this)" onmouseleave="hideUserInfo(this)">
                                        <div class="data-initials user-icon" data-user-id="{{ $vote->user_id }}">
                                            {{ strtoupper(substr($vote->user->name, 0, 1)) }}{{ substr($vote->user->name, strpos($vote->user->name, " ") + 1, 1) }}
                                        </div>
                                        <div class="user-info hidden absolute z-10 bottom-full left-1/2 -translate-x-1/2 w-52 bg-gray-50 dark:bg-gray-800 p-3 rounded mb-2">

                                        <!-- Pop-up content, hidden by default -->
                                            {{ $vote->user->name }}
                                        </div>
                                    </div>
                                @endforeach

                            </x-td>
                            <x-td>
                                <div class="button-container" style="margin: -8px;">
                                    @if ($suggestion->userVote(Auth::id()) || $suggestionsLeft == 0)
                                    @else
                                        <flux:button  wire:click="increaseVotes({{ $suggestion->id }})"
                                                      variant="primary"
                                                      size="sm"
                                                      square="true"
                                        >
                                            +1
                                        </flux:button>
                                    @endif

                                    @if (!$suggestion->userVote(Auth::id()) || $suggestion->votes()->count() === 1)
                                    @else

                                            <flux:button  wire:click="decreaseVotes({{ $suggestion }})"
                                                          variant="danger"
                                                          size="sm"
                                                          square="true"
                                            >
                                                -1
                                            </flux:button>
                                    @endif

                                    @if (!$suggestion->userVote(Auth::id()) || $suggestion->votes()->count() > 1)
                                    @else
                                            <flux:button         wire:click="removeSuggestion({{ $suggestion->id }})"
                                                          variant="danger"
                                                          size="sm"
                                                          square="true"
                                                          icon="trash"
                                            >

                                            </flux:button>

                                    @endif

                                </div>
                            </x-td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-3 py-2 text-center">No game votes available</td>
                        </tr>
                    @endforelse
                    </tbody>
                    <script>
                        function showUserInfo(element) {
                            element.querySelector('.user-info').style.display = 'block';
                        }

                        function hideUserInfo(element) {
                            element.querySelector('.user-info').style.display = 'none';
                        }

                        const colors = ['#D28100', '#D1423F', '#DC1677', '#C233A0', '#6163E1',
                            '#246DB6', '#008290', '#7BA100', '#9355D2', '#627A89'];
                        document.querySelectorAll('.user-icon').forEach(function (icon) {
                            const userId = parseInt(icon.getAttribute('data-user-id'));
                            const colorIndex = userId % colors.length; // modulo by the number of colors
                            icon.style.backgroundColor = colors[colorIndex];
                        });
                    </script>
                </x-table>
            </div>
        </div>
    @else
        <h2>No Tournament in Progress.</h2>
    @endif
</div>
