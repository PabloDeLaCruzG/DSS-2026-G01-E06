<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\GameAd;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AuctionBid>
 */
class AuctionBidFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'game_ad_id' => GameAd::factory(),
            'amount'     => fake()->randomFloat(2, 1, 1000),
            'bid_time'   => fake()->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
