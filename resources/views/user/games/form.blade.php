@extends('user.layout')

@section('title', $isEdit ? 'Editar Anuncio' : 'Crear Anuncio')

@section('content')
<h1 class="text-2xl font-bold mb-6">{{ $isEdit ? 'Editar anuncio' : 'Crear anuncio' }}</h1>

@if ($errors->any())
    <div class="bg-red-500/10 border border-red-500/30 text-red-400 text-sm rounded-lg px-4 py-3 mb-6">
        {{ $errors->first() }}
    </div>
@endif

<div class="bg-surface border border-border rounded-xl p-6">
    <form method="POST" action="{{ $isEdit ? route('games.update', $ad->id) : route('games.store') }}" class="space-y-4">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        <div>
            <label class="block text-sm text-text-muted mb-1">Juego</label>
            <select name="game_id" class="w-full bg-background border border-border px-3 py-2 rounded-lg">
                @foreach($games as $game)
                    <option value="{{ $game->id }}" @selected(old('game_id', $ad->game_id) == $game->id)>{{ $game->title }}</option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm text-text-muted mb-1">Precio</label>
                <input type="number" step="0.01" min="0.01" name="price" value="{{ old('price', $ad->price) }}" class="w-full bg-background border border-border px-3 py-2 rounded-lg">
            </div>
            <div>
                <label class="block text-sm text-text-muted mb-1">Cantidad</label>
                <input type="number" min="1" name="quantity" value="{{ old('quantity', $ad->quantity ?? 1) }}" class="w-full bg-background border border-border px-3 py-2 rounded-lg">
            </div>
        </div>

        <div>
            <label class="block text-sm text-text-muted mb-1">Descripción</label>
            <textarea name="description" rows="4" class="w-full bg-background border border-border px-3 py-2 rounded-lg">{{ old('description', $ad->description) }}</textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm text-text-muted mb-1">Condición</label>
                <select name="condition" class="w-full bg-background border border-border px-3 py-2 rounded-lg">
                    @foreach(['NEW','USED','DIGITAL'] as $condition)
                        <option value="{{ $condition }}" @selected(old('condition', $ad->condition ?? 'NEW') === $condition)>{{ $condition }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm text-text-muted mb-1">Formato</label>
                <select name="format" class="w-full bg-background border border-border px-3 py-2 rounded-lg">
                    @foreach(['PHYSICAL','DIGITAL_KEY'] as $format)
                        <option value="{{ $format }}" @selected(old('format', $ad->format ?? 'PHYSICAL') === $format)>{{ $format }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm text-text-muted mb-1">Estado</label>
                <select name="status" class="w-full bg-background border border-border px-3 py-2 rounded-lg">
                    @foreach(['ACTIVE','SOLD','HIDDEN'] as $status)
                        <option value="{{ $status }}" @selected(old('status', $ad->status ?? 'ACTIVE') === $status)>{{ $status }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm text-text-muted mb-1">Clave digital (opcional)</label>
            <input type="text" name="digital_key" value="{{ old('digital_key', $ad->digital_key) }}" class="w-full bg-background border border-border px-3 py-2 rounded-lg">
        </div>

        <div class="flex gap-3">
            <button type="submit" class="bg-primary hover:bg-primary-hover text-white font-semibold rounded-lg px-4 py-2 text-sm transition-colors">
                {{ $isEdit ? 'Guardar cambios' : 'Crear anuncio' }}
            </button>
            <a href="{{ route('games.index') }}" class="bg-background border border-border text-text-main rounded-lg px-4 py-2 text-sm">Cancelar</a>
        </div>
    </form>
</div>
@endsection
