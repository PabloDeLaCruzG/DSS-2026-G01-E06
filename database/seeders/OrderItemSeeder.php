<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrderItem;
use App\Models\Order;
use App\Models\GameAd;
use App\Enums\ShippingStatus;

class OrderItemSeeder extends Seeder
{
    public function run(): void
    {
        // Obtenemos un pedido existente
        $order = Order::first();

        // Creamos un GameAd (factory ya existe)
        $gameAd = GameAd::factory()->create();

        // Creamos el OrderItem
        OrderItem::create([
            'unit_price' => 50.0,
            'seller_fee' => 5.0,
            'net_income' => 45.0,
            'shipping_status' => ShippingStatus::PENDING,
            'tracking_number' => null,
            'order_id' => $order->id,
            'game_ad_id' => $gameAd->id,
        ]);
    }
}
