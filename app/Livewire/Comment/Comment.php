<?php

namespace App\Livewire\Comment;

use Livewire\Component;

class Comment extends Component
{
    public $comment;

    protected $listeners = [
        // gets called from the comment reply component after the add comment.
        'refreshComments'
    ];

    public function mount($object){
        $this->comment = $object;
    }

    public function render()
    {
        return view('livewire.comment.comment');
    }

    // Refreshes the comment.
    public function refreshComments(){
        $this->comment = \App\Models\Comment::whereKey($this->comment['id'])
            ->first()->toArray();
    }
}
