<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TmdbClient
{
    private string $baseUrl;
    private string $accessToken;

    public function __construct()
    {
        $this->baseUrl = config('services.tmdb.base_url');
        $this->accessToken = config('services.tmdb.access_token');
    }

    public function getMovie(int $id): array
    {
        return Http::withToken($this->accessToken)
            ->get("{$this->baseUrl}/movie/{$id}")
            ->json();
    }

    public function searchMovies(string $query): array
    {
        return Http::withToken($this->accessToken)
            ->get("{$this->baseUrl}/search/movie", [
                'query' => $query,
            ])
            ->json();
    }
}
