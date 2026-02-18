<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('users', function (Blueprint $table) {
        $table->id(); // id: Long (autoincremental)
        $table->string('name'); // name: String
        $table->string('email')->unique(); // email: String (único)
        $table->string('password'); // password: String
        $table->enum('role', ['admin', 'user'])->default('user'); // role: enum
        $table->float('wallet_balance')->default(0); // wallet_balance: Float
        $table->float('reputation')->default(0); // reputation: Float
        $table->boolean('is_banned')->default(false); // is_banned: Boolean
        $table->dateTime('created_at'); // created_at: DateTime
        $table->boolean('isAdmin')->default(false); // isAdmin(): Boolean
        $table->boolean('isProfessional')->default(false); // isProfessional(): Boolean
        $table->boolean('canSell')->default(true); // canSell(): Boolean
        $table->timestamps(); // created_at y updated_at automáticos
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
