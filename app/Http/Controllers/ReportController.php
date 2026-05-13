<?php

namespace App\Http\Controllers;

use App\Models\GameAd;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function create(GameAd $gameAd)
    {
        $gameAd->load(['game', 'user']);

        return view('reports.create', compact('gameAd'));
    }

    public function store(Request $request, GameAd $gameAd)
    {
        $data = $request->validate([
            'reason' => ['required', 'string', 'max:255'],
        ]);

        $duplicate = Report::where('user_id', $request->user()->id)
            ->where('game_ad_id', $gameAd->id)
            ->where('status', Report::STATUS_OPEN)
            ->exists();

        if ($duplicate) {
            return redirect()
                ->route('games.show', $gameAd->game_id)
                ->with('error', 'Ya tienes una denuncia abierta para este anuncio.');
        }

        Report::create([
            'user_id' => $request->user()->id,
            'game_ad_id' => $gameAd->id,
            'reason' => $data['reason'],
            'status' => Report::STATUS_OPEN,
        ]);

        return redirect()
            ->route('games.show', $gameAd->game_id)
            ->with('success', 'Denuncia enviada correctamente.');
    }
}
