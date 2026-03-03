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
            'cover_image' => fake()->imageUrl(600, 800, 'video-games'),
            'trailer_url' => fake()->url(),
            'rating' => fake()->randomFloat(2, 1, 5),
            
            'genres' => [fake()->randomElement(['ACTION', 'RPG', 'SPORTS', 'STRATEGY'])],
            'platforms' => [fake()->randomElement(['PS5', 'PC', 'SWITCH', 'XBOX'])],
        ];
    }
}