<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Game;
use App\Models\GameAd;
use App\Models\User;

class GameAdLogicTest extends TestCase
{
    use RefreshDatabase;

    // Relación 11: GameAd pertenece a un Game
    public function test_gamead_pertenece_a_un_game(): void
    {
        $game = Game::factory()->create();
        $gameAd = GameAd::factory()->create(['game_id' => $game->id]);

        $this->assertEquals($game->id, $gameAd->game->id);
    }

    // Relación 1: GameAd pertenece a un User
    public function test_gamead_pertenece_a_un_user(): void
    {
        $user = User::factory()->create();
        $gameAd = GameAd::factory()->create(['user_id' => $user->id]);

        $this->assertEquals($user->id, $gameAd->user->id);
    }

    // Método markAsSold
    public function test_mark_as_sold_cambia_status_a_sold(): void
    {
        $gameAd = GameAd::factory()->create(['status' => 'ACTIVE']);
        $gameAd->markAsSold();

        $this->assertEquals('SOLD', $gameAd->fresh()->status);
    }
}
