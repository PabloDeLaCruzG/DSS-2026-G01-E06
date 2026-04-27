<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;

class GameController extends Controller
{
    /**
     * Muestra el catálogo global (Home)
     */
    public function index()
    {
        $games = Game::query()
            ->withCount('gameAds')
            ->when(request('search'), function ($query, $search) {
                $query->where('title', 'like', '%' . $search . '%');
            })
            ->when(request('platform'), function ($query, $platform) {
                $query->whereJsonContains('platforms', $platform);
            })
            ->when(request('rating'), function ($query, $rating) {
                $query->where('rating', '>=', (float) $rating);
            })
            ->orderBy('id')
            ->paginate(12)
            ->withQueryString();

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