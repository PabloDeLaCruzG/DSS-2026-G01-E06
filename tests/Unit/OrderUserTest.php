<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Order;

class OrderUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_order_pertenece_a_un_user(): void
    {
        // Creamos un usuario
        $user = User::factory()->create();

        // Creamos un pedido asociado a ese usuario
        $order = Order::factory()->create([
            'user_id' => $user->id,
        ]);

        // Comprobamos la relación Order -> User
        $this->assertEquals($user->id, $order->user->id);
    }
}
