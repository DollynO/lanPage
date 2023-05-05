<?php

namespace App\Traits;

use App\Models\Rating;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;

Trait Rateable
{
    public function ratings() : MorphMany
    {
        return $this->morphMany(Rating::class, 'model');
    }

    public function userRating(): MorphMany|Builder
    {
        return $this->ratings()->whereHas('user', function($query){
            $query->where('id', Auth::id());
        });
    }

    public function getRatingAttribute() : int
    {
        return round( $this->ratings()->avg('rating'),0);
    }
}
