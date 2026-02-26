<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Order;
use App\Models\OrderItem;

class OrderItemOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_order_item_pertenece_a_un_order(): void
    {
        // Creamos un pedido
        $order = Order::factory()->create();

        // Creamos un OrderItem asociado al pedido
        $orderItem = OrderItem::factory()->create([
            'order_id' => $order->id,
        ]);

        // Comprobamos la relación OrderItem -> Order
        $this->assertEquals($order->id, $orderItem->order->id);
    }
}