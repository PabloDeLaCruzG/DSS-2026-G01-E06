@extends('admin.layouts.layout')
@section('title', $game ? 'Editar Juego' : 'Nuevo Juego')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <a href="{{ route('admin.games.index') }}" class="inline-flex items-center gap-1.5 text-sm text-text-muted hover:text-text-main transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Volver al catálogo
    </a>

    {{-- Flash de errores --}}
    @if($errors->any())
        <div class="bg-red-500/10 border border-red-500/30 text-red-400 rounded-lg px-4 py-3 text-sm space-y-1">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST"
          action="{{ $game ? route('admin.games.update', $game) : route('admin.games.store') }}">
        @csrf
        @if($game) @method('PUT') @endif

        {{-- Info básica --}}
        <div class="bg-surface rounded-xl border border-border p-6 space-y-5">
            <h2 class="text-sm font-semibold text-text-main uppercase tracking-wide">Información básica</h2>

            <div>
                <label class="block text-xs font-medium text-text-muted mb-1.5">Título <span class="text-red-400">*</span></label>
                <input type="text" name="title" value="{{ old('title', $game?->title) }}" required
                       class="w-full bg-background border border-border rounded-lg px-4 py-2.5 text-sm text-text-main focus:outline-none focus:border-primary transition">
            </div>

            <div>
                <label class="block text-xs font-medium text-text-muted mb-1.5">Descripción</label>
                <textarea name="description" rows="4"
                          class="w-full bg-background border border-border rounded-lg px-4 py-2.5 text-sm text-text-main focus:outline-none focus:border-primary transition resize-none">{{ old('description', $game?->description) }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-text-muted mb-1.5">Año de lanzamiento</label>
                    <input type="number" name="year" value="{{ old('year', $game?->year) }}"
                           min="1950" max="{{ date('Y') + 2 }}" placeholder="{{ date('Y') }}"
                           class="w-full bg-background border border-border rounded-lg px-4 py-2.5 text-sm text-text-main focus:outline-none focus:border-primary transition">
                </div>
                <div>
                    <label class="block text-xs font-medium text-text-muted mb-1.5">Rating (0–10)</label>
                    <input type="number" name="rating" value="{{ old('rating', $game?->rating) }}"
                           min="0" max="10" step="0.1" placeholder="0.0"
                           class="w-full bg-background border border-border rounded-lg px-4 py-2.5 text-sm text-text-main focus:outline-none focus:border-primary transition">
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-text-muted mb-1.5">URL de portada</label>
                <input type="url" name="cover_image" value="{{ old('cover_image', $game?->cover_image) }}"
                       placeholder="https://..."
                       class="w-full bg-background border border-border rounded-lg px-4 py-2.5 text-sm text-text-main focus:outline-none focus:border-primary transition">
            </div>

            <div>
                <label class="block text-xs font-medium text-text-muted mb-1.5">URL de tráiler</label>
                <input type="url" name="trailer_url" value="{{ old('trailer_url', $game?->trailer_url) }}"
                       placeholder="https://youtube.com/..."
                       class="w-full bg-background border border-border rounded-lg px-4 py-2.5 text-sm text-text-main focus:outline-none focus:border-primary transition">
            </div>
        </div>

        {{-- Géneros --}}
        <div class="bg-surface rounded-xl border border-border p-6 space-y-4 mt-4">
            <h2 class="text-sm font-semibold text-text-main uppercase tracking-wide">Géneros</h2>
            <div class="flex flex-wrap gap-2">
                @foreach($genres as $genre)
                    @php $checked = in_array($genre, old('genres', $game?->genres ?? [])); @endphp
                    <label class="flex items-center gap-2 px-3 py-1.5 rounded-full border text-xs cursor-pointer transition
                                  {{ $checked ? 'bg-primary/10 border-primary/30 text-primary' : 'bg-background border-border text-text-muted hover:border-primary/30' }}">
                        <input type="checkbox" name="genres[]" value="{{ $genre }}"
                               class="hidden" {{ $checked ? 'checked' : '' }}>
                        {{ $genre }}
                    </label>
                @endforeach
            </div>
        </div>

        {{-- Plataformas --}}
        <div class="bg-surface rounded-xl border border-border p-6 space-y-4 mt-4">
            <h2 class="text-sm font-semibold text-text-main uppercase tracking-wide">Plataformas</h2>
            <div class="flex flex-wrap gap-2">
                @foreach($platforms as $platform)
                    @php $checked = in_array($platform, old('platforms', $game?->platforms ?? [])); @endphp
                    <label class="flex items-center gap-2 px-3 py-1.5 rounded-full border text-xs cursor-pointer transition
                                  {{ $checked ? 'bg-primary/10 border-primary/30 text-primary' : 'bg-background border-border text-text-muted hover:border-primary/30' }}">
                        <input type="checkbox" name="platforms[]" value="{{ $platform }}"
                               class="hidden" {{ $checked ? 'checked' : '' }}>
                        {{ $platform }}
                    </label>
                @endforeach
            </div>
        </div>

        {{-- Visibilidad --}}
        <div class="bg-surface rounded-xl border border-border p-6 mt-4">
            <h2 class="text-sm font-semibold text-text-main uppercase tracking-wide mb-4">Visibilidad</h2>
            <label class="flex items-center justify-between cursor-pointer">
                <div>
                    <p class="text-sm text-text-main font-medium">Publicado</p>
                    <p class="text-xs text-text-muted mt-0.5">Si está desactivado, el juego no aparece en el catálogo público.</p>
                </div>
                <div class="relative">
                    <input type="hidden" name="is_published" value="0">
                    <input type="checkbox" name="is_published" value="1" id="is_published"
                           class="sr-only peer"
                           {{ old('is_published', $game?->is_published ?? true) ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-border rounded-full peer peer-checked:bg-primary transition-colors"></div>
                    <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform peer-checked:translate-x-5"></div>
                </div>
            </label>
        </div>

        {{-- Acciones --}}
        <div class="flex items-center justify-end gap-3 mt-6">
            <a href="{{ route('admin.games.index') }}"
               class="px-4 py-2 rounded-lg bg-surface border border-border text-text-muted hover:text-text-main text-sm transition">
                Cancelar
            </a>
            <button type="submit"
                    class="px-5 py-2 rounded-lg bg-primary hover:bg-primary-hover text-white text-sm font-medium transition shadow-lg shadow-primary/20">
                {{ $game ? 'Guardar cambios' : 'Crear juego' }}
            </button>
        </div>
    </form>

</div>

{{-- Toggle visual para checkboxes de géneros/plataformas --}}
<script>
document.querySelectorAll('label:has(input[type="checkbox"].hidden)').forEach(label => {
    const checkbox = label.querySelector('input[type="checkbox"]');
    checkbox.addEventListener('change', () => {
        if (checkbox.checked) {
            label.classList.add('bg-primary/10', 'border-primary/30', 'text-primary');
            label.classList.remove('bg-background', 'border-border', 'text-text-muted', 'hover:border-primary/30');
        } else {
            label.classList.remove('bg-primary/10', 'border-primary/30', 'text-primary');
            label.classList.add('bg-background', 'border-border', 'text-text-muted', 'hover:border-primary/30');
        }
    });
    label.addEventListener('click', () => {
        checkbox.checked = !checkbox.checked;
        checkbox.dispatchEvent(new Event('change'));
    });
});
</script>
@endsection
