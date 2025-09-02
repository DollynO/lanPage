<div>
    @php
        $maxWidth = 500;
        $referenceTotal = max(1, $playerResults[0]['total_points']?? 0);
        $pxPerPoint = $maxWidth / $referenceTotal;
    @endphp

    <figure class="w-full">
        <div class="y-axis">
            <h3>Players</h3>
        </div>

        <div class="graphic w-full">
            @foreach($playerResults as $player)
                <div class="row w-full mb-2">
                    <h6>{{ $player['name'] }} ({{ $player['total_points'] }} pts)</h6>

                    {{-- Balkenbreite = Summe der Scores * pxPerPoint --}}
                    <div class="chart" style="display:flex;">
                        @foreach($player['scores'] as $index => $score)
                            @php
                                $scoreWidth = round($score * $pxPerPoint);
                            @endphp
                            <span class="block color-{{ $index }}"
                                  style="width: {{ $scoreWidth }}px;"
                                  title="Round {{ $index + 1 }}">
                            <span class="value">{{ $score }}</span>
                        </span>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <div class="x-axis">
            <h3>Rounds</h3>
            <ul class="legend flex gap-4">
                <li class="color-0">Round 1</li>
                <li class="color-1">Round 2</li>
                <li class="color-2">Round 3</li>
                <li class="color-3">Round 4</li>
            </ul>
        </div>
    </figure>
</div>
