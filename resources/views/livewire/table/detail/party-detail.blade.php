@component('components.SlideIn')
    @slot('title')
        {{$game['name']}}
    @endslot

    @slot('upperButton')
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
    @endslot

    <label>{{__('Name')}}</label>
    <input type="text" id="name" wire:model.defer="game.name" x-bind:disabled="!inEditState"/>
    @error('game.name') <span class="error text-red-400">{{ $message }}</span> @enderror

    <label class="mt-2">{{__('Source')}}</label>
    <input type="text" id="source" wire:model.defer="game.source" x-bind:disabled="!inEditState"/>
    @error('game.source') <span class="error text-red-400">{{ $message }}</span> @enderror

    <div class="grid grid-cols-2 gap-2 mt-2">
        <div class="flex flex-column">
            <label>{{__('Price')}}</label>
            <input type="number" id="price" wire:model.defer="game.price" x-bind:disabled="!inEditState"
                   class="disabled:border-1"/>
            @error('game.price') <span class="error text-red-400">{{ $message }}</span> @enderror
        </div>
        <div class="flex flex-column">
            <label>{{__('Player count')}}</label>
            <input type="number" id="player_count" wire:model.defer="game.player_count" x-bind:disabled="!inEditState"
                   class="disabled:border-1"/>
            @error('game.player_count') <span class="error text-red-400">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="flex flex-row mt-4">
        <label>
            <input type="checkbox" id="already_played" wire:model.defer="game.already_played"
                   x-bind:disabled="!inEditState">
            {{__('Already played')}}
            @error('game.already_played') <span class="error text-red-400">{{ $message }}</span> @enderror
        </label>
    </div>

    <label class="mt-2">{{__('Note')}}</label>
    <textarea id="note" wire:model.defer="game.note" x-bind:disabled="!inEditState"
              class="disabled:border-1"/>
    @error('game.note') <span class="error text-red-400">{{ $message }}</span> @enderror
@endcomponent
