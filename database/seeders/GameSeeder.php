<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use App\Models\Game;

class GameSeeder extends Seeder
{
    public function run(): void
    {
        $json = File::get(database_path('data/games.json'));
        $games = json_decode($json, true);

        foreach ($games as $gameData) {
            
            // Comprobación de seguridad para arrays
            $platforms = isset($gameData['platforms']) ? json_decode($gameData['platforms'], true) : ['PC'];
            $genres = isset($gameData['genres']) ? json_decode($gameData['genres'], true) : ['Action'];

            Game::create([
                'title'        => $gameData['title'],
                'description'  => $gameData['description'],
                'year'         => $gameData['year'] ?? 0, 
                'cover_image'  => $gameData['cover_image'],
                'trailer_url'  => $gameData['trailer_url'],
                'rating'       => $gameData['rating'],
                'genres'       => $genres,
                'platforms'    => $platforms,
            ]);
        }
    }
}