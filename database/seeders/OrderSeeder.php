<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Game;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\GameAd;
use App\Enums\OrderStatus;
use App\Enums\ShippingStatus;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        // Crear 12 órdenes (al menos 10 como se pidió)
        for ($i = 0; $i < 12; $i++) {
            // Crear un comprador (usuario que hace el pedido)
            $buyer = User::factory()->create();

            // Crear la orden
            $order = Order::create([
                'total_amount' => 0.0, // Se calculará después
                'status' => fake()->randomElement([
                    OrderStatus::PENDING,
                    OrderStatus::PAID,
                    OrderStatus::COMPLETED,
                    OrderStatus::REFUNDED
                ]),
                'user_id' => $buyer->id,
            ]);

            // Crear entre 1 y 3 OrderItems por orden
            $itemCount = fake()->numberBetween(1, 3);
            $totalAmount = 0.0;

            for ($j = 0; $j < $itemCount; $j++) {
                // Crear un GameAd (con vendedor diferente) sobre un juego real
                $gameAd = GameAd::factory()->create([
                    'game_id' => Game::inRandomOrder()->value('id'),
                ]);

                $unitPrice = $gameAd->price;
                $sellerFee = round($unitPrice * 0.1, 2); // 10% de comisión
                $netIncome = round($unitPrice - $sellerFee, 2);

                OrderItem::create([
                    'unit_price' => $unitPrice,
                    'seller_fee' => $sellerFee,
                    'net_income' => $netIncome,
                    'shipping_status' => fake()->randomElement([
                        ShippingStatus::PENDING,
                        ShippingStatus::SHIPPED,
                        ShippingStatus::DELIVERED,
                        ShippingStatus::INSTANT
                    ]),
                    'tracking_number' => fake()->boolean(70) ? fake()->uuid() : null,
                    'order_id' => $order->id,
                    'game_ad_id' => $gameAd->id,
                ]);

                $totalAmount += $unitPrice;
            }

            // Actualizar el total de la orden
            $order->update(['total_amount' => $totalAmount]);
        }
    }
}
