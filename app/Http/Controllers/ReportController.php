<?php

namespace App\Http\Controllers;

use App\Models\GameAd;
use App\Services\ReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct(private ReportService $reportService) {}

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

        if ($this->reportService->isDuplicate($request->user()->id, $gameAd->id)) {
            return redirect()
                ->route('games.show', $gameAd->game_id)
                ->with('error', 'Ya tienes una denuncia abierta para este anuncio.');
        }

        $this->reportService->store($request->user()->id, $gameAd, $data['reason']);

        return redirect()
            ->route('games.show', $gameAd->game_id)
            ->with('success', 'Denuncia enviada correctamente.');
    }
}
