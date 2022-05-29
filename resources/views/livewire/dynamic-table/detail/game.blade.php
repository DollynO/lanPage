<div>
    <div class="grid grid-cols-12 gap-2 m-2">
        <div class="col-span-8">
            <label class="block text-sm font-medium text-gray-700 fw-bold text-lg">{{$entry['name']}}</label>
        </div>
        <div class="col-span-4 justify-self-end">
            <button @click="Livewire.emit('cancelDetail')"
                    class="h-9 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                X
            </button>
        </div>
        <div class="col-span-12">
            <div id="controls-carousel" class="relative bg-gray-200 dark" data-carousel="static">

                <div class="overflow-hidden relative h-48 rounded-lg sm:h-64 xl:h-80 2xl:h-96">
                    <div class="hidden duration-700 ease-in-out" data-carousel-item>
                        <img src="/docs/images/carousel/carousel-1.svg"
                             class="block absolute top-1/2 left-1/2 w-full -translate-x-1/2 -translate-y-1/2" alt="...">
                    </div>
                    <!-- Item 2 -->
                    <div class="hidden duration-700 ease-in-out" data-carousel-item="active">
                        <img src="/docs/images/carousel/carousel-2.svg"
                             class="block absolute top-1/2 left-1/2 w-full -translate-x-1/2 -translate-y-1/2" alt="...">
                    </div>
                </div>

                <button type="button"
                        class="flex absolute top-0 left-0 z-30 justify-center items-center px-4 h-full cursor-pointer group focus:outline-none"
                        data-carousel-prev="">
                    <span
                        class="inline-flex justify-center items-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                        <svg class="w-6 h-6 text-white dark:text-gray-800" fill="none" stroke="currentColor"
                             viewBox="0 0 24 24"
                             xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 19l-7-7 7-7">

                            </path>
                        </svg>
                        <span class="hidden">Previous</span>
                    </span>
                </button>
                <button type="button"
                        class="flex absolute top-0 right-0 z-30 justify-center items-center px-4 h-full cursor-pointer group focus:outline-none"
                        data-carousel-next="">
                    <span
                        class="inline-flex justify-center items-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                        <svg class="w-6 h-6 text-white dark:text-gray-800" fill="none" stroke="currentColor"
                             viewBox="0 0 24 24"
                             xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                        <span class="hidden">Next</span>
                    </span>
                </button>
            </div>
        </div>
        <div class="col-span-12 justify-self-end">
            <button @click="Livewire.emit('cancelDetail')"
                    class="mr-3 h-9 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                Bearbeiten
            </button>
        </div>
        <div class="col-span-12">
            <label class="block text-sm font-medium text-gray-700 fw-bold">Source:</label>
            <input type="text" wire:model="entry.source" id="source" autocomplete="given-name"
                   class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md disabled"
                   disabled>
        </div>
        <div class="col-span-6">
            <label class="block text-sm font-medium text-gray-700 fw-bold">Spieler:</label>
            <input type="text" wire:model="entry.player_count" id="player_count" autocomplete="given-name"
                   class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md disabled"
                   disabled>
        </div>
        <div class="col-span-6">
            <label class="block text-sm font-medium text-gray-700 fw-bold">Preis:</label>
            <input type="text" wire:model="entry.price" id="price" autocomplete="given-name"
                   class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md disabled"
                   disabled>
        </div>
        <div class="col-span-12">
            <label class="block text-sm font-medium text-gray-700 fw-bold">Schon gespielt:</label>
            <input type="checkbox" wire:model="entry.already_played" id="source" autocomplete="given-name"
                   class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md disabled"
                   disabled>
        </div>
        <div class="col-span-12">
            <label class="block text-sm font-medium text-gray-700 fw-bold">Notiz:</label>
            <textarea type="text" wire:model="entry.note" id="source" autocomplete="given-name"
                      class="mt-1 h-28 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md disabled w-full"
                      disabled>
            </textarea>
        </div>
    </div>
</div>
