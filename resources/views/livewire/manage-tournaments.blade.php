<div class="flex flex-row">
    <div class="flex-1">
    <table class="table-auto w-2/3 mr-4">
        <thead>
        <tr>
            <th class="px-4 py-2">Name</th>
            <th class="px-4 py-2">Date</th>
            <th class="px-4 py-2">Location</th>
            <th class="px-4 py-2">Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($tournaments as $tournament)
            <tr
                wire:click="selectTournament({{ $tournament->id }})"
                @class(['bg-gray-200' => $selectedTournament === $tournament->id])
            >
                <td class="border px-4 py-2">{{ $tournament->name }}</td>
                <td class="border px-4 py-2">{{ $tournament->date }}</td>
                <td class="border px-4 py-2">{{ $tournament->location }}</td>
                <td class="border px-4 py-2">
                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" wire:click="selectTournament({{ $tournament->id }})">Select</button>
                    <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" wire:click="deleteTournament({{ $tournament->id }})">Delete</button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <form class="my-4 w-2/3" wire:submit.prevent="createTournament">
        <div class="flex items-center mb-4">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit">Create new Tournament</button>
        </div>
    </form>
    </div>
    <div class="flex-1 ml-4">
    @if ($selectedTournament)
            <div class="mb-4">
                <h2 class="text-lg font-bold">{{ $selectedTournament->name }}</h2>
                <p class="text-gray-700">{{ $selectedTournament->location }} - {{ $selectedTournament->date }}</p>
            </div>
            <div class="flex mb-4">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2" wire:click="toggleSuggestionsClosed">{{ $selectedTournament->are_suggestions_closed ? 'Open Suggestions' : 'Close Suggestions' }}</button>
                <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded" wire:click="toggleCompleted">{{ $selectedTournament->is_completed ? 'Mark as Incomplete' : 'Mark as Complete' }}</button>
            </div>
            <form wire:submit.prevent="updateTournament">
                <div class="mb-4">
                    <label class="mr-4" for="name">Name:</label>
                    <input class="border rounded w-full py-2 px-3" type="text" wire:model="selectedTournament.name">
                </div>
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit">Save</button>
            </form>
    @endif
    </div>
</div>
