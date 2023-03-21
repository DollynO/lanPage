<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="grid grid-cols-2 gap-2">
        <livewire:dashboard-component.participants key="{{ now() }}" :party="$selectedParty"/>
        <livewire:dashboard-component.meals key="{{ now() }}"/>
    </div>
</div>
