<?php

namespace Tests\Feature;

use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_calculate_average_returns_average_for_game_ad_reviews(): void
    {
        $gameAd = \App\Models\GameAd::factory()->create();

        $users = User::factory()->count(3)->create();

        $firstReview = Review::create([
            'user_id' => $users[0]->id,
            'game_ad_id' => $gameAd->id,
            'rating' => 3,
            'comment' => 'ok',
        ]);

        Review::create([
            'user_id' => $users[1]->id,
            'game_ad_id' => $gameAd->id,
            'rating' => 4,
            'comment' => 'good',
        ]);

        Review::create([
            'user_id' => $users[2]->id,
            'game_ad_id' => $gameAd->id,
            'rating' => 5,
            'comment' => 'great',
        ]);

        $this->assertSame(4.0, $firstReview->calculateAverage());
    }
}
