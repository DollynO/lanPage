<div
    x-data="{
    showOverlay:@entangle('showOverlay'),
    selectedId:@entangle('selectedId'),
    }">
    <div class="top-0 left-0 z-10 w-full h-full backdrop-blur-lg bg-gray-200/50 fixed" x-show="showOverlay">
        <div class="w-1/2 h-auto mx-auto mt-60" @click.away="showOverlay = false">
            <x-customCard>
                <x-slot name="title">
                    {{$selectedDateTime}}
                </x-slot>
                <div>
                    <x-select
                        label="Select a food"
                        wire:model.defer="editRecipe"
                        placeholder="Select some user"
                        :async-data="route('api.recipes')"
                        :searchable="false"
                        option-label="name"
                        option-value="id"
                        x-on:selected="$wire.fillFromSelection()"
                    />
                </div>
                <div>
                    <x-input label="{{__('Name')}}" wire:model="editDishName"/>
                    <x-textarea label="{{__('Description')}}" wire:model="editDishDescription"/>
                </div>
                <div class="bottom-0 relative flex justify-between mt-5">
                    <x-button red label="{{__('Cancel')}}"
                              x-on:click="showOverlay = false;this.$wire.resetEditFields()"/>
                    <x-button dark label="{{__('Save')}}"
                              wire:click="AddMeal"/>
                </div>
            </x-customCard>
        </div>
    </div>
    <div class="flex justify-between">
        <div>
            @if($this->SelectedRowHasValidMeal())
                @if(!$this->IsUserAlreadyAssigned())
                    <x-button class="mb-4 w-48" dark label="{{__('Assign as Chef')}}"
                              wire:click="AssignUserToMeal"/>
                @else
                    <x-button class="mb-4 w-48" dark label="{{__('Remove as Chef')}}" wire:click="RemoveUserFromMeal"/>
                @endif
            @endif
        </div>
        <div class="justify-end w-full flex mb-4 gap-4">
            @if($this->SelectedRowHasValidMeal())
                <div>
                    <x-button dark label="Edit" wire:click="editMeal"/>
                    <x-button dark label="Remove" wire:click="removeMeal"/>
                </div>
            @else
                <x-button x-bind:disabled="selectedId < 0" dark label="Add" x-on:click="showOverlay = true"/>
            @endif
        </div>
    </div>
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <div class="relative overflow-x-auto shadow-md rounded-lg">
            <x-table>
                <x-thead>
                    <tr>
                        <x-th>
                            {{__('Date / Time')}}
                        </x-th>
                        <x-th>
                            {{__('Dish')}}
                        </x-th>
                        <x-th>
                            {{__('Chefs')}}
                        </x-th>
                        <x-th>
                            {{__('Rating')}}
                        </x-th>
                    </tr>
                </x-thead>
                <tbody>
                @foreach($foodSchedule as $key => $meal)
                    @if($meal['id'] == $selectedId)
                        <tr class='bg-gray-500 border-b border-gray-300 text-gray-50'
                            wire:click="select({{$meal['id']}})">
                    @else
                        <tr class="bg-white border-b hover:bg-gray-50" wire:click="select({{$meal['id']}})">
                            @endif
                            <x-td>
                                {{$meal['date'] . ' / ' . ($meal['isLunch'] ? __('Lunch'): __('Dinner'))}}
                            </x-td>
                            <x-td>
                                {{$meal['meal']?->recipe->name ?? ''}}
                            </x-td>
                            <x-td>
                                {{$meal['meal']?->chefs->pluck('name') ?? ''}}
                            </x-td>
                            <x-td>
                                @if($meal['meal'] != null)
                                    <livewire:components.star-rating :object="$meal['meal']->recipe ?? null"
                                                                     key="{{ now() }}"/>
                                @endif
                            </x-td>
                        </tr>
                        @endforeach
                </tbody>
            </x-table>
        </div>
    </div>
</div>
