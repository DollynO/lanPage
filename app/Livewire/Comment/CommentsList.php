<?php

namespace App\Livewire\Comment;

use App\Models\Game;
use Livewire\Component;

class CommentsList extends Component
{
    public Game $record;
    public $comments;

    protected $listeners = ['commentAdded' => 'refreshComments'];

    public function mount(Game $record)
    {
        $this->record = $record;
        $this->loadComments();
    }

    public function loadComments()
    {
        $this->comments = $this->record->comments()
            ->with('user', 'comments')
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();
    }

    public function refreshComments()
    {
        $this->loadComments();
    }

    public function render()
    {
        return view('livewire.comment.comments-list');
    }
}
