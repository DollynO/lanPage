<?php

namespace App\Repositories;

class TokenRepository
{
    public function getAccessToken(): ?string
    {
        return cache('spotify_access_token');
    }

    public function getRefreshToken(): ?string
    {
        return cache('spotify_refresh_token');
    }

    public function saveTokens(array $data): void
    {
        if(isset($data['access_token']) && isset($data['expires_in']))
        {
            cache()->put('spotify_access_token', $data['access_token'], $data['expires_in']);
        }

        if(isset($data['refresh_token'])){
            cache()->put('spotify_refresh_token', $data['refresh_token']);
        }
    }

    public function deleteTokens(): void
    {
        cache()->forget('spotify_access_token');
        cache()->forget('spotify_refresh_token');
    }
}
