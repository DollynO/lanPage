<div x-data="{takesPart: @entangle('takesPart')}">
    <div class="justify-end w-full flex mb-4">
        <x-button x-show="!takesPart" dark label="Attend" wire:click="takePart(true)"/>
        <x-button x-show="takesPart" dark label="Leave" wire:click="takePart(false)"/>
    </div>
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <div class="relative overflow-x-auto shadow-md rounded-lg">
            <x-table>
                <x-thead>
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
                </x-thead>
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
            </x-table>
        </div>
    </div>
</div>

