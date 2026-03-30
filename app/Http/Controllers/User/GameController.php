<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\GameAd;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function index()
    {
        $ads = GameAd::where('user_id', auth()->id())
            ->with('game')
            ->paginate(4);

        $totalVentas = GameAd::where('user_id', auth()->id())->sum('price');

        $activos = GameAd::where('user_id', auth()->id())
            ->where('status', 'ACTIVE')
            ->count();

        return view('user.games.index', compact('ads', 'totalVentas', 'activos'));
    }
}

public function edit($id)
{
    $ad = GameAd::where('user_id', auth()->id())
        ->with('game')
        ->findOrFail($id);

    return view('user.games.edit', compact('ad'));
}


public function update(Request $request, $id)
{
    $ad = GameAd::where('user_id', auth()->id())->findOrFail($id);

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