<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class GameFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->catchPhrase(),
            'description' => fake()->paragraph(),
            'year' => fake()->numberBetween(1995, 2026), 
            'cover_image' => null,
            'trailer_url' => fake()->url(),
            'rating' => fake()->randomFloat(2, 1, 5),
            
            'genres' => [fake()->randomElement(['ACTION', 'RPG', 'SPORTS', 'STRATEGY'])],
            'platforms' => [fake()->randomElement(['PS5', 'PC', 'SWITCH', 'XBOX'])],
        ];
    }

    public function configure(): static {

        return $this->afterMaking(function ($game) {
            $game->cover_image = 'https://placehold.co/600x800?text=' . urlencode($game->title);
        });
    }
}