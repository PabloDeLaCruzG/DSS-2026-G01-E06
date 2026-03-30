<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\GameAd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    public function index()
    {
        $userId = Auth::id() ?? 1;//si no existe porque aun no esta la autenticacion se asume que es 1

        $ads = GameAd::where('user_id', $userId)
            ->with('game')
            ->paginate(4);

        $totalVentas = GameAd::where('user_id', $userId)->sum('price');

        $activos = GameAd::where('user_id', $userId)
            ->where('status', 'ACTIVE')
            ->count();

        return view('user.games.index', compact('ads', 'totalVentas', 'activos'));
    }

    public function edit($id)
    {
        $userId = Auth::id() ?? 1;

        $ad = GameAd::where('user_id', $userId)
            ->with('game')
            ->findOrFail($id);

        return view('user.games.edit', compact('ad'));
    }

    public function update(Request $request, $id)
    {
        $userId = Auth::id() ?? 1;

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