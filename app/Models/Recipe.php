<?php

namespace App\Models;

use App\Traits\Rateable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Recipe extends Model
{
    use HasFactory, Rateable;

    protected $appends = [
        'rating'
    ];

    public function recipe(): BelongsToMany
    {
        return $this->belongsToMany(Meal::class);
    }

    public function delete()
    {
        $this->ratings()->delete();
        return parent::delete();
    }
}
