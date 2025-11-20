<div>
    <div wire:init="init">
        <!-- Wenn kein Token verfügbar oder ungültig -->
        @if(!$hasValidToken)
            <div class="p-4 rounded-md border font-medium
        bg-red-500/10 border-red-500 text-red-500
"> ✖ Noch nicht mit Spotify verbunden
            </div>
        @else

            <!-- PLAYER WENN TOKEN GÜLTIG -->
            <div
                class="bg-gray-900 shadow-lg text-white rounded-xl p-4 flex flex-col md:flex-row md:items-center gap-4">

                <!-- Cover -->
                <div class="w-16 h-16 bg-gray-700 rounded-lg overflow-hidden">
                    <img
                        src="{{ $playback['item']['album']['images'][0]['url'] ?? '/placeholder.jpg' }}"
                        alt="Cover"
                        class="w-full h-full object-cover">
                </div>

                <!-- Track Info -->
                <div class="flex-1">
                    <div>
                        @if(empty($playback))
                            <p>Kein Playback verfügbar oder Spotify nicht verbunden.</p>
                        @else
                            <div class="bg-gray-900 text-white p-4 rounded-xl">
                                <div wire:poll.5s="refreshPlayback"></div>
                                <p class="font-bold text-lg">
                                    Aktueller Track: {{ $playback['item']['name'] ?? 'unbekannt' }}
                                </p>
                                <p class="text-gray-300">
                                    Artist:
                                    {{ implode(', ', array_map(fn($a) => $a['name'], $playback['item']['artists'] ?? [])) }}
                                </p>
                                <p>
                                    Status: {{ ($playback['is_playing'] ?? false) ? 'Playing' : 'Paused' }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex items-center gap-3">

                    <!-- PREVIOUS -->
                    <button
                        wire:click="previous"
                        class="p-2 bg-gray-800 rounded-full hover:bg-gray-700 transition">
                        ⏮
                    </button>

                    <!-- Play/Pause -->
                    <button
                        wire:click="playToggle"
                        id="spotify-playpause"
                        class="p-3 rounded-full transition font-bold h-14 w-14
                            @if($isPlaying)
                                bg-red-600 hover:bg-red-500 text-white
                            @else
                                bg-green-600 hover:bg-green-500 text-black
                            @endif
                        "
                    >
                        @if($isPlaying)
                            ❚❚
                        @else
                            ▶
                        @endif
                    </button>

                    <!-- NEXT -->
                    <button
                        wire:click="next"
                        class="p-2 bg-gray-800 rounded-full hover:bg-gray-700 transition">
                        ⏭
                    </button>

                </div>
            </div>

        @endif
    </div>

</div>
