<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\GameAd;
use App\Models\OrderItem;

class OrderItemGameAdTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_order_item_pertenece_a_un_game_ad(): void
    {
        // Creamos un GameAd
        $gameAd = GameAd::factory()->create();

        // Creamos un OrderItem asociado al GameAd
        $orderItem = OrderItem::factory()->create([
            'game_ad_id' => $gameAd->id,
        ]);

        // Comprobamos la relación OrderItem -> GameAd
        $this->assertEquals($gameAd->id, $orderItem->gameAd->id);
    }
}
