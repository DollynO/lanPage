<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Game extends Model
{
    use HasFactory;

    protected $appends = [
        'rating'
    ];


    public function gameRatings(): hasMany
    {
        return $this->hasMany(GameRating::class);
    }

    public function gameVotes(): hasMany
    {
        return $this->hasMany(GameVote::class);
    }

    public function getRatingAttribute()
    {
        return round( $this->gameRatings->avg('rating'),0);
    }
}
