<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->date('release_date');
            $table->string('cover_image');
            $table->enum('genre', ['ACTION', 'RPG', 'SPORTS', 'STRATEGY', 'ADVENTURE']);
            $table->enum('platform', ['PC', 'PS5', 'XBOX', 'SWITCH']);
            $table->timestamps();
        });
    }
};
