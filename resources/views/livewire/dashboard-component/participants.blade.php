<div x-data="{takesPart: @entangle('takesPart')}" class="border-1 bg-white rounded-lg">
    <div class="justify-end w-full flex mb-4">
        <x-button x-show="!takesPart" dark label="Attend" wire:click="takePart(true)"/>
        <x-button x-show="takesPart" dark label="Leave" wire:click="takePart(false)"/>
    </div>
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <div class="relative overflow-x-auto shadow-md rounded-lg">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <x-th>
                        {{__('Participant')}}
                    </x-th>
                    <x-th>
                        {{__('From')}}
                    </x-th>
                    <x-th>
                        {{__('To')}}
                    </x-th>
                </tr>
                </thead>
                <tbody>
                @foreach($party['participants'] as $participants)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <x-td>
                            {{$participants['name']}}
                        </x-td>
                        <x-td>
                            {{$participants['pivot']['start_day']}}
                        </x-td>
                        <x-td>
                            {{$participants['pivot']['end_day']}}
                        </x-td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

