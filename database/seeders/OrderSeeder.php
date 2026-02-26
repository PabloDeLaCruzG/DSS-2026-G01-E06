<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\User;
use App\Enums\OrderStatus;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        // Creamos un usuario
        $user = User::factory()->create();

        // Creamos un pedido asociado al usuario
        Order::create([
            'total_amount' => 100.0,
            'status' => OrderStatus::PENDING,
            'user_id' => $user->id,
        ]);
    }
}
