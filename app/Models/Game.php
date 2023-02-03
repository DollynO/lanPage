<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;

class Game extends Model
{
    use HasFactory;

    protected $appends = [
        'rating'
    ];

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
