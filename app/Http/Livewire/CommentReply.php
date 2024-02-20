<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CommentReply extends Component
{
    // opens the reply input field.
    public bool $openReply;

    // the reply message.
    public string $reply;

    // The id of the object to save the comment to.
    public int $modelId;

    // The name of the object for the polymorph table.
    public string $modelType;

    public function mount($id, $objectName){
            $this->modelId = $id;
            $this->modelType = $objectName;
    }

    public function render()
    {
        return view('livewire.comment-reply');
    }

    // Adds a comment to the object .
    // Calls event refreshComments to notify the object to refresh its comments.
    public function addReply()
    {
        $comment = \App\Models\Comment::create([
            'user_id' => Auth::id(),
            'model_id' => $this->modelId,
            'model_type' => $this->modelType,
            'message' => $this->reply,
        ]);
        $comment->save();

        $this->emit('refreshComments');
        $this->openReply = false;
        $this->reply = '';
    }
}
