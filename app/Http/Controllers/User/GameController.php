<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\GameAd;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        $query = GameAd::where('user_id', $userId)
            ->with('game');

        if ($request->filled('search')) {
            $query->whereHas('game', function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%');
            });
        }
        $ads = $query->paginate(4)->withQueryString();$ads = $query->paginate(4);

        $totalVentas = GameAd::where('user_id', $userId)->sum('price');

        $activos = GameAd::where('user_id', $userId)
            ->where('status', 'ACTIVE')
            ->count();

        return view('user.games.index', compact('ads', 'totalVentas', 'activos'));
    }

    // CREAR
    public function create()
    {
        $games = Game::all();

        return view('user.games.form', compact('games'));
    }

    // GUARDAR NUEVO
    public function store(Request $request)
    {
        $request->validate([
            'game_id' => 'required|exists:games,id',
            'price' => 'required|numeric|min:0',
            'format' => 'required|in:PHYSICAL,DIGITAL_KEY',
            'description' => 'nullable|string',
            'key' => 'nullable|string',
        ]);

        GameAd::create([
            'user_id' => Auth::id(),
            'game_id' => $request->game_id,
            'price' => $request->price,
            'format' => $request->format,
            'status' => 'ACTIVE',
            'description' => $request->description,
            'digital_key' => $request->key, 
        ]);

        return redirect()->route('games.index')
            ->with('success', 'Anuncio creado correctamente');
    }

    // EDITAR
    public function edit($id)
    {
        $userId = Auth::id();

        $ad = GameAd::where('user_id', $userId)
            ->with('game')
            ->findOrFail($id);

        $games = Game::all();

        return view('user.games.form', compact('ad', 'games'));
    }

    // ACTUALIZAR
    public function update(Request $request, $id)
    {
        $userId = Auth::id();

        $ad = GameAd::where('user_id', $userId)->findOrFail($id);

        $request->validate([
            'game_id' => 'required|exists:games,id',
            'price' => 'required|numeric|min:0',
            'format' => 'required|in:PHYSICAL,DIGITAL_KEY',
            'status' => 'required|in:ACTIVE,SOLD',
            'description' => 'nullable|string',
            'key' => 'nullable|string',
        ]);

        $ad->update([
            'game_id' => $request->game_id,
            'price' => $request->price,
            'format' => $request->format,
            'status' => $request->status,
            'description' => $request->description,
            'digital_key' => $request->key, 
        ]);

        return redirect()->route('games.index')
            ->with('success', 'Anuncio actualizado correctamente');
    }

    // ELIMINAR
    public function destroy($id)
    {
        $ad = GameAd::where('user_id', Auth::id())->findOrFail($id);

        $ad->delete();

        return back()->with('success', 'Anuncio eliminado');
    }
}