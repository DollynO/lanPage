<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="flex flex-col gap-2 w-full">
        <div class="flex flex-row gap-2 justify-between">
            <x-customCard>
                <x-slot name="title">
                    {{__('Participants')}}
                </x-slot>
                <livewire:dashboard-component.participants class="flex w-1/2" key="{{ now() }}"
                                                           :party="$this->selectedParty()"/>

            </x-customCard>
            <x-customCard>
                <x-slot name="title">
                    {{__('Food Schedule')}}
                </x-slot>
                <livewire:dashboard-component.food-schedule class="flex" key="{{ now() }}"
                                                            :party="$this->selectedParty()"/>
            </x-customCard>
        </div>
        <x-customCard>
            <x-slot name="title">
                {{__('Food Schedule')}}
            </x-slot>
            <livewire:dashboard-component.food-schedule class="flex" key="{{ now() }}"
                                                        :party="$this->selectedParty()"/>
        </x-customCard>
        <x-customCard>
            <livewire:dashboard-component.game-suggestions class="flex" key="{{now()}}"
                                                           :party="$this->selectedParty()"/>
        </x-customCard>
        <div class="flex flex-row gap-2">
            <x-customCard>
                <x-slot name="title">
                    {{__('Bring list')}}
                </x-slot>
                <div class="flex flex-row w-full justify-between gap-4 m-auto">
                    <div class="flex flex-col">
                        {{__('Gaming')}}
                        <ul class="list-disc">
                            <li>{{__('PC')}}</li>
                            <li>{{__('Display')}}</li>
                            <li>{{__('Keyboard')}}</li>
                            <li>{{__('Mouse')}}</li>
                            <li>{{__('LAN-Cable')}}</li>
                            <li>{{__('Headset')}}</li>
                        </ul>
                    </div>
                    <div class="flex flex-col">
                        {{__('Sleepover')}}
                        <ul class="list-disc">
                            <li>{{__('Tooth brush')}}</li>
                            <li>{{__('Tooth paste')}}</li>
                        </ul>
                    </div>
                </div>
            </x-customCard>
        </div>
    </div>
</div>
