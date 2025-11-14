<div>

    <div>
        <div wire:loading.remove wire:target="activeTab" class="{{ $activeTab === 0 ? '' : 'hidden' }}">
            <livewire:dashboard-component.game-suggestions-tournament/>
        </div>
        <div wire:loading.remove wire:target="activeTab" class="{{ $activeTab === 1 ? '' : 'hidden' }}">
            <livewire:dashboard-component.leaderboard-tournament/>
        </div>
    </div>
</div>
