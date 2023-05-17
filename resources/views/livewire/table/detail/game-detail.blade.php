<x-slide-in>
    <x-slot name="title">
        {{$game['name']}}
    </x-slot>

    <x-slot name="upperButton">
        @if(array_key_exists('images',$game))
            <div id="carouselExampleCaptions" class="carousel slide relative" data-bs-ride="carousel">
                <div class="carousel-inner relative w-full overflow-hidden">
                    @foreach($game['images'] as $image)
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

    <x-input label="{{__('Name')}}" wire:model.defer="game.name" x-bind:disabled="!inEditState"/>
    <x-input label="{{__('Genre')}}" wire:model.defer="game.genre" x-bind:disabled="!inEditState"/>
    <x-input label="{{__('Source')}}" wire:model.defer="game.source" x-bind:disabled="!inEditState"/>
    <div class="grid grid-cols-2 gap-2 mt-2">
        <x-input label="{{__('Price')}}" class="disabled:border-1" wire:model.defer="game.price"
                 x-bind:disabled="!inEditState"/>
        <x-input label="{{__('Player count')}}" class="disabled:border-1" wire:model.defer="game.player_count"
                 x-bind:disabled="!inEditState"/>
    </div>
    <x-checkbox label="{{__('Already played')}}" wire:model.defer="game.already_played"
             x-bind:disabled="!inEditState"/>

    <x-textarea label="{{__('Note')}}" lass="disabled:border-1" wire:model.defer="game.note"
                x-bind:disabled="!inEditState"/>
</x-slide-in>
