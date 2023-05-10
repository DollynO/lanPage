<x-slide-in>
    <x-slot name="title">
        {{$party['start_date']}} - {{$party['end_date']}}
    </x-slot>

    <x-slot name="upperButton">
        @if(array_key_exists('images',$party))
            <div id="carouselExampleCaptions" class="carousel slide relative" data-bs-ride="carousel">
                <div class="carousel-inner relative w-full overflow-hidden">
                    @foreach($party['images'] as $image)
                        <div class="carousel-item active relative float-left w-full">
                            <div class="relative overflow-hidden bg-no-repeat bg-cover"
                                 style="background-position: 50%;">
                                <img src="https://mdbootstrap.com/img/Photos/Slides/img%20(123).jpg"
                                     class="block w-full"/>
                                <div
                                    class="absolute top-0 right-0 bottom-0 left-0 w-full h-full overflow-hidden bg-fixed bg-black opacity-50"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <button
                    class="m-auto carousel-control-prev absolute top-0 bottom-0 flex items-center justify-center p-0 text-center border-0 hover:outline-none hover:no-underline focus:outline-none focus:no-underline left-0"
                    type="button"
                    data-bs-target="#carouselExampleCaptions"
                    data-bs-slide="prev"
                >
                    <span class="carousel-control-prev-icon inline-block bg-no-repeat" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button
                    class="m-auto carousel-control-next absolute top-0 bottom-0 flex items-center justify-center p-0 text-center border-0 hover:outline-none hover:no-underline focus:outline-none focus:no-underline right-0"
                    type="button"
                    data-bs-target="#carouselExampleCaptions"
                    data-bs-slide="next"
                >
                    <span class="carousel-control-next-icon inline-block bg-no-repeat" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        @endif
    </x-slot>

    <x-datetime-picker wire:model.defer="party.start_date" x-bind:disabled="!inEditState" label="{{__('Start date')}}"
                       :without-time="true"/>
    <x-datetime-picker wire:model.defer="party.end_date" x-bind:disabled="!inEditState" label="{{__('End date')}}"
                       :without-time="true"/>
    <x-input wire:model.defer="party.location" x-bind:disabled="!inEditState" label="{{__('Location')}}"/>
    <x-checkbox wire:model="party.is_active" x-bind:disabled="!inEditState" left-label="{{__('Active')}}"/>

</x-slide-in>
