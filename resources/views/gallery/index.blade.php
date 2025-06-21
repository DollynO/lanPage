<!-- resources/views/gallery/index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Gallery') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($parties as $party)
                <a href="{{ route('gallery.party',$party) }}" class="block bg-white shadow-sm rounded-lg overflow-hidden">
                    <img
                        src="{{ asset('storage/'.$party->photos->first()?->path ?? 'placeholder.png') }}"
                        class="w-full h-48 object-cover"
                        alt="{{ $party->name }}"
                    >
                    <div class="p-4">
                        <h3 class="font-semibold">{{ $party->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $party->photos_count ?? 0 }} photos</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</x-app-layout>
