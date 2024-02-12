<?php

namespace App\Traits;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;

Trait Commentable
{
    public function comments() : MorphMany
    {
        return $this->morphMany(Comment::class, 'model');
    }

    public function userComments(): MorphMany|Builder
    {
        return $this->comments()->whereHas('user', function($query){
            $query->where('id', Auth::id());
        });
    }

    public function getCommentsAttribute(): Collection
    {
        return $this->comments()->get();
    }
}
