<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TournamentRound extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'tournament_id',
        'game_id',
        'round_number',
        'rules'
    ];

    public function results() : hasMany
    {
        return $this->hasMany(TournamentRoundUser::class);
    }

    public function game() : belongsTo
    {
        return $this->belongsTo(Game::class);
    }
}
