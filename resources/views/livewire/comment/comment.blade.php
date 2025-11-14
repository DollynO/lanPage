<x-filament::section
    class="bg-white dark:bg-gray-900 rounded-xl space-y-4 text-sm text-gray-700 dark:text-gray-300 mt-2"
>
    {{-- Kommentar Header --}}
    <div class="flex items-center justify-between">
        <div class="font-medium text-gray-900 dark:text-white">
            {{ $comment['user']['name'] }}
        </div>
        <div class="text-xs text-gray-500 dark:text-gray-400">
            {{ $comment['created_at_f'] }}
        </div>
    </div>

    {{-- Kommentar Inhalt --}}
    <div class="text-gray-700 dark:text-gray-300">
        {{ $comment['message'] }}
    </div>

    {{-- Antwort-Funktion --}}

        <livewire:comment.comment-reply
            :id="$comment['id']"
            :objectName="\App\Models\Comment::class"
            :key="'comment-reply-'.$comment['id']"
        />



    {{-- Sub-Kommentare --}}
    @if (!empty($comment['comments']))
        <div class="border-gray-200 dark:border-gray-700 pl-4 ">
            @foreach ($comment['comments'] as $subComment)
                <livewire:comment.comment
                    :object="$subComment"
                    :key="'sub_comment_id_'.$subComment['id']"
                />
                @if (!$loop->last)
                    <hr class="bg-gray-200 border-0 dark:bg-gray-700">
                @endif
            @endforeach
        </div>
    @endif
</x-filament::section>
