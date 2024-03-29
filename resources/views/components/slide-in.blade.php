<section
    @keydown.window.escape="open = false"
    x-show="open"
    x-cloak
    class="relative z-10"
    aria-labelledby="slide-over-title"
    x-ref="dialog"
    aria-modal="true"
>
    <div
        x-show="open"
        x-cloak
        x-transition:enter="ease-in-out duration-500"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in-out duration-500"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity">
    </div>
    <div class="fixed inset-0 overflow-hidden">
        <div class="absolute inset-0 overflow-hidden">
            <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                <div
                    x-show="open"
                    x-cloak
                    x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700"
                    x-transition:enter-start="translate-x-full"
                    x-transition:enter-end="translate-x-0"
                    x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700"
                    x-transition:leave-start="translate-x-0"
                    x-transition:leave-end="translate-x-full"
                    class="pointer-events-auto w-screen max-w-md">
                    <!--@click.away="open = false"-->
                    <div class="flex h-full flex-col overflow-y-scroll bg-white py-6 shadow-xl">
                        <div>
                            <header class="px-4 sm:px-6">
                                <div class="flex items-start justify-between">
                                    <h2 class="text-lg font-medium text-gray-900" id="slide-over-title">
                                        {{$title}}
                                    </h2>
                                    <div class="ml-3 flex h-7 items-center">
                                        <button
                                            type="button"
                                            class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                            @click="open = false">
                                            <span class="sr-only">Close panel</span>
                                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                 viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                 aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </header>
                            <div class="relative mt-6 flex-1 px-4 sm:px-6"
                                 x-data="{inEditState: @entangle('inEditState'), confirmDelete: @entangle('confirmDelete')}">
                                {{$upperButton}}
                                <div class="w-full">
                                    <div class="grid grid-cols-2 gap-2 mt-2" x-show="!confirmDelete">
                                        <div>
                                            <x-button dark class="w-full" x-on:click="inEditState = true"
                                                      x-show="!inEditState" label="{{__('Edit')}}"/>
                                            <x-button dark class="w-full" x-on:click="$wire.save()" x-show="inEditState"
                                                      label="{{__('Save')}}"/>
                                        </div>
                                        <x-button dark x-on:click="confirmDelete = true" x-show="!confirmDelete"
                                                  x-bind:disabled="inEditState" label="{{__('Delete')}}"/>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2 mt-2" x-show="confirmDelete">
                                        <x-button negative x-on:click="$wire.delete(); open = false"
                                                  x-show="confirmDelete" label="{{__('Delete')}}"/>
                                        <x-button dark x-on:click="confirmDelete = false" x-show="confirmDelete"
                                                  label="{{__('Cancel')}}"/>
                                    </div>
                                    <div class="mt-2 flex flex-column gap-3">
                                        {{$slot}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
