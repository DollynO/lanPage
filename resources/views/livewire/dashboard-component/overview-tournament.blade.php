<div>

    <div>
        <div wire:loading.remove wire:target="activeTab" class="{{ $activeTab === 0 ? '' : 'hidden' }}">
            @livewire('game-suggestions-tournament')
        </div>
        <div wire:loading.remove wire:target="activeTab" class="{{ $activeTab === 1 ? '' : 'hidden' }}">
            @livewire('leaderboard-tournament')
        </div>
    </div>

    <ul class="flex border-b">
        <li wire:click="$set('activeTab', 0)"
            class="cursor-pointer py-2 px-4 {{ $activeTab === 0 ? 'bg-gray-500' : 'bg-gray-200' }}">
            <span class="{{ $activeTab === 0 ? 'text-gray-50' : 'text-gray-500' }}">Game-Suggestions</span>
        </li>
        <li wire:click="$set('activeTab', 1)"
            class="cursor-pointer py-2 px-4 {{ $activeTab === 1 ? 'bg-gray-500' : 'bg-gray-200' }}">
            <span class="{{ $activeTab === 1 ? 'text-gray-50' : 'text-gray-500' }}">Leaderboard</span>
        </li>
    </ul>
</div>
