<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('auction_bids', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_ad_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 8, 2);
            $table->dateTime('bid_time');
            $table->timestamps();
        });

        Schema::create('auction_bid_user', function (Blueprint $table) {
            $table->foreignId('auction_bid_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->primary(['auction_bid_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auction_bid_user');
        Schema::dropIfExists('auction_bids');
    }
};
