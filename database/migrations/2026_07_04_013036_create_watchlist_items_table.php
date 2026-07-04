<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\WatchlistStatus;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('watchlist_items', function (Blueprint $table) {
            $table->id();

            // owner
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // external movie reference
            $table->unsignedBigInteger('external_id');
            $table->string('title');
            $table->text('overview')->nullable();

            $table->date('release_date')->nullable();

            // enum stored as string
            $table->string('status')
                ->default(WatchlistStatus::TO_WATCH->value);

            // external API rating (TMDB for now)
            $table->float('tmdb_rating')->nullable();
            $table->string('poster_path')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'external_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('watchlist_items');
    }
};
