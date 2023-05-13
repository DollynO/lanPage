<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tournament extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'party_id',
        'are_suggestions_closed',
        'is_completed',
        'amount_rounds',
        'amount_game_votes',
    ];

    public function rounds() : HasMany {
        return $this->hasMany(TournamentRound::class);
    }
}
