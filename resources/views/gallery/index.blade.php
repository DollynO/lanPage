<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gallery</h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto space-y-8">
        @foreach($parties as $party)
            <div
                x-data="{
                    showModal: false,
                    photos: @js($party->previewPhotos->values()),
                    activeIdx: 0,
                    open(idx){ this.activeIdx = idx; this.showModal = true }
                  }"
                class="bg-white shadow-md rounded-lg overflow-hidden @if(!$loop->first) mt-2 @endif"
            >
                <div class="flex items-center justify-between px-6 py-3 border-b">
                    <div class="flex items-baseline gap-3">
                        <div class="text-lg font-semibold">{{ $party->name }}</div>
                    </div>
                </div>

                <div class="px-6 py-4">
                    <div class="flex gap-1" style="width: calc(9 * 160px); overflow: hidden;">
                        @foreach($party->previewPhotos as $photo)
                            <div class="flex-shrink-0 relative" style="width:150px; height:100px;">
                                <button
                                    @click="open({{ $loop->index }})"
                                    class="border border-dashed border-gray-300 w-full h-full rounded overflow-hidden shadow-sm hover:shadow-lg transition"
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
                               class="w-full h-full flex flex-col items-center justify-center rounded border border-dashed border-gray-300 text-xs text-gray-700 font-semibold hover:bg-gray-50 transition">
                                <div class="mb-1">+{{ max(0, $party->photos_count - count($party->previewPhotos)) }} more</div>
                                <div class="underline">Full gallery</div>
                            </a>
                        </div>
                    </div>
                </div>

                <div
                    x-show="showModal"
                    x-cloak
                    x-transition
                    @keydown.window.escape="showModal=false"
                    class="fixed inset-0 bg-black/75 flex items-center justify-center z-50 px-4"
                >
                    <div class="relative max-w-4xl w-full" @click.self="showModal=false">
                        <button @click="showModal=false"
                                class="absolute top-3 right-3 text-white bg-black/50 hover:bg-black/70 rounded-full p-3 shadow-lg"
                                aria-label="Close">&times;</button>

                        <button
                            @click="activeIdx = (activeIdx - 1 + photos.length) % photos.length"
                            class="absolute left-3 top-1/2 transform -translate-y-1/2 text-white bg-black/50 hover:bg-black/70 rounded-full p-4 shadow-lg"
                            aria-label="Previous"
                        >&lsaquo;</button>

                        <div class="flex justify-center">
                            <img
                                :src="`/storage/${photos[activeIdx].path}`"
                                class="object-contain"
                                alt="full"
                                @click.stop
                                style="max-height: calc(100vh - 80px); max-width: calc(100vw - 180px);"
                            />
                        </div>

                        <button
                            @click="activeIdx = (activeIdx + 1) % photos.length"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-white bg-black/50 hover:bg-black/70 rounded-full p-4 shadow-lg"
                            aria-label="Next"
                        >&rsaquo;</button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

</x-app-layout>
