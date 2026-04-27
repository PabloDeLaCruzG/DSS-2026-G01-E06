<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\GameAd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    public function index()
    {
        $ads = GameAd::with('game')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('user.games.index', compact('ads'));
    }

    public function create()
    {
        $games = Game::orderBy('title')->get();

        return view('user.games.form', [
            'ad' => new GameAd(),
            'games' => $games,
            'isEdit' => false,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'game_id' => ['required', 'exists:games,id'],
            'price' => ['required', 'numeric', 'min:0.01'],
            'description' => ['required', 'string'],
            'condition' => ['required', 'in:NEW,USED,DIGITAL'],
            'format' => ['required', 'in:PHYSICAL,DIGITAL_KEY'],
            'status' => ['required', 'in:ACTIVE,SOLD,HIDDEN'],
            'quantity' => ['required', 'integer', 'min:1'],
            'digital_key' => ['nullable', 'string'],
        ]);

        $ad = new GameAd();
        $ad->game_id = $validated['game_id'];
        $ad->user_id = Auth::id();
        $ad->price = $validated['price'];
        $ad->description = $validated['description'];
        $ad->condition = $validated['condition'];
        $ad->format = $validated['format'];
        $ad->status = $validated['status'];
        $ad->quantity = $validated['quantity'];
        $ad->digital_key = $validated['digital_key'] ?? null;
        $ad->save();

        return redirect()->route('games.index')->with('success', 'Anuncio creado correctamente.');
    }

    public function edit($id)
    {
        $ad = GameAd::where('user_id', Auth::id())->findOrFail($id);
        $games = Game::orderBy('title')->get();

        return view('user.games.form', [
            'ad' => $ad,
            'games' => $games,
            'isEdit' => true,
        ]);
    }

    public function update(Request $request, $id)
    {
        $ad = GameAd::where('user_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'game_id' => ['required', 'exists:games,id'],
            'price' => ['required', 'numeric', 'min:0.01'],
            'description' => ['required', 'string'],
            'condition' => ['required', 'in:NEW,USED,DIGITAL'],
            'format' => ['required', 'in:PHYSICAL,DIGITAL_KEY'],
            'status' => ['required', 'in:ACTIVE,SOLD,HIDDEN'],
            'quantity' => ['required', 'integer', 'min:1'],
            'digital_key' => ['nullable', 'string'],
        ]);

        $ad->game_id = $validated['game_id'];
        $ad->price = $validated['price'];
        $ad->description = $validated['description'];
        $ad->condition = $validated['condition'];
        $ad->format = $validated['format'];
        $ad->status = $validated['status'];
        $ad->quantity = $validated['quantity'];
        $ad->digital_key = $validated['digital_key'] ?? null;
        $ad->save();

        return redirect()->route('games.index')->with('success', 'Anuncio actualizado correctamente.');
    }

    public function destroy($id)
    {
        $ad = GameAd::where('user_id', Auth::id())->findOrFail($id);
        $ad->delete();

        return redirect()->route('games.index')->with('success', 'Anuncio eliminado correctamente.');
    }
}
