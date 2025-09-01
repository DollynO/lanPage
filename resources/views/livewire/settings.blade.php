<div>
    @if (session()->has('message'))
        <div class="bg-green-600 text-white p-2 rounded mb-2">
            {{ session('message') }}
        </div>
    @endif

    <label for="spotify" class="block font-bold dark:text-white mb-1">Spotify Iframe</label>
    <textarea id="spotify" wire:model="spotify" rows="5" class="w-full border rounded p-2"></textarea>

    <button wire:click="save" class="mt-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
        Speichern
    </button>
</div>
