<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Favorite;
use App\Models\Game;
use App\Models\User;

class FavoriteSeeder extends Seeder
{
   
    public function run(): void
    {
        $users = User::all();
        $games = Game::all();

        foreach ($users as $user) {
            // Cada usuario marca entre 3 y 8 juegos como favoritos aleatoriamente
            $randomGames = $games->random(min(rand(3, 8), $games->count()));

            foreach ($randomGames as $game) {
                Favorite::firstOrCreate([
                    'user_id' => $user->id,
                    'game_id' => $game->id,
                ]);
            }
        }
    }
}
