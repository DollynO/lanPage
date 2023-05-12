<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
            </x-table>
        </div>
    </div>
</div>
