@extends('layouts.app')

@section('title', 'Reportar anuncio - GameLink')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <a href="{{ route('games.show', $gameAd->game) }}" class="inline-flex items-center gap-1.5 text-sm text-text-muted hover:text-text-main transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Volver al anuncio
    </a>

    <div>
        <h1 class="text-2xl font-bold text-text-main">Reportar anuncio</h1>
        <p class="text-sm text-text-muted mt-1">Describe el motivo de la denuncia para que el equipo pueda revisarlo.</p>
    </div>

    @if(session('error'))
        <div class="bg-red-500/20 border border-red-500/30 text-red-400 px-4 py-3 rounded-lg text-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-surface rounded-xl border border-border p-5">
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="text-xs text-text-muted uppercase tracking-wide mb-1">Anuncio</p>
                <h2 class="text-lg font-semibold text-text-main">{{ $gameAd->game->title ?? 'Anuncio eliminado' }}</h2>
                <p class="text-sm text-text-muted mt-1">
                    Vendedor: {{ $gameAd->user->name ?? 'Usuario eliminado' }}
                </p>
            </div>
            <div class="text-right shrink-0">
                <p class="text-xs text-text-muted">Precio</p>
                <p class="text-xl font-bold text-accent">{{ number_format($gameAd->price, 2) }} &euro;</p>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('reports.store', $gameAd) }}" class="bg-surface rounded-xl border border-border p-6 space-y-5">
        @csrf

        <div>
            <label for="reason" class="block text-sm font-medium text-text-main mb-2">Motivo</label>
            <textarea id="reason" name="reason" rows="6"
                class="w-full bg-background border border-border rounded-lg px-4 py-3 text-sm text-text-main placeholder-text-muted focus:outline-none focus:border-primary transition"
                placeholder="Explica brevemente qué ocurre con este anuncio...">{{ old('reason') }}</textarea>
            @error('reason')
                <p class="text-red-400 text-xs mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('games.show', $gameAd->game) }}"
               class="px-4 py-2 rounded bg-surface text-text-muted border border-border hover:text-text-main transition">
                Cancelar
            </a>
            <button type="submit"
                class="px-4 py-2 rounded bg-primary text-white hover:bg-primary-hover transition">
                Enviar denuncia
            </button>
        </div>
    </form>
</div>
@endsection
