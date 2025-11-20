<?php

namespace App\Livewire\DashboardComponent;

use App\Actions\Spotify\CurrentlyPlaying;
use App\Actions\Spotify\Pause;
use App\Actions\Spotify\Play;
use App\Actions\Spotify\PreviousTrack;
use App\Actions\Spotify\SkipTrack;
use App\Services\Spotify\SpotifyAuthService;
use Livewire\Component;

class Spotify extends Component
{
    public bool $hasValidToken = false;
    public bool $isPlaying = false;
    public $playback = [];

    public function init()
    {
        $auth = app(SpotifyAuthService::class);
        $result = $auth->checkToken();

        if ($result) {
            $this->hasValidToken = true;

            $this->refreshPlayback();
        }
    }

    public function next(SkipTrack $action): \Illuminate\Http\JsonResponse
    {
        $previousTrackId = $this->playback['item']['id'];
        $action();

        $tries = 0;
        do {
            $this->refreshPlayback();
            $tries++;
            usleep(200_000);
        } while ($this->playback['item']['id'] === $previousTrackId && $tries < 5);

        \Filament\Notifications\Notification::make()
            ->title('Next Track')
            ->body($this->playback['item']['name'] ?? 'Unbekannt')
            ->success()
            ->send();

        return response()->json(['status' => 'ok']);
    }

    public function previous(PreviousTrack $action): \Illuminate\Http\JsonResponse
    {
        $previousTrackId = $this->playback['item']['id'];
        $action();

        $tries = 0;
        do {
            $this->refreshPlayback();
            $tries++;
            usleep(200_000); // 200ms
        } while ($this->playback['item']['id'] === $previousTrackId && $tries < 5);

        \Filament\Notifications\Notification::make()
            ->title('Next Track')
            ->body($this->playback['item']['name'] ?? 'Unbekannt')
            ->success()
            ->send();

        return response()->json(['status' => 'ok']);
    }

    public function playToggle(): \Illuminate\Http\JsonResponse
    {
        // Playback neu laden
        $this->refreshPlayback();

        if ($this->isPlaying) {
            $this->isPlaying = false;
            app(Pause::class)();
        } else {
            $this->isPlaying = true;
            app(Play::class)();
        }

        return response()->json(['status' => 'ok']);
    }


    public function refreshPlayback()
    {
        $stateService = app(CurrentlyPlaying::class);
        $this->playback = $stateService();
        $this->isPlaying = $this->playback['is_playing'] ?? false;
    }

    public function render()
{
    return view('livewire.dashboard-component.spotify');
}

}
