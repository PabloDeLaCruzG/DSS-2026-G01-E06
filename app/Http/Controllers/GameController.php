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

            //Para mostrar los juegos según el filtro
            ->when($platform, function ($query) use ($platform) {
                $query->whereJsonContains('platforms', $platform);
            })

            //Para mostrar los juegos en la barra de búsqueda
            ->when($search, function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%');
            })

            //Para mostrar los mejor valorados
            ->when($request->get('rating'), function ($query) use ($request) {
                $query->where('rating', '>=', $request->get('rating'));
            })

            //Filtrado por género
            ->when($request->get('genre'), function ($query) use ($request) {
                $genreMap = [
                    'ACTION'   => 'Action',
                    'RPG'      => 'Role Playing',
                    'SPORTS'   => 'Sport',
                    'STRATEGY' => 'Strategy',
                ];
                $keyword = $genreMap[$request->get('genre')] ?? $request->get('genre');
                $query->where('genres', 'like', '%' . $keyword . '%');
            })

            // Filtro por precio máximo
            ->when($request->get('max_price') && $request->get('max_price') < 200, function ($query) use ($request) {
                $query->whereHas('gameAds', function($q) use ($request) {
                    $q->where('price', '<=', $request->get('max_price'));
                });
            })
            
            ->paginate(30)->withQueryString();
            
            return view('home', compact('games'));
    }
    /**
     * Muestra el detalle de un juego específico
     */
    public function show($id)
    {
        // 1. Cargamos el juego con sus anuncios y los vendedores de esos anuncios
        $game = Game::with('gameAds.user')->findOrFail($id);

        // 2. Separamos las ofertas (Lógica de Negocio)
        // Asumimos que los PROs venden formato DIGITAL y los Users FÍSICO
        $proAds = $game->gameAds->where('format', 'DIGITAL_KEY');
        $userAds = $game->gameAds->where('format', 'PHYSICAL');

        // 3. Enviamos todo a la vista
        return view('games.show', [
            'game' => $game,
            'proAds' => $proAds,
            'userAds' => $userAds
        ]);
    }
    
}