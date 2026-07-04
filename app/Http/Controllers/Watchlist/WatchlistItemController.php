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

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWatchlistItemRequest $request)
    {
        try {
            $item = $this->watchlistService->addItemToWatchlist([
                ...$request->validated(),
                'user_id' => $request->user()->id,
            ]);

        } catch (DuplicateWatchlistItemException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 409);
        }

        return response()->json([
            'data' => $item,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
