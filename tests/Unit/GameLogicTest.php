<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Game;
use App\Models\GameAd;

class GameLogicTest extends TestCase
{
    use RefreshDatabase;

    // Relación 11: Un Game tiene muchos GameAds
    public function test_game_tiene_muchos_gameads(): void
    {
        $game = Game::factory()->create();
        GameAd::factory(3)->create(['game_id' => $game->id]);

        $this->assertCount(3, $game->gameAds);
    }

    // Método getLowestPrice
    public function test_get_lowest_price_devuelve_el_precio_mas_bajo(): void
    {
        $game = Game::factory()->create();
        GameAd::factory()->create(['game_id' => $game->id, 'price' => 50.0]);
        GameAd::factory()->create(['game_id' => $game->id, 'price' => 20.0]);
        GameAd::factory()->create(['game_id' => $game->id, 'price' => 35.0]);

        $this->assertEquals(20.0, $game->getLowestPrice());
    }

    public function test_get_lowest_price_sin_anuncios_devuelve_cero(): void
    {
        $game = Game::factory()->create();
        $this->assertEquals(0.0, $game->getLowestPrice());
    }
}
