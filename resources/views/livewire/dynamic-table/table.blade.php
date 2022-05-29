<div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
    <div class="w-full flex justify-between">
        <div class="relative mb-4">
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
        <div class="">
            <button onclick="window.location.href='{{ route('table_entry.'.$data['class']) }}'"
                    class="h-9 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                new Item
            </button>
        </div>
    </div>
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <table class="w-full">
            <thead class="text-lg font-medium">
            <tr class="bg-gray-200">
                @foreach($data['config'] as $key=>$value)
                    <th class="border cursor-pointer {{$value['style']}}" sortable wire:click="sort('{{$key}}')">
                        @switch($value['type'])
                            @case('text')
                            <div class="text-left m-1">
                                {{$value['display_name']}}
                            </div>
                            @break
                            @case('number')
                            <div class="text-right m-1">
                                {{$value['display_name']}}
                            </div>
                            @break
                            @default
                            <div class="text-left m-1">
                                {{$value['display_name']}}
                            </div>
                        @endswitch
                    </th>
                @endforeach
            </tr>
            </thead>
            <tbody class="text-lg text-gray-500" x-data>
            @foreach($data['query'] as $item)
                <tr id="{{$item['id']}}" @click="Livewire.emit('selectEntry',{{$item['id']}})"
                    class="cursor-pointer even:bg-gray-100 hover:bg-gray-200">
                    @foreach($data['config'] as $key=>$value)
                        <td class="border">
                            @switch($value['type'])
                                @case('text')
                                <div class="mr-2">
                                <span class="truncate">
                                {{$item->$key}}
                                </span>
                                </div>
                                @break
                                @case('number')
                                <div class="text-right mr-2">
                                    {{$item->$key}}
                                </div>
                                @break
                                @case('checkbox')
                                <div class="text-center">
                                    <input class="mr-2" type="checkbox" disabled="disabled"
                                           @if($item->$key) checked @endif>
                                </div>
                                @break

                            @endswitch
                        </td>
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @if( $data['entry'] != null )
        <div class="fixed right-0 top-0 col-3 h-full bg-white w-full max-w-lg ">
            @livewire('dynamic-table.detail.'.$data['class'],['entry' => $data['entry']])
        </div>
    @endif
</div>
