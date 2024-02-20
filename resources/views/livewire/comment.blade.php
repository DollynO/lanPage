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
        @php($key = rand(11111,99999))
        @livewire('comment-reply', ['id' => $comment['id'], 'objectName' => \App\Models\Comment::class, key($key)])
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
