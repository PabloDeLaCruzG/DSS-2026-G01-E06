<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProfessionalProfile>
 */
class ProfessionalProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cif' => fake()->unique()->regexify('[A-Z]{1}[0-9]{8}'), // Ejemplo: B12345678
            'company_name' => fake()->company(),
            'website' => fake()->url(),
            'verification_docs' => fake()->url(), // URL falsa al PDF
            'is_verified' => fake()->boolean(80), // 80% de los pros están verificados
        ];
    }
}
