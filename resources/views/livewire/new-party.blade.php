<form wire:submit.prevent="submit" method="POST">
    <div class="shadow overflow-hidden sm:rounded-md min-h-[500px] relative bg-white">
        <div class="px-4 py-5 bg-white sm:p-6 h-50">
            <div class="flex flex-column">
                <div class="flex flex-row gap-4">
                    <div class="w-1/2">
                        <x-datetime-picker wire:model.defer="start_date" :without-time="true" placeholer="{{__('Start date')}}" label="{{__('Start date')}}" parse-format="YYYY-MM-DD"/>
                    </div>
                    <div class="w-1/2">
                        <x-datetime-picker wire:model.defer="end_date" :without-time="true" placeholer="{{__('End date')}}" label="{{__('End date')}}" parse-format="YYYY-MM-DD"/>
                    </div>
                </div>
                <x-input wire:model="location" placeholder="{{__('Location')}}" label="{{__('Location')}}" />
            </div>
        </div>
        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6 absolute inset-x-0 bottom-0">
            <x-button dark wire:click="submit" label="{{__('Save')}}"/>
        </div>
    </div>
</form>
