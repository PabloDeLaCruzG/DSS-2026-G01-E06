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

        $ads = $query->paginate(4)->withQueryString();

        $totalVentas = GameAd::where('user_id', $userId)->sum('price');

        $activos = GameAd::where('user_id', $userId)
            ->where('status', 'ACTIVE')
            ->count();

        return view('user.games.index', compact('ads', 'totalVentas', 'activos'));
    }

    public function create()
    {
        $games = Game::all();

        return view('user.games.form', compact('games'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'game_id'     => 'required|exists:games,id',
            'price'       => 'required|numeric|min:0',
            'format'      => 'required|in:PHYSICAL,DIGITAL_KEY',
            'description' => 'nullable|string',
            'key'         => 'required_if:format,DIGITAL_KEY|nullable|string',
            'quantity'    => 'required_if:format,DIGITAL_KEY|nullable|integer|min:1',
            'condition'   => 'required_if:format,PHYSICAL|nullable|in:USED,NEW',
        ]);

        if ($request->format === GameAd::FORMAT_DIGITAL_KEY && !$user->isProfessional()) {
            return back()->withErrors(['format' => 'Solo los vendedores profesionales verificados pueden publicar claves digitales.'])->withInput();
        }

        if ($request->format === GameAd::FORMAT_DIGITAL_KEY) {
            $condition = GameAd::CONDITION_NEW;
            $quantity  = (int) $request->quantity;
        } else {
            $condition = $request->condition;
            $quantity  = 1;
        }

        GameAd::create([
            'user_id'     => $user->id,
            'game_id'     => $request->game_id,
            'price'       => $request->price,
            'format'      => $request->format,
            'condition'   => $condition,
            'quantity'    => $quantity,
            'status'      => 'ACTIVE',
            'description' => $request->description ?? '',
            'digital_key' => $request->key,
        ]);

        return redirect()->route('games.index')
            ->with('success', 'Anuncio creado correctamente');
    }

    public function edit($id)
    {
        $userId = Auth::id();

        $ad = GameAd::where('user_id', $userId)
            ->with('game')
            ->findOrFail($id);

        $games = Game::all();

        return view('user.games.form', compact('ad', 'games'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();

        $ad = GameAd::where('user_id', $user->id)->findOrFail($id);

        $request->validate([
            'game_id'     => 'required|exists:games,id',
            'price'       => 'required|numeric|min:0',
            'format'      => 'required|in:PHYSICAL,DIGITAL_KEY',
            'status'      => 'required|in:ACTIVE,SOLD',
            'description' => 'nullable|string',
            'key'         => 'required_if:format,DIGITAL_KEY|nullable|string',
            'quantity'    => 'required_if:format,DIGITAL_KEY|nullable|integer|min:1',
            'condition'   => 'required_if:format,PHYSICAL|nullable|in:USED,NEW',
        ]);

        if ($request->format === GameAd::FORMAT_DIGITAL_KEY && !$user->isProfessional()) {
            return back()->withErrors(['format' => 'Solo los vendedores profesionales verificados pueden publicar claves digitales.'])->withInput();
        }

        if ($request->format === GameAd::FORMAT_DIGITAL_KEY) {
            $condition = GameAd::CONDITION_NEW;
            $quantity  = (int) $request->quantity;
        } else {
            $condition = $request->condition;
            $quantity  = 1;
        }

        $ad->update([
            'game_id'     => $request->game_id,
            'price'       => $request->price,
            'format'      => $request->format,
            'condition'   => $condition,
            'quantity'    => $quantity,
            'status'      => $request->status,
            'description' => $request->description ?? '',
            'digital_key' => $request->key,
        ]);

        return redirect()->route('games.index')
            ->with('success', 'Anuncio actualizado correctamente');
    }

    public function destroy($id)
    {
        $ad = GameAd::where('user_id', Auth::id())->findOrFail($id);

        $ad->delete();

        return back()->with('success', 'Anuncio eliminado');
    }
}
