@extends('layouts.app')

@section('title', 'Detalle del Juego - GameLink')

@section('content')

    @if(session('success'))
        <div class="mb-6 bg-green-500/20 border border-green-500/30 text-green-400 px-4 py-3 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-500/20 border border-red-500/30 text-red-400 px-4 py-3 rounded-lg text-sm">
            {{ session('error') }}
        </div>
    @endif

    {{-- PORTADA + INFO --}}
    <section class="flex flex-col md:flex-row gap-8 mb-10 mt-4">

        {{-- Imagen de portada --}}
        <div class="w-full md:w-64 lg:w-72 flex-shrink-0">
            <div class="rounded-xl overflow-hidden border border-border shadow-2xl bg-surface">
                @if($game->cover_image)
                    <img src="{{ $game->cover_image }}" alt="{{ $game->title }}" class="w-full h-auto object-cover">
                @else
                    <div class="w-full h-72 flex items-center justify-center text-6xl bg-surface">🎮</div>
                @endif
            </div>
        </div>

        {{-- Info del juego --}}
        <div class="flex-1 flex flex-col justify-center">

            {{-- Badges --}}
            <div class="flex items-center gap-2 mb-3">
                <span class="text-xs font-bold uppercase tracking-wider px-3 py-1 rounded-full bg-primary text-white">NUEVO
                    LANZAMIENTO</span>
                <span
                    class="text-xs font-bold uppercase tracking-wider px-3 py-1 rounded-full bg-surface text-text-muted border border-border">
                    {{ $game->year }}
                </span>
            </div>

            {{-- Titulo --}}
            <h1 class="text-3xl lg:text-4xl font-extrabold text-text-main mb-3 leading-tight">
                {{ $game->title }}
            </h1>

            {{-- Generos --}}
            <div class="flex flex-wrap items-center gap-3 text-sm text-text-muted mb-5">
                @foreach($game->genres as $genre)
                    <span class="flex items-center gap-1">
                        @if($loop->index === 0) 🎮
                        @elseif($loop->index === 1) ⚔️
                        @else 🌍
                        @endif
                        {{ $genre }}
                    </span>
                @endforeach
            </div>

            {{-- Descripcion --}}
            <p class="text-text-muted text-sm leading-relaxed mb-6 max-w-2xl">
                {{ $game->description }}
            </p>

            {{-- Precio y CTA --}}
            <div class="flex items-center gap-5">
                <div>
                    <span class="text-sm text-text-muted">Desde</span>
                    <p class="text-3xl font-extrabold text-accent">€{{ number_format($game->getLowestPrice(), 2) }}</p>
                </div>
                <a href="#offers"
                    class="inline-flex items-center px-6 py-3 rounded-lg font-bold text-white text-sm shadow-lg transition bg-primary hover:bg-primary-hover">
                    Ver Todas las Ofertas
                </a>
            </div>
        </div>
    </section>

    {{-- TIENDAS RECOMENDADAS --}}
    <section id="offers" class="mb-10">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-text-main font-bold text-lg flex items-center gap-2">
                🔑 Tiendas Recomendadas (Clave Digital)
            </h2>
            <a href="#" class="text-sm font-semibold text-accent hover:underline">Ver todas</a>
        </div>

        <div class="rounded-xl border border-border overflow-hidden bg-surface">
            {{-- Header de la tabla --}}
            <div
                class="grid grid-cols-12 gap-4 px-5 py-3 text-xs font-bold uppercase tracking-wider text-text-muted border-b border-border">
                <div class="col-span-4">Tienda</div>
                <div class="col-span-3">Plataforma / Región</div>
                <div class="col-span-2 text-center">Precio</div>
                <div class="col-span-3 text-right">Acción</div>
            </div>

            {{-- Filas --}}
            @forelse($proAds as $ad)
                <div class="grid grid-cols-12 gap-4 px-5 py-4 items-center border-b border-border hover:bg-white/5 transition">
                    {{-- Tienda --}}
                    <div class="col-span-4 flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm text-white bg-primary">
                            {{ strtoupper(substr($ad->user->name, 0, 2)) }}
                        </div>
                        <div class="flex items-center gap-1">
                            <span class="font-semibold text-text-main text-sm">{{ $ad->user->name }}</span>
                            @if($ad->user->isProfessional())
                                <span title="Socio Verificado" class="text-blue-400 text-xs font-bold">✔</span>
                            @endif
                        </div>
                    </div>

                    {{-- Plataforma --}}
                    <div class="col-span-3">
                        <p class="text-sm text-text-main font-medium">
                            @if($ad->platforms && count($ad->platforms) > 0)
                                {{ implode(' / ', $ad->platforms) }}
                            @else
                                Steam / PC
                            @endif
                        </p>
                        <p class="text-xs text-text-muted">Global</p>
                    </div>

                    {{-- Precio --}}
                    <div class="col-span-2 text-center">
                        <span class="text-text-main font-bold text-lg">€{{ number_format($ad->price, 2) }}</span>
                    </div>

                    {{-- Acción --}}
                    <div class="col-span-3 text-right">
                        <div class="flex items-center justify-end gap-2">
                            @auth
                                <a href="{{ route('reports.create', $ad) }}"
                                   class="px-3 py-2 rounded-lg text-text-muted text-xs font-semibold border border-border hover:text-text-main transition">
                                    Reportar
                                </a>
                            @endauth
                            <form action="{{ route('cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="game_ad_id" value="{{ $ad->id }}">
                                <button type="submit"
                                    class="px-5 py-2 rounded-lg text-white text-sm font-semibold transition bg-primary hover:bg-primary-hover shadow-lg shadow-primary/20">
                                    Añadir al carrito
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-5 py-8 text-center text-text-muted text-sm">
                    No hay ofertas digitales disponibles.
                </div>
            @endforelse
        </div>
    </section>

    {{-- SEGUNDA MANO --}}
    <section class="mb-10">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-text-main font-bold text-lg flex items-center gap-2">
                📦 Segunda Mano (Físico)
            </h2>
            <a href="#" class="text-sm font-semibold text-accent hover:underline">Ver todos</a>
        </div>

        <div class="rounded-xl border border-border overflow-hidden bg-surface">
            {{-- Header de la tabla --}}
            <div
                class="grid grid-cols-12 gap-4 px-5 py-3 text-xs font-bold uppercase tracking-wider text-text-muted border-b border-border">
                <div class="col-span-4">Vendedor</div>
                <div class="col-span-3">Estado</div>
                <div class="col-span-2 text-center">Precio</div>
                <div class="col-span-3 text-right">Acción</div>
            </div>

            {{-- Filas --}}
            @forelse($userAds as $ad)
                <div class="grid grid-cols-12 gap-4 px-5 py-4 items-center border-b border-border hover:bg-white/5 transition">
                    {{-- Vendedor --}}
                    <div class="col-span-4 flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-full flex items-center justify-center overflow-hidden border-2 border-border">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($ad->user->name) }}&background=random&size=40"
                                alt="{{ $ad->user->name }}" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <span class="font-semibold text-text-main text-sm">{{ $ad->user->name }}</span>
                            <p class="text-xs text-accent">
                                ★ {{ number_format(rand(40, 50) / 10, 1) }} ({{ rand(5, 30) }} ventas)
                            </p>
                        </div>
                    </div>

                    {{-- Estado --}}
                    <div class="col-span-3">
                        @if($ad->condition === 'NEW')
                            <span class="text-xs font-bold px-3 py-1 rounded-full text-accent">Como nuevo</span>
                        @else
                            <span class="text-xs font-bold px-3 py-1 rounded-full text-yellow-400">Bueno</span>
                        @endif
                    </div>

                    {{-- Precio --}}
                    <div class="col-span-2 text-center">
                        <span class="text-text-main font-bold text-lg">€{{ number_format($ad->price, 2) }}</span>
                    </div>

                    {{-- Acción --}}
                    <div class="col-span-3 text-right">
                        <div class="flex items-center justify-end gap-2">
                            @auth
                                <a href="{{ route('reports.create', $ad) }}"
                                   class="px-3 py-2 rounded-lg text-text-muted text-xs font-semibold border border-border hover:text-text-main transition">
                                    Reportar
                                </a>
                            @endauth
                            <form action="{{ route('cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="game_ad_id" value="{{ $ad->id }}">
                                <button type="submit"
                                    class="px-5 py-2 rounded-lg text-white text-sm font-semibold transition bg-primary hover:bg-primary-hover shadow-lg shadow-primary/20">
                                    Añadir al carrito
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-5 py-8 text-center text-text-muted text-sm">
                    No hay ofertas de segunda mano disponibles.
                </div>
            @endforelse
        </div>
    </section>

@endsection
