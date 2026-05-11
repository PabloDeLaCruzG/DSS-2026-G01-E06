@extends('admin.layouts.layout')

@section('title', 'Denuncia #' . $report->id)

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <a href="{{ route('admin.reports.index') }}" class="inline-flex items-center gap-1.5 text-sm text-text-muted hover:text-text-main transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Volver a denuncias
    </a>

    @if(session('success'))
        <div class="bg-green-500/20 border border-green-500/30 text-green-400 px-4 py-3 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-surface rounded-xl border border-border p-6">
        <div class="flex items-start justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <h1 class="text-2xl font-bold text-text-main">Denuncia #{{ $report->id }}</h1>
                    @if($report->status === \App\Models\Report::STATUS_OPEN)
                        <span class="px-2 py-0.5 rounded text-xs font-medium bg-yellow-500/10 text-yellow-400 border border-yellow-500/20">OPEN</span>
                    @elseif($report->status === \App\Models\Report::STATUS_RESOLVED)
                        <span class="px-2 py-0.5 rounded text-xs font-medium bg-accent/10 text-accent border border-accent/20">RESOLVED</span>
                    @else
                        <span class="px-2 py-0.5 rounded text-xs font-medium bg-border text-text-muted border border-border">DISMISSED</span>
                    @endif
                </div>
                <p class="text-sm text-text-muted">Creada el {{ $report->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="bg-surface rounded-xl border border-border p-5 space-y-4">
            <h2 class="text-sm font-semibold text-text-main uppercase tracking-wide">Usuario denunciante</h2>
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between gap-4">
                    <dt class="text-text-muted">Nombre</dt>
                    <dd class="text-text-main font-medium">{{ $report->user->name ?? 'Usuario eliminado' }}</dd>
                </div>
                <div class="flex justify-between gap-4">
                    <dt class="text-text-muted">Email</dt>
                    <dd class="text-text-main">{{ $report->user->email ?? '-' }}</dd>
                </div>
            </dl>
        </div>

        <div class="bg-surface rounded-xl border border-border p-5 space-y-4">
            <h2 class="text-sm font-semibold text-text-main uppercase tracking-wide">Anuncio reportado</h2>
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between gap-4">
                    <dt class="text-text-muted">Juego</dt>
                    <dd class="text-text-main font-medium">{{ optional(optional($report->gameAd)->game)->title ?? 'Anuncio eliminado' }}</dd>
                </div>
                <div class="flex justify-between gap-4">
                    <dt class="text-text-muted">Vendedor</dt>
                    <dd class="text-text-main">{{ optional(optional($report->gameAd)->user)->name ?? 'Usuario eliminado' }}</dd>
                </div>
                <div class="flex justify-between gap-4">
                    <dt class="text-text-muted">Precio</dt>
                    <dd class="text-accent font-medium">
                        @if($report->gameAd)
                            {{ number_format($report->gameAd->price, 2) }} &euro;
                        @else
                            -
                        @endif
                    </dd>
                </div>
            </dl>
        </div>
    </div>

    <div class="bg-surface rounded-xl border border-border p-5 space-y-3">
        <h2 class="text-sm font-semibold text-text-main uppercase tracking-wide">Motivo</h2>
        <p class="text-sm text-text-main leading-relaxed">{{ $report->reason }}</p>
    </div>

    <form method="POST" action="{{ route('admin.reports.resolve', $report) }}" class="bg-surface rounded-xl border border-border p-6 space-y-5">
        @csrf

        <div>
            <label for="resolution_notes" class="block text-sm font-medium text-text-main mb-2">Notas de resolucion</label>
            <textarea id="resolution_notes" name="resolution_notes" rows="5"
                class="w-full bg-background border border-border rounded-lg px-4 py-3 text-sm text-text-main placeholder-text-muted focus:outline-none focus:border-primary transition"
                placeholder="Notas internas sobre la decision...">{{ old('resolution_notes', $report->resolution_notes) }}</textarea>
            @error('resolution_notes')
                <p class="text-red-400 text-xs mt-2">{{ $message }}</p>
            @enderror
        </div>

        @error('decision')
            <p class="text-red-400 text-xs">{{ $message }}</p>
        @enderror

        <div class="flex items-center justify-end gap-3">
            <button type="submit" name="decision" value="{{ \App\Models\Report::STATUS_DISMISSED }}"
                class="px-4 py-2 text-sm rounded-lg bg-surface border border-border text-text-muted hover:text-text-main transition">
                Descartar
            </button>
            <button type="submit" name="decision" value="{{ \App\Models\Report::STATUS_RESOLVED }}"
                class="px-4 py-2 text-sm rounded-lg bg-primary hover:bg-primary-hover text-white transition">
                Resolver
            </button>
        </div>
    </form>
</div>
@endsection
