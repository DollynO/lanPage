<div>
    <!-- A leaderboard that shows the current progression of a tournament. -->
    <div class="px-2 my-2">
        <div class="flex justify-end space-x-8 mb-4">
            <x-button dark
                      wire:click="signalLeaveViewToParent"
                      class="whitespace-nowrap">
                View Game-Votes
            </x-button>
        </div>
    </div>
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            @if (isset($tournament))
            <x-table>
                <x-thead>
                    <tr class="text-left">
                        <x-th>Rank</x-th>
                        <x-th>Name</x-th>
                        <x-th>Points</x-th>
                        @foreach($rounds as $round)
                            <x-th>{{ $round->is_decoy ? 'Round ' . ($round->round_number + 1) : $round->game()->first()->name }}</x-th>
                        @endforeach
                    </tr>
                </x-thead>
                <tbody class="text-gray-600 text-sm font-normal">
                @forelse ($contestants as $index => $contestant)
                    @if ($index > 0)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <x-td>{{ $contestant['rank'] }}</x-td>
                            <x-td>{{ $contestant['user']->name }}</x-td>
                            <x-td>{{ $contestant['total_points'] }}</x-td>
                            @foreach($contestant['rounds'] as $round)
                                @if($round->has_won > 0)
                                    <x-td>
                                        <div style="display: flex; justify-content: center; align-items: center; cursor: pointer; border: 3px solid gold; border-radius: 50%; width: 34px; height: 34px; margin-left: -10px;"
                                             class="flex items-center cursor-pointer">
                                            <b>{{ $round->points }}</b>
                                        </div>
                                    </x-td>
                                @else
                                    <x-td>{{ $round->points }}</x-td>
                                @endif
                            @endforeach
                            @if(count($contestant['rounds']) < count($rounds))
                                @for($i = count($contestant['rounds']); $i < count($rounds); $i++)
                                    <x-td></x-td>
                                @endfor
                            @endif
                        </tr>
                    @endif
                @empty
                    <tr>
                        <x-td colspan="{{ count($rounds) + 3 }}" class="px-3 py-2 text-center">No data available</x-td>
                    </tr>
                @endforelse
                </tbody>
            </x-table>
            @else
                <h2>No Tournament in Progress.</h2>
            @endif
        </div>
    </div>
