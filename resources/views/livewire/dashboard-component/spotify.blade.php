<div>
    <div class="flex flex-col gap-4 md:flex-row md:gap-2 md:justify-between">
        <div class="flex-1"  style="width: 100%; max-width: 100%; margin: 0 auto;">
            @if (isset($tournament))
                <div class="leaderboard-wrapper">
                    <h2>Leaderboard</h2>
                    <div class="leaderboard" id="leaderboard"></div>
                </div>
            @endif
        </div>
    <div class="flex-1"  id="spotify" style="width: 100%; max-width: 100%; margin: 0 auto;">
        @php
            use App\Models\Setting;
            $spotify = Setting::firstOrCreate(
                ['key' => 'spotify'],
                ['value' => '']
            );
        @endphp

        @if($spotify->value)
            <div id="spotify-container">
                @if(!empty($spotify) && !empty($spotify->value))
                    {!! $spotify->value !!}
                @endif
            </div>
        @endif
    </div>
    </div>

</div>

<script>
    const players = @json($playerResults ?? '');
    const colorsBar = [
        '#a03b3b', // dunkles Rot/Bordeaux
        '#1a3f5c', // dunkles Blau
        '#8c7b3c',  // warmes Ocker/Braun
        '#3e5f1a', // dunkles Grün / Lime
    ];




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

        if (player.rank === 1) {
            rankCell.classList.add('name-gold');
        } else if (player.rank === 2) {
            rankCell.classList.add('name-silver');
        } else if (player.rank === 3) {
            rankCell.classList.add('name-bronze');
        }

        const rankCellWrapper = document.createElement('div');
        rankCellWrapper.classList.add('rank-cell-wrapper');

        rankCellWrapper.appendChild(rankCell);
        entry.appendChild(rankCellWrapper);

        const nameCell = document.createElement('div');
        nameCell.classList.add('name-cell');
        nameCell.innerText = player.name;
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
