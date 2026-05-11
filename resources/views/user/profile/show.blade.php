@extends('user.layout')

@section('title', 'Perfil de ' . $user->name)

@section('content')

{{-- ===== HEADER DE PERFIL ===== --}}
<div class="bg-surface border border-border rounded-xl p-6 mb-6">
    <div class="flex items-start gap-6">

        {{-- Avatar --}}
        <div class="relative flex-shrink-0">
            <img src="{{ $user->avatar_url }}"
                 alt="Avatar"
                 class="w-24 h-24 rounded-xl object-cover border-2 border-primary">
            <span class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-surface"></span>
        </div>

        {{-- Info principal --}}
        <div class="flex-1">
            <div class="flex items-center gap-3 mb-1">
                <h1 class="text-2xl font-bold text-text-main">{{ $user->name }}</h1>
                @if($user->isProfessional())
                    <span class="text-xs font-bold px-3 py-1 rounded-full bg-[#009194]/20 text-[#3bb1a5] border border-[#009194]/30">
                        ✔ VERIFIED MERCHANT
                    </span>
                @endif
            </div>
            <p class="text-text-muted text-sm mb-4 max-w-md">{{ $user->bio ?? 'Este usuario no ha añadido una biografía.' }}</p>

            {{-- Stats --}}
            <div class="flex gap-3">
                <div class="bg-background border border-border rounded-lg px-4 py-2 text-center">
                    <p class="text-xs text-text-muted uppercase tracking-wide">Reputación</p>
                    <p class="font-bold text-sm mt-0.5 text-text-main">⭐ {{ number_format($user->reputation ?? 0, 1) }} <span class="text-text-muted font-normal">/5.0</span></p>
                </div>
                <div class="bg-background border border-border rounded-lg px-4 py-2 text-center">
                    <p class="text-xs text-text-muted uppercase tracking-wide"> Ofertas Activas</p>
                    <p class="font-bold text-sm mt-0.5 text-text-main">{{ $ads->count() }}</p>
                </div>
                <div class="bg-background border border-border rounded-lg px-4 py-2 text-center">
                    <p class="text-xs text-text-muted uppercase tracking-wide">Vel. Media</p>
                    <p class="font-bold text-sm mt-0.5 text-[#3bb1a5]">~15m</p>
                </div>
                <div class="bg-background border border-border rounded-lg px-4 py-2 text-center">
                    <p class="text-xs text-text-muted uppercase tracking-wide">Miembro desde</p>
                    <p class="font-bold text-sm mt-0.5 text-text-main">{{ $user->created_at->format('M Y') }}</p>
                </div>
            </div>
        </div>

        {{-- Botones + denunciar --}}
        <div class="flex flex-col gap-2 flex-shrink-0 w-44">
            <p class="text-red-400 text-xs text-right hover:underline cursor-pointer mb-2">⚠ Denunciar usuario</p>
            <button class="bg-[#009194] hover:bg-[#007a7c] text-white text-sm font-semibold px-4 py-2.5 rounded-lg transition-colors text-center">
                Send Message
            </button>
            <button class="bg-background border border-border hover:bg-surface text-text-main text-sm font-semibold px-4 py-2.5 rounded-lg transition-colors text-center">
                Follow Shop
            </button>
        </div>

    </div>
</div>

{{-- ===== ACTIVE LISTINGS ===== --}}
<section class="mb-10">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-text-main font-bold text-lg flex items-center gap-2">
            Active Listings
            <span class="text-xs text-[#3bb1a5] font-normal">{{ $ads->count() }} total</span>
        </h2>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @forelse($ads as $ad)
            <a href="{{ route('games.show', $ad->game->id) }}"
               class="bg-surface border border-border rounded-xl overflow-hidden hover:border-[#009194] transition-colors block group">

                {{-- Imagen --}}
                <div class="relative bg-gray-700 flex items-center justify-center" style="height: 160px;">
                    @if($ad->game->cover_image)
                        <img src="{{ $ad->game->cover_image }}"
                             alt="{{ $ad->game->title }}"
                             class="w-full h-full object-cover">
                    @else
                        <span class="text-4xl">🎮</span>
                    @endif

                    {{-- Badge plataforma --}}
                    @php
                        $platform = is_array($ad->game->platforms) ? $ad->game->platforms[0] : $ad->game->platforms;
                        $platformColors = ['PS5' => '#1a4fc4', 'XBOX' => '#107c10', 'SWITCH' => '#e4000f', 'PC' => '#111111', 'RETRO' => '#b45309'];
                        $badgeColor = $platformColors[$platform] ?? '#009194';
                    @endphp
                    @if($platform)
                        <span class="absolute top-2 left-2 text-xs text-white px-2 py-0.5 rounded font-medium"
                              style="background-color: {{ $badgeColor }}">
                            {{ $platform }}
                        </span>
                    @endif

                    {{-- Badge instant --}}
                    @if($ad->format === 'DIGITAL_KEY')
                        <span class="absolute bottom-2 right-2 text-xs bg-[#009194] text-white px-2 py-0.5 rounded font-medium">
                            INSTANT
                        </span>
                    @endif
                </div>

                {{-- Info --}}
                <div class="p-3">
                    <p class="text-text-muted text-xs mb-0.5 uppercase tracking-wide">{{ $ad->game->genre }}</p>
                    <h3 class="text-text-main text-sm font-semibold mb-2 truncate" title="{{ $ad->game->title }}">
                        {{ $ad->game->title }}
                    </h3>
                    <div class="flex items-center justify-between">
                        <span class="text-text-main font-bold text-sm">{{ number_format($ad->price, 2) }}€</span>
                        <button class="text-text-muted hover:text-[#009194] transition-colors"
                                onclick="event.stopPropagation(); event.preventDefault();">
                            🛒
                        </button>
                    </div>
                </div>

            </a>
        @empty
            <div class="col-span-4 text-center text-text-muted py-16">
                <p class="text-5xl mb-4">🎮</p>
                <p class="text-sm">Este usuario no tiene anuncios activos.</p>
            </div>
        @endforelse
    </div>
</section>

{{-- ===== REVIEWS ===== --}}
<section>
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-text-main font-bold text-lg flex items-center gap-2">
            Reviews & Ratings
            <span class="flex items-center gap-1 text-xs bg-[#009194]/20 text-[#3bb1a5] px-2 py-0.5 rounded-full">
                ⭐ 4.9 · 86 reviews
            </span>
        </h2>
        <a href="#" class="text-xs text-[#3bb1a5] hover:text-[#009194] transition-colors">View All Reviews →</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- Review placeholder --}}
        @forelse([] as $review)
            {{-- aquí irían las reviews reales --}}
        @empty
            @for($i = 0; $i < 4; $i++)
                <div class="bg-surface border border-border rounded-xl p-4">
                    <div class="flex items-start gap-3 mb-3">
                        <div class="w-10 h-10 rounded-full bg-gray-700 flex-shrink-0 flex items-center justify-center text-lg">👤</div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <p class="text-text-main text-sm font-semibold">Usuario{{ $i + 1 }}</p>
                                <span class="text-xs text-text-muted">Jan 01, 2025</span>
                            </div>
                            <div class="flex items-center gap-1 mt-0.5">
                                @for($s = 0; $s < 5; $s++)
                                    <span class="text-yellow-400 text-xs">★</span>
                                @endfor
                            </div>
                        </div>
                    </div>
                    <p class="text-text-muted text-sm">Excelente vendedor, muy rápido y de confianza.</p>
                </div>
            @endfor
        @endforelse
    </div>
</section>

@endsection