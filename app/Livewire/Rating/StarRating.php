<?php

namespace App\Livewire\Rating;

use App\Models\Rating;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class StarRating extends Component
{
    public $record;
    public int $maxRating = 5;

    public function render(): View
    {
        return view('livewire.rating.star-rating');
    }

    public function mount(mixed $record): void
    {
        $this->record = $record;
    }

    public function setUserRating(int $rating){
        $userRating = $this->record?->userRating()?->first();
        if ($userRating == null){
            $this->record->ratings()->create([
                'user_id' => Auth::id(),
                'rating' => $rating,
            ]);
        }else{
            $userRating->rating = $rating;
            $userRating->save();
        }

        $this->record->refresh();
        $this->dispatch('refresh');
    }
}
