<?php

namespace App\Actions\Spotify;

use App\Services\Spotify\SpotifyApiService;

class Pause
{
    public function __construct(private SpotifyApiService $api)
    {
    }

    public function __invoke()
    {
        return $this->api->pause();
    }
}
