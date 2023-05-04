<div
    x-data="{openFilter: false,
    perPage: @entangle('perPage'),
    pageCount: @entangle('pageCount'),
    currentPage: @entangle('currentPage'),
    selectedRowId: @entangle('selectedRowId')}">
    @if(count($this->customButtons()))
        <div class="w-full flex flex-row justify-end gap-4">
            @foreach($this->customButtons() as $button)
                <x-button x-bind:disabled="{{$button->enableCondition}}" dark class="mb-4 h-[2.6rem]" x-on:click="$wire.{{$button->function}}" label="{{__($button->name)}}"/>
            @endforeach
        </div>
    @endif
    @if(!$this->disableHeaderBar())
        <div class="w-full flex justify-between">
            <div class="flex flex-row">
                <span class="mr-2 mt-2">{{__('Per page:')}}</span>
                <x-select
                    :options="[10, 25, 50, 100]"
                    wire:model="perPage"
                    :clearable="false"
                    class="w-30"
                    x-on:selected="currentPage = 1"
                />
            </div>
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
                       placeholder="Search for items"
                       x-bind:disabled="openFilter">
                <x-button dark label="{{__('Filter')}}" class="h-full -ml-2"
                          x-on:click="$wire.resetFilter(), openFilter = !openFilter" icon="filter"/>
            </div>
            <x-button dark class="mb-4 h-[2.6rem]" x-on:click="$wire.new()" label="{{__('new Item')}}"/>
        </div>
        <div x-show="openFilter" class="mb-4">
            <div class="grid grid-cols-4 gap-4">
                @foreach($this->filters() as $index => $filter)
                    <div class="">
                        {{$filter->render($component)}}
                    </div>
                @endforeach
            </div>
        </div>
    @endif
    <div class="relative overflow-x-auto shadow-md rounded-lg">
        <x-table>
            <x-thead>
                <tr>
                    @foreach($this->columns() as $column)
                        <x-th wire:click="sort('{{ $column->key }}')">
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
                        </x-th>
                    @endforeach
                </tr>
            </x-thead>
            <tbody>
            @foreach($data as $row)
                @if($row->id == $this->selectedRowId)
                    <tr class='bg-gray-500 border-b border-gray-300 text-gray-50'>
                @else
                    <tr class="bg-white border-b hover:bg-gray-50">
                @endif
                @foreach($this->columns() as $column)
                        @if($column->isLivewire)
                            <x-td>
                                @php($key = rand(11111,99999))
                                @livewire($column->component, ['object' => $row], key($key))
                            </x-td>
                        @else
                            <x-td wire:click="detail({{$row}})">
                                <x-dynamic-component
                                    :component="$column->component"
                                    :value="array_reduce(explode('.', $column->key), function ($array, $key) { return $array[$key]; }, $row)"/>
                            </x-td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </x-table>
        @if(!$this->disableFooterBar())
            <div>
                <div class="flex items-center justify-between px-4 py-3 sm:px-6">
                    <div class="flex flex-1 justify-between sm:hidden">
                        <a href="#"
                           class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Previous</a>
                        <a href="#"
                           class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Next</a>
                    </div>
                    <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700">
                                Showing
                                <span class="font-medium">{{1 + max($currentPage - 1, 0) * $perPage}}</span>
                                to
                                <span
                                    class="font-medium">{{$currentPage == $pageCount ? $totalRecords : $currentPage * $perPage}}</span>
                                of
                                <span class="font-medium">{{$totalRecords}}</span>
                                results
                            </p>
                        </div>
                        <div>
                            <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                                <a href="#"
                                   class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                    <span class="sr-only">Previous</span>
                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd"
                                              d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                </a>
                                <!-- Current: "z-10 bg-indigo-600 text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600", Default: "text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:outline-offset-0" -->
                                @if($pageCount <= 5)
                                    @for($i = 0; $i < $pageCount; $i++)
                                        <x-button
                                            dark
                                            label="{{$i}}"
                                            x-on:click="currentPage = {{$i}}"
                                        />
                                    @endfor
                                @else
                                    <x-button
                                        dark
                                        :outline="$currentPage != 1"
                                        label="1"
                                        x-on:click="currentPage = 1"
                                    />

                                    @if($currentPage > 2 && $currentPage < $pageCount-1)
                                        <span
                                            class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300 focus:outline-offset-0">...</span>


                                        <x-button
                                            dark
                                            outline
                                            label="{{$currentPage - 1}}"
                                            x-on:click="currentPage--"
                                        />
                                        <x-button
                                            dark
                                            label="{{$currentPage}}"
                                        />
                                        <x-button
                                            dark
                                            outline
                                            label="{{$currentPage + 1}}"
                                            x-on:click="currentPage++"
                                        />

                                        <span
                                            class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300 focus:outline-offset-0">...</span>
                                    @elseif($currentPage <= 2)
                                        <x-button
                                            dark
                                            :outline="$currentPage!=2"
                                            label="2"
                                            x-on:click="currentPage = 2"
                                        />
                                        <x-button
                                            dark
                                            outline
                                            label="3"
                                            x-on:click="currentPage = 3"
                                        />
                                        <span
                                            class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300 focus:outline-offset-0">...</span>
                                    @else
                                        <span
                                            class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300 focus:outline-offset-0">...</span>
                                        <x-button
                                            dark
                                            :outline="$currentPage!=$pageCount-2"
                                            label="{{$pageCount - 2}}"
                                            x-on:click="currentPage = {{$pageCount - 2}}"
                                        />
                                        <x-button
                                            dark
                                            :outline="$currentPage!=$pageCount-1"
                                            label="{{$pageCount - 1}}"
                                            x-on:click="currentPage = {{$pageCount - 1}}"
                                        />
                                    @endif
                                    <x-button
                                        dark
                                        :outline="$currentPage!=$pageCount"
                                        label="{{$pageCount}}"
                                        x-on:click="currentPage = {{$pageCount}}"
                                    />
                                @endif
                                <a href="#"
                                   class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                    <span class="sr-only">Next</span>
                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd"
                                              d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                </a>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <div x-data="{ open: @entangle('showDetail')}">
        @if($showDetail && $this->detailComponent() !== null)
            @php($key = rand(11111,99999))
            @livewire($this->detailComponent(), ['object' => $object], key($key))
        @endif
    </div>
</div>
