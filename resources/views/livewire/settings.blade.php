<div xmlns:flux="http://www.w3.org/1999/html">
    @if (session()->has('message'))
        <div class="bg-green-600 text-white p-2 rounded mb-2">
            {{ session('message') }}
        </div>
    @endif

    <flux:label
        for="spotify" >
        Spotify Iframe
    </flux:label>

    <flux:textarea
        id="spotify"
        wire:model="spotify">

    </flux:textarea>


        <flux:button
            wire:click="save"
            variant="fi-btn"
        >
            Speichern
        </flux:button>
</div>
