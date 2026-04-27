<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Game;
use App\Models\User;
use App\Models\GameAd;
use App\Models\ProfessionalProfile;

class GameAdSeeder extends Seeder
{
    public function run(): void
    {
        $game = Game::first();

        if (!$game) return;

        $panelUser = User::where('email', 'user@gamelink.com')->first();

        $proUser1 = User::factory()->create(['name' => 'GameStop ES', 'role' => 'user']);
        ProfessionalProfile::factory()->create([
            'user_id' => $proUser1->id,
            'company_name' => 'GameStop España',
            'is_verified' => true
        ]);

        GameAd::create([
            'game_id' => $game->id,
            'user_id' => $proUser1->id,
            'price' => 49.90,
            'condition' => 'NEW',
            'format' => 'PHYSICAL',
            'status' => 'ACTIVE',
            'quantity' => 50,
            'description' => 'Edición estándar precintada. Envío 24h.'
        ]);

        $normalUser = User::factory()->create(['name' => 'RetroX_Gamer', 'reputation' => 4.8]);

        GameAd::create([
            'game_id' => $game->id,
            'user_id' => $normalUser->id,
            'price' => 35.00,
            'condition' => 'USED',
            'format' => 'PHYSICAL',
            'status' => 'ACTIVE',
            'description' => 'Como nuevo, solo usado una vez.'
        ]);

        $proUser2 = User::factory()->create(['name' => 'DigitalKeys_Official', 'role' => 'user']);
        ProfessionalProfile::factory()->create([
            'user_id' => $proUser2->id,
            'company_name' => 'Digital Keys Ltd',
            'is_verified' => true
        ]);

        GameAd::create([
            'game_id' => $game->id,
            'user_id' => $proUser2->id,
            'price' => 41.50,
            'condition' => 'NEW',
            'format' => 'DIGITAL_KEY',
            'digital_key' => 'AAAA-BBBB-CCCC-DDDD',
            'status' => 'ACTIVE',
            'quantity' => 100,
            'description' => 'Entrega inmediata al correo.'
        ]);

        GameAd::factory(5)->create(['game_id' => $game->id]);
        if ($panelUser) {
            GameAd::factory(5)->create([
                'game_id' => Game::inRandomOrder()->first()->id,
                'user_id' => $panelUser->id
            ]);
        }

        // Crear ofertas para casi todos los demás juegos (excepto los 3 últimos)
        $sellers = [$proUser1, $proUser2, $normalUser];
        $remainingGames = Game::where('id', '!=', $game->id)->orderBy('id')->get()->slice(0, -3);

        $pricesByTitle = [
            'Elden Ring'             => [59.90, 44.99, 52.00],
            'Baldur\'s Gate 3'      => [54.90, 39.99],
            'Clair Obscur'           => [49.99],
            'The Witcher 3'         => [19.99, 14.50, 22.00],
            'Cyberpunk 2077'        => [34.99, 27.50],
            'Red Dead Redemption'   => [39.99, 29.99],
            'God of War'            => [49.99, 38.00],
            'Hollow Knight'         => [14.99, 9.99],
            'Hades'                 => [19.99, 15.50],
            'Stardew Valley'        => [13.99, 11.00],
        ];

        foreach ($remainingGames as $g) {
            $numAds = rand(1, 3);

            for ($i = 0; $i < $numAds; $i++) {
                $seller = $sellers[array_rand($sellers)];
                $conditions = ['NEW', 'USED', 'NEW', 'DIGITAL'];
                $formats = ['PHYSICAL', 'DIGITAL_KEY', 'PHYSICAL'];
                $condition = $conditions[array_rand($conditions)];
                $format = $condition === 'DIGITAL' ? 'DIGITAL_KEY' : $formats[array_rand($formats)];

                // Precio base realista según el año del juego
                $basePrice = match(true) {
                    $g->year >= 2024 => rand(4490, 6990) / 100,
                    $g->year >= 2021 => rand(2499, 4999) / 100,
                    $g->year >= 2017 => rand(1499, 3499) / 100,
                    default          => rand(499, 1999) / 100,
                };

                // USED tiene descuento del 20-35%
                if ($condition === 'USED') {
                    $basePrice = round($basePrice * (1 - rand(20, 35) / 100), 2);
                }

                GameAd::create([
                    'game_id'   => $g->id,
                    'user_id'   => $seller->id,
                    'price'     => $basePrice,
                    'condition' => $condition,
                    'format'    => $format,
                    'status'    => 'ACTIVE',
                    'quantity'  => rand(1, 20),
                    'description' => $condition === 'USED'
                        ? 'Segunda mano en buen estado.'
                        : ($condition === 'DIGITAL' ? 'Clave digital, entrega inmediata.' : 'Nuevo, precintado.'),
                ]);
            }
        }
    }
}
