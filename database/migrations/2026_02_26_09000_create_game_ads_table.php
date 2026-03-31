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
        Schema::create('game_ads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('price', 8, 2);
            $table->text('description');
            $table->enum('condition', ['NEW', 'USED', 'DIGITAL']);
            $table->enum('format', ['PHYSICAL', 'DIGITAL_KEY']);
            $table->json('platforms')->nullable();
            $table->string('digital_key')->nullable();
            $table->string('images')->nullable();
            $table->enum('status', ['ACTIVE', 'SOLD', 'HIDDEN'])->default('ACTIVE');
            $table->integer('quantity')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_ads');
    }
};
