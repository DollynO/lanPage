<?php

namespace App\Services\Spotify;

use App\Repositories\TokenRepository;
use Illuminate\Support\Facades\Http;

class SpotifyApiService
{
    private const API_SPOTIFY_URL = 'https://api.spotify.com/v1/';
    private const DEVICE_ID = '85da4547629dd0888b92e9a72e95a65713cb966e';

    public function __construct(
        private readonly TokenRepository $tokens
    )
    {
    }

    private function client()
    {
        return Http::withToken($this->tokens->getAccessToken())
            ->baseUrl(self::API_SPOTIFY_URL);
    }

    public function nextTrack()
    {
        return $this->client()->post('me/player/next');
    }

    public function previousTrack()
    {
        return $this->client()->post('me/player/previous');
    }

    public function pause()
    {
        return $this->client()->put('me/player/pause');
    }

    public function play()
    {
        return $this->client()
            ->put('me/player/play', [
//                "context_uri" => "spotify:album:5ht7ItJgpBH7W6vJ5BqpPr",
//                "offset" => [
//                    "position" => 5
//                ],
                "position_ms" => 0
            ]);
//        ->put('me/player/play?device_id=' . self::DEVICE_ID, [
//        "context_uri" => "spotify:album:5ht7ItJgpBH7W6vJ5BqpPr",
//        "offset" => [
//            "position" => 5
//        ],
//        "position_ms" => 200
//    ]);
    }

    public function getPlaybackState()
    {
        return $this->client()->get('me/player')->json();
    }

    public function getCurrentlyPlaying()
    {
        return $this->client()->get('me/player/currently-playing')->json();
    }
}
