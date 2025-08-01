<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gallery</h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto">
        @foreach($parties as $party)
            <div class="bg-white shadow-md rounded-lg overflow-hidden @if(! $loop->first) mt-2 @endif">
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
                                    @click="/* per-party Alpine modal logic */"
                                    class="border border-dashed border-gray-300 w-full h-full rounded overflow-hidden shadow-sm hover:shadow-lg transition"
                                    style="background:#f5f5f7;"
                                >
                                    <img loading="lazy" src="{{ asset('storage/'.$photo->path) }}"
                                         class="w-full h-full object-cover"
                                         alt="thumb">
                                </button>
                            </div>
                        @endforeach

                        <!-- fixed 9th tile -->
                        <div class="flex-shrink-0" style="width:160px; height:100px;">
                            <a href="{{ route('gallery.party', $party) }}"
                               class="w-full h-full flex flex-col items-center justify-center rounded border border-dashed border-gray-300 text-xs text-gray-700 font-semibold hover:bg-gray-50 transition">
                                <div class="mb-1">+{{ max(0, $party->photos_count - count($party->previewPhotos)) }} more</div>
                                <div class="underline">Full gallery</div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</x-app-layout>
