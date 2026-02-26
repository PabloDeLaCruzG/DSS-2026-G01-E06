<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\AuctionBid;
use App\Models\GameAd;
use App\Models\User;

class AuctionBidTest extends TestCase
{
    use RefreshDatabase;

    // Relación 12: AuctionBid pertenece a un GameAd
    public function test_auctionbid_pertenece_a_un_gamead(): void
    {
        $gameAd = GameAd::factory()->create(['type' => 'AUCTION']);
        $bid = AuctionBid::factory()->create(['game_ad_id' => $gameAd->id]);

        $this->assertEquals($gameAd->id, $bid->gamead->id);
    }

    // Relación 6: AuctionBid tiene muchos Users
    public function test_auctionbid_tiene_muchos_users(): void
    {
        $bid = AuctionBid::factory()->create();
        $users = User::factory(3)->create();
        $bid->users()->attach($users->pluck('id'));

        $this->assertCount(3, $bid->users);
    }

}
