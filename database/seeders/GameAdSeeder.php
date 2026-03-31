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

        // 🔥 AÑADIDO: tu usuario del panel
        $panelUser = User::where('email', 'mipanel@gamelink.com')->first();

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

        // 🔥🔥 AÑADIDO: anuncios SOLO para tu usuario
        if ($panelUser) {
            GameAd::factory(5)->create([
                'game_id' => $game->id,
                'user_id' => $panelUser->id
            ]);
        }
    }
}