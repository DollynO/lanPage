<div class="space-y-6">

    {{-- Verbindungsstatus --}}
    <div class="p-4 rounded-md border font-medium
    @if($hasValidToken)
        bg-lime-400/10 border-lime-400 text-lime-400
    @else
        bg-red-500/10 border-red-500 text-red-500
    @endif
">
        @if($hasValidToken)
            ✔ Spotify ist verbunden
        @else
            ✖ Noch nicht mit Spotify verbunden
        @endif
    </div>


    {{-- Client ID --}}
    <div class="space-y-1">
        <flux:label for="spotify_client_id">Spotify Client ID</flux:label>
        <flux:input
            id="spotify_client_id"
            type="text"
            wire:model.defer="spotify_client_id"
            placeholder="Deine Spotify Client ID"
        />
    </div>

    {{-- Client Secret --}}
    <div class="space-y-1">
        <flux:label for="spotify_client_secret">Spotify Client Secret</flux:label>
        <flux:input
            id="spotify_client_secret"
            type="password"
            wire:model.defer="spotify_client_secret"
            placeholder="Dein Spotify Client Secret"
        />
    </div>

    {{-- Buttons --}}
    <div class="flex gap-3 pt-4">

        {{-- Speichern --}}
        <flux:button
            wire:click="saveKeys"
            variant="fi-btn"
        >
            Speichern
        </flux:button>

        {{-- Connect / Disconnect --}}
        @if($hasValidToken)
            <flux:button
                wire:click="disconnectSpotify"
                variant="danger"
            >
                Disconnect Spotify
            </flux:button>
        @else
            <flux:button
                wire:click="connectSpotify"
                variant="primary"
            >
                Connect Spotify
            </flux:button>
        @endif

    </div>

</div>
