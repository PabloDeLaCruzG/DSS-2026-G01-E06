<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use Illuminate\Http\Request;

class GameController extends Controller
{
    protected array $genres = [
        'Acción', 'Aventura', 'RPG', 'Deportes', 'Estrategia',
        'Simulación', 'Terror', 'Plataformas', 'Lucha', 'Puzzle',
        'Carreras', 'Shooter', 'Multijugador', 'Indie',
    ];

    protected array $platforms = [
        'PC', 'PlayStation 4', 'PlayStation 5',
        'Xbox One', 'Xbox Series X', 'Nintendo Switch',
        'iOS', 'Android',
    ];

    public function index(Request $request)
    {
        $query = Game::withCount('gameAds');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filter === 'published') {
            $query->where('is_published', true);
        } elseif ($request->filter === 'hidden') {
            $query->where('is_published', false);
        }

        $games        = $query->latest()->paginate(15)->withQueryString();
        $totalGames   = Game::count();
        $published    = Game::where('is_published', true)->count();
        $hidden       = Game::where('is_published', false)->count();

        return view('admin.games.index', compact('games', 'totalGames', 'published', 'hidden'));
    }

    public function create()
    {
        return view('admin.games.form', [
            'game'      => null,
            'genres'    => $this->genres,
            'platforms' => $this->platforms,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        Game::create($data);

        return redirect()->route('admin.games.index')
            ->with('success', "Juego \"{$data['title']}\" creado correctamente.");
    }

    public function edit(Game $game)
    {
        return view('admin.games.form', [
            'game'      => $game,
            'genres'    => $this->genres,
            'platforms' => $this->platforms,
        ]);
    }

    public function update(Request $request, Game $game)
    {
        $data = $this->validated($request, $game);

        $game->update($data);

        return redirect()->route('admin.games.index')
            ->with('success', "Juego \"{$game->title}\" actualizado correctamente.");
    }

    public function destroy(Game $game)
    {
        $title = $game->title;
        $game->delete();

        return redirect()->route('admin.games.index')
            ->with('success', "Juego \"{$title}\" eliminado.");
    }

    public function togglePublish(Game $game)
    {
        $game->update(['is_published' => !$game->is_published]);

        $msg = $game->is_published ? 'publicado' : 'ocultado';
        return back()->with('success', "Juego \"{$game->title}\" {$msg}.");
    }

    private function validated(Request $request, ?Game $game = null): array
    {
        $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'year'         => 'nullable|integer|min:1950|max:' . (date('Y') + 2),
            'cover_image'  => 'nullable|url|max:500',
            'trailer_url'  => 'nullable|url|max:500',
            'rating'       => 'nullable|numeric|min:0|max:10',
            'genres'       => 'nullable|array',
            'genres.*'     => 'string',
            'platforms'    => 'nullable|array',
            'platforms.*'  => 'string',
            'is_published' => 'boolean',
        ]);

        return [
            'title'        => $request->title,
            'description'  => $request->description,
            'year'         => $request->year,
            'cover_image'  => $request->cover_image,
            'trailer_url'  => $request->trailer_url,
            'rating'       => $request->rating ?? 0,
            'genres'       => $request->genres ?? [],
            'platforms'    => $request->platforms ?? [],
            'is_published' => $request->boolean('is_published'),
        ];
    }
}
