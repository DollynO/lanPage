<div>
    <div class="w-full flex flex-row mb-2 justify-between">
        <div class="flex flex-row w-1/2">
                <i class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest transition bi bi-search"></i>
                <div class="ml-1 w-full">
                    <input id="searchInput" wire:model="search" type="text" class="-ml-4 w-full h-9 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block">
                </div>
        </div>
        <div>
            <button onclick="window.location.href='{{ route('table_entry.'.$data['class']) }}'"
                    class="h-9 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">new Item</button>
        </div>
    </div>
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
    <table class="w-full">
        <thead class="text-lg font-medium">
        <tr>
            @foreach($data['config'] as $key=>$value)
                <th class="border cursor-pointer" sortable wire:click="sort('{{$key}}')">
                    @switch($value['type'])
                        @case('text')
                        <div class="text-left mr-2">
                            {{$value['display_name']}}
                        </div>
                        @break
                        @case('number')
                        <div class="text-right mr-2">
                            {{$value['display_name']}}
                        </div>
                        @break
                        @default
                        <div class="text-left mr-2">
                            {{$value['display_name']}}
                        </div>
                    @endswitch
                </th>
            @endforeach
        </tr>
        </thead>
        <tbody class="text-sm">
        @foreach($data['query'] as $item)
            <tr id="{{$item['id']}}" wire:click="detail({{$item}})" class="cursor-pointer">
                @foreach($data['config'] as $key=>$value)
                    <td class="border">
                        @switch($value['type'])
                            @case('text')
                            <div class="mr-2">
                                {{$item->$key}}
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
        <div class="fixed right-0 top-0 col-3 h-full bg-white">
            @livewire('table-entry-detail',['entry'=> $data['entry'] ])
        </div>
    @endif
</div>
