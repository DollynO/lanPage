<div>
    <div class="w-full flex justify-between">
        <div class="relative mb-4 flex">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                     xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                          d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                          clip-rule="evenodd"></path>
                </svg>
            </div>
            <input id="searchInput" wire:model="search" type="text"
                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-80 pl-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                   placeholder="Search for items">
        </div>
        <x-button label="{{__('Filter')}}"/>
        <x-button dark class="flex h-10" x-on:click="$wire.new()" label="{{__('new Item')}}"/>
    </div>
    <div class="flex flex-row">
        @foreach($this->filters() as $index => $filter)
            <div class="flex w-50">
                {{$filter->render($component)}}
            </div>
        @endforeach
    </div>
    <div class="relative overflow-x-auto shadow-md rounded-lg">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
            <tr>
                @foreach($this->columns() as $column)
                    <th wire:click="sort('{{ $column->key }}')">
                        <div class="py-3 px-6 flex items-center cursor-pointer">
                            {{ $column->label }}
                            @if($sortBy === $column->key)
                                @if ($sortDirection === 'asc')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                         fill="currentColor">
                                        <path fill-rule="evenodd"
                                              d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                         fill="currentColor">
                                        <path fill-rule="evenodd"
                                              d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                @endif
                            @endif
                        </div>
                    </th>
                @endforeach
            </tr>
            </thead>
            <tbody>
            @foreach($data as $row)
                <tr class="bg-white border-b hover:bg-gray-50">
                    @foreach($this->columns() as $column)
                        @if($column->isLivewire)
                            <td>
                                <div class="py-3 px-6 flex items-center cursor-pointer">
                                    <div>
                                        @php($key = rand(11111,99999))
                                        @livewire($column->component, ['object' => $row], key($key))
                                    </div>
                                </div>
                            </td>
                        @else
                            <td wire:click="detail({{$row}})">
                                <div class="py-3 px-6 flex items-center cursor-pointer">
                                    <x-dynamic-component
                                        :component="$column->component"
                                        :value="$row[$column->key]"/>
                                </div>
                            </td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div x-data="{ open: @entangle('showDetail')}">
        @if($showDetail)
            @php($key = rand(11111,99999))
            @livewire($this->detailComponent(), ['object' => $object], key($key))
        @endif
    </div>
</div>
