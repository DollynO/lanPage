<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TournamentRoundUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'tournament_round_id',
        'user_id',
        'points',
        'has_won',
    ];

    public function user() : belongsTo
    {
        return $this->belongsTo(User::class);
    }
}
