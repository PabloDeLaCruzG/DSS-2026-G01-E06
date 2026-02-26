<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            $table->float('unit_price');
            $table->float('seller_fee');
            $table->float('net_income');

            $table->enum('shipping_status', [
                'PENDING',
                'SHIPPED',
                'DELIVERED',
                'INSTANT'
            ]);

            $table->string('tracking_number')->nullable();

            // Relaciones UML
            $table->foreignId('order_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->foreignId('game_ad_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
