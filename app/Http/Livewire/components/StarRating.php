<?php

namespace App\Http\Livewire\components;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class StarRating extends Component
{
    public $object;
    public int $maxRating = 5;

    public function render() : Application|Factory|View
    {
        return view('livewire.components.star-rating');
    }

    public function setUserRating(int $rating){
        $userRating = $this->object->userRating()->first();
        if ($userRating == null){
            $this->object->ratings()->create([
                'user_id' => Auth::id(),
                'rating' => $rating,
            ]);
        }else{
            $userRating->rating = $rating;
            $userRating->save();
        }

        $this->object->refresh();
    }
}
