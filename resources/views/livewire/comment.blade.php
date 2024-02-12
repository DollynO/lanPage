<div class="p-6 text-base bg-white rounded-lg dark:bg-gray-900">
    <footer class="flex justify-between items-center mb-2">
        <div>
            {{$comment['user']['name']}}
        </div>
        <div class="text-right">
            {{$comment['created_at_f']}}
        </div>
    </footer>
    <div class="text-gray-500 dark:text-gray-400">
        {{$comment['message']}}
    </div>
    <div class="flex items-center mt-4 space-x-4">
        <button type="button"
                class="flex items-center text-sm text-gray-500 hover:underline dark:text-gray-400 font-medium">
            <svg class="mr-1.5 w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 18">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5h5M5 8h2m6-3h2m-5 3h6m2-7H2a1 1 0 0 0-1 1v9a1 1 0 0 0 1 1h3v5l5-5h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1Z"/>
            </svg>
            Reply
        </button>
    </div>
    @if ($comment['comments'])
        @foreach($comment['comments'] as $subComment)
            @php($key = 'sub_comment_id_'.$subComment['id'])
            @livewire('comment', ['object' => $subComment], key($key))
            @if ($subComment != end($comment['comments']))
                <hr class="h-px bg-gray-200 border-0 dark:bg-gray-700">
            @endif
        @endforeach
    @endif
</div>
