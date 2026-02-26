<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Game>
 */
class GameFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title'        => fake()->words(3, true),
            'description'  => fake()->paragraph(),
            'release_date' => fake()->date(),
            'cover_image'  => fake()->imageUrl(640, 480),
            'genre'        => fake()->randomElement(['ACTION', 'RPG', 'SPORTS']),
            'platform'     => fake()->randomElement(['PS5', 'XBOX']),
        ];
    }
}
