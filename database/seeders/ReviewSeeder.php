<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Game;
use App\Models\GameAd;
use App\Models\Review;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gameIds = Game::pluck('id');
        Review::factory(15)->create([
            'game_ad_id' => fn() => GameAd::factory()->create([
                'game_id' => $gameIds->random(),
            ])->id,
        ]);
    }
}
