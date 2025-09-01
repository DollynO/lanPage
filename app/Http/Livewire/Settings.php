<?php

namespace App\Http\Livewire;

use App\Models\Setting;
use Livewire\Component;

class Settings extends Component
{
    public $spotify;

    public function mount()
    {
        $setting = Setting::firstOrCreate(['key' => 'spotify'], ['value' => '']);
        $this->spotify = $setting->value;
    }

    public function save()
    {
        Setting::updateOrCreate(
            ['key' => 'spotify'],
            ['value' => $this->spotify]
        );

        session()->flash('message', 'Spotify-Setting wurde gespeichert!');
    }

    public function render()
    {
        return view('livewire.settings');
    }
}
