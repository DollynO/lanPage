<?php

namespace App\Services\Spotify;

use App\Models\Setting;
use App\Repositories\TokenRepository;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class SpotifyAuthService
{
    public function getAuthUrl(): string
    {
        $spotifyRedirectUri = url('/spotify/callback');

        $state = bin2hex(random_bytes(16));
        session(['spotify_oauth_state' => $state]);

        $clientId = Setting::where('key', 'spotify_client_id')->value('value');

        $params = http_build_query([
            'client_id' => $clientId,
            'response_type' => 'code',
            'redirect_uri' => $spotifyRedirectUri,
            'state' => $state,
            'scope' => 'user-modify-playback-state user-read-playback-state'
        ]);

        return "https://accounts.spotify.com/authorize?" . $params;
    }

    /**
     * @throws ConnectionException
     */
    public function requestAccessToken(string $token, bool $isRefreshToken = false): array
    {
        $clientId = Setting::where('key', 'spotify_client_id')->value('value');
        $clientSecret = Setting::where('key', 'spotify_client_secret')->value('value');

        $spotifyRedirectUri = url('/spotify/callback');

        $payload = $isRefreshToken
            ? [
                'grant_type' => 'refresh_token',
                'refresh_token' => $token,
            ]
            : [
                'grant_type' => 'authorization_code',
                'code' => $token,
                'redirect_uri' => $spotifyRedirectUri,
            ];

        $response = Http::asForm()
            ->withBasicAuth($clientId, $clientSecret)
            ->post('https://accounts.spotify.com/api/token', $payload);

        return $response->json();
    }

    public function checkClientCredentials(): ?string
    {
        $clientId = Setting::where('key', 'spotify_client_id')->value('value');
        $clientSecret = Setting::where('key', 'spotify_client_secret')->value('value');

        if (empty($clientId) || empty($clientSecret)) {
            return null;
        }

        $response = Http::asForm()
            ->withBasicAuth($clientId, $clientSecret)
            ->post('https://accounts.spotify.com/api/token', [
                'grant_type' => 'client_credentials',
            ]);

        if ($response->successful() && isset($response['access_token'])) {
            return $response['access_token'];
        }

        return null;
    }

    public function checkToken()
    {
        $tokens = app(TokenRepository::class);
        $token = $tokens->getAccessToken();

        if ($token) {
            $response = Http::withToken($token)
                ->get('https://api.spotify.com/v1/me');

            if ($response->ok()) {
                return true;
            }
        }

        if (!$this->checkRefreshToken()) {
            return false;
        }

        return true;
    }

    public function checkRefreshToken()
    {
        $tokens = app(TokenRepository::class);
        $refreshToken = $tokens->getRefreshToken();

        if (!$refreshToken) {
            return false;
        }

        $data = $this->requestAccessToken($refreshToken, true);

        if (!isset($data['access_token'])) {
            return false;
        }

        $tokens->saveTokens($data);
        return true;
    }
}
