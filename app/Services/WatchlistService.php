<?php

namespace App\Services;

use App\Exceptions\DuplicateWatchlistItemException;
use App\Models\WatchlistItem;
use App\Enums\WatchlistStatus;
use Illuminate\Database\QueryException;

class WatchlistService
{
    public function __construct(
        private TmdbClient $tmdbClient
    ) {}

    public function addItemToWatchlist(array $data): WatchlistItem
    {
        // required: fetch canonical movie data by ID
        $movie = $this->tmdbClient->getMovie($data['external_id']);

        try {
            return WatchlistItem::create([
                'user_id'      => $data['user_id'],
                'external_id'  => $movie['id'],
                'title'        => $movie['title'],
                'overview'     => $movie['overview'] ?? null,
                'release_date' => $movie['release_date'] ?? null,
                'rating'       => $movie['vote_average'] ?? null,
                'poster_path'  => $movie['poster_path'] ?? null,
                'status'       => WatchlistStatus::TO_WATCH,
            ]);
        } catch (QueryException $e) {
            if (
                $e->getCode() === '23000'
                && (($e->errorInfo[1] ?? null) === 1062)
            ) {
                throw new DuplicateWatchlistItemException(
                    'Item already exists in watchlist.',
                    previous: $e
                );
            }

            throw $e;
        }
    }
}
