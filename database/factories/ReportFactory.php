<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\GameAd;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Report>
 */
class ReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'game_ad_id' => GameAd::factory(),
            'reason' => fake()->sentence(),
            'status' => fake()->randomElement(['OPEN', 'RESOLVED', 'DISMISSED']),
            'resolution_notes' => fake()->optional()->paragraph(),
        ];
    }
}
