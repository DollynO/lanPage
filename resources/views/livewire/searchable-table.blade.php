<div>
    <style>
        td {
            margin-right: 10px;
        }
    </style>
    <table>
        <thead>
        <tr>
            @foreach($data['config'] as $key=>$value)
                <td>
                    @switch($value['type'])
                        @case('text')
                        <div class="text-left mt-2">
                            {{$value['display_name']}}
                        </div>
                        @break
                        @case('number')
                        <div class="text-right mt-2">
                            {{$value['display_name']}}
                        </div>
                        @break
                        @default
                        <div class="text-left mt-2">
                            {{$value['display_name']}}
                        </div>
                    @endswitch
                </td>
            @endforeach
        </tr>
        </thead>
        <tbody>
        @foreach($data['query'] as $item)
            <tr>
                @foreach($data['config'] as $key=>$value)
                    <td>
                        @switch($value['type'])
                            @case('text')
                            <div class="">
                                {{$item->$key}}
                            </div>
                            @break
                            @case('number')
                            <div class="text-right">
                                {{$item->$key}}
                            </div>
                            @break
                            @case('checkbox')
                            <input type="checkbox" disabled="disabled" @if($item->$key) checked @endif>
                            @break

                        @endswitch
                    </td>
                @endforeach
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
