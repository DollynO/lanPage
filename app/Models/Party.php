<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;

class Party extends Model
{
    use HasFactory;

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

    public function ratings() : MorphMany
    {
        return $this->morphMany(Rating::class, 'model');
    }

    public function userRating(): MorphMany|Builder
    {
        return $this->ratings()->whereHas('user', function($query){
            $query->where('id', Auth::id());
        });
    }

    public function getRatingAttribute() : int
    {
        return round( $this->ratings()->avg('rating'),0);
    }

    public function participants() : BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function delete()
    {
        $this->ratings()->delete();
        return parent::delete();
    }
}
