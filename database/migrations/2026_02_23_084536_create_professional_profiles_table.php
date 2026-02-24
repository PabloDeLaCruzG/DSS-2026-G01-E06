<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Fichero: ..._create_professional_profiles_table.php
    public function up(): void
    {
        Schema::create('professional_profiles', function (Blueprint $table) {
            $table->id();
            // Relación 1 a 1 con users. Si se borra el user, se borra el perfil.
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');

            $table->string('cif')->unique();
            $table->string('company_name');
            $table->string('website')->nullable();
            $table->string('verification_docs'); // URL al documento
            $table->boolean('is_verified')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('professional_profiles');
    }
};
