<div
    x-data="{
    showOverlay:@entangle('showOverlay'),
    selectedId:@entangle('selectedId'),
    }">
    <div class="top-0 left-0 z-10 w-full h-full backdrop-blur-lg fixed" x-show="showOverlay" x-cloak>
        <div class="w-1/2 h-auto mx-auto mt-60" @click.away="showOverlay = false">
            <x-custom-card class="!bg-zinc-900">
                <x-slot name="title">
                    {{$selectedDateTime}}
                </x-slot>
                <div>
                    <x-select
                        label="Select a food"
                        wire:model.defer="editRecipe"
                        placeholder="Select a recipe"
                        option-label="name"
                        option-value="id"
                        x-on:selected="$wire.fillFromSelection()"
                    >
                        @foreach($availableRecipes as $availableRecipe)
                            <x-select.option label="{{$availableRecipe['name']}}" value="{{$availableRecipe['id']}}" description="{{$availableRecipe['description']}}" />
                        @endforeach
                    </x-select>
                </div>
                <div>
                    <x-input label="{{__('Name')}}" wire:model.defer="edit.name"/>
                    <x-textarea label="{{__('Description')}}" wire:model.defer="edit.description"/>
                </div>
                <div class="bottom-0 relative flex justify-between mt-5">
                    <flux:button  x-on:click="showOverlay = false; $wire.resetEditFields()"
                                 size="sm"
                    >
                        {{__('Cancel')}}
                    </flux:button>

                    <flux:button  wire:click="addMeal"
                                  variant="fi-btn"
                                  size="sm"
                    >
                        {{__('Save')}}
                    </flux:button>

                </div>
            </x-custom-card>
        </div>
    </div>
    <div class="flex justify-between">
        <div>
            @if($this->selectedRowHasValidMeal())
                @if(!$this->isUserAlreadyAssigned())
                    <flux:button  wire:click="assignUserToMeal"
                                  variant="fi-btn"
                                  size="sm"
                    >
                        {{__('Assign as Chef')}}
                    </flux:button>

                @else
                    <flux:button  wire:click="removeUserFromMeal"
                                  variant="danger"
                                  size="sm"
                    >
                        {{__('Remove as Chef')}}
                    </flux:button>

                @endif
            @endif
        </div>
        <div class="justify-end w-full flex mb-4 gap-4">
            @if($this->selectedRowHasValidMeal())
                <div>
                    <flux:button  wire:click="editMeal"
                                  variant="fi-btn"
                                  size="sm"
                    >
                        {{__('Edit')}}
                    </flux:button>
                    <flux:button  wire:click="removeMeal"
                                  variant="danger"
                                  size="sm"
                    >
                        {{__('Remove')}}
                    </flux:button>

                </div>
            @else
                <flux:button  x-bind:disabled="selectedId < 0"
                              x-on:click="showOverlay = true; $wire.resetEditFields()"
                              variant="fi-btn"
                              size="sm"
                >
                    {{__('Add')}}
                </flux:button>
            @endif
        </div>
    </div>
    <div class="overflow-hidden sm:rounded-lg">
        <div class="relative overflow-x-auto rounded-lg">
            <x-table>
                <x-thead>
                    <x-tr>
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
                    </x-tr>
                </x-thead>
                <tbody>
                @foreach($foodSchedule as $key => $meal)

                        <x-tr class="bg-white hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:hover:bg-[#374151] fi-ta-row"
                            :selected="$meal && $selectedId && $meal['id'] == $selectedId"
                            wire:click="select({{$meal['id']}})"
                        >
                            <x-td>
                                {{$meal['date'] . ' / ' . ($meal['isLunch'] ? __('Lunch'): __('Dinner'))}}
                            </x-td>
                            <x-td>
                                {{$meal['meal']?->recipe->name ?? ''}}
                            </x-td>
                            <x-td>
                                {{implode(',', $meal['meal']?->chefs->pluck('name')->toArray() ?? [])}}
                            </x-td>
                            <x-td>
                                @if($meal['meal'] != null)
{{--                                    <livewire:components.star-rating :object="$meal['meal']->recipe ?? null"--}}
{{--                                                                     key="{{ now() }}"/>--}}
                                @endif
                            </x-td>
                        </x-tr>
                        @endforeach
                </tbody>
            </x-table>
        </div>
    </div>
</div>
