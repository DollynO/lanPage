<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameRating extends Model
{
    use HasFactory;

    public function games(){
        $this->belongsTo(Game::class);
    }
}
