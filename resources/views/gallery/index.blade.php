

<x-layouts.app :title="__('Gallery')">

    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="z-0">
            <div class="max-w-[120rem] mx-auto">

                <div class="relative mb-6 w-full">
                    <flux:heading size="xl" level="4">{{ __('Photos') }}</flux:heading>
                    <flux:subheading size="lg" class="mb-6">{{ __('Manage Photos') }}</flux:subheading>
                    <flux:separator variant="subtle" />
                </div>
            @foreach($parties as $party)
                <div
                    x-data="{
                    showModal: false,
                    photos: @js($party->previewPhotos->values()),
                    activeIdx: 0,
                    open(idx){ this.activeIdx = idx; this.showModal = true }
                  }"
                    class="shadow-md rounded-lg overflow-hidden dark:text-white @if(!$loop->first) mt-2 @endif "
                >

                    <x-custom-card>
{{--                    <div class="flex items-center justify-between px-6 py-3 border-b ">--}}
{{--                        <div class="flex items-baseline gap-3">--}}
{{--                            <div class="text-lg font-semibold">{{ $party->name }}</div>--}}
{{--                        </div>--}}
{{--                    </div>--}}

                        <x-slot name="title">
                            {{ $party->name }}
                        </x-slot>

                    <div class="px-6 py-4 ">
                        <div class="flex gap-1" style="width: calc(9 * 160px); overflow: hidden;">
                            @foreach($party->previewPhotos as $photo)
                                <div class="flex-shrink-0 relative" style="width:150px; height:100px;">
                                    <button
                                        @click="open({{ $loop->index }})"
                                        class="border border-dashed border-gray-300 w-full h-full rounded overflow-hidden shadow-sm hover:shadow-lg transition gallery-button"
                                        style="background:#f5f5f7;"
                                    >
                                        <img loading="lazy"
                                             src="{{ asset('storage/'.$photo->path) }}"
                                             class="w-full h-full object-cover"
                                             alt="thumb">
                                    </button>
                                </div>
                            @endforeach

                            <div class="flex-shrink-0" style="width:160px; height:100px;">
                                <a href="{{ route('gallery.party', $party) }}"
                                   class="w-full h-full flex flex-col items-center justify-center rounded border border-dashed border-gray-300 text-xs text-gray-700 dark:text-white dark:hover:bg-lime-600 font-semibold hover:bg-gray-50 transition">
                                    <div class="mb-1">+{{ max(0, $party->photos_count - count($party->previewPhotos)) }}
                                        more
                                    </div>
                                    <div class="underline">Full gallery</div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <template x-teleport="body">
                        <div
                            x-show="showModal"
                            x-cloak
                            x-transition
                            @keydown.window.escape="showModal=false"
                            class="fixed inset-0 flex items-center justify-center px-4 z-[2147483647]"
                        >
                            <!-- Backdrop -->
                            <div
                                class="absolute inset-0"
                                style="background: rgba(0,0,0,0.75);"
                                @click="showModal=false"
                            ></div>

                            <div class="relative z-10 w-full" style="max-width:1200px; height:80vh;" @click.stop>
                                <!-- Close -->
                                <button
                                    @click="showModal=false"
                                    style="position:absolute; right:16px; transform:translateY(-50%); z-index:20; "
                                    class="text-white bg-black/60 hover:bg-black/80 rounded-full p-4 shadow-lg gallery-button translate-button"
                                    aria-label="Close"
                                >&times;</button>

                                <!-- Prev -->
                                <button
                                    @click="activeIdx = (activeIdx - 1 + photos.length) % photos.length"
                                    style="position:absolute; left:16px; top:50%; transform:translateY(-50%); z-index:20;"
                                    class="text-white bg-black/60 hover:bg-black/80 rounded-full p-4 shadow-lg gallery-button translate-button"
                                    aria-label="Previous"
                                >&lsaquo;</button>

                                <!-- Next -->
                                <button
                                    @click="activeIdx = (activeIdx + 1) % photos.length"
                                    style="position:absolute; right:16px; top:50%; transform:translateY(-50%); z-index:20;"
                                    class="text-white bg-black/60 hover:bg-black/80 rounded-full p-4 shadow-lg gallery-button translate-button"
                                    aria-label="Next"
                                >&rsaquo;</button>

                                <!-- Image area -->
                                <div class="w-full h-full flex items-center justify-center select-none" style="padding:2rem 6rem;">
                                    <template x-if="photos.length > 0 && photos[activeIdx]">
                                        <img
                                            :src="`/storage/${photos[activeIdx].path}`"
                                            class="object-contain"
                                            alt="full"
                                            style="max-height:100%; max-width:100%;"
                                        />
                                    </template>
                                </div>
                            </div>
                        </div>
                    </template>
                    </x-custom-card>
                </div>
            @endforeach
            </div>
        </div>
        </div>
</x-layouts.app>

