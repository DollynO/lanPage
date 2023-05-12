<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <table class="w-full table-auto border-collapse">
                <thead>
                <tr class="text-left">
                    @if(count($contestants) > 0)
                        <th class="px-3 py-2 border-b-2 border-gray-300">Rank</th>
                        <th class="px-3 py-2 border-b-2 border-gray-300">Name</th>
                        <th class="px-3 py-2 border-b-2 border-gray-300">Points</th>
                        @foreach($games as $game)
                            <th class="px-3 py-2 border-b-2 border-gray-300">{{ $game->name }}</th>
                        @endforeach
                    @endif
                </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-normal">
                @forelse ($contestants as $index => $contestant)
                    @if ($index > 0)
                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                            <td class="px-3 py-2">{{ $contestant['rank'] }}</td>
                            <td class="px-3 py-2">{{ $contestant['user']->name }}</td>
                            <td class="px-3 py-2">{{ $contestant['total_points'] }}</td>
                            @foreach($contestant['rounds'] as $round)
                                <td class="px-3 py-2">{{ $round->points }}</td>
                            @endforeach
                            @if(count($contestant['rounds']) < count($games))
                                @for($i = count($contestant['rounds']); $i < count($games); $i++)
                                    <td class="px-3 py-2"></td>
                                @endfor
                            @endif
                        </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="{{ count($games) + 3 }}" class="px-3 py-2 text-center">No data available</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
