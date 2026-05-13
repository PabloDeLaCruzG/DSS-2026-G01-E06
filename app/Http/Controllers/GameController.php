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

    // Construimos la query base con los filtros habituales
    $query = Game::published()
        ->withCount('gameAds')

        // Para mostrar los juegos según el filtro
        ->when($platform, function ($q) use ($platform) {
            $q->whereJsonContains('platforms', $platform);
        })

        // Para mostrar los juegos en la barra de búsqueda
        ->when($search, function ($q) use ($search) {
            $q->where('title', 'like', '%' . $search . '%');
        })

        // Para mostrar los mejor valorados
        ->when($request->get('rating'), function ($q) use ($request) {
            $q->where('rating', '>=', $request->get('rating'));
        })

        // Filtrado por género
        ->when($request->get('genre'), function ($q) use ($request) {
            $q->whereJsonContains('genres', $request->get('genre'));
        })

        // Filtro por precio máximo
        ->when($request->get('max_price') && $request->get('max_price') < 200, function ($q) use ($request) {
            $q->whereHas('gameAds', function ($qa) use ($request) {
                $qa->where('price', '<=', $request->get('max_price'));
            });
        });

    // Ordenación según request('sort')
    $sort = $request->get('sort');

    if ($sort === 'popular') {
       // Más populares: por rating (desc)
       $query->orderByRaw('COALESCE(rating, 0) DESC');
    } elseif ($sort === 'price_asc') {
        // Más barato: por precio mínimo ascendente
        $query->orderByRaw("(SELECT MIN(price) FROM game_ads WHERE game_ads.game_id = games.id) ASC");
    } elseif ($sort === 'price_desc') {
        // Más caro: por precio mínimo descendente
        $query->orderByRaw("(SELECT MIN(price) FROM game_ads WHERE game_ads.game_id = games.id) DESC");
    } elseif ($sort === 'name') {
        // Por nombre (alfabético)
        $query->orderBy('title', 'asc');
    } else {
        // Orden por defecto
        $query->orderBy('title', 'asc');
    }

    // Paginación y mantener query string
    $games = $query->paginate(30)->withQueryString();

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