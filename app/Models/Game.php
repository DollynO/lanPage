<?php

namespace App\Models;

use App\Traits\Commentable;
use App\Traits\Rateable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Game extends Model
{
    use HasFactory, Rateable, Commentable;

    protected $appends = [
        'rating',
        'comments'
    ];

    protected $fillable =[
        'id',
        'name',
        'note',
        'source',
        'player_count',
        'price',
        'already_played'
    ];

    public function delete()
    {
        $this->ratings()->delete();
        return parent::delete();
    }

    public function gameSuggestions(): HasMany
    {
        return $this->hasMany(GameSuggestion::class);
    }
}
