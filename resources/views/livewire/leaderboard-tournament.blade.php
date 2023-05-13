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
            <x-table>
                <x-thead>
                    <tr class="text-left">
                        @if(count($contestants) > 0)
                            <x-th>Rank</x-th>
                            <x-th>Name</x-th>
                            <x-th>Points</x-th>
                            @foreach($games as $game)
                                <x-th>{{ $game->name }}</x-th>
                            @endforeach
                        @endif
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
                                <x-td>{{ $round->points }}</x-td>
                            @endforeach
                            @if(count($contestant['rounds']) < count($games))
                                @for($i = count($contestant['rounds']); $i < count($games); $i++)
                                    <x-td></x-td>
                                @endfor
                            @endif
                        </tr>
                    @endif
                @empty
                    <tr>
                        <x-td colspan="{{ count($games) + 3 }}" class="px-3 py-2 text-center">No data available</x-td>
                    </tr>
                @endforelse
                </tbody>
            </x-table>
        </div>
    </div>
