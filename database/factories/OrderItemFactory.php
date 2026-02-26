<?php

namespace Database\Factories;

use App\Models\OrderItem;
use App\Models\Order;
use App\Models\GameAd;
use App\Enums\ShippingStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition(): array
    {
        return [
            'unit_price' => 50.0,
            'seller_fee' => 5.0,
            'net_income' => 45.0,
            'shipping_status' => ShippingStatus::PENDING,
            'tracking_number' => null,
            'order_id' => Order::factory(),
            'game_ad_id' => GameAd::factory(),
        ];
    }
}
