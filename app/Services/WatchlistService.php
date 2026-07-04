<?php

namespace App\Services;

use App\Exceptions\DuplicateWatchlistItemException;
use App\Models\WatchlistItem;
use Illuminate\Database\QueryException;

class WatchlistService
{
    public function addItemToWatchlist(array $data): WatchlistItem
    {
        try {
            return WatchlistItem::create([
                'user_id'      => $data['user_id'],
                'external_id'  => $data['external_id'],
                'title'        => $data['title'],
                'overview'     => $data['overview'] ?? null,
                'release_date' => $data['release_date'] ?? null,
                'status'       => \App\Enums\WatchlistStatus::TO_WATCH,
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
