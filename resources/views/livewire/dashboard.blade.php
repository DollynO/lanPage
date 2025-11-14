<div class="">
    @if(!$selectedPartyId)
        <x-custom-card>
            <x-slot name="title">
                No Party found
            </x-slot>
        </x-custom-card>
    @else

        <div class="flex h-full w-full flex-1 flex-col card-gap rounded-xl">
            <div class="grid auto-rows-min card-gap md:grid-cols-2">
                <x-custom-card class="dark:md:border-r! dark:border-gray-700!">
                    <x-slot name="title">
                        {{__('Participants')}}
                    </x-slot>


                    <livewire:dashboard-component.participants :party="$this->selectedParty()"/>
                </x-custom-card>

                <x-custom-card>
                    <x-slot name="title">
                        {{__('Checklist')}}
                    </x-slot>
                    <livewire:dashboard-component.checklist class="flex" key="{{now()}}"/>
                </x-custom-card>
            </div>

            <div class="grid auto-rows-min card-gap md:grid-cols-2">
                <x-custom-card class="dark:md:border-r! dark:border-gray-700!">
                    <x-slot name="title">
                        {{__('Overview Leaderboard')}}
                    </x-slot>
                    <livewire:dashboard-component.leaderboard-overview class="flex" key="{{now()}}"
                                                                       :party="$this->selectedParty()"/>
                </x-custom-card>
                <x-custom-card>
                    <x-slot name="title">
                        {{__('Spotify')}}
                    </x-slot>
                    <livewire:dashboard-component.spotify class="flex" key="{{now()}}"/>
                </x-custom-card>
            </div>
            <div class="grid auto-rows-min card-gap  md:grid-cols-2">
                <x-custom-card class="dark:md:border-r! dark:border-gray-700!">
                    <x-slot name="title">
                        {{__('Tournament')}}
                    </x-slot>
                    <livewire:dashboard-component.overview-tournament class="flex" key="{{now()}}"/>
                </x-custom-card>
                <x-custom-card>
                    <x-slot name="title">
                        {{__('Food Schedule')}}
                    </x-slot>
                    <livewire:dashboard-component.food-schedule class="flex" key="{{ now() }}"
                                                                :party="$this->selectedParty()"/>
                </x-custom-card>
            </div>
            <div class="">
                <x-custom-card>
                    <x-slot name="title">
                        {{__('Game Suggestions')}}
                    </x-slot>
                    <livewire:dashboard-component.game-suggestion-dashboard class="flex" key="{{now()}}"
                                                                            :party="$this->selectedParty()"/>
                </x-custom-card>
            </div>
        </div>
    @endif
</div>
