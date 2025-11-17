<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    <!-- The selected tournament, including the options to close voting and the tournament itself. -->
    <div class="relative h-full flex-1 overflow-hidden">
        <x-custom-card>
            <x-slot name="title">
                @if ($selectedTournament)
                    {{ $selectedTournament->name }}
                @else
                    ---
                @endif
            </x-slot>
            <div class="px-6">
                <div class="flex mb-4 gap-4">
                    @if ($selectedTournament)

                        <flux:button
                            size="sm"
                            wire:click="toggleSuggestionsClosed"
                        >
                            {{ $selectedTournament->are_suggestions_closed ? 'Open Suggestions' : 'Close Suggestions' }}
                            {{ ' (' . $this->totalSuggestions . ')' }}
                        </flux:button>

                        <flux:button
                            size="sm"
                            wire:click="toggleCompleted"
                        >
                            {{ $selectedTournament->is_completed ? 'Mark as Open' : 'Mark as Complete' }}
                        </flux:button>

                    @endif
                </div>
            </div>


    <!-- List of tournaments, including a delete button and option to create a new tournament. -->

            <x-table class="mr-4">
                <x-slot name="header">

                    <flux:button
                        size="sm"
                        variant="fi-btn"
                        wire:click="createTournament"
                        class=""
                    >
                        Create new Tournament
                    </flux:button>

                </x-slot>

                <x-thead>
                    <tr class="fi-ta-header-cell">
                        <x-th>Name</x-th>
                        <x-th>Date</x-th>
                        <x-th>Voting Closed</x-th>
                        <x-th>Finished</x-th>
                        <x-th></x-th>
                    </tr>
                </x-thead>
                <tbody>
                @foreach ($tournaments as $tournament)
                    <x-tr
                        :selected="$selectedTournament && $tournament && $selectedTournament->id == $tournament->id"
                        wire:key="tournament-{{ $tournament->id }}"
                        wire:click="selectTournament({{ $tournament->id }})"
                    >
                        <x-td>{{ $tournament->name }}</x-td>
                        <x-td>{{ $tournament->created_at->format('d.m.Y') }}</x-td>
                        <x-td-icon>
                            @if ($tournament->are_suggestions_closed)
                                <flux:icon.check-circle class="text-green-600"/>
                            @else
                                <flux:icon.x-circle />
                            @endif

                        </x-td-icon>
                        <x-td-icon>

                            @if ($tournament->is_completed)
                                <flux:icon.check-circle class="text-green-600"/>
                            @else
                                <flux:icon.x-circle />
                            @endif
                        </x-td-icon>
                        <x-td-action>

                            <flux:modal.trigger name="delete-profile-{{ $tournament->id }}">
                                <button
                                    class="fi-color fi-color-danger fi-text-color-600 dark:fi-text-color-300 fi-link fi-size-sm  fi-ac-link-action"
                                    type="button">
                                    <flux:icon.trash variant="micro" class="fi-icon fi-size-sm"/>
                                    Delete
                                </button>
                            </flux:modal.trigger>

                            <flux:modal name="delete-profile-{{ $tournament->id }}" class="min-w-[22rem]">
                                <div class="space-y-6">
                                    <div>
                                        <flux:heading size="lg">Delete Tournament?</flux:heading>

                                        <flux:text class="mt-2">
                                            You're about to delete this tournament.<br>
                                            This action cannot be reversed.
                                        </flux:text>
                                    </div>

                                    <div class="flex gap-2">
                                        <flux:spacer/>

                                        <flux:modal.close>
                                            <flux:button variant="ghost"
                                                         size="sm"
                                            >Cancel
                                            </flux:button>
                                        </flux:modal.close>

                                        <flux:button wire:click="deleteTournament({{ $tournament->id }})"
                                                     size="sm"
                                                     variant="danger"
                                        >Delete Tournament
                                        </flux:button>
                                    </div>
                                </div>
                            </flux:modal>

                        </x-td-action>
                    </x-tr>
                @endforeach
                </tbody>
            </x-table>
        </x-custom-card>
    </div>

    <div class="relative h-full flex-1 overflow-hidden">
        <x-custom-card>
            <x-slot name="title">
                {{__('Rounds')}}
            </x-slot>

            <div class="grid auto-rows-min gap-4 md:grid-cols-2">
                <x-table class="mr-4">


                    <x-thead>
                        <tr class="">
                            <x-th>Round</x-th>
                            <x-th>Game</x-th>
                            <x-th>Actions</x-th>
                        </tr>
                    </x-thead>
                    <tbody>
                    @if(isset($tournamentRounds))
                        @foreach($tournamentRounds as $round)
                            <x-tr
                                :selected="$selectedTournamentRound && $selectedTournamentRound->id === $round->id"
                                wire:click="selectTournamentRound({{ $round->id }})"
                            >


                                <x-td>{{ $round->round_number }}</x-td>
                                <x-td>{{ $round->is_decoy ? '-' : $round->game()->first()->name }}</x-td>
                                <x-td>
                                    <flux:button wire:click="rollGameForRound({{ $round->id }})"
                                                 class=" {{ $round->results->count() > 0 ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' }}"
                                                 size="sm">
                                        {{__('Roll Game')}}
                                    </flux:button>

                                </x-td>
                            </x-tr>
                        @endforeach
                    @endif
                    </tbody>
                </x-table>
                <!-- A detailed view of the selected result, to edit them. -->
                @if(isset($selectedTournamentRound))
                    <x-table class="mr-4">

                        <x-slot name="header">
                            <flux:button
                                size="sm"
                                wire:click="createUserResults"
                                variant="fi-btn"
                                class="{{ count($tournamentRoundUsers) > 0 ?  'opacity-50 cursor-not-allowed pointer-events-none' : '' }}">
                                {{__('Create Results')}}
                            </flux:button>

                            <flux:button
                                size="sm"
                                variant="fi-btn"
                                wire:click="saveUserResults">

                                {{__('Save Results')}}
                            </flux:button>


                        </x-slot>

                        <x-thead>
                            <tr>
                                <x-th>Player</x-th>
                                <x-th>Points</x-th>
                                <x-th>Winner?</x-th>
                            </tr>
                        </x-thead>
                        <tbody>
                        @if(isset($tournamentRoundUsers))
                            @foreach ($tournamentRoundUsers as $index => $userResult)
                                <x-tr
                                >
                                    <x-td class="px-4 py-3">{{ $userResult['user']['name'] }}</x-td>
                                    <x-td class="px-4 py-3">
                                        <input type="text"
                                               class="border border-gray-300 dark:border-gray-600 dark:bg-gray-900 rounded focus:ring focus:ring-indigo-500"
                                               wire:model="tournamentRoundUsers.{{ $index }}.points"
                                               wire:key="user-{{ $userResult['id'] }}"/>
                                    </x-td>
                                    <x-td class="px-4 py-3">{{ $userResult['has_won'] }}</x-td>
                                </x-tr>
                            @endforeach
                        @endif
                        </tbody>
                    </x-table>
                @endif
            </div>
        </x-custom-card>
    </div>
    <div class="grid auto-rows-min gap-4 md:grid-cols-2">
        <x-custom-card>
            <x-slot name="title">
                {{__('User Game Suggestions')}}
            </x-slot>
            <div class="flex gap-4 p-4 dark:text-white">

                <flux:button
                    size="sm"
                    wire:click="refreshList"
                    variant="fi-btn"
                >
                    Refresh List
                </flux:button>

                <ul class="w-full">
                    @foreach($userSuggestions as $user)
                        <li
                            class="px-2 py-1 rounded mb-1 {{ $user['games_count'] == 3 ? 'bg-green-500 ' : 'bg-red-500 ' }}">
                            {{ $user['name'] }} {{ $user['games_count'] }}/3
                        </li>
                    @endforeach
                </ul>
            </div>

        </x-custom-card>
        <x-custom-card>
            <x-slot name="title">
                {{__('User Roller')}}
            </x-slot>

            <div class="p-4">

                <div class="grid grid-cols-2 gap-6">

                    <div>
                        <div class="flex gap-2 mb-4">

                            <flux:button
                                size="sm"
                                wire:click="roll"
                                variant="fi-btn"
                            >
                                Roll User
                            </flux:button>

                            <flux:button
                                size="sm"
                                wire:click="resetRolls"
                            >
                                Reset
                            </flux:button>


                        </div>

                        @if($lastRolled)
                            <p class="rolled-user-dark inline-flex items-center gap-2 mb-4">
                                <span class="icon">🎲</span>
                                Gewählt: <strong>{{ $lastRolled->name }}</strong>
                            </p>
                        @endif

                        <ul class="space-y-1 w-full">
                            @foreach($rollUsers as $rollUser)
                                <li class="flex items-center gap-2">
                                    @if(in_array($rollUser->id, $selected))
                                    <flux:icon.check-circle class="text-green-600"/>
                                    @else
                                        <flux:icon.x-circle class="text-red-600"/>
                                    @endif
                                    {{ $rollUser->name }}
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div>
                        <h3 class="font-semibold mb-2">Reihenfolge der gewählten User:</h3>
                        <ol class="list-decimal list-inside s
                        pace-y-1">
                            @foreach($selected as $userId)
                                @php
                                    $rollUser = $rollUsers->firstWhere('id', $userId);
                                @endphp
                                <li>{{ $rollUser->name }}</li>
                            @endforeach
                        </ol>
                    </div>

                </div>
            </div>


        </x-custom-card>
    </div>
</div>

