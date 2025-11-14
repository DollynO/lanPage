<div>
    <livewire:comment.comment-reply :id="$record->id" :objectName="get_class($record)" wire:key="comment-reply-{{$record->id}}" />

    @if ($comments)
        <div class="">
            @foreach ($comments as $comment)
                <livewire:comment.comment :object="$comment" :key="'comment_id_'.$comment['id']" />
                @if (!$loop->last)
                    <hr class="bg-gray-400 border-0 dark:bg-gray-700" />
                @endif
            @endforeach
        </div>
    @endif
</div>
