{{-- resources/views/admin/photos/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manage Photos</h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
        @if(session('status'))
            <div class="bg-green-100 text-green-800 px-3 py-2 rounded text-sm">{{ session('status') }}</div>
        @endif

        <form method="GET" class="flex flex-wrap gap-3 items-end">
            <div>
                <label class="block text-sm text-gray-600 mb-1">Party</label>
                <select name="party_id" class="border rounded px-2 py-1">
                    <option value="">All</option>
                    @foreach($parties as $p)
                        <option value="{{ $p->id }}" @selected(request('party_id')==$p->id)">
                        {{ \Carbon\Carbon::parse($p->start_date)->format('Y') }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" class="border rounded px-2 py-1" placeholder="file path or uploader">
            </div>
            <button class="px-3 py-2 bg-gray-800 text-white rounded">Filter</button>
        </form>

        <form method="POST" action="{{ route('admin.photos.bulk') }}" id="bulkForm">
            @csrf
            <div class="flex items-center justify-between mb-2">
                <button type="submit"
                        id="bulkDeleteBtn"
                        class="px-3 py-2 bg-red-600 text-white rounded disabled:opacity-50"
                        onclick="return confirm('Delete selected photos?')"
                        disabled>
                    Delete selected
                </button>
            </div>
        </form>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($photos as $photo)
                    <div class="bg-white rounded shadow-sm overflow-hidden">
                        <img src="{{ asset('storage/'.$photo->path) }}" class="w-full h-40 object-cover" alt="">
                        <div class="p-3 text-sm space-y-2">
                            <div class="flex items-center justify-between">
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" name="ids[]" value="{{ $photo->id }}" class="select-photo h-4 w-4">
                                    <span class="text-gray-600 truncate max-w-[14rem]">{{ $photo->path }}</span>
                                </label>
                                <div class="text-gray-500">by {{ $photo->user->name }}</div>
                            </div>

                            <form method="POST" action="{{ route('admin.photos.update', $photo) }}" class="flex items-center gap-2">
                                @csrf @method('PATCH')
                                <label class="text-gray-600">Party:</label>
                                <select name="party_id" class="border rounded px-2 py-1">
                                    @foreach($parties as $p)
                                        <option value="{{ $p->id }}" @selected($photo->party_id==$p->id)">
                                        {{ \Carbon\Carbon::parse($p->start_date)->format('Y') }}
                                        </option>
                                    @endforeach
                                </select>
                                <button class="px-2 py-1 bg-indigo-600 text-white rounded">Save</button>
                            </form>
                            <form method="POST" action="{{ route('admin.photos.destroy', $photo) }}">
                                @csrf @method('DELETE')
                                <button class="px-2 py-1 bg-red-600 text-white rounded"
                                        onclick="return confirm('Delete this photo?')">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </form>

        <div>{{ $photos->links() }}</div>
    </div>

    <script>
        // enable/disable bulk button
        document.addEventListener('DOMContentLoaded', () => {
            const checks = document.querySelectorAll('.select-photo');
            const btn = document.getElementById('bulkDeleteBtn');
            function update() {
                btn.disabled = !Array.from(checks).some(c => c.checked);
            }
            checks.forEach(c => c.addEventListener('change', update));
            update();
        });
    </script>
</x-app-layout>
