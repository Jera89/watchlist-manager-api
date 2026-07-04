<?php

namespace App\Http\Controllers\Watchlist;

use App\Exceptions\DuplicateWatchlistItemException;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWatchlistItemRequest;
use App\Services\WatchlistService;
use Illuminate\Http\Request;

class WatchlistItemController extends Controller
{
    public function __construct(
        private WatchlistService $watchlistService
    ) {}

    public function index()
    {
        //
    }

    public function store(StoreWatchlistItemRequest $request)
    {
        $item = $this->watchlistService->addItemToWatchlist([
            ...$request->validated(),
            'user_id' => $request->user()->id,
        ]);

        return response()->json([
            'data' => $item,
        ], 201);
    }

    public function show(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
