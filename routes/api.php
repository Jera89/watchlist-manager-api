<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Watchlist\WatchlistItemController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    Route::post('/watchlist-items', [WatchlistItemController::class, 'store']);
});
