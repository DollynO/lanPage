<?php

namespace App\Livewire;

use App\Models\Setting;
use App\Repositories\TokenRepository;
use App\Services\Spotify\SpotifyAuthService;
use Filament\Notifications\Notification;
use Livewire\Component;

class Settings extends Component
{
    public $spotify_client_id;
    public $spotify_client_secret;

    public $hasValidToken = false;

    public function mount()
    {
        $this->spotify_client_id = Setting::firstOrCreate(
            ['key' => 'spotify_client_id'],
            ['value' => '']
        )->value;

        $this->spotify_client_secret = Setting::firstOrCreate(
            ['key' => 'spotify_client_secret'],
            ['value' => '']
        )->value;

        $auth = app(SpotifyAuthService::class);
        $result = $auth->checkToken();

        if ($result) {
            $this->hasValidToken = true;
        }
    }

    public function saveKeys()
    {
        Setting::updateOrCreate(
            ['key' => 'spotify_client_id'],
            ['value' => $this->spotify_client_id]
        );

        Setting::updateOrCreate(
            ['key' => 'spotify_client_secret'],
            ['value' => $this->spotify_client_secret]
        );


        $tokens = app(TokenRepository::class);
        $tokens->deleteTokens();

        $this->hasValidToken = false;

        Notification::make()
            ->title('Spotify Keys gespeichert')
            ->body('Die Client ID und Client Secret wurden gespeichert.')
            ->success()
            ->send();
    }

    public function connectSpotify(SpotifyAuthService $spotifyAuthService)
    {
        $token = $spotifyAuthService->checkClientCredentials();

        if ($token) {
            return redirect()->to('/spotify/auth');
        }

        Notification::make()
            ->title('Spotify Keys invalid')
            ->body('Die Spotify Client ID oder Client Secret fehlen oder sind ungültig.')
            ->danger()
            ->send();

        return false;
    }

    public function disconnectSpotify()
    {
        $tokens = app(TokenRepository::class);
        $tokens->deleteTokens();

        \Filament\Notifications\Notification::make()
            ->title('Spotify disconnected')
            ->body('You have successfully disconnected your Spotify account.')
            ->warning()
            ->send();

        $this->hasValidToken = false;
    }

    public function render()
    {
        return view('livewire.settings');
    }
}
