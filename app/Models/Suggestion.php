<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suggestion extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'game_id'];

    public function votes() {
        return Suggestion::query()->where('game_id', $this->game_id)->get();
    }

    public function userVote($userId)
    {
        return Suggestion::query()
            ->where('game_id', $this->game_id)
            ->where('user_id', $userId)
            ->get()->count() > 0;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
