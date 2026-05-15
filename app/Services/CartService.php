<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\ShippingStatus;
use App\Models\GameAd;
use App\Models\Order;
use App\Models\OrderItem;
use App\Notifications\OrderConfirmedNotification;
use Illuminate\Support\Facades\DB;

class CartService
{
    public function addItem(int $userId, int $gameAdId): Order
    {
        $ad = GameAd::findOrFail($gameAdId);

        return DB::transaction(function () use ($userId, $ad) {
            $order = Order::firstOrCreate(
                ['user_id' => $userId, 'status' => OrderStatus::PENDING],
                ['total_amount' => 0]
            );

            $sellerFee = $ad->price * 0.02;

            OrderItem::create([
                'order_id'        => $order->id,
                'game_ad_id'      => $ad->id,
                'unit_price'      => $ad->price,
                'seller_fee'      => $sellerFee,
                'net_income'      => $ad->price - $sellerFee,
                'shipping_status' => $ad->format === GameAd::FORMAT_DIGITAL_KEY
                    ? ShippingStatus::INSTANT
                    : ShippingStatus::PENDING,
            ]);

            $order->total_amount = $order->orderItems()->sum('unit_price');
            $order->save();

            return $order;
        });
    }

    public function removeItem(OrderItem $orderItem): void
    {
        DB::transaction(function () use ($orderItem) {
            $order = $orderItem->order;
            $orderItem->delete();

            $order->total_amount = $order->orderItems()->sum('unit_price');
            $order->save();
        });
    }

    public function checkout(Order $order, ?array $coupon): void
    {
        DB::transaction(function () use ($order, $coupon) {
            if ($coupon) {
                $order->total_amount = round($order->total_amount * (1 - $coupon['rate']), 2);
            }

            $order->status = OrderStatus::PAID;
            $order->save();

            foreach ($order->orderItems as $item) {
                $ad = $item->gameAd;
                $ad->decrement('quantity');
                if ($ad->fresh()->quantity <= 0) {
                    $ad->markAsSold();
                }
            }
        });

        // Notificación fuera de la transacción para no bloquearla si falla el envío
        $order->user->notify(new OrderConfirmedNotification($order));
    }
}
