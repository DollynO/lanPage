<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @method static find($id)
 */
class GameSuggestion extends Model
{
    use HasFactory;

    public function party() : BelongsTo
    {
        return $this->belongsTo(Party::class);
    }

    public function game() : BelongsTo
    {
        return $this->BelongsTo(Game::class);
    }

    public function user() : BelongsTo
    {
        return $this->BelongsTo(User::class);
    }

    public function delete(): ?bool
    {
        return parent::delete();
    }
}
