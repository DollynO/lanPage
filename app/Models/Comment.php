<?php

namespace App\Models;

use App\Traits\Commentable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model{

    use Commentable;

    protected $fillable = [
        'user_id',
        'message',
    ];

    protected $appends = [
        'user',
        'comments',
        'created_at_f',
        'updated_at_f',
    ];

    public function model(){
        return $this->morphTo();
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function getUserAttribute()
    {
        return $this->user()->first();
    }

    public function getCreatedAtFAttribute()
    {
        return Carbon::parse($this->created_at)->format('d.m.y H:i');
    }

    public function getUpdatedAtFAttribute()
    {
        return Carbon::parse($this->updated_at)->format('d.m.y H:i');
    }
}
