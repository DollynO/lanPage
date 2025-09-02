<div class="px-4 mx-auto sm:px-6 lg:px-8" style="max-width: 100rem;">
    <div class="flex flex-col gap-2 w-full">
        @if(!$selectedPartyId)
            <x-custom-card>
                <x-slot name="title">
                    No Party found
                </x-slot>
            </x-custom-card>
        @else
            <div class="flex flex-col gap-4 md:flex-row md:gap-2 md:justify-between">
                <x-custom-card>
                    <x-slot name="title">
                        {{__('Participants')}}
                    </x-slot>
                    <livewire:dashboard-component.participants class="flex w-1/2" key="{{ now() }}"
                                                               :party="$this->selectedParty()"/>

                </x-custom-card>
                <x-custom-card>
                    <x-slot name="title">
                        {{__('Checklist')}}
                    </x-slot>
                    <livewire:dashboard-component.checklist class="flex" key="{{now()}}"/>
                </x-custom-card>
            </div>
            <div class="flex flex-col gap-4 md:flex-row md:gap-2 md:justify-between">
                <x-custom-card>
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
                    <livewire:dashboard-component.spotify class="flex" key="{{now()}}" />
                </x-custom-card>
            </div>
            <div class="flex flex-col gap-4 md:flex-row md:gap-2 md:justify-between">
                <x-custom-card>
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
            <div class="flex flex-col gap-4 md:flex-row md:gap-2 md:justify-between">
                <x-custom-card>
                    <livewire:dashboard-component.game-suggestions class="flex" key="{{now()}}"
                                                                   :party="$this->selectedParty()"/>
                </x-custom-card>
            </div>
        @endif
    </div>
</div>
