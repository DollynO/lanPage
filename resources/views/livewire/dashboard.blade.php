<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="flex flex-col gap-2 w-full">
        @if(!$selectedPartyId)
            <x-custom-card>
                <x-slot name="title">
                    No Party found
                </x-slot>
            </x-custom-card>
        @else

            <div class="flex flex-row gap-2 justify-between">
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
                    <div class="flex flex-row w-full justify-between gap-4 m-auto px-4">
                        <div class="flex flex-col">
                            {{__('Gaming')}}
                            <ul class="list-disc">
                                <li>{{__('PC')}}</li>
                                <li>{{__('Display')}}</li>
                                <li>{{__('Keyboard')}}</li>
                                <li>{{__('Mouse')}}</li>
                                <li>{{__('LAN-Cable')}}</li>
                                <li>{{__('Headset')}}</li>
                                <li>{{__('Chair')}}</li>
                            </ul>
                        </div>
                        <div class="flex flex-col">
                            {{__('Sleepover')}}
                            <ul class="list-disc">
                                <li>{{__('Tooth brush')}}</li>
                                <li>{{__('Tooth paste')}}</li>
                                <li>{{__('Shower gel')}}</li>
                                <li>{{__('Clothes')}}</li>
                                <li>{{__('Bedding')}}</li>
                                <li>{{__('Sleeping mat')}}</li>
                                <li>{{__('Stuff for sauna')}}</li>
                            </ul>
                        </div>
                        <div class="flex flex-col">
                            {{__('For the group')}}
                            <ul class="list-disc">
                                <li>{{__('Shisha')}}</li>
                                <li>{{__('Ice cube machine')}}</li>
                                <li>{{__('Beerpong stuff')}}</li>
                            </ul>
                        </div>
                        <div class="flex flex-col">
                            {{__('Nice to have')}}
                            <ul class="list-disc">
                                <li>{{__('Chair')}}</li>
                                <li>{{__('Game to play (video/board)')}}</li>
                                <li>{{__('External fans')}}</li>
                            </ul>
                        </div>
                    </div>
                </x-custom-card>
            </div>
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

            <x-custom-card>
                <livewire:dashboard-component.game-suggestions class="flex" key="{{now()}}"
                                                               :party="$this->selectedParty()"/>
            </x-custom-card>
        @endif
    </div>
</div>
