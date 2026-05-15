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
            {{-- Header de la tabla (solo desktop) --}}
            <div class="hidden md:grid grid-cols-12 gap-4 px-5 py-3 text-xs font-bold uppercase tracking-wider text-text-muted border-b border-border">
                <div class="col-span-4">Tienda</div>
                <div class="col-span-3">Plataforma / Región</div>
                <div class="col-span-2 text-center">Precio</div>
                <div class="col-span-3 text-right">Acción</div>
            </div>

            {{-- Filas --}}
            @forelse($proAds as $ad)
                <div class="flex flex-wrap items-center gap-3 px-4 py-4 border-b border-border hover:bg-white/5 transition md:grid md:grid-cols-12 md:gap-4 md:px-5">
                    {{-- Tienda --}}
                    <div class="w-full md:col-span-4 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm text-white bg-primary shrink-0">
                            {{ strtoupper(substr($ad->user->name, 0, 2)) }}
                        </div>
                        <div class="flex items-center gap-1 min-w-0">
                            <a href="{{ route('users.show', $ad->user) }}" class="font-semibold text-text-main text-sm hover:text-[#3bb1a5] transition-colors truncate">{{ $ad->user->name }}</a>
                            @if($ad->user->isProfessional())
                                <span title="Socio Verificado" class="text-blue-400 text-xs font-bold shrink-0">✔</span>
                            @endif
                        </div>
                        {{-- Precio visible solo en móvil --}}
                        <span class="ml-auto font-bold text-text-main md:hidden">€{{ number_format($ad->price, 2) }}</span>
                    </div>

                    {{-- Plataforma (solo desktop) --}}
                    <div class="hidden md:block md:col-span-3">
                        <p class="text-sm text-text-main font-medium">
                            @if($ad->platforms && count($ad->platforms) > 0)
                                {{ implode(' / ', $ad->platforms) }}
                            @else
                                —
                            @endif
                        </p>
                        <p class="text-xs text-text-muted">Global</p>
                    </div>

                    {{-- Precio (solo desktop) --}}
                    <div class="hidden md:block md:col-span-2 text-center">
                        <span class="text-text-main font-bold text-lg">€{{ number_format($ad->price, 2) }}</span>
                    </div>

                    {{-- Acción --}}
                    <div class="w-full md:col-span-3 flex items-center gap-2 md:justify-end flex-wrap">
                        @auth
                            @if(auth()->id() !== $ad->user_id)
                                <button
                                    onclick="openReviewModal({{ $ad->id }}, '{{ addslashes($ad->game->title) }}', '{{ addslashes($ad->user->name) }}', '{{ $ad->user->avatar_url }}')"
                                    class="hidden md:inline-flex px-5 py-2 rounded-lg text-sm font-semibold transition border border-[#009194] text-[#3bb1a5] hover:bg-[#009194]/10">
                                    ★ Valorar
                                </button>
                                <a href="{{ route('reports.create', $ad) }}"
                                   class="hidden md:inline-flex px-3 py-2 rounded-lg text-text-muted text-xs font-semibold border border-border hover:text-text-main transition">
                                    Reportar
                                </a>
                            @endif
                        @endauth

                        <form action="{{ route('cart.add') }}" method="POST" class="w-full md:w-auto">
                            @csrf
                            <input type="hidden" name="game_ad_id" value="{{ $ad->id }}">
                            <button type="submit"
                                class="w-full md:w-auto px-5 py-2 rounded-lg text-white text-sm font-semibold transition bg-primary hover:bg-primary-hover shadow-lg shadow-primary/20">
                                Añadir al carrito
                            </button>
                        </form>
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
            {{-- Header de la tabla (solo desktop) --}}
            <div class="hidden md:grid grid-cols-12 gap-4 px-5 py-3 text-xs font-bold uppercase tracking-wider text-text-muted border-b border-border">
                <div class="col-span-4">Vendedor</div>
                <div class="col-span-2">Plataforma</div>
                <div class="col-span-2">Estado</div>
                <div class="col-span-1 text-center">Precio</div>
                <div class="col-span-3 text-right">Acción</div>
            </div>

            {{-- Filas --}}
            @forelse($userAds as $ad)
                <div class="flex flex-wrap items-center gap-3 px-4 py-4 border-b border-border hover:bg-white/5 transition md:grid md:grid-cols-12 md:gap-4 md:px-5">
                    {{-- Vendedor --}}
                    <div class="w-full md:col-span-4 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center overflow-hidden border-2 border-border shrink-0">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($ad->user->name) }}&background=random&size=40"
                                alt="{{ $ad->user->name }}" class="w-full h-full object-cover">
                        </div>
                        <div class="min-w-0">
                            <a href="{{ route('users.show', $ad->user) }}" class="font-semibold text-text-main text-sm hover:text-[#3bb1a5] transition-colors truncate block">{{ $ad->user->name }}</a>
                            <p class="text-xs text-accent">
                                ★ {{ number_format(rand(40, 50) / 10, 1) }} ({{ rand(5, 30) }} ventas)
                            </p>
                        </div>
                        {{-- Precio + estado visible solo en móvil --}}
                        <div class="ml-auto text-right md:hidden">
                            <span class="font-bold text-text-main block">€{{ number_format($ad->price, 2) }}</span>
                            @if($ad->condition === 'NEW')
                                <span class="text-xs font-bold text-accent">Como nuevo</span>
                            @else
                                <span class="text-xs font-bold text-yellow-400">Bueno</span>
                            @endif
                        </div>
                    </div>

                    {{-- Plataforma (solo desktop) --}}
                    <div class="hidden md:block md:col-span-2">
                        <p class="text-sm text-text-main font-medium">
                            @if($ad->platforms && count($ad->platforms) > 0)
                                {{ implode(' / ', $ad->platforms) }}
                            @else
                                —
                            @endif
                        </p>
                    </div>

                    {{-- Estado (solo desktop) --}}
                    <div class="hidden md:block md:col-span-2">
                        @if($ad->condition === 'NEW')
                            <span class="text-xs font-bold px-3 py-1 rounded-full text-accent">Como nuevo</span>
                        @else
                            <span class="text-xs font-bold px-3 py-1 rounded-full text-yellow-400">Bueno</span>
                        @endif
                    </div>

                    {{-- Precio (solo desktop) --}}
                    <div class="hidden md:block md:col-span-1 text-center">
                        <span class="text-text-main font-bold text-lg">€{{ number_format($ad->price, 2) }}</span>
                    </div>

                    {{-- Acción --}}
                    <div class="w-full md:col-span-3 flex items-center gap-2 md:justify-end flex-wrap">
                        @auth
                            @if(auth()->id() !== $ad->user_id)
                                <button
                                    onclick="openReviewModal({{ $ad->id }}, '{{ addslashes($ad->game->title) }}', '{{ addslashes($ad->user->name) }}', '{{ $ad->user->avatar_url }}')"
                                    class="hidden md:inline-flex px-5 py-2 rounded-lg text-sm font-semibold transition border border-[#009194] text-[#3bb1a5] hover:bg-[#009194]/10">
                                    ★ Valorar
                                </button>
                                <a href="{{ route('reports.create', $ad) }}"
                                   class="hidden md:inline-flex px-3 py-2 rounded-lg text-text-muted text-xs font-semibold border border-border hover:text-text-main transition">
                                    Reportar
                                </a>
                            @endif
                        @endauth

                        <form action="{{ route('cart.add') }}" method="POST" class="w-full md:w-auto">
                            @csrf
                            <input type="hidden" name="game_ad_id" value="{{ $ad->id }}">
                            <button type="submit"
                                class="w-full md:w-auto px-5 py-2 rounded-lg text-white text-sm font-semibold transition bg-primary hover:bg-primary-hover shadow-lg shadow-primary/20">
                                Añadir al carrito
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="px-5 py-8 text-center text-text-muted text-sm">
                    No hay ofertas de segunda mano disponibles.
                </div>
            @endforelse
        </div>
    </section>

    {{-- ===== MODAL DE RESEÑA ===== --}}
    @auth

    {{-- IDs de anuncios ya reseñados por el usuario actual --}}
    @php
        $reviewedAdIds = auth()->user()->reviews()->pluck('game_ad_id')->toArray();
    @endphp

    <div id="review-modal"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm"
        style="display: none !important;"
        onclick="if(event.target===this) closeReviewModal()">

        <div class="bg-[#0d1520] border border-border rounded-2xl w-full max-w-md mx-4 p-6 shadow-2xl">

            {{-- Header --}}
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-text-main font-bold text-lg flex items-center gap-2">
                    <span class="text-yellow-400">★</span> Valorar Vendedor/Producto
                </h3>
                <button onclick="closeReviewModal()" class="text-text-muted hover:text-text-main transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Estrellas --}}
            <div class="bg-surface border border-border rounded-xl p-5 mb-4 text-center">
                <p class="text-xs text-text-muted uppercase tracking-widest mb-3">Tu Calificación</p>
                <div class="flex justify-center gap-2 mb-2" id="star-container">
                    @for($i = 1; $i <= 5; $i++)
                        <button type="button"
                                onclick="setRating({{ $i }})"
                                onmouseover="hoverRating({{ $i }})"
                                onmouseout="resetHover()"
                                class="star-btn text-4xl transition-transform hover:scale-110"
                                data-value="{{ $i }}">
                            <span class="text-gray-600">★</span>
                        </button>
                    @endfor
                </div>
                <p class="text-xs text-text-muted">Toca las estrellas para calificar</p>
            </div>

            {{-- Info del anuncio --}}
            <div class="bg-surface border border-border rounded-xl px-4 py-3 mb-4 flex items-center gap-3">
                <img id="modal-avatar" src="" alt="" class="w-10 h-10 rounded-full object-cover border border-border">
                <div>
                    <p id="modal-game-title" class="text-text-main text-sm font-semibold"></p>
                    <p class="text-text-muted text-xs">Vendido por: <span id="modal-seller" class="text-[#3bb1a5]"></span></p>
                </div>
            </div>

            {{-- Formulario --}}
            <form id="review-form" action="{{ route('reviews.store') }}" method="POST" onsubmit="return handleReviewSubmit(event)">
                @csrf
                <input type="hidden" name="game_ad_id" id="modal-ad-id">
                <input type="hidden" name="rating" id="modal-rating" value="0">

                <div class="mb-4">
                    <label class="text-sm text-text-muted mb-2 block">Tu reseña</label>
                    <textarea name="comment"
                            id="modal-comment"
                            rows="4"
                            placeholder="Escribe tu reseña aquí..."
                            class="w-full bg-surface border border-border rounded-xl px-4 py-3 text-sm text-text-main placeholder-text-muted outline-none focus:border-primary transition-colors resize-none"
                            oninput="clearFormError()"></textarea>
                </div>

                {{-- Mensaje de error de validación --}}
                <div id="form-error"
                    class="hidden mb-4 flex items-start gap-2 bg-red-500/10 border border-red-500/30 rounded-xl px-4 py-3">
                    <span class="text-red-400 text-base mt-0.5 flex-shrink-0">⚠</span>
                    <p id="form-error-text" class="text-red-400 text-sm leading-snug"></p>
                </div>

                <button type="submit"
                        id="submit-btn"
                        class="w-full py-3 rounded-xl text-white font-bold text-sm transition bg-primary hover:bg-primary-hover flex items-center justify-center gap-2
                            disabled:opacity-50 disabled:cursor-not-allowed disabled:pointer-events-none">
                    Enviar Reseña
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                </button>

                <button type="button" onclick="closeReviewModal()"
                        class="w-full mt-3 text-sm text-text-muted hover:text-text-main transition-colors">
                    Quizás más tarde
                </button>
            </form>
        </div>
    </div>

    <script>
        let selectedRating = 0;
        const reviewedAdIds = @json($reviewedAdIds);

        function openReviewModal(adId, gameTitle, sellerName, avatarUrl) {
            document.getElementById('modal-ad-id').value = adId;
            document.getElementById('modal-game-title').textContent = gameTitle;
            document.getElementById('modal-seller').textContent = sellerName;
            document.getElementById('modal-avatar').src = avatarUrl;
            selectedRating = 0;
            updateStars(0);
            document.getElementById('modal-rating').value = 0;
            clearFormError();

            // Si ya existe reseña para este anuncio, bloqueamos el formulario
            if (reviewedAdIds.includes(adId)) {
                showFormError('Ya has enviado una reseña para esta oferta. Solo se permite una valoración por oferta.');
                lockSubmitButton();
            } else {
                unlockSubmitButton();
            }

            document.getElementById('review-modal').style.removeProperty('display');
            document.body.style.overflow = 'hidden';
        }

        function closeReviewModal() {
            document.getElementById('review-modal').style.display = 'none';
            document.body.style.overflow = '';
        }

        function handleReviewSubmit(event) {
            clearFormError();

            if (selectedRating === 0) {
                event.preventDefault();
                showFormError('Selecciona al menos una estrella antes de enviar tu reseña.');
                return false;
            }

            const comment = document.getElementById('modal-comment').value.trim();
            if (comment.length === 0) {
                event.preventDefault();
                showFormError('El comentario no puede estar vacío. Cuéntanos tu experiencia.');
                return false;
            }

            return true;
        }

        function showFormError(message) {
            const errorBox = document.getElementById('form-error');
            document.getElementById('form-error-text').textContent = message;
            errorBox.classList.remove('hidden');
            errorBox.classList.add('flex');
        }

        function clearFormError() {
            const errorBox = document.getElementById('form-error');
            errorBox.classList.add('hidden');
            errorBox.classList.remove('flex');
            document.getElementById('form-error-text').textContent = '';
        }

        function lockSubmitButton() {
            const btn = document.getElementById('submit-btn');
            btn.disabled = true;
            btn.setAttribute('disabled', 'disabled');
        }

        function unlockSubmitButton() {
            const btn = document.getElementById('submit-btn');
            btn.disabled = false;
            btn.removeAttribute('disabled');
        }

        function setRating(value) {
            selectedRating = value;
            document.getElementById('modal-rating').value = value;
            updateStars(value);
            clearFormError();
        }

        function hoverRating(value) { updateStars(value); }
        function resetHover() { updateStars(selectedRating); }

        function updateStars(value) {
            document.querySelectorAll('.star-btn').forEach(btn => {
                const star = btn.querySelector('span');
                star.style.color = btn.dataset.value <= value ? '#facc15' : '#4b5563';
            });
        }

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeReviewModal();
        });
    </script>
    @endauth
@endsection
