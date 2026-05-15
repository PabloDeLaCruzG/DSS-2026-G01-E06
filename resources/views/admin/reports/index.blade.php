@extends('admin.layouts.layout')

@section('title', 'Denuncias')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <div>
        <h1 class="text-2xl font-bold text-text-main">Denuncias</h1>
        <p class="text-sm text-text-muted mt-1">Revisa los reportes enviados por los usuarios sobre anuncios del marketplace.</p>
    </div>

    @if(session('success'))
        <div class="bg-green-500/20 border border-green-500/30 text-green-400 px-4 py-3 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-surface rounded-xl border border-border overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-border text-xs text-text-muted uppercase tracking-wide">
                    <th class="text-left px-5 py-3">Usuario</th>
                    <th class="text-left px-5 py-3">Anuncio</th>
                    <th class="text-left px-5 py-3">Estado</th>
                    <th class="text-left px-5 py-3">Fecha</th>
                    <th class="text-right px-5 py-3">Accion</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                @forelse($reports as $report)
                    <tr class="hover:bg-background/50 transition">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-xs font-bold text-white shrink-0">
                                    {{ strtoupper(substr($report->user->name ?? 'U', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-medium text-text-main text-sm">{{ $report->user->name ?? 'Usuario eliminado' }}</p>
                                    <p class="text-xs text-text-muted">{{ $report->user->email ?? '' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <p class="font-medium text-text-main">{{ optional(optional($report->gameAd)->game)->title ?? 'Anuncio eliminado' }}</p>
                            <p class="text-xs text-text-muted">
                                Vendedor: {{ optional(optional($report->gameAd)->user)->name ?? 'Usuario eliminado' }}
                            </p>
                        </td>
                        <td class="px-5 py-4">
                            @if($report->status === \App\Models\Report::STATUS_OPEN)
                                <span class="flex items-center gap-1.5 text-xs text-yellow-400">
                                    <span class="w-1.5 h-1.5 rounded-full bg-yellow-400"></span>
                                    OPEN
                                </span>
                            @elseif($report->status === \App\Models\Report::STATUS_RESOLVED)
                                <span class="flex items-center gap-1.5 text-xs text-accent">
                                    <span class="w-1.5 h-1.5 rounded-full bg-accent"></span>
                                    RESOLVED
                                </span>
                            @else
                                <span class="flex items-center gap-1.5 text-xs text-text-muted">
                                    <span class="w-1.5 h-1.5 rounded-full bg-text-muted"></span>
                                    DISMISSED
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-xs text-text-muted">
                            {{ $report->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-5 py-4 text-right">
                            <a href="{{ route('admin.reports.show', $report) }}"
                               class="px-3 py-1 text-xs rounded bg-surface text-text-muted border border-border hover:text-text-main hover:border-primary/30 transition">
                                VER
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-10 text-center text-text-muted text-sm">No hay denuncias registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($reports->hasPages())
            <div class="px-5 py-3 border-t border-border flex items-center justify-between">
                <p class="text-xs text-text-muted">
                    Mostrando {{ $reports->firstItem() }}-{{ $reports->lastItem() }} de {{ $reports->total() }} denuncias
                </p>
                <div class="flex gap-1">
                    {{ $reports->onEachSide(1)->links('admin.layouts.pagination') }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
