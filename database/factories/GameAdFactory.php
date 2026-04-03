<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Game;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GameAd>
 */
class GameAdFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'game_id'     => Game::factory(),
            'user_id'     => User::factory(),
            'price'       => fake()->randomFloat(2, 1, 500),
            'description' => fake()->paragraph(),
            'condition'   => fake()->randomElement(['NEW', 'USED', 'DIGITAL']),
            'format'      => fake()->randomElement(['PHYSICAL', 'DIGITAL_KEY']),
            'digital_key' => fake()->boolean(30) ? fake()->uuid() : null,
            'images'      => fake()->imageUrl(640, 480),
            'status'      => 'ACTIVE',
            'quantity'    => fake()->numberBetween(1, 10),
        ];
    }
}
