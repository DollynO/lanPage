<?php

namespace App\Models;

use App\Traits\Rateable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Recipe extends Model
{
    use HasFactory, Rateable;

    protected $appends = [
        'rating'
    ];

    public function meals(): HasMany
    {
        return $this->hasMany(Meal::class);
    }

    public function delete()
    {
        $this->ratings()->delete();
        return parent::delete();
    }
}
