<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\GameAd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    public function index(Request $request)
{
    $userId = Auth::id();

    $query = GameAd::where('user_id', $userId)
        ->with('game');

    //buscador
    if ($request->filled('search')) {
        $query->whereHas('game', function ($q) use ($request) {
            $q->where('title', 'like', '%' . $request->search . '%');
        });
    }

    $ads = $query->paginate(4);

    //estadisticas
    $totalVentas = GameAd::where('user_id', $userId)->sum('price');

    $activos = GameAd::where('user_id', $userId)
        ->where('status', 'ACTIVE')
        ->count();

    return view('user.games.index', compact('ads', 'totalVentas', 'activos'));
}

    public function edit($id)
    {
        $userId = Auth::id();

        $ad = GameAd::where('user_id', $userId)
            ->with('game')
            ->findOrFail($id);

        return view('user.games.edit', compact('ad'));
    }

    public function update(Request $request, $id)
    {
        $userId = Auth::id();

        $ad = GameAd::where('user_id', $userId)->findOrFail($id);

        $request->validate([
            'price' => 'required|numeric|min:0',
            'format' => 'required|in:PHYSICAL,DIGITAL_KEY',
            'status' => 'required|in:ACTIVE,SOLD'
        ]);

        $ad->update([
            'price' => $request->price,
            'format' => $request->format,
            'status' => $request->status,
        ]);

        return redirect()->route('games.index');
    }
}