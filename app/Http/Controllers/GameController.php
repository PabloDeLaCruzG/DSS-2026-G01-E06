<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;

class GameController extends Controller
{
    /**
     * Muestra el catálogo global (Home)
     */
    public function index(Request $request)
    {
        $platform = $request->get('platform');
        $search = $request->get('search');

        $games = Game::published()
            ->withCount('gameAds')

            // Para mostrar los juegos según el filtro
            ->when($platform, function ($query) use ($platform) {
                $query->whereJsonContains('platforms', $platform);
            })

            // Para mostrar los juegos en la barra de búsqueda
            ->when($search, function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%');
            })

            // Para mostrar los mejor valorados
            ->when($request->get('rating'), function ($query) use ($request) {
                $query->where('rating', '>=', $request->get('rating'));
            })

            // Filtrado por género
            ->when($request->get('genre'), function ($query) use ($request) {
                $query->whereJsonContains('genres', $request->get('genre'));
            })

            // Filtro por precio máximo
            ->when($request->get('max_price') && $request->get('max_price') < 200, function ($query) use ($request) {
                $query->whereHas('gameAds', function ($q) use ($request) {
                    $q->where('price', '<=', $request->get('max_price'));
                });
            })

            ->paginate(30)
            ->withQueryString();

        $favoriteIds = auth()->check()
            ? auth()->user()->favorites()->pluck('games.id')->toArray()
            : [];

        return view('home', compact('games', 'favoriteIds'));
    }

    /**
     * Muestra el detalle de un juego específico
     */
    public function show($id)
    {
        $game = Game::with('gameAds.user')->findOrFail($id);

        $proAds = $game->gameAds->where('format', 'DIGITAL_KEY');
        $userAds = $game->gameAds->where('format', 'PHYSICAL');

        return view('games.show', [
            'game' => $game,
            'proAds' => $proAds,
            'userAds' => $userAds
        ]);
    }
}