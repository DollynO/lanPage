<div>

    <div class="flex flex-col gap-4 md:flex-row md:gap-2 md:justify-between">
        <div class="" id="spotify" style="width: 100%; max-width: 100%; margin: 0 auto; padding:20px;">
            @php
                use App\Models\Setting;
                $spotify = Setting::firstOrCreate(
                    ['key' => 'spotify'],
                    ['value' => '']
                );
            @endphp

            @if($spotify->value)
                <div id="spotify-container">
                    @if(!empty($spotify) && !empty($spotify->value))
                        {!! $spotify->value !!}
                    @endif
                </div>
            @endif
        </div>
    </div>

</div>
