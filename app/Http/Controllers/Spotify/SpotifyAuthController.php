<?php

namespace App\Http\Controllers\Spotify;

use App\Services\Spotify\SpotifyAuthService;
use App\Repositories\TokenRepository;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;

class SpotifyAuthController
{
    public function __construct(
        private SpotifyAuthService $auth,
        private TokenRepository $tokens
    ) {}

    public function redirect()
    {
        return redirect()->away($this->auth->getAuthUrl());
    }

    public function callback(Request $request)
    {
        if (!$request->has('code')) {
            Notification::make()
                ->title('No authorization code provided.')
                ->body('Please connect your Spotify account.')
                ->danger()
                ->send();

            return redirect()->route('settings');
        }

        if(!$request->has('state')) {
            Notification::make()
                ->title('Authorization state missing.')
                ->danger()
                ->send();

            return redirect()->route('settings');
        }

        $stateExpected = session('spotify_oauth_state');
        if($request->get('state') != $stateExpected)
        {
            Notification::make()
                ->title('Unknown authorization state.')
                ->danger()
                ->send();

            return redirect()->route('settings');
        }

        $data = $this->auth->requestAccessToken($request->get('code'));

        $this->tokens->saveTokens($data);

        Notification::make()
            ->title('Spotify connected!')
            ->body('Spotify authenticated successfully. Token expires in ' . $data['expires_in'] . ' seconds.')
            ->success()
            ->send();

        return redirect()->route('settings');
    }
}
