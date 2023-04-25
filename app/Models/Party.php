<?php

namespace App\Models;

use App\Traits\Rateable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Party extends Model
{
    use HasFactory, Rateable;

    protected $appends = [
        'rating'
    ];

    protected $fillable =[
        'id',
        'max_participants',
        'location',
        'start_date',
        'end_date',
    ];

    public function participants() : BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot(['start_day','end_day']);
    }

    public function meals() : HasMany
    {
        return $this->hasMany(Meal::class)->with('recipe')->orderBy('date');
    }

    public function delete()
    {
        $this->ratings()->delete();
        return parent::delete();
    }
}
