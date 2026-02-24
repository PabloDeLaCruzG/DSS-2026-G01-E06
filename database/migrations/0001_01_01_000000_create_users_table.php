<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            $table->enum('role', ['admin', 'user'])->default('user');

            // Department del Admin
            $table->string('department')->nullable(); 

            $table->decimal('wallet_balance', 10, 2)->default(0.00);
            $table->float('reputation')->default(5.0);
            $table->boolean('is_banned')->default(false);

            $table->rememberToken();
            $table->timestamps(); // Añade created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
