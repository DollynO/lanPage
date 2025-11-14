<div>
    <div>
        <div class="justify-end w-full flex mb-4">


            <flux:button
                wire:click="takePart(true)"
                x-show="!$wire.takesPart"
                variant="fi-btn"
                size="sm"
            >
                Attend
            </flux:button>

<div>
    <x-filament::modal>
        <x-slot name="trigger">

            <flux:button  x-on:click="$openModal('simpleModal')"
                          x-show="$wire.takesPart"
                          variant="danger"
                          size="sm"
            >
                Leave
            </flux:button>
        </x-slot>

        <x-slot name="heading">
            Really leave?
        </x-slot>

        <x-slot name="description">
            If you leave all your tournament points, game and meal suggestions are deleted.
        </x-slot>

        <x-slot name="footerActions">
            <flux:button     x-on:click="close;$wire.takePart(false);"
                          variant="danger"
                          size="sm"
            >
                Delete
            </flux:button>

        </x-slot>
    </x-filament::modal>

</div>
        </div>
    {{ $this->table }}
    </div>
</div>
