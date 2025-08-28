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

    <div class="custom-podium">
        @php
            $podiumClasses = [
                0 => 'gold',
               1 => 'silver',
                2 => 'bronze'
            ];
             $minHeight = 40;   // Mindesthöhe
            $heightFactor = 10; // Höhe pro Punkt
            $maxHeight = 250;   // Maximalhöhe
            $index = 0;
        @endphp

        @if(!empty($totalPointsPerUser))
            @php
                $highestPoints = max(array_column($totalPointsPerUser, 'total_points'));
            @endphp

            @foreach($totalPointsPerUser as $userId => $result)
                @php
                    $class = $podiumClasses[$index] ?? 'other';
                    $totalPoints = $result['total_points'] ?? 0;
                    $userName = $result['user_name'] ?? '---';

                    $initialHeight = $minHeight + ($totalPoints * $heightFactor);

                    $maxPointsReached = max(array_column($totalPointsPerUser, 'total_points'));
                    $scaleFactor = $maxPointsReached * $heightFactor;

                    if($scaleFactor > $maxHeight){
                        $height = ($initialHeight / $scaleFactor) * $maxHeight;
                        if($height < $minHeight) $height = $minHeight;
                    } else {
                        $height = $initialHeight;
                        if($height > $maxHeight) $height = $maxHeight;
                    }
                @endphp

                <div class="custom-podium-bar {{ $class }}" style="height: {{ $height }}px;">
                    <div class="custom-podium-name">{{ $userName }}</div>
                    <span class="custom-podium-result">{{ $totalPoints }}</span>
                </div>

                @php $index++; @endphp
            @endforeach
        @endif
    </div>


    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg custom-background-transparent">
        @if (!empty($tournaments))

            @foreach($tournaments as $tournament)
                <div style="margin-bottom: 20px">
                    <h3>{{$tournament['name']}}</h3>
                    <x-table>
                        <x-thead>
                            <tr class="text-left">
                                <x-th>Rank</x-th>
                                <x-th>Name</x-th>
                                <x-th>Points</x-th>
                                @foreach($tournament['rounds'] as $round)
                                    <x-th>{{ $round->is_decoy ? 'Round ' . ($round->round_number + 1) : $round->game()->first()->name }}</x-th>
                                @endforeach
                            </tr>
                        </x-thead>
                        <tbody class="text-gray-600 text-sm font-normal">
                        @forelse ($tournament['contestants'] as $index => $contestant)
                            @if ($index > 0)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <x-td>{{ $contestant['rank'] }}</x-td>
                                    <x-td>{{ $contestant['user']->name }}</x-td>
                                    <x-td>{{ $contestant['total_points'] }}</x-td>
                                    @foreach($contestant['rounds'] as $round)
                                        @if($round->has_won > 0)
                                            <x-td>
                                                <div
                                                    style="display: flex; justify-content: center; align-items: center; cursor: pointer; border: 3px solid gold; border-radius: 50%; width: 34px; height: 34px; margin-left: -10px;"
                                                    class="flex items-center cursor-pointer">
                                                    <b>{{ $round->points }}</b>
                                                </div>
                                            </x-td>
                                        @else
                                            <x-td>{{ $round->points }}</x-td>
                                        @endif
                                    @endforeach
                                    @if(count($contestant['rounds']) < count($tournament['rounds']))
                                        @for($i = count($contestant['rounds']); $i < count($tournament['rounds']); $i++)
                                            <x-td></x-td>
                                        @endfor
                                    @endif
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <x-td colspan="{{ count($tournament['rounds']) + 3 }}" class="px-3 py-2 text-center">No
                                    data available
                                </x-td>
                            </tr>
                        @endforelse
                        </tbody>
                    </x-table>
                </div>

            @endforeach

        @else
            <h2>No Tournament in Progress.</h2>
        @endif
        </div>
    </div>
