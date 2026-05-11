<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Game;
use App\Models\GameAd;
use App\Models\Report;

class ReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gameIds = Game::pluck('id');
        Report::factory(10)->create([
            'game_ad_id' => fn() => GameAd::factory()->create([
                'game_id' => $gameIds->random(),
            ])->id,
        ]);
    }
}
