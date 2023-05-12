<div x-data="{suggestionOverlay:@entangle('suggestionOverlay')}">
    <div class="top-0 left-0 z-10 w-full h-full backdrop-blur-lg bg-gray-200/50 fixed" x-show="suggestionOverlay" x-cloak>
        <div class="w-1/2 h-auto mx-auto mt-24" @click.away="suggestionOverlay = false">
            <x-custom-card>
                <div>
                    <div class="flex flex-col gap-2">
                        <div>
                            <x-select
                                label="Select a game"
                                wire:model.defer="editGame"
                                placeholder="Select a game"
                                :async-data="route('api.games')"
                                :searchable="false"
                                option-label="name"
                                option-value="id"
                                x-on:selected="$wire.fillFromSelection()"
                            />
                        </div>
                        <x-input label="{{__('Name')}}" wire:model="edit.name"/>
                        <x-input label="{{__('Genre')}}" wire:model="edit.genre"/>
                        <div class="flex w-full gap-4">
                            <div class="w-1/2">
                                <x-input label="{{__('Player count')}}" wire:model="edit.player_count"/>
                            </div>
                            <div class="w-1/2">
                                <x-input label="{{__('Price')}}" wire:model="edit.price"/>
                            </div>
                        </div>
                        <x-input label="{{__('Source')}}" wire:model="edit.source"/>
                    </div>
                </div>
                <div class="bottom-0 relative flex justify-between mt-5">
                    <x-button red label="{{__('Cancel')}}"
                              x-on:click="suggestionOverlay = false;this.$wire.resetEditFields()"/>
                    <x-button dark label="{{__('Save')}}"
                              wire:click="addGameSuggestion"/>
                </div>
            </x-custom-card>
        </div>
    </div>
    <livewire:table.game-suggestion-dashboard-table key="{{now()}}"
                                                    :party="$this->party"/>
</div>
