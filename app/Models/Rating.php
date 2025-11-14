<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\HasCompositePrimaryKey;

class Rating extends Model{
    use HasCompositePrimaryKey;

    protected $primaryKey = ['user_id', 'model_type', 'model_id'];

    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'rating',
    ];

    protected $appends = [
        'user'
    ];

    public function model(){
        return $this->morphTo();
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function getUserAttribute()
    {
        return $this->user()->get();
    }
}
