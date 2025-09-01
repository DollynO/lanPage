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
    <div class="flex flex-col space-y-4">
        @if (isset($tournament))
        <div class="leaderboard-wrapper">
            <h2>Leaderboard</h2>
            <div class="leaderboard" id="leaderboard"></div>
        </div>
        @endif
    </div>

    <div class="flex flex-wrap -mx-2">
        @if (isset($tournament))
            @foreach($roundResults as $result)
                <div class="w-1/2 px-2 mb-4">
                    <div class="overflow-hidden shadow-xl sm:rounded-lg cyber-background-transparent h-full">
                        <h3 class="px-4 py-2 dark:text-white" >{{ $result['gameName'] }}</h3>
                        <x-table class="w-full">
                            <x-thead>
                                <tr class="text-left">
                                    <x-th>Rank</x-th>
                                    <x-th>Name</x-th>
                                    <x-th>Points</x-th>
                                </tr>
                            </x-thead>
                            <tbody class="text-gray-600 text-sm font-normal">
                            @foreach($result['result']??[] as $game)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <x-td>{{ $game['rank'] }}</x-td>
                                    <x-td>{{ $game['name'] }}</x-td>
                                    <x-td>{{ $game['points'] }}</x-td>
                                </tr>
                            @endforeach
                            </tbody>
                        </x-table>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <div class="flex flex-col space-y-4">

                <div class="w-full  overflow-hidden shadow-xl sm:rounded-lg cyber-background-transparent">
                    @if (isset($tournament))
                        <h2 class="px-4 py-2 dark:text-white" >Summary</h2>
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

    <script>
        const players = @json($playerResults ?? '');
        const colorsBar = ['#4CAF50', '#2196F3', '#FFC107', '#F44336'];

        const leaderboard = document.getElementById('leaderboard');

        const totals = players.map(p => p.scores.reduce((a, b) => a + b, 0));
        const maxTotal = Math.max(...totals);

        players.forEach((player) => {
            const entry = document.createElement('div');
            entry.classList.add('entry');

            // Rank
            const rankCell = document.createElement('div');
            rankCell.classList.add('rank-cell');
            rankCell.innerText = player.rank;
            entry.appendChild(rankCell);

            const nameCell = document.createElement('div');
            nameCell.classList.add('name-cell');
            // nameCell.innerText = player.name;



                const badge = document.createElement('div');
                badge.classList.add('badge');

                const crown = document.createElement('i');
                crown.classList.add('fa-solid', 'fa-crown');
                badge.appendChild(crown);

                const badgeText = document.createElement('div');
                badgeText.classList.add('badge-text');


                badgeText.innerText = player.name;

            if (player.rank === 1) {
                badgeText.classList.add('name-gold');
            } else if (player.rank === 2) {
                badgeText.classList.add('name-silver');
            } else if (player.rank === 3) {
                badgeText.classList.add('name-bronze');
            }

                badge.appendChild(badgeText);
                nameCell.appendChild(badge);


            entry.appendChild(nameCell);

            // Punkte
            const total = player.scores.reduce((a, b) => a + b, 0);
            const totalCell = document.createElement('div');
            totalCell.classList.add('total-cell');
            totalCell.innerText = total;
            entry.appendChild(totalCell);

            // Balken
            const barContainer = document.createElement('div');
            barContainer.classList.add('bar-container');

            player.scores.forEach((score, i) => {
                if (score > 0) {
                    const segment = document.createElement('div');
                    segment.classList.add('segment');
                    segment.style.width = ((score / maxTotal * 450) - 2) + 'px';
                    segment.style.backgroundColor = colorsBar[i % colorsBar.length];

                    const scoreText = document.createElement('span');
                    scoreText.classList.add('score-text');
                    scoreText.innerText = score;

                    segment.appendChild(scoreText);
                    barContainer.appendChild(segment);
                }
            });

            entry.appendChild(barContainer);
            leaderboard.appendChild(entry);
        });
    </script>





</div>
