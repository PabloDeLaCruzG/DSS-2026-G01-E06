<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index()
    {
        $favorites = auth()->user()
            ->favorites()
            ->withCount('gameAds')
            ->paginate(20);

        return view('user.favorites.index', compact('favorites'));
    }

    public function toggle(Game $game)
    {
        $user = auth()->user();
        $user->favorites()->toggle($game->id);
        $isFavorited = $user->favorites()->where('game_id', $game->id)->exists();

        return response()->json(['favorited' => $isFavorited]);
    }
}