<!-- resources/views/gallery/party.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $party->name }} Gallery
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
                <form method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="photos[]" multiple required class="block mb-4">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Upload</button>
                </form>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @foreach($photos as $photo)
                    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                        <img src="{{ asset('storage/' . $photo->path) }}"
                             class="w-full h-48 object-cover"
                             alt="Photo by {{ $photo->user->name }}">
                        <div class="p-4 text-sm text-gray-600">
                            By {{ $photo->user->name }} · {{ $photo->created_at->diffForHumans() }}
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $photos->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
