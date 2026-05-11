<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReportController extends Controller
{
    public function index()
    {
        $reports = Report::with(['user', 'gameAd.game', 'gameAd.user'])
            ->latest()
            ->paginate(10);

        return view('admin.reports.index', compact('reports'));
    }

    public function show(Report $report)
    {
        $report->load(['user', 'gameAd.game', 'gameAd.user']);

        return view('admin.reports.show', compact('report'));
    }

    public function resolve(Request $request, Report $report)
    {
        $data = $request->validate([
            'decision' => ['required', Rule::in(Report::RESOLUTION_DECISIONS)],
            'resolution_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $report->resolve($data['decision'], $data['resolution_notes'] ?? null);

        return redirect()
            ->route('admin.reports.show', $report)
            ->with('success', 'Denuncia actualizada correctamente.');
    }
}
