<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WatchlistItem extends Model
{
    protected $fillable = [
        'user_id',
        'external_id',
        'title',
        'overview',
        'release_date',
        'status',
        'tmdb_rating',
        'poster_path',
    ];

    protected $casts = [
        'status' => \App\Enums\WatchlistStatus::class,
        'release_date' => 'date',
        'tmdb_rating' => 'float',
    ];
}
