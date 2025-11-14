<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Meal extends Model
{
    use HasFactory;

    protected $casts = [
        'isLunch' => 'boolean',
    ];

    public function party() : BelongsTo
    {
        return $this->belongsTo(Party::class);
    }

    public function chefs() : BelongsToMany
    {
        return $this->BelongsToMany(User::class);
    }

    public function recipe() : BelongsTo
    {
        return $this->BelongsTo(Recipe::class)->with(['ratings']);
    }

    public function delete(): ?bool
    {
        return parent::delete();
    }
}
