<div>
    <div class="w-full flex flex-row">
        <div class="w-1/20" >
            <i wire:click="openSearch" class="bi bi-search"></i>
        </div>

        @if($showSearch)
        <div class="w-full">
            <input wire:model="search" type="text" class="w-full h-5">

        </div>
        @endif
            <div class="w-1/20">
            <i wire:click="addGame" class="bi bi-plus-circle"></i>
        </div>
    </div>
    <table class="w-full">
        <thead>
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
        <tbody>
        @foreach($data['query'] as $item)
            <tr>
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
